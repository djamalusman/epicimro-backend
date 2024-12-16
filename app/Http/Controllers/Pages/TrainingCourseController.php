<?php

namespace App\Http\Controllers\pages;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Http\Requests\AnakPerusahaan;
use App\Http\Requests\TraningCourse;
use App\Http\Requests\ListItemDetail as RequestsListItemDetail;
use Carbon\Carbon;
use App\Models\TraningCourseModel;
use App\Models\TrainingCourseFilesModel;
use App\Models\TraningCourseDetailsModel;
use App\Models\ListItemDetail;
use App\Models\MasterTipeModel;
use App\Models\ListItemModel;
use App\Models\LogApp;
use App\Models\MenuModel;
use App\Models\SideListModel;
use App\Models\M_Category_TrainingCourseModel;
use App\Models\M_Jenis_Sertifikasi_TrainingCourseModel;
use App\Models\Dtc_Persyaratan_TrainingCourseModel;
use App\Models\dtc_File_TrainingCourseModel;
use App\Models\Dtc_Materi_TrainingCourseModel;
use App\Models\Dtc_Fasilitas_TrainingCourseModel;
use App\Models\M_ProvinsiModel;
use App\Models\M_type_TrainingCourseModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Intervention\Image\ImageManager;
use PDO;

class TrainingCourseController extends Controller
{

    public function generateNumber()
    {
        // Fetch the last number from the database
        $lastRecord = TraningCourseDetailsModel::whereDate('created_at', Carbon::today())
            ->orderBy('generatenumber', 'desc')
            ->first();

        // Determine the last number part, if it exists
        $lastNumber = $lastRecord ? $lastRecord->generatenumber : null;
        $lastNumberPart = $lastNumber ? intval(substr($lastNumber, 4, 3)) : 0;

        // Generate new number
        $newNumberPart = str_pad($lastNumberPart + 1, 3, '0', STR_PAD_LEFT);

        // Format the new number with the current date
        $datePart = Carbon::now()->format('dmy');

        return "TRC-{$newNumberPart}-{$datePart}";
    }


    public function traningCourse($id)
    {
        $data['menus'] = MenuModel::find(base64_decode($id));
        $data['title']      = 'Traning Kerja | Pages';
        $data['title_page'] = 'Pelatihan / Kursus | ' . $data['menus']->menu_name;
        $data['menu']       = MenuModel::all();


        return view('pages.traningcourse', $data);
    }

    public function getFilters() {
        $filters = DB::table('dtc_training_course_detail')
        ->leftJoin('m_category_training_course', 'm_category_training_course.id', '=', 'dtc_training_course_detail.id_m_category_training_course')
        ->leftJoin('m_jenis_sertifikasi_training_course', 'm_jenis_sertifikasi_training_course.id', '=', 'dtc_training_course_detail.id_m_jenis_sertifikasi_training_course')
        ->leftJoin('m_type_training_course', 'm_type_training_course.id', '=', 'dtc_training_course_detail.typeonlineoffile')
        ->select(
            'dtc_training_course_detail.traning_name',
            DB::raw('CASE
                        WHEN dtc_training_course_detail.status = 1 THEN "Publish"
                        WHEN dtc_training_course_detail.status = 2 THEN "Pending"
                        WHEN dtc_training_course_detail.status = 3 THEN "Non Publish"
                        WHEN dtc_training_course_detail.status = 4 THEN "Kadaluarsa"
                        ELSE "Unknown"
                    END as status_training'),
            'm_category_training_course.nama as category',
            'm_jenis_sertifikasi_training_course.nama as cetificate_type',
            'm_type_training_course.nama as typeonlineofline'
        ) ->distinct() ->get();


        return response()->json($filters);
    }

    public function getDataCourses(Request $request) {

        //dd($request->all());
        $query = DB::table('dtc_training_course_detail')
        ->leftjoin('m_category_training_course', 'm_category_training_course.id', '=', 'dtc_training_course_detail.id_m_category_training_course') // Bergabung dengan tabel tipe_master
        ->leftjoin('m_jenis_sertifikasi_training_course', 'm_jenis_sertifikasi_training_course.id', '=', 'dtc_training_course_detail.id_m_jenis_sertifikasi_training_course') // Bergabung dengan tabel ifg_master_tipe
        ->leftjoin('m_type_training_course', 'm_type_training_course.id', '=', 'dtc_training_course_detail.typeonlineoffile')
        ->select('dtc_training_course_detail.*', 'm_category_training_course.nama as category','m_jenis_sertifikasi_training_course.nama as cetificate_type','m_type_training_course.nama as typeonlineofline'); // Pilih kolom yang dibutuhkan

        // Menerapkan filter berdasarkan parameter yang tersedia
        if ($request->has('traning_name') && $request->traning_name != '') {
            $query->where('dtc_training_course_detail.traning_name', 'LIKE', '%' . $request->traning_name . '%');
        }

        if ($request->category != '') {
            $query->where('m_category_training_course.nama', 'LIKE', '%' . $request->category . '%');
        }

        if ($request->cetificate_type != '') {
            $query->where('m_jenis_sertifikasi_training_course.nama', 'LIKE', '%' . $request->cetificate_type . '%');
        }
        if ($request->status_training != '') {

            if ( $request->status_training  =='Publish') {
                $query->where('dtc_training_course_detail.status',1);
            }
            elseif ( $request->status_training  == 'Pending') {
                $query->where('dtc_training_course_detail.status',2);
            }
            elseif ( $request->status_training  == 'Non Publish') {
                $query->where('dtc_training_course_detail.status',3);
            }
            elseif ($request->status_training  =='Kadaluarsa') {
                $query->where('dtc_training_course_detail.status',0);
            }

        }

        if ($request->typeonlineofline != '') {
            $query->where('m_type_training_course.id',$request->typeonlineofline);
        }

        // Mengambil hasil query
        $courses = $query->get();
        // Mengembalikan data dalam format JSON
        return response()->json($courses);
    }


    public function ViewsStoretraningcourse($id)
    {
        $data['menus'] = MenuModel::find(15);
        $data['title']      = 'Traning Kerja | Pages';
        $data['title_page'] = 'Pelatihan / Kursus | ' . $data['menus']->menu_name;
        $data['content'] = base64_decode($id);
        $data['menu']       = MenuModel::all();

        $data['liscategory'] = M_Category_TrainingCourseModel::all();
        $data['listsertifikasi'] = M_Jenis_Sertifikasi_TrainingCourseModel::all();
        $data['listprovinsi'] = M_ProvinsiModel::all();
        $data['listtype'] = M_type_TrainingCourseModel::all();



        return view('pages.traningcourse_store', $data);
    }


    public function storeCourseEndpoint(Request $req)
    {


        try {

            //dd($req->all());
            $jadwalMulai = Carbon::createFromDate(
                $req->jadwal_mulai_tahun,
                $req->jadwal_mulai_bulan,
                $req->jadwal_mulai_tanggal
            )->toDateString();

            $jadwalSelesai = Carbon::createFromDate(
                $req->jadwal_selesai_tahun,
                $req->jadwal_selesai_bulan,
                $req->jadwal_selesai_tanggal
            )->toDateString();

            $idProvinsi = $req->provinsi === 'Pilih Provinsi' ? 0 : $req->provinsi;
            $type = $req->type === 'Pilih Type' ? 0 : $req->type;

            $listItem = new TraningCourseDetailsModel();

            $listItem->abouttraining                = $req->abouttraining;
            $listItem->abouttrainer                 = $req->abouttrainer;
            $listItem->aboutcareer                  = $req->aboutcareer;

            $listItem->company_name                 = $req->company_name;
            $listItem->traning_name                 = $req->nama_training;
            $listItem->id_m_category_training_course          = $req->category;
            $listItem->id_m_jenis_sertifikasi_training_course             = $req->jenis_sertifikasi;
            $listItem->training_duration            = $req->training_duration;
            $listItem->startdate                    = $jadwalMulai;
            $listItem->enddate                      = $jadwalSelesai;
            $listItem->typeonlineoffile             = $type;
            $listItem->registrationfee              = $req->registrationfee;
            $listItem->id_provinsi                  = $idProvinsi;
            $listItem->lokasi                       = $req->lokasi;
            $listItem->yotube                       = $req->yotube;
            $listItem->link_pendaftaran             = $req->link_pendaftaran;
            $listItem->status                       = $req->status;
            $listItem->generatenumber               = $this->generateNumber(); // Generate the number
            $listItem->insert_by                    = session()->get('id');
            $listItem->updated_by                   = session()->get('id');
            $listItem->updated_by_ip                = $req->ip();
            $listItem->save();

            //persyarata
            if (!is_null($req->persyaratan)) {
                foreach ($req->materi_training as $materi_training) {
                    $datapersyaratan = new Dtc_Persyaratan_TrainingCourseModel();
                    $datapersyaratan->id_training_course_dtl = $listItem->id;
                    $datapersyaratan->nama = $materi_training;
                    $datapersyaratan->insert_by = session()->get('id');
                    $datapersyaratan->updated_by = session()->get('id');
                    $datapersyaratan->updated_by_ip = $req->ip();
                    $datapersyaratan->save();
                }
            }



            //materi_training
            if (!is_null($req->materi_training)) {
                foreach ($req->materi_training as $materi_training) {
                    $datamateri_training = new Dtc_Materi_TrainingCourseModel();
                    $datamateri_training->id_training_course_dtl = $listItem->id;
                    $datamateri_training->nama = $materi_training;
                    $datamateri_training->insert_by = session()->get('id');
                    $datamateri_training->updated_by = session()->get('id');
                    $datamateri_training->updated_by_ip =$req->ip();
                    $datamateri_training->save();
                }
            }

            //fasilitas
            if (!is_null($req->fasilitas)) {
                foreach ($req->fasilitas as $fasilitas) {
                    $datafasilitas = new Dtc_Fasilitas_TrainingCourseModel();
                    $datafasilitas->id_training_course_dtl = $listItem->id;
                    $datafasilitas->nama = $fasilitas;
                    $datafasilitas->insert_by = session()->get('id');
                    $datafasilitas->updated_by = session()->get('id');
                    $datafasilitas->updated_by_ip = $req->ip();
                    $datafasilitas->save();
                }
            }

            // ini file
            if (!is_null($req->photo)) {
                for ($index = 0; $index < count($req->photo); $index++) {
                    $filePhoto = null;

                    if (isset($req->photo[$index])) {
                        $file = $req->file('photo')[$index];
                        $ext = $file->extension();
                        $filePhoto = uniqid() . '.' . $file->getClientOriginalExtension();


                            $manager = new ImageManager();
                            $img = $manager->make($file->getPathname());

                            if ($ext == 'png' || $ext == 'PNG') {
                                $filePhoto = uniqid() . '.webp';
                            }
                            $img->save(public_path('storage') . '/'  . $filePhoto, 80);

                            if (env('PLATFORM_NAME') !== 'windows') {
                                // SFTP
                                Storage::disk('sftp')->put('/' . $filePhoto, $img->encode());
                            } else {
                                Storage::disk('windows_uploads')->put('/' . $filePhoto, $img->encode());
                            }
                        }

                        $datapenulis = new dtc_File_TrainingCourseModel();
                        $datapenulis->id_training_course_dtl = $listItem->id;
                        $datapenulis->nama = $filePhoto;
                        $datapenulis->fileold = $file;
                        $datapenulis->insert_by = session()->get('id');
                        $datapenulis->updated_by = session()->get('id');
                        $datapenulis->updated_by_ip = $req->ip();
                        $datapenulis->save();

                }
            }
            $response = [
                'status' => 'success',
                'message' => 'Data berhasil disimpan'
            ];
        } catch (ModelNotFoundException $e) {
            $response = [
                'status' => 'failed',
                'message' => "Terjadi Kesalahan pada sistem : " . $e,
            ];
        }

        $log_app = new LogApp();
        $log_app->method = $req->method();
        $log_app->request = "Create Traning Course";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Traning';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function editTraningCourse($id)
    {
        //dd(base64_decode($id));
        $data['menus'] = MenuModel::find(15);
        $data['title']      = 'Traning Kerja | Pages';
        $data['title_page'] = 'Pelatihan / Kursus | ' . $data['menus']->menu_name;
        $data['content'] = base64_decode($id);
        $data['menu']       = MenuModel::all();

        $data['liscategory'] = M_Category_TrainingCourseModel::all();
        $data['listsertifikasi'] = M_Jenis_Sertifikasi_TrainingCourseModel::all();
        $data['listprovinsi'] = M_ProvinsiModel::all();
        $data['listtype'] = M_type_TrainingCourseModel::all();

        $data['listpersyaratan'] =  Dtc_Persyaratan_TrainingCourseModel::where('id_training_course_dtl',base64_decode($id))->get();
        $data['listmateri'] =  Dtc_Materi_TrainingCourseModel::where('id_training_course_dtl',base64_decode($id))->get();
        $data['listfasilitas']=  Dtc_Fasilitas_TrainingCourseModel::where('id_training_course_dtl',base64_decode($id))->get();
        $data['listfiles']=  dtc_File_TrainingCourseModel::where('id_training_course_dtl',base64_decode($id))->get();

        $data['databyid'] = DB::table('dtc_training_course_detail')
        ->join('m_category_training_course', 'm_category_training_course.id', '=', 'dtc_training_course_detail.id_m_category_training_course') // Bergabung dengan tabel tipe_master
        ->join('m_jenis_sertifikasi_training_course', 'm_jenis_sertifikasi_training_course.id', '=', 'dtc_training_course_detail.id_m_jenis_sertifikasi_training_course') // Bergabung dengan tabel ifg_master_tipe
        ->leftjoin('m_provinsi', 'm_provinsi.id', '=', 'dtc_training_course_detail.id_provinsi') // Bergabung dengan tabel ifg_master_tipe
        ->select('dtc_training_course_detail.*', 'm_category_training_course.nama as category','m_jenis_sertifikasi_training_course.nama as cetificate_type','m_provinsi.nama as namaprovinsi')
        ->where('dtc_training_course_detail.id',base64_decode($id))->first(); // Pilih kolom yang dibutuhkan
        //dd($data);
        $dt_list_item =  TraningCourseDetailsModel::where('id',base64_decode($id))->first();
        $data['startdate']  = Carbon::parse($dt_list_item->startdate)->format('Y-m-d');
        $data['enddate']  = Carbon::parse($dt_list_item->enddate)->format('Y-m-d');

        $data['iddtl']=base64_decode($id);



        return view('pages.traningcourse_edit', $data);
    }


    public function updateCourseEndpoint(Request $req)
    {

        try {
           //dd($req->all());
            $jadwalMulai = Carbon::createFromDate(
                $req->jadwal_mulai_tahun,
                $req->jadwal_mulai_bulan,
                $req->jadwal_mulai_tanggal
            )->toDateString();

            $jadwalSelesai = Carbon::createFromDate(
                $req->jadwal_selesai_tahun,
                $req->jadwal_selesai_bulan,
                $req->jadwal_selesai_tanggal
            )->toDateString();

            $idProvinsi = $req->provinsi === 'Pilih Provinsi' ? 0 : $req->provinsi;


            $listItem = TraningCourseDetailsModel::find($req->iddtl);
            $listItem->abouttraining                = $req->abouttraining;
            $listItem->abouttrainer                 = $req->abouttrainer;
            $listItem->aboutcareer                  = $req->aboutcareer;
            $listItem->company_name                 = $req->company_name;
            $listItem->traning_name                 = $req->nama_training;
            $listItem->id_m_category_training_course          = $req->category;
            $listItem->id_m_jenis_sertifikasi_training_course             = $req->jenis_sertifikasi;
            $listItem->training_duration            = $req->training_duration;
            $listItem->startdate                    = $jadwalMulai;
            $listItem->enddate                      = $jadwalSelesai;
            $listItem->registrationfee              = $req->registrationfee;
            $listItem->typeonlineoffile             = $req->type;
            $listItem->id_provinsi                  = $idProvinsi;
            $listItem->lokasi                       = $req->lokasi;
            $listItem->yotube                       = $req->yotube;
            $listItem->link_pendaftaran             = $req->link_pendaftaran;
            $listItem->status                       = $req->status;
            $listItem->insert_by                    = session()->get('id');
            $listItem->updated_by                   = session()->get('id');
            $listItem->updated_by_ip                = $req->ip();
            $listItem->save();

            Dtc_Persyaratan_TrainingCourseModel::where('id_training_course_dtl', $req->iddtl)->delete();

            //persyaratan
            if (!is_null($req->persyaratan)) {
                //Dtc_Persyaratan_TrainingCourseModel::where('id_training_course_dtl', $req->iddtl)->delete();
                //$query = DB::table('id_training_course_dtl')->whereIn('id', $req->iddtl)->delete();
                foreach ($req->persyaratan as $persyaratan) {

                        if ($persyaratan !=null || $persyaratan !='') {
                            $datapersyaratan = new Dtc_Persyaratan_TrainingCourseModel();
                            $datapersyaratan->id_training_course_dtl =  $req->iddtl;
                            $datapersyaratan->nama = $persyaratan;
                            $datapersyaratan->insert_by = session()->get('id');
                            $datapersyaratan->updated_by = session()->get('id');
                            $datapersyaratan->updated_by_ip = $req->ip();
                            $datapersyaratan->save();
                        }


                }
            }

             //persyaratan Db
             if (!is_null($req->persyaratanDb)) {
                //$query = DB::table('id_training_course_dtl')->whereIn('id', $req->iddtl)->delete();
                foreach ($req->persyaratanDb as $persyaratanDb) {
                    //dd($persyaratanDb);
                    $datapersyaratan = new Dtc_Persyaratan_TrainingCourseModel();
                    $datapersyaratan->id_training_course_dtl =  $req->iddtl;
                    $datapersyaratan->nama = $persyaratanDb;
                    $datapersyaratan->insert_by = session()->get('id');
                    $datapersyaratan->updated_by = session()->get('id');
                    $datapersyaratan->updated_by_ip = $req->ip();
                    $datapersyaratan->save();
                }
            }

            Dtc_Materi_TrainingCourseModel::where('id_training_course_dtl', $req->iddtl)->delete();

            // //materi_training
            if (!is_null($req->materi_training)) {

                    foreach ($req->materi_training as $materi_training) {
                        if ($materi_training !=null || $materi_training !='') {

                            $datamateri_training = new Dtc_Materi_TrainingCourseModel();
                            $datamateri_training->id_training_course_dtl =  $req->iddtl;
                            $datamateri_training->nama = $materi_training;
                            $datamateri_training->insert_by = session()->get('id');
                            $datamateri_training->updated_by = session()->get('id');
                            $datamateri_training->updated_by_ip =$req->ip();
                            $datamateri_training->save();
                        }
                    }
            }

            // //materi_training Db
            if (isset($req->materi_trainingDb)) {

                foreach ($req->materi_trainingDb as $materi_trainingDb) {
                    $datamateri_training = new Dtc_Materi_TrainingCourseModel();
                    $datamateri_training->id_training_course_dtl =  $req->iddtl;
                    $datamateri_training->nama = $materi_trainingDb;
                    $datamateri_training->insert_by = session()->get('id');
                    $datamateri_training->updated_by = session()->get('id');
                    $datamateri_training->updated_by_ip =$req->ip();
                    $datamateri_training->save();
                }
            }

            Dtc_Fasilitas_TrainingCourseModel::where('id_training_course_dtl', $req->iddtl)->delete();
            // //fasilitas
            if (isset($req->fasilitas)) {

                foreach ($req->fasilitas as $fasilitas) {
                    if ($fasilitas !=null || $fasilitas !='') {
                        $datafasilitas = new Dtc_Fasilitas_TrainingCourseModel();
                        $datafasilitas->id_training_course_dtl =  $req->iddtl;
                        $datafasilitas->nama = $fasilitas;
                        $datafasilitas->insert_by = session()->get('id');
                        $datafasilitas->updated_by = session()->get('id');
                        $datafasilitas->updated_by_ip = $req->ip();
                        $datafasilitas->save();
                    }
                }
            }

            //fasilitas Db
            if (isset($req->fasilitasDb)) {
                foreach ($req->fasilitasDb as $fasilitasDb) {
                    $datafasilitas = new Dtc_Fasilitas_TrainingCourseModel();
                    $datafasilitas->id_training_course_dtl =  $req->iddtl;
                    $datafasilitas->nama = $fasilitasDb;
                    $datafasilitas->insert_by = session()->get('id');
                    $datafasilitas->updated_by = session()->get('id');
                    $datafasilitas->updated_by_ip = $req->ip();
                    $datafasilitas->save();
                }
            }

            // ini file
            if (!is_null($req->photo)) {
                for ($index = 0; $index < count($req->photo); $index++) {
                    $filePhoto = null;

                    if (isset($req->photo[$index])) {
                        $file = $req->file('photo')[$index];
                        $ext = $file->extension();
                        $filePhoto = uniqid() . '.' . $file->getClientOriginalExtension();


                            $manager = new ImageManager();
                            $img = $manager->make($file->getPathname());

                            if ($ext == 'png' || $ext == 'PNG') {
                                $filePhoto = uniqid() . '.webp';
                            }
                            $img->save(public_path('storage') . '/'  . $filePhoto, 80);

                            if (env('PLATFORM_NAME') !== 'windows') {
                                // SFTP
                                Storage::disk('sftp')->put('/' . $filePhoto, $img->encode());
                            } else {
                                Storage::disk('windows_uploads')->put('/' . $filePhoto, $img->encode());
                            }
                        }
                        dtc_File_TrainingCourseModel::where('id_training_course_dtl', $req->iddtl)->delete();
                        $datapenulis = new dtc_File_TrainingCourseModel();
                        $datapenulis->id_training_course_dtl =  $req->iddtl;
                        $datapenulis->nama = $filePhoto;
                        $datapenulis->fileold = $file;
                        $datapenulis->insert_by = session()->get('id');
                        $datapenulis->updated_by = session()->get('id');
                        $datapenulis->updated_by_ip = $req->ip();
                        $datapenulis->save();

                }
            }

            $response = [
                'status' => 'success',
                'message' => 'Data berhasil disimpan'
            ];
        } catch (ModelNotFoundException $e) {
            $response = [
                'status' => 'failed',
                'message' => "Terjadi Kesalahan pada sistem : " . $e,
            ];
        }
        $status = $req->status;

        $statusText = ($status == 1) ? 'publish' :
              (($status == 2) ? 'pending' :
              (($status == 3) ? 'preview' : 'unknown'));

        $log_app = new LogApp();
        $log_app->method = $req->method();
        $log_app->request = "Create Traning Course '{$statusText}'";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Traning';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function removePersyaratanEndpoint ($id)
    {
        Dtc_Persyaratan_TrainingCourseModel::where('id', $id)->delete();

        $response = [
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ];
        return json_encode($response);
    }

    public function removeMateriTrainingEndpoint ($id)
    {
        Dtc_Materi_TrainingCourseModel::where('id', $id)->delete();

        $response = [
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ];
        return json_encode($response);
    }

    public function removeFasilitasEndpoint ($id)
    {
        Dtc_Fasilitas_TrainingCourseModel::where('id', $id)->delete();

        $response = [
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ];
        return json_encode($response);
    }

    public function removePhotoEndpoint ($id)
    {

        dtc_File_TrainingCourseModel::where('id', $id)->delete();

        $response = [
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ];
        return json_encode($response);
    }


    public function stopTrainingCourse ($id)
    {

        $listItem = TraningCourseDetailsModel::find($id);
            $listItem->status                       = 3;
            $listItem->insert_by                    = session()->get('id');
            $listItem->updated_by                   = session()->get('id');
            $listItem->save();
        $response = [
            'status' => 'success',
            'message' => 'Data berhasil diupdate'
        ];
        return json_encode($response);
    }

    public function copyTrainingCourseList($id)
    {
        try {
            $dt_list_item =  TraningCourseDetailsModel::find($id);
            $output = View::make("components.copy-training-course-modal")
                ->with("dt_item", $dt_list_item)
                ->with("route", route('update-copy-training-course'))
                ->with("formId", "copy-training-course-edit")
                ->with("formMethod", "PUT")
                ->render();

            $response = [
                'status' => 'success',
                'output'  => $output,
                'message' => 'Berhasil Parsing',
            ];
            return json_encode($response);
        } catch (ModelNotFoundException $e) {
            $response = [
                'status' => 'failed',
                'message' => "Terjadi Kesalahan pada sistem.",
            ];
        }
        return json_encode($response);
    }


    public function updateCopyTrainingCourseList(Request $req)
    {

        try {

            $listItem = TraningCourseDetailsModel::find($req->upt_id);
            $listItem->link_pendaftaran             = $req->link_pendaftaran;

            $listItem->save();
            $response = [
                'status' => 'success',
                'message' => 'Data berhasil disimpan'
            ];
        } catch (ModelNotFoundException $e) {
            $response = [
                'status' => 'failed',
                'message' => "Terjadi Kesalahan pada sistem : " . $e,
            ];
        }

        $log_app = new LogApp();
        $log_app->method = $req->method();
        $log_app->request =  "Update Traning Course";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Traning';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }


    public function removePTrainingCourse ($id)
    {
        TraningCourseDetailsModel::where('id', $id)->delete();
        Dtc_Persyaratan_TrainingCourseModel::where('id_training_course_dtl', $id)->delete();
        Dtc_Materi_TrainingCourseModel::where('id_training_course_dtl', $id)->delete();
        Dtc_Fasilitas_TrainingCourseModel::where('id_training_course_dtl', $id)->delete();
        dtc_File_TrainingCourseModel::where('id_training_course_dtl', $id)->delete();

        $response = [
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ];
        return json_encode($response);
    }

    public function editTraningCourseDetail($id)
    {
        try {
                $dt_list_item =  TraningCourseDetailsModel::find($id);
                $output = View::make("components.traning-course-component")
                    ->with("dt_item", $dt_list_item)
                    ->with("route", route('update-traning-course-detail'))
                    ->with("formId", "traning-course-edit")
                    ->with("formMethod", "PUT")
                    ->render();

                $response = [
                    'status' => 'success',
                    'output'  => $output,
                    'message' => 'Berhasil Parsing',
                ];
                return json_encode($response);
        } catch (Exception $e) {
            $response = [
                'status' => 'failed',
                'message' => "Terjadi Kesalahan pada sistem.",
            ];
        }
        return json_encode($response);
    }


    public function updateTraningCourseDetail(Request $req)
    {


        try {

            $listItem = TraningCourseDetailsModel::find($req->upt_id);
            $listItem->traning_name            = $req->traning_name;
            $listItem->traning_name_en         = $req->traning_name;
            $listItem->cetificate_type         = $req->cetificate_type;
            $listItem->cetificate_type_en      = $req->cetificate_type_en;
            $listItem->startdate     = $req->startdate;
            $listItem->enddate     = $req->enddate;
            $listItem->training_duration       = $req->training_duration;
            $listItem->requirements       = (new HelperController)->scriptStripper( $req->requirements ?? '-');
            $listItem->requirements_en    = (new HelperController)->scriptStripper( $req->requirements_en ?? '-');
            $listItem->training_material                = $req->training_material;
            $listItem->training_material_en             = $req->training_material_en;
            $listItem->facility                = $req->facility;
            $listItem->facility_en             = $req->facility_en;

            $listItem->insert_by = session()->get('id');
            $listItem->updated_by = session()->get('id');
            $listItem->updated_by_ip = $req->ip();
            if (!is_null($req->file('item_file'))) {
                $manager                = new ImageManager();
                $ext                    =  $req->file('item_file')->extension();
                $img                    = $manager->make($req->file('item_file')->getPathname());
                $listItem->file         = uniqid() . '.' . $req->file('item_file')->getClientOriginalExtension();

                if ($ext == 'png' || $ext == 'PNG') {
                    $listItem->file = uniqid() . '.' . 'webp';
                }

                $img->save(public_path('storage') . '/'  . $listItem->file, 80);

                if (env('PLATFORM_NAME') !== 'windows') {
                    //SFTP
                    Storage::disk('sftp')->put('/' . $listItem->file, $img);
                } else {
                    Storage::disk('windows_uploads')->put('/' . $listItem->file, $img);
                }
            }

            $listItem->save();
            $response = [
                'status' => 'success',
                'message' => 'Data berhasil disimpan'
            ];
        } catch (ModelNotFoundException $e) {
            $response = [
                'status' => 'failed',
                'message' => "Terjadi Kesalahan pada sistem : " . $e,
            ];
        }

        $log_app = new LogApp();
        $log_app->method = $req->method();
        $log_app->request =  "Update Traning Course";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Traning';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }
}

<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Http\Requests\ListItemDetail as RequestsListItemDetail;
use Carbon\Carbon;
use App\Models\LogApp;
use App\Models\MenuModel;
use App\Models\SertifikatModel;

use App\Imports\NamaModelImport;
use App\Imports\SertifikatImport;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Intervention\Image\ImageManager;
use PDO;
class SertifikatController extends Controller
{
    public function index($id)
    {
        $data['menus'] = MenuModel::find(base64_decode($id));
        $data['title']      = 'Traning Kerja | Home';
        $data['title_page'] = 'Sertifikat | ' . $data['menus']->menu_name;
        $data['menu']       = MenuModel::all();
        $data['sertifikat']     = SertifikatModel::all()->count();


        return view('pages.sertifikat', $data);
    }

    public function getDropdown() {
        $filters = DB::table('d_sertifikat')
            ->select('d_sertifikat.*')
            ->distinct()
            ->get();
        return response()->json($filters);
    }

    public function getDataSertifikat(Request $request) {
        // Membuat query untuk tabel training_course_detail

        $query = DB::table('d_sertifikat')
        ->select('d_sertifikat.*') ; // Pilih kolom yang dibutuhkan

        // Menerapkan filter berdasarkan parameter yang tersedia
        if ($request->has('nama_peserta') && $request->nama_peserta != '') {
            $query->where('d_sertifikat.nama_peserta', 'LIKE', '%' . $request->nama_peserta . '%');
        }
        if ($request->nama_training != '') {
            $query->where('d_sertifikat.nama_training', 'LIKE', '%' . $request->nama_training . '%');
        }
        if ($request->no_sertifikat != '') {
            $query->where('d_sertifikat.no_sertifikat', 'LIKE', '%' . $request->no_sertifikat . '%');
        }


        // Mengambil hasil query
        $courses = $query->get();
        // Mengembalikan data dalam format JSON
        return response()->json($courses);
    }

    public function getViewStoreSertifikat($id)
    {
        $data['menus'] = MenuModel::find(44);
        $data['title']      = 'Traning Kerja | Pages';
        $data['title_page'] = 'Sertifikat | ' . $data['menus']->menu_name;
        $data['content'] = base64_decode($id);
        $data['menu']       = MenuModel::all();

        // $data['liscategory'] = M_Category_TrainingCourseModel::all();
        // $data['listsertifikasi'] = M_Jenis_Sertifikasi_TrainingCourseModel::all();
        // $data['listprovinsi'] = M_ProvinsiModel::all();
        // $data['listtype'] = M_type_TrainingCourseModel::all();

        return view('pages.sertifikat_store', $data);
    }

    public function getViewExcelSertifikat($id)
    {
        $data['menus'] = MenuModel::find(44);
        $data['title']      = 'Traning Kerja | Pages';
        $data['title_page'] = 'Sertifikat | ' . $data['menus']->menu_name;
        $data['content'] = base64_decode($id);
        $data['menu']       = MenuModel::all();

        // $data['liscategory'] = M_Category_TrainingCourseModel::all();
        // $data['listsertifikasi'] = M_Jenis_Sertifikasi_TrainingCourseModel::all();
        // $data['listprovinsi'] = M_ProvinsiModel::all();
        // $data['listtype'] = M_type_TrainingCourseModel::all();

        return view('pages.sertifikat_store_excel', $data);
    }

    public function storeSertifikat(Request $req)
    {
        $tanggal_training = Carbon::createFromDate(
            $req->jadwal_mulai_tahun,
            $req->jadwal_mulai_bulan,
            $req->jadwal_mulai_tanggal
        )->toDateString();
        $nosertifikat=$req->no_urut_srt . "/" . $req->kode_category_training_srt . "/" . $req->kode_srt . "/" . $req->tahun_training_srt;
        $existingRecord = SertifikatModel::where('no_sertifikat', $nosertifikat)->first();

        if ($existingRecord == null  ) {

            try {
                $listItem = new SertifikatModel();
                $listItem->nama_peserta                 = $req->participants_name;
                $listItem->email                        = $req->email;
                $listItem->nama_training                = $req->nama_training;
                $listItem->tanggal_training             = $tanggal_training;
                $listItem->no_urut_srt                  = $req->no_urut_srt;
                $listItem->kode_category_training_srt   = $req->kode_category_training_srt;
                $listItem->kode_srt                     = $req->kode_srt;
                $listItem->tahun_training_srt           = $req->tahun_training_srt;

                $listItem->no_sertifikat                = $req->no_urut_srt . "/" . $req->kode_category_training_srt . "/" . $req->kode_srt . "/" . $req->tahun_training_srt;

                $listItem->status                       = $req->status;
                $listItem->insert_by                    = session()->get('id');
                $listItem->updated_by                   = session()->get('id');

                $listItem->save();

                $response = [
                    'status' => 'success',
                    'message' => 'Data berhasil disimpan'
                ];
            }
            catch (ModelNotFoundException $e) {
                $response = [
                    'status' => 'failed',
                    'message' => "Terjadi Kesalahan pada sistem : " . $e,
                ];
            }

        }
        else {
            $response = [
                'status' => 'failed',
                'message' => 'Nomor Sertifikat sudah ada'
            ];
        }

        //dd($response);
        // $log_app = new LogApp();
        // $log_app->method = $req->method();
        // $log_app->request = "Create Traning Course";
        // $log_app->response =  json_encode($response);
        // $log_app->pages = 'Traning';
        // $log_app->user_id = session()->get('id');
        // $log_app->ip_address = $req->ip();
        // $log_app->save();
        return json_encode($response);
    }


    public function editSertifikat($id)
    {
        //dd(base64_decode($id));
        $data['menus'] = MenuModel::find(44);
        $data['title']      = 'Traning Kerja | Pages';
        $data['title_page'] = 'Pelatihan / Kursus | ' . $data['menus']->menu_name;
        $data['content'] = base64_decode($id);
        $data['menu']       = MenuModel::all();


        //dd($data);
         $dt_list_item =  SertifikatModel::where('id',base64_decode($id))->first();
        $data['startdate']  = Carbon::parse($dt_list_item->tanggal_training)->format('Y-m-d');
        // $data['enddate']  = Carbon::parse($dt_list_item->enddate)->format('Y-m-d');
        $data['listitem']=$dt_list_item;
        $data['iddtl']=base64_decode($id);



        return view('pages.sertifikat_edit', $data);
    }

    public function updateSertifikat(Request $req)
    {
        $tanggal_training = Carbon::createFromDate(
            $req->jadwal_mulai_tahun,
            $req->jadwal_mulai_bulan,
            $req->jadwal_mulai_tanggal
        )->toDateString();
        $nosertifikat=$req->no_urut_srt . "/" . $req->kode_category_training_srt . "/" . $req->kode_srt . "/" . $req->tahun_training_srt;

        if ($nosertifikat == $req->nosertifikat) {
            # code...

            try {
                    $listItem = SertifikatModel::find($req->iddtl);
                    $listItem->nama_peserta                 = $req->participants_name;
                    $listItem->email                        = $req->email;
                    $listItem->nama_training                = $req->nama_training;
                    $listItem->tanggal_training             = $tanggal_training;
                    $listItem->no_urut_srt                  = $req->no_urut_srt;
                    $listItem->kode_category_training_srt   = $req->kode_category_training_srt;
                    $listItem->kode_srt                     = $req->kode_srt;
                    $listItem->tahun_training_srt           = $req->tahun_training_srt;

                    $listItem->no_sertifikat                = $req->no_urut_srt . "/" . $req->kode_category_training_srt . "/" . $req->kode_srt . "/" . $req->tahun_training_srt;

                    $listItem->status                       = $req->status;
                    $listItem->insert_by                    = session()->get('id');
                    $listItem->updated_by                   = session()->get('id');
                    $listItem->updated_by_ip                = $req->ip();
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
        }
        elseif ($nosertifikat != $req->nosertifikat) {

            $existingRecord = SertifikatModel::where('no_sertifikat', $nosertifikat)->first();
            if ($existingRecord ==null) {
                    try {
                        $listItem = SertifikatModel::find($req->iddtl);
                        $listItem->nama_peserta                 = $req->participants_name;
                        $listItem->email                        = $req->email;
                        $listItem->nama_training                = $req->nama_training;
                        $listItem->tanggal_training             = $tanggal_training;
                        $listItem->no_urut_srt                  = $req->no_urut_srt;
                        $listItem->kode_category_training_srt   = $req->kode_category_training_srt;
                        $listItem->kode_srt                     = $req->kode_srt;
                        $listItem->tahun_training_srt           = $req->tahun_training_srt;

                        $listItem->no_sertifikat                = $req->no_urut_srt . "/" . $req->kode_category_training_srt . "/" . $req->kode_srt . "/" . $req->tahun_training_srt;

                        $listItem->status                       = $req->status;
                        $listItem->insert_by                    = session()->get('id');
                        $listItem->updated_by                   = session()->get('id');
                        $listItem->updated_by_ip                = $req->ip();
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
            }
            else
            {
                $response = [
                    'status' => 'failed',
                    'message' => 'Nomor Sertifikat sudah ada'
                ];
            }
        }
        //dd($response);

        // $log_app = new LogApp();
        // $log_app->method = $req->method();
        // $log_app->request = "Create Traning Course";
        // $log_app->response =  json_encode($response);
        // $log_app->pages = 'Traning';
        // $log_app->user_id = session()->get('id');
        // $log_app->ip_address = $req->ip();
        // $log_app->save();
        return json_encode($response);
    }


    public function deleteSertifikat ($id)
    {

        SertifikatModel::where('id', $id)->delete();

        $response = [
            'status' => 'success',
            'message' => 'Data berhasil dihapus'
        ];
        return json_encode($response);
    }

    public function import(Request $req)
    {

        $req->validate([
                'import_file'=>[
                    'required',
                    'file'
                ],
            ]);
        // Inisialisasi import
        $import = new SertifikatImport;

        // Jalankan proses import
        Excel::import($import, $req->file('import_file'));

        // Dapatkan data duplikat dari model import
        $duplicateData = $import->getDuplicateData();


        // Redirect ke view dengan pesan sukses dan data duplikat
        return response()->json([
            'success' => true,
            'message' => 'Proses impor selesai!',
            'duplicateData' => $duplicateData,
        ]);
    }



}

<?php

namespace App\Http\Controllers\pages;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Http\Requests\AnakPerusahaan;
use App\Http\Requests\TraningCourse;
use App\Http\Requests\ListItemDetail as RequestsListItemDetail;
use App\Models\CooperationModel;
use Carbon\Carbon;
use App\Models\JobVacancyModel;
use App\Models\JobVacancyDetailModel;
use App\Models\JobVacancyFilesModel;

use App\Models\M_Employee_Status_JobVacancyeModel;
use App\Models\M_Education_JobVacancyeModel;
use App\Models\M_Eperience_Level_JobVacancyeModel;
use App\Models\M_Fee_JobVacancyeModel;
use App\Models\M_Saralry_JobVacancyeModel;
use App\Models\M_Sector_JobVacancyeModel;
use App\Models\M_WorkLocation_JobVacancyeModel;
use App\Models\ListItemDetail;
use App\Models\MasterTipeModel;
use App\Models\ListItemModel;
use App\Models\M_ProvinsiModel;
use App\Models\LogApp;
use App\Models\MenuModel;
use App\Models\SideListModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Intervention\Image\ImageManager;
use PDO;

class JobVacancyController extends Controller
{

    public function generateNumber()
    {
        // Fetch the last number from the database
        $lastRecord = JobVacancyDetailModel::whereDate('created_at', Carbon::today())
            ->orderBy('generatenumber', 'desc')
            ->first();

        // Determine the last number part, if it exists
        $lastNumber = $lastRecord ? $lastRecord->generatenumber : null;
        $lastNumberPart = $lastNumber ? intval(substr($lastNumber, 4, 3)) : 0;

        // Generate new number
        $newNumberPart = str_pad($lastNumberPart + 1, 3, '0', STR_PAD_LEFT);

        // Format the new number with the current date
        $datePart = Carbon::now()->format('dmy');

        return "JOV-{$newNumberPart}-{$datePart}";
    }

    public function jobVacancy($id)
    {
        $data['menus'] = MenuModel::find(base64_decode($id));
        $data['title']      = 'Job Vacancy | Pages';
        $data['title_page'] = 'Job Vacancy | ' . $data['menus']->menu_name;
        $data['menu']       = MenuModel::all();

        return view('pages.jobvacancy', $data);
    }

    public function getDropdownJob() {
        $filters = DB::table('djv_job_vacancy_detail')
        ->leftjoin('m_employee_status', 'djv_job_vacancy_detail.id_m_employee_status', '=', 'm_employee_status.id')
        ->leftjoin('m_work_location', 'm_work_location.id', '=', 'djv_job_vacancy_detail.id_m_work_location')
        ->leftjoin('m_salary_date_month', 'm_salary_date_month.id', '=', 'djv_job_vacancy_detail.id_m_salaray_date_mont')
        ->leftjoin('m_salary', 'm_salary.id', '=', 'djv_job_vacancy_detail.id_m_salaray')
        ->leftjoin('m_sector', 'm_sector.id', '=', 'djv_job_vacancy_detail.id_m_sector')
        ->leftjoin('m_education', 'm_education.id', '=', 'djv_job_vacancy_detail.id_m_education')
        ->leftjoin('m_experience_level', 'm_experience_level.id', '=', 'djv_job_vacancy_detail.id_m_experience_level')
        ->select(
            'djv_job_vacancy_detail.*',
            DB::raw('CASE
            WHEN djv_job_vacancy_detail.status = 1 THEN "Publish"
            WHEN djv_job_vacancy_detail.status = 2 THEN "Pending"
            WHEN djv_job_vacancy_detail.status = 3 THEN "Non Publish"
            WHEN djv_job_vacancy_detail.status = 0 THEN "Kadaluarsa"
            ELSE "Unknown"
            END as status'),
            'm_employee_status.nama as employees_status',
            'm_work_location.nama as m_work_location',
            'm_salary_date_month.nama as fee',
            'm_salary.nama as salary',
            'm_sector.nama as sector',
            'm_education.nama as m_education',
            'm_experience_level.nama as experience_level'
        )
        ->distinct()
        ->get();

        return response()->json($filters);
    }

    public function getDataJobFilter(Request $request) {
        // Membuat query untuk tabel training_course_detail
        //dd($request->all());
        $query = DB::table('djv_job_vacancy_detail')
        ->leftjoin('m_employee_status', 'djv_job_vacancy_detail.id_m_employee_status', '=', 'm_employee_status.id')
        ->leftjoin('m_work_location', 'm_work_location.id', '=', 'djv_job_vacancy_detail.id_m_work_location')
        ->leftjoin('m_salary_date_month', 'm_salary_date_month.id', '=', 'djv_job_vacancy_detail.id_m_salaray_date_mont')
        ->leftjoin('m_salary', 'm_salary.id', '=', 'djv_job_vacancy_detail.id_m_salaray')
        ->leftjoin('m_sector', 'm_sector.id', '=', 'djv_job_vacancy_detail.id_m_sector')
        ->leftjoin('m_education', 'm_education.id', '=', 'djv_job_vacancy_detail.id_m_education')
        ->leftjoin('m_experience_level', 'm_experience_level.id', '=', 'djv_job_vacancy_detail.id_m_experience_level')
        ->select(
            'djv_job_vacancy_detail.*',
            'm_employee_status.nama as employees_status',
            'm_work_location.nama as work_location',
            'm_salary_date_month.nama as fee',
            'm_salary.nama as salary',
            'm_sector.nama as sector',
            'm_education.nama as education',
            'm_experience_level.nama as experience_level'
        );

        // Menerapkan filter berdasarkan parameter yang tersedia
        if ($request->has('job_title') && $request->job_title != '') {
            $query->where('djv_job_vacancy_detail.job_title', 'LIKE', '%' . $request->job_title . '%');
        }
        if ($request->employees_status != '') {
            $query->where('m_employee_status.nama', 'LIKE', '%' . $request->employees_status . '%');
        }
        if ($request->work_location != '') {
            $query->where('m_work_location.nama', 'LIKE', '%' . $request->work_location . '%');
        }
        if ($request->fee != '') {
            $query->where('m_salary_date_month.nama', 'LIKE', '%' . $request->fee . '%');
        }
        if ($request->salary != '') {
            $query->where('m_salary.nama', 'LIKE', '%' . $request->salary . '%');
        }
        if ($request->sector != '') {
            $query->where('m_sector.nama', 'LIKE', '%' . $request->sector . '%');
        }
        if ($request->education != '') {
            $query->where('m_education.nama', 'LIKE', '%' . $request->education . '%');
        }
        if ($request->experience_level != '') {
            $query->where('m_experience_level.nama', 'LIKE', '%' . $request->experience_level . '%');
        }
        if ($request->status != '') {

            if ( $request->status_training  =='Publish') {
                $query->where('djv_job_vacancy_detail.status',1);
            }
            elseif ( $request->status_training  == 'Pending') {
                $query->where('djv_job_vacancy_detail.status',2);
            }
            elseif ( $request->status_training  == 'Non Publish') {
                $query->where('djv_job_vacancy_detail.status',3);
            }
            elseif ($request->status_training  =='Kadaluarsa') {
                $query->where('djv_job_vacancy_detail.status',0);
            }
        }

        // Mengambil hasil query
        $jobvacancy = $query->get();
        //dd($jobvacancy);
        // Mengembalikan data dalam format JSON
        return response()->json($jobvacancy);
    }

    public function getViewStoreJobvacancy($id)
    {
        $data['menus'] = MenuModel::find(base64_decode($id));
        $data['title']      = 'Job Vacancy | Pages';
        $data['title_page'] = 'Job Vacancy | ' . $data['menus']->menu_name;
        $data['menu']       = MenuModel::all();
        $data['liscategory'] = JobVacancyModel::all();
        $data['listprovinsi'] = M_ProvinsiModel::all();

        $data['listemployeestatus'] = M_Employee_Status_JobVacancyeModel::all();
        $data['listworklocation'] = M_WorkLocation_JobVacancyeModel::all();
        $data['listsalary'] = M_Saralry_JobVacancyeModel::all();
        $data['listfee'] = M_Fee_JobVacancyeModel::all();
        $data['listsector'] = M_Sector_JobVacancyeModel::all();
        $data['listeducation'] = M_Education_JobVacancyeModel::all();
        $data['listexperiencelevel'] = M_Eperience_Level_JobVacancyeModel::all();

        return view('pages.jobvacancy_store', $data);
    }

    public function storeJobVacancy(Request $req)
    {

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

            if (!is_null($req->file('photo'))) {
                $ext                    =  $req->file('photo')->extension();
                $filename               = uniqid() . '.' . $req->file('photo')->getClientOriginalExtension();

                    $manager                = new ImageManager();
                    $img                    = $manager->make($req->file('photo')->getPathname());
                    if ($ext == 'png' || $ext == 'PNG') {
                        $filename = uniqid() . '.' . 'webp';
                    }
                    $img->save(public_path('storage') . '/'  . $filename, 80);

                    if (env('PLATFORM_NAME') !== 'windows') {
                        //SFTP
                        Storage::disk('sftp')->put('/' . $filename, $img);
                    } else {
                        Storage::disk('windows_uploads')->put('/' . $filename, $img);
                    }
            }
        try {

            $listItem = new JobVacancyDetailModel();
            $listItem->companyName                = $req->companyName;
            $listItem->job_title                = $req->jobTitle;
            $listItem->id_m_employee_status     = $req->employmentStatus;
            $listItem->id_m_work_location       = $req->workLocation;
            $listItem->id_m_salaray_date_mont   = $req->salaryDateMonth;
            $listItem->id_m_salaray             = $req->estSalary;
            $listItem->id_m_sector              = $req->sector;
            $listItem->id_m_education           = $req->education;
            $listItem->id_m_experience_level    = $req->experienceLevel;
            $listItem->id_provinsi              = $idProvinsi;
            $listItem->lokasi                    = $req->lokasi;
            $listItem->posted_date              = $jadwalMulai;
            $listItem->close_date               = $jadwalSelesai;
            $listItem->sertifikasi              = $req->certification;
            $listItem->linkpendaftaran          = $req->link_pendaftaran;
            $listItem->generatenumber           = $this->generateNumber(); // Generate the number

            $listItem->status                   = $req->status;
            $listItem->file                      = $filename;
            $listItem->job_description      = (new HelperController)->scriptStripper( $req->jobdescripsi ?? '-');
            $listItem->skill_requirment      = (new HelperController)->scriptStripper( $req->skillRequirement ?? '-');

            $listItem->insert_by = session()->get('id');
            $listItem->updated_by = session()->get('id');
            $listItem->updated_by_ip = $req->ip();


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


    public function editJobVacancy($id)
    {
        $data['menus'] = MenuModel::find(34);
        $data['title']      = 'Lowongan Kerja';
        $data['title_page'] = 'Pelatihan / Kursus | ' . $data['menus']->menu_name;
        $data['content'] = base64_decode($id);
        $data['menu']       = MenuModel::all();
        $data['listprovinsi'] = M_ProvinsiModel::all();
        $data['listemployeestatus'] = M_Employee_Status_JobVacancyeModel::all();
        $data['listworklocation'] = M_WorkLocation_JobVacancyeModel::all();
        $data['listsalary'] = M_Saralry_JobVacancyeModel::all();
        $data['listfee'] = M_Fee_JobVacancyeModel::all();
        $data['listsector'] = M_Sector_JobVacancyeModel::all();
        $data['listeducation'] = M_Education_JobVacancyeModel::all();
        $data['listexperiencelevel'] = M_Eperience_Level_JobVacancyeModel::all();

        $data['databyid'] = DB::table('djv_job_vacancy_detail')
        ->leftjoin('m_employee_status', 'djv_job_vacancy_detail.id_m_employee_status', '=', 'm_employee_status.id')
        ->leftjoin('m_work_location', 'm_work_location.id', '=', 'djv_job_vacancy_detail.id_m_work_location')
        ->leftjoin('m_salary_date_month', 'm_salary_date_month.id', '=', 'djv_job_vacancy_detail.id_m_salaray_date_mont')
        ->leftjoin('m_salary', 'm_salary.id', '=', 'djv_job_vacancy_detail.id_m_salaray')
        ->leftjoin('m_sector', 'm_sector.id', '=', 'djv_job_vacancy_detail.id_m_sector')
        ->leftjoin('m_education', 'm_education.id', '=', 'djv_job_vacancy_detail.id_m_education')
        ->leftjoin('m_experience_level', 'm_experience_level.id', '=', 'djv_job_vacancy_detail.id_m_experience_level')
        ->select('djv_job_vacancy_detail.*')->where('djv_job_vacancy_detail.id',base64_decode($id))->first();
        $dt_list_item =  JobVacancyDetailModel::where('id',base64_decode($id))->first();

            $data['posted_date']  = Carbon::parse($dt_list_item->posted_date)->format('Y-m-d-');
            $data['close_date']  = Carbon::parse($dt_list_item->close_date)->format('Y-m-d');

        $data['iddtl']=base64_decode($id);

        return view('pages.jobvacancy_edit', $data);
    }


    public function updateJobVacancy(Request $req)
    {

        try {

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

            if (!is_null($req->file('photo'))) {
                $ext                    =  $req->file('photo')->extension();
                $filename               = uniqid() . '.' . $req->file('photo')->getClientOriginalExtension();

                $manager                = new ImageManager();
                $img                    = $manager->make($req->file('photo')->getPathname());
                if ($ext == 'png' || $ext == 'PNG') {
                    $filename = uniqid() . '.' . 'webp';
                }
                $img->save(public_path('storage') . '/'  . $filename, 80);

                if (env('PLATFORM_NAME') !== 'windows') {
                    //SFTP
                    Storage::disk('sftp')->put('/' . $filename, $img);
                } else {
                    Storage::disk('windows_uploads')->put('/' . $filename, $img);
                }
            }
            //dd($req->photo);
            $listItem = JobVacancyDetailModel::find($req->iddtl);
            $listItem->companyName                = $req->companyName;
            $listItem->id_provinsi              = $idProvinsi;
            $listItem->lokasi                    = $req->lokasi;
            $listItem->linkpendaftaran          = $req->link_pendaftaran;
            $listItem->job_title                = $req->jobTitle;
            $listItem->id_m_employee_status     = $req->employmentStatus;
            $listItem->id_m_work_location       = $req->workLocation;
            $listItem->id_m_salaray_date_mont   = $req->salaryDateMonth;
            $listItem->id_m_salaray             = $req->estSalary;
            $listItem->id_m_sector              = $req->sector;
            $listItem->id_m_education           = $req->education;
            $listItem->id_m_experience_level    = $req->experienceLevel;
            $listItem->posted_date              = $jadwalMulai;
            $listItem->close_date               = $jadwalSelesai;
            $listItem->sertifikasi              = $req->certification;
            if ($req->photo !=null) {
                $listItem->file                 = $filename;
            }

            $listItem->status                   = $req->status;
            $listItem->job_description      = (new HelperController)->scriptStripper( $req->jobdescripsi ?? '-');
            $listItem->skill_requirment      = (new HelperController)->scriptStripper( $req->skillRequirement ?? '-');

            $listItem->insert_by = session()->get('id');
            $listItem->updated_by = session()->get('id');
            $listItem->updated_by_ip = $req->ip();
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


    public function stopJobvacancy ($id)
    {

        $listItem = JobVacancyDetailModel::find($id);
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

    public function PopUpJobVacancyDetail($id)
    {

        try {
                $dt_list_item =  JobVacancyDetailModel::find($id);
                $output = View::make("components.job-vacancy-component")
                    ->with("dataPages", $dt_list_item)
                    ->with("route", route('update-job-vacancy-detail'))
                    ->with("formId", "job-vacancy-edit")
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


    public function deleteJobVacancyDetail($id, Request $req)
    {
        try {
            $dt_list_item =  JobVacancyDetailModel::destroy($id);

            if ($dt_list_item) {
                $response = [
                    'status' => 'success',
                    'message' => 'Data berhasil dihapus',
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Data gagal dihapus, Ulangi kembali',
                ];
            }
        } catch (Exception $e) {
            $response = [
                'status' => 'failed',
                'message' => "Terjadi Kesalahan pada sistem.",
            ];
        }

        $log_app = new LogApp();
        $log_app->method = $req->method();
        $log_app->request =  "Delete ProductServiceList";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Laporan Tahunan';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function cooperation($id)
    {
        $data['menus'] = MenuModel::find(base64_decode($id));
        $data['title']      = 'Cooperation | Pages';
        $data['title_page'] = 'Cooperation | ' . $data['menus']->menu_name;
        $data['menu']       = MenuModel::all();

        return view('pages.cooperation', $data);
    }

    public function getDataCooperationFilter(Request $request) {

        // $query = CooperationModel::all();
        $query = DB::table('d_cooperation')

            ->select(
                'd_cooperation.*'

            );
        if ($request->has('companyName') && $request->companyName != '') {
            $query->where('d_cooperation.nama', 'LIKE', '%' . $request->companyName . '%');
        }
        if ($request->has('status') && $request->status != '') {
            $query->where('d_cooperation.status', 'LIKE', '%' . $request->status . '%');
        }
        $data = $query->get();
        return response()->json($data);
    }

    public function getDropdownCooperation() {
        $filters = DB::table('d_cooperation')

            ->select(
                'd_cooperation.*',
                DB::raw('CASE
            WHEN d_cooperation.status = 1 THEN "Publish"
            WHEN d_cooperation.status = 2 THEN "Pending"
            WHEN d_cooperation.status = 3 THEN "Non Publish"
            WHEN d_cooperation.status = 0 THEN "Kadaluarsa"
            ELSE "Unknown"
            END as status')
            )
            ->distinct()
            ->get();

        return response()->json($filters);
    }

    public function getViewStoreCooperation($id)
    {
        $data['menus'] = MenuModel::find(base64_decode($id));
        $data['title']      = 'Cooperation | Pages';
        $data['title_page'] = 'Cooperation | ' . $data['menus']->menu_name;
        $data['menu']       = MenuModel::all();

        return view('pages.cooperation_store', $data);
    }

    public function storeCooperation(Request $req)
    {

        if (!is_null($req->file('photo'))) {
            $ext                    =  $req->file('photo')->extension();
            $filename               = uniqid() . '.' . $req->file('photo')->getClientOriginalExtension();

            $manager                = new ImageManager();
            $img                    = $manager->make($req->file('photo')->getPathname());
            if ($ext == 'png' || $ext == 'PNG') {
                $filename = uniqid() . '.' . 'webp';
            }
            $img->save(public_path('storage') . '/'  . $filename, 80);

            if (env('PLATFORM_NAME') !== 'windows') {
                //SFTP
                Storage::disk('sftp')->put('/' . $filename, $img);
            } else {
                Storage::disk('windows_uploads')->put('/' . $filename, $img);
            }
        }
        try {

            $listItem = new CooperationModel();
            $listItem->nama                = $req->companyName;
            $listItem->url                        = $req->urlcompanyName;
            $listItem->file                       = $filename;
            $listItem->status                     = $req->status;
            $listItem->insert_by = session()->get('id');
            $listItem->updated_by = session()->get('id');
            $listItem->updated_by_ip = $req->ip();
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


    public function editCooperation($id)
    {
        $data['menus'] = MenuModel::find(37);
        $data['title']      = 'Lowongan Kerja';
        $data['title_page'] = 'Pelatihan / Kursus | ' . $data['menus']->menu_name;
        $data['content'] = base64_decode($id);
        $data['menu']       = MenuModel::all();
        $data['databyid'] = CooperationModel::find(base64_decode($id));
        $data['iddtl']=$id;
        return view('pages.cooperation_edit', $data);
    }

    public function updateCooperation(Request $req)
    {
        try {

            if (!is_null($req->file('photo'))) {
                $ext = $req->file('photo')->extension();
                $filename = uniqid() . '.' . $req->file('photo')->getClientOriginalExtension();

                $manager = new ImageManager();
                $img = $manager->make($req->file('photo')->getPathname());
                if ($ext == 'png' || $ext == 'PNG') {
                    $filename = uniqid() . '.' . 'webp';
                }
                $img->save(public_path('storage') . '/' . $filename, 80);

                if (env('PLATFORM_NAME') !== 'windows') {
                    //SFTP
                    Storage::disk('sftp')->put('/' . $filename, $img);
                } else {
                    Storage::disk('windows_uploads')->put('/' . $filename, $img);
                }
            }

            $listItem = CooperationModel::find(base64_decode($req->iddtl));
            $listItem->nama = $req->companyName;
            $listItem->url = $req->urlcompanyName;
            if ($req->photo != null) {
                $listItem->file                       = $filename;
            } else
            {
            }

            $listItem->status                     = $req->status;
            $listItem->insert_by = session()->get('id');
            $listItem->updated_by = session()->get('id');
            $listItem->updated_by_ip = $req->ip();
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

    public function removeCooperation ($id)
    {

        CooperationModel::where('id', $id)->delete();

        $response = [
            'status' => 'success',
            'message' => 'Data berhasil dihapus'
        ];
        return json_encode($response);
    }

}

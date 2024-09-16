<?php

namespace App\Http\Controllers\pages;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Http\Requests\ListItemDetail as RequestsListItemDetail;
use Carbon\Carbon;
use App\Models\LogApp;
use App\Models\MenuModel;
use App\Models\SocialMediaModel;
use App\Models\TestimonialsDetailModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Intervention\Image\ImageManager;

use PDO;

class SocialMediaController extends Controller
{
    public function index($id)
    {
        $data['menus'] = MenuModel::find(base64_decode($id));
        $data['title']      = 'Social Media | Pages';
        $data['title_page'] = 'Pelatihan / Kursus | ' . $data['menus']->menu_name;
        $data['menu']       = MenuModel::all();
       //d_socialmedia
        return view('pages.socialmedia', $data);
    }

    public function getDropdownSm() {
        $filters = DB::table('d_socialmedia')
            ->select('d_socialmedia.nama', DB::raw('CASE
            WHEN d_socialmedia.status = 1 THEN "Publish"
            WHEN d_socialmedia.status = 2 THEN "Pending"
            WHEN d_socialmedia.status = 3 THEN "Non Publish"
            WHEN d_socialmedia.status = 4 THEN "Kadaluarsa"
            ELSE "Unknown"
        END as status_training'),'d_socialmedia.nama','d_socialmedia.url') 
            ->distinct()
            ->get();
        return response()->json($filters);
    }
    
    public function getDataSm(Request $request) {
        // Membuat query untuk tabel training_course_detail
        //dd($request);
        $query = DB::table('d_socialmedia')
        ->join('m_category_sosialmedia', 'm_category_sosialmedia.id', '=', 'd_socialmedia.id_category')
        ->select('d_socialmedia.*','m_category_sosialmedia.nama as nama');
        
        // Menerapkan filter berdasarkan parameter yang tersedia
        if ($request->has('title') && $request->title != '') {
            $query->where('m_category_sosialmedia.nama', 'LIKE', '%' . $request->title . '%');
        }
       
      
        // Mengambil hasil query
        $courses = $query->get();
        // Mengembalikan data dalam format JSON
        return response()->json($courses);
    }


    public function getViewStoreSm($id)
    {
        $data['menus'] = MenuModel::find(42);
        $data['title']      = 'Social Media';
        $data['title_page'] = 'Social Media | ' . $data['menus']->menu_name;
        $data['content'] = base64_decode($id);
        $data['menu']       = MenuModel::all();
        $data['liscategory'] =  DB::table('m_category_sosialmedia')
        ->select('m_category_sosialmedia.*')->get();
        return view('pages.socialmedia_store', $data);
    }

    public function storeSm(Request $req)
    {
        try {
            
            
                $listItem = new SocialMediaModel();
                $listItem->id_category      = $req->idcategory;
                $listItem->url              = $req->urlsm;
                $listItem->status           = $req->status;
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


    public function getVieweditSm($id)
    {
        $data['menus'] = MenuModel::find(42);
        $data['title']      = 'Social Media | Pages';
        $data['title_page'] = 'Social Media | ' . $data['menus']->menu_name;
        $data['content'] = base64_decode($id);
        $data['menu']       = MenuModel::all();
        
        $data['iddtl']=base64_decode($id);
        $data['datasm'] =  SocialMediaModel::where('id',base64_decode($id))->first();

        $data['liscategory'] =  DB::table('m_category_sosialmedia')
        ->select('m_category_sosialmedia.*')->get();

        return view('pages.socialmedia_edit', $data);
    }

    public function updateSm(Request $req)
    {
        try {
            
            
                $listItem = SocialMediaModel::find($req->iddtl);
                $listItem->id_category      = $req->idcategory;
                $listItem->url              = $req->urlsm;
                $listItem->status           = $req->status;
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

}

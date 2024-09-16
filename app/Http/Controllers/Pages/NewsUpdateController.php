<?php

namespace App\Http\Controllers\pages;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Http\Requests\AnakPerusahaan;
use App\Http\Requests\TraningCourse;
use App\Http\Requests\ListItemDetail as RequestsListItemDetail;
use Carbon\Carbon;

use App\Models\NewsTypeModel;
use App\Models\NewsUpdateDetailModel;
use App\Models\NewsUpdateModel;
use App\Models\NewsFilesModel;

use App\Models\ListItemDetail;
use App\Models\MasterTipeModel;
use App\Models\ListItemModel;
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
class NewsUpdateController extends Controller
{
    public function newsUpdate($id)
    {
        $data['menus'] = MenuModel::find(base64_decode($id));
        $data['title']      = 'News & Update | Pages';
        $data['title_page'] = 'News & Update | ' . $data['menus']->menu_name;
        $data['menu']       = MenuModel::all();
        $searchTerm = 'newsupdate';
        // $data['typeLaporan'] = MasterTipeModel::where('tipe_pages', 'like', '%' . $searchTerm . '%')->get();



        // $data['typejobvacancy'] =  DB::table('news')
        // ->leftjoin('ifg_master_tipe', 'ifg_master_tipe.id', '=', 'news.id_master_tipe')
        // ->select('ifg_master_tipe.tipe_name','news.id')->get();
        return view('pages.newsupdate', $data);
    }


    public function getDropdownNews() {
        $filters = DB::table('news_detail')
            ->join('m_news', 'm_news.id', '=', 'news_detail.id_m_news')
            ->select('news_detail.*', 'm_news.nama as category') 
            ->distinct()
            ->get();
        return response()->json($filters);
    }
    
    public function getDataNewsFilter(Request $request) {
        // Membuat query untuk tabel training_course_detail

        $query = DB::table('news_detail')
        ->join('m_news', 'm_news.id', '=', 'news_detail.id_m_news')
        ->select('news_detail.*', 'm_news.nama as category') ; // Pilih kolom yang dibutuhkan
        
        // Menerapkan filter berdasarkan parameter yang tersedia
        if ($request->has('title') && $request->title != '') {
            $query->where('news_detail.title', 'LIKE', '%' . $request->title . '%');
        }
        if ($request->category != '') {
            $query->where('m_news.nama', 'LIKE', '%' . $request->category . '%');
        }
    
    
      
        // Mengambil hasil query
        $courses = $query->get();
        // Mengembalikan data dalam format JSON
        return response()->json($courses);
    }


    public function getViewStoreNews($id)
    {
        $data['menus'] = MenuModel::find(35);
        $data['title']      = 'Lowongan Kerja';
        $data['title_page'] = 'Pelatihan / Kursus | ' . $data['menus']->menu_name;
        $data['content'] = base64_decode($id);
        $data['menu']       = MenuModel::all();

        $data['liscategory'] = NewsUpdateModel::all();
        
        return view('pages.newsupdate_store', $data);
    }


    public function storeNewsUpdate(Request $req)
    {
        
        try {
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
            $startdate = Carbon::createFromFormat('m/d/Y', $req->startdate)->format('Y-m-d');
            $listItem = new NewsUpdateDetailModel();
            $listItem->id_m_news = $req->category;
            $listItem->title              = $req->title;
            $listItem->implementation_date       = $startdate;
            $listItem->description       = (new HelperController)->scriptStripper( $req->requirements ?? '-');
            $listItem->status              = $req->status;
            $listItem->insert_by = session()->get('id');
            $listItem->updated_by = session()->get('id');
            $listItem->updated_by_ip = $req->ip();
            if ($req->photo !=null) {
                $listItem->file          = $filename;
            }
            $listItem->save();

            // if (!is_null($req->item_file)) {
            //     for ($index = 0; $index < count($req->item_file); $index++) {
            //         $filenamepenulis = null;
            
            //         if (isset($req->item_file[$index])) {
            //             $file = $req->file('item_file')[$index];
            //             $ext = $file->extension();
            //             $filenamepenulis = uniqid() . '.' . $file->getClientOriginalExtension();
            
                        
            //                 $manager = new ImageManager();
            //                 $img = $manager->make($file->getPathname());
            
            //                 if ($ext == 'png' || $ext == 'PNG') {
            //                     $filenamepenulis = uniqid() . '.webp';
            //                 }
            //                 $img->save(public_path('storage') . '/'  . $filenamepenulis, 80);
            
            //                 if (env('PLATFORM_NAME') !== 'windows') {
            //                     // SFTP
            //                     Storage::disk('sftp')->put('/' . $filenamepenulis, $img->encode());
            //                 } else {
            //                     Storage::disk('windows_uploads')->put('/' . $filenamepenulis, $img->encode());
            //                 }
            //             }
                       
            //             $datapenulis = new NewsFilesModel();
            //             $datapenulis->id_news_dtl = $listItem->id;
            //             $datapenulis->nama = $filenamepenulis;
            //             $datapenulis->insert_by = session()->get('id');
            //             $datapenulis->updated_by = session()->get('id');
            //             $datapenulis->updated_by_ip = $req->ip();
            //             $datapenulis->save();
                   
            //     }
            // }
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


    public function editNewsUpdate($id)
    {
        $data['menus'] = MenuModel::find(35);
        $data['title']      = 'Lowongan Kerja';
        $data['title_page'] = 'Pelatihan / Kursus | ' . $data['menus']->menu_name;
        $data['content'] = base64_decode($id);
        $data['menu']       = MenuModel::all();
       
        $data['liscategory'] = NewsUpdateModel::all();
        
        $data['databyid'] = DB::table('news_detail')
        ->join('m_news', 'm_news.id', '=', 'news_detail.id_m_news')
        ->select('news_detail.*', 'm_news.nama as category') // Pilih kolom yang dibutuhkan
            ->where('news_detail.id',base64_decode($id))->first();

        $data['getImage'] = DB::table('news_detail')
            ->join('m_news_file', 'm_news_file.id_news_dtl', '=', 'news_detail.id') // Bergabung dengan tabel tipe_master
            ->select('m_news_file.nama as namaImage' )
            ->where('m_news_file.id_news_dtl',base64_decode($id))->get();
        
        $dt_list_item =  NewsUpdateDetailModel::where('id',base64_decode($id))->first();
        $data['implementation_date']  = Carbon::parse($dt_list_item->implementation_date)->format('d/m/Y');
    
        $data['iddtl']=base64_decode($id);

        return view('pages.newsupdate_edit', $data);
    }

    public function updateNewsUpdate(Request $req)
    {
       
        try {
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
            $startdate = Carbon::createFromFormat('m/d/Y', $req->startdate)->format('Y-m-d');
            $listItem = NewsUpdateDetailModel::find($req->iddtl);
            $listItem->id_m_news = $req->category;
            $listItem->title              = $req->title;
            $listItem->implementation_date       = $startdate;
            $listItem->description       = (new HelperController)->scriptStripper( $req->requirements ?? '-');
            $listItem->status            = $req->status;
            if ($req->photo !=null) {
                $listItem->file          = $filename;
            }
            $listItem->insert_by = session()->get('id');
            $listItem->updated_by = session()->get('id');
            $listItem->updated_by_ip = $req->ip();
           
           

            $listItem->save();

            // if (!is_null($req->item_file)) {
            //     for ($index = 0; $index < count($req->item_file); $index++) {
            //         $filenamepenulis = null;
            
            //         if (isset($req->item_file[$index])) {
            //             $file = $req->file('item_file')[$index];
            //             $ext = $file->extension();
            //             $filenamepenulis = uniqid() . '.' . $file->getClientOriginalExtension();
            
                        
            //                 $manager = new ImageManager();
            //                 $img = $manager->make($file->getPathname());
            
            //                 if ($ext == 'png' || $ext == 'PNG') {
            //                     $filenamepenulis = uniqid() . '.webp';
            //                 }
            //                 $img->save(public_path('storage') . '/'  . $filenamepenulis, 80);
            
            //                 if (env('PLATFORM_NAME') !== 'windows') {
            //                     // SFTP
            //                     Storage::disk('sftp')->put('/' . $filenamepenulis, $img->encode());
            //                 } else {
            //                     Storage::disk('windows_uploads')->put('/' . $filenamepenulis, $img->encode());
            //                 }
            //             }
            //             $listItem = new NewsFilesModel() ;
            //             //dd($listItem);
            //             $listItem->id_news_dtl = $req->iddtl;
            //             $listItem->nama = $filenamepenulis;
            //             $listItem->insert_by = session()->get('id');
            //             $listItem->updated_by = session()->get('id');
            //             $listItem->updated_by_ip = $req->ip();
            //             $listItem->save();
                   
            //     }
            // }

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


    public function editNewsUpdateDetail($id)
    {
       

        try 
        {
            $dt_list_item =  NewsUpdateDetailModel::find($id);
          
            $jenisnews      =  DB::table('news')
            ->leftjoin('ifg_master_tipe', 'ifg_master_tipe.id', '=', 'news.id_master_tipe')
            ->leftjoin('news_type', 'news_type.id_master_tipe', '=', 'ifg_master_tipe.id')
            ->select('news_type.nama','news_type.id')
            ->where('news.id', $dt_list_item->id)->get();

                $dt_list_item =  NewsUpdateDetailModel::find($id);
                $output = View::make("components.news-update-component")
                    ->with("dataPages", $dt_list_item)
                    ->with("jenisnews", $jenisnews)
                    ->with("route", route('update-news-update-detail'))
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

  
}

<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Http\Requests\ListItemDetail as RequestsListItemDetail;
use Carbon\Carbon;
use App\Models\LogApp;
use App\Models\MenuModel;
use App\Models\BannerModel;
use App\Models\BannerDetailModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Intervention\Image\ImageManager;
use PDO;
class BannerController extends Controller
{
    public function index($id)
    {
        $data['menus'] = MenuModel::find(base64_decode($id));
        $data['title']      = 'Traning Kerja | Home';
        $data['title_page'] = 'Home | ' . $data['menus']->menu_name;
        $data['menu']       = MenuModel::all();
        $data['banner']     = BannerModel::all()->count();


        return view('pages.banner', $data);
    }

    public function getDropdownBanner() {
        $filters = DB::table('d_banner')
        ->leftJoin('ifg_menu', 'ifg_menu.id', '=', 'd_banner.id_menu')
        ->leftJoin('m_category_testimonials', 'm_category_testimonials.id', '=', 'd_banner.id_category')
            ->select('d_banner.nama', DB::raw('CASE
            WHEN d_banner.status = 1 THEN "Publish"
            WHEN d_banner.status = 2 THEN "Pending"
            WHEN d_banner.status = 3 THEN "Non Publish"
            WHEN d_banner.status = 4 THEN "Kadaluarsa"
            ELSE "Unknown"
        END as status_training'),'ifg_menu.menu_name','m_category_testimonials.nama as category')
        ->where('d_banner.id_category',33)
            ->distinct()
            ->get();

        return response()->json($filters);
    }

    public function getDataBanner(Request $request) {
        // Membuat query untuk tabel training_course_detail

        $query = DB::table('d_banner')
        ->join('ifg_menu', 'ifg_menu.id', '=', 'd_banner.id_menu')
        ->join('m_category_testimonials', 'm_category_testimonials.id', '=', 'd_banner.id_category')
        ->select('d_banner.*','ifg_menu.menu_name','m_category_testimonials.nama as category')
        ->where('d_banner.id_category',33); // Pilih kolom yang dibutuhkan

        // Menerapkan filter berdasarkan parameter yang tersedia
        if ($request->has('title') && $request->title != '') {
            $query->where('d_banner.nama', 'LIKE', '%' . $request->title . '%');
        }
        if ($request->has('menu') && $request->menu != '') {
            $query->where('ifg_menu.menu_name', 'LIKE', '%' . $request->menu . '%');
        }
        if ($request->has('category') && $request->category != '') {
            $query->where('m_category_testimonials.nama', 'LIKE', '%' . $request->category . '%');
        }



        // Mengambil hasil query
        $courses = $query->get();
        // Mengembalikan data dalam format JSON
        return response()->json($courses);
    }

    public function viewInmageBanner($id)
    {
        try {
                $dt_list_item =  BannerDetailModel::where('id_banner',$id)->get();

                $output = View::make("components.view-image-gallery")
                    ->with("dt_item", $dt_list_item)
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

    public function getViewStoreBanner($id)
    {
        $data['menus'] = MenuModel::find(1);
        $data['title']      = 'Training Kerja';
        $data['title_page'] = 'Home | ' . $data['menus']->menu_name;
        $data['content'] = base64_decode($id);
        $data['menu']       = MenuModel::all();
        $data['liscategory'] =  DB::table('m_category_testimonials')
        ->select('m_category_testimonials.*')->get();
        return view('pages.banner_store', $data);
    }

    public function storeBanner(Request $req)
    {
       // $this->generateNumber();
        //dd($req->photo);
        try {

            //dd($req->all());


                $listItem = new BannerModel();
                $listItem->nama                         = $req->nama_gallery;
                $listItem->id_category                  = $req->category;
                $listItem->id_menu                      = 1;
                $listItem->status                       = $req->status;
                $listItem->insert_by                    = session()->get('id');
                $listItem->updated_by                   = session()->get('id');
                $listItem->updated_by_ip                = $req->ip();
                $listItem->save();



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
                            $img->save(public_path('storage') . '/' . $filePhoto, 80);

                            if (env('PLATFORM_NAME') !== 'windows') {
                                // SFTP
                                Storage::disk('sftp')->put('/' . $filePhoto, $img->encode());
                            } else {
                                Storage::disk('windows_uploads')->put('/' . $filePhoto, $img->encode());
                            }

                            // Cek apakah file dengan nama yang sama sudah ada di database
                            $existingFile = BannerDetailModel::where('id_banner', $listItem->id)
                                ->where('fileold', $file->getClientOriginalName())
                                ->first();

                            if ($existingFile) {
                                // Jika file sudah ada, abaikan insert atau lakukan update jika diperlukan
                                continue; // Lewati iterasi ini jika sudah ada file yang sama
                            }

                            // Insert data baru ke dalam database
                            $datapenulis = new BannerDetailModel();
                            $datapenulis->id_banner = $listItem->id;
                            $datapenulis->file = $filePhoto;
                            $datapenulis->fileold = $file->getClientOriginalName(); // Simpan nama file asli
                            $datapenulis->status = $req->status;
                            $datapenulis->insert_by = session()->get('id');
                            $datapenulis->updated_by = session()->get('id');
                            $datapenulis->updated_by_ip = $req->ip();
                            $datapenulis->save();
                        }
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

    public function bannerEdit($id,$id_category)
    {
        $data['menus'] = MenuModel::find(1);
        $data['title']      = 'Traning Kerja | Pages';
        $data['title_page'] = 'Pelatihan / Kursus | ' . $data['menus']->menu_name;
        $data['content'] = base64_decode($id);
        $data['menu']       = MenuModel::all();
        $data['databyid']=  BannerModel::where('id',base64_decode($id))->first();
        $data['listmateri'] =  BannerDetailModel::where('id_banner',base64_decode($id))->get();
        $data['listfiles']=  BannerDetailModel::where('id_banner',base64_decode($id))->get();
        $data['liscategory'] =  DB::table('m_category_testimonials')->get();
        $data['iddtl']=base64_decode($id);
        if (base64_decode($id_category) == 33) {
            return view('pages.banner_edit', $data);
        }

    }

    public function bannerUpdate(Request $req)
    {

        try {



            $listItem =  BannerModel::find($req->iddtl);
            $listItem->nama                 = $req->nama;
            $listItem->id_menu              = 1;
            $listItem->status               = $req->status;
            $listItem->insert_by                    = session()->get('id');
            $listItem->updated_by                   = session()->get('id');
            $listItem->updated_by_ip                = $req->ip();
            $listItem->save();



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
                        $img->save(public_path('storage') . '/' . $filePhoto, 80);

                        if (env('PLATFORM_NAME') !== 'windows') {
                            // SFTP
                            Storage::disk('sftp')->put('/' . $filePhoto, $img->encode());
                        } else {
                            Storage::disk('windows_uploads')->put('/' . $filePhoto, $img->encode());
                        }

                        // Cek apakah file dengan nama yang sama sudah ada di database
                        $existingFile = BannerDetailModel::where('id_banner', $listItem->id)
                            ->where('fileold', $file->getClientOriginalName())
                            ->first();

                        if ($existingFile) {
                            // Jika file sudah ada, abaikan insert atau lakukan update jika diperlukan
                            continue; // Lewati iterasi ini jika sudah ada file yang sama
                        }

                        // Insert data baru ke dalam database
                        $datapenulis = new BannerDetailModel();
                        $datapenulis->id_banner = $listItem->id;
                        $datapenulis->file = $filePhoto;
                        $datapenulis->fileold = $file->getClientOriginalName(); // Simpan nama file asli
                        $datapenulis->status = $req->status;
                        $datapenulis->insert_by = session()->get('id');
                        $datapenulis->updated_by = session()->get('id');
                        $datapenulis->updated_by_ip = $req->ip();
                        $datapenulis->save();
                    }
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


    public function removePhotoBanner ($id)
    {

        BannerDetailModel::where('id', $id)->delete();

        $response = [
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ];
        return json_encode($response);
    }

    public function deleteDataBanner ($id)
    {

        BannerModel::where('id', $id)->delete();
        BannerDetailModel::where('id_banner', $id)->delete();

        $response = [
            'status' => 'success',
            'message' => 'Data berhasil dihapus'
        ];
        return json_encode($response);
    }
}

<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Http\Requests\KontakProdukDanLayanan;
use App\Http\Requests\KontenProdukDanLayanan;
use App\Http\Requests\ListItemDetail;
use App\Models\GalleryAnggotaHolding;
use App\Models\KontakModel;
use App\Models\ListItemDetail as ModelsListItemDetail;
use App\Models\ListItemModel;
use App\Models\LogApp;
use App\Models\MenuModel;
use App\Models\ProductAndServicesList;
use App\Models\SideListModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Intervention\Image\ImageManager;

class ProdukDanLayananController extends Controller
{
    public function index($id)
    {
        $data['menus'] = MenuModel::find(base64_decode($id));
        $data['title']      = 'IFG | Pages';
        $data['title_page'] = 'Produk dan Layanan | ' . $data['menus']->menu_name;
        $data['menu']       = MenuModel::all();
        $data['dataTk']  = SideListModel::where('id_menu', base64_decode($id))->get();
        $data['dataItem']  = DB::table('ifg_pages_content')
            ->leftjoin('ifg_pages_side_list', 'ifg_pages_side_list.id_pages_content', '=', 'ifg_pages_content.id')
            ->select('ifg_pages_content.id', 'ifg_pages_side_list.id as id_side', 'ifg_pages_content.item_file', 'ifg_pages_content.item_body', 'ifg_pages_content.item_body_en', 'ifg_pages_side_list.side_list', 'ifg_pages_side_list.side_list_en', 'ifg_pages_content.item_title', 'ifg_pages_content.item_title_en')
            ->where('ifg_pages_content.id_menu', base64_decode($id))
            ->where('ifg_pages_content.id_pages_content_order', '1')
            ->first();
        return view('pages.asuransi_umum_dan_penjaminan', $data);
    }

    public function editProdukDanLayanan($id)
    {
        $data['content'] = base64_decode($id);
        $data['menu']       = MenuModel::all();
        $data['dataPages']  = SideListModel::where('id', base64_decode($id))->first();
        $data['menus'] = MenuModel::find($data['dataPages']->id_menu);
        $data['title']      = 'IFG | Pages';
        $data['title_page'] = 'Produk dan Layanan | ' . $data['menus']->menu_name;
        $data['dataTk']  = ModelsListItemDetail::where('id_side_list', base64_decode($id))->first();
        $data['dataContact']  = KontakModel::where('id_content', base64_decode($id))->first();
        $data['dataProduct']  = ProductAndServicesList::where('id_content', base64_decode($id))->where('flag', '1')->get();
        $data['dataProductPs']  = ProductAndServicesList::where('id_content', base64_decode($id))->where('flag', '2')->first();
        $data['dataProductAs']  = ProductAndServicesList::where('id_content', base64_decode($id))->where('flag', '3')->first();
        $data['dataPicture']  = GalleryAnggotaHolding::where('id_content', base64_decode($id))->get();
        return view('pages.produk_dan_layanan_edit', $data);
    }

    public function storeKontenDetail(KontenProdukDanLayanan $req)
    {
        try {

            $dt_list_item =  ModelsListItemDetail::find(base64_decode($req->idSP));
            $filename = $dt_list_item->file ?? '';
            $filename_2 = $dt_list_item->file2 ?? '';


            if (!is_null($req->file('file'))) {

                $manager                = new ImageManager();
                $ext                    =  $req->file('file')->extension();
                $img                    = $manager->make($req->file('file')->getPathname());
                $filename         = uniqid() . '.' . $req->file('file')->getClientOriginalExtension();

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

            if (!is_null($req->file('file2'))) {

                $manager                = new ImageManager();
                $ext                    =  $req->file('file2')->extension();
                $img                    = $manager->make($req->file('file2')->getPathname());
                $filename_2         = uniqid() . '.' . $req->file('file2')->getClientOriginalExtension();

                if ($ext == 'png' || $ext == 'PNG') {
                    $filename_2 = uniqid() . '.' . 'webp';
                }

                $img->save(public_path('storage') . '/'  . $filename_2, 80);

                if (env('PLATFORM_NAME') !== 'windows') {
                    //SFTP
                    Storage::disk('sftp')->put('/' . $filename_2, $img);
                } else {
                    Storage::disk('windows_uploads')->put('/' . $filename_2, $img);
                }
            }


            $data = ModelsListItemDetail::updateOrCreate([
                'id' => base64_decode($req->idSP)
            ], [
                'id_side_list'        => base64_decode($req->id_content),
                'title'             =>(new HelperController)->scriptStripper( $req->title_id),
                'title_en'          =>(new HelperController)->scriptStripper( $req->title_eng),
                'description'       =>(new HelperController)->scriptStripper( $req->description),
                'description_en'    =>(new HelperController)->scriptStripper( $req->description_en),
                'description2'      =>(new HelperController)->scriptStripper( $req->description2),
                'description2_en'   =>(new HelperController)->scriptStripper( $req->description2_en),
                'url'               =>(new HelperController)->scriptStripper( $req->url ?? '-'),
                'file'               => $filename ?? '-',
                'file2'               => $filename_2 ?? '-',
                'insert_by' => session()->get('id'),
                'updated_by' => session()->get('id'),
                'updated_by_ip' => $req->ip()
            ]);
            $data->save();

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
        $log_app->request =  "Store Content Details";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Produk dan Layanan';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function storeKontakDetail(KontakProdukDanLayanan $req)
    {
        try {

            $dt_list_item =  KontakModel::find(base64_decode($req->idSP));
            $instagram_icon = $dt_list_item->instagram_icon ?? '';
            $facebook_icon = $dt_list_item->facebook_icon ?? '';
            $youtube_icon = $dt_list_item->youtube_icon ?? '';
            $phone_icon = $dt_list_item->phone_icon ?? '';
            $email_icon = $dt_list_item->email_icon ?? '';
            $website_icon = $dt_list_item->website_icon ?? '';


            if (!is_null($req->file('instagram_icon'))) {

                $manager                = new ImageManager();
                $ext                    =  $req->file('instagram_icon')->extension();
                $img                    = $manager->make($req->file('instagram_icon')->getPathname());
                $instagram_icon         = uniqid() . '.' . $req->file('instagram_icon')->getClientOriginalExtension();

                if ($ext == 'png' || $ext == 'PNG') {
                    $instagram_icon = uniqid() . '.' . 'webp';
                }

                $img->save(public_path('storage') . '/'  . $instagram_icon, 80);

                if (env('PLATFORM_NAME') !== 'windows') {
                    //SFTP
                    Storage::disk('sftp')->put('/' . $instagram_icon, $img);
                } else {
                    Storage::disk('windows_uploads')->put('/' . $instagram_icon, $img);
                }
            }

            if (!is_null($req->file('facebook_icon'))) {

                $manager                = new ImageManager();
                $ext                    =  $req->file('facebook_icon')->extension();
                $img                    = $manager->make($req->file('facebook_icon')->getPathname());
                $facebook_icon         = uniqid() . '.' . $req->file('facebook_icon')->getClientOriginalExtension();

                if ($ext == 'png' || $ext == 'PNG') {
                    $facebook_icon = uniqid() . '.' . 'webp';
                }

                $img->save(public_path('storage') . '/'  . $facebook_icon, 80);

                if (env('PLATFORM_NAME') !== 'windows') {
                    //SFTP
                    Storage::disk('sftp')->put('/' . $facebook_icon, $img);
                } else {
                    Storage::disk('windows_uploads')->put('/' . $facebook_icon, $img);
                }
            }
            if (!is_null($req->file('youtube_icon'))) {

                $manager                = new ImageManager();
                $ext                    =  $req->file('youtube_icon')->extension();
                $img                    = $manager->make($req->file('youtube_icon')->getPathname());
                $youtube_icon         = uniqid() . '.' . $req->file('youtube_icon')->getClientOriginalExtension();

                if ($ext == 'png' || $ext == 'PNG') {
                    $youtube_icon = uniqid() . '.' . 'webp';
                }

                $img->save(public_path('storage') . '/'  . $youtube_icon, 80);

                if (env('PLATFORM_NAME') !== 'windows') {
                    //SFTP
                    Storage::disk('sftp')->put('/' . $youtube_icon, $img);
                } else {
                    Storage::disk('windows_uploads')->put('/' . $youtube_icon, $img);
                }
            }
            if (!is_null($req->file('phone_icon'))) {

                $manager                = new ImageManager();
                $ext                    =  $req->file('phone_icon')->extension();
                $img                    = $manager->make($req->file('phone_icon')->getPathname());
                $phone_icon         = uniqid() . '.' . $req->file('phone_icon')->getClientOriginalExtension();

                if ($ext == 'png' || $ext == 'PNG') {
                    $phone_icon = uniqid() . '.' . 'webp';
                }

                $img->save(public_path('storage') . '/'  . $phone_icon, 80);

                if (env('PLATFORM_NAME') !== 'windows') {
                    //SFTP
                    Storage::disk('sftp')->put('/' . $phone_icon, $img);
                } else {
                    Storage::disk('windows_uploads')->put('/' . $phone_icon, $img);
                }
            }
            if (!is_null($req->file('email_icon'))) {

                $manager                = new ImageManager();
                $ext                    =  $req->file('email_icon')->extension();
                $img                    = $manager->make($req->file('email_icon')->getPathname());
                $email_icon         = uniqid() . '.' . $req->file('email_icon')->getClientOriginalExtension();

                if ($ext == 'png' || $ext == 'PNG') {
                    $email_icon = uniqid() . '.' . 'webp';
                }

                $img->save(public_path('storage') . '/'  . $email_icon, 80);

                if (env('PLATFORM_NAME') !== 'windows') {
                    //SFTP
                    Storage::disk('sftp')->put('/' . $email_icon, $img);
                } else {
                    Storage::disk('windows_uploads')->put('/' . $email_icon, $img);
                }
            }
            if (!is_null($req->file('website_icon'))) {

                $manager                = new ImageManager();
                $ext                    =  $req->file('website_icon')->extension();
                $img                    = $manager->make($req->file('website_icon')->getPathname());
                $website_icon         = uniqid() . '.' . $req->file('website_icon')->getClientOriginalExtension();

                if ($ext == 'png' || $ext == 'PNG') {
                    $website_icon = uniqid() . '.' . 'webp';
                }

                $img->save(public_path('storage') . '/'  . $website_icon, 80);

                if (env('PLATFORM_NAME') !== 'windows') {
                    //SFTP
                    Storage::disk('sftp')->put('/' . $website_icon, $img);
                } else {
                    Storage::disk('windows_uploads')->put('/' . $website_icon, $img);
                }
            }


            $data = KontakModel::updateOrCreate([
                'id' => base64_decode($req->idSP)
            ], [
                'id_content'        => base64_decode($req->id_content),
                'address'           => (new HelperController)->scriptStripper($req->address),
                'address_en'        => (new HelperController)->scriptStripper($req->address_en),
                'instagram_icon'    => $instagram_icon ?? '',
                'instagram_link'    => (new HelperController)->scriptStripper($req->instagram_link),
                'facebook_icon'     => $facebook_icon ?? '',
                'facebook_link'     => (new HelperController)->scriptStripper($req->facebook_link),
                'youtube_icon'      => $youtube_icon ?? '',
                'youtube_link'      => (new HelperController)->scriptStripper($req->youtube_link),
                'phone_icon'        => $phone_icon ?? '',
                'phone_number_one'  => (new HelperController)->scriptStripper($req->phone_number_one),
                'phone_number_two'  => (new HelperController)->scriptStripper($req->phone_number_two),
                'email_icon'        => $email_icon ?? '',
                'email_link'        => (new HelperController)->scriptStripper($req->email_link),
                'website_icon'      => $website_icon ?? '',
                'website_link'      => (new HelperController)->scriptStripper($req->website_link),
                'insert_by' => session()->get('id'),
                'updated_by' => session()->get('id'),
                'updated_by_ip' => $req->ip()
            ]);
            $data->save();

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
        $log_app->request =  "Store Contact Details";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Produk dan Layanan';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        
        return json_encode($response);
    }

    public function storeProductServiceList(Request $req)
    {
        try {
            $this->validate($req, [
                'product_name' => 'required',
                'product_name_en' => 'required',
            ], [
                'product_name.confirmed' => "Please fill this column",
                'product_name_en.confirmed' => "Please fill this column"
            ]);

            $data = new ProductAndServicesList();
            $data->id_content        = base64_decode($req->id_content);
            $data->product_name      = (new HelperController)->scriptStripper($req->product_name);
            $data->product_name_en   = (new HelperController)->scriptStripper($req->product_name_en);
            $data->flag              = $req->flag;
            $data->insert_by = session()->get('id');
            $data->updated_by = session()->get('id');
            $data->updated_by_ip = $req->ip();
            $data->save();

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
        $log_app->request =  "Store Product Service List";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Produk dan Layanan';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function updateProductServiceList(Request $req)
    {
        try {
            $this->validate($req, [
                'product_name' => 'required',
                'product_name_en' => 'required',
            ], [
                'product_name.confirmed' => "Please fill this column",
                'product_name_en.confirmed' => "Please fill this column"
            ]);

            $data = ProductAndServicesList::find($req->upt_id);
            $data->product_name      = (new HelperController)->scriptStripper($req->product_name);
            $data->product_name_en   = (new HelperController)->scriptStripper($req->product_name_en);
            $data->insert_by = session()->get('id');
            $data->updated_by = session()->get('id');
            $data->updated_by_ip = $req->ip();
            $data->save();

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
        $log_app->request =  "Update Product Service List";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Produk dan Layanan';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();

        return json_encode($response);
    }

    public function deleteProductServiceList($id, Request $req)
    {
        try {
            $dt_list_item =  ProductAndServicesList::destroy($id);

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
        $log_app->request =  "Delete Product Service List";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Produk dan Layanan';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
          
        return json_encode($response);
    }

    public function showModalProduct($id)
    {
        try {
            $dt_list_item =  ProductAndServicesList::find($id);
            $output = View::make("components.produk-dan-layanan-modal")
                ->with("dt_item", $dt_list_item)
                ->with("route", route('update-product-list'))
                ->with("formId", "produk-dan-layanan-modal")
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

    public function storeProductServiceListApp(Request $req)
    {
        try {

            $this->validate($req, [
                'flag_ps' => 'required',
                'flag_as' => 'required',
            ], [
                'flag_ps.confirmed' => "Please fill this column",
                'flag_as.confirmed' => "Please fill this column",
            ]);

            $data = ProductAndServicesList::updateOrCreate([
                'id' => base64_decode($req->idSP)
            ], [
                'id_content'        => base64_decode($req->id_content),
                'product_name'      => (new HelperController)->scriptStripper($req->product_name_ps),
                'flag'              => $req->flag_ps,
                'insert_by' => session()->get('id'),
                'updated_by' => session()->get('id'),
                'updated_by_ip' => $req->ip()
            ]);

            $data->save();

            $data_as = ProductAndServicesList::updateOrCreate([
                'id' => base64_decode($req->idSPAS)
            ], [
                'id_content'        => base64_decode($req->id_content),
                'product_name'      => (new HelperController)->scriptStripper($req->product_name_as),
                'flag'              => $req->flag_as,
                'insert_by' => session()->get('id'),
                'updated_by' => session()->get('id'),
                'updated_by_ip' => $req->ip()
            ]);

            $data_as->save();

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
        $log_app->request =  "Store Product Service List Apps";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Produk dan Layanan';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();

        return json_encode($response);
    }

    public function storeProductServicePicture(Request $req)
    {
        try {
            $this->validate($req, [
                'pictureHolding' => 'required',
            ], [
                'pictureHolding' => 'mimes:jpeg,png,jpg|max:100000'
            ]);

            if (!is_null($req->file('pictureHolding'))) {

                $manager                = new ImageManager();
                $ext                    =  $req->file('pictureHolding')->extension();
                $img                    = $manager->make($req->file('pictureHolding')->getPathname());
                $pictureHolding         = uniqid() . '.' . $req->file('pictureHolding')->getClientOriginalExtension();

                if ($ext == 'png' || $ext == 'PNG') {
                    $pictureHolding = uniqid() . '.' . 'webp';
                }

                $img->save(public_path('storage') . '/'  . $pictureHolding, 80);

                if (env('PLATFORM_NAME') !== 'windows') {
                    //SFTP
                    Storage::disk('sftp')->put('/' . $pictureHolding, $img);
                } else {
                    Storage::disk('windows_uploads')->put('/' . $pictureHolding, $img);
                }
            }

            $data = new GalleryAnggotaHolding();
            $data->id_content        = base64_decode($req->id_content);
            $data->picture      = $pictureHolding;
            $data->insert_by = session()->get('id');
            $data->updated_by = session()->get('id');
            $data->updated_by_ip = $req->ip();
            $data->save();

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
        $log_app->request =  "Store Product Service Picture";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Produk dan Layanan';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function deleteProductServiceGallry($id, Request $req)
    {
        try {
            $dt_list_item =  GalleryAnggotaHolding::destroy($id);

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
        $log_app->request =  "Delete Product Service Gallery";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Produk dan Layanan';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }
}

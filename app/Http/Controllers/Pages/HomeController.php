<?php
namespace App\Http\Controllers\pages;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Models\MenuModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Intervention\Image\ImageManager;
use App\Models\ListItemDetail;
use App\Models\ListItemModel;
use App\Http\Requests\TentangKami;
use App\Models\LogApp;
use Exception;
use PDO;
class HomeController extends Controller
{
    public function index($id)
    {
        $data['menus'] = MenuModel::find(base64_decode($id));
        $data['title']      = 'Training Kerja | Pages';
        $data['title_page'] = 'Master | ' . $data['menus']->menu_name;
        $data['menu']       = MenuModel::all();
        $data['dataTk']  = DB::table('ifg_pages_content')
        ->leftjoin('ifg_pages_side_list', 'ifg_pages_side_list.id_pages_content', '=', 'ifg_pages_content.id')
        ->select('ifg_pages_content.id', 'ifg_pages_side_list.id as id_side', 'ifg_pages_content.item_file','ifg_pages_content.item_file_2', 'ifg_pages_content.item_body','ifg_pages_content.item_link', 'ifg_pages_content.item_body_en', 'ifg_pages_side_list.side_list', 'ifg_pages_side_list.side_list_en', 'ifg_pages_content.item_title', 'ifg_pages_content.item_title_en')
        ->where('ifg_pages_content.id', '2480')
        ->where('ifg_pages_content.id_pages_content_order', '1')
        ->first();
        return view('pages.master', $data);
    }

    public function storeHome(TentangKami $req)
    {
        try {
            $dt_list_item =  ListItemModel::find(base64_decode($req->idSP));

            if ($dt_list_item != null) {

                $filename = $dt_list_item->item_file;
                // dd($filename);
                $chkOrder = ListItemModel::where('id_pages_content_order', $dt_list_item->id_pages_content_order)->where('id_menu', $dt_list_item->id_menu)->get();
                if (count($chkOrder) <= 1) {
                    $order = 1;
                } else {
                    $order = count($chkOrder) + 1;
                }
            } else {
                // dd('addd');
                $order = 1;
            }

            // $dt_list_item =  ListItemModel::find(base64_decode($req->idSP));

            if (!is_null($req->file('item_file'))) {

                $manager                = new ImageManager();
                $ext                    =  $req->file('item_file')->extension();
                $img                    = $manager->make($req->file('item_file')->getPathname());
                $filename         = uniqid() . '.' . $req->file('item_file')->getClientOriginalExtension();

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

            if (!is_null($req->file('item_file_2'))) {

                $manager                = new ImageManager();
                $ext                    =  $req->file('item_file_2')->extension();
                $img                    = $manager->make($req->file('item_file_2')->getPathname());
                $filename_2         = uniqid() . '.' . $req->file('item_file_2')->getClientOriginalExtension();

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

            $listItem = ListItemModel::updateOrCreate([
                'id' => base64_decode($req->idSP)
            ], [
                'id_menu' => base64_decode($req->pages),
                'id_pages_content_order' => $req->id_content_order,
                'item_extras' => $req->item_extras ?? '-',
                'item_title' => (new HelperController)->scriptStripper($req->title),
                'item_link' => $req->title_en,
                'item_body' => (new HelperController)->scriptStripper($req->description),
                'item_body_en' => (new HelperController)->scriptStripper($req->description_en),
                'item_order' => $order,
                'item_file' => $filename ?? '',
                'item_file_2' => $filename_2 ?? '',
                'insert_by' => session()->get('id'),
                'updated_by' => session()->get('id'),
                'updated_by_ip' => $req->ip()
            ]);
            $listItem->save();
            (new HelperController)->storeSideList($req, $listItem->id);

            $response = [
                'status' => 'success',
                'message' => 'Data berhasil disimpan'
            ];
        } catch (Exception $e) {
            $response = [
                'status' => 'failed',
                'message' => "Terjadi Kesalahan pada sistem : " . $e,
            ];
        }

        $log_app = new LogApp();
        $log_app->method = $req->method();
        $log_app->request =  "Store Sekilas Perusahaan";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Tentang Kami';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();

        return json_encode($response);
    }

    public function registrasiindex($id)
    {
        $data['menus'] = MenuModel::find(base64_decode($id));
        $data['title']      = 'Training Kerja | Pages';
        $data['title_page'] = 'Registrasion | ' . $data['menus']->menu_name;
        $data['menu']       = MenuModel::all();
        $data['dataTk']  = DB::table('ifg_pages_content')
        ->leftjoin('ifg_pages_side_list', 'ifg_pages_side_list.id_pages_content', '=', 'ifg_pages_content.id')
        ->select('ifg_pages_content.id', 'ifg_pages_side_list.id as id_side', 'ifg_pages_content.item_file','ifg_pages_content.item_file_2', 'ifg_pages_content.item_body','ifg_pages_content.item_link', 'ifg_pages_content.item_body_en', 'ifg_pages_side_list.side_list', 'ifg_pages_side_list.side_list_en', 'ifg_pages_content.item_title', 'ifg_pages_content.item_title_en')
        ->where('ifg_pages_content.id', '2483')
        ->where('ifg_pages_content.id_pages_content_order', '1')
        ->first();
        return view('pages.master_registration', $data);
    }

    public function storeHomeregistrasion(TentangKami $req)
    {
        try {
            $dt_list_item =  ListItemModel::find(base64_decode($req->idSP));

            if ($dt_list_item != null) {

                $filename = $dt_list_item->item_file;
                // dd($filename);
                $chkOrder = ListItemModel::where('id_pages_content_order', $dt_list_item->id_pages_content_order)->where('id_menu', $dt_list_item->id_menu)->get();
                if (count($chkOrder) <= 1) {
                    $order = 1;
                } else {
                    $order = count($chkOrder) + 1;
                }
            } else {
                // dd('addd');
                $order = 1;
            }

            // $dt_list_item =  ListItemModel::find(base64_decode($req->idSP));

            if (!is_null($req->file('item_file'))) {

                $manager                = new ImageManager();
                $ext                    =  $req->file('item_file')->extension();
                $img                    = $manager->make($req->file('item_file')->getPathname());
                $filename         = uniqid() . '.' . $req->file('item_file')->getClientOriginalExtension();

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

            if (!is_null($req->file('item_file_2'))) {

                $manager                = new ImageManager();
                $ext                    =  $req->file('item_file_2')->extension();
                $img                    = $manager->make($req->file('item_file_2')->getPathname());
                $filename_2         = uniqid() . '.' . $req->file('item_file_2')->getClientOriginalExtension();

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

            $listItem = ListItemModel::updateOrCreate([
                'id' => base64_decode($req->idSP)
            ], [
                'id_menu' => base64_decode($req->pages),
                'id_pages_content_order' => $req->id_content_order,
                'item_extras' => $req->item_extras ?? '-',
                'item_title' => (new HelperController)->scriptStripper($req->title),
                'item_link' => $req->title_en,
                'item_body' => (new HelperController)->scriptStripper($req->description),
                'item_body_en' => (new HelperController)->scriptStripper($req->description_en),
                'item_order' => $order,
                'item_file' => $filename ?? '',
                'item_file_2' => $filename_2 ?? '',
                'insert_by' => session()->get('id'),
                'updated_by' => session()->get('id'),
                'updated_by_ip' => $req->ip()
            ]);
            $listItem->save();
            (new HelperController)->storeSideList($req, $listItem->id);

            $response = [
                'status' => 'success',
                'message' => 'Data berhasil disimpan'
            ];
        } catch (Exception $e) {
            $response = [
                'status' => 'failed',
                'message' => "Terjadi Kesalahan pada sistem : " . $e,
            ];
        }

        $log_app = new LogApp();
        $log_app->method = $req->method();
        $log_app->request =  "Store Sekilas Perusahaan";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Tentang Kami';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();

        return json_encode($response);
    }

}

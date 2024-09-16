<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Http\Requests\AnakPerusahaan;
use App\Http\Requests\ListItemDetail as RequestsListItemDetail;
use App\Http\Requests\PenghargaanSertifikat;
use App\Http\Requests\PenghargaanSertifikatUpdate;
use App\Http\Requests\ProfileManajemen;
use App\Http\Requests\SejarahKami;
use App\Http\Requests\TentangKami;
use App\Http\Requests\VisiMisi;
use App\Models\ListItemDetail;
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

class TentangKamiController extends Controller
{
    public function sekilasPerusahaan($id)
    {
        $data['menus'] = MenuModel::find(base64_decode($id));
        $data['title']      = 'IFG | Pages';
        $data['title_page'] = 'Tentang Kami | ' . $data['menus']->menu_name;
        $data['menu']       = MenuModel::all();
        // $data['dataTk']  = ListItemModel::where('id_menu', '3')->where('id_pages_content_order', '1')->first();
        $data['dataTk']  = DB::table('ifg_pages_content')
            ->leftjoin('ifg_pages_side_list', 'ifg_pages_side_list.id_pages_content', '=', 'ifg_pages_content.id')
            ->select('ifg_pages_content.id', 'ifg_pages_side_list.id as id_side', 'ifg_pages_content.item_file', 'ifg_pages_content.item_body', 'ifg_pages_content.item_body_en', 'ifg_pages_side_list.side_list', 'ifg_pages_side_list.side_list_en', 'ifg_pages_content.item_title', 'ifg_pages_content.item_title_en')
            ->where('ifg_pages_content.id_menu', '3')
            ->where('ifg_pages_content.id_pages_content_order', '1')
            ->first();
        return view('pages.sekilas_perusahaan', $data);
    }

    public function storeSekilasPerusahaan(TentangKami $req)
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
                'item_title_en' => (new HelperController)->scriptStripper($req->title_en),
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

    public function visiMisi($id)
    {
        $data['menus'] = MenuModel::find(base64_decode($id));
        $data['title']      = 'IFG | Pages';
        $data['title_page'] = 'Tentang Kami | ' . $data['menus']->menu_name;
        $data['menu']       = MenuModel::all();
        $data['dataTk']  = SideListModel::where('id_menu', base64_decode($id))->where('id_pages_content_order', '1')->get();
        $data['dataItem']  = ListItemModel::where('id_menu', base64_decode($id))->where('id_pages_content_order', '1')->first();
        return view('pages.visi_misi', $data);
    }

    public function editVisiMisi($id)
    {
        $data['menus'] = MenuModel::find(4);
        $data['title']      = 'IFG | Pages';
        $data['title_page'] = 'Tentang Kami | ' . $data['menus']->menu_name;
        $data['content'] = base64_decode($id);
        $data['menu']       = MenuModel::all();
        $data['dataPages']  = SideListModel::where('id', base64_decode($id))->first();
        $data['dataItem']  = ListItemModel::where('id_menu', base64_decode($id))->where('id_pages_content_order', '1')->first();
        $data['dataTk']  = ListItemDetail::where('id_side_list', base64_decode($id))->get();
        return view('pages.visi_misi_edit', $data);
    }

    public function storeVisiMisi(Request $req)
    {
        try {
            $dt_list_item =  ListItemModel::find(base64_decode($req->pages));
            if ($dt_list_item != null) {
                $chkOrder = ListItemModel::where('id_pages_content_order', $dt_list_item->id_pages_content_order)->where('id_menu', $dt_list_item->id_menu)->get();
                if (count($chkOrder) <= 0) {
                    $order = 1;
                } else {
                    $order = count($chkOrder) + 1;
                }
            } else {
                $order = 1;
            }
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
            $listItem = ListItemModel::updateOrCreate([
                'id_menu' => base64_decode($req->pages),
                'id_pages_content_order' => $req->id_content_order,
                'item_extras' => '-',
                'item_title' => (new HelperController)->scriptStripper($req->title),
                'item_title_en' => (new HelperController)->scriptStripper($req->title_en),
                'item_body' => (new HelperController)->scriptStripper($req->description),
                'item_body_en' => (new HelperController)->scriptStripper($req->description_en),
                'item_file' => $filename ?? '',
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
        } catch (ModelNotFoundException $e) {
            $response = [
                'status' => 'failed',
                'message' => "Terjadi Kesalahan pada sistem : " . $e,
            ];
        }

        $log_app = new LogApp();
        $log_app->method = $req->method();
        $log_app->request =  "Store Visi Misi";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Tentang Kami';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function updateTitleVisiMisi(Request $req)
    {
        try {

            $req->validate([
                'title' => 'required',
                'title_en' => 'required',
            ], [
                'title.required' => "Side list is required",
                'title_en.required' => "Side list ENG is required",
            ]);

            $listItem = SideListModel::find(base64_decode($req->id_content));
            $listItem->side_list            = (new HelperController)->scriptStripper($req->title);
            $listItem->side_list_en         = (new HelperController)->scriptStripper($req->title_en);
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
        $log_app->request =  "Update Title Visi Misi";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Tentang Kami';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }
    public function updateVisiMisi(VisiMisi $req)
    {
        try {
            $listItem = new ListItemDetail();
            $listItem->id_side_list = base64_decode($req->id_content);
            $listItem->title            =(new HelperController)->scriptStripper( $req->title_id);
            $listItem->title_en         =(new HelperController)->scriptStripper( $req->title_eng);
            $listItem->description      =(new HelperController)->scriptStripper( $req->description);
            $listItem->description_en   =(new HelperController)->scriptStripper( $req->description_en);
            $listItem->description2     =(new HelperController)->scriptStripper( $req->description2 ?? '-');
            $listItem->description2_en  =(new HelperController)->scriptStripper( $req->description2_en ?? '-');
            $listItem->extras             = $req->flag ?? '0';
            $listItem->url              = $req->url ?? '-';
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
        $log_app->request =  "Update Visi Misi";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Tentang Kami';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function updateVisiMisiDetail(Request $req)
    {
        try {
            $listItem = ListItemDetail::find($req->upt_id);
            $listItem->title            =(new HelperController)->scriptStripper( $req->title);
            $listItem->title_en         =(new HelperController)->scriptStripper( $req->title_en);
            $listItem->description      =(new HelperController)->scriptStripper( $req->description);
            $listItem->description_en   =(new HelperController)->scriptStripper( $req->description_en);
            $listItem->description2     =(new HelperController)->scriptStripper( $req->description2 ?? '-');
            $listItem->description2_en  =(new HelperController)->scriptStripper( $req->description2_en ?? '-');
            $listItem->extras             = $req->flag ?? '0';
            $listItem->url              = $req->url ?? '-';
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
        $log_app->request =  "Update Visi Misi Detail";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Tentang Kami';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    //sejarah kami
    public function sejarahKami($id)
    {
        $data['menus'] = MenuModel::find(base64_decode($id));
        $data['title']      = 'IFG | Pages';
        $data['title_page'] = 'Tentang Kami | ' . $data['menus']->menu_name;
        $data['menu']       = MenuModel::all();
        $data['dataTk']  = DB::table('ifg_pages_content')
            ->leftjoin('ifg_pages_side_list', 'ifg_pages_side_list.id_pages_content', '=', 'ifg_pages_content.id')
            ->select('ifg_pages_content.id', 'ifg_pages_side_list.id as id_side', 'ifg_pages_content.item_file', 'ifg_pages_content.item_body', 'ifg_pages_content.item_body_en', 'ifg_pages_side_list.side_list', 'ifg_pages_side_list.side_list_en', 'ifg_pages_content.item_title', 'ifg_pages_content.item_title_en')
            ->where('ifg_pages_content.id_menu', base64_decode($id))
            ->where('ifg_pages_content.id_pages_content_order', '1')
            ->first();

        if (isset($data['dataTk']))
            $data['dataTkDetail']  = ListItemDetail::where('id_side_list', $data['dataTk']->id_side)->get();

        // dd($data['dataTkDetail']);

        return view('pages.sejarah_kami', $data);
    }

    public function storeSejarahKami(SejarahKami $req)
    {
        try {
            $listItem = new ListItemDetail();
            $listItem->id_side_list       = base64_decode($req->id_content);
            $listItem->title            = (new HelperController)->scriptStripper($req->title_id);
            $listItem->title_en         = (new HelperController)->scriptStripper($req->title_id);
            $listItem->description      = (new HelperController)->scriptStripper($req->description);
            $listItem->description_en   = (new HelperController)->scriptStripper($req->description_en);
            $listItem->extras             = $req->title_id ?? '0';
            $listItem->url              = $req->url ?? '-';
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
        $log_app->request =  "Store Sejarah Kami";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Tentang Kami';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function editListItemDetail($id)
    {
        try {
            $dt_list_item =  ListItemDetail::find($id);
            $output = View::make("components.sejarah-kami-component")
                ->with("dt_item", $dt_list_item)
                ->with("route", route('update-history'))
                ->with("formId", "tentang-kami-edit")
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

    public function updateSejarahKamiDetail(Request $req)
    {
        try {
            $listItem = ListItemDetail::find($req->upt_id);
            $listItem->title            = (new HelperController)->scriptStripper($req->title);
            $listItem->title_en         = (new HelperController)->scriptStripper($req->title);
            $listItem->description      = (new HelperController)->scriptStripper($req->description);
            $listItem->description_en   = (new HelperController)->scriptStripper($req->description_en);
            $listItem->description2     = (new HelperController)->scriptStripper($req->description2 ?? '-');
            $listItem->description2_en  = (new HelperController)->scriptStripper($req->description2_en ?? '-');
            $listItem->extras             = $req->title ?? '0';
            $listItem->url              = $req->url ?? '-';
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
        $log_app->request =  "Update Sejarah Kami";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Tentang Kami';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function profileManajemen($id)
    {
        $data['menus'] = MenuModel::find(base64_decode($id));
        $data['title']      = 'IFG | Pages';
        $data['title_page'] = 'Tentang Kami | ' . $data['menus']->menu_name;
        $data['menu']       = MenuModel::all();
        $data['dataTk']  = SideListModel::where('id_menu', base64_decode($id))->where('id_pages_content_order', '1')->get();
        // $data['dataItem']  = ListItemModel::where('id_menu', base64_decode($id))->where('id_pages_content_order', '1')->first();
        $data['dataItem']  = DB::table('ifg_pages_content')
            ->leftjoin('ifg_pages_side_list', 'ifg_pages_side_list.id_pages_content', '=', 'ifg_pages_content.id')
            ->select('ifg_pages_content.id', 'ifg_pages_side_list.id as id_side', 'ifg_pages_content.item_file', 'ifg_pages_content.item_file_2', 'ifg_pages_content.item_body', 'ifg_pages_content.item_body_en', 'ifg_pages_side_list.side_list', 'ifg_pages_side_list.side_list_en', 'ifg_pages_content.item_title', 'ifg_pages_content.item_title_en')
            ->where('ifg_pages_content.id_menu', base64_decode($id))
            ->where('ifg_pages_content.id_pages_content_order', '1')
            ->first();
        return view('pages.profile_manajemen', $data);
    }

    public function editProfileManajemen($id)
    {
        $data['menus'] = MenuModel::find(6);
        $data['title']      = 'IFG | Pages';
        $data['title_page'] = 'Tentang Kami | ' . $data['menus']->menu_name;
        $data['content'] = base64_decode($id);
        $data['menu']       = MenuModel::all();
        // $data['dataPages']  = ListItemModel::where('id', base64_decode($id))->first();
        $data['dataPages']  = DB::table('ifg_pages_content')
            ->leftjoin('ifg_pages_side_list', 'ifg_pages_side_list.id_pages_content', '=', 'ifg_pages_content.id')
            ->select('ifg_pages_content.id', 'ifg_pages_side_list.id as id_side', 'ifg_pages_content.item_file', 'ifg_pages_content.item_body', 'ifg_pages_content.item_body_en', 'ifg_pages_side_list.side_list', 'ifg_pages_side_list.side_list_en', 'ifg_pages_content.item_title', 'ifg_pages_content.item_title_en')
            ->where('ifg_pages_side_list.id', base64_decode($id))
            ->first();
        // dd($data['dataPages']);

        $data['dataTk']  = ListItemDetail::where('id_side_list', base64_decode($id))->get();
        return view('pages.profile_manajemen_edit', $data);
    }

    public function storeProfilManajemen(ProfileManajemen $req)
    {
        try {
            $listItem = new ListItemDetail();
            $listItem->id_side_list = base64_decode($req->id_content);
            $listItem->title            = (new HelperController)->scriptStripper($req->title_id);
            $listItem->title_en         = (new HelperController)->scriptStripper($req->title_id);
            $listItem->description      = (new HelperController)->scriptStripper($req->description);
            $listItem->description_en   = (new HelperController)->scriptStripper($req->description_en);
            $listItem->description2     = (new HelperController)->scriptStripper($req->description2);
            $listItem->description2_en  = (new HelperController)->scriptStripper($req->description2_en);
            $listItem->extras             = $req->flag ?? '0';
            $listItem->url              = $req->url ?? '-';
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
        $log_app->request =  "Store Profile Manajemen";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Tentang Kami';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();

        return json_encode($response);
    }

    public function updateProfileManajemen(ProfileManajemen $req)
    {
        try {
            $listItem = ListItemDetail::find($req->upt_id);
            $listItem->title            = (new HelperController)->scriptStripper($req->title_id);
            $listItem->title_en         = (new HelperController)->scriptStripper($req->title_id);
            $listItem->description      = (new HelperController)->scriptStripper($req->description);
            $listItem->description_en   = (new HelperController)->scriptStripper($req->description_en);
            $listItem->description2     = (new HelperController)->scriptStripper($req->description2);
            $listItem->description2_en  = (new HelperController)->scriptStripper($req->description2_en);
            $listItem->extras             = $req->flag ?? '0';
            $listItem->url              = $req->url ?? '-';
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
        $log_app->request =  "Update Profile Manajemen";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Tentang Kami';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();

        return json_encode($response);
    }

    public function modalProfileManajemen($id)
    {
        try {
            $dt_list_item =  ListItemDetail::find($id);
            $output = View::make("components.profile-manajemen-edit-component")
                ->with("dt_item", $dt_list_item)
                ->with("route", route('update-profile-manajemen'))
                ->with("formId", "tentang-kami-edit")
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

    public function anakPerusahaan($id)
    {
        $data['menus'] = MenuModel::find(base64_decode($id));
        $data['title']      = 'IFG | Pages';
        $data['title_page'] = 'Tentang Kami | ' . $data['menus']->menu_name;
        $data['menu']       = MenuModel::all();
        $data['dataTk']  = ListItemModel::where('id_menu', '7')->where('id_pages_content_order', '1')->first();
        $data['dataTkDetail']  = ListItemDetail::where('id_content', $data['dataTk']->id ?? '')->first();
        return view('pages.anak_perusahaan', $data);
    }
    public function storeAnakPerusahaan(AnakPerusahaan $req)
    {
        try {
            // $dt_list_item =  ListItemModel::find(base64_decode($req->idSP));

            // if ($dt_list_item != null) {

            //     $filename = $dt_list_item->item_file;
            //     // dd($filename);
            //     $chkOrder = ListItemModel::where('id_pages_content_order', $dt_list_item->id_pages_content_order)->where('id_menu', $dt_list_item->id_menu)->get();
            //     if (count($chkOrder) <= 1) {
            //         $order = 1;
            //     } else {
            //         $order = count($chkOrder) + 1;
            //     }
            // } else {
            //     // dd('addd');
            //     $order = 1;
            // }

            $listItemDetail = ListItemDetail::where('id', base64_decode($req->idSPD))->first();
            $filename_id = $listItemDetail->file ?? '';
            $filename = $listItemDetail->file2 ?? '';

            if (!is_null($req->file('item_file_2'))) {

                $manager                = new ImageManager();
                $ext                    =  $req->file('item_file_2')->extension();
                $img                    = $manager->make($req->file('item_file_2')->getPathname());
                $filename_id         = uniqid() . '.' . $req->file('item_file_2')->getClientOriginalExtension();

                if ($ext == 'png' || $ext == 'PNG') {
                    $filename_id = uniqid() . '.' . 'webp';
                }

                $img->save(public_path('storage') . '/'  . $filename_id, 80);

                if (env('PLATFORM_NAME') !== 'windows') {
                    //SFTP
                    Storage::disk('sftp')->put('/' . $filename_id, $img);
                } else {
                    Storage::disk('windows_uploads')->put('/' . $filename_id, $img);
                }
            }

            if (!is_null($req->file('item_file_en'))) {

                $manager                = new ImageManager();
                $ext                    =  $req->file('item_file_en')->extension();
                $img                    = $manager->make($req->file('item_file_en')->getPathname());
                $filename         = uniqid() . '.' . $req->file('item_file_en')->getClientOriginalExtension();

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
            
            $listItem = ListItemModel::updateOrCreate([
                'id' => base64_decode($req->idSP)
            ], [
                'id_menu' => base64_decode($req->pages),
                'id_pages_content_order' => $req->id_content_order,
                'item_title' => (new HelperController)->scriptStripper($req->title),
                'item_title_en' => (new HelperController)->scriptStripper($req->title_en),
                'item_body' => (new HelperController)->scriptStripper($req->description),
                'item_body_en' => (new HelperController)->scriptStripper($req->description_en),
                'item_file_2' => $filename_2 ?? '',
                'insert_by' => session()->get('id'),
                'updated_by' => session()->get('id'),
                'updated_by_ip' => $req->ip()
            ]);
            $listItem->save();

            $listItemDetail = ListItemDetail::updateOrCreate([
                'id' => base64_decode($req->idSPD)
            ], [
                'id_content' => base64_decode($req->idSP),
                'file' => $filename_id ?? '',
                'file2' => $filename ?? '',
                'insert_by' => session()->get('id'),
                'updated_by' => session()->get('id'),
                'updated_by_ip' => $req->ip()
            ]);
            $listItemDetail->save();
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
        $log_app->request =  "Store Anak Perusahaan Content";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Tentang Kami';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();

        $log_app = new LogApp();
        $log_app->method = $req->method();
        $log_app->request =  "Store Anak Perusahaan Content Detail";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Tentang Kami';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();

        return json_encode($response);
    }

    public function penghargaanSertifikat($id)
    {
        $data['menus'] = MenuModel::find(base64_decode($id));
        $data['title']      = 'IFG | Pages';
        $data['title_page'] = 'Tentang Kami | ' . $data['menus']->menu_name;
        $data['menu']       = MenuModel::all();
        $data['dataTk']  = ListItemModel::where('id_menu', '8')->where('id_pages_content_order', '1')->first();
        $data['dataMenu']  = ListItemDetail::where('id_content', $data['dataTk']->id ?? '')->orderBy('order', 'DESC')->get();
        return view('pages.penghargaan_sertifikat', $data);
    }

    public function storePenghargaanSertifikat(PenghargaanSertifikat $req)
    {
        try {
            //check order list
            $chkOrder = ListItemDetail::where('id_content', base64_decode($req->id_content))->get();
            // dd(count($chkOrder));
            if (count($chkOrder) <= 0) {
                $order = 1;
            } else {
                $order = count($chkOrder) + 1;
            }
            if (!is_null($req->file('item_file'))) {
                $manager                = new ImageManager();
                $ext                    =  $req->file('item_file')->extension();
                $img                    = $manager->make($req->file('item_file')->getPathname());
                $filename    = uniqid() . '.' . $req->file('item_file')->getClientOriginalExtension();

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
                $filename2    = uniqid() . '.' . $req->file('item_file_2')->getClientOriginalExtension();

                if ($ext == 'png' || $ext == 'PNG') {
                    $filename2 = uniqid() . '.' . 'webp';
                }

                $img->save(public_path('storage') . '/'  . $filename2, 80);

                if (env('PLATFORM_NAME') !== 'windows') {
                    //SFTP
                    Storage::disk('sftp')->put('/' . $filename2, $img);
                } else {
                    Storage::disk('windows_uploads')->put('/' . $filename2, $img);
                }
            }

            $listItem = ListItemDetail::updateOrCreate([
                'id' => base64_decode($req->idSP)
            ], [
                'id_content' => base64_decode($req->id_content),
                'extras' => $req->item_extras,
                'title' => (new HelperController)->scriptStripper($req->title),
                'title_en' => (new HelperController)->scriptStripper($req->title),
                'description' => (new HelperController)->scriptStripper($req->description),
                'description_en' => (new HelperController)->scriptStripper($req->description_en),
                'url' => (new HelperController)->scriptStripper($req->url),
                'order' => $order,
                'file' => $filename ?? '',
                'file2' => $filename2 ?? '',
                'insert_by' => session()->get('id'),
                'updated_by' => session()->get('id'),
                'updated_by_ip' => $req->ip()
            ]);

            $listItem->save();

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
        $log_app->request =  "Store Penghargaan Sertifikat";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Tentang Kami';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();


        return json_encode($response);
    }

    public function editPernghargaanSertifikat($id)
    {
        try {
            $dt_list_item =  ListItemDetail::find($id);
            $chkOrder = ListItemDetail::where('id_content', $dt_list_item->id_content)->get();

            $output = View::make("components.penghargaan-sertifikat-component")
                ->with("dt_item", $dt_list_item)
                ->with("chkOrder", $chkOrder)
                ->with("route", route('update-penghargaan-dan-sertifikat'))
                ->with("formId", "penghargaan-sertifikat-edit")
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
                'message' => "Terjadi Kesalahan pada sistem." . $e,
            ];
        }
        return json_encode($response);
    }

    public function updatePernghargaanSertifikat(PenghargaanSertifikatUpdate $req)
    {
        try {

            $changeOrder = ListItemDetail::where('id', $req->upt_id)->where('order', $req->upt_item_order)->first();
            $order = $changeOrder->order;
            $changeOrder->order = $req->upt_current_order;
            $changeOrder->save();

            $listItem = ListItemDetail::find($req->upt_id);
            $listItem->extras = $req->upt_item_extras;
            $listItem->title = (new HelperController)->scriptStripper($req->upt_title);
            $listItem->title_en = (new HelperController)->scriptStripper($req->upt_title_en);
            $listItem->description = (new HelperController)->scriptStripper($req->upt_description);
            $listItem->description_en = (new HelperController)->scriptStripper($req->upt_description_en);
            $listItem->order = $order;
            $listItem->url = (new HelperController)->scriptStripper($req->upt_url);
            $listItem->updated_by = session()->get('id');
            $listItem->updated_by_ip = $req->ip();
            if (!is_null($req->file('upt_item_file'))) {
                $manager                = new ImageManager();
                $ext                    =  $req->file('upt_item_file')->extension();
                $img                    = $manager->make($req->file('upt_item_file')->getPathname());
                $listItem->file    = uniqid() . '.' . $req->file('upt_item_file')->getClientOriginalExtension();

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
        } catch (Exception $e) {
            $response = [
                'status' => 'failed',
                'message' => "Terjadi Kesalahan pada sistem : " . $e,
            ];
        }

        $log_app = new LogApp();
        $log_app->method = $req->method();
        $log_app->request =  "Updae Penghargaan Sertifikat List";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Tentang Kami';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }
}

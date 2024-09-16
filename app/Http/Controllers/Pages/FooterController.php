<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Models\ListItemDetail;
use App\Models\ListItemModel;
use App\Models\LogApp;
use App\Models\MenuModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Intervention\Image\ImageManager;

class FooterController extends Controller
{
    public function index($id)
    {
        $data['menus'] = MenuModel::find(base64_decode($id));
        $data['title']      = 'IFG | Pages';
        $data['title_page'] = 'Hubungi Kami | ' . $data['menus']->menu_name;
        $data['menu']       = MenuModel::all();
        $data['dataContent']       = ListItemModel::where('id_menu', base64_decode($id))->first();
        $data['dataContentList']       = ListItemDetail::where('id_content', $data['dataContent']->id ?? '')->get();
        return view('pages.footer', $data);
    }

    public function storeFooterList(Request $req)
    {
        try {
            $this->validate($req, [
                'item_file' => 'required|mimes:jpeg,png,jpg|max:100000',
                'description' => 'required',
                'description_en' => 'required',
                'type' => 'required',
            ], [
                'item_file.required' => "Please fill this column",
                'description.confirmed' => "Please fill this column",
                'description_en.confirmed' => "Please fill this column",
                'type.confirmed' => "Please fill this column",
            ]);

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

            $data = new ListItemDetail();
            $data->id_content        = base64_decode($req->idSP);
            $data->file   = $filename;
            $data->description = (new HelperController)->scriptStripper($req->description);
            $data->description_en = (new HelperController)->scriptStripper($req->description_en);
            $data->extras = (new HelperController)->scriptStripper($req->type);
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
        $log_app->request =  "Store Footer List";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Footer';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function editFooterList($id)
    {
        try {
            $dt_list_item =  ListItemDetail::find($id);
            $output = View::make("components.footer")
                ->with("dt_item", $dt_list_item)
                ->with("route", route('update-footer-list'))
                ->with("formId", "footer-edit")
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

    public function updateFooterList(Request $req)
    {
        try {
            $this->validate($req, [
                'item_file' => 'mimes:jpeg,png,jpg|max:100000',
                'description' => 'required',
                'description_en' => 'required',
                'type' => 'required',
            ], [
                'item_file.required' => "Please fill this column",
                'description.confirmed' => "Please fill this column",
                'description_en.confirmed' => "Please fill this column",
                'type.confirmed' => "Please fill this column"
            ]);

            $data = ListItemDetail::find($req->upt_id);

            if (!is_null($req->file('item_file'))) {

                $manager                = new ImageManager();
                $ext                    =  $req->file('item_file')->extension();
                $img                    = $manager->make($req->file('item_file')->getPathname());
                $filename         = uniqid() . '.' . $req->file('item_file')->getClientOriginalExtension();

                if ($ext == 'png' || $ext == 'PNG') {
                    $filename = uniqid() . '.' . 'webp';
                }

                $img->save(public_path('storage') . '/'  . $filename, 80);

                $data->file             = $filename;
            }
            $data->description      = (new HelperController)->scriptStripper($req->description);
            $data->description_en   = (new HelperController)->scriptStripper($req->description_en);
            $data->extras             = (new HelperController)->scriptStripper($req->type);
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
        $log_app->request =  "Update Footer List";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Footer';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }
}

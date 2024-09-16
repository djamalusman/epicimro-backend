<?php

namespace App\Http\Controllers;

use App\Models\LaporanTahunanModel;
use App\Models\ListItemModel;
use App\Models\LogApp;
use App\Models\MasterTipeModel;
use App\Models\Insightmodel;
use App\Models\MenuModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Intervention\Image\ImageManager;

class InsightContoller extends Controller
{
    public function index($id)
    {
        $data['menus'] = MenuModel::find(base64_decode($id));
        $data['title']      = 'IFG | Pages';
        $data['title_page'] = 'Insight | ' . $data['menus']->menu_name;
        $data['menu']       = MenuModel::all();
        $data['dataContent']       = ListItemModel::where('id_menu', base64_decode($id))->first();
        $data['dataContentList']       = Insightmodel::where('id_content', $data['dataContent']->id ?? '')->get();
        $data['typeLaporan'] = MasterTipeModel::whereIn('tipe_kode', array(11, 12))->get();
        //dump($data['dataContent']);
        return view('pages.Insight', $data);
    }
    public function storeinsight(Request $req)
    {
        
        try {
            
            $this->validate($req, [
                'type' => 'required',
                'title' => 'required',
                'title_en' => 'required',
                'url1' => 'required',
                'description' => 'required',
                'description_en' => 'required',
                'item_file' => 'mimes:jpeg,png,jpg,pdf|max:100000'
            ], [
                'type.confirmed' => "Please fill this column",
                'title.confirmed' => "Please fill this column",
                'title_en.confirmed' => "Please fill this column",
                'url1.confirmed' => "Please fill this column",
                'description.confirmed' => "Please fill this column",
                'description_en.confirmed' => "Please fill this column"
            ]);
            
            $dt_list_item =  ListItemModel::find(base64_decode($req->idSP));
            // dd($dt_list_item);
            if ($dt_list_item != null) {

                $filename = $dt_list_item->item_file;
                $chkOrder = ListItemModel::where('id_pages_content_order', $dt_list_item->id_pages_content_order)->where('id_menu', $dt_list_item->id_menu)->get();
                if (count($chkOrder) <= 1) {
                    $order = 1;
                } else {
                    $order = count($chkOrder) + 1;
                }
            } else {
                $order = 1;
            }

            if (!is_null($req->file('item_file'))) {
                $ext                    =  $req->file('item_file')->extension();
                $filename               = uniqid() . '.' . $req->file('item_file')->getClientOriginalExtension();
                if ($ext == 'pdf') {
                    $req->file('item_file')->storeAs(
                        'public/',
                        $filename
                    );

                    if (env('PLATFORM_NAME') !== 'windows') {
                        //SFTP
                        Storage::disk('sftp')->put('/' . $filename, file_get_contents($req->file('item_file')));
                    } else {
                        Storage::disk('windows_uploads')->put('/' . $filename, file_get_contents($req->file('item_file')));
                    }
                } else {
                    $manager                = new ImageManager();
                    $img                    = $manager->make($req->file('item_file')->getPathname());
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
                'item_order' => $order,
                'item_file' => $filename ?? '',
                'insert_by' => session()->get('id'),
                'updated_by' => session()->get('id'),
                'updated_by_ip' => $req->ip()
            ]);
            $listItem->save();

            
            $data = new Insightmodel();
            $data->id_content        = base64_decode($req->idSP);
            $data->date_report   = (new HelperController)->scriptStripper($req->year);
            $data->url_content = $req->url1;
            $data->file   = $filename;
            // $data->file2   = $filename2;
            $data->type   = $req->type;
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

        // $log_app = new LogApp();
        // $log_app->method = $req->method();
        // $log_app->request =  "Store Insight";
        // $log_app->response =  json_encode($response);
        // $log_app->pages = 'Insight';
        // $log_app->user_id = session()->get('id');
        // $log_app->ip_address = $req->ip();
        // $log_app->save();
       
        return json_encode($response);
    }

    
}

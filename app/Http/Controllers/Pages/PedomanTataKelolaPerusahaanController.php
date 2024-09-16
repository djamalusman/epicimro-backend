<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Http\Requests\ListItemDetail;
use App\Models\ListItemDetail as ModelsListItemDetail;
use App\Models\ListItemModel;
use App\Models\LogApp;
use App\Models\MenuModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Intervention\Image\ImageManager;

class PedomanTataKelolaPerusahaanController extends Controller
{
    public function index($id)
    {
        $data['menus'] = MenuModel::find(base64_decode($id));
        $data['title']      = 'IFG | Pages';
        $data['title_page'] = 'Tata Kelola Perusahaan | ' . $data['menus']->menu_name;
        $data['menu']       = MenuModel::all();
        $data['dataContent']       = ListItemModel::where('id_menu', base64_decode($id))->first();
        $data['dataContentList']       = ModelsListItemDetail::where('id_content', $data['dataContent']->id)->get();

        // dd($data['dataContentList']);
        return view('pages.pedoman_tata_kelola_perusahaan', $data);
    }

    public function storePedomanTKP(Request $req)
    {
        try {
            $this->validate($req, [
                'title' => 'required',
                'title_en' => 'required',
                'description' => 'required',
                'description_en' => 'required',
                'item_file' => 'mimes:jpeg,png,jpg|max:100000'
            ], [
                'title.confirmed' => "Please fill this column",
                'title_en.confirmed' => "Please fill this column",
                'description.confirmed' => "Please fill this column",
                'description_en.confirmed' => "Please fill this column"
            ]);

            $dt_list_item =  ListItemModel::find(base64_decode($req->idSP));

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
                'id' => base64_decode($req->idSP)
            ], [
                'id_menu' => base64_decode($req->pages),
                'id_pages_content_order' => $req->id_content_order,
                'item_title' =>(new HelperController)->scriptStripper( $req->title),
                'item_title_en' =>(new HelperController)->scriptStripper( $req->title_en),
                'item_body' =>(new HelperController)->scriptStripper( $req->description),
                'item_body_en' =>(new HelperController)->scriptStripper( $req->description_en),
                'item_order' => $order,
                'item_file' => $filename ?? '',
                'insert_by' => session()->get('id'),
                'updated_by' => session()->get('id'),
                'updated_by_ip' => $req->ip()
            ]);
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
        $log_app->request =  "Store Tata Kelola Perusahaan";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Tata Kelola Perusahaan';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function storePedomanTKPList(Request $req)
    {
        try {
            $this->validate($req, [
                'item_file' => 'required|mimes:jpeg,png,jpg,pdf|max:100000',
                'item_file_2' => 'required|mimes:jpeg,png,jpg,pdf|max:100000'
            ], [
                'item_file.required' => "Please fill this column",
                'item_file_2.required' => "Please fill this column"
            ]);

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
            if (!is_null($req->file('item_file_2'))) {
                $ext                    =  $req->file('item_file_2')->extension();
                $filename2               = uniqid() . '.' . $req->file('item_file_2')->getClientOriginalExtension();
                if ($ext == 'pdf') {
                    $req->file('item_file_2')->storeAs(
                        'public/',
                        $filename2
                    );

                    if (env('PLATFORM_NAME') !== 'windows') {
                        //SFTP
                        Storage::disk('sftp')->put('/' . $filename2, file_get_contents($req->file('item_file_2')));
                    } else {
                        Storage::disk('windows_uploads')->put('/' . $filename2, file_get_contents($req->file('item_file_2')));
                    }
                } else {
                    $manager                = new ImageManager();
                    $img                    = $manager->make($req->file('item_file_2')->getPathname());
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
            }

            $data = new ModelsListItemDetail();
            $data->id_content        = base64_decode($req->idSP);
            $data->file   = $filename;
            $data->file2   = $filename2;
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
        $log_app->request =  "Store Pedoman";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Tata Kelola Perusahaan';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function editPedomanTKPList($id)
    {
        try {
            $dt_list_item =  ModelsListItemDetail::find($id);
            $output = View::make("components.pedoman-tata-kelola-perusahaan-modal")
                ->with("dt_item", $dt_list_item)
                ->with("route", route('update-pedoman-tata-kelola-perusahaan-list'))
                ->with("formId", "laporan-tahunan-edit")
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

    public function updatePedomanTKPList(Request $req)
    {
        try {
            $this->validate($req, [
                'item_file' => 'mimes:jpeg,png,jpg,pdf|max:100000',
                'item_file_2' => 'mimes:jpeg,png,jpg,pdf|max:100000'
            ], [
                'item_file.required' => "Please fill this column",
                'item_file_2.required' => "Please fill this column"
            ]);

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
            if (!is_null($req->file('item_file_2'))) {
                $ext                    =  $req->file('item_file_2')->extension();
                $filename2               = uniqid() . '.' . $req->file('item_file_2')->getClientOriginalExtension();
                if ($ext == 'pdf') {
                    $req->file('item_file_2')->storeAs(
                        'public/',
                        $filename2
                    );
                    if (env('PLATFORM_NAME') !== 'windows') {
                        //SFTP
                        Storage::disk('sftp')->put('/' . $filename2, file_get_contents($req->file('item_file_2')));
                    } else {
                        Storage::disk('windows_uploads')->put('/' . $filename2, file_get_contents($req->file('item_file_2')));
                    }
                } else {
                    $manager                = new ImageManager();
                    $img                    = $manager->make($req->file('item_file_2')->getPathname());
                    if ($ext == 'png' || $ext == 'PNG') {
                        $filename2 = uniqid() . '.' . 'webp';
                    }
                    $img->save(public_path('storage') . '/'  . $filename2, 80);
                }
            }

            $data = ModelsListItemDetail::find($req->upt_id);
            $data->file   = $filename ?? $data->file;
            $data->file2   = $filename2 ?? $data->file2;
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
        $log_app->request =  "Update Pedoman";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Tata Kelola Perusahaan';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }
}

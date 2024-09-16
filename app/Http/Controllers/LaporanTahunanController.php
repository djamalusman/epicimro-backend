<?php

namespace App\Http\Controllers;

use App\Models\LaporanTahunanModel;
use App\Models\ListItemModel;
use App\Models\LogApp;
use App\Models\MasterTipeModel;
use App\Models\MenuModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Intervention\Image\ImageManager;

class LaporanTahunanController extends Controller
{
    public function index($id)
    {
        $data['menus'] = MenuModel::find(base64_decode($id));
        $data['title']      = 'IFG | Pages';
        $data['title_page'] = 'Publikasi | ' . $data['menus']->menu_name;
        $data['menu']       = MenuModel::all();
        $data['dataContent']       = ListItemModel::where('id_menu', base64_decode($id))->first();
        $data['dataContentList']       = LaporanTahunanModel::where('id_content', $data['dataContent']->id ?? '')->get();
        $data['typeLaporan'] = MasterTipeModel::where('tipe_kode','>=',1)->get();
        return view('pages.laporan_tahunan', $data);
    }

    public function storeLaporanTahunan(Request $req)
    {
        try {
            $this->validate($req, [
                'title' => 'required',
                'title_en' => 'required',
                'description' => 'required',
                'description_en' => 'required',
                'item_file' => 'mimes:jpeg,png,jpg,pdf|max:100000'
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
        $log_app->request =  "Store Laporan Tahunan";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Laporan Tahunan';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function storeLaporanTahunanList(Request $req)
    {
        try {
            $this->validate($req, [
                'title' => 'required',
                'title_en' => 'required',
                'year' => 'required',
                'type' => 'required',
                'item_file' => 'required|mimes:jpeg,png,jpg,pdf|max:100000',
                'item_file_2' => 'required|mimes:jpeg,png,jpg,pdf|max:100000'
            ], [
                'title.required' => "Please fill this column",
                'title_en.required' => "Please fill this column",
                'type.required' => "Please fill this column",
                'year.required' => "Please fill this column",
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

            $data = new LaporanTahunanModel();
            $data->id_content        = base64_decode($req->idSP);
            $data->title      = (new HelperController)->scriptStripper($req->title);
            $data->title_en   = (new HelperController)->scriptStripper($req->title_en);
            $data->date_report   = (new HelperController)->scriptStripper($req->year);
            $data->file   = $filename;
            $data->file2   = $filename2;
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
            // $log_app->request =  "Store Laporan Tahunan List";
            // $log_app->response =  json_encode($response);
            // $log_app->pages = 'Laporan Tahunan';
            // $log_app->user_id = session()->get('id');
            // $log_app->ip_address = $req->ip();
            // $log_app->save();
        return json_encode($response);
    }

    public function deleteProductServiceList($id, Request $req)
    {
        try {
            $dt_list_item =  LaporanTahunanModel::destroy($id);

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

    public function editProductServiceList($id)
    {
        try {
            $dt_list_item =  LaporanTahunanModel::find($id);
            $masterTipeItems = MasterTipeModel::where('tipe_kode', '>=', 1)->get();
            $output = View::make("components.laporan-tahunan-modal")
                ->with("dt_item", $dt_list_item)
                ->with("route", route('update-laporan-tahunan-list'))
                ->with("formId", "laporan-tahunan-edit")
                ->with("formMethod", "PUT")
                ->with("masterTipeItems", $masterTipeItems)
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

    public function updateProductServiceList(Request $req)
    {
        try {
            $this->validate($req, [
                'title' => 'required',
                'title_en' => 'required',
                'year' => 'required',
                'type' => 'required',
                'item_file' => 'mimes:jpeg,png,jpg,pdf|max:100000',
                'item_file_2' => 'mimes:jpeg,png,jpg,pdf|max:100000'
            ], [
                'title.required' => "Please fill this column",
                'title_en.required' => "Please fill this column",
                'type.required' => "Please fill this column",
                'year.required' => "Please fill this column",
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

            $data = LaporanTahunanModel::find($req->upt_id);
            $data->title      = (new HelperController)->scriptStripper($req->title);
            $data->title_en   = (new HelperController)->scriptStripper($req->title_en);
            $data->date_report   = (new HelperController)->scriptStripper($req->year);
            $data->file   = $filename ?? $data->file;
            $data->file2   = $filename2 ?? $data->file2;
            $data->type   = $req->type;
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
        $log_app->pages = 'Laporan Tahunan';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }
}

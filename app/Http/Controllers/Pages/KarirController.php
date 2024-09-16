<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Models\KarirModel;
use App\Models\ListItemModel;
use App\Models\ListItemDetail;
use App\Models\LogApp;
use App\Models\MasterKontrakModel;
use App\Models\MenuModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Intervention\Image\ImageManager;
use Illuminate\Validation\ValidationException;

class KarirController extends Controller
{
    public function karir($id)
    {
        $data['menus'] = MenuModel::find(base64_decode($id));
        $data['title']      = 'IFG | Pages';
        $data['title_page'] = 'Karir | ' . $data['menus']->menu_name;
        $data['menu']       = MenuModel::all();
        $data['dataContent']      = ListItemModel::where('id_menu', base64_decode($id))->first();
        $data['youtubeContent'] = ListItemModel::where('id_menu', base64_decode($id))
            ->where('id_pages_content_order', '!=', 1)
            ->orderBy('id_pages_content_order', 'asc')
            ->first();
        $data['dataWidget'] = ListItemDetail::where('id_content', $data['dataContent']->id)
            ->whereBetween('order', [1, 4])
            ->orderBy('order')
            ->get();

        $data['dataYoutubeContent'] = ListItemDetail::where('id_content', $data['dataContent']->id)
            ->where('order', '>=', 5)
            ->orderBy('order')
            ->get();

        return view('pages.karir', $data);
    }

    public function storeKarir(Request $req)
    {
        try {
            $this->validate($req, [
                'title' => 'required',
                'title_en' => 'required',
                'description' => 'required',
                'description_en' => 'required',
                'item_link' => 'required',
                'item_file' => 'mimes:jpeg,png,jpg|max:100000',
            ], [
                'title.confirmed' => "Please fill this column",
                'title_en.confirmed' => "Please fill this column",
                'description.confirmed' => "Please fill this column",
                'description_en.confirmed' => "Please fill this column",
                'item_link.confirmed' => "Please fill this column",
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
                'item_title' => (new HelperController)->scriptStripper($req->title),
                'item_title_en' => (new HelperController)->scriptStripper($req->title_en),
                'item_body' => (new HelperController)->scriptStripper($req->description),
                'item_body_en' => (new HelperController)->scriptStripper($req->description_en),
                'item_link' => $req->item_link,
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
        $log_app->request =  "Store Karir";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Career';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function storeKarirYoutube(Request $req)
    {
        try {
            $this->validate($req, [
                'title' => 'required',
                'title_en' => 'required',
                'description' => 'required',
                'description_en' => 'required',
            ], [
                'title.confirmed' => "Please fill this column",
                'title_en.confirmed' => "Please fill this column",
                'description.confirmed' => "Please fill this column",
                'description_en.confirmed' => "Please fill this column",
            ]);

            $dt_list_item =  ListItemModel::where('id_menu', base64_decode($req->pages))
                ->where('id_pages_content_order', '!=', 1)
                ->orderBy('id_pages_content_order', 'asc')
                ->first();

            if ($dt_list_item != null) {

                $chkOrder = ListItemModel::where('id_pages_content_order', $dt_list_item->id_pages_content_order)->where('id_menu', $dt_list_item->id_menu)->get();
                if (count($chkOrder) <= 1) {
                    $order = 1;
                } else {
                    $order = count($chkOrder) + 1;
                }
            } else {
                $order = 1;
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
        $log_app->request =  "Store Karir Youtube";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Career';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function storeKarirWidget(Request $req)
    {
        try {
            $this->validate($req, [
                'title' => 'required',
                'title_en' => 'required',
                'description' => 'required',
                'description_en' => 'required',
                'item_file_widget' => 'mimes:jpeg,png,jpg|max:100000',
                // 'item_file_widget' => 'mimes:jpeg,png,jpg|dimensions:min_width=350,min_height=600,max_width=350,max_height=600|max:100000',
            ], [
                'title.confirmed' => "Please fill this column",
                'title_en.confirmed' => "Please fill this column",
                'description.confirmed' => "Please fill this column",
                'description_en.confirmed' => "Please fill this column",
            ]);

            $maxItems = 4; // Jumlah maksimal data
            $idSP = base64_decode($req->input('idSP'));

            // Mencari jumlah item dalam rentang order 1-4
            $occupiedItemCount = ListItemDetail::where('id_content', $idSP)
                ->whereBetween('order', [1, $maxItems])
                ->count();

            if ($occupiedItemCount === $maxItems) {
                throw ValidationException::withMessages(['message' => 'Jumlah maksimal data telah tercapai.']);
            }



            $dt_list_item = ListItemDetail::where('id_content', base64_decode($req->input('idSP')))->first();

            $newOrder = 1; // Default value jika order tidak ditemukan atau null

            if ($dt_list_item) {
                // Ambil order terbesar dari record dengan kondisi WHERE
                $maxOrderItem = ListItemDetail::where('id_content', base64_decode($req->input('idSP')))->max('order');

                if (!is_null($maxOrderItem)) {
                    for ($i = 1; $i <= $maxOrderItem + 1; $i++) {
                        if (!ListItemDetail::where('id_content', base64_decode($req->input('idSP')))->where('order', $i)->exists()) {
                            $newOrder = $i;
                            break;
                        }
                    }
                }
            }




            $descriptionValue = $req->input('description');
            $titleValue = $req->input('title');
            $titleEnValue = $req->input('title_en');
            $description = $req->input('description');
            $description_en = $req->input('description_en');
            $item_file_widget = $req->file('item_file_widget');


            if (!is_null($req->file('item_file_widget'))) {

                $manager                = new ImageManager();
                $ext                    =  $req->file('item_file_widget')->extension();
                $img                    = $manager->make($req->file('item_file_widget')->getPathname());
                $filename         = uniqid() . '.' . $req->file('item_file_widget')->getClientOriginalExtension();

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


            $data = [
                'description' => $descriptionValue,
                'id_content' =>  base64_decode($req->input('idSP')),
                'title' => $titleValue,
                'title_en' => $titleEnValue,
                'description' => $description,
                'order' => $newOrder,
                'description_en' => $description_en,
                'file' => $filename ?? '',
            ];

            ListItemDetail::create($data);



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
        $log_app->request =  "Store Karir Widget";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Career';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function storeKarirWidgetYoutube(Request $req)
    {
        try {
            $this->validate($req, [
                'title' => 'required',
                'title_en' => 'required',
                'url' => 'required',
            ], [
                'title.confirmed' => "Please fill this column",
                'title_en.confirmed' => "Please fill this column",
                'url.confirmed' => "Please fill this column",
            ]);


            $dt_list_item = ListItemDetail::where('id_content', base64_decode($req->input('idSP')))->first();

            $newOrder = 5; // Set nilai default untuk new order

            if ($dt_list_item) {
                // Ambil order terbesar dari record dengan kondisi WHERE
                $maxOrderItem = ListItemDetail::where('id_content', base64_decode($req->input('idSP')))->max('order');

                // Jika maxOrderItem tidak null dan lebih besar atau sama dengan 4, maka tambahkan 1
                if (!is_null($maxOrderItem) && $maxOrderItem >= 5) {
                    $newOrder = $maxOrderItem + 1;
                }
            }




            $titleValue = $req->input('title');
            $titleEnValue = $req->input('title_en');
            $url = $req->input('url');




            $data = [
                'id_content' =>  base64_decode($req->input('idSP')),
                'title' => $titleValue,
                'title_en' => $titleEnValue,
                'order' => $newOrder,
                'url' => $url
            ];

            ListItemDetail::create($data);

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
        $log_app->request =  "Store Karir Widget";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Career';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function updateKarirWidget(Request $req)
    {
        try {
            $this->validate($req, [
                'title' => 'required',
                'order' => 'required',
                'title_en' => 'required',
                'description' => 'required',
                'description_en' => 'required',
                'item_file_widget' => 'mimes:jpeg,png,jpg|max:100000',
                // 'item_file_widget' => 'mimes:jpeg,png,jpg|dimensions:min_width=350,min_height=600,max_width=350,max_height=600|max:100000',
            ], [
                'title.confirmed' => "Please fill this column",
                'title_en.confirmed' => "Please fill this column",
                'description.confirmed' => "Please fill this column",
                'description_en.confirmed' => "Please fill this column",
            ]);


            $id = base64_decode($req->input('id'));
            $descriptionValue = $req->input('description');
            $titleValue = $req->input('title');
            $titleEnValue = $req->input('title_en');
            $description = $req->input('description');
            $description_en = $req->input('description_en');
            $item_file_widget = $req->file('item_file_widget');
            $order = $req->input('order');


            if (!is_null($req->file('item_file_widget'))) {

                $manager                = new ImageManager();
                $ext                    =  $req->file('item_file_widget')->extension();
                $img                    = $manager->make($req->file('item_file_widget')->getPathname());
                $filename         = uniqid() . '.' . $req->file('item_file_widget')->getClientOriginalExtension();

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


            $data = [
                'description' => $descriptionValue,
                'id_content' =>  base64_decode($req->input('idSP')),
                'title' => $titleValue,
                'title_en' => $titleEnValue,
                'description' => $description,
                'order' => $order,
                'description_en' => $description_en,
            ];


            if (!empty($filename)) {
                $data['file'] = $filename;
            }

            if (!empty($id)) {
                // Update the data based on the provided ID
                ListItemDetail::where('id', $id)->update($data);

                $response = [
                    'status' => 'success',
                    'message' => 'Data berhasil diupdate'
                ];
            } else {
                throw ValidationException::withMessages(['message' => 'ID for update is missing.']);
            }
        } catch (ModelNotFoundException $e) {
            $response = [
                'status' => 'failed',
                'message' => "Terjadi Kesalahan pada sistem : " . $e,
            ];
        }


        $log_app = new LogApp();
        $log_app->method = $req->method();
        $log_app->request =  "Update Karir Widget";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Career';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function updateKarirWidgetYoutue(Request $req)
    {
        try {
            $this->validate($req, [
                'title' => 'required',
                'order' => 'required',
                'title_en' => 'required',
                'url' => 'required',
            ], [
                'title.confirmed' => "Please fill this column",
                'title_en.confirmed' => "Please fill this column",
                'order.confirmed' => "Please fill this column",
                'order.confirmed' => "Please fill this column",
            ]);


            $id = base64_decode($req->input('id'));
            $titleValue = $req->input('title');
            $titleEnValue = $req->input('title_en');
            $url = $req->input('url');
            $order = $req->input('order');

            $data = [
                'title' => $titleValue,
                'title_en' => $titleEnValue,
                'order' => intval($order) + 4,
                'url' => $url,
            ];

            if (!empty($id)) {
                // Update the data based on the provided ID
                ListItemDetail::where('id', $id)->update($data);

                $response = [
                    'status' => 'success',
                    'message' => 'Data berhasil diupdate'
                ];
            } else {
                throw ValidationException::withMessages(['message' => 'ID for update is missing.']);
            }
        } catch (ModelNotFoundException $e) {
            $response = [
                'status' => 'failed',
                'message' => "Terjadi Kesalahan pada sistem : " . $e,
            ];
        }


        $log_app = new LogApp();
        $log_app->method = $req->method();
        $log_app->request =  "Update Karir Widget";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Career';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }


    public function storeKarirDelete(Request $req, $id)
    {
        try {

            // Hapus item berdasarkan ID
            $deleted = ListItemDetail::destroy($id);

            if ($deleted) {
                $response = [
                    'status' => 'success',
                    'message' => 'Data berhasil dihapus'
                ];
                $status = 200; // HTTP status code: 200 OK
            } else {
                $response = [
                    'status' => 'failed',
                    'message' => 'Item tidak ditemukan'
                ];
                $status = 404; // HTTP status code: 404 Not Found
            }
        } catch (ModelNotFoundException $e) {
            $response = [
                'status' => 'failed',
                'message' => "Terjadi Kesalahan pada sistem : " . $e,
            ];
        }

        $log_app = new LogApp();
        $log_app->method = $req->method();
        $log_app->request =  "Store Karir Widget";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Career';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function rekrutmen($id)
    {
        $data['menus'] = MenuModel::find(base64_decode($id));
        $data['title']      = 'IFG | Pages';
        $data['title_page'] = 'Karir | ' . $data['menus']->menu_name;
        $data['menu']       = MenuModel::all();
        $data['dataContent']       = ListItemModel::where('id_menu', base64_decode($id))->orderBy('updated_at', 'DESC')->first();
        // $data['dataContentList']       = DB::join('ifg_master_tipe_contract', 'ifg_master_tipe_contract.id', '=', 'ifg_pages_karir.work_type')->where('id_content', $data['dataContent']->id ?? '')->get();
        $data['dataContentList']       =  DB::table('ifg_pages_karir')
            ->leftjoin('ifg_master_tipe_contract', 'ifg_master_tipe_contract.id', '=', 'ifg_pages_karir.work_type')
            ->select('ifg_pages_karir.*', 'ifg_master_tipe_contract.name')
            ->where('id_content', $data['dataContent']->id ?? '')
            ->orderBy('status', 'desc')->get();
        $data['dataMasterContract']       = MasterKontrakModel::all();
        return view('pages.rekrutmen', $data);
    }

    public function storeRekrutmenList(Request $req)
    {
        try {
            $this->validate($req, [
                'title' => 'required',
                'title_en' => 'required',
                'divisi' => 'required',
                'city' => 'required',
                'description' => 'required',
                'description_en' => 'required',
                'work_type' => 'required',
                'thumbnail' => 'mimes:jpeg,png,jpg|max:100000'

            ], [
                'title.required' => "Please fill this column",
                'title_en.required' => "Please fill this column",
                'city.required' => "Please fill this column",
                'divisi.required' => "Please fill this column",
                'description.required' => "Please fill this column",
                'description_en.required' => "Please fill this column",
                'work_type.required' => 'Please fill this column'
            ]);

            $data = new KarirModel();
            $data->id_content        = base64_decode($req->idSP);
            $data->title      = (new HelperController)->scriptStripper($req->title);
            $data->title_en   = (new HelperController)->scriptStripper($req->title_en);
            $data->divisi   = (new HelperController)->scriptStripper($req->divisi);
            $data->city   = (new HelperController)->scriptStripper($req->city);
            $data->description   = (new HelperController)->scriptStripper($req->description);
            $data->description_en   = (new HelperController)->scriptStripper($req->description_en);
            $data->work_type   = (new HelperController)->scriptStripper($req->work_type);
            $data->start_date   = date_format(date_create($req->start_date), 'Y-m-d H:i:s');
            $data->end_date   = date_format(date_create($req->end_date), 'Y-m-d H:i:s');
            $data->url   = (new HelperController)->scriptStripper($req->url);
            $data->insert_by = session()->get('id');
            $data->updated_by = session()->get('id');
            $data->updated_by_ip = $req->ip();
            if (!is_null($req->file('thumbnail'))) {
                $ext                    =  $req->file('thumbnail')->extension();
                $data->thumbnail    = uniqid() . '.' . $req->file('thumbnail')->getClientOriginalExtension();

                $manager                = new ImageManager();
                $img                    = $manager->make($req->file('thumbnail')->getPathname());
                if ($ext == 'png' || $ext == 'PNG') {
                    $data->thumbnail = uniqid() . '.' . 'webp';
                }
                $img->save(public_path('storage') . '/'  . $data->thumbnail, 80);
                if (env('PLATFORM_NAME') !== 'windows') {
                    //SFTP
                    Storage::disk('sftp')->put('/' . $data->thumbnail, $img);
                } else {
                    Storage::disk('windows_uploads')->put('/' . $data->thumbnail, $img);
                }
            }
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
        $log_app->request =  "Store Recruitment List";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Recruitment';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();

        return json_encode($response);
    }

    public function deleteKarir($id, $param = '', Request $req)
    {
        try {

            if ($param == 'isActive') {
                $dt_list_item =  KarirModel::find($id);

                if ($dt_list_item->status == 0)
                    $dt_list_item->status = '1';
                else
                    $dt_list_item->status = '0';

                $dt_list_item->save();
            } else {
                $dt_list_item =  KarirModel::destroy($id);
            }

            if ($dt_list_item) {
                $response = [
                    'status' => 'success',
                    'message' => 'Success',
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
        $log_app->request =  "Delete Career";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Career';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function editRekrutmenList($id)
    {
        try {
            $dt_list_item =  KarirModel::find($id);
            $dmc =  MasterKontrakModel::all();
            $output = View::make("components.rekrutmen")
                ->with("dt_item", $dt_list_item)
                ->with("dmc", $dmc)
                ->with("route", route('update-rekrutmen-list'))
                ->with("formId", "rekrutmen-edit")
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

    public function updateRekrutmenList(Request $req)
    {
        try {
            $this->validate($req, [
                'title' => 'required',
                'title_en' => 'required',
                'divisi' => 'required',
                'city' => 'required',
                'description' => 'required',
                'description_en' => 'required',
                'work_type' => 'required',
                'thumbnail' => 'mimes:jpeg,png,jpg|max:100000'

            ], [
                'title.required' => "Please fill this column",
                'title_en.required' => "Please fill this column",
                'city.required' => "Please fill this column",
                'divisi.required' => "Please fill this column",
                'description.required' => "Please fill this column",
                'description_en.required' => "Please fill this column",
                'work_type.required' => 'Please fill this column'
            ]);


            $data = KarirModel::find($req->upt_id);
            $data->title      = (new HelperController)->scriptStripper($req->title);
            $data->title_en   = (new HelperController)->scriptStripper($req->title_en);
            $data->divisi   = (new HelperController)->scriptStripper($req->divisi);
            $data->city   = (new HelperController)->scriptStripper($req->city);
            $data->description   = (new HelperController)->scriptStripper($req->description);
            $data->description_en   = (new HelperController)->scriptStripper($req->description_en);
            $data->work_type   = (new HelperController)->scriptStripper($req->work_type);
            $data->start_date   = date_format(date_create($req->start_date), 'Y-m-d H:i:s');
            $data->end_date   = date_format(date_create($req->end_date), 'Y-m-d H:i:s');
            $data->url   = (new HelperController)->scriptStripper($req->url);
            $data->updated_by = session()->get('id');
            $data->updated_by_ip = $req->ip();
            if (!is_null($req->file('thumbnail'))) {
                $ext                    =  $req->file('thumbnail')->extension();
                $data->thumbnail    = uniqid() . '.' . $req->file('thumbnail')->getClientOriginalExtension();

                $manager                = new ImageManager();
                $img                    = $manager->make($req->file('thumbnail')->getPathname());
                if ($ext == 'png' || $ext == 'PNG') {
                    $data->thumbnail = uniqid() . '.' . 'webp';
                }
                $img->save(public_path('storage') . '/'  . $data->thumbnail, 80);
                if (env('PLATFORM_NAME') !== 'windows') {
                    //SFTP
                    Storage::disk('sftp')->put('/' . $data->thumbnail, $img);
                } else {
                    Storage::disk('windows_uploads')->put('/' . $data->thumbnail, $img);
                }
            }
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
        $log_app->request =  "Update Recruitment List";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Recruitment';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();

        return json_encode($response);
    }
}

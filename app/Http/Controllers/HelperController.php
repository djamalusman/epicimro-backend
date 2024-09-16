<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnggotaHolding;
use App\Models\AnggotaHoldingModel;
use App\Models\IfgGaleryModel;
use App\Models\IfgNewsModel;
use App\Models\ListItemDetail;
use App\Models\ListItemModel;
use App\Models\LogApp;
use App\Models\PostIg;
use App\Models\SideListModel;
use App\Models\TokenIg;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Intervention\Image\ImageManager;

class HelperController extends Controller
{
    public function deleteListItemDetail($id, Request $req)
    {
        try {
            $check = ListItemDetail::where('id', $id)->first();
            $dt_list_item =  ListItemDetail::destroy($id);
            // dd($check->id_content);
            if ($check->id_content != null) {
                $reOrder = ListItemDetail::where('id_content', $check->id_content)->get();
                if ($reOrder) {
                    foreach ($reOrder as $key => $orderItem) {
                        $listItem = ListItemDetail::find($orderItem->id);
                        $listItem->order = $key + 1;
                        $listItem->save();
                    }
                }
            }
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
        $log_app->request =  "Delete List Item Details";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Helper';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function deleteAnggotaHolding($id, Request $req)
    {
        try {
            $check = AnggotaHoldingModel::where('id', $id)->first();
            $dt_list_item =  AnggotaHoldingModel::destroy($id);
            // dd($check->id_content);
            if ($check != null) {
                $reOrder = AnggotaHoldingModel::all();
                foreach ($reOrder as $key => $orderItem) {
                    $listItem = AnggotaHoldingModel::find($orderItem->id);
                    $listItem->order = $key + 1;
                    $listItem->save();
                }
            }
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
        $log_app->request =  "Delete Anggota Holding";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Helper';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function editListItemDetail($id)
    {
        try {
            $dt_list_item =  ListItemDetail::find($id);
            $output = View::make("components.tentang-kami-component")
                ->with("dt_item", $dt_list_item)
                ->with("route", route('update-visi-misi-detail'))
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

    public function storePageHeader(Request $req)
    {
        try {
            $req->validate([
                'item_file' => 'mimes:jpeg,png,jpg|max:100000'
            ]);

            $dt_list_item =  ListItemModel::find(base64_decode($req->idSP));

            if ($dt_list_item != null) {

                $filename = $dt_list_item->item_file;
                $filename_2 = $dt_list_item->item_file_2;
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
                'item_order' => $order,
                'item_title' => $this->scriptStripper($req->title ?? ''),
                'item_title_en' => $this->scriptStripper($req->title_en ?? ''),
                'item_file' => $filename ?? '',
                'item_file_2' => $filename_2 ?? '',
                'insert_by' => session()->get('id'),
                'updated_by' => session()->get('id'),
                'updated_by_ip' => $req->ip()
            ]);
            $listItem->save();

            if (!empty($req->side_list) && !empty($req->side_list_en))
                $this->storeSideList($req, $listItem->id);


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
        $log_app->request =  "Store Page Header";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Helper';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function storeSideList(Request $req, $id = '')
    {
        try {
            $req->validate([
                'side_list' => 'required',
                'side_list_en' => 'required'
            ], [
                'side_list.required' => "Column must not be empty",
                'side_list_en.required' => "Column must not be empty"
            ]);

            $chkOrder = SideListModel::all();
            if (count($chkOrder) < 1) {
                $order = 1;
            } else {
                $order = count($chkOrder) + 1;
            }

            $listItem = SideListModel::updateOrCreate([
                'id' => base64_decode($req->idSide)
            ], [
                'id_menu' => base64_decode($req->pages),
                'id_pages_content' => $id,
                'id_pages_content_order' => $req->id_content_order,
                'order' => $order,
                'side_list' => $this->scriptStripper($req->side_list),
                'side_list_en' => $this->scriptStripper($req->side_list_en),
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
        $log_app->request =  "Store Side List";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Helper';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function updateOnlySideList(Request $req, $id = '')
    {
        try {
            $req->validate([
                'side_list' => 'required',
                'side_list_en' => 'required'
            ], [
                'side_list.required' => "Column must not be empty",
                'side_list_en.required' => "Column must not be empty"
            ]);

            $listItem = SideListModel::find(base64_decode($req->idSide));
            $listItem->side_list = $this->scriptStripper($req->side_list);
            $listItem->side_list_en = $this->scriptStripper($req->side_list_en);
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
        $log_app->request =  "Update Side List Only";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Helper';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function storeAnggotaHolding(AnggotaHolding $req)
    {
        try {

            //check order list
            $chkOrder = AnggotaHoldingModel::all();

            if (count($chkOrder) < 1) {
                $order = 1;
            } else {
                $order = count($chkOrder) + 1;
            }

            $listItem = new AnggotaHoldingModel();
            $listItem->nama_holding = $this->scriptStripper($req->subs_name);
            $listItem->jenis_holding = $this->scriptStripper($req->jenis_holding);
            $listItem->url = $this->scriptStripper($req->url);
            $listItem->order = $order;
            $listItem->insert_by = session()->get('id');
            $listItem->updated_by = session()->get('id');
            $listItem->updated_by_ip = $req->ip();
            if (!is_null($req->file('item_file'))) {
                $manager                = new ImageManager();
                $ext                    =  $req->file('item_file')->extension();
                $img                    = $manager->make($req->file('item_file')->getPathname());
                $listItem->gambar_holding    = uniqid() . '.' . $req->file('item_file')->getClientOriginalExtension();

                if ($ext == 'png' || $ext == 'PNG') {
                    $listItem->gambar_holding = uniqid() . '.' . 'webp';
                }

                $img->save(public_path('storage') . '/'  . $listItem->gambar_holding, 80);

                if (env('PLATFORM_NAME') !== 'windows') {
                    //SFTP
                    Storage::disk('sftp')->put('/' . $listItem->gambar_holding, $img);
                } else {
                    Storage::disk('windows_uploads')->put('/' . $listItem->gambar_holding, $img);
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
        $log_app->request =  "Store Anggota Holding";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Helper';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function deleteSideList($id, Request $req)
    {
        try {
            $dt_list_item =  SideListModel::destroy($id);

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
        $log_app->request =  "Delete Side List";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Helper';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function insertApiBerita()
    {

        $getNews = IfgNewsModel::select('news_table_id')->get()->toArray();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://connect.ifg.id/api/berita/edii/getNewsList',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'api_key=%242a%2410%24N719adrYJ0DU4JviiOovdOrs1ciyCRxoKosg1ZwQ.fv.PxnrkFLH6',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);
        $data = json_decode($response);

        foreach ($data->content as $dataApi) {

            if (in_array($dataApi->news_table_id,  array_column($getNews, 'news_table_id'))) {
                $ifg_news                           = IfgNewsModel::where('news_table_id', $dataApi->news_table_id)->first();
                $ifg_news->news_table_id            = $dataApi->news_table_id;
                $ifg_news->mnc_table_id             = $dataApi->mnc_table_id;
                $ifg_news->news_title               = $dataApi->news_title;
                $ifg_news->news_title_english       = $dataApi->news_title_english;
                $ifg_news->news_highlight           = $dataApi->news_highlight;
                $ifg_news->news_highlight_english   = $dataApi->news_highlight_english;
                $ifg_news->news_urlmovie            = $dataApi->news_urlmovie;
                $ifg_news->news_imagepreview        = $dataApi->news_imagepreview;
                $ifg_news->news_image               = $dataApi->news_image;
                $ifg_news->news_urlstatus           = $dataApi->news_urlstatus;
                $ifg_news->news_url                 = $dataApi->news_url;
                $ifg_news->news_prioritystatus      = $dataApi->news_prioritystatus;
                $ifg_news->keterangan_prioritas     = $dataApi->keterangan_prioritas;
                $ifg_news->entry_date               = $dataApi->entry_date;
                $ifg_news->entry_id                 = $dataApi->entry_id;
                $ifg_news->entry_name               = $dataApi->entry_name;
                $ifg_news->foto_entry               = $dataApi->foto_entry;
                $ifg_news->kategori                 = $dataApi->kategori;
                $ifg_news->save();
            } else {
                $ifg_news = new IfgNewsModel();
                $ifg_news->news_table_id            = $dataApi->news_table_id;
                $ifg_news->mnc_table_id             = $dataApi->mnc_table_id;
                $ifg_news->news_title               = $dataApi->news_title;
                $ifg_news->news_title_english       = $dataApi->news_title_english;
                $ifg_news->news_highlight           = $dataApi->news_highlight;
                $ifg_news->news_highlight_english   = $dataApi->news_highlight_english;
                $ifg_news->news_urlmovie            = $dataApi->news_urlmovie;
                $ifg_news->news_imagepreview        = $dataApi->news_imagepreview;
                $ifg_news->news_image               = $dataApi->news_image;
                $ifg_news->news_urlstatus           = $dataApi->news_urlstatus;
                $ifg_news->news_url                 = $dataApi->news_url;
                $ifg_news->news_prioritystatus      = $dataApi->news_prioritystatus;
                $ifg_news->keterangan_prioritas     = $dataApi->keterangan_prioritas;
                $ifg_news->entry_date               = $dataApi->entry_date;
                $ifg_news->entry_id                 = $dataApi->entry_id;
                $ifg_news->entry_name               = $dataApi->entry_name;
                $ifg_news->foto_entry               = $dataApi->foto_entry;
                $ifg_news->kategori                 = $dataApi->kategori;
                $ifg_news->save();
            }
        }

        foreach ($getNews as $gn) {
            if (!in_array($gn['news_table_id'],  array_column($data->content, 'news_table_id')))
                IfgNewsModel::where('news_table_id', $gn['news_table_id'])->delete();
        }
    }

    public function insertApiGallery()
    {

        $getGallery = IfgGaleryModel::select('dgallery_table_id')->get()->toArray();


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://connect.ifg.id/api/gallery/edii/getGalleryList',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'api_key=%242a%2410%24N719adrYJ0DU4JviiOovdOrs1ciyCRxoKosg1ZwQ.fv.PxnrkFLH6',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);
        $data = json_decode($response);
        echo json_encode($data->content);die();
        foreach ($data->content as $dataApi) {

            if (in_array($dataApi->dgallery_table_id,  array_column($getGallery, 'dgallery_table_id'))) {
                $ifg_gallery                                = IfgGaleryModel::where('dgallery_table_id', $dataApi->dgallery_table_id)->first();
                $ifg_gallery->hgallery_table_id             = $dataApi->hgallery_table_id;
                $ifg_gallery->hgallery_title                = $dataApi->hgallery_title;
                $ifg_gallery->hgallery_title_english        = $dataApi->hgallery_title_english;
                $ifg_gallery->hgallery_highlight            = $dataApi->hgallery_highlight;
                $ifg_gallery->hgallery_highlight_english    = $dataApi->hgallery_highlight_english;
                $ifg_gallery->hgallery_text                 = $dataApi->hgallery_text;
                $ifg_gallery->hgallery_text_english         = $dataApi->hgallery_text_english;
                $ifg_gallery->hgallery_type                 = $dataApi->hgallery_type;
                $ifg_gallery->keterangan_hgallery_type      = $dataApi->keterangan_hgallery_type;
                $ifg_gallery->hgallery_imagepreview         = $dataApi->hgallery_imagepreview;
                $ifg_gallery->hgallery_urlsource            = $dataApi->hgallery_urlsource;
                $ifg_gallery->hgallery_prioritystatus       = $dataApi->hgallery_prioritystatus;
                $ifg_gallery->keterangan_prioritas          = $dataApi->keterangan_prioritas;
                $ifg_gallery->entry_date                    = $dataApi->entry_date;
                $ifg_gallery->entry_id                      = $dataApi->entry_id;
                $ifg_gallery->dgallery_table_id             = $dataApi->dgallery_table_id;
                $ifg_gallery->dgallery_imagepreview         = $dataApi->dgallery_imagepreview;
                $ifg_gallery->dgallery_image                = $dataApi->dgallery_image;
                $ifg_gallery->dgallery_description          = $dataApi->dgallery_description;
                $ifg_gallery->urutan                        = $dataApi->urutan;
                //$ifg_gallery->entry_name = $dataApi->entry_name;
                $ifg_gallery->foto_entry                    = $dataApi->foto_entry;
                $ifg_gallery->save();
            } else {
                $ifg_gallery = new IfgGaleryModel();
                $ifg_gallery->hgallery_table_id             = $dataApi->hgallery_table_id;
                $ifg_gallery->hgallery_title                = $dataApi->hgallery_title;
                $ifg_gallery->hgallery_title_english        = $dataApi->hgallery_title_english;
                $ifg_gallery->hgallery_highlight            = $dataApi->hgallery_highlight;
                $ifg_gallery->hgallery_highlight_english    = $dataApi->hgallery_highlight_english;
                $ifg_gallery->hgallery_text                 = $dataApi->hgallery_text;
                $ifg_gallery->hgallery_text_english         = $dataApi->hgallery_text_english;
                $ifg_gallery->hgallery_type                 = $dataApi->hgallery_type;
                $ifg_gallery->keterangan_hgallery_type      = $dataApi->keterangan_hgallery_type;
                $ifg_gallery->hgallery_imagepreview         = $dataApi->hgallery_imagepreview;
                $ifg_gallery->hgallery_urlsource            = $dataApi->hgallery_urlsource;
                $ifg_gallery->hgallery_prioritystatus       = $dataApi->hgallery_prioritystatus;
                $ifg_gallery->keterangan_prioritas          = $dataApi->keterangan_prioritas;
                $ifg_gallery->entry_date                    = $dataApi->entry_date;
                $ifg_gallery->entry_id                      = $dataApi->entry_id;
                $ifg_gallery->dgallery_table_id             = $dataApi->dgallery_table_id;
                $ifg_gallery->dgallery_imagepreview         = $dataApi->dgallery_imagepreview;
                $ifg_gallery->dgallery_image                = $dataApi->dgallery_image;
                $ifg_gallery->dgallery_description          = $dataApi->dgallery_description;
                $ifg_gallery->urutan                        = $dataApi->urutan;
                //$ifg_gallery->entry_name = $dataApi->entry_name;
                $ifg_gallery->foto_entry                    = $dataApi->foto_entry;
                $ifg_gallery->save();
            }
        }

        foreach ($getGallery as $gn) {
            if (!in_array($gn['dgallery_table_id'],  array_column($data->content, 'dgallery_table_id')))
                IfgGaleryModel::where('dgallery_table_id', $gn['dgallery_table_id'])->delete();
        }
    }
    public function insertApiNews()
    {

        $getNews = IfgNewsModel::select('news_table_id')->get()->toArray();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://connect.ifg.id/api/berita/edii/getNewsList',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'api_key=%242a%2410%24N719adrYJ0DU4JviiOovdOrs1ciyCRxoKosg1ZwQ.fv.PxnrkFLH6',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);
        $data = json_decode($response);
        dd($data);

        foreach ($data->content as $dataApi) {

            if (in_array($dataApi->news_table_id,  array_column($getNews, 'news_table_id'))) {
                $ifg_news                           = IfgNewsModel::where('news_table_id', $dataApi->news_table_id)->first();
                $ifg_news->news_table_id            = $dataApi->news_table_id;
                $ifg_news->mnc_table_id             = $dataApi->mnc_table_id;
                $ifg_news->news_title               = $dataApi->news_title;
                $ifg_news->news_title_english       = $dataApi->news_title_english;
                $ifg_news->news_highlight           = $dataApi->news_highlight;
                $ifg_news->news_highlight_english   = $dataApi->news_highlight_english;
                $ifg_news->news_urlmovie            = $dataApi->news_urlmovie;
                $ifg_news->news_imagepreview        = $dataApi->news_imagepreview;
                $ifg_news->news_image               = $dataApi->news_image;
                $ifg_news->news_urlstatus           = $dataApi->news_urlstatus;
                $ifg_news->news_url                 = $dataApi->news_url;
                $ifg_news->news_prioritystatus      = $dataApi->news_prioritystatus;
                $ifg_news->keterangan_prioritas     = $dataApi->keterangan_prioritas;
                $ifg_news->entry_date               = $dataApi->entry_date;
                $ifg_news->entry_id                 = $dataApi->entry_id;
                $ifg_news->entry_name               = $dataApi->entry_name;
                $ifg_news->foto_entry               = $dataApi->foto_entry;
                $ifg_news->kategori                 = $dataApi->kategori;
                $ifg_news->save();
            } else {
                $ifg_news = new IfgNewsModel();
                $ifg_news->news_table_id            = $dataApi->news_table_id;
                $ifg_news->mnc_table_id             = $dataApi->mnc_table_id;
                $ifg_news->news_title               = $dataApi->news_title;
                $ifg_news->news_title_english       = $dataApi->news_title_english;
                $ifg_news->news_highlight           = $dataApi->news_highlight;
                $ifg_news->news_highlight_english   = $dataApi->news_highlight_english;
                $ifg_news->news_urlmovie            = $dataApi->news_urlmovie;
                $ifg_news->news_imagepreview        = $dataApi->news_imagepreview;
                $ifg_news->news_image               = $dataApi->news_image;
                $ifg_news->news_urlstatus           = $dataApi->news_urlstatus;
                $ifg_news->news_url                 = $dataApi->news_url;
                $ifg_news->news_prioritystatus      = $dataApi->news_prioritystatus;
                $ifg_news->keterangan_prioritas     = $dataApi->keterangan_prioritas;
                $ifg_news->entry_date               = $dataApi->entry_date;
                $ifg_news->entry_id                 = $dataApi->entry_id;
                $ifg_news->entry_name               = $dataApi->entry_name;
                $ifg_news->foto_entry               = $dataApi->foto_entry;
                $ifg_news->kategori                 = $dataApi->kategori;
                $ifg_news->save();
            }

            $curlGetDetailNews = curl_init();

            curl_setopt_array($curlGetDetailNews, array(
                CURLOPT_URL => 'https://connect.ifg.id/api/berita/edii/getNewsListDetail',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'api_key=%242a%2410%24N719adrYJ0DU4JviiOovdOrs1ciyCRxoKosg1ZwQ.fv.PxnrkFLH6&news_table=' . $dataApi->news_table_id,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded',
                ),
            ));

            $responseGetDetailNews = curl_exec($curlGetDetailNews);
            $dataGetDetailNews = json_decode($responseGetDetailNews);

            foreach ($dataGetDetailNews->content as $dataApi) {
                $ifg_news_detail                     = IfgNewsModel::where('news_table_id', $dataApi->news_table_id)->first();
                $ifg_news_detail->news_text          = $dataApi->news_text;
                $ifg_news_detail->news_text_english  = $dataApi->news_text_english;
                $ifg_news_detail->save();
            }
        }

        foreach ($getNews as $gn) {
            if (!in_array($gn['news_table_id'],  array_column($data->content, 'news_table_id')))
                IfgNewsModel::where('news_table_id', $gn['news_table_id'])->delete();
        }
    }

    public function storeIg()
    {
        $token = TokenIg::find(1);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.instagram.com/me/media?fields=id,caption,media_type,media_url,username,timestamp&access_token=' . $token->token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Cookie: csrftoken=j9uh4LXOKsNSFDQsMZIdzOjmjoXhFBCj; ig_did=AA2AEA80-0A7D-45B1-8D9A-B2B485DE509B; ig_nrcb=1; mid=ZBgJlQAEAAHnBMam3Sg3ZhlascxF'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response);
        $i = 0;
        PostIg::truncate();
        foreach ($data->data as $dataApi) {
            $i += 1;
            if ($i <= 3) {
                $storePost = new PostIg();
                $storePost->id = $dataApi->id;
                $storePost->caption = $dataApi->caption;
                $storePost->media_type = $dataApi->media_type;
                $storePost->media_url = $dataApi->media_url;
                $storePost->username = $dataApi->username;
                $storePost->timestamp = date_format(date_create($dataApi->timestamp), "Y/m/d H:i:s");

                $storePost->save();
            }
        }
    }

    public function refreshTokenIg()
    {
        $token = TokenIg::find(1);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token=' . $token->token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Cookie: csrftoken=j9uh4LXOKsNSFDQsMZIdzOjmjoXhFBCj; ig_did=AA2AEA80-0A7D-45B1-8D9A-B2B485DE509B; ig_nrcb=1; mid=ZBgJlQAEAAHnBMam3Sg3ZhlascxF'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response);

        $token->token = $data->access_token;
        $token->save();
    }


    function phpinfo()
    {
        phpinfo();
    }

    function newsDetail()
    {
        // $ifg_news = new IfgNewsModel();
        // $ifg_news->news_table_id = '82';

        $curl2 = curl_init();

        curl_setopt_array($curl2, array(
            CURLOPT_URL => 'https://connect.ifg.id/api/berita/edii/getNewsListDetail',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'api_key=%242a%2410%24N719adrYJ0DU4JviiOovdOrs1ciyCRxoKosg1ZwQ.fv.PxnrkFLH6&news_table=' . 82,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
            ),
        ));

        $response2 = curl_exec($curl2);
        $data2 = json_decode($response2, true);
        dd($data2);

    }

    function scriptStripper($input)
    {
        return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $input);
    }
}

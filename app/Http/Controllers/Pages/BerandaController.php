<?php

namespace App\Http\Controllers\Pages;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Http\Requests\AnggotaHolding;
use App\Http\Requests\SliderRequest;
use App\Http\Requests\UpdateAnggotaHolding;
use App\Http\Requests\UpdateSliderRequest;
use App\Http\Requests\YoutubeRequest;
use App\Models\AnggotaHoldingModel;
use App\Models\ListItemModel;
use App\Models\CooperationModel;
use App\Models\LogApp;
use App\Models\MenuModel;
use App\Models\User;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use PDO;

class BerandaController extends Controller
{
    public function index()
    {
        $data['title']      = 'IFG | Pages';
        $data['title_page'] = 'Beranda | Beranda';
        $data['menu']       = MenuModel::all();
        $data['dataMenu']  = ListItemModel::where('id_menu', '1')->orderBy('item_order', 'ASC')->get();
        $data['dataMenuVid']  = ListItemModel::where('id_menu', '1')->where('ifg_pages_content.id_menu', '=', 1)->where('item_file', 'LIKE', '%mp4')->first();
        $data['dataYoutube']  = ListItemModel::where('id_menu', '1')->where('id_pages_content_order', '3')->first();
        $data['dataHolding']  = AnggotaHoldingModel::all();
        $data['dataHoldingType']  = MenuModel::where('parent_id', 9)->get();
        return view('pages.berandav2', $data);
    }

    public function storeFirstSlider(SliderRequest $req)
    {
        try {

            //check order list
            if ($req->id_content_order == 1) {
                $chkOrder = ListItemModel::where('id_menu', '1')->where('id_pages_content_order', '1')->get();
            } else {
                $chkOrder = ListItemModel::where('id_menu', '1')->where('id_pages_content_order', '2')->get();
            }

            if (count($chkOrder) <= 0) {
                $order = 1;
            } else {
                $order = count($chkOrder) + 1;
            }

            $listItem = new ListItemModel();
            $listItem->id_menu = $req->id_content;
            $listItem->id_pages_content_order = $req->id_content_order;
            $listItem->item_extras = '-';
            $listItem->item_title = (new HelperController)->scriptStripper($req->title);
            $listItem->item_body = (new HelperController)->scriptStripper($req->description);
            $listItem->item_title_en = (new HelperController)->scriptStripper($req->title_en);
            $listItem->item_body_en = (new HelperController)->scriptStripper($req->description_en);
            $listItem->item_link = (new HelperController)->scriptStripper($req->url);
            $listItem->item_order = $order;
            $listItem->insert_by = session()->get('id');
            $listItem->updated_by = session()->get('id');
            $listItem->updated_by_ip = $req->ip();
            if (!is_null($req->file('item_file'))) {
                $ext                    =  $req->file('item_file')->extension();
                $listItem->item_file    = uniqid() . '.' . $req->file('item_file')->getClientOriginalExtension();
                if ($ext == 'mp4') {
                    $req->file('item_file')->storeAs(
                        'public/',
                        $listItem->item_file
                    );
                    if (env('PLATFORM_NAME') !== 'windows') {
                        //SFTP
                        Storage::disk('sftp')->put('/' . $listItem->item_file, file_get_contents($req->file('item_file')));
                    } else {
                        Storage::disk('windows_uploads')->put('/' . $listItem->item_file, file_get_contents($req->file('item_file')));
                    }
                } else {
                    $manager                = new ImageManager();
                    $img                    = $manager->make($req->file('item_file')->getPathname());
                    if ($ext == 'png' || $ext == 'PNG') {
                        $listItem->item_file = uniqid() . '.' . 'webp';
                    }


                    $img->save(public_path('storage') . '/'  . $listItem->item_file, 80);

                    if (env('PLATFORM_NAME') !== 'windows') {
                        //SFTP
                        Storage::disk('sftp')->put('/' . $listItem->item_file, $img);
                    } else {
                        Storage::disk('windows_uploads')->put('/' . $listItem->item_file, $img);
                    }
                }

                // if ($ext == 'png' || $ext == 'PNG') {
                //     $listItem->item_file = uniqid() . '.' . 'webp';
                // }

                // $img->save(public_path('storage') . '/'  . $listItem->item_file, 80);
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
        $log_app->request =  "Insert Slider";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Beranda';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();

        return json_encode($response);
    }


    public function updatePassword(Request $request)
    {

        try {
            $this->validate($request, [
                'old_password' => 'required',
                'new_password' => 'required|confirmed|min:6',
            ], [
                'new_password.confirmed' => "Password tidak sama"
            ]);

            $userDetail = User::find(session()->get('id'));
            // echo $request->old_password;die();

            if (Hash::check($request->old_password, $userDetail->password)) {
                $userDetail->password = Hash::make($request->new_password);

                if ($userDetail->save()) {
                    $response = [
                        'status' => 'success',
                        'message' => 'Data berhasil diperbaharui',
                    ];
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => 'Data gagal diperbaharui, Ulangi kembali',
                    ];
                }
            } else {
                $response = [
                    'status' => 'failed',
                    'message' => "Password lama tidak sama.",
                ];
            }
        } catch (Exception $e) {
            $response = [
                'status' => 'failed',
                'message' => "Password lama tidak sama.",
            ];
        }

        $log_app = new LogApp();
        $log_app->method = $request->method();
        $log_app->request =  "Update Password";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'All';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $request->ip();
        $log_app->save();

        return json_encode($response);
    }

    public function activateVideo($id, $param, Request $req)
    {
        try {


            $video = ListItemModel::find($id);
            $video->item_extras = $param;
            $video->updated_by = session()->get('id');
            $video->updated_by_ip = $req->ip();
            if ($video->save()) {
                $response = [
                    'status' => 'success',
                    'message' => 'Data berhasil diperbaharui',
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Data gagal diperbaharui, Ulangi kembali',
                ];
            }
        } catch (Exception $e) {
            $response = [
                'status' => 'failed',
                'message' => "Terjadi Kesalahan pada system.",
            ];
        }

        $log_app = new LogApp();
        $log_app->method = $req->method();
        $log_app->request =  "Activate / Deadactivate Video Slider";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Beranda';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();


        return json_encode($response);
    }


    public function deleteListItem($id, Request $request)
    {
        try {
            $check = ListItemModel::where('id', $id)->first();
            $dt_list_item =  ListItemModel::destroy($id);

            // if ($check->id != null) {
                $reOrder = ListItemModel::where('id_menu', $check->id_menu)->where('id_pages_content_order', $check->id_pages_content_order)->get();
                if($reOrder){
                    foreach ($reOrder as $key => $orderItem) {
                        $listItem = ListItemModel::find($orderItem->id);
                        $listItem->item_order = $key + 1;
                        $listItem->save();
                    }
                }
            // }

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
        $log_app->method = $request->method();
        $log_app->request =  "Delete List Item";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Beranda';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $request->ip();
        $log_app->save();

        return json_encode($response);
    }

    public function editListItem($id)
    {
        try {
            $dt_list_item =  ListItemModel::find($id);
            $chkOrder = ListItemModel::where('id_pages_content_order', $dt_list_item->id_pages_content_order)->where('id_menu', '1')->get();


            if ($dt_list_item->id_pages_content_order != 4) {
                $output = FacadesView::make("components.slider-component")
                    ->with("dt_item", $dt_list_item)
                    ->with("chkOrder", $chkOrder)
                    ->with("route", route('beranda-detail-item-update'))
                    ->with("formId", "slide-tab-edit")
                    ->with("formMethod", "PUT")
                    ->render();
            } else {
                $output = FacadesView::make("components.anggota-holding-component")
                    ->with("dt_item", $dt_list_item)
                    ->with("chkOrder", $chkOrder)
                    ->with("route", route('beranda-update-anggota-holding'))
                    ->with("formId", "slide-tab-edit")
                    ->with("formMethod", "PUT")
                    ->render();
            }

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

    public function editListAnggotaHolding($id)
    {
        try {
            $dt_list_item =  AnggotaHoldingModel::find($id);
            $chkOrder = AnggotaHoldingModel::all();
            $dataHoldingType  = MenuModel::where('parent_id', 9)->get();

            $output = FacadesView::make("components.anggota-holding-component")
                ->with("dt_item", $dt_list_item)
                ->with("chkOrder", $chkOrder)
                ->with("dataHoldingType", $dataHoldingType)
                ->with("route", route('beranda-update-anggota-holding'))
                ->with("formId", "slide-tab-edit")
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

    public function updateSlider(UpdateSliderRequest $req)
    {
        try {
            $changeOrder = ListItemModel::where('id_pages_content_order', $req->pages_order)->where('id_menu', '1')->where('item_order', $req->upt_item_order)->first();
            $order = $changeOrder->item_order;

            $changeOrder->item_order = $req->upt_current_order;
            $changeOrder->updated_by = session()->get('id');
            $changeOrder->updated_by_ip = $req->ip();
            $changeOrder->save();

            $listItem = ListItemModel::find($req->upt_id);
            $listItem->item_title = (new HelperController)->scriptStripper($req->upt_title);
            $listItem->item_body = (new HelperController)->scriptStripper($req->upt_description);
            $listItem->item_title_en = (new HelperController)->scriptStripper($req->upt_title_en);
            $listItem->item_body_en = (new HelperController)->scriptStripper($req->upt_description_en);
            $listItem->item_link = (new HelperController)->scriptStripper($req->upt_url);
            $listItem->item_order = $order;
            $listItem->updated_by = session()->get('id');
            $listItem->updated_by_ip = $req->ip();
            if (!is_null($req->file('upt_item_file'))) {
                $ext                    =  $req->file('upt_item_file')->extension();
                $listItem->item_file    = uniqid() . '.' . $req->file('upt_item_file')->getClientOriginalExtension();
                if ($ext == 'mp4') {
                    $req->file('upt_item_file')->storeAs(
                        'public/',
                        $listItem->item_file
                    );

                    if (env('PLATFORM_NAME') !== 'windows') {
                        //SFTP
                        Storage::disk('sftp')->put('/' . $listItem->item_file, file_get_contents($req->file('upt_item_file')));
                    } else {
                        Storage::disk('windows_uploads')->put('/' . $listItem->item_file, file_get_contents($req->file('upt_item_file')));
                    }
                } else {
                    $manager                = new ImageManager();
                    $img                    = $manager->make($req->file('upt_item_file')->getPathname());
                    if ($ext == 'png' || $ext == 'PNG') {
                        $listItem->item_file = uniqid() . '.' . 'webp';
                    }

                    $img->save(public_path('storage') . '/'  . $listItem->item_file, 80);

                    if (env('PLATFORM_NAME') !== 'windows') {
                        //SFTP
                        Storage::disk('sftp')->put('/' . $listItem->item_file, $img);
                    } else {
                        Storage::disk('windows_uploads')->put('/' . $listItem->item_file, $img);
                    }
                }

                // if ($ext == 'png' || $ext == 'PNG') {
                //     $listItem->upt_item_file = uniqid() . '.' . 'webp';
                // }

                // $img->save(public_path('storage') . '/'  . $listItem->upt_item_file, 80);
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
        $log_app->request =  "Update Slider";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Beranda';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function updateSliderVideo(Request $req)
    {
        try {
            $this->validate($req, [
                'item_file' => 'required|mimes:mp4|max:100000'
            ], [
                'item_file.required' => "Video belum di upload"
            ]);

            if ($req->id_content_order == 1) {
                $chkOrder = ListItemModel::where('id_menu', '1')->where('id_pages_content_order', '1')->get();
            } else {
                $chkOrder = ListItemModel::where('id_menu', '1')->where('id_pages_content_order', '2')->get();
            }

            if (count($chkOrder) <= 0) {
                $order = 1;
            } else {
                $order = count($chkOrder) + 1;
            }

            if (!is_null($req->file('item_file'))) {
                $ext                    =  $req->file('item_file')->extension();
                $filename    = uniqid() . '.' . $req->file('item_file')->getClientOriginalExtension();
                if ($ext == 'mp4') {
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
                }
            }

            $listItem = ListItemModel::updateOrCreate([
                'id' => base64_decode($req->idSp)
            ], [
                'item_file' => $filename ?? '',
                'id_menu' => $req->id_content,
                'id_pages_content_order' => $req->id_content_order,
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
        } catch (Exception $e) {
            $response = [
                'status' => 'failed',
                'message' => "Terjadi Kesalahan pada sistem : " . $e->getMessage(),
            ];
        }

        $log_app = new LogApp();
        $log_app->method = $req->method();
        $log_app->request =  "Update Video Slider";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Beranda';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function storeYoutube(YoutubeRequest $req)
    {
        try {

            //check order list
            // $chkOrder = ListItemModel::where('id_pages_content_order', '1')->where('id_pages_content', '1')->get();


            $listItem = ListItemModel::updateOrCreate(
                [
                    'id' => base64_decode($req->idSPA)
                ],
                [
                    'id_menu' => $req->id_content,
                    'id_pages_content_order' => $req->id_content_order,
                    'item_extras' => '-',
                    'item_title' => (new HelperController)->scriptStripper($req->title_youtube),
                    'item_body' => (new HelperController)->scriptStripper($req->description_youtube),
                    'item_title_en' => (new HelperController)->scriptStripper($req->title_youtube_en),
                    'item_body_en' => (new HelperController)->scriptStripper($req->description_youtube_en),
                    'item_link' => (new HelperController)->scriptStripper($req->url_youtube),
                    'item_order' => '1',
                    'insert_by' => session()->get('id'),
                    'updated_by' => session()->get('id'),
                    'updated_by_ip' => $req->ip()
                ]
            );
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
        $log_app->request =  "Store Youtube";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Beranda';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();

        return json_encode($response);
    }

    public function storeAnggotaHolding(AnggotaHolding $req)
    {
        try {

            //check order list
            $chkOrder = ListItemModel::where('id_menu', '1')->where('id_pages_content_order', '4')->get();

            if (count($chkOrder) <= 0) {
                $order = 1;
            } else {
                $order = count($chkOrder) + 1;
            }

            $listItem = new ListItemModel();
            $listItem->id_menu = $req->id_content;
            $listItem->id_pages_content_order = $req->id_content_order;
            $listItem->item_extras = '-';
            $listItem->item_title = '-';
            $listItem->item_body = '-';
            $listItem->item_link = (new HelperController)->scriptStripper($req->url);
            $listItem->item_order = $order;
            $listItem->insert_by = session()->get('id');
            $listItem->updated_by = session()->get('id');
            $listItem->updated_by_ip = $req->ip();
            if (!is_null($req->file('item_file'))) {
                $manager                = new ImageManager();
                $ext                    =  $req->file('item_file')->extension();
                $img                    = $manager->make($req->file('item_file')->getPathname());
                $listItem->item_file    = uniqid() . '.' . $req->file('item_file')->getClientOriginalExtension();

                if ($ext == 'png' || $ext == 'PNG') {
                    $listItem->item_file = uniqid() . '.' . 'webp';
                }

                $img->save(public_path('storage') . '/'  . $listItem->item_file, 80);

                if (env('PLATFORM_NAME') !== 'windows') {
                    //SFTP
                    Storage::disk('sftp')->put('/' . $listItem->item_file, $img);
                } else {
                    Storage::disk('windows_uploads')->put('/' . $listItem->item_file, $img);
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
        $log_app->pages = 'Beranda';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function updateAnggotaHolding(UpdateAnggotaHolding $req)
    {
        try {

            //check order list
            $changeOrder = AnggotaHoldingModel::where('order', $req->upt_item_order)->first();
            $order = $changeOrder->order;
            $changeOrder->order = $req->upt_current_order;
            $changeOrder->updated_by = session()->get('id');
            $changeOrder->updated_by_ip = $req->ip();
            $changeOrder->save();

            $listItem = AnggotaHoldingModel::find($req->upt_id);
            $listItem->nama_holding = (new HelperController)->scriptStripper($req->subs_name ?? '-');
            $listItem->jenis_holding = (new HelperController)->scriptStripper($req->jenis_holding ?? '-');
            $listItem->url = (new HelperController)->scriptStripper($req->upt_url);
            $listItem->order = $order;
            $listItem->updated_by = session()->get('id');
            $listItem->updated_by_ip = $req->ip();
            if (!is_null($req->file('upt_item_file'))) {
                $ext                    =  $req->file('upt_item_file')->extension();
                $listItem->gambar_holding    = uniqid() . '.' . $req->file('upt_item_file')->getClientOriginalExtension();
                if ($ext == 'mp4') {
                    $req->file('item_file')->storeAs(
                        'public/',
                        $listItem->gambar_holding
                    );

                    if (env('PLATFORM_NAME') !== 'windows') {
                        //SFTP
                        Storage::disk('sftp')->put('/' . $listItem->gambar_holding, file_get_contents($req->file('upt_item_file')));
                    } else {
                        Storage::disk('windows_uploads')->put('/' . $listItem->gambar_holding, file_get_contents($req->file('upt_item_file')));
                    }
                } else {
                    $manager                = new ImageManager();
                    $img                    = $manager->make($req->file('upt_item_file')->getPathname());
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
        $log_app->request =  "Update Anggota Holding";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Beranda';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();

        return json_encode($response);
    }

    public function fileStore(Request $request)
    {
        // $image = $request->file('file');
        // $imageName = $image->getClientOriginalName();
        // $image->move(public_path('images'),$imageName);
        // return response()->json(['success'=>$imageName]);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.instagram.com/oauth/access_token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'client_id=1960043931022121&code=AQD3cFkXOi4J3O1-efj7qIsCi5p2x4m-2WAEzStpUsJ55IYYfjJeChY2X0feY6iNE-54BHJY2oTjNjKmSG1Ybe59OtRUeeWfY6b1F6hRzJh5ewHIuA4-WEyNhWQ0ORyi9oDPYTiVp5H8EdFxmm6i_ZshtxAjdDuqW_pYMCsU_uX0GHJ1dhFFRxYi6rhcJ3_WO0dynkzo0hVukw2IYTE_39xRdE5t1yh0ajLN_EkTDYEnpg%23_&client_secret=fabfcaaa0788b037032c5b0d44030622&grant_type=authorization_code&redirect_uri=https%3A%2F%2Fwww.google.com%2F',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Cookie: csrftoken=j9uh4LXOKsNSFDQsMZIdzOjmjoXhFBCj; ig_did=AA2AEA80-0A7D-45B1-8D9A-B2B485DE509B; ig_nrcb=1; mid=ZBgJlQAEAAHnBMam3Sg3ZhlascxF'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }


}

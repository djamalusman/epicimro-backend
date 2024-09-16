<?php


namespace App\Http\Controllers\pages;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Http\Requests\ListItemDetail as RequestsListItemDetail;
use Carbon\Carbon;
use App\Models\LogApp;
use App\Models\MenuModel;
use App\Models\TestimonialsModel;
use App\Models\TestimonialsDetailModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Intervention\Image\ImageManager;
use PDO;

class YotubeNewsController extends Controller
{
    public function index($id)
    {
        $data['menus'] = MenuModel::find(base64_decode($id));
        $data['title']      = 'Video | Pages';
        $data['title_page'] = 'Pelatihan / Kursus | ' . $data['menus']->menu_name;
        $data['menu']       = MenuModel::all();

        return view('pages.youtubenews', $data);
    }

    public function getDropdownVideo() {
        $filters = DB::table('d_testimonials')
        ->leftJoin('ifg_menu', 'ifg_menu.id', '=', 'd_testimonials.id_menu')
        ->leftJoin('m_category_testimonials', 'm_category_testimonials.id', '=', 'd_testimonials.id_category')
            ->select('d_testimonials.nama', DB::raw('CASE
            WHEN d_testimonials.status = 1 THEN "Publish"
            WHEN d_testimonials.status = 2 THEN "Pending"
            WHEN d_testimonials.status = 3 THEN "Non Publish"
            WHEN d_testimonials.status = 4 THEN "Kadaluarsa"
            ELSE "Unknown"
        END as status_training'),'ifg_menu.menu_name','m_category_testimonials.nama as category')
        ->where('d_testimonials.id_category',29) // Pilih kolom yang dibutuhkan
        ->where('d_testimonials.id_menu',35)
            ->distinct()
            ->get();
        return response()->json($filters);
    }

    public function getDataVideo(Request $request) {
        // Membuat query untuk tabel training_course_detail

        $query = DB::table('d_testimonials')
        ->join('ifg_menu', 'ifg_menu.id', '=', 'd_testimonials.id_menu')
        ->join('m_category_testimonials', 'm_category_testimonials.id', '=', 'd_testimonials.id_category')
        ->select('d_testimonials.*','ifg_menu.menu_name','m_category_testimonials.nama as category')
        ->where('d_testimonials.id_category',29) // Pilih kolom yang dibutuhkan
        ->where('d_testimonials.id_menu',35) ; // Pilih kolom yang dibutuhkan

        // Menerapkan filter berdasarkan parameter yang tersedia
        if ($request->has('title') && $request->title != '') {
            $query->where('d_testimonials.nama', 'LIKE', '%' . $request->title . '%');
        }
        if ($request->has('category') && $request->category != '') {
            $query->where('m_category_testimonials.nama', 'LIKE', '%' . $request->category . '%');
        }


        // Mengambil hasil query
        $courses = $query->get();
        // Mengembalikan data dalam format JSON
        return response()->json($courses);
    }

    public function getViewStoreVideo($id)
    {
        $data['menus'] = MenuModel::find(41);
        $data['title']      = 'Video';
        $data['title_page'] = 'Video | ' . $data['menus']->menu_name;
        $data['content'] = base64_decode($id);
        $data['menu']       = MenuModel::all();
        $data['liscategory'] =  DB::table('m_category_testimonials')
        ->select('m_category_testimonials.*')->get();
        return view('pages.youtubenews_store', $data);
    }

    public function storeVideo(Request $req)
    {

        try {
            //dd($req->all());
            $this->validate($req, [
                'category' => 'required',
                //'menu' => 'required',
            ], [
                'category.required' => 'Inputan category tidak boleh kosong',
                //'menu.required' => 'Inputan menu tidak boleh kosong',
            ]);
                $listItem = new TestimonialsModel();
                $listItem->nama             = $req->nama_testimoni;
                $listItem->id_menu          = $req->menuyoutebe;
                $listItem->id_category      = $req->category;
                $listItem->description      = (new HelperController)->scriptStripper( $req->description ?? '-');
                $listItem->status           = $req->status;
                $listItem->insert_by = session()->get('id');
                $listItem->updated_by = session()->get('id');
                $listItem->updated_by_ip = $req->ip();
                $listItem->save();
                //video
                if (!is_null($req->embedvideoytb)) {
                    foreach ($req->embedvideoytb as $embedvideoytb) {
                        $dataembedvideo = new TestimonialsDetailModel();
                        $dataembedvideo->id_testimoni = $listItem->id;
                        $dataembedvideo->url = $embedvideoytb;
                        $dataembedvideo->status           = $req->status;
                        $dataembedvideo->insert_by = session()->get('id');
                        $dataembedvideo->updated_by = session()->get('id');
                        $dataembedvideo->updated_by_ip = $req->ip();
                        $dataembedvideo->save();
                    }
                }



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
        $log_app->request =  "Update Traning Course";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Traning';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }


    public function getVieweditVideo($id)
    {
        $data['menus'] = MenuModel::find(41);
        $data['title']      = 'Video';
        $data['title_page'] = 'Video | ' . $data['menus']->menu_name;
        $data['content'] = base64_decode($id);
        $data['menu']       = MenuModel::all();
        $decodedId = base64_decode($id);
        $data['liscategory'] =  DB::table('m_category_testimonials')
        ->select('m_category_testimonials.*')->get();

        $data['embedvideo']=  TestimonialsDetailModel::where('id_testimoni',base64_decode($id))->get();

        $data['databyid'] = DB::table('d_testimonials')
            ->leftJoin('d_testimonials_video', 'd_testimonials_video.id_testimoni', '=', 'd_testimonials.id')
            ->select('d_testimonials.*', 'd_testimonials_video.url')
            ->where('d_testimonials.id', $decodedId)
            ->first();


        $data['iddtl']=base64_decode($id);

        return view('pages.youtubenews_edit', $data);
    }


    public function updateVideo(Request $req)
    {

        try {

            $this->validate($req, [
                'category' => 'required',
                //'menu' => 'required',
            ], [
                'category.required' => 'Inputan category tidak boleh kosong',
                //'menu.required' => 'Inputan menu tidak boleh kosong',
            ]);



            TestimonialsDetailModel::where('id_testimoni', $req->iddtl)->delete();

            $listItem = TestimonialsModel::find($req->iddtl);
                $listItem->nama             = $req->nama_testimoni;
                $listItem->id_menu          = $req->menuyoutebe;
                $listItem->id_category      = $req->category;
                $listItem->description      = (new HelperController)->scriptStripper( $req->description ?? '-');
                $listItem->status           = $req->status;
                $listItem->insert_by = session()->get('id');
                $listItem->updated_by = session()->get('id');
                $listItem->updated_by_ip = $req->ip();
                $listItem->save();
                //video
                if (!is_null($req->embedvideoytb)) {
                    foreach ($req->embedvideoytb as $embedvideoytb) {
                        $dataembedvideo = new TestimonialsDetailModel();
                        $dataembedvideo->id_testimoni = $listItem->id;
                        $dataembedvideo->url = $embedvideoytb;
                        $dataembedvideo->status           = $req->status;
                        $dataembedvideo->insert_by = session()->get('id');
                        $dataembedvideo->updated_by = session()->get('id');
                        $dataembedvideo->updated_by_ip = $req->ip();
                        $dataembedvideo->save();
                    }
                }


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
        $log_app->request =  "Update Traning Course";
        $log_app->response =  json_encode($response);
        $log_app->pages = 'Traning';
        $log_app->user_id = session()->get('id');
        $log_app->ip_address = $req->ip();
        $log_app->save();
        return json_encode($response);
    }

    public function removeEmbedVideo ($id)
    {
        TestimonialsDetailModel::where('id', $id)->delete();

        $response = [
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ];
        return json_encode($response);
    }



    public function viewPopUpVid($id)
    {

        try {
                $dt_list_item =  TestimonialsDetailModel::where('id_testimoni',$id)->first();

                $output = View::make("components.view-video")
                    ->with("dt_item", $dt_list_item)
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

    public function deleteDataVideo ($id)
    {
        TestimonialsModel::where('id', $id)->delete();
        TestimonialsDetailModel::where('id_testimoni', $id)->delete();

        $response = [
            'status' => 'success',
            'message' => 'Data berhasil dihapus'
        ];
        return json_encode($response);
    }
}

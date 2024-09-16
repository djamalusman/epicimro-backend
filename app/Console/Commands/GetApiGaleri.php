<?php

namespace App\Console\Commands;

use App\Models\IfgGaleryModel;
use Illuminate\Console\Command;

class GetApiGaleri extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getApi:Gallery';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get data Gallery';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
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
        return Command::SUCCESS;
    }
}

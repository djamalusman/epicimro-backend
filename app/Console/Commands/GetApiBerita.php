<?php

namespace App\Console\Commands;

use App\Models\IfgNewsModel;
use Illuminate\Console\Command;

class GetApiBerita extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getApi:News';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get data news';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
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

        return Command::SUCCESS;
    }
}

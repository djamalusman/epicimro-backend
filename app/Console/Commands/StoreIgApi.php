<?php

namespace App\Console\Commands;

use App\Models\PostIg;
use App\Models\TokenIg;
use Illuminate\Console\Command;

class StoreIgApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storeApi:storeIg';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store post ig limit 3';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
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
        return Command::SUCCESS;
    }
}

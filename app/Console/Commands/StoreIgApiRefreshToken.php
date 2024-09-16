<?php

namespace App\Console\Commands;

use App\Models\TokenIg;
use Illuminate\Console\Command;

class StoreIgApiRefreshToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storeApi:storeIgRefreshToken';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get ig refresh token';

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
        return Command::SUCCESS;
    }
}

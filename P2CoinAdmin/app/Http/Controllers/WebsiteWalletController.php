<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Common;

class WebsiteWalletController extends Controller
{
    //
    public function index() {
        $server_btc_wallet = env('SERVER_BTC_WALLET_ADDRESS');
        $server_eth_wallet = env('SERVER_ETH_WALLET_ADDRESS');
        $post_param = array('btc_address'=>$server_btc_wallet, 'eth_address'=>$server_eth_wallet);
        $api_link = "http://localhost:5003/api/v1/getwalletbalance/{$server_btc_wallet}/{$server_eth_wallet}";

        $ch = curl_init($api_link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_wallet_info = json_decode(curl_exec($ch));
        curl_close($ch);

        $model = new Common();
        $pricedata =$model->getCurrentPrice();
        $wallet_data = $model->getPendingWithdrawals();
//        dd($server_wallet_info);
        return view('websitewallet.index')->with(['price_data'=>$pricedata, 'wallet_data'=>$wallet_data,'btc_info'=>$server_wallet_info->btc,'eth_info'=>$server_wallet_info->eth, 'totalusers'=>$this->totalusers, 'volume'=>$this->volume]);
    }
}

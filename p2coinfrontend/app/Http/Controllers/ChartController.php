<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChartController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index() {
        $dateStr = date("M j,  Y");

        $ch = curl_init("https://api.coinmarketcap.com/v1/ticker/bitcoin/?convert=USD");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $btc_data_str = json_decode(curl_exec($ch));
        curl_close($ch);

        $ch = curl_init("https://api.coinmarketcap.com/v1/ticker/ethereum/?convert=USD");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $eth_data_str = json_decode(curl_exec($ch));
        curl_close($ch);

        $btc_data = $this->getArrayFromStdClass($btc_data_str[0]);
        $eth_data = $this->getArrayFromStdClass($eth_data_str[0]);
        return view('chart.index')->with(['dateStr'=>$dateStr,'btc_data'=>$btc_data,'eth_data'=>$eth_data]);

    }
    public function getchartdatabycoin( Request $request ) {
        header('Content-type:application/json');
        ( $request->coin == 'eth' ) ? $url = 'https://graphs.coinmarketcap.com/currencies/ethereum/' : $url = 'https://graphs.coinmarketcap.com/currencies/bitcoin/';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $coin_data = (curl_exec($ch));
        curl_close($ch);
        echo $coin_data;
        exit;
    }
    private function getArrayFromStdClass($stdClassObj) {
        $ret_arr = array('name'=>'', 'market_cap_usd'=>0, 'price_usd'=>0, 'available_supply'=>0,'percent_change_24h'=>0, '24h_volume_usd'=>0);
        if (!($stdClassObj)) return $ret_arr;
        foreach( $stdClassObj as $key=>$val ) {
            $ret_arr[$key] = $val;
        }
        return $ret_arr;
    }
}

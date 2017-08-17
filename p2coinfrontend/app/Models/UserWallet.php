<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWallet extends Model
{
    //
    protected $table = 'user_wallet';
    
    public function getUserWallet( $user_id, $wallet_type=null ) {
        if ( is_null($wallet_type) ) $wallet_type = 'btc';
        $row = self::all()->where('user_id', '=', $user_id)->where('wallet_type', '=', $wallet_type)->first();
        return $row->wallet_address;
    }
    public function getWalletInfo( $user_id, $wallet_type ) {
        if ( is_null($wallet_type) ) $wallet_type = 'btc';
        $row = self::all()->where('user_id', '=', $user_id)->where('wallet_type', '=', $wallet_type)->first();
        return $row;
    }
    public function getCurrentPrice() {
        $ch = curl_init("https://api.coinmarketcap.com/v1/ticker/bitcoin/?convert=USD");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $btc_data_str = json_decode(curl_exec($ch));
        curl_close($ch);


        $ch = curl_init("https://api.coinmarketcap.com/v1/ticker/ethereum/?convert=USD");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $eth_data_str = json_decode(curl_exec($ch));
        curl_close($ch);

        $btc = $btc_data_str[0];
        $eth = $eth_data_str[0];
        $return_data = array('btc'=>$btc->price_usd, 'eth'=>$eth->price_usd);

        return $return_data;
    }
    /*
    */
    public function getLocalCurrencyRate($local_currency) {
        $ch = curl_init("https://api.coinmarketcap.com/v1/ticker/bitcoin/?convert=$local_currency");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $btc_data_str = json_decode(curl_exec($ch));
        curl_close($ch);


        $ch = curl_init("https://api.coinmarketcap.com/v1/ticker/ethereum/?convert=$local_currency");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $eth_data_str = json_decode(curl_exec($ch));
        curl_close($ch);

        $btc = self::getArrayfromStdObj($btc_data_str[0]);
        $eth = self::getArrayfromStdObj($eth_data_str[0]);
        $return_data = array('btc'=>round($btc['price_'.strtolower($local_currency)],2), 'eth'=>round($eth['price_'.strtolower($local_currency)],2));

        return $return_data;
    }
    public function getArrayfromStdObj($stdObj){
        $ret_arr = array();
        foreach( $stdObj as $key=>$val ) $ret_arr[$key] = $val;
        return $ret_arr;
    }
}

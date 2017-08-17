<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserWallet;
use DB;

class OpenTradeController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index() {

        $user = \Auth::user();
        $user_id = $user->id;

        //
        $sell_listings = DB::table('listings')
                        ->join('contract', 'listings.id', '=', 'contract.listing_id')
                        ->join('users', 'contract.sender_id', '=', 'users.id')
                        ->join('transaction_history', 'transaction_history.contract_id', '=', 'contract.id')
                        ->select('contract.*', 'listings.coin_type','listings.currency','listings.user_type', 'users.name', 'transaction_history.transaction_id','transaction_history.coin_amount', 'listings.payment_method','listings.is_closed')
                        ->where('listings.is_closed', '=', '0')->where('contract.receiver_id', '=', $user->id)
                        ->orderBy('contract.created_at', 'desc')
                        ->get()->toArray();
        $model = new UserWallet();
        $sell_arr = array();
        foreach( $sell_listings as $sell_listings ) {
            $tmp_arr = $model->getArrayfromStdObj($sell_listings);
            $price_info = $model->getLocalCurrencyRate($tmp_arr['currency']);
            $tmp_arr['fiat_amount'] = $tmp_arr['coin_amount']*$price_info[$tmp_arr['coin_type']];
            $sell_arr[] = $tmp_arr;
        }
        
        $buy_listings = DB::table('listings')
                        ->join('contract', 'listings.id', '=', 'contract.listing_id')
                        ->join('users', 'contract.receiver_id', '=', 'users.id')
                        ->join('transaction_history', 'transaction_history.contract_id', '=', 'contract.id')
                        ->select('contract.*', 'listings.coin_type','listings.currency','listings.user_type', 'users.name', 'transaction_history.transaction_id','transaction_history.coin_amount', 'listings.payment_method','listings.is_closed')
                        ->where('listings.is_closed', '=', '0')->where('contract.sender_id', '=', $user->id)
                        ->orderBy('contract.created_at', 'desc')
                        ->get()->toArray();
        $model = new UserWallet();
        $buy_arr= array();
        foreach( $buy_listings as $buy_listings ) {
            $tmp_arr = $model->getArrayfromStdObj($sell_listings);
            $price_info = $model->getLocalCurrencyRate($tmp_arr['currency']);
            $tmp_arr['fiat_amount'] = $tmp_arr['coin_amount']*$price_info[$tmp_arr['coin_type']];
            $buy_arr[] = $tmp_arr;
        }
        return view('trade.opentrade')->with('sell_listings', $sell_arr)->with('buy_listings', $buy_arr);
    }
}

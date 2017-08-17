<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Database;

class IndexModel extends Model
{
    protected $table = 'listings';

    /**
        This function 
        @param: $user_id - logged in User Id
                $type    - 0 seller,  1 buyer
                $init    - flag for see more action { 1 - limit 5, 0 - all }
                $filter_param - filter request array
                    { coin_amount, coin_type, location, payment_method }
        @return Array
        @Author : Daiki Isoroku87
    */
    public function getListingsData( $user_id, $type = 0, $init =1, $filter_param=array() ) {

        $data = DB::table('listings')
            ->join('users', 'users.id', '=', 'listings.user_id')
            ->select('listings.*', 'users.name')
            ->where( 'user_id', '<>', $user_id )->where( 'is_closed', '=', '0')->where('status', '=',1 )->where('user_type', '=', $type)->where('is_closed', '=', 0);

        if ( $filter_param['coin_amount']>0 )
            $data->where('coin_amount', '>=', $filter_param['coin_amount']);
        if ( $filter_param['coin_type'] != 'none' ) 
            $data->where('coin_type', '=', $filter_param['coin_type']);
        if ( $filter_param['location'] != '' )
            $data->where('location', 'like', '%' . $filter_param['location'] . '%');
        if ( $filter_param['payment_method'] != 'none' )
            $data->where("payment_method", "=", $filter_param['payment_method']);

        $data->orderBy('created_at', 'asc');
        if ( $init )
            $data->offset(0)->limit(5);
        return $data->get()->toArray();
    }

    public function getLastMessageList( $user_id ) {
        $msg_listings = DB::table('trade_message')
            ->join('contract', 'contract.id', '=', 'trade_message.contract_id')
            ->join('listings', 'listings.id', '=', 'contract.listing_id')
            ->leftjoin('users', 'trade_message.sender_id', '=', 'users.id')
            ->select('trade_message.*', 'users.name', 'contract.listing_id', 'listings.user_type', 'listings.user_id')
            ->where( 'trade_message.receiver_id', '=', $user_id )->where('trade_message.is_read', '=', 0)
            ->orderBy('created_at', 'desc')
            ->get();
//          dd($msg_listings);  

//        $data = array();
//        $contract_id = 0;
//        foreach($msg_listings as $listing){
//                $data[] = $listing;
//        }
        return $msg_listings;
    }

}

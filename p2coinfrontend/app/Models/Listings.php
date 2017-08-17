<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Database;
class Listings extends Model
{
    //
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
    
    public function getListingsDataByUser( $user_id, $coin_type, $init = 1 ) {
        $data = DB::table('listings')
            ->select('listings.*')
            ->where( 'user_id', '=', $user_id )->where('coin_type', '=', $coin_type);

        $data->orderBy('created_at', 'desc');
        if ( $init )
            $data->offset(0)->limit(5);
        return $data->get();
    } 

    public function getTradeCount( $user_id , $coin_type ) {
        $data_cnt = DB::select("select * from (SELECT * FROM `contract` WHERE sender_id= $user_id or receiver_id=$user_id) as c
                    join transaction_history th
                    on th.contract_id = c.id
                    join listings l
                    on l.id=c.listing_id
                    where l.is_closed in (2,3) and l.coin_type='$coin_type'");
        return count($data_cnt);
    }
    public function getTradeAmount($user_id , $coin_type ) {
        $data = DB::select("select sum(th.coin_amount) amount, l.coin_type from (SELECT * FROM `contract` WHERE sender_id= $user_id or receiver_id=$user_id) as c join transaction_history th on th.contract_id = c.id join listings l on l.id=c.listing_id where l.is_closed in (2,3) and l.coin_type='$coin_type' group by l.coin_type");
        $ret_arr = array('btc'=>0, 'eth'=>0);
        if ( count($data)>0 ) {
            foreach( $data as $d ) {
                $ret_arr[$d->coin_type] = $d->amount;
            }
        }
        return $ret_arr;
    }
}

<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Common extends Model
{
    //
    private $wallet_data;
    public function getUserList( $useremail=null, $username=null ) {
        $data = DB::table('users')->select('users.*');        
        if ( !is_null($useremail) ) $data->where('email', 'like', "%".$useremail."%");
        if ( !is_null($username) ) $data->where('name', 'like', "%".$username."%");
        return $data->get()->toArray();
    }
    public function getUserinfoById( $user_id ) {
        $data = DB::table('users')->select('users.*')->where('id', '=', $user_id)->get()->toArray();
        return self::getArrayfromStdObj($data[0]);
    }
    public function getArrayfromStdObj($stdObj){
        $ret_arr = array();
        foreach( $stdObj as $key=>$val ) $ret_arr[$key] = $val;
        return $ret_arr;
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
    public function getAllListingsData() {
        $listings = DB::select(DB::raw("select listings.*, users.name from listings join users on users.id=listings.user_id
                                where listings.is_closed='0' and listings.id not in (select listing_id from listing_report)"));
       
        return $listings;
    }
    public function getAllReportedListingsData() {
        $listings = DB::select(DB::raw("select `listing_report`.id as report_id, `listing_report`.listing_id, `listing_report`.report_user_id,
        `listing_report`.report_reason, `listing_report`.report_status, `listing_report`.created_at, `listing_report`.updated_at,
        listings.*, `users`.`name` from `listing_report` inner join `users` on 
        `listing_report`.`report_user_id` = `users`.`id` inner join `listings` on `listing_report`.`listing_id` = `listings`.`id`"));
        return $listings;
    }
    public function getOpenTradeList() {
        $trades = DB::table('listings')
                    ->join('users', 'listings.user_id', '=', 'users.id')
                    ->join('contract', 'listings.id', '=', 'contract.listing_id')
                    ->join('transaction_history', 'transaction_history.contract_id', '=', 'contract.id')
                    ->select('transaction_history.*','listings.*', 'users.name','users.email')
                    // ->where('listings.is_closed', '<>', '0')
                    ->orderBy('contract.created_at', 'desc')
                    ->get();
        return $trades;
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
    public function updateUserVerification($user_id, $type, $status) {
        $field_arr = array('sms'=>'phone_verify', 'id'=>'id_verify');
        try{
            DB::table('users')
                ->where('id', '=',$user_id)
                ->update([$field_arr[$type] => $status]);
            return true;
        }
        catch(exception $exp) {
            return false;
        }
        
    }
    public function getPendingWithdrawals() {
        $transaction_history_data = DB::table('transaction_history')->select()->distinct()->where('status_col', '=', '0')->get()->toArray();
        $transaction_history_data = DB::table('listings')
                        ->join('contract', 'listings.id', '=', 'contract.listing_id')
                        ->join('transaction_history', 'transaction_history.contract_id', '=', 'contract.id')
                        ->select('contract.*', 'listings.coin_type', 'transaction_history.*', 'listings.payment_method')
                        ->where('listings.is_closed', '=', '0')
                        ->orderBy('contract.created_at', 'desc')
                        ->get();
        $user_list = DB::table('users')->select()->get()->toArray();
        $this->wallet_data = DB::table('user_wallet')->select()->get()->toArray();

        $user_data = array();
        foreach( $user_list as $list ) {
            $user_data[$list->id] = array('name'=>$list->name, 'email'=>$list->email, 'wallet_info'=>$this->getArrayDataFromWallet($list->id));
        }
        $transaction_arr = array();
        $status_arr = array('progressing','pending','released');
        foreach ($transaction_history_data as $transaction ) {
            $ret_arr = array();
            $ret_arr['sender'] = $user_data[$transaction->coin_sender_id];
            $ret_arr['receiver'] = $user_data[$transaction->coin_receiver_id];
            $ret_arr['coin_amount'] = $transaction->coin_amount;
            $ret_arr['date'] = $transaction->created_at;
            $ret_arr['status'] = $status_arr[$transaction->status_col];
            $ret_arr['coin_type'] = $transaction->coin_type;
            $transaction_arr[] = $ret_arr;
        }

        return $transaction_arr;
    }
    public function getArrayDataFromWallet( $user_id ) {
        $ret_arr = array();
        foreach( $this->wallet_data as $info ){
            if ( $info->user_id == $user_id ) {
                $ret_arr[$info->wallet_type]['wallet_address'] = $info->wallet_address;
                $ret_arr[$info->wallet_type]['private'] = $info->private;
                $ret_arr[$info->wallet_type]['public'] = $info->public;
                // $ret_arr['type'] = $info->wallet_type;
            }
        }
        return $ret_arr;
    }
    public function getWithdrawalHistory() {
        $withdrawal_history_data = DB::table('users')
            ->join('withdrawal_history', 'users.id', '=', 'withdrawal_history.user_id')
            ->select('withdrawal_history.*', 'users.name')
//            ->where('listings.is_closed', '=', '0')
            ->orderBy('withdrawal_history.created_at', 'desc')
            ->get();
        return $withdrawal_history_data->toArray();
    }
    public function deleteListing($listing_id) {
        try {
            DB::table('listings')->where('id', '=', $listing_id)->delete();
            return 'true';
        }
        catch( Exception $exp ) {
            return 'fail';
        }
    }
    public function updateListingStatus($listing_id, $is_closed_status) {
        try{
            DB::table('listings')
                ->where('id', '=',$listing_id)
                ->update(['is_closed'=>$is_closed_status]);
            return true;
        }
        catch(exception $exp) {
            return false;
        }

    }
    public function changeuserBlockStatus($user_id, $type, $status) {
        $status = 1 - $status;
        $ret = DB::table('user_login_status')->where('user_id', '=', $user_id)
                ->update(['block_'.$type=>$status]);
        return $ret;
    }
    public function getUserStatus($user_id) {
        try{
            $data = DB::table('user_login_status')->where('user_id', '=', $user_id)->get()->toArray();
            if (count($data))            
                return self::getArrayfromStdObj($data[0]);
            else
                return array('block_account'=>0, 'block_ip'=>0, 'logged_ip'=>'*');
        }
        catch( Exception $exp ) {
            return array('block_account'=>0, 'block_ip'=>0, 'logged_ip'=>'*');
        }
    }

    /** Main Chart(Statics part) */
    public function getTotalUsers(){
        $data = DB::select("select count(email) as cnt from users");
        return $data[0]->cnt;
    }

    public function getVolumeValue() {
        $start_time = Date('Y-m-d 00:00:00');
        $end_time = date('Y-m-d h:i:s');
        $data = DB::select(DB::raw("select l.coin_type ctype, SUM(t.coin_amount) as amount 
                                from transaction_history t
                                join contract c on (c.id = t.contract_id)
                                join listings l on (c.listing_id = l.id)
                                where t.created_at > '". $start_time . "' and t.created_at < '" . $end_time . "' 
                                group by l.coin_type"));
        $data_list = array();
        foreach($data as $row){
            $data_list[$row->ctype] = number_format($row->amount, 8, '.',',');

        }
        
        if ( !array_key_exists('eth', $data_list) ) $data_list['eth'] = 0;
        if ( !array_key_exists('btc', $data_list) ) $data_list['btc'] = 0;
        return $data_list;
    }

    public function getCurrentUsers() {
        return (self::getTotalUsers()-1);
    }
    public function getRevenuValue(){
        return 320;
    }
    /****/

    public function sendNotification($user_id, $msg_content)
    {
        $values = array('sender_id' => -3000, 'receiver_id' => $user_id, 'message_content' => $msg_content, 'contract_id' => -3000, 'created_at' => Date('Y-m-d H:i:s'), 'updated_at' => Date('Y-m-d H:i:s'));
        return DB::table('trade_message')->insert($values);
    }

    public function getDesiputeList() {
        $data  = DB::select("SELECT th.coin_sender_id, th.coin_receiver_id, l.id listing_id, dh.dispute_reason, l.is_closed, c.id contract_id FROM dispute_history dh
                            join `transaction_history` th 
                            on dh.transaction_id = th.transaction_id
                            join contract c
                            on c.id=th.contract_id
                            join listings l
                            on l.id = c.listing_id
                            where l.is_closed<3;");
        return $data;
    }

    public function getMsgListByContractId( $contract_id, $user_id ) {
        $datas = DB::table('trade_message')
                    ->join('users', 'users.id', '=', 'trade_message.sender_id')
                    ->select('trade_message.*', 'users.name')
                    ->where('contract_id', '=', $contract_id)
                    ->orderBy('created_at', 'asc')
                    ->get();

        $arr = array();
        $current_id = $user_id;
        foreach( $datas as $data ) {
            $user_state = 'success left-content';
            if($current_id == $data->sender_id)
                $user_state = 'info right-content';
            $arr[] = array( 'contract_id'       => $data->contract_id,
                            'sender_id'         => $data->sender_id,
                            'receiver_id'       => $data->receiver_id,
                            'message_content'   => $data->message_content,
                            'user_state'        => $user_state,
                            'name'              => $data->name,
                            'created_at'        => date('H:m:s M j,Y',strtotime($data->created_at)));
        }
        return $arr;
    }
    
    public function getUserIdImageinfo($user_id) {
        $data = DB::select("select image_path from user_id_image_info where user_id=$user_id");
        return $data;
    }
}

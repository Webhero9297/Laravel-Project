<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ContractFeedback extends Model
{
    //
    protected $table = 'trade_feedback';

    public function getFeedbackByUser( $user_id ) {
        $datas = DB::select("SELECT c.receiver_id contact_user_id, tf.* FROM `contract` c
                join trade_feedback tf 
                on c.id=tf.contract_id 
                WHERE c.sender_id = $user_id
                union
                SELECT c.sender_id contact_user_id, tf.* FROM `contract` c
                join trade_feedback tf 
                on c.id=tf.contract_id 
                WHERE c.receiver_id = $user_id");
        $ret_arr = array();
        $outcome = array('-1'=>'Negative', '0'=>'Neutral', '1'=>'Positive');
        $total = array('-1'=>0, '0'=>0, '1'=>0);
        foreach( $datas as $data ) {
            $accept_user_info = $this->getUserinfoById( $data->contact_user_id );
            $contact_user_name = substr($accept_user_info['name'],0, 1).$this->makeSubString('*', strlen($accept_user_info['name'])-2, '*').substr($accept_user_info['name'],-1,1);
            $total[$data->outcome]++;
            $ret_arr[] = array('date'=>date('j,n,Y', strtotime($data->created_at)), 
                               'user'=>$contact_user_name, 'outcome'=>$outcome[$data->outcome], 'feedback'=>$data->feedback);
        }
        return array('data'=>$ret_arr,'total'=>$total);
    }
    private function makeSubString($ref_str, $length) {
        $str = '';
        for($i=0;$i<$length;$i++) $str .= $ref_str;
        return $str;
    }
    public function getArrayfromStdObj($stdObj){
        $ret_arr = array();
        foreach( $stdObj as $key=>$val ) $ret_arr[$key] = $val;
        return $ret_arr;
    }
    public function getUserinfoById( $user_id ) {
        $data = DB::table('users')->select('users.*')->where('id', '=', $user_id)->get()->toArray();
        return self::getArrayfromStdObj($data[0]);
    }
}

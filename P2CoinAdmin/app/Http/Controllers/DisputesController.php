<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Common;


class DisputesController extends Controller
{
    //
    public function index() {
        $totalusers = $this->totalusers;
        $volume = $this->volume;

        $model = new Common();
        $dispute_list = $model->getDesiputeList();
        $ret_arr = array();
        foreach( $dispute_list as $dispute ) {
            $sender_user_info = $model->getUserinfoById($dispute->coin_sender_id);
            $receiver_user_info = $model->getUserinfoById($dispute->coin_receiver_id);
            $tmp_arr = array();
            foreach( $dispute as $key=>$val )  $tmp_arr[$key] = $val;
            $tmp_arr['sender'] = $sender_user_info['name'];
            $tmp_arr['receiver'] = $receiver_user_info['name'];
            $tmp_arr['user_id'] = $dispute->coin_sender_id;
            $ret_arr[] = $tmp_arr;
        }
        return view('disputes.index')->with(['dispute_list'=>$ret_arr, 'totalusers'=>$totalusers, 'volume'=>$volume]);
    } 

    public function getMsgListByContractId( Request $request ) {
        $contract_id = $request->contract_id;
        $user_id = $request->user_id; //First User Id

        $msg_str = "";
        $model = new Common();
        $msg_list = $model->getMsgListByContractId($contract_id, $user_id);
        foreach( $msg_list as $msg_content ) {
            $msg_str .= "<div class='alert alert-" . $msg_content['user_state'] . "'>";
            $msg_str .=     "<strong>" . $msg_content['name'] . " - " . $msg_content['created_at'] . "</strong><br>";
            $msg_str .=     "<p>" . html_entity_decode($msg_content['message_content']) . "</p>";
            $msg_str .= "</div>";
        }

        echo $msg_str;
        exit;
    }
}

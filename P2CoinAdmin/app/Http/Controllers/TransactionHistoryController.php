<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Common;

use DB;

class TransactionHistoryController extends Controller
{
    //
    public function index() {
        return view('transactionhistory.index')->with(['totalusers'=>$this->totalusers, 'volume'=>$this->volume, 'revenue'=>$this->revenu]);
    }
    public function displaytrades() {
        $status = request()->get('status');
        $ret_arr = array();
        if ($status == 'all') {
            $data = DB::select("SELECT l.*, th.updated_at as tupd, th.coin_amount, th.coin_sender_id, th.coin_receiver_id, th.seller_feedback, th.buyer_feedback FROM `listings` l
                                join contract c
                                on l.id = c.listing_id
                                join transaction_history th
                                on th.contract_id = c.id
                                where (th.seller_feedback+th.buyer_feedback) < 1");

        }
        else {
            $data = DB::select("SELECT l.*, th.updated_at as tupd, th.coin_amount, th.coin_sender_id, th.coin_receiver_id, th.seller_feedback, th.buyer_feedback FROM `listings` l
                                join contract c
                                on l.id = c.listing_id
                                join transaction_history th
                                on th.contract_id = c.id
                                where (th.seller_feedback = 1 or th.buyer_feedback = 1)");
        }
        $model = new Common();
        header('Content-type:application/html');
        $retHTML = '';
        foreach( $data as $d ) {
            $row_data = $model->getArrayfromStdObj($d);
            $sellerInfo = $model->getUserinfoById($row_data['coin_sender_id']);
            $buyerInfo = $model->getUserinfoById($row_data['coin_receiver_id']);
            $row_data['seller_name'] = $sellerInfo['name'];
            $row_data['buyer_name'] = $buyerInfo['name'];
            $localPriceInfo = $model->getLocalCurrencyRate($row_data['currency']);
            $rate = $localPriceInfo[$row_data['coin_type']];
            $row_data['fiat_rate'] = $rate;
            $row_data['rate_info'] = $localPriceInfo;
            $ret_arr[] = $row_data;
            $opt = $this->getOptValue($row_data['seller_feedback'],$row_data['buyer_feedback']);
            $retHTML .= "<tr>";
            $retHTML .= "<td>".date('M j, Y', strtotime($row_data['updated_at']))."</td>";
            $retHTML .= "<td>".$row_data['buyer_name']."</td>";
            $retHTML .= "<td>".$row_data['seller_name']."</td>";
            $retHTML .= "<td>".$row_data['coin_amount']."</td>";
            $retHTML .= "<td>".strtoupper($row_data['coin_type'])."</td>";
            $retHTML .= "<td>".($row_data['fiat_rate']*$row_data['coin_amount'])."</td>";
            $retHTML .= "<td>".strtoupper($row_data['currency'])."</td>";
            $retHTML .= $this->getOptValue($row_data['seller_feedback'],$row_data['buyer_feedback']);
            $retHTML .= "</tr>";
        }
        echo $retHTML;
        exit;
    }
    private function getOptValue($op1, $op2) {
        if ( $op1 == -1 && $op2 == -1 ) return "<td style='color:grey;'>Not yet</td>";
        if ( $op1 == 0 && $op2 == 0 ) return "<td style='color:red;'>No</td>";
        return "<td style='color:green;'>Yes</td>";
    }
    private function getArrayfromStdObj($stdObj){
        $ret_arr = array();
        foreach( $stdObj as $key=>$val ) $ret_arr[$key] = $val;
        return $ret_arr;
    }
}

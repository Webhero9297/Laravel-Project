<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Common;

class WithdrawalsController extends Controller
{
    //
    public function index() {
        $model = new Common();
        $data = $model->getWithdrawalHistory();
        $status_arr = array('pending');
        return view('withdrawals.index')->with(['data'=>$data,'status_arr'=>$status_arr, 'totalusers'=>$this->totalusers, 'volume'=>$this->volume]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index() {
        $total_users = 500;
        $active_users = 300;
        $user_rate = $active_users/$total_users*100;

        $nowdate = date("Y-m-d H:m:s", strtotime(date("Y-m-d H:m:s")));
        $yesterday = date("Y-m-d H:m:s", strtotime("-1 days", strtotime($nowdate)));
        session()->put('total_users', '500');
        session()->put('active_users', '300');
        return view('index.index')->with(['totalusers'=>$this->totalusers, 'volume'=>$this->volume, 'active_users'=>$active_users,'user_rate'=>$user_rate]);
    }
}

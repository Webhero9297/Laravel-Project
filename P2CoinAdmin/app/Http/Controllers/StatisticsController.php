<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class StatisticsController extends Controller
{
    //
    public function index() {
        $totalusers = $this->totalusers;
        $volume = $this->volume;

        return view('statistics.index')->with(['totalusers'=>$totalusers, 'volume'=>$volume]);
    }

    public function getVolume(Request $request) {
        header('Content-type:application/json');
        (is_null($request->year)) ? $year = date('Y') : $year = $request->year;
        (is_null($request->month)) ? $month = date('m') : $month = $request->month;
        
        //Bitcoin
        $data = DB::select(DB::raw("select l.coin_type ctype, SUM(t.coin_amount) as amount, date_format(t.created_at, '%Y-%m-%d') real_day 
                                from transaction_history t
                                join contract c on (c.id = t.contract_id)
                                join listings l on (c.listing_id = l.id)
                                where t.created_at like '".$year."-".$month."-%' 
                                group by l.coin_type, real_day
                                order by l.coin_type asc"));

        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $data_list = array();
        foreach($data as $row){
            $data_list[$row->ctype][date('j', strtotime($row->real_day))] = round($row->amount * 0.005, 8);
        }

        $coin_list = array();
        for($i = 1; $i <= $days; $i++){
            $coin_list['day'][] = $i;
            $coin_list['btc'][] = (isset($data_list['btc'][$i])) ? $data_list['btc'][$i] : 0;
            $coin_list['eth'][] = (isset($data_list['eth'][$i])) ? $data_list['eth'][$i] : 0;
        }

        echo json_encode($coin_list);
        exit;
    }

    public function getRevenu(Request $request) {
        header('Content-type:application/json');
        (is_null($request->year)) ? $year = date('Y') : $year = $request->year;
        (is_null($request->month)) ? $month = date('m') : $month = $request->month;
        
        //Bitcoin
        $data = DB::select(DB::raw("select l.coin_type ctype, SUM(t.coin_amount) as amount, date_format(t.created_at, '%Y-%m-%d') real_day 
                                from transaction_history t
                                join contract c on (c.id = t.contract_id)
                                join listings l on (c.listing_id = l.id)
                                where t.created_at like '".$year."-".$month."-%' 
                                group by l.coin_type, real_day
                                order by l.coin_type asc"));

        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $data_list = array();
        foreach($data as $row){
            $data_list[$row->ctype][date('j', strtotime($row->real_day))] = $row->amount;
        }

        $coin_list = array();
        for($i = 1; $i <= $days; $i++){
            $coin_list['day'][] = $i;
            $coin_list['btc'][] = (isset($data_list['btc'][$i])) ? $data_list['btc'][$i] : 0;
            $coin_list['eth'][] = (isset($data_list['eth'][$i])) ? $data_list['eth'][$i] : 0;
        }

        echo json_encode($coin_list);
        exit;
    }

    public function getTrades(Request $request) {
        header('Content-type:application/json');
        (is_null($request->year)) ? $year = date('Y') : $year = $request->year;
        (is_null($request->month)) ? $month = date('m') : $month = $request->month;

        $data = DB::select(DB::raw("select l.coin_type ctype, count(t.coin_amount) as trades, date_format(t.created_at, '%Y-%m-%d') real_day 
                                from transaction_history t
                                join contract c on (c.id = t.contract_id)
                                join listings l on (c.listing_id = l.id)
                                where t.created_at like '".$year."-".$month."-%' 
                                group by l.coin_type, real_day
                                order by l.coin_type asc"));

        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $data_list = array();
        foreach($data as $row){
            $data_list[$row->ctype][date('j', strtotime($row->real_day))] = $row->trades;
        }

        $trades_list = array();
        for($i = 1; $i <= $days; $i++){
            $trades_list['day'][] = $i;
            $trades_list['btc'][] = (isset($data_list['btc'][$i])) ? $data_list['btc'][$i] : 0;
            $trades_list['eth'][] = (isset($data_list['eth'][$i])) ? $data_list['eth'][$i] : 0;
        }

        echo json_encode($trades_list);
        exit;
    }

    public function getListings(Request $request) {
        header('Content-type:application/json');
        (is_null($request->year)) ? $year = date('Y') : $year = $request->year;
        (is_null($request->month)) ? $month = date('m') : $month = $request->month;

        $data = DB::select(DB::raw("select coin_type, count(coin_amount) listing_num, date_format(created_at, '%Y-%m-%d') real_day 
                                    from listings 
                                    where created_at like '" . $year . "-" . $month . "-%' 
                                    group by coin_type, real_day"));

        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $data_list = array();
        foreach($data as $row){
            $data_list[$row->coin_type][date('j', strtotime($row->real_day))] = $row->listing_num;
        }

        $listings_list = array();
        for($i = 1; $i <= $days; $i++){
            $listings_list['day'][] = $i;
            $listings_list['btc'][] = (isset($data_list['btc'][$i])) ? $data_list['btc'][$i] : 0;
            $listings_list['eth'][] = (isset($data_list['eth'][$i])) ? $data_list['eth'][$i] : 0;
        }

        echo json_encode($listings_list);
        exit;
    }

    public function getSignUpUsers(Request $request) {
        header('Content-type:application/json');
        (is_null($request->year)) ? $year = date('Y') : $year = $request->year;
        (is_null($request->month)) ? $month = date('m') : $month = $request->month;

        $data = DB::select(DB::raw("select count(email) users_num, date_format(created_at, '%Y-%m-%d') real_day 
                                    from users where created_at like '" . $year . "-" . $month . "-%' group by real_day"));

        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $data_list = array();
        foreach($data as $row){
            $data_list[date('j', strtotime($row->real_day))] = $row->users_num;
        }

        $signupusers_list = array();
        for($i = 1; $i <= $days; $i++){
            $signupusers_list['day'][] = $i;
            $signupusers_list['signupusers'][] = (isset($data_list[$i])) ? $data_list[$i] : 0;
        }

        echo json_encode($signupusers_list);
        exit;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Database;
class SheetSettings extends Model
{
    //
    protected $table = 'sheet_settings';
    public function getLastStatus( $updated_at = null ) {
        ( is_null($updated_at) ) ? $whereStr = "1=1" : $whereStr = "updated_at = '{$updated_at}'";

        $query_data = DB::select("SELECT * FROM `sheet_settings` WHERE {$whereStr} order by updated_at desc limit 1");
        $ret_arr = array();
        foreach( $query_data[0] as $key=>$val ){
            $ret_arr[$key] = $val;
        }
        return $ret_arr;
    }
    public function getAllStatus() {
        $all_data = self::all()->sortByDesc('updated_at');
        $ret_arr = array();
        foreach( $all_data as $record ) {
            $ret_arr[] = $record->original;
        }
        return $ret_arr;
    }
    public function getStatusById( $id ) {
        $query_data = self::find($id);
        $ret_arr = array();
        $record_data = $query_data->original;
        $ret_arr['script_cell'] = self::splitRowCol($record_data['script_cell']);
        $ret_arr['status_cell'] = (ord(strtolower($record_data['status_cell']))-ord('a')+1);
        $ret_arr['case_sep'] = $record_data['case_sep'];
        $ret_arr['fb_cell'] = explode(',',$record_data['fb_cell']);
        $ret_arr['status_row_start'] = $record_data['status_row_start'];
        //dd(trim($record_data['ignore_list']));
        $tmpStr = str_replace(' ','',strtolower($record_data['ignore_list']));
    // dd($tmpStr);
        $ret_arr['ignore_list'] = explode(',',$tmpStr);
        $ret_arr['additional_columns'] = self::getAdditionalColumns($record_data['additional_columns']);
        return $ret_arr;
    }
    private function splitRowCol($cellId) {
        $numbers = preg_replace('/[^0-9]/', '', $cellId);
        $letters = preg_replace('/[^a-zA-Z]/', '', $cellId);
        $ret_arr = array('row'=>($numbers), 'col'=>(1+ord(strtolower($letters))-ord('a')));
        return $ret_arr;
    }
    private function getAdditionalColumns( $additional_column_str ) {
        $tmp = explode(',', $additional_column_str);
        $ret_arr = array();
        foreach( $tmp as $val ) {
            $ret_arr[] = (1+ord(strtolower($val))-ord('a'));
        }
        return $ret_arr;
    }
}

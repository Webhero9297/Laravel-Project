<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class SheetStatus extends Model
{
    //
    protected $table = 'sheetstatus';
    public function getTestScriptStatus() {
        $all_data = self::all(['displayed_value', 'cell_values', 'ordering'])->sortBy('ordering');
        $ret_arr = array();
        foreach( $all_data as $record ) {
            $ret_arr[$record->displayed_value] = $record->cell_values;
        }
//dd($ret_arr);
        return $ret_arr;
    }

    public function getAllStatus() {
        $all_data = self::all()->sortBy('ordering');
        $ret_arr = array();
        foreach( $all_data as $record ) {
            $ret_arr[] = $record->original;
        }
        return $ret_arr;
    }
}

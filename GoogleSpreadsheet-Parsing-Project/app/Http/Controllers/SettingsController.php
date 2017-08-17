<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GoogleAccount;
use App\Models\GoogleDrvObj;
use App\Models\SheetStatus;
use App\Models\SheetSettings;
use Illuminate\Validation\Validator;
use Illuminate\Auth\Access\Response;

use App\Models\GoogleFolder;
use Laravel\Socialite\SocialiteServiceProvider;
use Mockery\Exception;
use Session;
use Socialite;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Publication as Publications;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use App\Models\UserLog;
class SettingsController extends Controller
{
    //
    public function index() {

    }
    public function sheet() {
        $userInfo = array();

        $userInfo = session()->get('userinfo');
        $sheet_settings =  SheetSettings::all();
//dd($sheet_settings);				
        if (is_null($userInfo)) return redirect()->action('IndexController@index');
        return view('settings.sheet')->with('userInfo',$userInfo)->with('sheet_settings',$sheet_settings);
    }
    public function status() {
        $userInfo = array();
        $userInfo = session()->get('userinfo');
        $sheet_status =  SheetStatus::all()->sortBy('ordering');
        if (is_null($userInfo)) return redirect()->action('IndexController@index');
        return view('settings.status')->with('userInfo', $userInfo)->with('sheet_status',$sheet_status);
    }
		public function selectid( Request $request ) {
			$sid = $request->sid;
			$userInfo = session()->get('userinfo');
			$userInfo['status_id'] = $sid;
			session()->put('userinfo', $userInfo);
			$userModel = new UserLog();
			$userLogInfo = $userModel->getLastDataByUserEmail($userInfo['email']);
			$currentUserId = $userLogInfo['id'];
			$userRow = UserLog::find($currentUserId);
			$userRow->status_id = $sid;
			$userRow->save();
//dd($userLogInfo);			
			exit("ok");
		}
    public function reorderstatus(Request $request) {
        $post_param = json_decode($request->get('param'));
        foreach( $post_param as $stdObj ) {
            $statusRow = SheetStatus::find($stdObj->id);
            $statusRow->ordering = $stdObj->ordering;
            $statusRow->save();
        }
//        var_dump(print_r(json_decode($post_param), true));exit;
        exit;
    }
    public function savesheet( Request $request ) {
        $script_cell = $request->get('script_cell');
        $status_cell = $request->get('status_cell');
        $case_sep = $request->get('case_sep');
        $fb_cell = $request->get('fb_cell');
        $status_row_start = $request->get('status_row_start');
        $ignore_list = $request->get('ignore_list');
        $additional_columns = $request->get('additional_columns');
        $tbl_id = $request->get('id');
        $action_status = $request->get('action_status');
        if ( !isset($script_cell) ) $script_cell = '';
        if ( !isset($status_cell) ) $status_cell = '';
        if ( !isset($case_sep) ) $case_sep = '';
        if ( !isset($fb_cell) ) $fb_cell = '';
        if ( !isset($status_row_start) ) $status_row_start = 1;
        if ( !isset($ignore_list) ) $ignore_list = '';
        if ( !isset($additional_columns) ) $additional_columns = '';
        $ret = 'FAIL';
        if ( $action_status != 'delete' ){
            ($action_status == 'insert') ? $sheet = new SheetSettings(): $sheet = SheetSettings::find($tbl_id);
            $sheet->script_cell = $script_cell;
            $sheet->status_cell = $status_cell;
            $sheet->case_sep = $case_sep;
            $sheet->fb_cell = $fb_cell;
            $sheet->status_row_start = $status_row_start;
            $sheet->ignore_list = $ignore_list;
            $sheet->additional_columns = $additional_columns;
            $sheet->save();
            $ret = 'SUCCESS';

            $userInfo = array();

            return redirect()->action('SettingsController@sheet');
        }
        else{
            SheetSettings::find($tbl_id)->delete();
            $ret = 'SUCCESS';
            return $ret;
        }
    }
    public function savestatus( Request $request ) {
        $tbl_id = $request->get('id');
        $action_status = $request->get('action_status');
        $displayed_value = $request->get('displayed_value');
        $cell_values = $request->get('cell_values');

        $cnt = SheetStatus::all('ordering')->max('ordering');
        if ( !isset($cell_values) ) $cell_values = '';
        $ret = 'FAIL';
        if ( $action_status != 'delete' ){
            ($action_status == 'insert') ? $status = new SheetStatus(): $status = SheetStatus::find($tbl_id);
            $status->displayed_value = $displayed_value;
            $status->cell_values = $cell_values;

            if ($action_status == 'insert')            $status->ordering = $cnt+1;
            $status->save();
            $ret = 'SUCCESS';
        }
        else{
            SheetStatus::find($tbl_id)->delete();
            $ret = 'SUCCESS';
        }
        return $ret;
    }
}

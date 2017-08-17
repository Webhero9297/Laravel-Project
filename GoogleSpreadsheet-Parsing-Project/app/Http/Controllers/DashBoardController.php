<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Models\GoogleAccount;
use App\Models\GoogleDrvObj;
use App\Models\GoogleFolder;
use App\Models\SheetSettings;
use App\Models\UserLog;
use Laravel\Socialite\SocialiteServiceProvider;
use Session;
use Socialite;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Publication as Publications;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use DB;
use App\Models\SheetStatus;
class DashBoardController extends Controller
{
    private $realtime_chart_data = array();
    private $daily_chart_data = array();
    public function index() {
        $userInfo = array();
        $userInfo = session()->get('userinfo');
        if (is_null($userInfo)) return redirect()->action('IndexController@index');
        $user_login_datas = $this->getUserLogData($userInfo['email']);
				$dailyStatusData = array();
        $status_data = array();
				session()->put('userinfo', $userInfo);
				
        $realtimestatus_data = $this->getRealTimeStatus();
        if ( $realtimestatus_data ) {
        	$dailyStatusData = $this->getDailyStatus('5');
            $statusModel = new SheetStatus();
            $status_data = $statusModel->getTestScriptStatus();

        }
				
        $settingsModel = new SheetSettings();
        $settings_data = $settingsModel->getAllStatus();
        $client = \Google::getClient();
        $userInfo['client'] = $client;			
        $userModel = new UserLog();
        $userLogInfo = $userModel->getLastDataByUserEmail($userInfo['email']);
                
        if ( !$userLogInfo ) {
            $status_id = "NULL";
            $folder_id = "NULL";
            $folder_title = "Untitled";
        }
        else {
            $status_id = $userLogInfo['status_id'];
            $folder_id = $userLogInfo['item_id'];
            $folder_title = $userLogInfo['item_title'];
        }
        $treegrid_html = '';
        $userInfo['status_id'] = $status_id;
        $userInfo['item_id'] = $folder_id;
        $userInfo['item_title'] = $folder_title;
        session()->put('userinfo', $userInfo);

        return view('dashboard.index')->with('userInfo', $userInfo)
                                      ->with('item_title', $folder_title)
                                      ->with('user_login_datas', $user_login_datas)
                                      ->with('realtimestatus_data', $realtimestatus_data)
                                      ->with('dailystatus_data', $dailyStatusData)
                                      ->with('realtime_chart_data', $this->realtime_chart_data)
                                      ->with('daily_chart_data', $this->daily_chart_data)
																			->with('treegrid_html', $treegrid_html)
																			->with('status_id', $status_id)
                                      ->with('status_data', $status_data);
    }
    private function getUserLogData( $email )
    {
        $user_data = UserLog::all()->where('email', '=', $email)->sortByDesc('created_at');
//dd($user_data);
        $arr = array();
        if (count($user_data)>10) {
            for ($i = count($user_data) - 1; $i >= count($user_data) - 10; $i--) {
                $arr[] = $user_data[$i];
            }
        }
        return $arr;
    }
    private function getRealTimeStatus() {
        $status_arr = DB::select('SELECT displayed_value as status_name from sheetstatus');
        $sheet_arr = DB::select('SELECT sheet_name from tbl_status_for_data group by sheet_name');
        $ret_arr = array();
        $total_arr = array('tests'=>'Total');
        $percent_arr = array();
//dd($sheet_arr);				
        if ($sheet_arr) { 
					$total_arr['total'] = 0;
					$total_arr['executed'] = 0;
					foreach( $sheet_arr as $sheet ) {
							$element_arr = array();
							$sheet_name = $sheet->sheet_name;
							$element_arr['tests'] = $sheet_name;
							$executed = $totalcnt = 0;
							foreach( $status_arr as $status ) {
									$status_name = $status->status_name;
									$cnt_datas = DB::select("SELECT count(*) cnt from tbl_status_for_data where status = '{$status_name}' and sheet_name = '{$sheet_name}'");
									$cnt_data = $cnt_datas[0];
									$element_arr[$status_name] = (isset($cnt_data->cnt)?$cnt_data->cnt:0);
									if ( array_key_exists($status_name, $total_arr) )
											$total_arr[$status_name] += (isset($cnt_data->cnt)?$cnt_data->cnt:0);
									else {
											$total_arr[$status_name] = 0;
											$total_arr[$status_name] += (isset($cnt_data->cnt)?$cnt_data->cnt:0);
									}
									if ( $status_name != 'Not Started' ) {
											$executed += $element_arr[$status_name];
									}
									$totalcnt += $element_arr[$status_name];
							}
							//if ( array_key_exists('executed', $total_arr) )
									$total_arr['executed'] += $executed;
						 // else {
							//    $total_arr['executed'] = 0;
							//    $total_arr['executed'] += $executed;
							//}

							//if ( array_key_exists('total', $total_arr) )// will be recoded
							//    $total_arr['total'] += $totalcnt;
							//else {
							//    $total_arr['total'] = 0;
									$total_arr['total'] += $totalcnt;
							//}
							$element_arr['executed'] = $executed;
							$element_arr['total'] = $totalcnt;
							$ret_arr[] = $element_arr;
					}
        }
        else
					return array();
        $percent_arr = array('tests'=>'Percentage&nbsp;(%)', 'total'=>100);
        //$total_arr['total'] = 1;
        //$total_arr['executed'] = 0;
        //dd($total_arr);
        ($total_arr['total'] != 0) ? $percent_arr['executed'] = number_format($total_arr['executed']*100/$total_arr['total'],2):$percent_arr['executed'] = 0;
        foreach( $status_arr as $status ) {
            $status_name = $status->status_name;
						($total_arr['total']==0) ? $total = 1 : $total = $total_arr['total'];
						$percent_arr[$status_name] = number_format($total_arr[$status_name]*100/$total,2);
        }
        $ret_arr[] = $total_arr;
        $ret_arr[] = $percent_arr;
        foreach( $percent_arr as $key=>$val ) {
            if ( $key != 'total' && $key != 'tests' && $key != 'executed' ) {
                if ($key == 'executed') $key = 'Executed';
                $this->realtime_chart_data[] = array('label'=>$key, 'y'=>$val, 'legendText'=>$key);
            }
        }
        
        return $ret_arr;
    }
    private function getDailyStatus($date_column) {
        
        $column_arr = DB::select("SHOW COLUMNS FROM `tbl_status_for_data` LIKE '{$date_column}'");
// dd($column_arr);        
        if ( count($column_arr)==0 ) return array();

        $status_arr = DB::select('SELECT displayed_value as status_name from sheetstatus');
        $date_column_arr = DB::select("SELECT `{$date_column}` as date_column from tbl_status_for_data group by `{$date_column}`");
        $ret_arr = array();
        $total_arr = array('tests'=>'Total');
        $percent_arr = array();
        foreach( $date_column_arr as $date_field ) {
            $dateStr = $date_field->date_column;
            $element_arr = array();
            $element_arr['tests'] = $dateStr;
            $executed = 0;
            $totalcnt = 0;
            foreach( $status_arr as $status ) {
                $status_name = $status->status_name;
                
                $cnt_datas = DB::select("SELECT count(*) cnt from tbl_status_for_data where status = '{$status_name} ' and `{$date_column}` = '{$dateStr}'");
                $cnt_data = $cnt_datas[0];
                $element_arr[$status_name] = (isset($cnt_data->cnt)?$cnt_data->cnt:0);
                if ( array_key_exists($status_name, $total_arr) )
                    $total_arr[$status_name] += (isset($cnt_data->cnt)?$cnt_data->cnt:0);
                else {
                    $total_arr[$status_name] = 0;
                    $total_arr[$status_name] += (isset($cnt_data->cnt)?$cnt_data->cnt:0);
                }
                if ( $status_name != 'Not Started' ) {
                    $executed += $element_arr[$status_name];
                }
                $totalcnt += $element_arr[$status_name];
            }
            if ( array_key_exists('executed', $total_arr) )
                $total_arr['executed'] += $executed;
            else {
                $total_arr['executed'] = 0;
                $total_arr['executed'] += $executed;
            }

            if ( array_key_exists('total', $total_arr) )// will be recoded
                $total_arr['total'] += $totalcnt;
            else {
                $total_arr['total'] = 0;
                $total_arr['total'] += $totalcnt;
            }
            $element_arr['executed'] = $executed;
            $element_arr['total'] = $totalcnt;
            $ret_arr[] = $element_arr;
        }
        $percent_arr = array('tests'=>'Percentage&nbsp;(%)', 'total'=>100);
				($total_arr['total']==0) ? $total = 1 : $total = $total_arr['total'];
        $percent_arr['executed'] = number_format($total_arr['executed']*100/$total,2);
        foreach( $status_arr as $status ) {
            $status_name = $status->status_name;
            ($total_arr['total']==0) ? $total = 1 : $total = $total_arr['total'];
            $percent_arr[$status_name] = number_format(($total_arr[$status_name]*100)/$total,2);
        }
        $data_arr = array();
        foreach( $ret_arr as $ele_arr ) {
            // $data_arr[$ele_arr['tests']] = array('type'=>'stepLine');
            foreach( $ele_arr as $key=>$val ) {
                if ( $key != 'total' && $key != 'tests' ) {
                    $data_arr[$key]['type'] = 'line';
                    $data_arr[$key]['name'] = ucfirst($key);
                    $data_arr[$key]['markerType'] = 'square';
                    $data_arr[$key]['showInLegend'] = true;
                    $data_arr[$key]['lineThickness'] = 2;
                    
                    $data_arr[$key]['dataPoints'][] = array('x'=>date('Y,m,d', strtotime($ele_arr['tests'])), 'y'=>$val);
                }    
            }
            // $data_arr[] = $data_ele;
        }
        $arr = array();
        foreach( $data_arr as $ar ) {
            $arr[] = $ar;
        }
// dd($arr);        
        $this->daily_chart_data = $arr;
        $ret_arr[] = $total_arr;
        $ret_arr[] = $percent_arr;
        return $ret_arr;
    }
}

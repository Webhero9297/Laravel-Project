<?php

namespace App\Http\Controllers;

use App\Models\SheetSettings;
use App\models\SheetStatus;
use App\Models\UserLog;
use Illuminate\Http\Request;
use App\Models\GoogleAccount;
use App\Models\GoogleDrvObj;
use App\Models\GoogleFolder;
use Laravel\Socialite\SocialiteServiceProvider;
use Session;
use Socialite;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Publication as Publications;
use App\Models\GdriveCls;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Connection;
use DB;
class AdminController extends Controller
{
    private $client;
    private $service;

    public function __construct()
    {
        //notasecret
//        session_start();
        
    }

    public function index() {

        $userInfo = array();
	
				$userInfo = session()->get('userinfo');
//dd($userInfo);
        if (is_null($userInfo)) return redirect()->action('IndexController@index');
        $statusModel = new SheetStatus();
        $status_data = $statusModel->getAllStatus();
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
//dd($userInfo);				
        return view('admin.index')->with('userInfo', $userInfo)->with('settings_data', $settings_data)->with('status_data', $status_data)
            ->with('treegrid_html', $treegrid_html)->with('status_id', $status_id)->with('item_title', $userInfo['item_title']);
	
        
    }

    public function parsefolder(Request $request){
        header('Content-type: application/html');
        $folderId = $request->id;
				if (is_null($folderId)) exit("NoData");
				$selected_settings_id = $request->selected_settings_id;
				$folderTitle = $request->title;
				$userInfo = session()->get('userinfo');
				$userInfo = session()->get('userinfo');
				$userId = $userInfo['user_id'];
				
				
				$row = UserLog::find($userId);
				$row->item_id = $folderId;
				$row->item_title = $folderTitle;
				$row->status_id = $selected_settings_id;
				$row->save();
				
				$newUserInfo = array('name'=>$userInfo['name'], 'email'=>$userInfo['email'], 'userAvatar'=>$userInfo['userAvatar'],
					'login_at'=>$row->login_at, 'refresh_token'=>$row->refresh_token, 'user_id'=>$row->id, 'status_id'=>$row->status_id,
					'item_id'=>$row->item_id,  'item_title'=>$row->item_title);
				session()->put('userinfo', $newUserInfo);
					
//dd($request);	
				try{
					$echoStr = $this->getTreeGridHTMLString( $selected_settings_id, $folderId, $folderTitle );
						
        	echo $echoStr;
        }
        catch(Exception $e) {
        	echo "No Data";
        }
        exit;

    }
    private function getTreeGridHTMLString( $selected_settings_id, $folderId, $folderTitle = 'none' ) {
    	if (is_null($selected_settings_id)) return '';
        $statusModel = new SheetStatus();
        $status_data = $statusModel->getTestScriptStatus();
        $userInfo = session()->get('userinfo');

        $settingsModel = new SheetSettings();
        $settings_data = $settingsModel->getStatusById($selected_settings_id);
//dd($settings_data);				
        $refresh_token = $userInfo['refresh_token'];
        $client = \Google::getClient();
        if (isset($refresh_token) ) {
            $client->refreshToken($refresh_token);
            $token = $client->getAccessToken();
            $drive = \Google::make('drive');
            $folder_arr = array();
            $result = array();
            $pageToken = NULL;
            $folder_arr = array();
            $cls = new GoogleDrvObj($drive, $token['access_token']);
            $cls->setStatusData($status_data);
            $cls->setSettings($settings_data);
            $anal_data = $cls->getSubtreeForFolderSpreadSheet($folderId, true);
            $head_arr = $cls->getHead();
            $retHTML = "<thead><tr class='cell-tr' height='32px'>";

            $tableName = "status_for_data";
            $createTableQuery = "DROP TABLE IF EXISTS `tbl_{$tableName}`;
            CREATE TABLE `tbl_{$tableName}`(
            `id` int(11) auto_increment,
            `name` varchar(255) default NULL,
            `status` varchar(255) default NULL,
            `sheet_name` varchar(255) default NULL,
            ";
            $insertQuery = "insert into `tbl_{$tableName}`(`name`, `status`,`sheet_name`";
            foreach( $settings_data['additional_columns'] as $key=>$val ) {
                $createTableQuery .= "`{$val}` varchar(255) default NULL,";
                $insertQuery .= ",`{$val}`";
            }
            $insertQuery .= ") values";
            $createTableQuery .= "PRIMARY KEY(`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            // $head_arr[0]="a";$head_arr[1]="a_";$head_arr[2]="b";$head_arr[3]="c";$head_arr[4]="d";

            foreach( $head_arr as $key=>$val ) {
                $retHTML .= "<td class='cell-tr' align='center'><span class='middle-text'>".$val."</span></td>";
                ($key==0) ? $w='40%' : $w='10%';
            }
            $retHTML .= "</tr></thead><tbody id='tbody_tree_area'>";
            $value_arr = array();
            foreach( $anal_data as $child_data ) {
                if ( !array_key_exists('id', $child_data) ) continue;
                $retHTML .= "<tr class='cell-tr treegrid-{$child_data['id']} ".($child_data['parent']=='#'?'\'':"treegrid-parent-{$child_data['parent']}'").">";
                if (stripos( $child_data['mimetype'],'folder') ) $icon = "&nbsp;<img src='./public/assets/img/folder.png'/>&nbsp;";
                if (stripos( $child_data['mimetype'], 'spreadsheet') ) $icon = "&nbsp;<img src='./public/assets/img/sheet.png'/>&nbsp;";
                if ( $child_data['mimetype'] == 'sheet' ) $icon = "&nbsp;<img src='./public/assets/img/tabs-panel.jpg'/>&nbsp;";
                if ( $child_data['mimetype'] == 'script' ) $icon = "&nbsp;<img src='./public/assets/img/test.png'/>&nbsp;";
								
								$link = "https://docs.google.com/spreadsheets/d/";
								$hrefStr = $child_data['name'];
								if ( stripos( $child_data['mimetype'], 'folder') ) $hrefStr = "&nbsp;<a href='https://drive.google.com/drive/folders/{$child_data['id']}' target='_blank'>{$child_data['name']}</a>";
								if ( stripos( $child_data['mimetype'], 'spreadsheet') ) $hrefStr = "&nbsp;<a href='{$link}/{$child_data['id']}' target='_blank'>{$child_data['name']}</a>";
								if ( $child_data['mimetype'] == 'sheet' ) $hrefStr = "&nbsp;<a href='{$link}/{$child_data['parent']}/edit#gid={$child_data['id']}' target='_blank'>{$child_data['name']}</a>";
                
								$retHTML .= "<td width='50%' class='cell-tr'>{$icon}{$hrefStr}</span></td>";
                $retHTML .= "<td width='20' class='cell-tr'  align='center'>".(isset($child_data['status'])?$child_data['status']:"")."</td>";
                if ( $child_data['mimetype'] == 'script' ) {
                    $temp = "('{$child_data['treename']}', '{$child_data['status']}', '{$child_data['sheet_name']}'";
                }
                foreach( $settings_data['additional_columns'] as $val ) {
                    $retHTML .= "<td width='10%' class='cell-tr' align='center'>".(isset($child_data[$val])?$child_data[$val]:"")."</td>";
                    if ( $child_data['mimetype'] == 'script' ) {
                      ( array_key_exists($val, $child_data) ) ? $field_val = $child_data[$val] : $field_val = '';
											$temp .= ",'{$field_val}'";
                    }
                }
                if ( $child_data['mimetype'] == 'script' ) {
                    $temp .= ")";
                    $value_arr[] = $insertQuery.$temp;
                }
                $retHTML .= "</tr>";
            }
            $insertSQL = implode(';', $value_arr);
            $sql = $createTableQuery.$insertSQL.";"  ;     
            DB::connection()->getPdo()->exec( $sql );
            return $retHTML."</tbody>";

        }
        return "";
    }
    public function regcode(){
        $code = $_GET['code'];
        session()->put('code', $code);
        return redirect()->action('AdminController@index');
    }
    public function analyzefolder() {
        header('Content-type: application/html');
        $client = \Google::getClient();
        $selected_settings_id = session()->get('selected_settings_id');
        $rootDriverFolderId = session()->get('selected_folder_id');

        $statusModel = new SheetStatus();
        $status_data = $statusModel->getTestScriptStatus();

        $settingsModel = new SheetSettings();
        $settings_data = $settingsModel->getStatusById($selected_settings_id);
        $token = $_SESSION['access_token'];

        $client = \Google::getClient();
        $code = session()->get('code');
        if (isset($code)) {
            $client->authenticate($code);
            $token = $client->getAccessToken();

            $drive = \Google::make('drive');
            $folder_arr = array();
            $result = array();
            $pageToken = NULL;
            $folder_arr = array();
            $cls = new GoogleDrvObj($drive, $token['access_token']);
            $cls->setStatusData($status_data);
            $cls->setSettings($settings_data);
            $child_arr = $cls->getSubtreeForFolderSpreadSheet($rootDriverFolderId, true);

            $retHTML = "";
            foreach( $child_arr as $child_data ) {
                $retHTML .= "<tr data-tt-id='{$child_data['id']}' ".($child_data['parent']=='#'?'':"data-tt-parent-id='{$child_data['parent']}'").">";
                if (stripos( $child_data['mimetype'],'folder') ) $icon = "'fa fa-folder-open-o'";
                if (stripos( $child_data['mimetype'], 'spreadsheet') ) $icon = "'fa fa-excel-o'";
                if ( $child_data['mimetype'] == 'sheet' ) $icon = "'fa fa-sticky-note'";
                if ( $child_data['mimetype'] == 'script' ) $icon = "'fa fa-code'";
                $retHTML .= "<td><span class={$icon} >{$child_data['name']}</span></td>";
                $retHTML .= "<td>Fail</td>";
                $retHTML .= "<td>".(isset($child_data['F'])?$child_data['F']:"")."</td>";
                $retHTML .= "<td>".(isset($child_data['G'])?$child_data['G']:"")."</td>";
                $retHTML .= "</tr>";
            }

            echo $retHTML;
            exit;
        }
    }

    
}

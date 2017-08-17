<?php

namespace App\Http\Controllers;

use App\Models\SheetSettings;
use App\models\SheetStatus;
use Illuminate\Http\Request;
use App\Models\GoogleAccount;
use App\Models\GoogleDrvObj;
use App\Models\GoogleFolder;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\SocialiteServiceProvider;
use Session;
use Socialite;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Publication as Publications;
use App\Models\GdriveCls;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use App\Models\UserLog;

class IndexController extends Controller
{
    //
    public function index() {
        $client = \Google::getClient();
        return view('index.index')->with('client', $client);
//        return view('index.index');
    }
    public function regcode( Request $request ) {
        //dd($request);
				if ( !array_key_exists('code', $_GET) ) return redirect()->action('IndexController@index');
        
				$code = $_GET['code'];
        
				$client = \Google::getClient();
        if ( !isset($code) ) {
            return redirect()->action('IndexController@index');
        }
        else {
            $client->authenticate($_GET['code']);
            $access_token = $client->getAccessToken();
// dd($access_token['refresh_token']);            
            //get user email address
            $google_oauth =new \Google_Service_Oauth2($client);
            $google_account_email = $google_oauth->userinfo->get()->email;
            $familyName = $google_oauth->userinfo->get()->familyName;
            $givenName = $google_oauth->userinfo->get()->givenName;
            $name = $google_oauth->userinfo->get()->name;
            $gender = $google_oauth->userinfo->get()->gender;
            $picture = $google_oauth->userinfo->get()->picture; //profile picture

            $userModel = new UserLog();
            $userLogInfo = $userModel->getLastDataByUserEmail($google_account_email);
            $login_at = date('Y-m-d H:m:s');
//dd($access_token);
            $status_id = NULL;
            $item_id = NULL;
            $item_title = 'none';
            if ( !array_key_exists('refresh_token', $access_token) ) {
                
                if ($userLogInfo) {
                    $refresh_token = $userLogInfo['refresh_token'];
                    $status_id = $userLogInfo['status_id'];
                    $item_id = $userLogInfo['item_id'];
                    $item_title = $userLogInfo['item_title'];
                }
                else {
                    return redirect()->action('IndexController@index');
                }
            }
            else {
                $refresh_token = $access_token['refresh_token'];
            }
            
            $userRow = new UserLog();
            $userRow->email = $google_account_email;
            $userRow->firstname = $givenName;
            $userRow->lastName = $familyName;
            $userRow->login_at = date('Y-m-d H:i:s');
            $userRow->refresh_token = $refresh_token;
            $userRow->status_id = $status_id;
            $userRow->item_id = $item_id;
            $userRow->item_title = $item_title;
            $userRow->save();
            $userId =$userRow->id;

            $userInfo = array('name'=>$givenName." ".$familyName, 'email'=>$google_account_email, 'userAvatar'=>$picture,
            'login_at'=>$login_at, 'refresh_token'=>$refresh_token, 'user_id'=>$userId, 'status_id'=>$userRow->status_id, 'item_id'=>$userRow->item_id,
            'item_title'=>$item_title);
            session()->put('userinfo', $userInfo);
            return redirect()->route('dashboard');
//            $client->refreshToken($refresh_token);

//dd($access_token);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Common;

class UserControlController extends Controller
{
    //
    public function index() {
        $model = new Common();
        $userlist = $model->getUserList();
        return view('usercontrol.usercontrol')->with(['userlist'=> $userlist, 'totalusers'=>$this->totalusers, 'volume'=>$this->volume]);
    }
    public function getuserbysearch(Request $request) {
        $user_name = $request->user_name;
        $user_email = $request->user_email;
        $model = new Common();
        $userlist = $model->getUserList($user_email, $user_name);
        
        echo json_encode($userlist);
        exit;
    }
    public function userdetail($userid) {
        $model = new Common();
        $userInfo = $model->getUserInfoById($userid);
        $userStatus = $model->getUserStatus($userid);
//        dd($userStatus);
        ( $userStatus['block_account'] == 0 ) ? $userInfo['block_account_status'] = "checked" : $userInfo['block_account_status'] = '';
        ( $userStatus['block_ip'] == 0 ) ? $userInfo['block_ip_status'] = "checked" : $userInfo['block_ip_status'] = '';
        $userInfo['block_account'] = $userStatus['block_account'];
        $userInfo['block_ip'] = $userStatus['block_ip'];
        $userInfo['user_status'] = 'Active';
        $userInfo['ip_address'] = $userStatus['logged_ip'];
        $userInfo['user_id'] = $userid;
        return view('usercontrol.userdetail')->with(['user_info'=> $userInfo, 'totalusers'=>$this->totalusers, 'volume'=>$this->volume]);
    }
    public function blockuser(Request $request) {
        $user_id = $request->user_id;
        $status = $request->status;
        $type = $request->type;

        $model = new Common();
        echo $model->changeuserBlockStatus($user_id, $type, $status);
        exit;
    }

    public function sendNotification(Request $request){
        $user_id = $request->user_id;
        $msg_content = $request->msg_content;

        $model = new Common();
        $userInfo = $model->getUserInfoById($user_id);

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        mail($userInfo['email'], "P2Coin.net Support", $msg_content, $headers);
        // echo $model->sendNotification($user_id, $msg_content);
        exit;
    }

    public function userIdInfo() {
        $model = new Common();
        $userlist = $model->getUserList();
        return view('usercontrol.useridinfo')->with(['userlist'=> $userlist, 'totalusers'=>$this->totalusers, 'volume'=>$this->volume]);
    }
    public function useriddetail($userid) {
        $path = env('FRONTEND_UPLOADED_PATH').$userid."/";
        $model = new Common();
        $userInfo = $model->getUserInfoById($userid);
        $userIdImageInfos = $model->getUserIdImageinfo($userid);
        $image_list = array();
        foreach( $userIdImageInfos as $image_name ) {
            $image_list[] = $path.$image_name->image_path;
        }
        
        return view('usercontrol.useriddetail')->with(['image_list'=> $image_list, 'totalusers'=>$this->totalusers, 'volume'=>$this->volume]);
    }
}

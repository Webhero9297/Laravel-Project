<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChangePasswordController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index() {
        return view('change.password');
    }
    public function resetpassword(Request $request) {
        $user = \Auth::user();
        $user_email = $user->email;
        $user_password = $user->password;
        $old_password = $request->old_password;
        $new_password = $request->new_password;
// dd($user_password,bcrypt($old_password));
        $echoStr = "SUCCESS";
        // if ( $user_password != bcrypt($old_password) ) {
        //     $echoStr = "wrong_pass";
        // }
        // else {
        $userId = $user->id;
            //$rUser = \User::find($userId);
        $user->password = bcrypt($new_password);
            //dd($user);
        $user->save();
        // }
        return view('change.password');

//dd($user,$request);        
    }
}

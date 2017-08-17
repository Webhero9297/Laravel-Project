<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use checkmobi\CheckMobiRest;

class VerifyPhoneController extends Controller
{
    //
    private $chkmobi_api;
    public function __construct()
    {
        $this->middleware('auth');
        $checkmobi_secret_key = env('CHECKMOBI_SECRET_KEY');
        $this->chkmobi_api = new CheckMobiRest($checkmobi_secret_key);
    }
    public function index() {
        return view('verify.phone');
    }
    public function getpincode(Request $request){
        header('Content-type:application/json');
        $number = $request->phone_number;
        $dial_code = $request->dial_code;
        
        $response = $this->chkmobi_api->RequestValidation(array("type" => "sms", "number" => $dial_code.$number));
        echo json_encode($response);
        exit;
    }
    public function verifycode() {
        $request_id = request()->get('request_id');
        $request_code = request()->get('request_code');
        $dd = $this->chkmobi_api->VerifyPin(array("id" => $request_id, "pin" => $request_code));
        if ( $dd['status'] == 200 && $dd['response']['validated'] == true ) {
            $user = \Auth::user();
            $user->phone_number = $dd['response']['number'];
            $user->phone_verify = 1;
            $user->save();
            echo 'ok';
        }
        else
            echo 'fail';
        exit;
    }
}

<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use App\Models\Auth2fa;

class ChangeEmailController extends Controller
{
    //
    private $fileName = 'google2fasecret.key';
    private $secretKey;
    private $email = '';

    private $keySize = 32;

    private $keyPrefix = '';
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index() {
        
        return view('change.email');
    }
    public function change2fa() {
        $user = \Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        // $valid = $this->validateInput($key = $this->getSecretKey());
        $key = $this->createNewKey();
        $inlineUrl = $this->getInlineUrl($key);
        return view('change.twofaauth')->with(compact('key', 'inlineUrl', 'valid'));
    }
    public function changephone() {
        $user = \Auth::user();
        return view('change.phone')->with('phone_number', $user->phone_number);
    }
    public function changepersonphonenumber(Request $request) {
        try{
            $phone_number = $request->phone_number;
            $user = \Auth::user();
            $user->phone_number = $phone_number;
            $user->save();
        }
        catch(Exception $e){

        }
        return view('change.phone')->with('phone_number', $user->phone_number);
    }
    private function getInlineUrl($key)
    {
        $gCls = new Google2FA();
        return $gCls->getQRCodeInline($this->name,$this->email,$key);
    }
    private function getSecretKey()
    {
        if (! $key = $this->getStoredKey())
        {
            $key = $this->createNewKey();
        }
        return $key;
    }
    private function createNewKey() {
        $cls = new Google2FA();
        $key = $cls->generateSecretKey($this->keySize, $this->keyPrefix);
        return $key;
    }
    private function storeKey($key)
    {
        Storage::put($this->fileName, $key);
    }
    private function getStoredKey()
    {
        // No need to read it from disk it again if we already have it
        // if ($this->secretKey)
        // {
        //     return $this->secretKey;
        // }

        $user = \Auth::user();
        $authmodel = new Auth2fa();
        $keyStr = $authmodel->getKeyByUserId($user->id);
        return $keyStr;

        // if (! Storage::exists($this->fileName))
        // {
        //     return null;
        // }

        // return Storage::get($this->fileName);
    }
    private function validateInput($key)
    {
        // Get the code from input
        if (! $code = request()->get('code'))
        {
            return false;
        }
        $gCls = new Google2FA();
        // Verify the code
        return $gCls->verifyKey($key, $code);
    }
    public function check2fa()
    {
        $isValid = $this->validateInput($key = $this->getSecretKey());

        // Render index and show the result
        if ($isValid)
            echo "ok";
        else
            echo "fail";
        exit;
//        return $this->index($isValid);
    }
    public function registeKey() {
        $key = request()->get('key');
        $code = request()->get('code');
        $isValid = $this->validateInput($key);
        // Render index and show the result
        if ($isValid){
            $user = \Auth::user();
            $auth2famodel = new Auth2fa();
            $auth2famodel->addAuthInfo( $user->id, $key );
            echo "ok";
        }
        else
            echo "fail";
        exit;
    }

    public function reportuser() {
        $user = \Auth::user();
        $content = request()->get('content');

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $verificationid = $user->email_token;
        $msg = "<h1>Why are you reporting this user?</h1>
        <a href='".$_SERVER['HTTP_HOST']."/report/".$verificationid."' >".$_SERVER['HTTP_HOST']."/report/".$verificationid."</a>
        <br/>
        <label>$content</label>
        <br>
        ". $content;
        mail('admin@p2coin.net', "User report", $msg, $headers);
    }
    public function update2fa() {
        return view('settings.update2fa');
    }
}

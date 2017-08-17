<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Models\UserWallet;
use App\Models\WalletManage;
use App\Models\BlockchainWalletMng;
use App\Models\UserLoginStatus;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/verifyready';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
//  dd(request()->get('user_ip'));        
        $mUser = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'email_token' => base64_encode($data['email'])
        ]);
        $userSelfInfo = new UserLoginStatus();
        $userSelfInfo->user_id = $mUser->id;
        $userSelfInfo->online_status = 0;
        $userSelfInfo->logged_ip = request()->get('user_ip');
        $userSelfInfo->logged_at = date('Y-m-d h:i:s');
        $userSelfInfo->block_account = 0;
        $userSelfInfo->block_ip = 0;
        $userSelfInfo->save();

        // $walletRow = new UserWallet();
        // $model = new WalletManage();
        // $wallet_arr = $model->generateWallet();
        // $walletRow->user_id = $mUser->id;
        // $walletRow->wallet_address = $wallet_arr['address'];
        // $walletRow->wallet_type = 'btc';
        // $walletRow->save();

//        $walletRow = new UserWallet();
//        $wmodel = new BlockchainWalletMng();
//        $wmodel->setWalletType('btc');
//        $address = $wmodel->generateAddress();
//        $walletRow->user_id = $mUser->id;
//        $walletRow->wallet_type = 'btc';
//        $walletRow->wallet_address = $address['address'];
//        $walletRow->public = $address['public'];
//        $walletRow->private = $address['private'];
//        $walletRow->save();

        // $walletRow = new UserWallet();
        // $wmodel = new BlockchainWalletMng();
        // $wmodel->setWalletType('eth');
        // $address = $wmodel->generateAddress();
        // $walletRow->user_id = $mUser->id;
        // $walletRow->wallet_type = 'eth';
        // $walletRow->wallet_address = $address['address'];
        // $walletRow->public = $address['public'];
        // $walletRow->private = $address['private'];
        // $walletRow->save();

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $verificationid = $mUser['email_token'];
        $msg = "Please click the following link to verify your email:<br><a href='".$_SERVER['HTTP_HOST']."/verify/email/".$verificationid."' >".$_SERVER['HTTP_HOST']."/verify/email/".$verificationid."</a>
        <br/><br/>
        Thanks<br/>
        P2Coin.net";
        mail($data['email'], "Please verify your email", $msg, $headers);
        return $mUser;
        //return view('verify.email')->with('email_token', $verificationid);
    }
    /**
     * Handle a registration request for the application.
     *
     * @param $token
     * @return \Illuminate\Http\Response
     */
    public function emailverify($token)
    {
        $user = User::where('email_token',$token)->first();
        $user->verified = 1;
        if($user->save()){

//            $userWallet = new UserWallet();
//            $model = new WalletManage();
//            $wallet_arr = $model->generateWallet();
//            $walletRow->user_id = $user->id;
//            $walletRow->wallet_address = $wallet_arr['address'];
//            $walletRow->save();
            return view('auth.login');
        }
    }

    public function verifyready() {
        return view('auth.verifyready');
    }
}

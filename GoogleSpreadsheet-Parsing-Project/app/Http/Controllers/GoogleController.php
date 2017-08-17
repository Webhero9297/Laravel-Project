<?php

namespace App\Http\Controllers;

use Laravel\Socialite\SocialiteServiceProvider;
use Socialite;
use App\Http\Controllers;
use App\Http\Controllers\Auth;
use Illuminate\Http\Request;

use App\Models\GoogleAccount;
use Session;
class GoogleController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback(Request $request)
    {
        try{
//            dd($request->code);
            $user = Socialite::driver('google')->user();

//            $id = '0B3laAeybcy8dfk1kZ0d1ZFZrMUlSZUxzRU9RVDRwQ0xUX1VFTGxNZ18xZjFNZjBianVfc1U';
//            dd($driver);
            $token = $user->token;
            $userName   = $user->getName();
            $useremail  = $user->getEmail();
            $userAvatar = $user->getAvatar();
            $userId     = $user->getId();
            $code = $request->code;
            $state = $request->state;
            $gAccount = new GoogleAccount();
            $gAccount->setGoogleAccountInfo( $userName, $useremail, $userAvatar, $userId, $token, $code, $state);
            $userInfo = $gAccount->getGoogleAccountInfo();
//            Auth::login()
            session()->put('userinfo',$userInfo);
            session()->put('google_userinfo', $user);
//            dd($userInfo);
            return redirect()->route('dashboard');
        }
        catch ( Exception $e ){

        }
        
        // $user->token;
    }
}
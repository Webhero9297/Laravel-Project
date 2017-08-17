<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleAccount extends Model
{
    //
    private $username;
    private $useremail = '';
    private $userAvatar = '';
    private $userID = '';
    private $token = '';
    private $tokenSecret = '';
    private $refreshToken = '';
    private $expiresIn = '';
    private $code = '';
    private $state = '';

    public function setGoogleAccountInfo( $username, $useremail, $userAvatar, $userID, $token, $code, $state ) {
        $this->username = $username;
        $this->useremail = $useremail;
        $this->userAvatar = $userAvatar;
        $this->userID = $userID;
        $this->token = $token;
        $this->code = $code;
        $this->state = $state;
    }
    public function getGoogleAccountInfo() {
        return array('name'=>$this->username, 'email'=>$this->useremail, 'userAvatar'=>$this->userAvatar, 'userID'=>$this->userID, 'token'=>$this->token, 'state'=>$this->state, 'code'=>$this->code);
    }
}

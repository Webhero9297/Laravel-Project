<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Auth2fa extends Model
{
    //
    protected $table="tbl_2fa_auth_by_user";
    public function is2faAuthed($user_id) {
        $resp = self::all()->where('user_id', '=',$user_id)->toArray();
        // dd(count($resp));
        if (count($resp)>0) 
            return true;
        else
            return false;
    }
    public function addAuthInfo( $user_id, $key_str ) {
        $resp = self::is2faAuthed($user_id);
        $curDateTime = date("Y-m-d H:i:s");
        if ($resp)
            DB::table($this->table)->where('user_id','=',$user_id)->update(['key_str'=>$key_str,'updated_at'=>$curDateTime]);
        else
            DB::table($this->table)->insert(['user_id'=>$user_id, 'key_str'=>$key_str, 'created_at'=>$curDateTime, 'updated_at'=>$curDateTime]);
    }
    public function getKeyByUserId( $user_id ) {
        $auth_info = self::all()->where('user_id', '=', $user_id)->first();
        if (is_null($auth_info)) return null;
        return $auth_info->key_str;
    }
}

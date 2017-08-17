<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    //
    protected $table = 'user_log';
    public function getLastDataByUserEmail($email) {
        $row_data = self::all()->where('email','=', $email)->sortByDesc('login_at');
        if (count($row_data)>0){
            $row = $row_data[count($row_data)-1];
            return array('id'=>$row->id,
                'email'=>$row->email, 'givenName'=>$row->firstname, 'familyName'=>$row->lastname,
                'login_at'=>$row->login_at, 'refresh_token'=>$row->refresh_token, 'status_id'=>$row->status_id, 'item_id'=>$row->item_id, 'item_title'=>$row->item_title);
        }
        return false;
//        if ( count($row_data )
    }
}

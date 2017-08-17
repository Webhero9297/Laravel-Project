<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisputeHistory extends Model
{
    //
    protected $table = 'dispute_history';
    public function getDisputeStatus($transaction_id) {
        $data = self::all()->where('transaction_id', '=', $transaction_id)->toArray();
        if (count($data)>0) return true; else return false;
    }
}

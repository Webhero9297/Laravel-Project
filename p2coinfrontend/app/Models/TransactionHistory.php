<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{
    //
    protected $table = 'transaction_history';
    public function getDataByContractId($contract_id) {
        return self::all()->where('contract_id', '=', $contract_id)->first();
    }
}

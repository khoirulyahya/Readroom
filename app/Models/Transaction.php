<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public function member()
    {
        return $this->belongsTo('App\Models\Member','member_id');
    }

    public function transactionDetails()
    {
        return $this->hasMany('App\Models\TransactionDetail','transaction_id');
    }
}

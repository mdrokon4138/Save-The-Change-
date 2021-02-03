<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    protected $fillable = [
        'user_id', 'amount', 'bank_name','account_number','note',
    ];

    protected $table = 'withdraws';
}

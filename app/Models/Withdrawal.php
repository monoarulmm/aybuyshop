<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = ['user_id', 'amount', 'payment_method', 'account_number', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

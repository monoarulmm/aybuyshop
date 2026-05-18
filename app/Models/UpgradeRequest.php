<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpgradeRequest extends Model
{
    use HasFactory;

    // এই অংশটুকু যোগ করুন বা আপডেট করুন
    protected $fillable = [
        'user_id',
        'target_package',
        'amount_to_pay',
        'payment_type',
        'transaction_id',
        'status'
    ];

    /**
     * ইউজারের সাথে রিলেশনশিপ (ঐচ্ছিক কিন্তু জরুরি)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

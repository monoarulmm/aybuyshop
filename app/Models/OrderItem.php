<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{

 protected $fillable = [
        'product_id',
        'product_note',
        'price',
        'quantity',
    ];


public function product()
    {
        // এখানে নিশ্চিত করুন আপনার ফরেন কি 'product_id' আছে কি না
        return $this->belongsTo(Product::class, 'product_id');
    }
}

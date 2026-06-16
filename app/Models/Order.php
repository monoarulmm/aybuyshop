<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'total_amount', 'wallet_paid', 'cash_on_delivery', 'status', 'phone', 'address', 'note'];


    // app/Models/Order.php

    public function products()
    {
        // এখানে দ্বিতীয় প্যারামিটার হিসেবে আপনার সঠিক টেবিলের নাম দিন (যেমন: order_items)
        return $this->belongsToMany(Product::class, 'order_items')
            ->withPivot('quantity', 'price');
    }


    public function items()
    {
        // এখানে নিশ্চিত করুন আপনার ফরেন কি 'order_id' আছে কি না
        return $this->hasMany(OrderItem::class, 'order_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    use HasFactory;

    /**
     * যে ফিল্ডগুলো মাস অ্যাসাইনমেন্ট করা যাবে।
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id', // যদি প্রোডাক্টের সাথে লিঙ্ক থাকে
        'image_path', // আপনার কাঙ্ক্ষিত ফিল্ড
    ];

    /**
     * প্রোডাক্টের সাথে রিলেশনশিপ (যদি প্রয়োজন হয়)
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

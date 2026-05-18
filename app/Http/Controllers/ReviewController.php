<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function create($id)
    {
        $product = \App\Models\Product::findOrFail($id);
        return view('content.users.shop.review_create', compact('product'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'required|string|max:500',
        ]);

        \App\Models\Review::create([
            'user_id'    => auth()->id(),
            'product_id' => $request->product_id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        return redirect()->route('orders.my')->with('success', 'রিভিউ সফলভাবে জমা হয়েছে!');
    }
}

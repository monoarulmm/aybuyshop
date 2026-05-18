<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminManagementController extends Controller
{
    // অ্যাডমিন তৈরির ফর্ম দেখানো
    public function create()
    {
        return view('content.admin.create_admin');
    }

    // নতুন অ্যাডমিন বা স্টাফ সেভ করা
    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required',
        ]);

        $user = User::create([
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 'active',
            'type' => 'premium_pro', // মাইগ্রেশনে টাইপ ফিল্ডটি যেহেতু নাল-এবল না
            'transaction_id' => 'ADMIN_CREATED_' . time(),
            'paid_amount' => 0,
        ]);

        if ($user) {
            return redirect()->route('admin.dashboard')->with('success', 'Admin created!');
        }

        return back()->with('error', 'Something went wrong!');
    }



    // সকল অর্ডার দেখার পেজ
    public function orders()
    {
        $orders = Order::with('user')->latest()->paginate(20);
        return view('content.admin.orders.index', compact('orders'));
    }

    // স্ট্যাটাস আপডেট করার মেথড
    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // 'delivered' অপশনটি এখানে যোগ করা হয়েছে
        $request->validate([
            'status' => 'required|in:pending,active,rejected,delivered'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        // ঐচ্ছিক: অর্ডার ডেলিভারড হলে যদি বিশেষ কোনো কাজ করতে চান (যেমন স্টক কমানো বা মেইল পাঠানো)
        if ($request->status == 'delivered') {
            // এখানে আপনার ডেলিভারি পরবর্তী লজিক লিখতে পারেন
        }

        return back()->with('success', 'অর্ডারের স্ট্যাটাস সফলভাবে "' . ucfirst($request->status) . '" করা হয়েছে!');
    }
}

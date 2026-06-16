<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserWelcomeMail;
use App\Mail\AdminOrderMail;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    // প্রোডাক্ট লিস্ট দেখানো
    public function index()
    {
        $products = Product::where('stock', '>', 0)->latest()->get();
        return view('content.users.shop.index', compact('products'));
    }

    public function cartIndex()
    {
        return view('content.users.shop.cart');
    }

    public function removeFromCart(Request $request)
    {
        \Cart::remove($request->id);
        return back()->with('success', 'আইটেম রিমুভ করা হয়েছে।');
    }

    public function addToCart(Request $request)
    {
        \Cart::add([
            'id' => $request->id,
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => 1,
            'attributes' => [
                'thumbnail' => $request->thumbnail,
                'product_note' => null // ডিফল্ট কন্ডিশন
            ]
        ]);
        return back()->with('success', 'প্রোডাক্ট কার্টে যুক্ত হয়েছে!');
    }

    // কার্ট থেকে কোয়ান্টিটি বাড়ানো বা কমানো
    public function updateCart(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'quantity' => 'required|in:1,-1' // শুধুমাত্র ১ অথবা -১ গ্রহণ করবে
        ]);

        $cartItem = \Cart::get($request->id);

        if ($cartItem) {
            // যদি ইউজার ১টি থাকা অবস্থায় আরও কমাতে চায় (অর্থাৎ ০ করতে চায়), তবে সেটি রিমুভ হয়ে যাবে
            if ($cartItem->quantity == 1 && $request->quantity == -1) {
                \Cart::remove($request->id);
                return redirect()->back()->with('success', 'পণ্যটি কার্ট থেকে মুছে ফেলা হয়েছে!');
            }

            // কোয়ান্টিটি আপডেট (প্লাস হলে ১ যোগ হবে, মাইনাস হলে ১ বিয়োগ হবে)
            \Cart::update($request->id, [
                'quantity' => [
                    'relative' => true,
                    'value' => $request->quantity
                ]
            ]);
        }

        return redirect()->back()->with('success', 'কার্ট আপডেট করা হয়েছে!');
    }

    // সিকেএডিটর থেকে আসা নোট কার্ট মেমরিতে সেভ করার মেথড 
    // (নোট: এখন ডিরেক্ট অর্ডারের কারণে এটি আর প্রয়োজন হবে না, তবুও ব্যাকআপ হিসেবে ফিক্সড রাখা হলো)
    public function updateProductNote(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'product_note' => 'nullable|string|max:500'
        ]);

        $item = \Cart::get($request->id);

        if ($item) {
            $currentAttributes = $item->attributes->toArray();
            $currentAttributes['product_note'] = $request->product_note;

            \Cart::update($request->id, [
                'attributes' => $currentAttributes
            ]);

            return redirect()->back()->with('success', 'পণ্যের স্পেসিফিকেশন নোট আপডেট করা হয়েছে!');
        }

        return redirect()->back()->with('error', 'পণ্যটি খুঁজে পাওয়া যায়নি।');
    }

    // মূল অর্ডার প্লেস করার মেথড (সম্পূর্ণ কারেক্টেড)
    public function placeOrder(Request $request)
    {
        $user = Auth::user();

        // ১. ভ্যালিডেশন
        $request->validate([
            'name'          => $user ? 'nullable|string|max:255' : 'required|string|max:255',
            'phone'         => ($user && $user->phone) ? 'nullable|string|max:20' : 'required|string|max:20',
            'address'       => ($user && $user->address) ? 'nullable|string' : 'required|string',
            'email'         => 'nullable|email',
            'product_notes' => 'nullable|array', // নতুন পাঠানো নোট অ্যারে ভ্যালিডেশন
        ]);

        if (\Cart::isEmpty()) {
            return back()->with('error', 'আপনার কার্ট খালি!');
        }

        $cartItems = \Cart::getContent();
        $totalAmount = \Cart::getTotal();
        $passwordSent = null;

        // ২. গেস্ট ইউজার হ্যান্ডেলিং
        if (!$user) {
            $passwordSent = Str::random(8);
            $user = User::create([
                'name'     => $request->name,
                'phone'    => $request->phone,
                'email'    => $request->email,
                'address'  => $request->address,
                'password' => Hash::make($passwordSent),
                'role'     => 'n_user',
                'status'   => 'active',
            ]);

            try {
                Mail::to($user->email)->send(new \App\Mail\UserWelcomeMail($user, $passwordSent));
            } catch (\Exception $e) {
                \Log::error('Customer Welcome Mail Error: ' . $e->getMessage());
            }
            Auth::login($user);
        }

        // ৩. ওয়ালেট ব্যালেন্স ক্যালকুলেশন
        $walletPaid = 0;
        $totalEarnings = DB::table('user_earnings')->where('user_id', $user->id)->sum('amount');

        if ($user->role !== 'n_user' && in_array($user->type, ['basic', 'premium', 'premium_pro'])) {
            if ($totalEarnings > 0) {
                $walletPaid = min($totalEarnings, $totalAmount);
            }
        }

        $cashOnDelivery = $totalAmount - $walletPaid;

        // ৪. ডাটাবেজ ট্রানজ্যাকশন
        try {
            $order = DB::transaction(function () use ($user, $request, $totalAmount, $walletPaid, $cashOnDelivery, $cartItems) {

                $finalPhone = $request->phone ?? $user->phone;
                $finalAddress = $request->address ?? $user->address;

                // ৪.১ অর্ডার টেবিল এ এন্ট্রি
                $order = Order::create([
                    'user_id'          => $user->id,
                    'total_amount'     => $totalAmount,
                    'wallet_paid'      => $walletPaid,
                    'cash_on_delivery' => $cashOnDelivery,
                    'status'           => 'pending',
                    'phone'            => $finalPhone,
                    'address'          => $finalAddress,
                    'note'             => $request->note ?? 'Direct Shop Order',
                ]);

                // ৪.২ ব্যালেন্স মাইনাস
                if ($walletPaid > 0) {
                    DB::table('user_earnings')->insert([
                        'user_id'    => $user->id,
                        'amount'     => -$walletPaid,
                        'type'       => 'shopping',
                        'date'       => now()->toDateString(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // ৪.৩ অর্ডার আইটেমস সেভিং (এখানেই মূল কারেকশনটি করা হয়েছে)
                foreach ($cartItems as $item) {
                    // ফ্রন্টএন্ড থেকে আসা ডাইনামিক 'product_notes[id]' অ্যারে থেকে ডেটা নেওয়া হচ্ছে
                    $singleProductNote = $request->input("product_notes.{$item->id}") ?? null;

                    DB::table('order_items')->insert([
                        'order_id'     => $order->id,
                        'product_id'   => $item->id,
                        'price'        => $item->price,
                        'quantity'     => $item->quantity,
                        'product_note' => $singleProductNote, // 👈 এখন ডাটাবেজে পারফেক্টলি নোট সেভ হবে
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]);
                }

                return $order;
            });

            // ৫. এডমিনকে মেইল পাঠানো
            try {
                $adminEmail = 'kwab.bd@gmail.com
';
                Mail::to($adminEmail)->send(new \App\Mail\AdminOrderMail($order));
            } catch (\Exception $e) {
                \Log::error('Admin Mail Error: ' . $e->getMessage());
            }

            // কার্ট মেমরি খালি করা
            \Cart::clear();

            return redirect()->route('shop.index')->with('success', "অর্ডার সফল হয়েছে!" . ($passwordSent ? " পাসওয়ার্ড মেইলে পাঠানো হয়েছে।" : ""));
        } catch (\Exception $e) {
            \Log::error('Order General Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'অর্ডার সম্পন্ন করা যায়নি। সমস্যা: ' . $e->getMessage());
        }
    }

    public function myOrders()
    {
        $orders = \App\Models\Order::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('content.users.shop.order_index', compact('orders'));
    }

    public function show($id)
    {
        $product = Product::with(['reviews.user'])->findOrFail($id);
        return view('content.users.shop.show', compact('product'));
    }
}
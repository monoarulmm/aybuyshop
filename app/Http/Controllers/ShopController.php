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
        return back()->with('success', 'আইটেম রিমুভ করা হয়েছে।');
    }
    public function addToCart(Request $request)
    {
        \Cart::add([
            'id' => $request->id,
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => 1,
            'attributes' => [
                'thumbnail' => $request->thumbnail
            ]
        ]);
        return back()->with('success', 'প্রোডাক্ট কার্টে যুক্ত হয়েছে!');
    }



    public function placeOrder(Request $request)
    {
        $user = Auth::user();

        // ১. ভ্যালিডেশন (লজিক উন্নত করা হয়েছে)
        $request->validate([
            'name'    => $user ? 'nullable|string|max:255' : 'required|string|max:255',
            'phone'   => ($user && $user->phone) ? 'nullable|string|max:20' : 'required|string|max:20',
            'address' => ($user && $user->address) ? 'nullable|string' : 'required|string',
            'email'   => 'nullable|email',
        ]);

        if (\Cart::isEmpty()) {
            return back()->with('error', 'আপনার কার্ট খালি!');
        }

        $cartItems = \Cart::getContent();
        $totalAmount = \Cart::getTotal();
        $passwordSent = null;

        // ২. গেস্ট ইউজার হ্যান্ডেলিং (যদি লগইন না থাকে)
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

                // ফোন এবং অ্যাড্রেস নিশ্চিত করা (লগইন ইউজার হলে প্রোফাইল থেকে নিবে, না থাকলে ইনপুট থেকে)
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

                // ৪.৩ অর্ডার আইটেমস
                foreach ($cartItems as $item) {
                    DB::table('order_items')->insert([
                        'order_id'   => $order->id,
                        'product_id' => $item->id,
                        'price'      => $item->price,
                        'quantity'   => $item->quantity,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                return $order;
            });

            // ৫. এডমিনকে মেইল পাঠানো (ইমেইল সরাসরি স্ট্রিং হিসেবে দেওয়া হয়েছে)
            try {
                // কন্ট্রোলারের ভিতরে এভাবে লিখুন
                $adminEmail = 'monoarulislam.cse@gmail.com';
                Mail::to($adminEmail)->send(new \App\Mail\AdminOrderMail($order));
            } catch (\Exception $e) {
                \Log::error('Admin Mail Error: ' . $e->getMessage());
            }

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
            ->paginate(10); // প্রতি পেজে ১০টি করে অর্ডার দেখাবে

        return view('content.users.shop.order_index', compact('orders'));
    }


    public function show($id)
    {
        // প্রোডাক্টের সাথে রিভিউ এবং রিভিউ প্রদানকারী ইউজারদের তথ্য লোড করা
        $product = Product::with(['reviews.user'])->findOrFail($id);

        return view('content.users.shop.show', compact('product'));
    }
}

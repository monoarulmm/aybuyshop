<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->get();
        return view('content.admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('content.admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // ১. ভ্যালিডেশন (বানান ঠিক করা হয়েছে: descripton -> description)
        $request->validate([
            'name'        => 'required',
            'description' => 'required', // বানান ঠিক করা হয়েছে
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric',
            'stock'       => 'required|integer',
            'thumbnail'   => 'required|image|max:5120', // ৫ এমবি পর্যন্ত অনুমতি
            'gallery.*'   => 'nullable|image|max:5120'
        ]);

        // ২. থাম্বনেইল আপলোড
        $thumbnailPath = $request->file('thumbnail')->store('products/thumbnails', 'public');

        // ৩. প্রোডাক্ট সেভ
        $product = Product::create([
            'name'        => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'price'       => $request->price,
            'thumbnail'   => $thumbnailPath,
            'stock'       => $request->stock,
        ]);

        // ৪. গ্যালারি ইমেজ আপলোড
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $path = $file->store('products/gallery', 'public');
                // এখানে নিশ্চিত করুন আপনার Product মডেলে gallery() রিলেশন আছে
                $product->gallery()->create(['image_path' => $path]);
            }
        }

        return redirect()->back()->with('success', 'প্রোডাক্ট সফলভাবে আপলোড হয়েছে!');
    }


    // এডিট পেজ (যেখানে এরর আসছিল)
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('content.admin.products.edit', compact('product', 'categories'));
    }

    // ডাটা আপডেট এবং ইমেজ রিপ্লেস
    // আপডেট মেথড
    public function update(Request $request, Product $product)
    {
        // ১. ভ্যালিডেশন
        $request->validate([
            'name'        => 'required|max:255',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric',
            'stock'       => 'required|integer',
            'thumbnail'   => 'nullable|image|max:5120',
            'gallery.*'   => 'nullable|image|max:5120',
        ]);

        // ২. ডাটা রিসিভ (ইমেজ বাদে)
        $data = $request->only(['name', 'category_id', 'price', 'description', 'stock']);

        // ৩. থাম্বনেইল আপডেট
        if ($request->hasFile('thumbnail')) {
            // পুরানো ইমেজ ডিলিট করা
            if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            // নতুন ইমেজ স্টোর
            $data['thumbnail'] = $request->file('thumbnail')->store('products/thumbnails', 'public');
        }

        // ৪. মেইন প্রোডাক্ট আপডেট
        $product->update($data);

        // ৫. গ্যালারি ইমেজ আপডেট (নতুনগুলো যোগ করা)
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $path = $file->store('products/gallery', 'public');
                $product->gallery()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('products.index')->with('success', 'প্রোডাক্ট সফলভাবে আপডেট হয়েছে!');
    }
    // প্রোডাক্ট ডিলিট এবং স্টোরেজ থেকে সব ইমেজ ক্লিনআপ
    public function destroy(Product $product)
    {
        // ১. মেইন থাম্বনেইল ডিলিট
        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        // ২. গ্যালারির সব ইমেজ ডিলিট
        foreach ($product->gallery as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        // ৩. ডাটাবেজ থেকে ডিলিট
        $product->delete();

        return back()->with('success', 'Product and all images deleted!');
    }
}

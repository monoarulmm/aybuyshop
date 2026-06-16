<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Video;
use App\Models\Product;
use App\Models\UserEarning;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{


   public function home(){
     $settings = DB::table('site_settings')->first();
        return view('content.users.welcome', compact('settings'));
   }


    public function index(Request $request)
    {
        // ১. যারা লগইন নেই (Guest) অথবা যাদের রোল 'n_user'
        // তারা শুধু ভিডিও দেখবে কিন্তু তাদের আর্নিং ডাটাবেজে জমানো হবে না।
        if (!Auth::check() || Auth::user()->role === 'n_user') {
            $videos = Video::where('status', true)->latest()->paginate(6);

            return view('content.users.home', [
                'videos' => $videos,
                'isGuestOrNormal' => true // ব্লেডে কন্ডিশন চেক করার জন্য
            ]);
        }

        $user = Auth::user();

        // ২. অ্যাডমিন বা সুপার অ্যাডমিন হলে ড্যাশবোর্ডে রিডাইরেক্ট
        if (in_array($user->role, ['admin', 'super_admin'])) {
            return view('content.admin.dashboard');
        }

        // ৩. প্রিমিয়াম ইউজার টাইপ অনুযায়ী ডেইলি লিমিট সেট করা (আর্নিং ইউজারদের জন্য)
        $limit = 10; // Default for basic premium
        if ($user->type === 'premium') {
            $limit = 20;
        } elseif ($user->type === 'premium_pro') {
            $limit = 50;
        }

        // ৪. আজকের দেখা ভিডিওগুলোর ID সংগ্রহ (যাতে একই ভিডিও বারবার দেখে টাকা না পায়)
        $watchedVideoIds = UserEarning::where('user_id', $user->id)
            ->where('type', 'video_view')
            ->whereDate('created_at', \Carbon\Carbon::today())
            ->pluck('video_id')
            ->toArray();

        $todayTasksCount = count($watchedVideoIds);

        // ৫. লিমিট শেষ হয়ে গেলে
        if ($todayTasksCount >= $limit) {
            return view('content.users.home', [
                'videos' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 6),
                'taskCompleted' => true,
                'todayTasksCount' => $todayTasksCount,
                'limit' => $limit
            ]);
        }

        // ৬. আর্নিং ইউজারদের জন্য আজ যা দেখা হয়নি সেগুলো দেখানো
        $videos = Video::where('status', true)
            ->whereNotIn('id', $watchedVideoIds)
            ->latest()
            ->paginate(6);

        return view('content.users.home', compact('videos', 'todayTasksCount', 'limit'));
    }

  
  
    public function completeTask(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated']);
        }

        // ১. ভিডিওটি ডাটাবেজে আছে কি না চেক
        $video = Video::find($request->video_id);
        if (!$video) {
            return response()->json(['success' => false, 'message' => 'ভিডিওটি পাওয়া যায়নি!']);
        }

        // ২. ইউজার লিমিট চেক
        $limit = 10;
        if ($user->type === 'premium') {
            $limit = 20;
        } elseif ($user->type === 'premiumpro') {
            $limit = 50;
        }

        $todayTasksCount = UserEarning::where('user_id', $user->id)
            ->where('type', 'video_view')
            ->whereDate('created_at', Carbon::today())
            ->count();

        if ($todayTasksCount >= $limit) {
            return response()->json(['success' => false, 'message' => 'আপনার আজকের লিমিট শেষ।']);
        }

        // ৩. ডাবল আর্নিং প্রোটেকশন
        $alreadyDone = UserEarning::where('user_id', $user->id)
            ->where('video_id', $video->id)
            ->whereDate('created_at', Carbon::today())
            ->exists();

        if ($alreadyDone) {
            return response()->json(['success' => false, 'message' => 'এই ভিডিওটি আজ দেখা হয়েছে!']);
        }

        // ৪. আর্নিং সেভ করা (Database Transaction ব্যবহার করা ভালো)
        try {
            DB::beginTransaction();

            UserEarning::create([
                'user_id'  => $user->id,
                'video_id' => $video->id,
                'amount'   => $video->earning_per_view,
                'type'     => 'video_view',
                'date'     => Carbon::today()->toDateString(), // Y-m-d format
            ]);

            // ইউজারের ব্যালেন্স যদি User টেবিলের 'balance' কলামে থাকে তবে সেটা আপডেট করুন (ঐচ্ছিক)
            // $user->increment('balance', $video->earning_per_view);

            DB::commit();

            return response()->json(['success' => true, 'message' => '৳' . $video->earning_per_view . ' সফলভাবে যোগ হয়েছে!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'ত্রুটি হয়েছে: ' . $e->getMessage()]);
        }
    }


    public function packages()
    {
        return view('content.users.packages');
    }
    public function privecy()
    {
        return view('content.users.privecy');
    }
    public function about()
    {
        $settings = DB::table('site_settings')->first();
        return view('content.users.about_contact', compact('settings'));
    }








    public function search(Request $request)
    {
        $query = $request->input('query');

        // যদি সার্চ ইনপুট খালি না থাকে, তবে ডাটাবেজে খুঁজবে
        $products = Product::when($query, function ($q) use ($query) {
            return $q->where('name', 'LIKE', "%{$query}%")
                     ->orWhere('description', 'LIKE', "%{$query}%");
        })
        ->latest()
        ->get();

        // আপনার শপ ইনডেক্স ব্লেড ফাইলটি রিটার্ন করবে (পার্থটি আপনার প্রজেক্ট অনুযায়ী চেক করে নিবেন)
        return view('content.users.shop.index', compact('products', 'query'));
    }
}

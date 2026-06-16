<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::latest()->get();
        return view('content.admin.videos.index', compact('videos'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'video_type' => 'required',
                'local_video' => 'required_if:video_type,local|file|mimes:mp4,mov,avi|max:102400',
            ], [
                'local_video.max' => 'ভিডিওটি অনেক বড়! আপনি সর্বোচ্চ ১০০ এমবি পর্যন্ত ভিডিও আপলোড করতে পারবেন।',
                'local_video.mimes' => 'শুধুমাত্র mp4, mov, বা avi ফরম্যাট সাপোর্ট করে।'
            ]);

            $video = new Video();
            $video->title = $request->title;
            $video->video_type = $request->video_type;
            $video->earning_per_view = $request->earning_per_view ?? 0;
            $video->duration = $request->duration;

            if ($request->video_type === 'local' && $request->hasFile('local_video')) {
                $video->local_video = $request->file('local_video')->store('videos', 'public');
            } else {
                $video->video_url = $request->video_url;
            }

            $video->save();
            return back()->with('success', 'ভিডিও সফলভাবে আপলোড হয়েছে!');
        } catch (\Illuminate\Http\Exceptions\PostTooLargeException $e) {
            return back()->with('error', 'ফাইল সাইজ খুব বড়! দয়া করে ১০০ এমবি-র নিচের ভিডিও ফাইল দিন।')->withInput();
        } catch (Exception $e) {
            return back()->with('error', 'সমস্যা হয়েছে: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * ভিডিও এডিট পেজ দেখানোর মেথড
     */
    public function edit(Video $video)
    {
        // এখানে আপনার এডিট ব্লেড ফাইলের পাথটি দিবেন (যেমন: content.admin.videos.edit)
        return view('content.admin.videos.edit', compact('video'));
    }

    /**
     * ভিডিও আপডেট করার মেথড
     */
    public function update(Request $request, Video $video)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'video_type' => 'required',
                // আপডেটের সময় ফাইল রিকোয়ার্ড না, দিলে সর্বোচ্চ ১০০ এমবি
                'local_video' => 'nullable|file|mimes:mp4,mov,avi|max:102400',
            ], [
                'local_video.max' => 'ভিডিওটি অনেক বড়! আপনি সর্বোচ্চ ১০০ এমবি পর্যন্ত ভিডিও আপলোড করতে পারবেন।',
                'local_video.mimes' => 'শুধুমাত্র mp4, mov, বা avi ফরম্যাট সাপোর্ট করে।'
            ]);

            $video->title = $request->title;
            $video->video_type = $request->video_type;
            $video->earning_per_view = $request->earning_per_view ?? 0;
            $video->duration = $request->duration;

            if ($request->video_type === 'local') {
                // যদি নতুন লোকাল ফাইল আপলোড করা হয়
                if ($request->hasFile('local_video')) {
                    // পুরোনো লোকাল ভিডিও ফাইলটি সার্ভার থেকে ডিলিট করা
                    if ($video->local_video && Storage::disk('public')->exists($video->local_video)) {
                        Storage::disk('public')->delete($video->local_video);
                    }
                    // নতুন ফাইল স্টোর করা
                    $video->local_video = $request->file('local_video')->store('videos', 'public');
                }
                $video->video_url = null; // টাইপ লোকাল হলে URL ফাকা করে দেওয়া
            } else {
                // টাইপ ইউটিউব/ফেসবুক হলে যদি আগে লোকাল ফাইল থাকে তা ডিলিট করা
                if ($video->local_video && Storage::disk('public')->exists($video->local_video)) {
                    Storage::disk('public')->delete($video->local_video);
                }
                $video->local_video = null;
                $video->video_url = $request->video_url;
            }

            $video->save();
            
            // ইনডেক্স পেজে রিডাইরেক্ট করা (আপনার রাউট অনুযায়ী চেঞ্জ করতে পারেন)
            return redirect()->route('admin.videos.index')->with('success', 'ভিডিওটি সফলভাবে আপডেট করা হয়েছে!');
            
        } catch (\Illuminate\Http\Exceptions\PostTooLargeException $e) {
            return back()->with('error', 'ফাইল সাইজ খুব বড়! আপডেট করা সম্ভব হয়নি।')->withInput();
        } catch (Exception $e) {
            return back()->with('error', 'সমস্যা হয়েছে: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Video $video)
    {
        if ($video->video_type === 'local' && $video->local_video) {
            if (Storage::disk('public')->exists($video->local_video)) {
                Storage::disk('public')->delete($video->local_video);
            }
        }
        $video->delete();
        return back()->with('success', 'ভিডিওটি সফলভাবে ডিলিট করা হয়েছে!');
    }
}
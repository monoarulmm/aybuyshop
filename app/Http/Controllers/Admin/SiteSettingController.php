<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // এটি অ্যাড করুন

class SiteSettingController extends Controller
{
    public function index()
    {
        $setting = SiteSetting::first();
        return view('content.admin.settings', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name'    => 'required|string|max:255',
            'logo'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'favicon'      => 'nullable|image|mimes:png,ico,svg|max:1024',
            'main_banner'  => 'nullable|image|max:5120',
            'sponsor_banner.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $setting = SiteSetting::first() ?? new SiteSetting();

        $setting->site_name = $request->site_name;
        $setting->email = $request->email;
        $setting->phone_primary = $request->phone_primary;
        $setting->phone_secondary = $request->phone_secondary;
        $setting->address = $request->address;
        $setting->fb_link = $request->fb_link;
        $setting->youtube_link = $request->youtube_link;
        $setting->whatsapp_link = $request->whatsapp_link;

        // সিঙ্গেল ইমেজ হ্যান্ডলিং (Storage ব্যবহার করে)
        $singleImages = ['logo', 'favicon', 'main_banner'];
        foreach ($singleImages as $img) {
            if ($request->hasFile($img)) {
                // আগের ফাইল থাকলে ডিলিট করা
                if ($setting->$img) {
                    Storage::disk('public')->delete($setting->$img);
                }

                // নতুন ফাইল স্টোর করা (storage/app/public/settings এ সেভ হবে)
                $path = $request->file($img)->store('settings', 'public');
                $setting->$img = $path;
            }
        }

        // মাল্টিপল স্পন্সর ব্যানার হ্যান্ডলিং
        $sponsorBanners = json_decode($setting->sponsor_banner, true) ?? [];

        if ($request->has('remove_sponsors')) {
            foreach ($request->remove_sponsors as $index) {
                if (isset($sponsorBanners[$index])) {
                    Storage::disk('public')->delete($sponsorBanners[$index]);
                    unset($sponsorBanners[$index]);
                }
            }
            $sponsorBanners = array_values($sponsorBanners);
        }

        if ($request->hasFile('sponsor_banner')) {
            foreach ($request->file('sponsor_banner') as $file) {
                $path = $file->store('settings', 'public');
                $sponsorBanners[] = $path;
            }
        }

        $setting->sponsor_banner = json_encode($sponsorBanners);
        $setting->save();

        return redirect()->back()->with('success', 'Settings Updated Successfully!');
    }
}

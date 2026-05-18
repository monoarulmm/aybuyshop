<div style="background: #111; color: #fff; padding: 20px; font-family: sans-serif; border-radius: 10px;">
    <h2 style="color: #facc15;">স্বাগতম, {{ $user->name }}!</h2>
    <p>আপনার অ্যাকাউন্ট সফলভাবে তৈরি করা হয়েছে। আপনি এখন থেকে এই পাসওয়ার্ড দিয়ে লগইন করতে পারবেন:</p>
    <div
        style="background: #222; padding: 10px; border: 1px solid #444; display: inline-block; font-size: 20px; letter-spacing: 2px;">
        <strong>{{ $password }}</strong>
    </div>
    <p style="margin-top: 20px; font-size: 12px; color: #888;">ধন্যবাদ, আমাদের শপে কেনাকাটা করার জন্য।</p>
</div>

<div x-show="showAd" x-transition x-cloak
    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/90 backdrop-blur-md">
    <div
        class="relative max-w-sm w-full bg-[#161925] rounded-[2rem] overflow-hidden shadow-[0_0_50px_rgba(234,179,8,0.2)] border border-yellow-500/20">

        <button @click="showAd = false"
            class="absolute top-4 right-4 z-50 bg-white/10 text-white w-8 h-8 rounded-full flex items-center justify-center hover:bg-red-500 transition">
            ✕
        </button>

        <div class="p-8 text-center">
            <div class="w-20 h-20 bg-yellow-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-4xl">🎁</span>
            </div>
            <h3 class="text-2xl font-bold text-white">স্পেশাল অফার!</h3>
            <p class="text-gray-400 text-sm mt-3">
                এখনই একাউন্ট আপডেট করুন আর পান দ্বিগুণ আয়ের সুযোগ। সীমিত সময়ের জন্য!
            </p>
            <a href="/packages"
                class="mt-6 block w-full bg-yellow-500 text-black font-bold py-4 rounded-2xl shadow-lg shadow-yellow-500/20 hover:scale-[1.02] active:scale-95 transition">
                প্যাকেজ দেখুন
            </a>
        </div>
    </div>
</div>

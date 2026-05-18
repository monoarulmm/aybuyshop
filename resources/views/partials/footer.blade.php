<footer class="bg-[#0f111a] border-t border-white/5 pt-20 pb-10 pt-10">
    <div class="max-w-7xl mx-auto px-4 lg:px-10">
        {{-- Main Footer Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-20">

            {{-- Brand Section --}}
            <div class="space-y-8">
                <a href="/" class="inline-block">

                    <img src="{{ asset('storage/' . $settings->logo) }}" class="h-10 md:h-12 object-contain"
                        alt="Logo">

                </a>
                <p class="text-gray-400 text-sm leading-relaxed max-w-xs">
                    {{ $settings->site_description ?? 'সহজ উপায়ে ভিডিও দেখে আয় করার নির্ভরযোগ্য প্ল্যাটফর্ম। আমাদের সাথে আপনার ফ্রিল্যান্সিং যাত্রা শুরু করুন আজই।' }}
                </p>
                {{-- Social Icons --}}
                <div class="flex gap-3">
                    @if ($settings->fb_link)
                        <a href="{{ $settings->fb_link }}" target="_blank"
                            class="w-10 h-10 rounded-xl bg-blue-600/10 flex items-center justify-center text-blue-500 hover:bg-blue-600 hover:text-white transition-all duration-300 shadow-lg shadow-blue-600/5">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    @endif
                    @if ($settings->whatsapp_link)
                        <a href="{{ $settings->whatsapp_link }}" target="_blank"
                            class="w-10 h-10 rounded-xl bg-emerald-600/10 flex items-center justify-center text-emerald-500 hover:bg-emerald-600 hover:text-white transition-all duration-300 shadow-lg shadow-emerald-600/5">
                            <i class="fab fa-whatsapp text-lg"></i>
                        </a>
                    @endif

                </div>
            </div>

            {{-- Quick Links --}}
            <div>
                <h4 class="text-white font-black mb-8 text-xs uppercase tracking-[0.2em] italic">Quick Navigation</h4>
                <ul class="space-y-5">

                    <li><a href="{{ route('pakages') }}"
                            class="text-gray-400 hover:text-yellow-500 text-sm font-bold flex items-center gap-2 transition-all group"><span
                                class="w-1 h-1 bg-yellow-500 rounded-full opacity-0 group-hover:opacity-100 transition-all"></span>
                            Premium Plans</a></li>
                    <li><a href="/about"
                            class="text-gray-400 hover:text-yellow-500 text-sm font-bold flex items-center gap-2 transition-all group"><span
                                class="w-1 h-1 bg-yellow-500 rounded-full opacity-0 group-hover:opacity-100 transition-all"></span>
                            About Platform</a></li>
                </ul>
            </div>

            {{-- Support & Legal --}}
            <div>
                <h4 class="text-white font-black mb-8 text-xs uppercase tracking-[0.2em] italic">Legal & Support</h4>
                <ul class="space-y-5">
                    <li><a href="/privacy-policy"
                            class="text-gray-400 hover:text-yellow-500 text-sm font-bold flex items-center gap-2 transition-all group"><span
                                class="w-1 h-1 bg-yellow-500 rounded-full opacity-0 group-hover:opacity-100 transition-all"></span>
                            Privacy Policy</a></li>
                    <li><a href="/privacy-policy"
                            class="text-gray-400 hover:text-yellow-500 text-sm font-bold flex items-center gap-2 transition-all group"><span
                                class="w-1 h-1 bg-yellow-500 rounded-full opacity-0 group-hover:opacity-100 transition-all"></span>
                            Terms of Service</a></li>

                </ul>
            </div>

            {{-- Contact Info --}}
            <div>
                <h4 class="text-white font-black mb-8 text-xs uppercase tracking-[0.2em] italic">Get In Touch</h4>
                <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-indigo-400 border border-white/5">
                            <i class="fas fa-envelope text-xs"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest">Email Us</p>
                            <p class="text-gray-300 text-sm font-bold">{{ $settings->email ?? 'support@TaKa ID24.com' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-yellow-500 border border-white/5">
                            <i class="fas fa-phone-alt text-xs"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest">Call Center</p>
                            <p class="text-gray-300 text-sm font-bold">
                                {{ $settings->phone_primary ?? '+880 1XXX XXXXXX' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Copyright & Developer Section --}}
        <div class="pt-10 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="text-xs text-gray-500 font-medium">
                &copy; {{ date('Y') }} <span
                    class="text-white font-black">{{ $settings->site_name ?? 'TaKa ID 24' }}</span>. Built for earners.
            </p>

            {{-- Developed By Section --}}
            <div class="flex items-center gap-3 bg-white/5 px-5 py-2.5 rounded-2xl border border-white/5">
                <span class="text-[10px] text-gray-500 font-black uppercase tracking-widest">Developed By</span>
                <a href="#"
                    class="text-xs font-black text-white hover:text-yellow-500 transition-all italic tracking-tighter">
                    Monoar <span class="text-indigo-400">IT</span> Solutions
                </a>
            </div>
        </div>
    </div>
</footer>

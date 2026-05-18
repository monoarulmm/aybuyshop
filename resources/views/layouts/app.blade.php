<!DOCTYPE html>
<html lang="bn">

@php
    // যদি লেআউট ফাইল থেকে $settings না আসে, তবে এখানে কোয়েরি করে নেওয়া ভালো
    $settings = $settings ?? \DB::table('site_settings')->first();
@endphp

@include('partials.head')

<body class="bg-[#0a0c14] text-gray-200" x-data="{ sidebarOpen: false, showAd: true }">

    {{-- @include('partials.promo-modal'); --}}

    @include('partials.header')

    <div class="flex pt-16 h-screen overflow-hidden">

        {{-- SweetAlert মেসেজসমূহ --}}
        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'সফল!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#2563eb'
                });
            </script>
        @endif

        @if ($errors->any())
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'ভুল হয়েছে!',
                    html: '{!! implode('<br>', $errors->all()) !!}',
                    confirmButtonColor: '#dc2626'
                });
            </script>
        @endif
        @include('partials.sidebar')

        <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 lg:p-10 pb-28">
            {{-- @include('partials.hero') --}}

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <div class="lg:col-span-3">
                    @yield('content')
                </div>

                <div class="hidden lg:block space-y-6">
                    @include('partials.right-ad-bar')


                </div>
            </div>
            @include('partials.footer')

        </main>
    </div>

    @include('partials.mobile_nav')
    @include('partials.script')

</body>

</html>

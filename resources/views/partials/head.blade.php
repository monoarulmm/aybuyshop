<head>
    <meta charset="UTF-8">
    {{-- মোবাইল জুম এবং ডিজাইন ফিক্সড রাখার জন্য নিচের মেটা ট্যাগটি যুক্ত করা হয়েছে --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $settings->site_name ?? 'TaKa ID 24' }} | Watch & Earn</title>

    {{-- ফ্যাভিকন পাথ কারেকশন: সরাসরি public/uploads থেকে লোড হবে --}}
    @if ($settings && $settings->favicon)
        <link rel="icon" type="image/x-icon" href="{{ asset($settings->favicon) }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;600;700&display=swap" rel="stylesheet">

    {{-- Alpine.js x-cloak স্টাইল সহ যুক্ত করা হয়েছে --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://www.youtube.com/iframe_api"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: 'Hind Siliguri', sans-serif;
            scroll-behavior: smooth;
            {{-- মোবাইলে ডানে-বামে নড়াচড়া বন্ধ করতে --}} overflow-x: hidden;
            touch-action: pan-x pan-y;
        }

        [x-cloak] {
            display: none !important;
        }

        .glass-nav {
            background: rgba(15, 17, 26, 0.95);
            backdrop-filter: blur(12px);
        }

        .premium-card {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
            border: 1px solid rgba(99, 102, 241, 0.3);
        }

        .coin-shimmer {
            animation: shimmer 2s infinite linear;
            background: linear-gradient(90deg, #facc15 0%, #eab308 50%, #facc15 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        @keyframes shimmer {
            to {
                background-position: 200% center;
            }
        }
    </style>
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* স্মুথ স্ক্রলিং এর জন্য */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

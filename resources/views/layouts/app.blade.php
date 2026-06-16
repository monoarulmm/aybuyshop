<!DOCTYPE html>
<html lang="bn">

@php
    $settings = $settings ?? \DB::table('site_settings')->first();
    $phase2Enabled = $settings->phase2_enabled ?? false;
    $cartItems = session('cart', []);
    $cartCount = is_array($cartItems) ? count($cartItems) : 0;
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $settings->site_name ?? 'AyBuyShop' }} | @yield('title', 'Online Shop')</title>

  @if ($settings && $settings->favicon)
    <link rel="icon" type="image/x-icon" href="{{ asset($settings->favicon) }}">
@else
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.png') }}">
@endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Sora:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://www.youtube.com/iframe_api"></script>

    <style>
        :root {
            --bg-page:    #f4f6fb;
            --bg-white:   #ffffff;
            --bg-surface: #f0f2f8;
            --bg-hover:   #e6e9f4;
            --border:     rgba(60,70,130,0.10);
            --border-md:  rgba(60,70,130,0.16);
            --gold:       #e8920a;
            --gold-lt:    #fff8ed;
            --gold-bdr:   rgba(232,146,10,0.28);
            --indigo:     #4f46e5;
            --indigo-dk:  #4338ca;
            --indigo-lt:  #eef2ff;
            --indigo-bdr: rgba(79,70,229,0.22);
            --green:      #059669;
            --red:        #dc2626;
            --text-1:     #1a1d2e;
            --text-2:     #4b5473;
            --text-3:     #9aa3c2;
            --shadow-sm:  0 1px 4px rgba(60,70,130,0.07);
            --shadow-md:  0 4px 20px rgba(60,70,130,0.10);
            --shadow-lg:  0 12px 40px rgba(60,70,130,0.14);
            --radius-sm:  10px;
            --radius-md:  14px;
            --radius-lg:  20px;
            --radius-xl:  28px;
            --sidebar-w:  252px;
            --nav-h:      64px;
            --tr:         all 0.2s cubic-bezier(0.4,0,0.2,1);
        }

        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Hind Siliguri', sans-serif;
            background: var(--bg-page);
            color: var(--text-1);
            overflow-x: hidden;
            margin: 0;
        }
        [x-cloak] { display: none !important; }

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(79,70,229,0.15); border-radius: 5px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(79,70,229,0.28); }

        /* ════════════════════════════════════════
           TOPNAV
        ════════════════════════════════════════ */
        .topnav {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: var(--nav-h);
            background: var(--bg-white);
            border-bottom: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px 0 16px;
            z-index: 100;
            gap: 12px;
        }

        .topnav-left { display: flex; align-items: center; gap: 10px; }

        .nav-hamburger {
            display: none;
            width: 40px; height: 40px;
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            align-items: center; justify-content: center;
            cursor: pointer;
            color: var(--text-2);
            transition: var(--tr);
            flex-shrink: 0;
        }
        .nav-hamburger:hover { background: var(--indigo-lt); color: var(--indigo); }
        @media (max-width: 1023px) { .nav-hamburger { display: flex; } }

        .nav-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .nav-logo img { height: 38px; object-fit: contain; }
        .nav-logo-fallback {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--gold), #f97316);
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Sora', sans-serif;
            font-weight: 800; font-size: 16px; color: #fff;
        }
        .nav-logo-text {
            font-family: 'Sora', sans-serif;
            font-size: 19px; font-weight: 800;
            color: var(--text-1); letter-spacing: -0.5px;
        }
        .nav-logo-text span { color: var(--gold); }

        /* Search – desktop */
        .nav-search {
            flex: 1; max-width: 440px;
            position: relative;
            display: flex; align-items: center;
        }
        .nav-search input {
            width: 100%;
            background: var(--bg-surface);
            border: 1.5px solid var(--border-md);
            border-radius: 50px;
            padding: 10px 50px 10px 20px;
            font-size: 13.5px;
            color: var(--text-1);
            font-family: 'Hind Siliguri', sans-serif;
            outline: none;
            transition: var(--tr);
        }
        .nav-search input::placeholder { color: var(--text-3); }
        .nav-search input:focus {
            border-color: var(--indigo);
            background: var(--indigo-lt);
            box-shadow: 0 0 0 3px rgba(79,70,229,0.10);
        }
        .nav-search-btn {
            position: absolute; right: 6px;
            width: 36px; height: 36px;
            background: var(--indigo); border: none;
            border-radius: 50px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: var(--tr);
        }
        .nav-search-btn:hover { background: var(--indigo-dk); }
        .nav-search-btn i { color: #fff; font-size: 13px; }
        @media (max-width: 640px) { .nav-search { display: none; } }

        /* Search toggle – mobile */
        .nav-search-toggle {
            display: none;
            width: 40px; height: 40px;
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            align-items: center; justify-content: center;
            cursor: pointer; color: var(--text-2);
            transition: var(--tr);
        }
        .nav-search-toggle:hover { background: var(--indigo-lt); color: var(--indigo); }
        @media (max-width: 640px) { .nav-search-toggle { display: flex; } }

        /* Mobile search drawer */
        .mobile-search-drawer {
            display: none;
            position: fixed;
            top: var(--nav-h); left: 0; right: 0;
            background: var(--bg-white);
            border-bottom: 1px solid var(--border-md);
            box-shadow: var(--shadow-md);
            padding: 14px 16px;
            z-index: 99;
            gap: 10px;
            align-items: center;
            animation: slideDown 0.22s ease;
        }
        .mobile-search-drawer.open { display: flex; }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .mobile-search-drawer input {
            flex: 1;
            background: var(--bg-surface);
            border: 1.5px solid var(--border-md);
            border-radius: 50px;
            padding: 11px 20px;
            font-size: 14px;
            color: var(--text-1);
            font-family: 'Hind Siliguri', sans-serif;
            outline: none; transition: var(--tr);
        }
        .mobile-search-drawer input:focus { border-color: var(--indigo); }
        .mobile-search-drawer .ms-search-btn {
            width: 42px; height: 42px;
            background: var(--indigo); border: none;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; flex-shrink: 0; transition: var(--tr);
        }
        .mobile-search-drawer .ms-search-btn:hover { background: var(--indigo-dk); }
        .mobile-search-drawer .ms-close-btn {
            width: 42px; height: 42px;
            background: var(--bg-surface);
            border: 1px solid var(--border-md);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: var(--text-2); flex-shrink: 0;
            transition: var(--tr);
        }
        .mobile-search-drawer .ms-close-btn:hover { background: var(--bg-hover); color: var(--red); }

        /* Nav right */
        .nav-right { display: flex; align-items: center; gap: 6px; }

        .nav-cart {
            position: relative;
            width: 40px; height: 40px;
            display: flex; align-items: center; justify-content: center;
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text-2); text-decoration: none;
            transition: var(--tr);
        }
        .nav-cart:hover { background: var(--gold-lt); border-color: var(--gold-bdr); color: var(--gold); }
        .nav-cart-badge {
            position: absolute; top: -5px; right: -5px;
            width: 18px; height: 18px;
            background: var(--red); color: #fff;
            font-size: 9px; font-weight: 700;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid var(--bg-white);
        }

        .nav-divider { width: 1px; height: 24px; background: var(--border-md); margin: 0 4px; }

        .nav-balance {
            display: flex; align-items: center; gap: 7px;
            background: var(--gold-lt);
            border: 1px solid var(--gold-bdr);
            border-radius: 50px;
            padding: 7px 14px;
            box-shadow: var(--shadow-sm);
        }
        .nav-balance-icon {
            width: 20px; height: 20px;
            background: var(--gold); border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 10px; font-weight: 800; color: #fff; flex-shrink: 0;
        }
        .nav-balance span { font-size: 13px; font-weight: 700; color: var(--gold); white-space: nowrap; }
        @media (max-width: 480px) { .nav-balance { display: none; } }

        .nav-avatar {
            width: 38px; height: 38px;
            border-radius: var(--radius-sm);
            object-fit: cover;
            border: 2px solid var(--border-md);
            cursor: pointer; transition: var(--tr);
        }
        .nav-avatar:hover { border-color: var(--indigo); }

        .nav-user-menu {
            position: absolute;
            top: calc(var(--nav-h) + 8px); right: 16px;
            width: 252px;
            background: var(--bg-white);
            border: 1px solid var(--border-md);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            z-index: 200; overflow: hidden;
        }
        .user-menu-header {
            padding: 18px 20px;
            background: linear-gradient(135deg, var(--indigo-lt), var(--gold-lt));
            border-bottom: 1px solid var(--border);
        }
        .user-menu-header p:first-child {
            font-size: 10px; font-weight: 700;
            color: var(--indigo); text-transform: uppercase;
            letter-spacing: 0.1em; margin-bottom: 4px;
        }
        .user-menu-header p:last-child { font-size: 20px; font-weight: 700; color: var(--text-1); }
        .user-menu-item {
            display: flex; align-items: center; gap: 10px;
            padding: 12px 18px;
            font-size: 13.5px; font-weight: 600;
            color: var(--text-2); text-decoration: none;
            transition: var(--tr); cursor: pointer;
        }
        .user-menu-item:hover { background: var(--bg-surface); color: var(--indigo); }
        .user-menu-item i { color: var(--indigo); width: 16px; text-align: center; }
        .user-menu-item.danger { color: var(--red); }
        .user-menu-item.danger i { color: var(--red); }
        .user-menu-item.danger:hover { background: #fff5f5; }
        .user-menu-divider { height: 1px; background: var(--border); margin: 4px 0; }

        .btn-login {
            font-size: 13px; font-weight: 600;
            color: var(--text-2); background: none; border: none;
            cursor: pointer; padding: 7px 12px;
            transition: var(--tr); text-decoration: none;
        }
        .btn-login:hover { color: var(--indigo); }

        .btn-register {
            font-size: 12px; font-weight: 800;
            color: #fff; background: var(--indigo);
            border: none; border-radius: 50px;
            padding: 9px 20px; cursor: pointer;
            text-transform: uppercase; letter-spacing: 0.05em;
            text-decoration: none; transition: var(--tr);
            box-shadow: 0 4px 14px rgba(79,70,229,0.28);
            white-space: nowrap;
        }
        .btn-register:hover { background: var(--indigo-dk); transform: translateY(-1px); }

        /* ════════════════════════════════════════
           LAYOUT WRAPPER
        ════════════════════════════════════════ */
        .layout-wrapper {
            display: flex;
            padding-top: var(--nav-h);
            min-height: 100vh;
        }

        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(20,24,60,0.40);
            z-index: 90;
            backdrop-filter: blur(3px);
        }
        @media (max-width: 1023px) { .sidebar-overlay.active { display: block; } }

        /* ════════════════════════════════════════
           SIDEBAR
        ════════════════════════════════════════ */
        .sidebar {
            position: fixed;
            top: var(--nav-h); left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: var(--bg-white);
            border-right: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            display: flex; flex-direction: column;
            z-index: 95;
            transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
        }
        @media (max-width: 1023px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: var(--shadow-lg); }
        }

        .sidebar-close-btn {
            display: none;
            position: absolute; top: 12px; right: 12px;
            width: 30px; height: 30px;
            background: var(--bg-surface);
            border: 1px solid var(--border-md);
            border-radius: 50%;
            align-items: center; justify-content: center;
            cursor: pointer; color: var(--text-2);
            font-size: 14px; transition: var(--tr);
            z-index: 2;
        }
        .sidebar-close-btn:hover { background: #fff0f0; color: var(--red); }
        @media (max-width: 1023px) { .sidebar-close-btn { display: flex; } }

        .sidebar-scroll {
            flex: 1; overflow-y: auto;
            padding: 16px 12px;
        }

        /* Profile card */
        .sidebar-profile {
            background: linear-gradient(135deg, var(--indigo-lt), #f0fdf9);
            border: 1px solid var(--indigo-bdr);
            border-radius: var(--radius-md);
            padding: 14px; margin-bottom: 20px;
        }
        .sidebar-profile-row { display: flex; align-items: center; gap: 10px; }
        .sidebar-avatar {
            width: 42px; height: 42px;
            border-radius: 12px;
            background: var(--indigo);
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; font-weight: 700; color: #fff;
            flex-shrink: 0; overflow: hidden;
        }
        .sidebar-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .sidebar-profile-name { font-size: 13.5px; font-weight: 700; color: var(--text-1); }
        .sidebar-profile-role {
            font-size: 10px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.08em;
            color: var(--indigo); margin-top: 2px;
        }
        .sidebar-profile-bar {
            margin-top: 10px; height: 4px;
            background: rgba(79,70,229,0.10);
            border-radius: 4px; overflow: hidden;
        }
        .sidebar-profile-bar-fill { height: 100%; background: var(--indigo); border-radius: 4px; width: 65%; }

        /* Section labels */
        .sidebar-section-label {
            font-size: 9px; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.18em;
            color: var(--text-3);
            padding: 0 10px;
            margin: 18px 0 6px;
        }

        .sidebar-link {
            display: flex; align-items: center; gap: 11px;
            padding: 10px 13px;
            border-radius: var(--radius-sm);
            font-size: 13.5px; font-weight: 600;
            color: var(--text-2); text-decoration: none;
            transition: var(--tr); margin-bottom: 1px;
        }
        .sidebar-link:hover { background: var(--bg-surface); color: var(--indigo); }
        .sidebar-link.active {
            background: var(--indigo-lt);
            color: var(--indigo);
            border-left: 3px solid var(--indigo);
        }
        .sidebar-link i { font-size: 17px; width: 20px; text-align: center; flex-shrink: 0; }
        .sidebar-link-badge {
            margin-left: auto;
            font-size: 8.5px; font-weight: 800;
            padding: 3px 8px; border-radius: 20px;
            background: var(--gold-lt); color: var(--gold);
            border: 1px solid var(--gold-bdr);
            text-transform: uppercase;
        }

        /* Shop grid */
        .sidebar-shop-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 7px; margin-top: 4px; }
        .sidebar-shop-tile {
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            gap: 6px; padding: 13px 6px;
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            text-decoration: none;
            color: var(--text-2);
            font-size: 10.5px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.05em;
            transition: var(--tr);
        }
        .sidebar-shop-tile:hover { background: var(--bg-hover); color: var(--indigo); border-color: var(--indigo-bdr); }
        .sidebar-shop-tile i { font-size: 20px; }
        .sidebar-shop-tile.gold { color: var(--gold); border-color: var(--gold-bdr); background: var(--gold-lt); }
        .sidebar-shop-tile.gold:hover { background: #fde9c2; }
        .sidebar-shop-tile.purple { color: var(--indigo); border-color: var(--indigo-bdr); background: var(--indigo-lt); }
        .sidebar-shop-tile.purple:hover { background: #e0e7ff; }

        /* Phase 2 teaser */
        .phase2-teaser {
            margin: 16px 4px 0;
            background: linear-gradient(135deg, var(--gold-lt), var(--indigo-lt));
            border: 1px solid var(--gold-bdr);
            border-radius: var(--radius-md); padding: 14px;
        }
        .phase2-teaser-label { font-size: 9.5px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: var(--gold); margin-bottom: 5px; }
        .phase2-teaser-desc { font-size: 12px; color: var(--text-2); line-height: 1.55; }
        .phase2-pill {
            display: inline-block; margin-top: 8px;
            background: var(--gold-lt); border: 1px solid var(--gold-bdr);
            color: var(--gold); font-size: 8.5px; font-weight: 800;
            padding: 3px 10px; border-radius: 20px;
            text-transform: uppercase; letter-spacing: 0.08em;
        }

        /* Admin block */
        .sidebar-admin-block {
            margin-top: 12px;
            background: #fff5f5;
            border: 1px solid rgba(220,38,38,0.14);
            border-radius: var(--radius-md); padding: 8px;
        }
        .sidebar-admin-label {
            display: flex; align-items: center; gap: 6px;
            font-size: 9px; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.15em;
            color: rgba(220,38,38,0.65);
            padding: 6px 8px 8px;
        }
        .sidebar-admin-label .pulse { width: 6px; height: 6px; background: var(--red); border-radius: 50%; animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:0.35;} }
        .sidebar-admin-link {
            display: flex; align-items: center; gap: 9px;
            padding: 9px 10px; border-radius: 8px;
            font-size: 12.5px; font-weight: 600;
            color: var(--text-2); text-decoration: none;
            transition: var(--tr); margin-bottom: 1px;
        }
        .sidebar-admin-link:hover { background: rgba(220,38,38,0.06); color: var(--red); }
        .sidebar-admin-link i { font-size: 14px; width: 18px; text-align: center; }

        /* Sidebar footer */
        .sidebar-footer { padding: 14px; border-top: 1px solid var(--border); text-align: center; }
        .sidebar-footer p { font-size: 11px; color: var(--text-3); font-weight: 600; line-height: 1.7; }

        /* ════════════════════════════════════════
           MAIN CONTENT
        ════════════════════════════════════════ */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-w);
            display: flex; flex-direction: column;
            min-height: calc(100vh - var(--nav-h));
        }
        @media (max-width: 1023px) { .main-content { margin-left: 0; } }

        .main-inner {
            display: grid;
            grid-template-columns: 1fr 210px;
            gap: 24px;
            padding: 24px 20px;
            flex: 1;
        }
        @media (max-width: 1280px) {
            .main-inner { grid-template-columns: 1fr; }
            .sidebar-right-col { display: none; }
        }
        @media (max-width: 1023px) { .main-inner { padding: 18px 14px; } }

        /* ════════════════════════════════════════
           RIGHT AD SIDEBAR
        ════════════════════════════════════════ */
        .sidebar-right-col { display: flex; flex-direction: column; gap: 14px; }

        .ad-slot {
            background: var(--bg-white);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }
        .ad-slot-header {
            padding: 10px 14px 6px;
            font-size: 9.5px; font-weight: 800;
            color: var(--text-3); text-transform: uppercase;
            letter-spacing: 0.12em;
        }
        .ad-slot-img-wrap {
            position: relative; aspect-ratio: 9/5;
            cursor: pointer; overflow: hidden;
        }
        .ad-slot-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
        .ad-slot-img-wrap:hover img { transform: scale(1.06); }
        .ad-slot-overlay {
            position: absolute; inset: 0;
            background: rgba(79,70,229,0.32);
            opacity: 0; display: flex;
            align-items: center; justify-content: center;
            transition: var(--tr);
        }
        .ad-slot-img-wrap:hover .ad-slot-overlay { opacity: 1; }
        .ad-slot-overlay span {
            background: var(--bg-white); color: var(--indigo);
            font-size: 9px; font-weight: 800;
            padding: 5px 12px; border-radius: 20px;
            text-transform: uppercase;
        }

        .yt-embed-wrap {
            background: var(--bg-white);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }
        .yt-embed-header {
            padding: 10px 14px 6px;
            font-size: 9.5px; font-weight: 800;
            color: var(--text-3); text-transform: uppercase;
            letter-spacing: 0.12em;
        }
        .yt-embed-wrap iframe { display: block; width: 100%; aspect-ratio: 16/9; }

        /* ════════════════════════════════════════
           FOOTER
        ════════════════════════════════════════ */
        .footer {
            background: var(--bg-white);
            border-top: 1px solid var(--border);
            padding: 52px 20px 28px;
        }
        @media (max-width: 1023px) { .footer { margin-left: 0; } }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr 1fr 1fr;
            gap: 40px;
            max-width: 1100px;
            margin: 0 auto 40px;
        }
        @media (max-width: 900px) { .footer-grid { grid-template-columns: 1fr 1fr; gap: 28px; } }
        @media (max-width: 560px) { .footer-grid { grid-template-columns: 1fr; } }

        .footer-brand img { height: 36px; object-fit: contain; margin-bottom: 14px; display: block; }
        .footer-desc { font-size: 13.5px; color: var(--text-2); line-height: 1.75; max-width: 240px; }

        .footer-socials { display: flex; gap: 9px; margin-top: 18px; }
        .footer-social-btn {
            width: 38px; height: 38px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px;
            border: 1px solid var(--border-md);
            text-decoration: none; transition: var(--tr);
        }
        .footer-social-btn.fb { color: #2563eb; background: #eff6ff; }
        .footer-social-btn.fb:hover { background: #dbeafe; border-color: #93c5fd; }
        .footer-social-btn.wa { color: #059669; background: #f0fdf4; }
        .footer-social-btn.wa:hover { background: #dcfce7; border-color: #6ee7b7; }

        .footer-col-title {
            font-family: 'Sora', sans-serif;
            font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.14em;
            color: var(--text-1); margin-bottom: 18px;
        }
        .footer-link {
            display: flex; align-items: center; gap: 7px;
            font-size: 13.5px; font-weight: 600;
            color: var(--text-2); text-decoration: none;
            margin-bottom: 12px; transition: var(--tr);
        }
        .footer-link:hover { color: var(--indigo); padding-left: 4px; }

        .footer-contact-row { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 16px; }
        .footer-contact-icon {
            width: 36px; height: 36px; flex-shrink: 0;
            border-radius: 10px;
            background: var(--bg-surface);
            border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            font-size: 13px;
        }
        .footer-contact-icon.mail { color: var(--indigo); }
        .footer-contact-icon.phone { color: var(--gold); }
        .footer-contact-label { font-size: 9.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--text-3); margin-bottom: 3px; }
        .footer-contact-val { font-size: 12.5px; font-weight: 600; color: var(--text-1); }

        .footer-bottom {
            max-width: 1100px; margin: 0 auto;
            padding-top: 24px;
            border-top: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            gap: 16px; flex-wrap: wrap;
        }
        .footer-copyright { font-size: 12.5px; color: var(--text-2); }
        .footer-copyright strong { color: var(--text-1); font-weight: 700; }
        .footer-dev {
            display: flex; align-items: center; gap: 8px;
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: 50px; padding: 7px 18px;
            font-size: 11px; font-weight: 700;
        }
        .footer-dev span { color: var(--text-3); text-transform: uppercase; letter-spacing: 0.1em; }
        .footer-dev a { color: var(--indigo); text-decoration: none; font-style: italic; }
        .footer-dev a:hover { color: var(--gold); }

        /* ════════════════════════════════════════
           MOBILE BOTTOM NAV
        ════════════════════════════════════════ */
        .mobile-bottom-nav {
            display: none;
            position: fixed; bottom: 0; left: 0; right: 0;
            height: 68px;
            background: var(--bg-white);
            border-top: 1px solid var(--border-md);
            box-shadow: 0 -4px 20px rgba(60,70,130,0.10);
            z-index: 80;
            align-items: center; justify-content: space-around;
            padding: 0 8px;
        }
        @media (max-width: 1023px) { .mobile-bottom-nav { display: flex; } }

        .mob-nav-btn {
            display: flex; flex-direction: column;
            align-items: center; gap: 3px;
            background: none; border: none;
            cursor: pointer; color: var(--text-3);
            padding: 6px 10px; text-decoration: none;
            transition: var(--tr);
        }
        .mob-nav-btn.active, .mob-nav-btn:hover { color: var(--indigo); }
        .mob-nav-btn i { font-size: 21px; }
        .mob-nav-btn span { font-size: 8.5px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; }

        .mob-nav-center { position: relative; margin-top: -20px; }
        .mob-nav-center-btn {
            width: 54px; height: 54px;
            background: var(--indigo); border-radius: 50%;
            border: 4px solid var(--bg-page);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(79,70,229,0.32);
            transition: var(--tr);
        }
        .mob-nav-center-btn:hover { background: var(--indigo-dk); transform: scale(1.06); }
        .mob-nav-center-btn i { font-size: 22px; color: #fff; }

        /* ════════════════════════════════════════
           IMAGE FULLSCREEN MODAL
        ════════════════════════════════════════ */
        .img-modal {
            position: fixed; inset: 0; z-index: 999;
            background: rgba(0,0,0,0.88);
            backdrop-filter: blur(8px);
            display: flex; align-items: center; justify-content: center;
            padding: 20px;
        }
        .img-modal-close {
            position: absolute; top: 20px; right: 20px;
            width: 42px; height: 42px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.22);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: #fff; font-size: 18px;
            transition: var(--tr);
        }
        .img-modal-close:hover { background: rgba(255,255,255,0.22); }
        .img-modal img { max-width: 100%; max-height: 88vh; border-radius: var(--radius-md); object-fit: contain; }

        /* ════════════════════════════════════════
           MOBILE PADDING
        ════════════════════════════════════════ */
        @media (max-width: 1023px) {
            .main-content { padding-bottom: 80px; }
            .footer { padding-bottom: 90px; }
        }
    </style>
</head>

<body x-data="{
    sidebarOpen: false,
    userMenuOpen: false,
    imgModal: false,
    imgFull: '',
    searchOpen: false
}">

    {{-- ════════════════════════════════════════════
         TOP NAVIGATION
    ════════════════════════════════════════════ --}}
    <nav class="topnav">

        {{-- Left: hamburger + logo --}}
        <div class="topnav-left">
            <button
                class="nav-hamburger"
                @click="sidebarOpen = true"
                aria-label="Open menu">
                <i class="fas fa-bars fa-fw"></i>
            </button>

            <a href="/" class="nav-logo">
                @if ($settings && $settings->logo)
                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="{{ $settings->site_name ?? 'Logo' }}">
                @else
                    <div class="nav-logo-fallback">A</div>
                @endif
                <div class="nav-logo-text">{{ $settings->site_name ?? 'Ay<span>Buy</span>Shop' }}</div>
            </a>
        </div>

        {{-- Center: desktop search --}}
        <div class="nav-search">
    <input
        type="text"
        id="desktop-search-input"
        placeholder="পণ্য খুঁজুন..."
        value="{{ request('query') }}" {{-- সার্চ করার পর যেন লেখাটি ইনপুট বক্সে থেকে যায় --}}
        onkeydown="if(event.key==='Enter') doSearch('desktop-search-input')">
    
    <button class="nav-search-btn" onclick="doSearch('desktop-search-input')" aria-label="Search">
        <i class="fas fa-search"></i>
    </button>
</div>



        {{-- Right actions --}}
        <div class="nav-right">

            {{-- Mobile search icon --}}
            <button
                class="nav-search-toggle"
                @click="searchOpen = !searchOpen"
                aria-label="Toggle search">
                <i class="fas fa-search fa-fw"></i>
            </button>

            {{-- Cart --}}
            <a href="{{ route('cart.index') }}" class="nav-cart" title="Cart">
                <i class="fas fa-shopping-cart fa-fw" style="font-size:16px;"></i>
  <span class="bg-blue-600 text-white px-1.5 py-0.5 rounded-md text-[10px] font-black">
                        {{ \Cart::getContent()->count() }}
                    </span>                
            </a>

            @guest
                <div class="nav-divider"></div>
                <a href="{{ route('login') }}" class="btn-login">Login</a>
                <a href="{{ route('normal.register') }}" class="btn-register">Join Now</a>
            @endguest

            @auth
                @php
                    $userId = Auth::id();
                    $totalEarnings  = \DB::table('user_earnings')->where('user_id', $userId)->sum('amount');
                    $totalWithdrawn = \DB::table('withdrawals')->where('user_id', $userId)->whereIn('status', ['pending','approved'])->sum('amount');
                    $currentBalance = $totalEarnings - $totalWithdrawn;
                @endphp

                <div class="nav-balance">
                    <div class="nav-balance-icon">৳</div>
                    <span>{{ number_format($currentBalance, 2) }}</span>
                </div>

                <div class="nav-divider"></div>

                <div style="position:relative;">
                    <img
                        src="{{ Auth::user()->profile_images ?: 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=4f46e5&color=fff&bold=true' }}"
                        class="nav-avatar"
                        @click="userMenuOpen = !userMenuOpen"
                        @click.away="userMenuOpen = false"
                        alt="{{ Auth::user()->name }}">

                    <div class="nav-user-menu" x-show="userMenuOpen" x-cloak x-transition>
                        <div class="user-menu-header">
                            <p>Available Balance</p>
                            <p>৳ {{ number_format($currentBalance, 2) }}</p>
                        </div>
                        <a href="/profile"              class="user-menu-item"><i class="fas fa-user-circle fa-fw"></i> Profile</a>
                        <a href="/upgrade"              class="user-menu-item"><i class="fas fa-gem fa-fw"></i> Subscription</a>
                        <a href="{{ route('cart.index') }}" class="user-menu-item"><i class="fas fa-shopping-cart fa-fw"></i> My Cart</a>
                        <a href="/my-orders"            class="user-menu-item"><i class="fas fa-box fa-fw"></i> My Orders</a>
                        <div class="user-menu-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="user-menu-item danger" style="width:100%;border:none;background:none;text-align:left;font-family:inherit;cursor:pointer;">
                                <i class="fas fa-sign-out-alt fa-fw"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </nav>

    {{-- Mobile search drawer --}}
    <div class="mobile-search-drawer" :class="{ open: searchOpen }" id="mobile-search-drawer">
        <input
            type="text"
            id="mobile-search-input"
            placeholder="পণ্য খুঁজুন..."
            onkeydown="if(event.key==='Enter') doSearch('mobile-search-input')"
            autofocus>
        <button class="ms-search-btn" onclick="doSearch('mobile-search-input')" aria-label="Search">
            <i class="fas fa-search" style="color:#fff;font-size:14px;"></i>
        </button>
        <button class="ms-close-btn" @click="searchOpen = false" aria-label="Close">
            <i class="fas fa-times"></i>
        </button>
    </div>

    {{-- ════════════════════════════════════════════
         LAYOUT WRAPPER
    ════════════════════════════════════════════ --}}
    <div class="layout-wrapper">

        {{-- Mobile overlay --}}
        <div
            class="sidebar-overlay"
            :class="{ active: sidebarOpen }"
            @click="sidebarOpen = false">
        </div>

        {{-- ──────────── SIDEBAR ──────────── --}}
        <aside class="sidebar" :class="{ open: sidebarOpen }">

            <button class="sidebar-close-btn" @click="sidebarOpen = false" aria-label="Close menu">
                <i class="fas fa-times"></i>
            </button>

            <div class="sidebar-scroll">

                {{-- Profile block --}}
                @auth
                    <div class="sidebar-profile">
                        <div class="sidebar-profile-row">
                            <div class="sidebar-avatar">
                                @if (Auth::user()->profile_images)
                                    <img src="{{ Auth::user()->profile_images }}" alt="">
                                @else
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                @endif
                            </div>
                            <div>
                                <div class="sidebar-profile-name">{{ Str::limit(Auth::user()->name, 18) }}</div>
                                <div class="sidebar-profile-role">
                                    @if (Auth::user()->role === 'admin') 🛡️ Admin
                                    @elseif (Auth::user()->type) {{ Auth::user()->type }}
                                    @else 💎 Member
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="sidebar-profile-bar">
                            <div class="sidebar-profile-bar-fill"></div>
                        </div>
                    </div>
                @endauth

                {{-- Core navigation --}}
                <div class="sidebar-section-label">Core Platform</div>

                <a href="/" class="sidebar-link {{ request()->is('/') ? 'active' : '' }}" @click="sidebarOpen=false">
                    <i class="fas fa-home fa-fw"></i> Home
                </a>

                <a href="/shop" class="sidebar-link {{ request()->is('shop*') ? 'active' : '' }}" @click="sidebarOpen=false">
                    <i class="fas fa-store fa-fw"></i> Shop
                </a>

                @auth
                    @if (Auth::user()->role !== 'admin')
                        <a href="/pakages" class="sidebar-link {{ request()->is('pakages*') ? 'active' : '' }}" @click="sidebarOpen=false">
                            <i class="fas fa-star fa-fw"></i> Packages
                        </a>

                        {{-- Phase 2 block (uncomment when live) --}}
                        {{--
                        @if ($phase2Enabled)
                            <div class="sidebar-section-label" style="margin-top:14px;">Earn Money</div>
                            <a href="/watch" class="sidebar-link {{ request()->is('watch*') ? 'active' : '' }}" @click="sidebarOpen=false">
                                <i class="fas fa-play-circle fa-fw"></i> Watch & Earn
                                <span class="sidebar-link-badge">New</span>
                            </a>
                            <a href="/withdraw" class="sidebar-link {{ request()->is('withdraw*') ? 'active' : '' }}" @click="sidebarOpen=false">
                                <i class="fas fa-wallet fa-fw"></i> Withdraw
                            </a>
                        @endif
                        --}}

                        <div class="sidebar-section-label" style="margin-top:14px;">Shop & Orders</div>
                        <div class="sidebar-shop-grid">
                            <a href="{{ route('cart.index') }}" class="sidebar-shop-tile gold" @click="sidebarOpen=false">
                                <i class="fas fa-shopping-cart"></i> Cart
                            </a>
                            <a href="/my-orders" class="sidebar-shop-tile purple" @click="sidebarOpen=false">
                                <i class="fas fa-box"></i> Orders
                            </a>
                        </div>
                    @endif
                @endauth

                @guest
                    <div style="margin-top:16px; display:flex; flex-direction:column; gap:8px;">
                        <a href="{{ route('login') }}" class="sidebar-link" style="justify-content:center; background:var(--indigo-lt); border:1px solid var(--indigo-bdr); color:var(--indigo);">
                            <i class="fas fa-sign-in-alt fa-fw"></i> Sign In
                        </a>
                        <a href="{{ route('normal.register') }}" class="sidebar-link" style="justify-content:center; background:var(--gold-lt); border:1px solid var(--gold-bdr); color:var(--gold);">
                            <i class="fas fa-user-plus fa-fw"></i> Register
                        </a>
                    </div>
                @endguest

                {{-- Phase 2 teaser (remove when phase2 live) --}}
                @auth
                    @if (!$phase2Enabled && Auth::user()->role !== 'admin')
                        <div class="phase2-teaser">
                            <div class="phase2-teaser-label">🔒 Coming Soon</div>
                            <div class="phase2-teaser-desc">ভিডিও দেখে টাকা আয় করুন — শীঘ্রই আসছে!</div>
                            <span class="phase2-pill">Phase 2 — Watch & Earn</span>
                        </div>
                    @endif
                @endauth

                {{-- Admin section --}}
                @auth
                    @if (Auth::user()->role === 'admin')
                        <div class="sidebar-admin-block">
                            <div class="sidebar-admin-label">
                                <span class="pulse"></span> Administration
                            </div>
                            <a href="/admin/dashboard"  class="sidebar-admin-link"><i class="fas fa-tachometer-alt fa-fw"></i> Dashboard</a>
                            <a href="/admin/users"      class="sidebar-admin-link"><i class="fas fa-users fa-fw"></i> Users List</a>
                            <a href="/admin/withdraw"   class="sidebar-admin-link"><i class="fas fa-money-bill-wave fa-fw"></i> Payout Requests</a>
                            <a href="/orders"           class="sidebar-admin-link" style="color:#d97706;"><i class="fas fa-inbox fa-fw"></i> Customer Orders</a>
                            <div style="height:1px; background:rgba(220,38,38,0.08); margin:6px 8px;"></div>
                            <a href="/admin/products"   class="sidebar-admin-link"><i class="fas fa-box-open fa-fw"></i> Inventory</a>
                            <a href="/admin/categories" class="sidebar-admin-link"><i class="fas fa-folder fa-fw"></i> Categories</a>
                            <div style="height:1px; background:rgba(220,38,38,0.08); margin:6px 8px;"></div>
                            <a href="/admin/settings"   class="sidebar-admin-link"><i class="fas fa-cog fa-fw"></i> Settings</a>
                        </div>
                    @endif
                @endauth

                {{-- Help links --}}
                <div class="sidebar-section-label" style="margin-top:18px;">Help</div>
                <a href="/about"          class="sidebar-link" @click="sidebarOpen=false"><i class="fas fa-info-circle fa-fw"></i> About Us</a>
                <a href="/privacy-policy" class="sidebar-link" @click="sidebarOpen=false"><i class="fas fa-shield-alt fa-fw"></i> Privacy Policy</a>

            </div>

            <div class="sidebar-footer">
                <p>&copy; {{ date('Y') }} {{ $settings->site_name ?? 'AyBuyShop' }}<br>
                <span style="color:var(--text-3);font-weight:500;">Version 4.1.0</span></p>
            </div>
        </aside>

        {{-- ──────────── MAIN CONTENT ──────────── --}}
        <div class="main-content">

            {{-- Flash messages --}}
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            icon: 'success', title: 'সফল!',
                            text: "{{ session('success') }}",
                            confirmButtonColor: '#4f46e5',
                            background: '#fff', color: '#1a1d2e',
                        });
                    });
                </script>
            @endif

            @if (session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            icon: 'error', title: 'ভুল হয়েছে!',
                            text: "{{ session('error') }}",
                            confirmButtonColor: '#dc2626',
                            background: '#fff', color: '#1a1d2e',
                        });
                    });
                </script>
            @endif

            @if ($errors->any())
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            icon: 'error', title: 'Validation Error',
                            html: `{!! implode('<br>', $errors->all()) !!}`,
                            confirmButtonColor: '#dc2626',
                            background: '#fff', color: '#1a1d2e',
                        });
                    });
                </script>
            @endif


                <div>
                    @yield('content')
                </div>

               

            

            {{-- ──────────── FOOTER ──────────── --}}
            <footer class="footer">
                <div class="footer-grid">

                    <div class="footer-brand">
                        @if ($settings && $settings->logo)
                            <img src="{{ asset('storage/' . $settings->logo) }}" alt="{{ $settings->site_name ?? 'Logo' }}">
                        @endif
                        <p class="footer-desc">
                            {{ $settings->site_description ?? 'বাংলাদেশের সেরা অনলাইন শপিং প্ল্যাটফর্ম। সেরা দামে পণ্য কিনুন এবং পরিবারকে সেরাটা দিন।' }}
                        </p>
                        <div class="footer-socials">
                            @if ($settings->fb_link)
                                <a href="{{ $settings->fb_link }}" target="_blank" rel="noopener" class="footer-social-btn fb">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            @endif
                            @if ($settings->whatsapp_link)
                                <a href="{{ $settings->whatsapp_link }}" target="_blank" rel="noopener" class="footer-social-btn wa">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    <div>
                        <div class="footer-col-title">Quick Links</div>
                        <a href="/"        class="footer-link">Home</a>
                        <a href="/shop"    class="footer-link">Shop</a>
                        <a href="/pakages" class="footer-link">Packages</a>
                        <a href="/about"   class="footer-link">About Us</a>
                    </div>

                    <div>
                        <div class="footer-col-title">Legal & Help</div>
                        <a href="/privacy-policy" class="footer-link">Privacy Policy</a>
                        <a href="/privacy-policy" class="footer-link">Terms of Service</a>
                        <a href="/about"          class="footer-link">FAQ</a>
                    </div>

                    <div>
                        <div class="footer-col-title">Contact Us</div>
                        <div class="footer-contact-row">
                            <div class="footer-contact-icon mail"><i class="fas fa-envelope fa-fw"></i></div>
                            <div>
                                <div class="footer-contact-label">Email</div>
                                <div class="footer-contact-val">{{ $settings->email ?? 'support@example.com' }}</div>
                            </div>
                        </div>
                        <div class="footer-contact-row">
                            <div class="footer-contact-icon phone"><i class="fas fa-phone-alt fa-fw"></i></div>
                            <div>
                                <div class="footer-contact-label">Phone</div>
                                <div class="footer-contact-val">{{ $settings->phone_primary ?? '+880 1XXX XXXXXX' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer-bottom">
                    <p class="footer-copyright">
                        &copy; {{ date('Y') }} <strong>{{ $settings->site_name ?? 'AyBuyShop' }}</strong>. All rights reserved.
                    </p>
                    <div class="footer-dev">
                        <span>Developed by</span>
                        <a href="#"> IT Solutions</a>
                    </div>
                </div>
            </footer>

        </div>
    </div>

    {{-- ════════════════════════════════════════════
         MOBILE BOTTOM NAV
    ════════════════════════════════════════════ --}}
    <nav class="mobile-bottom-nav">
        <a href="/" class="mob-nav-btn {{ request()->is('/') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>

        <a href="/shop" class="mob-nav-btn {{ request()->is('shop*') ? 'active' : '' }}">
            <i class="fas fa-store"></i>
            <span>Shop</span>
        </a>

        <div class="mob-nav-center">
            <div class="mob-nav-center-btn" @click="sidebarOpen = true">
                <i class="fas fa-bars"></i>
            </div>
        </div>

        <a href="/my-orders" class="mob-nav-btn {{ request()->is('my-orders*') ? 'active' : '' }}">
            <i class="fas fa-box"></i>
            <span>Orders</span>
        </a>

        @auth
            <a href="{{ route('cart.index') }}" class="mob-nav-btn {{ request()->is('cart*') ? 'active' : '' }}" style="position:relative;">
                <i class="fas fa-shopping-cart"></i>
                <span class="bg-blue-600 text-white px-1.5 py-0.5 rounded-md text-[10px] font-black">
                        {{ \Cart::getContent()->count() }}
                    </span> 
                <span>Cart</span>
            </a>
        @endauth

        @guest
            <a href="{{ route('login') }}" class="mob-nav-btn">
                <i class="fas fa-sign-in-alt"></i>
                <span>Login</span>
            </a>
        @endguest
    </nav>

    {{-- ════════════════════════════════════════════
         IMAGE FULLSCREEN MODAL
    ════════════════════════════════════════════ --}}
    <div class="img-modal" x-show="imgModal" x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="imgModal=false"
        @keydown.escape.window="imgModal=false"
        style="display:none;">
        <div class="img-modal-close" @click="imgModal=false">
            <i class="fas fa-times"></i>
        </div>
        <div @click.stop>
            <img :src="imgFull" alt="Full view">
        </div>
    </div>

    {{-- ════════════════════════════════════════════
         SCRIPTS
    ════════════════════════════════════════════ --}}
    <script>
        /* ── Search ── */
        function doSearch(inputId) {
            const val = document.getElementById(inputId)?.value?.trim();
            if (val) window.location.href = '/shop?q=' + encodeURIComponent(val);
        }

        document.addEventListener('DOMContentLoaded', function () {
            ['desktop-search-input', 'mobile-search-input'].forEach(function (id) {
                const el = document.getElementById(id);
                if (el) el.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') doSearch(id);
                });
            });
        });

        /* ── Video players ── */
        let ytPlayers = {}, lastTime = {};
        const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
        const userRole   = "{{ Auth::check() ? Auth::user()->role : 'guest' }}";

        window.fbAsyncInit = function () {
            FB.init({ xfbml: true, version: 'v18.0' });
        };

        function playFBVideo(id) {
            const starter     = document.getElementById('fb-starter-' + id);
            const timerStatus = document.getElementById('fb-timer-status-' + id);
            const overlay     = document.getElementById('fb-overlay-' + id);
            starter.style.display = 'none';
            timerStatus.classList.remove('hidden');
            overlay.classList.remove('hidden');
            let timeLeft = parseInt(starter?.dataset?.duration ?? 30);
            timerStatus.innerHTML = `⏳ <span id="timer-count-${id}">${timeLeft}</span>s বাকি`;
            FB.XFBML.parse(document.getElementById('fb-container-' + id), function () {
                FB.Event.subscribe('xfbml.ready', function (msg) {
                    if (msg.type === 'video' && msg.id === 'fb-player-' + id) {
                        let p = msg.instance;
                        p.unmute(); p.play(); p.setVolume(1);
                    }
                });
            });
            let countdown = setInterval(() => {
                timeLeft--;
                let el = document.getElementById('timer-count-' + id);
                if (el) el.innerText = timeLeft;
                if (timeLeft <= 0) {
                    clearInterval(countdown);
                    overlay.classList.add('hidden');
                    timerStatus.innerHTML = "✅ সম্পন্ন!";
                    timerStatus.className = "absolute top-4 left-4 z-40 bg-green-600 text-white text-[10px] px-4 py-1.5 rounded-full font-black uppercase";
                    finishTask(id);
                }
            }, 1000);
        }

        function onYouTubeIframeAPIReady() {
            document.querySelectorAll('.yt-wrapper').forEach(w => {
                let tid = w.dataset.taskId;
                ytPlayers[tid] = new YT.Player('player-' + tid, {
                    videoId: w.dataset.videoId,
                    playerVars: { controls: 0, disablekb: 1, rel: 0, modestbranding: 1 },
                    events: {
                        onStateChange: (e) => {
                            let ui = document.getElementById('yt-ui-' + tid);
                            if (e.data === 1) { if (ui) ui.style.opacity = '0'; startSecurityYT(tid); }
                            else { if (ui) ui.style.opacity = '1'; }
                            if (e.data === 0) finishTask(tid);
                        }
                    }
                });
            });
        }

        function toggleYT(id) {
            if (!ytPlayers[id]) return;
            ytPlayers[id].getPlayerState() === 1
                ? ytPlayers[id].pauseVideo()
                : ytPlayers[id].playVideo();
        }

        function startSecurityYT(id) {
            setInterval(() => {
                if (ytPlayers[id] && ytPlayers[id].getPlayerState() === 1) {
                    let curr = ytPlayers[id].getCurrentTime();
                    if (curr - (lastTime[id] || 0) > 2.0) ytPlayers[id].seekTo(lastTime[id] || 0);
                    else lastTime[id] = curr;
                }
            }, 500);
        }

        function toggleLocal(id) {
            let v  = document.getElementById('local-' + id);
            let ui = document.getElementById('local-ui-' + id);
            if (!v) return;
            if (v.paused) {
                v.play();
                if (ui) ui.style.opacity = '0';
                v.onended = () => finishTask(id);
            } else {
                v.pause();
                if (ui) ui.style.opacity = '1';
            }
        }

        function finishTask(taskId) {
            if (!isLoggedIn) { showFinalMessage(taskId, false, "টাকা আয় করতে আগে লগইন করুন।"); return; }
            if (userRole === 'n_user') { showFinalMessage(taskId, false, "একটি প্যাকেজ দিয়ে একাউন্ট একটিভ করুন।"); return; }
            fetch("{{ route('complete.task') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ video_id: taskId })
            })
            .then(r => r.json())
            .then(data => showFinalMessage(taskId, data.success, data.message))
            .catch(() => showFinalMessage(taskId, false, "সার্ভার ত্রুটি! আবার চেষ্টা করুন।"));
        }

        function showFinalMessage(id, success, msg) {
            const overlay     = document.getElementById('msg-overlay-' + id);
            if (!overlay) return;
            const statusIcon  = document.getElementById('status-icon-' + id);
            const statusTitle = document.getElementById('status-title-' + id);
            const statusText  = document.getElementById('status-text-' + id);
            const actionBtn   = overlay.querySelector('button');
            if (statusIcon)  statusIcon.innerText  = success ? "✅" : "❌";
            if (statusTitle) statusTitle.innerText = success ? "সফল!"  : "ব্যর্থ!";
            if (statusText)  statusText.innerText  = msg;
            if (actionBtn) {
                if (userRole === 'n_user' || !isLoggedIn) {
                    actionBtn.innerText = "Upgrade Account";
                    actionBtn.onclick   = () => window.location.href = "/upgrade";
                } else {
                    actionBtn.innerText = "Collect & Next";
                    actionBtn.onclick   = () => location.reload();
                }
            }
            overlay.classList.remove('hidden');
        }
    </script>

    @stack('scripts')

    {{-- জাভাস্ক্রিপ্ট কোড (আপনার ব্লেডের একদম নিচে স্ক্রিপ্ট ট্যাগে রাখবেন) --}}
<script>
    function doSearch(inputId) {
        const searchInput = document.getElementById(inputId);
        const queryValue = searchInput.value.trim();

        // যদি ইউজার কিছু না লিখে সার্চ বাটনে চাপ দেয় তবে সার্চ হবে না
        if (queryValue === '') {
            window.location.href = "{{ route('shop.search') }}"; // খালি থাকলে সব প্রোডাক্ট দেখাবে
            return;
        }

        // লারাভেলের সার্চ রাউটে রিডাইরেক্ট করা হচ্ছে কুয়েরি প্যারামিটারসহ
        const searchUrl = "{{ route('shop.search') }}?query=" + encodeURIComponent(queryValue);
        window.location.href = searchUrl;
    }
</script>

</body>
</html>
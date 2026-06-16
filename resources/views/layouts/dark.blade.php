<!DOCTYPE html>
<html lang="bn">

@php
    $settings = $settings ?? \DB::table('site_settings')->first();
    $phase2Enabled = $settings->phase2_enabled ?? false;

    // Cart count (session based)
    $cartItems = session('cart', []);
    $cartCount = is_array($cartItems) ? count($cartItems) : 0;
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $settings->site_name ?? 'TaKa ID 24' }} | @yield('title', 'Online Shop')</title>

    @if ($settings && $settings->favicon)
        <link rel="icon" type="image/x-icon" href="{{ asset($settings->favicon) }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Sora:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- YouTube IFrame API — সবসময় লোড হবে (home page এ video আছে) --}}
    <script src="https://www.youtube.com/iframe_api"></script>

    <style>
        :root {
            --bg-deep:   #07090f;
            --bg-base:   #0b0d17;
            --bg-card:   #111422;
            --bg-hover:  #181c2e;
            --border:    rgba(255,255,255,0.06);
            --border-md: rgba(255,255,255,0.10);
            --gold:      #f5a623;
            --gold-dim:  #b87a18;
            --gold-bg:   rgba(245,166,35,0.08);
            --gold-bdr:  rgba(245,166,35,0.20);
            --indigo:    #6366f1;
            --indigo-bg: rgba(99,102,241,0.08);
            --green:     #10b981;
            --red:       #ef4444;
            --text-1:    #f1f5f9;
            --text-2:    #94a3b8;
            --text-3:    #4b5563;
            --radius-sm: 10px;
            --radius-md: 14px;
            --radius-lg: 20px;
            --radius-xl: 28px;
            --sidebar-w: 248px;
            --nav-h:     62px;
            --transition: all 0.22s cubic-bezier(0.4,0,0.2,1);
        }

        *, *::before, *::after { box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Hind Siliguri', sans-serif;
            background: var(--bg-deep);
            color: var(--text-1);
            overflow-x: hidden;
            margin: 0;
        }

        [x-cloak] { display: none !important; }

        /* ─── SCROLLBAR ─── */
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.07); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.14); }

        /* ─── TOPNAV ─── */
        .topnav {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: var(--nav-h);
            background: rgba(11,13,23,0.97);
            backdrop-filter: blur(20px);
            border-bottom: 0.5px solid var(--border);
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
            width: 38px; height: 38px;
            background: rgba(255,255,255,0.04);
            border: 0.5px solid var(--border);
            border-radius: var(--radius-sm);
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-2);
            transition: var(--transition);
            flex-shrink: 0;
        }
        .nav-hamburger:hover { background: rgba(255,255,255,0.08); color: var(--text-1); }

        @media (max-width: 1023px) { .nav-hamburger { display: flex; } }

        .nav-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .nav-logo img { height: 36px; object-fit: contain; }
        .nav-logo-fallback {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--gold), #ef4444);
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Sora', sans-serif;
            font-weight: 800; font-size: 15px; color: #000;
        }

        /* Search bar */
        .nav-search {
            flex: 1;
            max-width: 420px;
            position: relative;
            display: flex;
            align-items: center;
        }
        .nav-search input {
            width: 100%;
            background: rgba(255,255,255,0.04);
            border: 0.5px solid var(--border-md);
            border-radius: 50px;
            padding: 9px 42px 9px 18px;
            font-size: 13px;
            color: var(--text-1);
            font-family: 'Hind Siliguri', sans-serif;
            outline: none;
            transition: var(--transition);
        }
        .nav-search input::placeholder { color: var(--text-3); }
        .nav-search input:focus { border-color: rgba(99,102,241,0.4); background: rgba(99,102,241,0.04); }
        .nav-search-icon {
            position: absolute; right: 14px;
            color: var(--text-3); font-size: 14px;
            pointer-events: none;
        }
        @media (max-width: 640px) { .nav-search { display: none; } }

        /* Nav right actions */
        .nav-right { display: flex; align-items: center; gap: 6px; }

        .nav-cart {
            position: relative;
            width: 38px; height: 38px;
            display: flex; align-items: center; justify-content: center;
            background: rgba(255,255,255,0.04);
            border: 0.5px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text-2);
            text-decoration: none;
            transition: var(--transition);
        }
        .nav-cart:hover { background: var(--gold-bg); border-color: var(--gold-bdr); color: var(--gold); }
        .nav-cart-badge {
            position: absolute; top: -5px; right: -5px;
            width: 17px; height: 17px;
            background: var(--red);
            color: #fff;
            font-size: 8px; font-weight: 700;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid var(--bg-deep);
        }

        .nav-divider { width: 0.5px; height: 22px; background: var(--border-md); margin: 0 4px; }

        /* Balance chip */
        .nav-balance {
            display: flex; align-items: center; gap-7px;
            background: var(--gold-bg);
            border: 0.5px solid var(--gold-bdr);
            border-radius: 50px;
            padding: 6px 14px;
            gap: 7px;
        }
        .nav-balance-icon {
            width: 18px; height: 18px;
            background: var(--gold);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 9px; font-weight: 800; color: #000;
            flex-shrink: 0;
        }
        .nav-balance span { font-size: 12px; font-weight: 700; color: var(--gold); white-space: nowrap; }
        @media (max-width: 480px) { .nav-balance { display: none; } }

        /* User avatar dropdown */
        .nav-avatar {
            width: 36px; height: 36px;
            border-radius: var(--radius-sm);
            object-fit: cover;
            border: 1.5px solid var(--border-md);
            cursor: pointer;
            transition: var(--transition);
        }
        .nav-avatar:hover { border-color: var(--gold); }

        .nav-user-menu {
            position: absolute;
            top: calc(var(--nav-h) + 6px);
            right: 16px;
            width: 240px;
            background: var(--bg-card);
            border: 0.5px solid var(--border-md);
            border-radius: var(--radius-lg);
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
            z-index: 200;
            overflow: hidden;
        }
        .user-menu-header {
            padding: 16px 20px;
            border-bottom: 0.5px solid var(--border);
        }
        .user-menu-header p:first-child { font-size: 10px; font-weight: 700; color: var(--gold); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 4px; }
        .user-menu-header p:last-child { font-size: 18px; font-weight: 700; color: var(--text-1); }
        .user-menu-item {
            display: flex; align-items: center; gap: 10px;
            padding: 11px 16px;
            font-size: 13px; font-weight: 600;
            color: var(--text-2);
            text-decoration: none;
            transition: var(--transition);
            cursor: pointer;
        }
        .user-menu-item:hover { background: rgba(255,255,255,0.04); color: var(--text-1); }
        .user-menu-item.danger { color: #f87171; }
        .user-menu-item.danger:hover { background: rgba(239,68,68,0.06); color: #ef4444; }
        .user-menu-divider { height: 0.5px; background: var(--border); margin: 4px 0; }

        /* Auth buttons */
        .btn-login {
            font-size: 12px; font-weight: 600;
            color: var(--text-2);
            background: none; border: none;
            cursor: pointer;
            padding: 6px 10px;
            transition: var(--transition);
            text-decoration: none;
        }
        .btn-login:hover { color: var(--text-1); }

        .btn-register {
            font-size: 11px; font-weight: 800;
            color: #000;
            background: var(--gold);
            border: none;
            border-radius: 50px;
            padding: 8px 18px;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            text-decoration: none;
            transition: var(--transition);
            white-space: nowrap;
        }
        .btn-register:hover { background: #f0b429; transform: translateY(-1px); }

        /* ─── LAYOUT WRAPPER ─── */
        .layout-wrapper {
            display: flex;
            padding-top: var(--nav-h);
            min-height: 100vh;
        }

        /* ─── SIDEBAR OVERLAY (mobile) ─── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            z-index: 90;
            backdrop-filter: blur(4px);
        }
        @media (max-width: 1023px) {
            .sidebar-overlay.active { display: block; }
        }

        /* ─── SIDEBAR ─── */
        .sidebar {
            position: fixed;
            top: var(--nav-h);
            left: 0;
            bottom: 0;
            width: var(--sidebar-w);
            background: var(--bg-base);
            border-right: 0.5px solid var(--border);
            display: flex;
            flex-direction: column;
            z-index: 95;
            transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
        }

        @media (max-width: 1023px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
        }

        .sidebar-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 16px 12px;
        }

        /* User profile block */
        .sidebar-profile {
            background: var(--bg-card);
            border: 0.5px solid var(--border-md);
            border-radius: var(--radius-md);
            padding: 14px;
            margin-bottom: 20px;
        }
        .sidebar-profile-row { display: flex; align-items: center; gap: 10px; }
        .sidebar-avatar {
            width: 38px; height: 38px;
            border-radius: 10px;
            background: var(--indigo-bg);
            border: 1px solid rgba(99,102,241,0.3);
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; font-weight: 700;
            color: var(--indigo);
            flex-shrink: 0;
            overflow: hidden;
        }
        .sidebar-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .sidebar-profile-name { font-size: 13px; font-weight: 700; color: var(--text-1); }
        .sidebar-profile-role {
            font-size: 10px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.08em;
            color: var(--gold);
            margin-top: 2px;
        }
        .sidebar-profile-bar { margin-top: 10px; height: 3px; background: rgba(255,255,255,0.05); border-radius: 4px; overflow: hidden; }
        .sidebar-profile-bar-fill { height: 100%; background: var(--indigo); border-radius: 4px; width: 65%; }

        /* Nav sections */
        .sidebar-section-label {
            font-size: 9px; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.18em;
            color: var(--text-3);
            padding: 0 10px;
            margin: 18px 0 6px;
        }

        .sidebar-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px;
            border-radius: var(--radius-sm);
            font-size: 13px; font-weight: 600;
            color: var(--text-2);
            text-decoration: none;
            transition: var(--transition);
            margin-bottom: 1px;
        }
        .sidebar-link:hover { background: rgba(255,255,255,0.04); color: var(--text-1); }
        .sidebar-link.active {
            background: rgba(99,102,241,0.10);
            color: #818cf8;
            border-left: 2px solid var(--indigo);
        }
        .sidebar-link i { font-size: 16px; width: 20px; text-align: center; flex-shrink: 0; }
        .sidebar-link-badge {
            margin-left: auto;
            font-size: 8px; font-weight: 800;
            padding: 2px 7px;
            border-radius: 20px;
            background: var(--gold-bg);
            color: var(--gold);
            border: 0.5px solid var(--gold-bdr);
            text-transform: uppercase;
        }

        /* Shop grid links */
        .sidebar-shop-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 6px; margin-top: 4px; }
        .sidebar-shop-tile {
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            gap: 5px;
            padding: 12px 6px;
            background: var(--bg-card);
            border: 0.5px solid var(--border);
            border-radius: var(--radius-sm);
            text-decoration: none;
            color: var(--text-2);
            font-size: 10px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.05em;
            transition: var(--transition);
        }
        .sidebar-shop-tile:hover { background: var(--bg-hover); color: var(--text-1); border-color: var(--border-md); }
        .sidebar-shop-tile i { font-size: 18px; }
        .sidebar-shop-tile.gold { color: var(--gold); border-color: var(--gold-bdr); background: var(--gold-bg); }
        .sidebar-shop-tile.purple { color: #a78bfa; border-color: rgba(167,139,250,0.2); background: rgba(167,139,250,0.05); }

        /* Phase 2 teaser */
        .phase2-teaser {
            margin: 16px 4px 0;
            background: linear-gradient(135deg, rgba(245,166,35,0.05), rgba(99,102,241,0.05));
            border: 0.5px solid var(--gold-bdr);
            border-radius: var(--radius-md);
            padding: 14px;
        }
        .phase2-teaser-label {
            font-size: 9px; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.1em;
            color: var(--gold-dim);
            margin-bottom: 5px;
        }
        .phase2-teaser-desc { font-size: 11px; color: #78716c; line-height: 1.5; }
        .phase2-pill {
            display: inline-block;
            margin-top: 8px;
            background: rgba(245,166,35,0.12);
            border: 0.5px solid var(--gold-bdr);
            color: var(--gold-dim);
            font-size: 8px; font-weight: 800;
            padding: 3px 10px; border-radius: 20px;
            text-transform: uppercase; letter-spacing: 0.08em;
        }

        /* Admin section */
        .sidebar-admin-block {
            margin-top: 12px;
            background: rgba(239,68,68,0.04);
            border: 0.5px solid rgba(239,68,68,0.12);
            border-radius: var(--radius-md);
            padding: 8px;
        }
        .sidebar-admin-label {
            display: flex; align-items: center; gap-6px;
            font-size: 9px; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.15em;
            color: rgba(239,68,68,0.6);
            padding: 6px 8px 8px;
            gap: 6px;
        }
        .sidebar-admin-label .pulse { width: 6px; height: 6px; background: #ef4444; border-radius: 50%; animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:0.4;} }
        .sidebar-admin-link {
            display: flex; align-items: center; gap: 8px;
            padding: 8px 10px;
            border-radius: 8px;
            font-size: 12px; font-weight: 600;
            color: var(--text-2);
            text-decoration: none;
            transition: var(--transition);
            margin-bottom: 1px;
        }
        .sidebar-admin-link:hover { background: rgba(239,68,68,0.07); color: #f87171; }
        .sidebar-admin-link i { font-size: 14px; width: 18px; text-align: center; }

        /* Sidebar footer */
        .sidebar-footer {
            padding: 14px;
            border-top: 0.5px solid var(--border);
            text-align: center;
        }
        .sidebar-footer p { font-size: 10px; color: var(--text-3); font-weight: 600; line-height: 1.7; }

        /* ─── MAIN CONTENT ─── */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-w);
            display: flex;
            flex-direction: column;
            min-height: calc(100vh - var(--nav-h));
        }

        @media (max-width: 1023px) { .main-content { margin-left: 0; } }

        .main-inner {
            display: grid;
            grid-template-columns: 1fr 200px;
            gap: 24px;
            padding: 24px 20px;
            flex: 1;
        }

        @media (max-width: 1280px) {
            .main-inner { grid-template-columns: 1fr; }
            .sidebar-right-col { display: none; }
        }
        @media (max-width: 1023px) {
            .main-inner { padding: 18px 14px; }
        }

        /* ─── RIGHT SIDEBAR (ads) ─── */
        .sidebar-right-col { display: flex; flex-direction: column; gap: 14px; }
        .ad-slot {
            background: var(--bg-card);
            border: 0.5px solid var(--border);
            border-radius: var(--radius-md);
            overflow: hidden;
        }
        .ad-slot-header {
            padding: 10px 14px 6px;
            font-size: 9px; font-weight: 800;
            color: var(--text-3);
            text-transform: uppercase; letter-spacing: 0.12em;
        }
        .ad-slot-img-wrap {
            position: relative;
            aspect-ratio: 9/5;
            background: linear-gradient(135deg, #1e1b4b, #0f172a);
            cursor: pointer;
            overflow: hidden;
        }
        .ad-slot-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
        .ad-slot-img-wrap:hover img { transform: scale(1.06); }
        .ad-slot-overlay {
            position: absolute; inset: 0;
            background: rgba(0,0,0,0.4);
            opacity: 0;
            display: flex; align-items: center; justify-content: center;
            transition: var(--transition);
        }
        .ad-slot-img-wrap:hover .ad-slot-overlay { opacity: 1; }
        .ad-slot-overlay span {
            background: var(--gold);
            color: #000;
            font-size: 9px; font-weight: 800;
            padding: 5px 12px; border-radius: 20px;
            text-transform: uppercase;
        }

        .yt-embed-wrap {
            background: var(--bg-card);
            border: 0.5px solid var(--border);
            border-radius: var(--radius-md);
            overflow: hidden;
        }
        .yt-embed-header {
            padding: 10px 14px 6px;
            font-size: 9px; font-weight: 800;
            color: var(--text-3);
            text-transform: uppercase; letter-spacing: 0.12em;
        }
        .yt-embed-wrap iframe { display: block; width: 100%; aspect-ratio: 16/9; }

        /* ─── FOOTER ─── */
        .footer {
            background: var(--bg-base);
            border-top: 0.5px solid var(--border);
            padding: 48px 20px 24px;
            margin-left: var(--sidebar-w);
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

        .footer-brand img { height: 34px; object-fit: contain; margin-bottom: 14px; display: block; }
        .footer-desc { font-size: 13px; color: var(--text-2); line-height: 1.7; max-width: 240px; }
        .footer-socials { display: flex; gap: 8px; margin-top: 18px; }
        .footer-social-btn {
            width: 36px; height: 36px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px;
            border: 0.5px solid var(--border-md);
            text-decoration: none;
            transition: var(--transition);
        }
        .footer-social-btn.fb { color: #60a5fa; background: rgba(96,165,250,0.06); }
        .footer-social-btn.fb:hover { background: rgba(96,165,250,0.14); border-color: rgba(96,165,250,0.3); }
        .footer-social-btn.wa { color: #34d399; background: rgba(52,211,153,0.06); }
        .footer-social-btn.wa:hover { background: rgba(52,211,153,0.14); border-color: rgba(52,211,153,0.3); }

        .footer-col-title {
            font-family: 'Sora', sans-serif;
            font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.14em;
            color: var(--text-1);
            margin-bottom: 18px;
        }
        .footer-link {
            display: flex; align-items: center; gap: 7px;
            font-size: 13px; font-weight: 600;
            color: var(--text-2);
            text-decoration: none;
            margin-bottom: 12px;
            transition: var(--transition);
        }
        .footer-link::before { content: ''; width: 4px; height: 4px; background: var(--gold); border-radius: 50%; opacity: 0; transition: var(--transition); }
        .footer-link:hover { color: var(--gold); padding-left: 4px; }
        .footer-link:hover::before { opacity: 1; }

        .footer-contact-row { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 16px; }
        .footer-contact-icon {
            width: 34px; height: 34px; flex-shrink: 0;
            border-radius: 9px;
            background: rgba(255,255,255,0.04);
            border: 0.5px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            font-size: 12px;
        }
        .footer-contact-icon.mail { color: var(--indigo); }
        .footer-contact-icon.phone { color: var(--gold); }
        .footer-contact-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--text-3); margin-bottom: 3px; }
        .footer-contact-val { font-size: 12px; font-weight: 600; color: var(--text-1); }

        .footer-bottom {
            max-width: 1100px; margin: 0 auto;
            padding-top: 24px;
            border-top: 0.5px solid var(--border);
            display: flex; align-items: center; justify-content: space-between; gap: 16px;
            flex-wrap: wrap;
        }
        .footer-copyright { font-size: 12px; color: var(--text-3); }
        .footer-copyright strong { color: var(--text-1); font-weight: 700; }
        .footer-dev {
            display: flex; align-items: center; gap-8px;
            background: rgba(255,255,255,0.03);
            border: 0.5px solid var(--border);
            border-radius: 50px;
            padding: 6px 16px;
            font-size: 10px; font-weight: 700;
            gap: 8px;
        }
        .footer-dev span { color: var(--text-3); text-transform: uppercase; letter-spacing: 0.1em; }
        .footer-dev a { color: var(--text-1); text-decoration: none; font-style: italic; }
        .footer-dev a:hover { color: var(--gold); }

        /* ─── MOBILE BOTTOM NAV ─── */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0; left: 0; right: 0;
            height: 68px;
            background: rgba(17,20,34,0.97);
            backdrop-filter: blur(20px);
            border-top: 0.5px solid var(--border-md);
            z-index: 80;
            align-items: center;
            justify-content: space-around;
            padding: 0 8px;
        }
        @media (max-width: 1023px) { .mobile-bottom-nav { display: flex; } }

        .mob-nav-btn {
            display: flex; flex-direction: column;
            align-items: center; gap: 3px;
            background: none; border: none;
            cursor: pointer;
            color: var(--text-3);
            padding: 6px 10px;
            text-decoration: none;
            transition: var(--transition);
        }
        .mob-nav-btn.active, .mob-nav-btn:hover { color: var(--gold); }
        .mob-nav-btn i { font-size: 20px; }
        .mob-nav-btn span { font-size: 8px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; }

        .mob-nav-center { position: relative; margin-top: -20px; }
        .mob-nav-center-btn {
            width: 52px; height: 52px;
            background: var(--gold);
            border-radius: 50%;
            border: 4px solid var(--bg-deep);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(245,166,35,0.35);
            transition: var(--transition);
        }
        .mob-nav-center-btn:hover { background: #f0b429; transform: scale(1.06); }
        .mob-nav-center-btn i { font-size: 22px; color: #000; }

        /* ─── ALERTS ─── */
        .swal-glass { background: var(--bg-card) !important; border: 0.5px solid var(--border-md) !important; }

        /* ─── MOBILE BOTTOM PADDING ─── */
        @media (max-width: 1023px) {
            .main-content { padding-bottom: 80px; }
            .footer { padding-bottom: 90px; }
        }

        /* ─── IMAGE FULLSCREEN MODAL ─── */
        .img-modal {
            position: fixed; inset: 0;
            z-index: 999;
            background: rgba(0,0,0,0.93);
            backdrop-filter: blur(8px);
            display: flex; align-items: center; justify-content: center;
            padding: 20px;
        }
        .img-modal-close {
            position: absolute; top: 20px; right: 20px;
            width: 40px; height: 40px;
            background: rgba(255,255,255,0.1);
            border: 0.5px solid rgba(255,255,255,0.15);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            color: rgba(255,255,255,0.7);
            font-size: 18px;
            transition: var(--transition);
        }
        .img-modal-close:hover { background: rgba(255,255,255,0.18); color: #fff; }
        .img-modal img { max-width: 100%; max-height: 88vh; border-radius: var(--radius-md); object-fit: contain; }
    </style>
</head>

<body x-data="{ sidebarOpen: false, userMenuOpen: false, imgModal: false, imgFull: '' }">

    {{-- ═══════════════════════════════════════════════════
         TOP NAVIGATION
    ═══════════════════════════════════════════════════ --}}
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
                    <div class="nav-logo-fallback">T</div>
                @endif
            </a>
        </div>

        {{-- Center: search --}}
        <div class="nav-search">
            <input type="text" placeholder="পণ্য খুঁজুন..." onkeydown="if(event.key==='Enter') window.location.href='/shop?q='+this.value">
            <i class="fas fa-search nav-search-icon"></i>
        </div>

        {{-- Right: actions --}}
        <div class="nav-right">

            {{-- Cart (always visible) --}}
            <a href="{{ route('cart.index') }}" class="nav-cart" title="Cart">
                <i class="fas fa-shopping-cart fa-fw" style="font-size:16px;"></i>
                @if ($cartCount > 0)
                    <span class="nav-cart-badge">{{ $cartCount > 9 ? '9+' : $cartCount }}</span>
                @endif
            </a>

            @guest
                <div class="nav-divider"></div>
                <a href="{{ route('login') }}" class="btn-login">Login</a>
                <a href="{{ route('register') }}" class="btn-register">Join Now</a>
            @endguest

            @auth
                @php
                    $userId = Auth::id();
                    $totalEarnings  = \DB::table('user_earnings')->where('user_id', $userId)->sum('amount');
                    $totalWithdrawn = \DB::table('withdrawals')->where('user_id', $userId)->whereIn('status', ['pending','approved'])->sum('amount');
                    $currentBalance = $totalEarnings - $totalWithdrawn;
                @endphp

                {{-- Balance chip --}}
                <div class="nav-balance">
                    <div class="nav-balance-icon">৳</div>
                    <span>{{ number_format($currentBalance, 2) }}</span>
                </div>

                <div class="nav-divider"></div>

                {{-- User avatar dropdown --}}
                <div style="position:relative;">
                    <img
                        src="{{ Auth::user()->profile_images ?: 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=6366f1&color=fff&bold=true' }}"
                        class="nav-avatar"
                        @click="userMenuOpen = !userMenuOpen"
                        @click.away="userMenuOpen = false"
                        alt="{{ Auth::user()->name }}">

                    <div class="nav-user-menu" x-show="userMenuOpen" x-cloak x-transition>
                        <div class="user-menu-header">
                            <p>Available Balance</p>
                            <p>৳ {{ number_format($currentBalance, 2) }}</p>
                        </div>
                        <a href="/profile" class="user-menu-item"><i class="fas fa-user-circle fa-fw"></i> Profile</a>
                        <a href="/upgrade" class="user-menu-item"><i class="fas fa-gem fa-fw"></i> Subscription</a>
                        <a href="{{ route('cart.index') }}" class="user-menu-item"><i class="fas fa-shopping-cart fa-fw"></i> My Cart</a>
                        <a href="/my-orders" class="user-menu-item"><i class="fas fa-box fa-fw"></i> My Orders</a>
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

    {{-- ═══════════════════════════════════════════════════
         LAYOUT: sidebar + content
    ═══════════════════════════════════════════════════ --}}
    <div class="layout-wrapper">

        {{-- Mobile overlay --}}
        <div
            class="sidebar-overlay"
            :class="{ active: sidebarOpen }"
            @click="sidebarOpen = false">
        </div>

        {{-- ───────────── SIDEBAR ───────────── --}}
        <aside class="sidebar" :class="{ open: sidebarOpen }">

            {{-- Mobile search bar (desktop এ hidden) --}}
            <div style="padding:12px 12px 0; display:none;" class="mobile-sidebar-search">
                <div style="position:relative;">
                    <input
                        id="mobile-search-input"
                        type="text"
                        placeholder="পণ্য খুঁজুন..."
                        style="width:100%; background:rgba(255,255,255,0.05); border:0.5px solid var(--border-md); border-radius:50px; padding:10px 38px 10px 16px; font-size:13px; color:var(--text-1); font-family:'Hind Siliguri',sans-serif; outline:none;"
                    >
                    <i class="fas fa-search" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);color:var(--text-3);font-size:13px;pointer-events:none;"></i>
                </div>
            </div>
            <style>
                @media (max-width: 1023px) {
                    .mobile-sidebar-search { display: block !important; }
                }
            </style>

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

                <a href="/" class="sidebar-link {{ request()->is('/') ? 'active' : '' }}">
                    <i class="fas fa-home fa-fw"></i> Home
                </a>

                <a href="/shop" class="sidebar-link {{ request()->is('shop*') ? 'active' : '' }}">
                    <i class="fas fa-store fa-fw"></i> Shop
                </a>

                @auth
                    @if (Auth::user()->role !== 'admin')
                        <a href="/pakages" class="sidebar-link {{ request()->is('pakages*') ? 'active' : '' }}">
                            <i class="fas fa-star fa-fw"></i> Packages
                        </a>

                        {{-- ─── PHASE 2: Video Earn ─────────────────
                             When phase2_enabled = true in site_settings,
                             uncomment this block and remove the teaser below.
                        ─────────────────────────────────────────── --}}
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

                        {{-- Shop & Orders grid --}}
                        <div class="sidebar-section-label" style="margin-top:14px;">Shop & Orders</div>
                        <div class="sidebar-shop-grid">
                            <a href="{{ route('cart.index') }}" class="sidebar-shop-tile gold">
                                <i class="fas fa-shopping-cart"></i> Cart
                            </a>
                            <a href="/my-orders" class="sidebar-shop-tile purple">
                                <i class="fas fa-box"></i> Orders
                            </a>
                        </div>
                    @endif
                @endauth

                @guest
                    <div style="margin-top:16px; display:flex; flex-direction:column; gap:8px;">
                        <a href="{{ route('login') }}" class="sidebar-link" style="justify-content:center; background:rgba(99,102,241,0.08); border:0.5px solid rgba(99,102,241,0.2); color:#818cf8;">
                            <i class="fas fa-sign-in-alt fa-fw"></i> Sign In
                        </a>
                        <a href="{{ route('register') }}" class="sidebar-link" style="justify-content:center; background:var(--gold-bg); border:0.5px solid var(--gold-bdr); color:var(--gold);">
                            <i class="fas fa-user-plus fa-fw"></i> Register
                        </a>
                    </div>
                @endguest

                {{-- Phase 2 teaser (remove when phase2 goes live) --}}
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
                            <a href="/admin/dashboard" class="sidebar-admin-link"><i class="fas fa-tachometer-alt fa-fw"></i> Dashboard</a>
                            <a href="/admin/users" class="sidebar-admin-link"><i class="fas fa-users fa-fw"></i> Users List</a>
                            <a href="/admin/withdraw" class="sidebar-admin-link"><i class="fas fa-money-bill-wave fa-fw"></i> Payout Requests</a>
                            <a href="/orders" class="sidebar-admin-link" style="color:#fbbf24;"><i class="fas fa-inbox fa-fw"></i> Customer Orders</a>
                            <div style="height:0.5px; background:rgba(255,255,255,0.04); margin:6px 8px;"></div>
                            <a href="/admin/products" class="sidebar-admin-link"><i class="fas fa-box-open fa-fw"></i> Inventory</a>
                            <a href="/admin/categories" class="sidebar-admin-link"><i class="fas fa-folder fa-fw"></i> Categories</a>
                            <div style="height:0.5px; background:rgba(255,255,255,0.04); margin:6px 8px;"></div>
                            <a href="/admin/settings" class="sidebar-admin-link"><i class="fas fa-cog fa-fw"></i> Settings</a>
                        </div>
                    @endif
                @endauth

                {{-- Support links --}}
                <div class="sidebar-section-label" style="margin-top:18px;">Help</div>
                <a href="/about" class="sidebar-link"><i class="fas fa-info-circle fa-fw"></i> About Us</a>
                <a href="/privacy-policy" class="sidebar-link"><i class="fas fa-shield-alt fa-fw"></i> Privacy Policy</a>

            </div>

            {{-- Sidebar footer --}}
            <div class="sidebar-footer">
                <p>&copy; {{ date('Y') }} {{ $settings->site_name ?? 'TaKa ID 24' }}<br>
                <span style="color:var(--text-3); font-weight:500;">Version 4.0.2</span></p>
            </div>
        </aside>

        {{-- ───────────── MAIN CONTENT ───────────── --}}
        <div class="main-content">

            {{-- Flash messages --}}
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            icon: 'success',
                            title: 'সফল!',
                            text: "{{ session('success') }}",
                            confirmButtonColor: '#6366f1',
                            background: '#111422',
                            color: '#f1f5f9',
                        });
                    });
                </script>
            @endif

            @if (session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'ভুল হয়েছে!',
                            text: "{{ session('error') }}",
                            confirmButtonColor: '#ef4444',
                            background: '#111422',
                            color: '#f1f5f9',
                        });
                    });
                </script>
            @endif

            @if ($errors->any())
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            html: `{!! implode('<br>', $errors->all()) !!}`,
                            confirmButtonColor: '#ef4444',
                            background: '#111422',
                            color: '#f1f5f9',
                        });
                    });
                </script>
            @endif

            {{-- Page content + right ad column --}}
            <div class="main-inner">

                {{-- Main yield --}}
                <div>
                    @yield('content')
                </div>

                {{-- Right ad sidebar --}}
                <aside class="sidebar-right-col">
                    @php
                        $siteSetting = $siteSetting ?? $settings ?? \App\Models\SiteSetting::first();
                        $sponsors = [];
                        if ($siteSetting && $siteSetting->sponsor_banner) {
                            $decoded = json_decode($siteSetting->sponsor_banner, true);
                            $sponsors = is_array($decoded) ? $decoded : [];
                        }
                        $youtubeUrl = $siteSetting->youtube_link ?? '';
                        $embedUrl = '';
                        if ($youtubeUrl) {
                            if (str_contains($youtubeUrl, 'watch?v=')) {
                                $vid = explode('&', explode('v=', $youtubeUrl)[1])[0];
                                $embedUrl = 'https://www.youtube.com/embed/' . $vid;
                            } elseif (str_contains($youtubeUrl, 'youtu.be/')) {
                                $embedUrl = 'https://www.youtube.com/embed/' . explode('youtu.be/', $youtubeUrl)[1];
                            } else {
                                $embedUrl = $youtubeUrl;
                            }
                        }
                    @endphp

                    @foreach ($sponsors as $banner)
                        @php
                            $cleanPath  = str_starts_with($banner, 'settings/') ? $banner : 'settings/' . $banner;
                            $imagePath  = asset('storage/' . $cleanPath);
                        @endphp
                        <div class="ad-slot">
                            <div class="ad-slot-header">Sponsored</div>
                            <div class="ad-slot-img-wrap" @click="imgModal=true; imgFull='{{ $imagePath }}'">
                                <img src="{{ $imagePath }}"
                                    alt="Sponsor"
                                    onerror="this.src='https://via.placeholder.com/400x220?text=Ad'">
                                <div class="ad-slot-overlay"><span>View Full 🔍</span></div>
                            </div>
                        </div>
                    @endforeach

                    @if ($embedUrl)
                        <div class="yt-embed-wrap">
                            <div class="yt-embed-header">Video</div>
                            <iframe
                                src="{{ $embedUrl }}?rel=0&modestbranding=1"
                                title="Video"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </div>
                    @endif
                </aside>
            </div>

            {{-- ───────────── FOOTER ───────────── --}}
            <footer class="footer">
                <div class="footer-grid">

                    {{-- Brand --}}
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

                    {{-- Quick links --}}
                    <div>
                        <div class="footer-col-title">Quick Links</div>
                        <a href="/"               class="footer-link">Home</a>
                        <a href="/shop"           class="footer-link">Shop</a>
                        <a href="/pakages"        class="footer-link">Packages</a>
                        <a href="/about"          class="footer-link">About Us</a>
                    </div>

                    {{-- Legal --}}
                    <div>
                        <div class="footer-col-title">Legal & Help</div>
                        <a href="/privacy-policy" class="footer-link">Privacy Policy</a>
                        <a href="/privacy-policy" class="footer-link">Terms of Service</a>
                        <a href="/about"          class="footer-link">FAQ</a>
                    </div>

                    {{-- Contact --}}
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
                        &copy; {{ date('Y') }} <strong>{{ $settings->site_name ?? 'TaKa ID 24' }}</strong>. All rights reserved.
                    </p>
                    <div class="footer-dev">
                        <span>Developed by</span>
                        <a href="#"> IT Solutions</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════
         MOBILE BOTTOM NAV
    ═══════════════════════════════════════════════════ --}}
    <nav class="mobile-bottom-nav">
        <a href="/" class="mob-nav-btn {{ request()->is('/') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>

        <a href="/shop" class="mob-nav-btn {{ request()->is('shop*') ? 'active' : '' }}">
            <i class="fas fa-store"></i>
            <span>Shop</span>
        </a>

        {{-- Center hamburger --}}
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
                @if ($cartCount > 0)
                    <span style="position:absolute;top:2px;right:6px;width:14px;height:14px;background:var(--red);border-radius:50%;font-size:7px;font-weight:800;display:flex;align-items:center;justify-content:center;border:2px solid var(--bg-deep);">{{ $cartCount }}</span>
                @endif
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

    {{-- ═══════════════════════════════════════════════════
         IMAGE FULLSCREEN MODAL
    ═══════════════════════════════════════════════════ --}}
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

    {{-- ═══════════════════════════════════════════════════
         VIDEO PLAYER SCRIPTS (Facebook, YouTube, Local)
         সবসময় লোড — home page এ সবসময় video থাকে
    ═══════════════════════════════════════════════════ --}}
    <script>
        let ytPlayers = {}, lastTime = {};
        const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
        const userRole   = "{{ Auth::check() ? Auth::user()->role : 'guest' }}";

        // ─── Facebook ───
        window.fbAsyncInit = function() {
            FB.init({ xfbml: true, version: 'v18.0' });
        };

        function playFBVideo(id) {
            const starter     = document.getElementById('fb-starter-' + id);
            const timerStatus = document.getElementById('fb-timer-status-' + id);
            const overlay     = document.getElementById('fb-overlay-' + id);

            starter.style.display = 'none';
            timerStatus.classList.remove('hidden');
            overlay.classList.remove('hidden');

            // duration — view এ পাঠানো হলে ব্যবহার করবে, না হলে 30s default
            let timeLeft = parseInt(document.getElementById('fb-starter-' + id)?.dataset?.duration ?? 30);
            timerStatus.innerHTML = `⏳ <span id="timer-count-${id}">${timeLeft}</span>s বাকি`;

            FB.XFBML.parse(document.getElementById('fb-container-' + id), function() {
                FB.Event.subscribe('xfbml.ready', function(msg) {
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

        // ─── YouTube ───
        function onYouTubeIframeAPIReady() {
            document.querySelectorAll('.yt-wrapper').forEach(w => {
                let tid = w.dataset.taskId;
                ytPlayers[tid] = new YT.Player('player-' + tid, {
                    videoId: w.dataset.videoId,
                    playerVars: { controls: 0, disablekb: 1, rel: 0, modestbranding: 1 },
                    events: {
                        onStateChange: (e) => {
                            let ui = document.getElementById('yt-ui-' + tid);
                            if (e.data === 1) { if(ui) ui.style.opacity = '0'; startSecurityYT(tid); }
                            else { if(ui) ui.style.opacity = '1'; }
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

        // ─── Local video ───
        function toggleLocal(id) {
            let v  = document.getElementById('local-' + id);
            let ui = document.getElementById('local-ui-' + id);
            if (!v) return;
            if (v.paused) {
                v.play();
                if(ui) ui.style.opacity = '0';
                v.onended = () => finishTask(id);
            } else {
                v.pause();
                if(ui) ui.style.opacity = '1';
            }
        }

        // ─── Task complete API ───
        function finishTask(taskId) {
            if (!isLoggedIn) {
                showFinalMessage(taskId, false, "টাকা আয় করতে আগে লগইন করুন।");
                return;
            }
            if (userRole === 'n_user') {
                showFinalMessage(taskId, false, "একটি প্যাকেজ দিয়ে একাউন্ট একটিভ করুন।");
                return;
            }
            fetch("{{ route('complete.task') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
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

            if(statusIcon)  statusIcon.innerText  = success ? "✅" : "❌";
            if(statusTitle) statusTitle.innerText = success ? "সফল!" : "ব্যর্থ!";
            if(statusText)  statusText.innerText  = msg;

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

        // ─── Mobile search toggle ───
        document.addEventListener('DOMContentLoaded', function() {
            const mobileSearchInput = document.getElementById('mobile-search-input');
            if (mobileSearchInput) {
                mobileSearchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        window.location.href = '/shop?q=' + encodeURIComponent(this.value);
                    }
                });
            }
        });
    </script>

    @stack('scripts')

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        cyber: {
                            50:  '#f0fffe',
                            100: '#ccfffe',
                            200: '#99fffd',
                            300: '#00e5ff',
                            400: '#00bcd4',
                            500: '#0097a7',
                            600: '#00838f',
                        },
                        neon: {
                            green:  '#00e676',
                            cyan:   '#00e5ff',
                            violet: '#7c3aed',
                            pink:   '#ec4899',
                        }
                    },
                }
            }
        }
    </script>
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8faff; }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f0f4ff; }
        ::-webkit-scrollbar-thumb { background: linear-gradient(180deg,#6366f1,#06b6d4); border-radius: 10px; }

        /* ── Futuristic grid bg ── */
        .grid-bg {
            background-color: #f8faff;
            background-image:
                linear-gradient(rgba(99,102,241,.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99,102,241,.06) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        /* ── Glassmorphism ── */
        .glass {
            background: rgba(255,255,255,0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.6);
        }
        .glass-card {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(99,102,241,0.12);
            box-shadow: 0 4px 24px rgba(99,102,241,0.08), 0 1px 4px rgba(0,0,0,0.04);
        }

        /* ── Neon glow buttons ── */
        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #06b6d4 100%);
            color: #fff;
            font-weight: 600;
            border-radius: 12px;
            position: relative;
            overflow: hidden;
            transition: all .3s cubic-bezier(.4,0,.2,1);
            box-shadow: 0 4px 20px rgba(99,102,241,0.35);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(99,102,241,0.5);
        }
        .btn-primary::after {
            content:''; position:absolute; inset:0;
            background: linear-gradient(135deg,rgba(255,255,255,.15),transparent);
            transition: opacity .3s;
        }
        .btn-primary:hover::after { opacity: 0; }

        /* ── Shimmer sweep ── */
        .btn-primary::before {
            content:''; position:absolute;
            top:0; left:-100%; width:60%; height:100%;
            background: linear-gradient(90deg,transparent,rgba(255,255,255,.25),transparent);
            transition: left .5s;
        }
        .btn-primary:hover::before { left:140%; }

        /* ── Input futuristic ── */
        .input-cyber {
            background: rgba(255,255,255,0.9);
            border: 1.5px solid rgba(99,102,241,0.2);
            border-radius: 10px;
            transition: all .25s;
            color: #1e1b4b;
        }
        .input-cyber:focus {
            outline: none;
            border-color: #6366f1;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.12), 0 0 12px rgba(6,182,212,0.15);
        }

        /* ── Nav link ── */
        .nav-link {
            position: relative;
            transition: color .25s;
            font-weight: 500;
        }
        .nav-link::after {
            content:''; position:absolute; bottom:-3px; left:50%;
            width:0; height:2px;
            background: linear-gradient(90deg,#6366f1,#06b6d4);
            border-radius: 2px;
            transition: width .3s, left .3s;
        }
        .nav-link:hover::after { width:100%; left:0; }
        .nav-link:hover { color: #6366f1; }

        /* ── Card 3-D lift ── */
        .card-lift {
            transition: transform .35s cubic-bezier(.34,1.56,.64,1), box-shadow .35s;
            border-radius: 16px;
        }
        .card-lift:hover {
            transform: translateY(-6px) scale(1.015);
            box-shadow: 0 20px 50px rgba(99,102,241,0.18), 0 6px 16px rgba(6,182,212,0.12);
        }

        /* ── Glow badge ── */
        .badge-glow {
            animation: pulse-badge 2s ease-in-out infinite;
        }
        @keyframes pulse-badge {
            0%,100% { box-shadow: 0 0 0 0 rgba(239,68,68,.5); }
            50%      { box-shadow: 0 0 0 6px rgba(239,68,68,0); }
        }

        /* ── Floating orbs (hero) ── */
        .orb {
            position: absolute; border-radius: 50%;
            filter: blur(60px); opacity: .25;
            animation: drift 8s ease-in-out infinite;
        }
        .orb-2 { animation-delay: -3s; animation-direction: reverse; }
        .orb-3 { animation-delay: -5s; }
        @keyframes drift {
            0%,100% { transform: translate(0,0) scale(1); }
            33%      { transform: translate(30px,-20px) scale(1.08); }
            66%      { transform: translate(-20px,15px) scale(.95); }
        }

        /* ── Scan line (decorative) ── */
        .scan-line {
            position: absolute; left:0; right:0; height:2px;
            background: linear-gradient(90deg,transparent,rgba(99,102,241,.4),rgba(6,182,212,.4),transparent);
            animation: scan 4s linear infinite;
        }
        @keyframes scan {
            0%   { top: -5%; }
            100% { top: 105%; }
        }

        /* ── Fade / slide animations ── */
        @keyframes fadeUp   { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:none} }
        @keyframes fadeIn   { from{opacity:0} to{opacity:1} }
        @keyframes slideRight{ from{opacity:0;transform:translateX(-20px)} to{opacity:1;transform:none} }
        .anim-up   { animation: fadeUp   .55s cubic-bezier(.22,1,.36,1) both; }
        .anim-in   { animation: fadeIn   .4s ease both; }
        .anim-right{ animation: slideRight .45s cubic-bezier(.22,1,.36,1) both; }
        .d1{animation-delay:.05s} .d2{animation-delay:.1s} .d3{animation-delay:.15s}
        .d4{animation-delay:.2s}  .d5{animation-delay:.25s} .d6{animation-delay:.3s}

        /* ── Dropdown ── */
        .dd-menu {
            opacity:0; transform:translateY(-8px) scale(.97);
            pointer-events:none;
            transition: opacity .2s, transform .2s;
        }
        .dd-wrap:hover .dd-menu,
        .dd-wrap:focus-within .dd-menu {
            opacity:1; transform:none; pointer-events:auto;
        }

        /* ── Cyber tag ── */
        .cyber-tag {
            background: linear-gradient(135deg,rgba(99,102,241,.1),rgba(6,182,212,.1));
            border: 1px solid rgba(99,102,241,.2);
            color: #6366f1;
            font-size:.65rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase;
            padding:.25rem .6rem; border-radius:6px;
        }

        /* ── Status pills ── */
        .pill-pending   { background:#fef9c3; color:#854d0e; border:1px solid #fde68a; }
        .pill-active    { background:#dbeafe; color:#1e40af; border:1px solid #bfdbfe; }
        .pill-delivered { background:#dcfce7; color:#166534; border:1px solid #bbf7d0; }
        .pill-cancelled { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }

        /* ── Toast ── */
        .toast-ok  { background:linear-gradient(135deg,#f0fdf4,#dcfce7); border-left:4px solid #22c55e; }
        .toast-err { background:linear-gradient(135deg,#fef2f2,#fee2e2); border-left:4px solid #ef4444; }

        /* ── Section accent line ── */
        .sec-title { position:relative; display:inline-block; }
        .sec-title::after {
            content:''; position:absolute; bottom:-6px; left:0;
            width:36px; height:3px; border-radius:2px;
            background: linear-gradient(90deg,#6366f1,#06b6d4);
        }

        /* ── Futuristic divider ── */
        .cyber-divider {
            height:1px;
            background: linear-gradient(90deg,transparent,rgba(99,102,241,.3),rgba(6,182,212,.3),transparent);
        }
    </style>
    @stack('head')
</head>
<body class="grid-bg text-gray-800 flex flex-col min-h-screen">

    {{-- Announcement bar --}}
    <div style="background:linear-gradient(90deg,#6366f1,#4f46e5,#06b6d4)" class="text-white text-xs text-center py-2 px-4 relative overflow-hidden">
        <div class="scan-line"></div>
        <span class="relative z-10 font-medium tracking-wide">
            ✦ Free shipping over Rs {{ number_format(config('app.store.free_shipping_threshold')) }} &nbsp;·&nbsp; 📞 {{ config('app.store.phone') }} &nbsp;·&nbsp; Cash on Delivery everywhere in Pakistan ✦
        </span>
    </div>

    {{-- Header --}}
    <header class="glass shadow-sm shadow-indigo-100/50 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center gap-4">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="shrink-0 flex items-center gap-2.5 group">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200 transition-all duration-300 group-hover:shadow-indigo-300 group-hover:scale-105"
                     style="background:linear-gradient(135deg,#6366f1,#06b6d4)">
                    <span class="text-white font-black text-sm">RS</span>
                </div>
                <div class="hidden sm:block">
                    <span class="font-black text-base tracking-tight" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">{{ config('app.name') }}</span>
                </div>
            </a>

            {{-- Search --}}
            <form action="{{ route('products.index') }}" method="GET" class="flex-1 hidden md:flex max-w-xl">
                <div class="flex w-full rounded-xl overflow-hidden shadow-sm shadow-indigo-100 border border-indigo-100 focus-within:border-indigo-300 focus-within:shadow-indigo-200 transition-all duration-300">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search products, categories…"
                           class="w-full px-4 py-2.5 text-sm bg-white/90 text-gray-700 placeholder-gray-400 focus:outline-none">
                    <button class="btn-primary px-5 py-2.5 text-sm flex items-center gap-1.5 rounded-none rounded-r-xl">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Search
                    </button>
                </div>
            </form>

            {{-- Nav --}}
            <nav class="flex items-center gap-1 ml-auto text-sm">
                <a href="{{ route('products.index') }}" class="nav-link hidden sm:inline px-3 py-2 rounded-lg hover:bg-indigo-50 transition-colors duration-200">Shop</a>

                @auth
                    <a href="{{ route('wishlist.index') }}" class="nav-link hidden sm:inline px-3 py-2 rounded-lg hover:bg-indigo-50 transition-colors duration-200 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        Wishlist
                    </a>
                    <a href="{{ route('orders.index') }}" class="nav-link hidden sm:inline px-3 py-2 rounded-lg hover:bg-indigo-50 transition-colors duration-200">Orders</a>

                    <div class="relative dd-wrap">
                        <button class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-indigo-50 transition-all duration-200 font-medium">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm"
                                 style="background:linear-gradient(135deg,#6366f1,#06b6d4)">
                                {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                            </div>
                            <span class="hidden sm:inline text-sm text-gray-700">{{ Str::limit(auth()->user()->name,12) }}</span>
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="dd-menu absolute right-0 top-full mt-2 glass-card rounded-2xl py-2 w-48 z-50">
                            <a href="{{ route('account.edit') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm hover:bg-indigo-50 hover:text-indigo-700 transition-colors rounded-lg mx-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                My Account
                            </a>
                            <a href="{{ route('orders.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm hover:bg-indigo-50 hover:text-indigo-700 transition-colors rounded-lg mx-1 sm:hidden">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                My Orders
                            </a>
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm hover:bg-cyan-50 hover:text-cyan-700 transition-colors rounded-lg mx-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                Admin Panel
                            </a>
                            @endif
                            <div class="cyber-divider my-1 mx-3"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="flex items-center gap-2.5 w-full px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-colors rounded-lg mx-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="nav-link px-3 py-2 rounded-lg hover:bg-indigo-50 transition-colors duration-200 text-gray-700">Login</a>
                    <a href="{{ route('register') }}" class="btn-primary px-4 py-2 text-sm ml-1">Register</a>
                @endauth

                {{-- Cart --}}
                <a href="{{ route('cart.index') }}" class="btn-primary relative inline-flex items-center gap-1.5 px-4 py-2 text-sm ml-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span class="hidden sm:inline">Cart</span>
                    @if(($cartCount ?? 0) > 0)
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] rounded-full w-5 h-5 flex items-center justify-center font-bold badge-glow">{{ $cartCount }}</span>
                    @endif
                </a>
            </nav>
        </div>

        {{-- Mobile search --}}
        <form action="{{ route('products.index') }}" method="GET" class="md:hidden px-4 pb-3 flex">
            <div class="flex w-full rounded-xl overflow-hidden border border-indigo-100 shadow-sm">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search products…"
                       class="w-full px-4 py-2.5 text-sm bg-white/90 focus:outline-none">
                <button class="btn-primary px-4 text-sm rounded-none rounded-r-xl">Go</button>
            </div>
        </form>
    </header>

    <main class="flex-1">
        <div class="max-w-7xl mx-auto px-4 pt-4">
            @if(session('success'))
                <div class="mb-4 toast-ok rounded-xl px-4 py-3 text-green-800 text-sm anim-up flex items-center gap-2 shadow-sm">
                    <svg class="w-5 h-5 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 toast-err rounded-xl px-4 py-3 text-red-800 text-sm anim-up flex items-center gap-2 shadow-sm">
                    <svg class="w-5 h-5 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 toast-err rounded-xl px-4 py-3 text-red-800 text-sm anim-up shadow-sm">
                    <ul class="space-y-0.5 list-disc list-inside">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif
        </div>

        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="mt-20" style="background:linear-gradient(180deg,#f0f4ff 0%,#e8eeff 100%)">
        <div class="cyber-divider"></div>
        <div class="max-w-7xl mx-auto px-4 py-12 grid grid-cols-1 sm:grid-cols-3 gap-10 text-sm">
            <div>
                <div class="flex items-center gap-2.5 mb-4">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center shadow"
                         style="background:linear-gradient(135deg,#6366f1,#06b6d4)">
                        <span class="text-white font-black text-xs">RS</span>
                    </div>
                    <span class="font-black text-gray-900 text-base">{{ config('app.name') }}</span>
                </div>
                <p class="text-gray-500 leading-relaxed text-xs">{{ config('app.store.address') }}</p>
                <div class="mt-3 space-y-1.5 text-gray-600">
                    <p>📞 {{ config('app.store.phone') }}</p>
                    <p>✉️ {{ config('app.store.email') }}</p>
                </div>
            </div>
            <div>
                <h3 class="font-bold text-gray-900 mb-4 sec-title">Quick Links</h3>
                <ul class="mt-2 space-y-2.5 text-gray-500">
                    <li><a href="{{ route('products.index') }}" class="hover:text-indigo-600 transition-colors flex items-center gap-1.5"><span class="text-indigo-400 text-xs">▸</span> All Products</a></li>
                    <li><a href="{{ route('orders.index') }}" class="hover:text-indigo-600 transition-colors flex items-center gap-1.5"><span class="text-indigo-400 text-xs">▸</span> Track Order</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold text-gray-900 mb-4 sec-title">Why Shop With Us</h3>
                <ul class="mt-2 space-y-2.5 text-gray-500">
                    @foreach(['Cash on Delivery','Fast local delivery','WhatsApp support: '.config('app.store.whatsapp'),'Easy 3-day returns'] as $f)
                    <li class="flex items-center gap-2">
                        <span class="w-4 h-4 rounded-full flex items-center justify-center text-[10px] text-indigo-600 font-bold" style="background:rgba(99,102,241,.12)">✓</span>
                        {{ $f }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="cyber-divider"></div>
        <p class="text-center text-xs text-gray-400 py-4">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved. Built with ❤️ in Pakistan</p>
    </footer>
</body>
</html>

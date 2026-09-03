<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background: #f5f7ff; }

        .grid-bg {
            background-color: #f5f7ff;
            background-image:
                linear-gradient(rgba(99,102,241,.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99,102,241,.05) 1px, transparent 1px);
            background-size: 32px 32px;
        }
        .sidebar {
            background: linear-gradient(180deg, #ffffff 0%, #f8f9ff 100%);
            border-right: 1px solid rgba(99,102,241,.12);
            box-shadow: 4px 0 24px rgba(99,102,241,.06);
        }
        .sidebar-link {
            display:flex; align-items:center; gap:10px;
            padding:.65rem 1rem; border-radius:10px; margin:.1rem .6rem;
            font-size:.875rem; font-weight:500; color:#4b5563;
            transition: all .2s cubic-bezier(.4,0,.2,1);
            position:relative; overflow:hidden;
        }
        .sidebar-link:hover {
            background: rgba(99,102,241,.08);
            color: #4f46e5;
            transform: translateX(3px);
        }
        .sidebar-link.active {
            background: linear-gradient(135deg,rgba(99,102,241,.15),rgba(6,182,212,.08));
            color: #4f46e5; font-weight:600;
            box-shadow: inset 3px 0 0 #6366f1;
        }
        .glass-card {
            background: rgba(255,255,255,0.9);
            border: 1px solid rgba(99,102,241,.1);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(99,102,241,.07), 0 1px 4px rgba(0,0,0,.04);
        }
        .btn-primary {
            background: linear-gradient(135deg,#6366f1,#06b6d4);
            color:#fff; font-weight:600; border-radius:10px;
            padding:.5rem 1.25rem; font-size:.875rem;
            transition: all .3s; box-shadow:0 4px 14px rgba(99,102,241,.3);
            position:relative; overflow:hidden;
        }
        .btn-primary:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(99,102,241,.4); }
        .input-cyber {
            background:#fff; border:1.5px solid rgba(99,102,241,.2);
            border-radius:10px; padding:.5rem .75rem; font-size:.875rem;
            width:100%; transition:all .25s; color:#1e1b4b;
        }
        .input-cyber:focus { outline:none; border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,.12); }
        .stat-card {
            background:#fff; border:1px solid rgba(99,102,241,.1);
            border-radius:16px; padding:1.25rem;
            box-shadow:0 4px 16px rgba(99,102,241,.06);
            transition:all .3s cubic-bezier(.34,1.56,.64,1);
        }
        .stat-card:hover { transform:translateY(-4px); box-shadow:0 12px 32px rgba(99,102,241,.14); }
        .pill-pending   { background:#fef9c3; color:#854d0e; border:1px solid #fde68a; padding:.2rem .6rem; border-radius:9999px; font-size:.7rem; font-weight:600; }
        .pill-active    { background:#dbeafe; color:#1e40af; border:1px solid #bfdbfe; padding:.2rem .6rem; border-radius:9999px; font-size:.7rem; font-weight:600; }
        .pill-delivered { background:#dcfce7; color:#166534; border:1px solid #bbf7d0; padding:.2rem .6rem; border-radius:9999px; font-size:.7rem; font-weight:600; }
        .pill-cancelled { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; padding:.2rem .6rem; border-radius:9999px; font-size:.7rem; font-weight:600; }
        .toast-ok  { background:linear-gradient(135deg,#f0fdf4,#dcfce7); border-left:4px solid #22c55e; border-radius:10px; padding:.75rem 1rem; font-size:.875rem; color:#166534; margin-bottom:1rem; }
        .toast-err { background:linear-gradient(135deg,#fef2f2,#fee2e2); border-left:4px solid #ef4444; border-radius:10px; padding:.75rem 1rem; font-size:.875rem; color:#991b1b; margin-bottom:1rem; }
        ::-webkit-scrollbar{width:4px} ::-webkit-scrollbar-thumb{background:linear-gradient(180deg,#6366f1,#06b6d4);border-radius:4px}
        @keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:none}}
        .anim-up{animation:fadeUp .45s cubic-bezier(.22,1,.36,1) both}
        .d1{animation-delay:.05s}.d2{animation-delay:.1s}.d3{animation-delay:.15s}
        .d4{animation-delay:.2s}.d5{animation-delay:.25s}.d6{animation-delay:.3s}
        .cyber-divider{height:1px;background:linear-gradient(90deg,transparent,rgba(99,102,241,.2),rgba(6,182,212,.2),transparent)}
    </style>
</head>
<body class="grid-bg">
<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="sidebar w-60 shrink-0 flex flex-col sticky top-0 h-screen overflow-y-auto">
        <div class="px-4 py-5 border-b border-indigo-50">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center shadow-sm" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">
                    <span class="text-white font-black text-xs">RS</span>
                </div>
                <div>
                    <p class="font-black text-gray-900 text-sm leading-none">{{ config('app.name') }}</p>
                    <p class="text-[10px] font-semibold text-indigo-400 tracking-widest uppercase mt-0.5">Admin Panel</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 py-3">
            <div class="px-3 pb-2">
                <a href="{{ route('admin.pos.index') }}"
                   class="flex items-center justify-between px-3 py-2 rounded-xl font-bold text-xs text-white shadow-md transition group"
                   style="background:linear-gradient(135deg,#10b981,#059669)">
                    <div class="flex items-center gap-2">
                        <span class="text-sm">⚡</span>
                        <span>POS Counter</span>
                    </div>
                    <span class="px-1.5 py-0.5 rounded text-[9px] font-black uppercase bg-white/20 text-white tracking-wider">LIVE</span>
                </a>
            </div>

            @php
                $links = [
                    ['admin.dashboard',         'Dashboard',       '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>'],
                    ['admin.products.index',    'Products',        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>'],
                    ['admin.categories.index',  'Categories',      '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>'],
                    ['admin.orders.index',      'Orders',          '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>'],
                    ['admin.customers.index',   'Customers',       '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>'],
                ];
                $links2 = [
                    ['admin.pos.register',            'Shift Register',   '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                    ['admin.vendors.index',           'Vendors',          '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>'],
                    ['admin.purchase-orders.index',   'Purchase Orders',  '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>'],
                    ['admin.stock.index',             'Stock',            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>'],
                    ['admin.discount-codes.index',    'Discount Codes',   '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>'],
                    ['admin.expenses.index',          'Expenses',         '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>'],
                    ['admin.reports.index',           'Reports',          '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>'],
                ];
            @endphp
            @foreach($links as [$route, $label, $svgPath])
                <a href="{{ route($route) }}"
                   class="sidebar-link {{ request()->routeIs($route.'*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgPath !!}</svg>
                    {{ $label }}
                </a>
            @endforeach

            <div class="cyber-divider mx-4 my-3"></div>
            <p class="px-4 py-1 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Operations</p>

            @foreach($links2 as [$route, $label, $svgPath])
                <a href="{{ route($route) }}"
                   class="sidebar-link {{ request()->routeIs(str_replace('.index','',$route).'*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svgPath !!}</svg>
                    {{ $label }}
                </a>
            @endforeach

            <div class="cyber-divider mx-4 my-3"></div>

            <a href="{{ route('home') }}" target="_blank" class="sidebar-link">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                View Store
            </a>
            <form action="{{ route('logout') }}" method="POST" class="mx-1">
                @csrf
                <button class="sidebar-link w-full text-left text-red-500 hover:bg-red-50">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </form>
        </nav>
    </aside>

    {{-- Main --}}
    <div class="flex-1 flex flex-col min-w-0">
        <header class="bg-white/80 backdrop-blur border-b border-indigo-50 px-6 py-4 flex items-center justify-between sticky top-0 z-40 shadow-sm shadow-indigo-50">
            <h1 class="text-base font-bold text-gray-800 flex items-center gap-2">
                <span class="w-1 h-5 rounded-full inline-block" style="background:linear-gradient(180deg,#6366f1,#06b6d4)"></span>
                @yield('page-title', 'Dashboard')
            </h1>
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm"
                     style="background:linear-gradient(135deg,#6366f1,#06b6d4)">
                    {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                </div>
                <span class="text-sm font-medium text-gray-600">{{ auth()->user()->name }}</span>
            </div>
        </header>

        <main class="flex-1 p-6">
            @if(session('success'))
                <div class="toast-ok flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="toast-err">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="toast-err">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
</body>
</html>

@extends('layouts.app')
@section('title', config('app.name').' — Shop Online with Cash on Delivery')
@section('content')

{{-- ── HERO ── --}}
<section class="relative overflow-hidden" style="background:linear-gradient(135deg,#f0f4ff 0%,#e8eeff 50%,#f0faff 100%); min-height:520px;">
    {{-- Orbs --}}
    <div class="orb w-96 h-96" style="background:#6366f1;top:-80px;right:-60px;"></div>
    <div class="orb orb-2 w-72 h-72" style="background:#06b6d4;bottom:-60px;left:-40px;"></div>
    <div class="orb orb-3 w-48 h-48" style="background:#a78bfa;top:50%;left:35%;"></div>

    {{-- Grid overlay --}}
    <div class="absolute inset-0 pointer-events-none"
         style="background-image:linear-gradient(rgba(99,102,241,.08) 1px,transparent 1px),linear-gradient(90deg,rgba(99,102,241,.08) 1px,transparent 1px);background-size:40px 40px;"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 py-20 flex flex-col md:flex-row items-center gap-12">
        {{-- Text --}}
        <div class="flex-1 anim-up">
            <div class="inline-flex items-center gap-2 mb-5 px-4 py-1.5 rounded-full text-xs font-semibold border border-indigo-200 bg-white/60 backdrop-blur text-indigo-700 shadow-sm">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                Pakistan's #1 Local Retail Store
            </div>
            <h1 class="text-4xl sm:text-6xl font-black leading-tight text-gray-900 mb-5">
                Shop Smart.<br>
                <span style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Pay on Delivery.</span>
            </h1>
            <p class="text-gray-500 text-base sm:text-lg max-w-lg leading-relaxed mb-8">
                Electronics, fashion, beauty & home essentials — delivered anywhere in Pakistan with Cash on Delivery.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('products.index') }}" class="btn-primary inline-flex items-center gap-2 px-7 py-3.5 text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    Start Shopping
                </a>
                <a href="#categories"
                   class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl border-2 border-indigo-200 text-indigo-700 font-semibold bg-white/60 backdrop-blur hover:bg-indigo-50 hover:border-indigo-400 transition-all duration-300 text-base">
                    Browse Categories
                </a>
            </div>
            <div class="flex items-center gap-8 mt-8 text-sm text-gray-500">
                @foreach(['✦ Free Shipping','✦ COD Available','✦ Easy Returns'] as $f)
                    <span class="font-medium">{{ $f }}</span>
                @endforeach
            </div>
        </div>

        {{-- Floating card mockup --}}
        <div class="flex-shrink-0 hidden md:block anim-up d2">
            <div class="relative w-72">
                <div class="glass-card rounded-3xl p-6 shadow-2xl shadow-indigo-200/50">
                    <div class="w-full h-40 rounded-2xl mb-4 flex items-center justify-center text-6xl"
                         style="background:linear-gradient(135deg,#e0e7ff,#cffafe)">🛍️</div>
                    <div class="cyber-tag mb-2">New Arrival</div>
                    <p class="font-bold text-gray-900 text-sm mt-2">Premium Quality Products</p>
                    <p class="text-xs text-gray-400 mt-1">Delivered across Pakistan</p>
                    <div class="flex items-center justify-between mt-4">
                        <span class="font-black text-lg" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Rs 1,999</span>
                        <div class="btn-primary px-4 py-1.5 text-xs rounded-xl">Add to Cart</div>
                    </div>
                </div>
                {{-- Floating badges --}}
                <div class="absolute -top-3 -right-3 bg-white rounded-2xl shadow-lg shadow-indigo-100 px-3 py-2 text-xs font-bold text-indigo-700 border border-indigo-100">
                    🚚 Free Ship
                </div>
                <div class="absolute -bottom-3 -left-3 bg-white rounded-2xl shadow-lg shadow-cyan-100 px-3 py-2 text-xs font-bold text-cyan-700 border border-cyan-100">
                    💳 COD
                </div>
            </div>
        </div>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4">

    {{-- Trust badges --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 -mt-6 mb-14 relative z-10">
        @foreach([
            ['🚀','Fast Delivery','2–5 business days','#e0e7ff','#6366f1'],
            ['💰','Cash on Delivery','Pay on arrival','#cffafe','#0891b2'],
            ['🔄','Easy Returns','3-day return policy','#dcfce7','#16a34a'],
            ['🎧','24/7 Support','WhatsApp anytime','#fce7f3','#db2777'],
        ] as [$icon,$title,$sub,$bg,$color])
        <div class="glass-card rounded-2xl p-4 flex items-center gap-3 card-lift anim-up">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xl shrink-0" style="background:{{ $bg }}">{{ $icon }}</div>
            <div>
                <p class="font-bold text-sm text-gray-900">{{ $title }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $sub }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Categories --}}
    @if($categories->isNotEmpty())
    <section id="categories" class="mb-14">
        <div class="flex items-center justify-between mb-7">
            <div>
                <h2 class="text-xl font-black text-gray-900 sec-title">Shop by Category</h2>
                <p class="text-sm text-gray-400 mt-3">Find exactly what you need</p>
            </div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
            @foreach($categories as $i => $cat)
            <a href="{{ route('categories.show', $cat) }}"
               class="glass-card rounded-2xl p-4 text-center card-lift anim-up d{{ min($i+1,6) }} group">
                <div class="w-12 h-12 rounded-xl mx-auto mb-3 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform duration-300"
                     style="background:linear-gradient(135deg,#e0e7ff,#cffafe)">🛍️</div>
                <p class="text-sm font-semibold text-gray-800 group-hover:text-indigo-700 transition-colors">{{ $cat->name }}</p>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Featured Deals --}}
    @if($featured->isNotEmpty())
    <section class="mb-14">
        <div class="flex items-center justify-between mb-7">
            <div>
                <h2 class="text-xl font-black text-gray-900 sec-title">Featured Deals</h2>
                <p class="text-sm text-gray-400 mt-3">Handpicked just for you</p>
            </div>
            <a href="{{ route('products.index') }}"
               class="inline-flex items-center gap-1.5 text-sm font-semibold text-indigo-600 border border-indigo-200 px-4 py-2 rounded-xl hover:bg-indigo-50 hover:border-indigo-400 transition-all duration-200">
                View all
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            @foreach($featured as $i => $product)
            <div class="anim-up d{{ min($i+1,6) }}"><x-product-card :product="$product" /></div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- New Arrivals --}}
    @if($newArrivals->isNotEmpty())
    <section class="mb-14">
        <div class="flex items-center justify-between mb-7">
            <div>
                <h2 class="text-xl font-black text-gray-900 sec-title">New Arrivals</h2>
                <p class="text-sm text-gray-400 mt-3">Fresh stock just added</p>
            </div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            @foreach($newArrivals as $i => $product)
            <div class="anim-up d{{ min($i+1,6) }}"><x-product-card :product="$product" /></div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- CTA Banner --}}
    <section class="mb-16 rounded-3xl overflow-hidden relative" style="background:linear-gradient(135deg,#6366f1 0%,#4f46e5 40%,#0891b2 100%)">
        <div class="orb w-64 h-64" style="background:#a78bfa;top:-40px;right:5%;opacity:.3"></div>
        <div class="orb orb-2 w-48 h-48" style="background:#22d3ee;bottom:-20px;left:10%;opacity:.25"></div>
        <div class="relative z-10 p-10 sm:p-14 flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="text-white">
                <p class="text-sm font-semibold text-indigo-200 mb-1 tracking-wide uppercase">Limited Time</p>
                <h2 class="text-2xl sm:text-3xl font-black mb-2">Get Free Shipping Today!</h2>
                <p class="text-indigo-200">Orders above Rs {{ number_format(config('app.store.free_shipping_threshold')) }} qualify for free delivery.</p>
            </div>
            <a href="{{ route('products.index') }}"
               class="shrink-0 bg-white text-indigo-700 font-black px-8 py-3.5 rounded-2xl hover:bg-indigo-50 transition-all duration-300 shadow-xl hover:shadow-2xl hover:-translate-y-1 whitespace-nowrap">
                Shop Now →
            </a>
        </div>
    </section>

</div>
@endsection

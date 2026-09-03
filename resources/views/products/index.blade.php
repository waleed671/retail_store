@extends('layouts.app')
@section('title', 'Shop All Products — '.config('app.name'))
@section('content')
<div class="max-w-7xl mx-auto px-4 pb-14">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-1.5 text-xs text-gray-400 mb-6 anim-in">
        <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors font-medium">Home</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-700 font-semibold">Shop</span>
    </div>

    <div class="flex flex-col md:flex-row gap-6">

        {{-- Sidebar --}}
        <aside class="w-full md:w-64 shrink-0 anim-up">
            <form action="{{ route('products.index') }}" method="GET"
                  class="glass-card rounded-2xl p-5 space-y-6 sticky top-24">
                <input type="hidden" name="q" value="{{ request('q') }}">

                <div class="flex items-center justify-between">
                    <h3 class="font-black text-gray-900 text-sm">Filters</h3>
                    <a href="{{ route('products.index') }}"
                       class="text-[11px] font-semibold text-red-400 hover:text-red-600 transition-colors bg-red-50 px-2 py-0.5 rounded-lg">Clear</a>
                </div>

                <div class="cyber-divider"></div>

                {{-- Category --}}
                <div>
                    <h4 class="text-xs font-black text-gray-700 uppercase tracking-widest mb-3">Category</h4>
                    <div class="space-y-2 text-sm">
                        <label class="flex items-center gap-2.5 cursor-pointer group">
                            <input type="radio" name="category" value="" {{ request('category') ? '' : 'checked' }}
                                   onchange="this.form.submit()" class="accent-indigo-600 w-3.5 h-3.5">
                            <span class="group-hover:text-indigo-700 transition-colors text-gray-600">All Categories</span>
                        </label>
                        @foreach($categories as $cat)
                        <label class="flex items-center gap-2.5 cursor-pointer group">
                            <input type="radio" name="category" value="{{ $cat->slug }}"
                                   {{ request('category') == $cat->slug ? 'checked' : '' }}
                                   onchange="this.form.submit()" class="accent-indigo-600 w-3.5 h-3.5">
                            <span class="group-hover:text-indigo-700 transition-colors text-gray-600">{{ $cat->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="cyber-divider"></div>

                {{-- Price --}}
                <div>
                    <h4 class="text-xs font-black text-gray-700 uppercase tracking-widest mb-3">Price Range (Rs)</h4>
                    <div class="flex items-center gap-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min"
                               class="input-cyber w-full px-3 py-2 text-sm">
                        <span class="text-gray-300 font-light">–</span>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max"
                               class="input-cyber w-full px-3 py-2 text-sm">
                    </div>
                </div>

                {{-- Stock --}}
                <label class="flex items-center gap-2.5 text-sm cursor-pointer group">
                    <input type="checkbox" name="in_stock" value="1" {{ request('in_stock') ? 'checked' : '' }}
                           class="accent-indigo-600 w-4 h-4 rounded">
                    <span class="text-gray-600 group-hover:text-indigo-700 transition-colors">In stock only</span>
                </label>

                <button class="btn-primary w-full py-2.5 text-sm">Apply Filters</button>
            </form>
        </aside>

        {{-- Products --}}
        <div class="flex-1 anim-up d1">
            {{-- Toolbar --}}
            <div class="glass-card rounded-2xl px-5 py-3 flex items-center justify-between mb-5">
                <p class="text-sm text-gray-500">
                    <span class="font-bold text-gray-900">{{ $products->total() }}</span> products
                    @if(request('q'))<span class="text-indigo-600"> for "{{ request('q') }}"</span>@endif
                </p>
                <form action="{{ route('products.index') }}" method="GET" class="flex items-center gap-2">
                    @foreach(request()->except('sort') as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <label class="text-xs text-gray-400 hidden sm:inline font-semibold">Sort:</label>
                    <select name="sort" onchange="this.form.submit()"
                            class="input-cyber px-3 py-1.5 text-sm cursor-pointer">
                        <option value="newest" {{ request('sort','newest')=='newest'?'selected':'' }}>Newest</option>
                        <option value="price_asc" {{ request('sort')=='price_asc'?'selected':'' }}>Price: Low–High</option>
                        <option value="price_desc" {{ request('sort')=='price_desc'?'selected':'' }}>Price: High–Low</option>
                        <option value="name" {{ request('sort')=='name'?'selected':'' }}>Name A–Z</option>
                    </select>
                </form>
            </div>

            @if($products->isEmpty())
                <div class="glass-card rounded-2xl p-14 text-center">
                    <div class="text-5xl mb-4">🔍</div>
                    <p class="text-gray-500 font-semibold">No products match your filters.</p>
                    <a href="{{ route('products.index') }}" class="text-sm text-indigo-600 hover:underline mt-2 inline-block">Clear filters</a>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($products as $i => $product)
                        <div class="anim-up" style="animation-delay:{{ $i * 0.04 }}s">
                            <x-product-card :product="$product" />
                        </div>
                    @endforeach
                </div>
                <div class="mt-8">{{ $products->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', $category->name.' — '.config('app.name'))
@section('content')
<div class="max-w-7xl mx-auto px-4 pb-14">

    {{-- Category Hero --}}
    <div class="relative rounded-3xl overflow-hidden mb-10 anim-up" style="background:linear-gradient(135deg,#f0f4ff,#e8eeff,#f0faff)">
        <div class="absolute inset-0 pointer-events-none"
             style="background-image:linear-gradient(rgba(99,102,241,.07) 1px,transparent 1px),linear-gradient(90deg,rgba(99,102,241,.07) 1px,transparent 1px);background-size:32px 32px;"></div>
        <div class="orb w-48 h-48" style="background:#6366f1;top:-30px;right:5%;opacity:.15"></div>
        <div class="relative z-10 px-8 py-10 flex items-center gap-6">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl shadow-lg flex-shrink-0"
                 style="background:linear-gradient(135deg,#e0e7ff,#cffafe)">🛍️</div>
            <div>
                <div class="cyber-tag mb-2">Category</div>
                <h1 class="text-3xl font-black text-gray-900">{{ $category->name }}</h1>
                @if($category->description)
                    <p class="text-gray-500 text-sm mt-1">{{ $category->description }}</p>
                @endif
            </div>
        </div>
    </div>

    @if($products->isEmpty())
        <div class="glass-card rounded-3xl p-14 text-center anim-up">
            <div class="text-6xl mb-4">📭</div>
            <p class="text-gray-500 font-semibold">No products in this category yet.</p>
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
@endsection

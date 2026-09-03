@extends('layouts.app')
@section('title', 'My Wishlist — '.config('app.name'))
@section('content')
<div class="max-w-7xl mx-auto px-4 pb-14">

    <h1 class="text-2xl font-black text-gray-900 mb-7 anim-up flex items-center gap-2">
        <svg class="w-6 h-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        My Wishlist
    </h1>

    @if($items->isEmpty())
        <div class="glass-card rounded-3xl p-16 text-center anim-up">
            <div class="text-7xl mb-5">💝</div>
            <h2 class="text-xl font-black text-gray-800 mb-2">Your wishlist is empty</h2>
            <p class="text-gray-400 mb-6">Save products you love and come back to them later.</p>
            <a href="{{ route('products.index') }}" class="btn-primary inline-flex items-center gap-2 px-6 py-3 text-sm">
                Browse Products
            </a>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($items as $i => $item)
                <div class="anim-up d{{ min($i+1,6) }}">
                    <x-product-card :product="$item->product" />
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

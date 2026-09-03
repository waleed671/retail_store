@extends('layouts.app')
@section('title', 'Your Cart — '.config('app.name'))
@section('content')
<div class="max-w-7xl mx-auto px-4 pb-14">
    <h1 class="text-2xl font-black text-gray-900 mb-7 anim-up flex items-center gap-2">
        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        Shopping Cart
    </h1>

    @if($items->isEmpty())
        <div class="glass-card rounded-3xl p-16 text-center anim-up">
            <div class="text-7xl mb-5">🛒</div>
            <h2 class="text-xl font-black text-gray-800 mb-2">Your cart is empty</h2>
            <p class="text-gray-400 mb-6">Looks like you haven't added anything yet.</p>
            <a href="{{ route('products.index') }}" class="btn-primary inline-flex items-center gap-2 px-6 py-3 text-sm">
                Continue Shopping
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Items --}}
            <div class="lg:col-span-2 space-y-3">
                @foreach($items as $item)
                <div class="glass-card rounded-2xl p-4 flex items-center gap-4 anim-up card-lift">
                    <a href="{{ route('products.show', $item['product']) }}" class="shrink-0">
                        @if($item['product']->image)
                            <img src="{{ Storage::url($item['product']->image) }}"
                                 class="w-16 h-16 object-cover rounded-xl shadow-sm">
                        @else
                            <div class="w-16 h-16 rounded-xl flex items-center justify-center text-gray-300"
                                 style="background:linear-gradient(135deg,#f0f4ff,#e8eeff)">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/></svg>
                            </div>
                        @endif
                    </a>
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('products.show', $item['product']) }}"
                           class="font-semibold text-sm text-gray-800 hover:text-indigo-700 transition-colors line-clamp-1">
                            {{ $item['product']->name }}
                        </a>
                        <p class="text-xs text-gray-400 mt-0.5">Rs {{ number_format($item['product']->final_price) }} each</p>
                    </div>
                    <form action="{{ route('cart.update', $item['product']) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['product']->stock }}"
                               class="input-cyber w-16 px-2 py-1.5 text-sm text-center" onchange="this.form.submit()">
                    </form>
                    <div class="w-24 text-right font-black text-sm" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                        Rs {{ number_format($item['subtotal']) }}
                    </div>
                    <form action="{{ route('cart.remove', $item['product']) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="w-8 h-8 flex items-center justify-center rounded-xl text-gray-300 hover:text-red-500 hover:bg-red-50 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </form>
                </div>
                @endforeach
            </div>

            {{-- Summary --}}
            <div class="glass-card rounded-2xl p-6 h-fit sticky top-24 anim-up d1">
                <h2 class="font-black text-gray-900 mb-5 text-base">Order Summary</h2>
                <div class="flex justify-between text-sm text-gray-600 mb-2">
                    <span>Subtotal</span>
                    <span class="font-semibold text-gray-900">Rs {{ number_format($subtotal) }}</span>
                </div>
                <p class="text-xs text-gray-400 mb-6 pb-4 border-b border-gray-100">
                    Shipping calculated at checkout. Free over Rs {{ number_format(config('app.store.free_shipping_threshold')) }}.
                </p>
                <a href="{{ route('checkout.index') }}" class="btn-primary w-full py-3.5 text-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    Proceed to Checkout
                </a>
                <a href="{{ route('products.index') }}" class="block text-center text-sm text-indigo-500 hover:text-indigo-700 transition-colors mt-3 font-medium">
                    ← Continue Shopping
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

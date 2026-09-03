@extends('layouts.app')
@section('title', 'Order '.$order->order_number.' — '.config('app.name'))
@section('content')
<div class="max-w-5xl mx-auto px-4 pb-14">

    {{-- Header --}}
    <div class="flex items-start justify-between mb-8 anim-up">
        <div>
            <a href="{{ route('orders.index') }}" class="text-xs text-indigo-500 hover:text-indigo-700 font-semibold flex items-center gap-1 mb-3 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Orders
            </a>
            <h1 class="text-2xl font-black text-gray-900">Order {{ $order->order_number }}</h1>
            <p class="text-sm text-gray-400 mt-1">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>
        </div>
        <span @class([
            'text-sm px-4 py-1.5 rounded-full font-bold',
            'pill-pending'   => $order->status === 'pending',
            'pill-active'    => in_array($order->status, ['confirmed','processing','shipped']),
            'pill-delivered' => $order->status === 'delivered',
            'pill-cancelled' => in_array($order->status, ['cancelled','returned']),
        ])>{{ $order->statusLabel() }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Items --}}
        <div class="lg:col-span-2 glass-card rounded-2xl overflow-hidden anim-up d1">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-900 text-sm">Order Items</h2>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($order->items as $item)
                    <div class="px-5 py-4 flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-sm text-gray-800">{{ $item->product_name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">Rs {{ number_format($item->product_price) }} × {{ $item->quantity }}</p>
                        </div>
                        <p class="font-black text-sm" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                            Rs {{ number_format($item->subtotal) }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">

            {{-- Summary --}}
            <div class="glass-card rounded-2xl p-5 anim-up d2">
                <h2 class="font-bold text-gray-900 mb-4 text-sm">Payment Summary</h2>
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span class="font-semibold text-gray-800">Rs {{ number_format($order->subtotal) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Shipping</span>
                        <span class="font-semibold {{ $order->shipping_fee > 0 ? 'text-gray-800' : 'text-green-600' }}">
                            {{ $order->shipping_fee > 0 ? 'Rs '.number_format($order->shipping_fee) : 'Free' }}
                        </span>
                    </div>
                </div>
                <div class="cyber-divider my-3"></div>
                <div class="flex justify-between items-center">
                    <span class="font-black text-gray-900 text-sm">Total</span>
                    <span class="font-black text-base" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                        Rs {{ number_format($order->total) }}
                    </span>
                </div>
                <p class="text-xs text-gray-400 mt-3">
                    {{ $order->payment_method === 'cod' ? '💵 Cash on Delivery' : '🏦 Bank Transfer' }}
                    · {{ ucfirst($order->payment_status) }}
                </p>
            </div>

            {{-- Delivery --}}
            <div class="glass-card rounded-2xl p-5 anim-up d3">
                <h2 class="font-bold text-gray-900 mb-3 text-sm">Delivery Info</h2>
                <div class="space-y-1.5 text-sm text-gray-600">
                    <p class="font-semibold text-gray-800">{{ $order->customer_name }}</p>
                    <p>{{ $order->customer_phone }}</p>
                    <p class="text-gray-500">{{ $order->shipping_address }}, {{ $order->city }}</p>
                    @if($order->notes)
                        <p class="text-xs text-gray-400 pt-1 border-t border-gray-100 mt-2">Note: {{ $order->notes }}</p>
                    @endif
                </div>
            </div>

            {{-- Cancel --}}
            @if($order->canBeCancelled())
                <form action="{{ route('orders.cancel', $order) }}" method="POST"
                      onsubmit="return confirm('Cancel this order?')" class="anim-up d4">
                    @csrf
                    <button class="w-full py-2.5 rounded-xl text-sm font-semibold text-red-500 border-2 border-red-200 hover:bg-red-50 hover:border-red-400 transition-all duration-200">
                        Cancel Order
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection

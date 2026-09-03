@extends('layouts.app')
@section('title', 'My Orders — '.config('app.name'))
@section('content')
<div class="max-w-4xl mx-auto px-4 pb-14">

    <h1 class="text-2xl font-black text-gray-900 mb-7 anim-up flex items-center gap-2">
        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        My Orders
    </h1>

    @if($orders->isEmpty())
        <div class="glass-card rounded-3xl p-16 text-center anim-up">
            <div class="text-7xl mb-5">📦</div>
            <h2 class="text-xl font-black text-gray-800 mb-2">No orders yet</h2>
            <p class="text-gray-400 mb-6">You haven't placed any orders yet.</p>
            <a href="{{ route('products.index') }}" class="btn-primary inline-flex items-center gap-2 px-6 py-3 text-sm">
                Start Shopping
            </a>
        </div>
    @else
        <div class="space-y-3">
            @foreach($orders as $i => $order)
                <a href="{{ route('orders.show', $order) }}"
                   class="glass-card rounded-2xl px-5 py-4 flex items-center justify-between hover:shadow-lg hover:shadow-indigo-100/50 transition-all duration-300 hover:-translate-y-0.5 anim-up d{{ min($i+1,6) }} block">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                             style="background:linear-gradient(135deg,#e0e7ff,#cffafe)">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <div>
                            <p class="font-bold text-sm text-gray-900">{{ $order->order_number }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $order->created_at->format('d M Y, h:i A') }} · {{ $order->items->count() }} item(s)</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="font-black text-sm" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                            Rs {{ number_format($order->total) }}
                        </span>
                        <span @class([
                            'text-xs px-3 py-1 rounded-full font-bold',
                            'pill-pending'   => $order->status === 'pending',
                            'pill-active'    => in_array($order->status, ['confirmed','processing','shipped']),
                            'pill-delivered' => $order->status === 'delivered',
                            'pill-cancelled' => in_array($order->status, ['cancelled','returned']),
                        ])>{{ $order->statusLabel() }}</span>
                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-8">{{ $orders->links() }}</div>
    @endif
</div>
@endsection

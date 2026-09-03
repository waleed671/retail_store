@extends('layouts.admin')

@section('title', 'Order '.$order->order_number)
@section('page-title', 'Order '.$order->order_number)

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white border rounded-lg divide-y">
            @foreach($order->items as $item)
                <div class="p-4 flex justify-between text-sm">
                    <div>
                        <p class="font-medium">{{ $item->product_name }}</p>
                        <p class="text-gray-500">Rs {{ number_format($item->product_price) }} &times; {{ $item->quantity }}</p>
                    </div>
                    <p class="font-semibold">Rs {{ number_format($item->subtotal) }}</p>
                </div>
            @endforeach
            <div class="p-4 text-sm space-y-1">
                <div class="flex justify-between"><span>Subtotal</span><span>Rs {{ number_format($order->subtotal) }}</span></div>
                <div class="flex justify-between"><span>Shipping</span><span>Rs {{ number_format($order->shipping_fee) }}</span></div>
                <div class="flex justify-between font-bold"><span>Total</span><span>Rs {{ number_format($order->total) }}</span></div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-white border rounded-lg p-5">
                <h2 class="font-semibold mb-2 text-sm">Update Status</h2>
                <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="flex gap-2">
                    @csrf @method('PATCH')
                    <select name="status" class="flex-1 border rounded px-2 py-2 text-sm">
                        @foreach(['pending','confirmed','processing','shipped','delivered','cancelled','returned'] as $status)
                            <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <button class="bg-brand-600 hover:bg-brand-700 text-white px-3 py-2 rounded-md text-sm">Update</button>
                </form>
                <p class="text-xs text-gray-500 mt-2">Payment: {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Bank Transfer' }} — {{ ucfirst($order->payment_status) }}</p>
            </div>

            <div class="bg-white border rounded-lg p-5">
                <h2 class="font-semibold mb-2 text-sm">Customer</h2>
                <p class="text-sm">{{ $order->customer_name }}</p>
                <p class="text-sm">{{ $order->customer_phone }}</p>
                @if($order->customer_email)<p class="text-sm">{{ $order->customer_email }}</p>@endif
                <p class="text-sm text-gray-700 mt-2">{{ $order->shipping_address }}, {{ $order->city }}</p>
                @if($order->notes)<p class="text-xs text-gray-500 mt-2">Note: {{ $order->notes }}</p>@endif
                @if($order->user)
                    <p class="text-xs text-gray-400 mt-2">Account: {{ $order->user->email }}</p>
                @endif
            </div>
        </div>
    </div>
@endsection

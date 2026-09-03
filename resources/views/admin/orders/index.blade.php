@extends('layouts.admin')

@section('title', 'Orders')
@section('page-title', 'Orders')

@section('content')
    <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap gap-3 mb-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search order #, name, phone..." class="border rounded px-3 py-2 text-sm w-64">
        <select name="status" onchange="this.form.submit()" class="border rounded px-3 py-2 text-sm">
            <option value="">All Statuses</option>
            @foreach(['pending','confirmed','processing','shipped','delivered','cancelled','returned'] as $status)
                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
        <button class="bg-brand-600 hover:bg-brand-700 text-white text-sm px-4 py-2 rounded-md">Filter</button>
    </form>

    <div class="bg-white border rounded-lg overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-xs text-gray-500 border-b bg-gray-50">
                <tr>
                    <th class="text-left px-4 py-2">Order #</th>
                    <th class="text-left px-4 py-2">Customer</th>
                    <th class="text-left px-4 py-2">Payment</th>
                    <th class="text-right px-4 py-2">Total</th>
                    <th class="text-center px-4 py-2">Status</th>
                    <th class="text-left px-4 py-2">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('admin.orders.show', $order) }}'">
                        <td class="px-4 py-2 font-medium">{{ $order->order_number }}</td>
                        <td class="px-4 py-2">{{ $order->customer_name }}<br><span class="text-xs text-gray-500">{{ $order->customer_phone }}</span></td>
                        <td class="px-4 py-2">{{ $order->payment_method === 'cod' ? 'COD' : 'Bank Transfer' }}</td>
                        <td class="px-4 py-2 text-right">Rs {{ number_format($order->total) }}</td>
                        <td class="px-4 py-2 text-center">
                            <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100">{{ ucfirst($order->status) }}</span>
                        </td>
                        <td class="px-4 py-2 text-gray-500">{{ $order->created_at->format('d M, h:i A') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $orders->links() }}</div>
@endsection

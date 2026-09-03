@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('content')

{{-- Stat cards row 1 --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-4">
    @php
        $cards = [
            ['Orders Today',    $stats['orders_today'],                      '#e0e7ff','#6366f1','<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>'],
            ['Revenue (Month)', 'Rs '.number_format($stats['revenue_month']), '#cffafe','#0891b2','<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
            ['Pending Orders',  $stats['pending_orders'],                    '#fef9c3','#ca8a04','<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
            ['Low Stock',       $stats['low_stock'],                         '#fee2e2','#dc2626','<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>'],
            ['Total Products',  $stats['total_products'],                    '#f3e8ff','#7c3aed','<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>'],
        ];
    @endphp
    @foreach($cards as $i => [$label,$value,$bg,$color,$path])
        <div class="stat-card anim-up d{{ min($i+1,6) }}">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background:{{ $bg }}">
                <svg class="w-4 h-4" fill="none" stroke="{{ $color }}" viewBox="0 0 24 24">{!! $path !!}</svg>
            </div>
            <p class="text-xs text-gray-400 font-medium">{{ $label }}</p>
            <p class="text-xl font-black text-gray-900 mt-0.5">{{ $value }}</p>
        </div>
    @endforeach
</div>

{{-- Stat cards row 2: financial --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    @php
        $cards2 = [
            ['Total Customers',   $stats['total_customers'],                           '#dcfce7','#16a34a','<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>'],
            ['Active Vendors',    $stats['total_vendors'],                             '#fef3c7','#d97706','<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>'],
            ['Expenses (Month)',  'Rs '.number_format($stats['expenses_month']),       '#fee2e2','#dc2626','<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>'],
            ['Net Profit (Month)','Rs '.number_format($stats['net_profit']),           $stats['net_profit'] >= 0 ? '#dcfce7' : '#fee2e2', $stats['net_profit'] >= 0 ? '#16a34a' : '#dc2626','<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>'],
        ];
    @endphp
    @foreach($cards2 as $i => [$label,$value,$bg,$color,$path])
        <div class="stat-card anim-up d{{ min($i+1,6) }}">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background:{{ $bg }}">
                <svg class="w-4 h-4" fill="none" stroke="{{ $color }}" viewBox="0 0 24 24">{!! $path !!}</svg>
            </div>
            <p class="text-xs text-gray-400 font-medium">{{ $label }}</p>
            <p class="text-xl font-black text-gray-900 mt-0.5">{{ $value }}</p>
        </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Recent Orders --}}
    <div class="glass-card rounded-2xl overflow-hidden anim-up d1">
        <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
            <h2 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                <span class="w-1 h-4 rounded-full inline-block" style="background:linear-gradient(180deg,#6366f1,#06b6d4)"></span>
                Recent Orders
            </h2>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">View all &rarr;</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentOrders as $order)
                <a href="{{ route('admin.orders.show', $order) }}"
                   class="flex items-center justify-between px-5 py-3.5 hover:bg-indigo-50/40 transition-colors">
                    <div>
                        <p class="font-semibold text-sm text-gray-800">{{ $order->order_number }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $order->customer_name }} &middot; {{ $order->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-black text-sm" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                            Rs {{ number_format($order->total) }}
                        </p>
                        <span @class([
                            'text-[10px] font-bold px-2 py-0.5 rounded-full',
                            'pill-pending'   => $order->status === 'pending',
                            'pill-active'    => in_array($order->status, ['confirmed','processing','shipped']),
                            'pill-delivered' => $order->status === 'delivered',
                            'pill-cancelled' => in_array($order->status, ['cancelled','returned']),
                        ])>{{ ucfirst($order->status) }}</span>
                    </div>
                </a>
            @empty
                <div class="px-5 py-8 text-center text-gray-400 text-sm">
                    <div class="text-3xl mb-2">&#128301;</div>No orders yet.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Low Stock --}}
    <div class="glass-card rounded-2xl overflow-hidden anim-up d2">
        <div class="px-5 py-4 border-b border-gray-50 flex items-center gap-2">
            <span class="w-1 h-4 rounded-full inline-block" style="background:linear-gradient(180deg,#ef4444,#f97316)"></span>
            <h2 class="font-bold text-gray-900 text-sm">Low Stock Alerts</h2>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($lowStockProducts as $product)
                <a href="{{ route('admin.products.edit', $product) }}"
                   class="flex items-center justify-between px-5 py-3.5 hover:bg-red-50/40 transition-colors">
                    <span class="text-sm text-gray-700 font-medium">{{ $product->name }}</span>
                    <span class="text-xs font-black text-red-500 bg-red-50 border border-red-100 px-2.5 py-1 rounded-lg">
                        {{ $product->stock }} left
                    </span>
                </a>
            @empty
                <div class="px-5 py-8 text-center text-gray-400 text-sm">
                    <div class="text-3xl mb-2">&#9989;</div>Stock levels look healthy!
                </div>
            @endforelse
        </div>
    </div>

    {{-- Top Products --}}
    <div class="glass-card rounded-2xl overflow-hidden lg:col-span-2 anim-up d3">
        <div class="px-5 py-4 border-b border-gray-50 flex items-center gap-2">
            <span class="w-1 h-4 rounded-full inline-block" style="background:linear-gradient(180deg,#f59e0b,#10b981)"></span>
            <h2 class="font-bold text-gray-900 text-sm">Top Selling Products</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead style="background:linear-gradient(135deg,#f8f9ff,#f0f4ff)">
                    <tr>
                        <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">#</th>
                        <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Units Sold</th>
                        <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($topProducts as $i => $row)
                        <tr class="hover:bg-indigo-50/30 transition-colors">
                            <td class="px-5 py-3.5 text-gray-400 font-bold text-xs">{{ $i + 1 }}</td>
                            <td class="px-5 py-3.5 font-semibold text-gray-800">{{ $row->product_name }}</td>
                            <td class="px-5 py-3.5 text-right">
                                <span class="font-bold text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-lg text-xs">{{ $row->total_qty }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-right font-black text-sm" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                                Rs {{ number_format($row->total_revenue) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-gray-400">
                                <div class="text-3xl mb-2">&#128202;</div>No sales data yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

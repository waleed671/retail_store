@extends('layouts.admin')
@section('title', 'Financial Reports')
@section('page-title', 'Financial Reports')
@section('content')

{{-- Summary stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @php
        $summaryCards = [
            ['Revenue This Month', 'Rs '.number_format($revenueMonth),   '#cffafe','#0891b2','<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
            ['Revenue This Year',  'Rs '.number_format($revenueYear),    '#e0e7ff','#6366f1','<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>'],
            ['Total Orders',       number_format($totalOrders),           '#dcfce7','#16a34a','<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>'],
            ['Avg Order Value',    'Rs '.number_format($avgOrderValue),   '#fef3c7','#d97706','<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>'],
        ];
    @endphp
    @foreach($summaryCards as $i => [$label,$value,$bg,$color,$path])
        <div class="stat-card anim-up d{{ $i+1 }}">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background:{{ $bg }}">
                <svg class="w-4 h-4" fill="none" stroke="{{ $color }}" viewBox="0 0 24 24">{!! $path !!}</svg>
            </div>
            <p class="text-xs text-gray-400 font-medium">{{ $label }}</p>
            <p class="text-xl font-black text-gray-900 mt-0.5">{{ $value }}</p>
        </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    {{-- Top 10 products --}}
    <div class="glass-card rounded-2xl overflow-hidden anim-up d1">
        <div class="px-5 py-4 border-b border-gray-50 flex items-center gap-2">
            <span class="w-1 h-4 rounded-full inline-block" style="background:linear-gradient(180deg,#f59e0b,#10b981)"></span>
            <h2 class="font-bold text-gray-900 text-sm">Top 10 Products by Revenue</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead style="background:linear-gradient(135deg,#f8f9ff,#f0f4ff)">
                    <tr>
                        <th class="text-left px-4 py-2.5 text-xs font-bold text-gray-500 uppercase tracking-wider">#</th>
                        <th class="text-left px-4 py-2.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="text-right px-4 py-2.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Qty</th>
                        <th class="text-right px-4 py-2.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($topProducts as $i => $row)
                        <tr class="hover:bg-indigo-50/30">
                            <td class="px-4 py-2.5 text-xs text-gray-400 font-bold">{{ $i+1 }}</td>
                            <td class="px-4 py-2.5 text-gray-700 font-medium text-xs">{{ $row->product_name }}</td>
                            <td class="px-4 py-2.5 text-right">
                                <span class="font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded text-xs">{{ $row->total_qty }}</span>
                            </td>
                            <td class="px-4 py-2.5 text-right font-black text-xs" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                                Rs {{ number_format($row->total_revenue) }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400 text-xs">No data yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Payment method breakdown --}}
    <div class="glass-card rounded-2xl overflow-hidden anim-up d2">
        <div class="px-5 py-4 border-b border-gray-50 flex items-center gap-2">
            <span class="w-1 h-4 rounded-full inline-block" style="background:linear-gradient(180deg,#6366f1,#06b6d4)"></span>
            <h2 class="font-bold text-gray-900 text-sm">Payment Method Breakdown</h2>
        </div>
        <div class="p-5 space-y-4">
            @forelse($paymentBreakdown as $method)
                @php
                    $pct = $totalOrders > 0 ? round(($method->count / $totalOrders) * 100) : 0;
                @endphp
                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <span class="text-sm font-semibold text-gray-700 capitalize">{{ str_replace('_', ' ', $method->payment_method) }}</span>
                        <div class="text-right">
                            <span class="text-xs font-bold text-indigo-600">{{ $method->count }} orders</span>
                            <span class="text-xs text-gray-400 ml-2">Rs {{ number_format($method->total) }}</span>
                        </div>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full" style="width:{{ $pct }}%; background:linear-gradient(90deg,#6366f1,#06b6d4)"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $pct }}%</p>
                </div>
            @empty
                <p class="text-center text-gray-400 text-sm py-8">No payment data yet.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Daily sales - last 30 days --}}
<div class="glass-card rounded-2xl overflow-hidden anim-up d3">
    <div class="px-5 py-4 border-b border-gray-50 flex items-center gap-2">
        <span class="w-1 h-4 rounded-full inline-block" style="background:linear-gradient(180deg,#6366f1,#06b6d4)"></span>
        <h2 class="font-bold text-gray-900 text-sm">Daily Revenue — Last 30 Days</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead style="background:linear-gradient(135deg,#f8f9ff,#f0f4ff)">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Orders</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Revenue</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-56">Bar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @php
                    $maxRevenue = $last30Days->max('daily_revenue') ?: 1;
                @endphp
                @foreach($last30Days as $day)
                    @php $barWidth = round(($day->daily_revenue / $maxRevenue) * 100); @endphp
                    <tr class="{{ $day->daily_revenue > 0 ? 'hover:bg-indigo-50/30' : '' }} transition-colors">
                        <td class="px-5 py-2.5 text-xs text-gray-600 font-medium">{{ \Carbon\Carbon::parse($day->date)->format('D, d M') }}</td>
                        <td class="px-5 py-2.5 text-right text-xs">
                            @if($day->orders_count > 0)
                                <span class="font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded text-xs">{{ $day->orders_count }}</span>
                            @else
                                <span class="text-gray-300">0</span>
                            @endif
                        </td>
                        <td class="px-5 py-2.5 text-right text-xs font-bold {{ $day->daily_revenue > 0 ? '' : 'text-gray-300' }}" @if($day->daily_revenue > 0) style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;" @endif>
                            Rs {{ number_format($day->daily_revenue) }}
                        </td>
                        <td class="px-5 py-2.5">
                            @if($barWidth > 0)
                                <div class="h-2 rounded-full" style="width:{{ $barWidth }}%; background:linear-gradient(90deg,#6366f1,#06b6d4); min-width:4px;"></div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

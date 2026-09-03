@extends('layouts.admin')
@section('title', 'Financial Reports')
@section('page-title', 'Financial Reports')
@section('content')

{{-- Summary stats --}}
<div class="grid grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
    @php
        $summaryCards = [
            ['Revenue This Month', 'Rs '.number_format($revenueMonth),  '#cffafe','#0891b2'],
            ['Revenue This Year',  'Rs '.number_format($revenueYear),   '#e0e7ff','#6366f1'],
            ['Total Orders',       number_format($totalOrders),          '#dcfce7','#16a34a'],
            ['Avg Order Value',    'Rs '.number_format($avgOrderValue),  '#fef3c7','#d97706'],
            ['Expenses This Year', 'Rs '.number_format($expensesYear),   '#fee2e2','#dc2626'],
            ['Net Profit (Year)',  'Rs '.number_format($netProfit),      $netProfit >= 0 ? '#dcfce7' : '#fee2e2', $netProfit >= 0 ? '#16a34a' : '#dc2626'],
        ];
    @endphp
    @foreach($summaryCards as $i => [$label,$value,$bg,$color])
        <div class="stat-card anim-up d{{ min($i+1,6) }}">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center mb-2" style="background:{{ $bg }}">
                <div class="w-3 h-3 rounded-full" style="background:{{ $color }}"></div>
            </div>
            <p class="text-xs text-gray-400 font-medium leading-tight">{{ $label }}</p>
            <p class="text-lg font-black text-gray-900 mt-0.5">{{ $value }}</p>
        </div>
    @endforeach
</div>

{{-- Row 1: Revenue area + Payment donut --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    <div class="glass-card rounded-2xl p-5 lg:col-span-2 anim-up d1">
        <h2 class="font-bold text-gray-900 text-sm mb-1 flex items-center gap-2">
            <span class="w-1 h-4 rounded-full inline-block" style="background:linear-gradient(180deg,#6366f1,#06b6d4)"></span>
            Daily Revenue — Last 30 Days
        </h2>
        <div id="chart-30day" style="min-height:240px"></div>
    </div>

    <div class="glass-card rounded-2xl p-5 anim-up d2">
        <h2 class="font-bold text-gray-900 text-sm mb-1 flex items-center gap-2">
            <span class="w-1 h-4 rounded-full inline-block" style="background:linear-gradient(180deg,#f59e0b,#ef4444)"></span>
            Payment Methods
        </h2>
        <div id="chart-payment" style="min-height:240px"></div>
    </div>
</div>

{{-- Row 2: Monthly revenue vs expenses + Top products horizontal bar --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <div class="glass-card rounded-2xl p-5 anim-up d3">
        <h2 class="font-bold text-gray-900 text-sm mb-1 flex items-center gap-2">
            <span class="w-1 h-4 rounded-full inline-block" style="background:linear-gradient(180deg,#10b981,#ef4444)"></span>
            Revenue vs Expenses — {{ now()->year }}
        </h2>
        <div id="chart-rev-exp" style="min-height:240px"></div>
    </div>

    <div class="glass-card rounded-2xl p-5 anim-up d4">
        <h2 class="font-bold text-gray-900 text-sm mb-1 flex items-center gap-2">
            <span class="w-1 h-4 rounded-full inline-block" style="background:linear-gradient(180deg,#f59e0b,#10b981)"></span>
            Top 10 Products by Revenue
        </h2>
        <div id="chart-top-rev" style="min-height:240px"></div>
    </div>
</div>

{{-- Daily sales table --}}
<div class="glass-card rounded-2xl overflow-hidden anim-up d5">
    <div class="px-5 py-4 border-b border-gray-50 flex items-center gap-2">
        <span class="w-1 h-4 rounded-full inline-block" style="background:linear-gradient(180deg,#6366f1,#06b6d4)"></span>
        <h2 class="font-bold text-gray-900 text-sm">Daily Breakdown — Last 30 Days</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead style="background:linear-gradient(135deg,#f8f9ff,#f0f4ff)">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase">Date</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase">Orders</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase">Revenue</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase w-48">Bar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @php $maxRevenue = $last30Days->max('daily_revenue') ?: 1; @endphp
                @foreach($last30Days as $day)
                    @php $barWidth = round(($day->daily_revenue / $maxRevenue) * 100); @endphp
                    <tr class="transition-colors hover:bg-indigo-50/30">
                        <td class="px-5 py-2.5 text-xs text-gray-600 font-medium">{{ \Carbon\Carbon::parse($day->date)->format('D, d M') }}</td>
                        <td class="px-5 py-2.5 text-right text-xs">
                            @if($day->orders_count > 0)
                                <span class="font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">{{ $day->orders_count }}</span>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-2.5 text-right text-xs font-bold {{ $day->daily_revenue > 0 ? 'text-indigo-600' : 'text-gray-300' }}">
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

<script>
document.addEventListener('DOMContentLoaded', function () {

    const base = {
        chart: { toolbar: { show: false }, fontFamily: 'Inter, sans-serif', background: 'transparent' },
        grid: { borderColor: 'rgba(99,102,241,0.08)', strokeDashArray: 4 },
        tooltip: { theme: 'light' },
        colors: ['#6366f1', '#ef4444', '#10b981', '#f59e0b', '#06b6d4', '#8b5cf6'],
        dataLabels: { enabled: false },
    };

    // ── 30-day area chart ────────────────────────────────────────────────
    new ApexCharts(document.getElementById('chart-30day'), {
        ...base,
        chart: { ...base.chart, type: 'area', height: 240 },
        series: [{ name: 'Revenue (Rs)', data: @json($chartRevenue) }],
        xaxis: { categories: @json($chartDates), tickAmount: 6, labels: { style: { fontSize: '10px' } } },
        yaxis: { labels: { formatter: v => 'Rs ' + (v >= 1000 ? (v/1000).toFixed(1)+'k' : v), style: { fontSize: '10px' } } },
        fill: { type: 'gradient', gradient: { shade: 'light', type: 'vertical', opacityFrom: 0.45, opacityTo: 0.05 } },
        stroke: { curve: 'smooth', width: 2.5 },
        markers: { size: 0, hover: { size: 4 } },
    }).render();

    // ── Payment donut ─────────────────────────────────────────────────────
    new ApexCharts(document.getElementById('chart-payment'), {
        ...base,
        chart: { ...base.chart, type: 'donut', height: 240 },
        series: @json($paymentTotals),
        labels: @json($paymentLabels),
        plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'Total', formatter: function(w){ const total = w.globals.seriesTotals.reduce((a,b)=>a+b,0); return 'Rs '+(total>=1000?(total/1000).toFixed(1)+'k':total); } } } } } },
        legend: { position: 'bottom', fontSize: '11px' },
    }).render();

    // ── Revenue vs Expenses grouped bar ───────────────────────────────────
    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    new ApexCharts(document.getElementById('chart-rev-exp'), {
        ...base,
        chart: { ...base.chart, type: 'bar', height: 240 },
        plotOptions: { bar: { borderRadius: 4, columnWidth: '70%', grouped: true } },
        series: [
            { name: 'Revenue', data: @json($monthlyRevenue) },
            { name: 'Expenses', data: @json($monthlyExpenses) },
        ],
        xaxis: { categories: months, labels: { style: { fontSize: '10px' } } },
        yaxis: { labels: { formatter: v => 'Rs ' + (v >= 1000 ? (v/1000).toFixed(1)+'k' : v), style: { fontSize: '10px' } } },
        legend: { position: 'top', fontSize: '11px' },
    }).render();

    // ── Top 10 products horizontal bar ────────────────────────────────────
    new ApexCharts(document.getElementById('chart-top-rev'), {
        ...base,
        chart: { ...base.chart, type: 'bar', height: 240 },
        plotOptions: { bar: { horizontal: true, borderRadius: 4, barHeight: '60%' } },
        series: [{ name: 'Revenue (Rs)', data: @json($topProductRevenues) }],
        xaxis: { categories: @json($topProductNames), labels: { style: { fontSize: '9px' }, formatter: v => 'Rs ' + (v >= 1000 ? (v/1000).toFixed(1)+'k' : v) } },
        yaxis: { labels: { style: { fontSize: '9px' }, maxWidth: 120 } },
        dataLabels: { enabled: false },
        fill: { type: 'gradient', gradient: { shade: 'light', type: 'horizontal', gradientToColors: ['#10b981'], stops: [0, 100] } },
    }).render();
});
</script>
@endsection

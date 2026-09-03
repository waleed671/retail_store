@extends('layouts.admin')

@section('title', 'POS Shift Register')
@section('page-title', 'Counter Register & Daily Summary')

@section('content')
<div class="space-y-6">

    {{-- Top Action Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 glass-card p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white" style="background:linear-gradient(135deg,#10b981,#059669)">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900">Counter Cash Register</h2>
                <p class="text-xs text-gray-500">Summary for {{ $summary['date'] }}</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <form method="GET" action="{{ route('admin.pos.register') }}" class="flex items-center gap-2">
                <input type="date" name="date" value="{{ $date }}" class="input-cyber text-xs py-1.5 px-3">
                <button type="submit" class="btn-primary text-xs py-2 px-3">Filter</button>
            </form>
            <a href="{{ route('admin.pos.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                Back to POS Terminal
            </a>
        </div>
    </div>

    {{-- Register Stats Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Sales</span>
                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-xs">Rs</div>
            </div>
            <p class="text-2xl font-black text-indigo-700">Rs {{ number_format($summary['total_sales']) }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $summary['total_orders'] }} orders completed</p>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Cash in Drawer</span>
                <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold">Cash</span>
            </div>
            <p class="text-2xl font-black text-emerald-600">Rs {{ number_format($summary['cash_sales']) }}</p>
            <p class="text-xs text-gray-400 mt-1">Cash collected at counter</p>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-cyan-600 uppercase tracking-wider">JazzCash / EasyPaisa</span>
                <span class="text-xs px-2 py-0.5 rounded-full bg-cyan-100 text-cyan-800 font-bold">Wallets</span>
            </div>
            <p class="text-2xl font-black text-cyan-700">Rs {{ number_format($summary['jazzcash_sales'] + $summary['easypaisa_sales']) }}</p>
            <p class="text-xs text-gray-400 mt-1">JC: Rs {{ number_format($summary['jazzcash_sales']) }} · EP: Rs {{ number_format($summary['easypaisa_sales']) }}</p>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-violet-600 uppercase tracking-wider">Card & Bank</span>
                <span class="text-xs px-2 py-0.5 rounded-full bg-violet-100 text-violet-800 font-bold">Card</span>
            </div>
            <p class="text-2xl font-black text-violet-700">Rs {{ number_format($summary['card_sales'] + $summary['bank_sales']) }}</p>
            <p class="text-xs text-gray-400 mt-1">Card POS / Raast / Bank</p>
        </div>
    </div>

    {{-- Today's POS Transactions Table --}}
    <div class="glass-card overflow-hidden">
        <div class="px-5 py-4 border-b border-indigo-50 flex items-center justify-between">
            <h3 class="font-bold text-gray-900 text-sm">Today's Counter Receipts</h3>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-600">{{ $orders->count() }} bills</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-gray-50/80 text-gray-500 uppercase tracking-wider border-b border-gray-100">
                        <th class="px-4 py-3 font-semibold">Bill #</th>
                        <th class="px-4 py-3 font-semibold">Time</th>
                        <th class="px-4 py-3 font-semibold">Cashier</th>
                        <th class="px-4 py-3 font-semibold">Customer</th>
                        <th class="px-4 py-3 font-semibold">Items</th>
                        <th class="px-4 py-3 font-semibold">Method</th>
                        <th class="px-4 py-3 font-semibold text-right">Amount</th>
                        <th class="px-4 py-3 font-semibold text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $ord)
                        <tr class="hover:bg-indigo-50/30 transition">
                            <td class="px-4 py-3 font-bold text-gray-900 font-mono">{{ $ord->order_number }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $ord->created_at->format('h:i A') }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $ord->cashier?->name ?? 'Admin' }}</td>
                            <td class="px-4 py-3">
                                <span class="font-medium text-gray-900">{{ $ord->customer_name }}</span>
                                @if($ord->customer_phone && $ord->customer_phone !== 'Counter Sale')
                                    <span class="block text-[11px] text-gray-400">{{ $ord->customer_phone }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $ord->items->sum('quantity') }} items</td>
                            <td class="px-4 py-3">
                                @php
                                    $methodColors = [
                                        'cash' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                        'jazzcash' => 'bg-red-100 text-red-800 border-red-200',
                                        'easypaisa' => 'bg-green-100 text-green-800 border-green-200',
                                        'card' => 'bg-purple-100 text-purple-800 border-purple-200',
                                        'bank_transfer' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    ];
                                @endphp
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $methodColors[$ord->payment_method] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $ord->payment_method }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-black text-gray-900">Rs {{ number_format($ord->total) }}</td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('admin.pos.receipt', $ord) }}" target="_blank"
                                   class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-semibold text-[11px] transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                    </svg>
                                    Reprint
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-400">
                                No POS sales recorded for {{ $summary['date'] }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

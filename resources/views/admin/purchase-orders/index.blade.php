@extends('layouts.admin')
@section('title', 'Purchase Orders')
@section('page-title', 'Purchase Orders')
@section('content')

<div class="flex items-center justify-between mb-6 anim-up">
    <div>
        <h2 class="text-lg font-black text-gray-900 flex items-center gap-2">
            <span class="w-1 h-5 rounded-full inline-block" style="background:linear-gradient(180deg,#6366f1,#06b6d4)"></span>
            <span style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Purchase Orders</span>
        </h2>
        <p class="text-xs text-gray-400 mt-0.5 ml-3">{{ $purchaseOrders->total() }} total</p>
    </div>
    <a href="{{ route('admin.purchase-orders.create') }}" class="btn-primary inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Purchase Order
    </a>
</div>

{{-- Filters --}}
<form method="GET" class="glass-card p-4 mb-6 anim-up d1 flex gap-3 items-end flex-wrap">
    <div class="flex-1 min-w-48">
        <label class="block text-xs font-semibold text-gray-500 mb-1">Search</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Reference or vendor..." class="input-cyber">
    </div>
    <div class="w-40">
        <label class="block text-xs font-semibold text-gray-500 mb-1">Status</label>
        <select name="status" class="input-cyber">
            <option value="">All</option>
            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="ordered" {{ request('status') === 'ordered' ? 'selected' : '' }}>Ordered</option>
            <option value="received" {{ request('status') === 'received' ? 'selected' : '' }}>Received</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
    </div>
    <button type="submit" class="btn-primary">Filter</button>
    @if(request()->hasAny(['search','status']))
        <a href="{{ route('admin.purchase-orders.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-500 bg-gray-100 rounded-lg hover:bg-gray-200">Clear</a>
    @endif
</form>

<div class="glass-card rounded-2xl overflow-hidden anim-up d2">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead style="background:linear-gradient(135deg,#f8f9ff,#f0f4ff)">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Reference</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Vendor</th>
                    <th class="text-center px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($purchaseOrders as $po)
                    @php
                        $pillMap = ['draft'=>'pill-pending','ordered'=>'pill-active','received'=>'pill-delivered','cancelled'=>'pill-cancelled'];
                    @endphp
                    <tr class="hover:bg-indigo-50/30 transition-colors">
                        <td class="px-5 py-3.5 font-mono font-semibold text-indigo-600 text-xs">{{ $po->reference_number }}</td>
                        <td class="px-5 py-3.5 font-medium text-gray-700">{{ $po->vendor->name }}</td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="{{ $pillMap[$po->status] ?? 'pill-pending' }}">{{ ucfirst($po->status) }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-right font-bold text-sm" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                            Rs {{ number_format($po->total_amount) }}
                        </td>
                        <td class="px-5 py-3.5 text-xs text-gray-500">{{ $po->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ route('admin.purchase-orders.show', $po) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 px-2 py-1 bg-indigo-50 rounded-lg">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">
                        <div class="text-3xl mb-2">&#128203;</div>No purchase orders found.
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($purchaseOrders->hasPages())
        <div class="px-5 py-4 border-t border-gray-50">{{ $purchaseOrders->links() }}</div>
    @endif
</div>
@endsection

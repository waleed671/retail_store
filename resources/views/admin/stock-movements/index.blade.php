@extends('layouts.admin')
@section('title', 'Stock Movements')
@section('page-title', 'Stock Movements')
@section('content')

<div class="flex items-center justify-between mb-6 anim-up">
    <div>
        <h2 class="text-lg font-black" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Stock Movements</h2>
        <p class="text-xs text-gray-400 mt-0.5">History of all stock transfers between warehouses</p>
    </div>
    <a href="{{ route('admin.stock-movements.create') }}" class="btn-primary text-sm">+ Transfer Stock</a>
</div>

{{-- Filters --}}
<form method="GET" class="glass-card p-4 rounded-2xl mb-5 anim-up d1 flex flex-wrap gap-3 items-end">
    <div>
        <label class="block text-xs font-semibold text-gray-500 mb-1">Type</label>
        <select name="type" class="input-cyber" style="width:160px">
            <option value="">All Types</option>
            <option value="transfer" {{ request('type')=='transfer' ? 'selected' : '' }}>Transfer</option>
            <option value="purchase_receipt" {{ request('type')=='purchase_receipt' ? 'selected' : '' }}>Purchase Receipt</option>
            <option value="sale" {{ request('type')=='sale' ? 'selected' : '' }}>Sale</option>
            <option value="adjustment" {{ request('type')=='adjustment' ? 'selected' : '' }}>Adjustment</option>
            <option value="return" {{ request('type')=='return' ? 'selected' : '' }}>Return</option>
        </select>
    </div>
    <div>
        <label class="block text-xs font-semibold text-gray-500 mb-1">Warehouse</label>
        <select name="warehouse_id" class="input-cyber" style="width:180px">
            <option value="">All Warehouses</option>
            @foreach($warehouses as $wh)
                <option value="{{ $wh->id }}" {{ request('warehouse_id') == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-semibold text-gray-500 mb-1">Product</label>
        <select name="product_id" class="input-cyber" style="width:200px">
            <option value="">All Products</option>
            @foreach($products as $p)
                <option value="{{ $p->id }}" {{ request('product_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn-primary text-sm">Filter</button>
    <a href="{{ route('admin.stock-movements.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-500 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Reset</a>
</form>

<div class="glass-card rounded-2xl overflow-hidden anim-up d2">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead style="background:linear-gradient(135deg,#f8f9ff,#f0f4ff)">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">From</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">To</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Qty</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Notes</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">By</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($movements as $mv)
                    @php
                        $typeColors = [
                            'transfer'         => 'bg-blue-50 text-blue-700',
                            'purchase_receipt' => 'bg-green-50 text-green-700',
                            'sale'             => 'bg-orange-50 text-orange-700',
                            'adjustment'       => 'bg-purple-50 text-purple-700',
                            'return'           => 'bg-yellow-50 text-yellow-700',
                        ];
                    @endphp
                    <tr class="hover:bg-indigo-50/30 transition-colors">
                        <td class="px-5 py-3.5 text-xs text-gray-500 whitespace-nowrap">{{ $mv->created_at->format('d M Y, h:i A') }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2 py-0.5 rounded-md text-xs font-semibold {{ $typeColors[$mv->type] ?? 'bg-gray-50 text-gray-600' }}">
                                {{ $mv->type_label }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 font-semibold text-gray-800">{{ $mv->product->name ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-xs text-gray-600">{{ $mv->fromWarehouse->name ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-xs text-gray-600">{{ $mv->toWarehouse->name ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-right font-bold text-indigo-700">{{ number_format($mv->quantity) }}</td>
                        <td class="px-5 py-3.5 text-xs text-gray-400 max-w-[200px] truncate">{{ $mv->notes ?: '—' }}</td>
                        <td class="px-5 py-3.5 text-xs text-gray-500">{{ $mv->creator->name ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-5 py-10 text-center text-gray-400">No stock movements found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($movements->hasPages())
        <div class="px-5 py-4 border-t border-gray-50">{{ $movements->links() }}</div>
    @endif
</div>
@endsection

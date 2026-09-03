@extends('layouts.admin')
@section('title', $warehouse->name . ' — Stock')
@section('page-title', 'Warehouse Stock')
@section('content')

<div class="flex items-center gap-3 mb-6 anim-up">
    <a href="{{ route('admin.warehouses.index') }}" class="text-gray-400 hover:text-indigo-600 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
        <h2 class="text-lg font-black" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">{{ $warehouse->name }}</h2>
        @if($warehouse->location)<p class="text-xs text-gray-400">📍 {{ $warehouse->location }}</p>@endif
    </div>
    <div class="ml-auto flex gap-2">
        <a href="{{ route('admin.stock-movements.create') }}?from={{ $warehouse->id }}" class="btn-primary text-xs">Transfer Stock Out</a>
        <a href="{{ route('admin.warehouses.edit', $warehouse) }}" class="text-xs font-semibold text-amber-600 hover:text-amber-800 px-3 py-1.5 bg-amber-50 rounded-lg">Edit</a>
    </div>
</div>

<div class="glass-card rounded-2xl overflow-hidden anim-up d1">
    <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
        <h3 class="font-bold text-gray-900 text-sm">Stock Levels</h3>
        <span class="text-xs text-gray-400">{{ $stocks->total() }} product(s)</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead style="background:linear-gradient(135deg,#f8f9ff,#f0f4ff)">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">SKU</th>
                    <th class="text-center px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Unit</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Qty</th>
                    <th class="text-center px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($stocks as $stock)
                    <tr class="hover:bg-indigo-50/30 transition-colors">
                        <td class="px-5 py-3.5 font-semibold text-gray-800">{{ $stock->product->name }}</td>
                        <td class="px-5 py-3.5 text-xs text-gray-500">{{ $stock->product->category->name ?? '—' }}</td>
                        <td class="px-5 py-3.5 font-mono text-xs text-indigo-600">{{ $stock->product->sku }}</td>
                        <td class="px-5 py-3.5 text-center text-xs text-gray-500">{{ $stock->product->unit->abbreviation ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-right font-black text-lg {{ $stock->quantity <= 5 ? 'text-red-600' : ($stock->quantity <= 20 ? 'text-amber-600' : 'text-green-700') }}">
                            {{ number_format($stock->quantity) }}
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            @if($stock->quantity == 0)
                                <span class="pill-cancelled">Out of Stock</span>
                            @elseif($stock->quantity <= 5)
                                <span class="pill-pending">Low Stock</span>
                            @else
                                <span class="pill-delivered">OK</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">No stock in this warehouse yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($stocks->hasPages())
        <div class="px-5 py-4 border-t border-gray-50">{{ $stocks->links() }}</div>
    @endif
</div>
@endsection

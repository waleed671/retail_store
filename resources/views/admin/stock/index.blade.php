@extends('layouts.admin')
@section('title', 'Stock Management')
@section('page-title', 'Stock Management')
@section('content')

<div class="flex items-center justify-between mb-6 anim-up">
    <div>
        <h2 class="text-lg font-black text-gray-900 flex items-center gap-2">
            <span class="w-1 h-5 rounded-full inline-block" style="background:linear-gradient(180deg,#6366f1,#06b6d4)"></span>
            <span style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Stock Management</span>
        </h2>
        <p class="text-xs text-gray-400 mt-0.5 ml-3">{{ $products->total() }} products</p>
    </div>
    <div class="flex items-center gap-3 text-xs font-semibold">
        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-red-500 inline-block"></span>Critical (&le;5)</span>
        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-yellow-400 inline-block"></span>Low (6–20)</span>
        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-green-500 inline-block"></span>Good (&gt;20)</span>
    </div>
</div>

{{-- Filters --}}
<form method="GET" class="glass-card p-4 mb-6 anim-up d1 flex gap-3 items-end flex-wrap">
    <div class="flex-1 min-w-48">
        <label class="block text-xs font-semibold text-gray-500 mb-1">Search Product</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or SKU..." class="input-cyber">
    </div>
    <div class="w-40">
        <label class="block text-xs font-semibold text-gray-500 mb-1">Stock Level</label>
        <select name="stock_level" class="input-cyber">
            <option value="">All</option>
            <option value="low" {{ request('stock_level') === 'low' ? 'selected' : '' }}>Critical (&le;5)</option>
            <option value="medium" {{ request('stock_level') === 'medium' ? 'selected' : '' }}>Low (6–20)</option>
            <option value="high" {{ request('stock_level') === 'high' ? 'selected' : '' }}>Good (&gt;20)</option>
        </select>
    </div>
    <button type="submit" class="btn-primary">Filter</button>
    @if(request()->hasAny(['search','stock_level']))
        <a href="{{ route('admin.stock.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-500 bg-gray-100 rounded-lg hover:bg-gray-200">Clear</a>
    @endif
</form>

<div class="glass-card rounded-2xl overflow-hidden anim-up d2">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead style="background:linear-gradient(135deg,#f8f9ff,#f0f4ff)">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">SKU</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Current Stock</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-72">Quick Adjust</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($products as $product)
                    @php
                        $stockClass = match(true) {
                            $product->stock <= 5  => 'text-red-600 bg-red-50 border-red-100',
                            $product->stock <= 20 => 'text-yellow-600 bg-yellow-50 border-yellow-100',
                            default               => 'text-green-600 bg-green-50 border-green-100',
                        };
                        $rowBg = match(true) {
                            $product->stock <= 5  => 'bg-red-50/30',
                            $product->stock <= 20 => 'bg-yellow-50/20',
                            default               => '',
                        };
                    @endphp
                    <tr class="hover:bg-indigo-50/20 transition-colors {{ $rowBg }}">
                        <td class="px-5 py-3.5">
                            <p class="font-semibold text-gray-800">{{ $product->name }}</p>
                            <p class="text-xs text-gray-400">{{ $product->category->name ?? '—' }}</p>
                        </td>
                        <td class="px-5 py-3.5 font-mono text-xs text-gray-500">{{ $product->sku }}</td>
                        <td class="px-5 py-3.5 text-right">
                            <span class="font-black text-sm px-3 py-1 rounded-lg border {{ $stockClass }}">{{ $product->stock }}</span>
                        </td>
                        <td class="px-5 py-3.5">
                            <form action="{{ route('admin.stock.adjust', $product) }}" method="POST" class="flex gap-2 items-center">
                                @csrf
                                <input type="number" name="adjustment" placeholder="±qty" class="input-cyber w-20 text-center text-sm py-1.5" required>
                                <input type="text" name="reason" placeholder="Reason..." class="input-cyber flex-1 text-sm py-1.5" required>
                                <button type="submit" class="btn-primary text-xs py-1.5 px-3 shrink-0">Adjust</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-12 text-center text-gray-400">
                        <div class="text-3xl mb-2">&#128230;</div>No products found.
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
        <div class="px-5 py-4 border-t border-gray-50">{{ $products->links() }}</div>
    @endif
</div>

<p class="text-xs text-gray-400 mt-3 anim-up d3">
    Enter a positive number to add stock, negative to subtract. e.g. +50 to restock, -5 to write off damaged goods.
</p>
@endsection

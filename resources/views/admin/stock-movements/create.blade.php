@extends('layouts.admin')
@section('title', 'Transfer Stock')
@section('page-title', 'Transfer Stock')
@section('content')

<div class="max-w-2xl">
    <div class="flex items-center gap-3 mb-6 anim-up">
        <a href="{{ route('admin.stock-movements.index') }}" class="text-gray-400 hover:text-indigo-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h2 class="text-lg font-black" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Transfer Stock Between Warehouses</h2>
    </div>

    <form action="{{ route('admin.stock-movements.store') }}" method="POST" class="glass-card p-6 rounded-2xl anim-up d1 space-y-5">
        @csrf

        {{-- Product --}}
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">Product <span class="text-red-400">*</span></label>
            <select name="product_id" id="product_id" class="input-cyber" required onchange="checkAvailable()">
                <option value="">Select a product…</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" data-unit="{{ $product->unit->abbreviation ?? '' }}"
                        {{ old('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->name }} ({{ $product->sku }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            {{-- From warehouse --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">From Warehouse <span class="text-red-400">*</span></label>
                <select name="from_warehouse_id" id="from_warehouse_id" class="input-cyber" required onchange="checkAvailable()">
                    <option value="">Select source…</option>
                    @foreach($warehouses as $wh)
                        <option value="{{ $wh->id }}" {{ (old('from_warehouse_id', $fromWarehouseId) == $wh->id) ? 'selected' : '' }}>
                            {{ $wh->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1" id="available-label">Select product + source to see available stock.</p>
            </div>

            {{-- To warehouse --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">To Warehouse <span class="text-red-400">*</span></label>
                <select name="to_warehouse_id" id="to_warehouse_id" class="input-cyber" required>
                    <option value="">Select destination…</option>
                    @foreach($warehouses as $wh)
                        <option value="{{ $wh->id }}" {{ old('to_warehouse_id') == $wh->id ? 'selected' : '' }}>
                            {{ $wh->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Quantity --}}
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">Quantity <span class="text-red-400">*</span></label>
            <input type="number" name="quantity" id="quantity" min="1" value="{{ old('quantity', 1) }}"
                   class="input-cyber" style="max-width:200px" required>
        </div>

        {{-- Notes --}}
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">Notes</label>
            <textarea name="notes" rows="2" class="input-cyber" placeholder="Optional reason or reference">{{ old('notes') }}</textarea>
        </div>

        <div class="cyber-divider"></div>
        <div class="flex gap-3">
            <button type="submit" class="btn-primary">Transfer Stock</button>
            <a href="{{ route('admin.stock-movements.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-500 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Cancel</a>
        </div>
    </form>
</div>

<script>
function checkAvailable() {
    const productId   = document.getElementById('product_id').value;
    const warehouseId = document.getElementById('from_warehouse_id').value;
    const label       = document.getElementById('available-label');

    if (!productId || !warehouseId) {
        label.textContent = 'Select product + source to see available stock.';
        label.className   = 'text-xs text-gray-400 mt-1';
        return;
    }

    fetch(`{{ route('admin.stock-movements.available') }}?warehouse_id=${warehouseId}&product_id=${productId}`)
        .then(r => r.json())
        .then(data => {
            const qty = data.available || 0;
            label.textContent = `Available in source: ${qty}`;
            label.className   = `text-xs mt-1 font-semibold ${qty === 0 ? 'text-red-500' : qty <= 5 ? 'text-amber-500' : 'text-green-600'}`;
            document.getElementById('quantity').max = qty;
        })
        .catch(() => { label.textContent = 'Could not fetch stock.'; });
}
// On page load if values pre-selected
document.addEventListener('DOMContentLoaded', checkAvailable);
</script>
@endsection

@extends('layouts.admin')
@section('title', 'New Purchase Order')
@section('page-title', 'New Purchase Order')
@section('content')

<div class="flex items-center gap-3 mb-6 anim-up">
    <a href="{{ route('admin.purchase-orders.index') }}" class="text-gray-400 hover:text-indigo-600 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <h2 class="text-lg font-black" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">New Purchase Order</h2>
</div>

<form action="{{ route('admin.purchase-orders.store') }}" method="POST" id="po-form">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- PO details --}}
        <div class="lg:col-span-1 space-y-4">
            <div class="glass-card p-5 rounded-2xl anim-up d1 space-y-4">
                <h3 class="font-bold text-gray-700 text-sm flex items-center gap-2">
                    <span class="w-1 h-4 rounded-full inline-block" style="background:linear-gradient(180deg,#6366f1,#06b6d4)"></span>
                    Order Details
                </h3>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Vendor <span class="text-red-400">*</span></label>
                    <select name="vendor_id" class="input-cyber" required>
                        <option value="">Select vendor...</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" {{ (request('vendor_id') == $vendor->id || old('vendor_id') == $vendor->id) ? 'selected' : '' }}>{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Notes</label>
                    <textarea name="notes" rows="4" class="input-cyber" placeholder="Internal notes...">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="glass-card p-5 rounded-2xl anim-up d2">
                <h3 class="font-bold text-gray-700 text-sm mb-3 flex items-center gap-2">
                    <span class="w-1 h-4 rounded-full inline-block" style="background:linear-gradient(180deg,#f59e0b,#ef4444)"></span>
                    Order Summary
                </h3>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Items:</span>
                        <span id="summary-items" class="font-bold text-gray-700">0</span>
                    </div>
                    <div class="cyber-divider my-2"></div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Total:</span>
                        <span id="summary-total" class="font-black text-lg" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Rs 0</span>
                    </div>
                </div>
                <button type="submit" class="btn-primary w-full mt-4">Create Purchase Order</button>
            </div>
        </div>

        {{-- Items --}}
        <div class="lg:col-span-2 anim-up d2">
            <div class="glass-card rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="font-bold text-gray-700 text-sm flex items-center gap-2">
                        <span class="w-1 h-4 rounded-full inline-block" style="background:linear-gradient(180deg,#6366f1,#06b6d4)"></span>
                        Products
                    </h3>
                    <button type="button" id="add-item-btn" class="btn-primary text-xs inline-flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Item
                    </button>
                </div>

                <div id="items-container" class="divide-y divide-gray-50 p-4 space-y-3">
                    {{-- Initial row --}}
                    <div class="po-item grid grid-cols-12 gap-2 items-end">
                        <div class="col-span-5">
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Product</label>
                            <select name="items[0][product_id]" class="input-cyber product-select" required>
                                <option value="">Select...</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->sku }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Qty</label>
                            <input type="number" name="items[0][quantity]" min="1" value="1" class="input-cyber qty-input" required>
                        </div>
                        <div class="col-span-3">
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Unit Cost</label>
                            <input type="number" name="items[0][unit_cost]" min="0" step="0.01" value="" class="input-cyber cost-input" placeholder="0.00" required>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Line Total</label>
                            <span class="line-total text-sm font-bold text-indigo-600 py-2 block">Rs 0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>

<script>
    const products = @json($products->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'sku' => $p->sku]));
    let rowIndex = 1;

    function buildOptions() {
        return products.map(p => `<option value="${p.id}">${p.name} (${p.sku})</option>`).join('');
    }

    function recalculate() {
        let total = 0, count = 0;
        document.querySelectorAll('.po-item').forEach(row => {
            const qty  = parseFloat(row.querySelector('.qty-input').value)  || 0;
            const cost = parseFloat(row.querySelector('.cost-input').value) || 0;
            const line = qty * cost;
            row.querySelector('.line-total').textContent = 'Rs ' + line.toLocaleString('en-PK', {minimumFractionDigits: 0});
            total += line;
            if (qty > 0 && cost > 0) count++;
        });
        document.getElementById('summary-total').textContent = 'Rs ' + total.toLocaleString('en-PK', {minimumFractionDigits: 0});
        document.getElementById('summary-items').textContent = count;
    }

    document.getElementById('add-item-btn').addEventListener('click', () => {
        const row = document.createElement('div');
        row.className = 'po-item grid grid-cols-12 gap-2 items-end';
        row.innerHTML = `
            <div class="col-span-5">
                <select name="items[${rowIndex}][product_id]" class="input-cyber product-select" required>
                    <option value="">Select...</option>
                    ${buildOptions()}
                </select>
            </div>
            <div class="col-span-2">
                <input type="number" name="items[${rowIndex}][quantity]" min="1" value="1" class="input-cyber qty-input" required>
            </div>
            <div class="col-span-3">
                <input type="number" name="items[${rowIndex}][unit_cost]" min="0" step="0.01" value="" class="input-cyber cost-input" placeholder="0.00" required>
            </div>
            <div class="col-span-1">
                <span class="line-total text-sm font-bold text-indigo-600 py-2 block">Rs 0</span>
            </div>
            <div class="col-span-1">
                <button type="button" class="remove-btn text-red-400 hover:text-red-600 py-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>`;
        row.querySelector('.remove-btn').addEventListener('click', () => { row.remove(); recalculate(); });
        row.querySelectorAll('.qty-input,.cost-input').forEach(i => i.addEventListener('input', recalculate));
        document.getElementById('items-container').appendChild(row);
        rowIndex++;
        recalculate();
    });

    document.getElementById('items-container').addEventListener('input', recalculate);
</script>
@endsection

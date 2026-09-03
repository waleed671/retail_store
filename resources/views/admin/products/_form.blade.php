@csrf
@if(isset($product)) @method('PUT') @endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1">Product Name</label>
            <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required class="w-full border rounded px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Category</label>
            <select name="category_id" required class="w-full border rounded px-3 py-2 text-sm">
                <option value="">Select category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Unit of Measure</label>
            <select name="unit_id" class="w-full border rounded px-3 py-2 text-sm">
                <option value="">— No unit —</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}" {{ old('unit_id', $product->unit_id ?? '') == $unit->id ? 'selected' : '' }}>
                        {{ $unit->name }} ({{ $unit->abbreviation }})
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">SKU</label>
            <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}" required class="w-full border rounded px-3 py-2 text-sm">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Price (Rs)</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $product->price ?? '') }}" required class="w-full border rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Discount Price (optional)</label>
                <input type="number" step="0.01" name="discount_price" value="{{ old('discount_price', $product->discount_price ?? '') }}" class="w-full border rounded px-3 py-2 text-sm">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Stock Quantity</label>
            <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" required class="w-full border rounded px-3 py-2 text-sm">
        </div>
        <div class="flex items-center gap-6">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}>
                Featured
            </label>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
                Active (visible in store)
            </label>
        </div>
    </div>

    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1">Main Image</label>
            @if(isset($product) && $product->image)
                <img src="{{ Storage::url($product->image) }}" class="w-24 h-24 object-cover rounded border mb-2">
            @endif
            <input type="file" name="image" accept="image/*" class="w-full border rounded px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Description</label>
            <textarea name="description" rows="5" class="w-full border rounded px-3 py-2 text-sm">{{ old('description', $product->description ?? '') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Specifications</label>
            <textarea name="specifications" rows="4" class="w-full border rounded px-3 py-2 text-sm">{{ old('specifications', $product->specifications ?? '') }}</textarea>
        </div>
    </div>
</div>

<div class="mt-6 flex gap-3">
    <button class="bg-brand-600 hover:bg-brand-700 text-white px-5 py-2.5 rounded-md text-sm">
        {{ isset($product) ? 'Update Product' : 'Create Product' }}
    </button>
    <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 rounded-md text-sm border hover:bg-gray-50">Cancel</a>
</div>

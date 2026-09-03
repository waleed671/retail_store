@csrf
@if(isset($category)) @method('PUT') @endif

<div class="max-w-lg space-y-4">
    <div>
        <label class="block text-sm font-medium mb-1">Category Name</label>
        <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required class="w-full border rounded px-3 py-2 text-sm">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Parent Category (optional)</label>
        <select name="parent_id" class="w-full border rounded px-3 py-2 text-sm">
            <option value="">None (top-level)</option>
            @foreach($parents as $parent)
                <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id ?? '') == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Description</label>
        <textarea name="description" rows="3" class="w-full border rounded px-3 py-2 text-sm">{{ old('description', $category->description ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Sort Order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}" class="w-full border rounded px-3 py-2 text-sm">
    </div>
    <label class="flex items-center gap-2 text-sm">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
        Active (visible in store)
    </label>

    <div class="flex gap-3 pt-2">
        <button class="bg-brand-600 hover:bg-brand-700 text-white px-5 py-2.5 rounded-md text-sm">
            {{ isset($category) ? 'Update Category' : 'Create Category' }}
        </button>
        <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 rounded-md text-sm border hover:bg-gray-50">Cancel</a>
    </div>
</div>

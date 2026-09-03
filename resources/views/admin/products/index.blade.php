@extends('layouts.admin')

@section('title', 'Products')
@section('page-title', 'Products')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <form action="{{ route('admin.products.index') }}" method="GET">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search products..." class="border rounded px-3 py-2 text-sm w-64">
        </form>
        <a href="{{ route('admin.products.create') }}" class="bg-brand-600 hover:bg-brand-700 text-white text-sm px-4 py-2 rounded-md">+ Add Product</a>
    </div>

    <div class="bg-white border rounded-lg overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-xs text-gray-500 border-b bg-gray-50">
                <tr>
                    <th class="text-left px-4 py-2">Product</th>
                    <th class="text-left px-4 py-2">Category</th>
                    <th class="text-right px-4 py-2">Price</th>
                    <th class="text-right px-4 py-2">Stock</th>
                    <th class="text-center px-4 py-2">Status</th>
                    <th class="text-right px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($products as $product)
                    <tr>
                        <td class="px-4 py-2">
                            <div class="flex items-center gap-2">
                                @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}" class="w-8 h-8 object-cover rounded border">
                                @endif
                                <span>{{ $product->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-2 text-gray-500">{{ $product->category->name }}</td>
                        <td class="px-4 py-2 text-right">Rs {{ number_format($product->discount_price ?? $product->price) }}</td>
                        <td class="px-4 py-2 text-right {{ $product->stock <= 5 ? 'text-red-600 font-semibold' : '' }}">{{ $product->stock }}</td>
                        <td class="px-4 py-2 text-center">
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                                {{ $product->is_active ? 'Active' : 'Hidden' }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-right space-x-2">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-brand-700 hover:underline">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Delete this product?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $products->links() }}</div>
@endsection

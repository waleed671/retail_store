@props(['product'])

<div class="glass-card rounded-2xl overflow-hidden card-lift flex flex-col group">
    <a href="{{ route('products.show', $product) }}" class="block relative overflow-hidden">
        @if($product->image)
            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                 class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
        @else
            <div class="w-full h-48 flex flex-col items-center justify-center gap-2 text-gray-300"
                 style="background:linear-gradient(135deg,#f0f4ff,#e8eeff)">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="text-xs font-medium">No image</span>
            </div>
        @endif

        {{-- Sale badge --}}
        @if($product->is_on_sale)
            <span class="absolute top-2 left-2 text-white text-[10px] font-black px-2 py-0.5 rounded-lg shadow"
                  style="background:linear-gradient(135deg,#ef4444,#f97316)">-{{ $product->discount_percent }}%</span>
        @endif

        {{-- Out of stock overlay --}}
        @if(!$product->in_stock)
            <div class="absolute inset-0 bg-white/75 backdrop-blur-sm flex items-center justify-center">
                <span class="text-xs font-bold text-gray-600 bg-white px-4 py-1.5 rounded-full shadow border border-gray-200">Out of Stock</span>
            </div>
        @endif

        {{-- Hover tint --}}
        <div class="absolute inset-0 bg-gradient-to-t from-indigo-900/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
    </a>

    <div class="p-3.5 flex flex-col flex-1">
        <a href="{{ route('categories.show', $product->category) }}"
           class="cyber-tag inline-block self-start mb-1.5 hover:opacity-80 transition-opacity">
            {{ $product->category->name }}
        </a>
        <a href="{{ route('products.show', $product) }}"
           class="block font-semibold text-sm text-gray-800 line-clamp-2 group-hover:text-indigo-700 transition-colors leading-snug mb-2 flex-1">
            {{ $product->name }}
        </a>
        <div class="flex items-baseline gap-2 mb-3">
            <span class="font-black text-base" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                Rs {{ number_format($product->final_price) }}
            </span>
            @if($product->is_on_sale)
                <span class="text-xs text-gray-400 line-through">Rs {{ number_format($product->price) }}</span>
            @endif
        </div>

        @if($product->in_stock)
            <form action="{{ route('cart.add', $product) }}" method="POST">
                @csrf
                <button class="btn-primary w-full py-2 text-sm flex items-center justify-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Add to Cart
                </button>
            </form>
        @else
            <button disabled class="w-full py-2 text-sm font-semibold bg-gray-100 text-gray-400 rounded-xl cursor-not-allowed">Out of Stock</button>
        @endif
    </div>
</div>

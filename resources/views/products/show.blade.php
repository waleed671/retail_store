@extends('layouts.app')
@section('title', $product->name.' — '.config('app.name'))
@section('content')
<div class="max-w-7xl mx-auto px-4 pb-14">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-xs text-gray-400 mb-7 anim-in">
        <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('categories.show', $product->category) }}" class="hover:text-indigo-600 transition-colors">{{ $product->category->name }}</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-700 font-medium truncate max-w-xs">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-14">

        {{-- Images --}}
        <div class="anim-up">
            <div class="glass-card rounded-3xl overflow-hidden group">
                @if($product->image)
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                         class="w-full max-h-[460px] object-cover group-hover:scale-105 transition-transform duration-500" id="mainImg">
                @else
                    <div class="w-full h-80 flex flex-col items-center justify-center gap-3 text-gray-300"
                         style="background:linear-gradient(135deg,#f0f4ff,#e8eeff)">
                        <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="text-sm">No image available</span>
                    </div>
                @endif
            </div>
            @if($product->images->isNotEmpty())
                <div class="flex gap-2 mt-3 overflow-x-auto pb-1">
                    @foreach($product->images as $img)
                        <img src="{{ Storage::url($img->path) }}"
                             class="w-20 h-20 object-cover rounded-xl border-2 border-transparent hover:border-indigo-400 cursor-pointer transition-all duration-200 hover:shadow-md shrink-0"
                             onclick="document.getElementById('mainImg').src='{{ Storage::url($img->path) }}'">
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Info --}}
        <div class="anim-up d1">
            <span class="cyber-tag">{{ $product->category->name }}</span>
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 mt-3 mb-1">{{ $product->name }}</h1>
            <p class="text-xs text-gray-400 mb-4">SKU: {{ $product->sku }}</p>

            {{-- Stars --}}
            <div class="flex items-center gap-2 mb-5">
                <div class="flex gap-0.5">
                    @for($i=1;$i<=5;$i++)
                        <svg class="w-4 h-4 {{ $i<=round($product->average_rating)?'text-amber-400':'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                <span class="text-xs text-gray-400">{{ $product->average_rating }} ({{ $product->reviews->count() }} reviews)</span>
            </div>

            {{-- Price --}}
            <div class="flex items-baseline gap-3 mb-3">
                <span class="text-4xl font-black" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                    Rs {{ number_format($product->final_price) }}
                </span>
                @if($product->is_on_sale)
                    <span class="text-lg text-gray-300 line-through">Rs {{ number_format($product->price) }}</span>
                    <span class="text-xs font-black text-white px-2.5 py-1 rounded-full" style="background:linear-gradient(135deg,#ef4444,#f97316)">Save {{ $product->discount_percent }}%</span>
                @endif
            </div>

            {{-- Stock indicator --}}
            <div class="flex items-center gap-2 mb-6">
                <span class="w-2 h-2 rounded-full {{ $product->in_stock?'bg-green-400':'bg-red-400' }} {{ $product->in_stock?'animate-pulse':'' }}"></span>
                <p class="text-sm font-semibold {{ $product->in_stock?'text-green-600':'text-red-500' }}">
                    {{ $product->in_stock ? $product->stock.' in stock' : 'Out of stock' }}
                </p>
            </div>

            {{-- Actions --}}
            @if($product->in_stock)
                <form action="{{ route('cart.add', $product) }}" method="POST" class="flex items-center gap-3 mb-6">
                    @csrf
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                           class="input-cyber w-20 px-3 py-2.5 text-sm text-center">
                    <button class="btn-primary flex-1 py-2.5 text-sm flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Add to Cart
                    </button>
                    @auth
                        <button formaction="{{ route('wishlist.toggle', $product) }}"
                                class="border-2 border-gray-200 hover:border-rose-300 px-3 py-2.5 rounded-xl text-gray-400 hover:text-rose-500 transition-all duration-300 hover:bg-rose-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </button>
                    @endauth
                </form>
            @else
                @auth
                    <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="mb-6">
                        @csrf
                        <button class="border-2 border-indigo-200 hover:border-indigo-400 px-5 py-2.5 rounded-xl text-sm text-indigo-600 hover:bg-indigo-50 transition-all duration-300 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            Notify me / Wishlist
                        </button>
                    </form>
                @endauth
            @endif

            {{-- Perks --}}
            <div class="rounded-2xl p-4 space-y-2.5" style="background:linear-gradient(135deg,#f0f4ff,#f0faff);border:1px solid rgba(99,102,241,.12)">
                @foreach([
                    ['✅','Cash on Delivery available'],
                    ['🚚','Delivery in 2–5 business days'],
                    ['↩️','Easy returns within 3 days'],
                ] as [$icon,$text])
                <div class="flex items-center gap-2.5 text-sm text-gray-600">
                    <span class="w-7 h-7 flex items-center justify-center rounded-lg text-sm"
                          style="background:rgba(99,102,241,.1)">{{ $icon }}</span>
                    {{ $text }}
                </div>
                @endforeach
            </div>

            @if($product->description)
                <div class="mt-6">
                    <h2 class="font-bold text-gray-900 text-sm uppercase tracking-wider mb-2">Description</h2>
                    <p class="text-sm text-gray-500 whitespace-pre-line leading-relaxed">{{ $product->description }}</p>
                </div>
            @endif
            @if($product->specifications)
                <div class="mt-4">
                    <h2 class="font-bold text-gray-900 text-sm uppercase tracking-wider mb-2">Specifications</h2>
                    <p class="text-sm text-gray-500 whitespace-pre-line leading-relaxed">{{ $product->specifications }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Reviews --}}
    <section class="mb-14 anim-up">
        <h2 class="text-xl font-black text-gray-900 sec-title mb-8">Customer Reviews</h2>

        @auth
            <div class="glass-card rounded-3xl p-6 mb-8 max-w-2xl">
                <h3 class="font-bold text-gray-800 mb-4">Write a Review</h3>
                <form action="{{ route('reviews.store', $product) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Rating</label>
                        <select name="rating" class="input-cyber px-3 py-2 text-sm" required>
                            <option value="">Select rating…</option>
                            @for($i=5;$i>=1;$i--)
                                <option value="{{ $i }}">{{ str_repeat('⭐',$i) }} {{ $i }} star{{ $i>1?'s':'' }}</option>
                            @endfor
                        </select>
                    </div>
                    <textarea name="comment" rows="3" placeholder="Share your experience…"
                              class="input-cyber w-full px-4 py-3 text-sm resize-none"></textarea>
                    <button class="btn-primary px-6 py-2.5 text-sm">Submit Review</button>
                </form>
            </div>
        @else
            <div class="rounded-2xl p-4 mb-8 text-sm max-w-sm" style="background:rgba(99,102,241,.07);border:1px solid rgba(99,102,241,.15)">
                <a href="{{ route('login') }}" class="font-bold text-indigo-700 hover:underline">Log in</a>
                <span class="text-gray-500"> to write a review.</span>
            </div>
        @endauth

        <div class="space-y-3 max-w-2xl">
            @forelse($product->reviews as $review)
                <div class="glass-card rounded-2xl p-5 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-sm"
                             style="background:linear-gradient(135deg,#6366f1,#06b6d4)">
                            {{ strtoupper(substr($review->user->name,0,1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-sm text-gray-900">{{ $review->user->name }}</p>
                            <div class="flex gap-0.5">
                                @for($i=1;$i<=5;$i++)
                                    <svg class="w-3 h-3 {{ $i<=$review->rating?'text-amber-400':'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                    @if($review->comment)
                        <p class="text-sm text-gray-500 leading-relaxed">{{ $review->comment }}</p>
                    @endif
                </div>
            @empty
                <div class="glass-card rounded-2xl p-10 text-center text-gray-400">
                    <div class="text-4xl mb-3">💬</div>
                    <p class="font-medium">No reviews yet — be the first!</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- Related --}}
    @if($related->isNotEmpty())
        <section class="anim-up">
            <h2 class="text-xl font-black text-gray-900 sec-title mb-8">You May Also Like</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach($related as $i => $item)
                    <div class="anim-up d{{ min($i+1,6) }}"><x-product-card :product="$item" /></div>
                @endforeach
            </div>
        </section>
    @endif

</div>
@endsection

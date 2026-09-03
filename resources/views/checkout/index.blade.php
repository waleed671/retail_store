@extends('layouts.app')
@section('title', 'Checkout — '.config('app.name'))
@section('content')
<div class="max-w-7xl mx-auto px-4 pb-14">

    <h1 class="text-2xl font-black text-gray-900 mb-7 anim-up flex items-center gap-2">
        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        Checkout
    </h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Form --}}
        <form action="{{ route('checkout.store') }}" method="POST" class="lg:col-span-2 space-y-5 anim-up">
            @csrf

            {{-- Delivery --}}
            <div class="glass-card rounded-2xl p-6">
                <h2 class="font-black text-gray-900 mb-5 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg flex items-center justify-center text-white text-xs font-black"
                          style="background:linear-gradient(135deg,#6366f1,#06b6d4)">1</span>
                    Delivery Details
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Full Name</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name', $user->name) }}" required
                               class="input-cyber w-full px-4 py-3 text-sm" placeholder="Your full name">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Phone Number</label>
                        <input type="text" name="customer_phone" value="{{ old('customer_phone', $user->phone) }}" required
                               class="input-cyber w-full px-4 py-3 text-sm" placeholder="03xx-xxxxxxx">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Email <span class="font-normal normal-case text-gray-400">(optional)</span></label>
                    <input type="email" name="customer_email" value="{{ old('customer_email', $user->email) }}"
                           class="input-cyber w-full px-4 py-3 text-sm" placeholder="you@example.com">
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Delivery Address</label>
                    <textarea name="shipping_address" rows="3" required
                              class="input-cyber w-full px-4 py-3 text-sm resize-none"
                              placeholder="Street, area, landmark…">{{ old('shipping_address', $user->address) }}</textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">City</label>
                    <input type="text" name="city" value="{{ old('city', $user->city) }}" required
                           class="input-cyber w-full px-4 py-3 text-sm" placeholder="Karachi, Lahore, Islamabad…">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Order Notes <span class="font-normal normal-case text-gray-400">(optional)</span></label>
                    <textarea name="notes" rows="2"
                              class="input-cyber w-full px-4 py-3 text-sm resize-none"
                              placeholder="Special instructions…">{{ old('notes') }}</textarea>
                </div>
            </div>

            {{-- Payment --}}
            <div class="glass-card rounded-2xl p-6">
                <h2 class="font-black text-gray-900 mb-5 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg flex items-center justify-center text-white text-xs font-black"
                          style="background:linear-gradient(135deg,#6366f1,#06b6d4)">2</span>
                    Payment Method
                </h2>
                <div class="space-y-3">
                    <label class="flex items-start gap-4 p-4 rounded-xl cursor-pointer border-2 transition-all duration-200 has-[:checked]:border-indigo-400 has-[:checked]:bg-indigo-50/60 border-gray-200 hover:border-indigo-200">
                        <input type="radio" name="payment_method" value="cod" checked class="mt-1 accent-indigo-600 w-4 h-4">
                        <div>
                            <span class="block font-bold text-sm text-gray-800">💵 Cash on Delivery</span>
                            <span class="block text-xs text-gray-400 mt-0.5">Pay in cash when your order arrives. Most popular option.</span>
                        </div>
                    </label>
                    <label class="flex items-start gap-4 p-4 rounded-xl cursor-pointer border-2 transition-all duration-200 has-[:checked]:border-indigo-400 has-[:checked]:bg-indigo-50/60 border-gray-200 hover:border-indigo-200">
                        <input type="radio" name="payment_method" value="bank_transfer" class="mt-1 accent-indigo-600 w-4 h-4">
                        <div>
                            <span class="block font-bold text-sm text-gray-800">🏦 Bank Transfer</span>
                            <span class="block text-xs text-gray-400 mt-0.5">
                                {{ config('app.store.bank.name') }} · {{ config('app.store.bank.account_title') }} · IBAN: {{ config('app.store.bank.iban') }}.
                                Send receipt via WhatsApp ({{ config('app.store.whatsapp') }}) after ordering.
                            </span>
                        </div>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn-primary w-full py-4 text-base flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Place Order
            </button>
        </form>

        {{-- Order Summary --}}
        <div class="glass-card rounded-2xl p-6 h-fit sticky top-24 anim-up d1">
            <h2 class="font-black text-gray-900 mb-5">Order Summary</h2>
            <div class="space-y-2 mb-4">
                @foreach($items as $item)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 pr-2 line-clamp-1 flex-1">{{ $item['product']->name }} × {{ $item['quantity'] }}</span>
                        <span class="font-semibold text-gray-800 shrink-0">Rs {{ number_format($item['subtotal']) }}</span>
                    </div>
                @endforeach
            </div>
            <div class="cyber-divider my-4"></div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal</span>
                    <span class="font-semibold text-gray-800">Rs {{ number_format($subtotal) }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Shipping</span>
                    <span class="font-semibold {{ $shippingFee > 0 ? 'text-gray-800' : 'text-green-600' }}">
                        {{ $shippingFee > 0 ? 'Rs '.number_format($shippingFee) : 'Free 🎉' }}
                    </span>
                </div>
            </div>
            <div class="cyber-divider my-4"></div>
            <div class="flex justify-between items-center">
                <span class="font-black text-gray-900">Total</span>
                <span class="text-xl font-black" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                    Rs {{ number_format($total) }}
                </span>
            </div>
        </div>
    </div>
</div>
@endsection

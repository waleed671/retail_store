<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Terminal — {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@500;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; font-family: 'Inter', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        body { background: #f5f7ff; color: #1e293b; overflow: hidden; height: 100vh; }
        .pos-grid-bg {
            background-color: #f5f7ff;
            background-image:
                linear-gradient(rgba(99,102,241,.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99,102,241,.05) 1px, transparent 1px);
            background-size: 28px 28px;
        }
        .pos-card {
            background: #ffffff;
            border: 1px solid rgba(99,102,241,0.12);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(99,102,241,0.04);
            transition: all .15s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .pos-card:hover:not(:disabled) {
            border-color: #6366f1;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(99,102,241,0.12);
        }
        .pos-card:active:not(:disabled) {
            transform: scale(0.98);
        }
        .custom-scroll::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scroll::-webkit-scrollbar-track { background: #eef2ff; }
        .custom-scroll::-webkit-scrollbar-thumb { background: rgba(99,102,241,0.3); border-radius: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: rgba(99,102,241,0.6); }
        .glow-emerald { box-shadow: 0 4px 18px rgba(16,185,129,0.3); }
        .animate-pop { animation: pop .2s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        @keyframes pop { 0% { transform: scale(0.95); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
    </style>
</head>
<body class="pos-grid-bg flex flex-col select-none">

    {{-- Light POS Cashier Header --}}
    <header class="h-14 bg-white/90 backdrop-blur border-b border-indigo-100 px-4 flex items-center justify-between shrink-0 z-30 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-white text-sm shadow-sm" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">
                ⚡
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="font-black text-sm tracking-wide text-gray-900">{{ config('app.name') }}</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-emerald-100 text-emerald-800 border border-emerald-200">
                        Counter POS
                    </span>
                </div>
                <div class="text-[11px] text-gray-500 flex items-center gap-2">
                    <span>Cashier: <strong class="text-gray-800">{{ Auth::user()->name }}</strong></span>
                    <span>•</span>
                    <span id="liveClock" class="font-mono font-semibold text-indigo-600">--:--:--</span>
                </div>
            </div>
        </div>

        {{-- Today register pill & shortcuts --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.pos.register') }}" class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-indigo-50/80 border border-indigo-100 hover:border-indigo-300 text-xs text-gray-700 hover:text-indigo-900 transition">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Today's Counter: <strong class="text-indigo-700">Rs {{ number_format($todayStats['total_sales']) }}</strong> ({{ $todayStats['orders_count'] }} sales)</span>
            </a>

            <button onclick="toggleFullscreen()" title="Toggle Fullscreen (F11)" class="p-2 rounded-xl bg-gray-50 border border-gray-200 hover:bg-indigo-50 hover:border-indigo-200 text-gray-600 hover:text-indigo-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                </svg>
            </button>

            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gray-50 border border-gray-200 hover:bg-indigo-50 hover:border-indigo-200 text-xs font-semibold text-gray-700 hover:text-indigo-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Admin Panel
            </a>
        </div>
    </header>

    {{-- Main POS Workspace: Left Catalog, Right Active Bill --}}
    <main class="flex-1 flex overflow-hidden">

        {{-- LEFT: Catalog & Search --}}
        <section class="flex-1 flex flex-col min-w-0 border-r border-indigo-100 bg-transparent">

            {{-- Barcode / Name Search Bar --}}
            <div class="p-3 bg-white/70 backdrop-blur border-b border-indigo-100 flex items-center gap-3">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-indigo-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text"
                           id="barcodeInput"
                           autocomplete="off"
                           placeholder="Scan Barcode (SKU) or type product name... [Press F2]"
                           class="w-full bg-white border-2 border-indigo-200 focus:border-indigo-600 rounded-xl pl-11 pr-24 py-2.5 text-sm font-medium text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-3 focus:ring-indigo-100 shadow-sm transition">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center gap-1 text-[11px] font-mono text-gray-400">
                        <span class="px-1.5 py-0.5 rounded bg-gray-100 border border-gray-200 text-gray-600">Enter</span>
                        <span>to add</span>
                    </div>
                </div>

                {{-- Quick held orders recall dropdown --}}
                <div class="relative">
                    <button id="heldOrdersBtn" onclick="toggleHeldMenu()" class="flex items-center gap-1.5 px-3 py-2.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 hover:bg-amber-100 text-xs font-bold transition shadow-sm">
                        <span>Parked Bills</span>
                        <span id="heldCountBadge" class="w-5 h-5 rounded-full bg-amber-500 text-white text-[11px] font-black flex items-center justify-center">0</span>
                    </button>
                    <div id="heldMenu" class="hidden absolute right-0 mt-2 w-72 bg-white border border-gray-200 rounded-xl shadow-xl p-2 z-50">
                        <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider px-2 py-1">Parked / Held Bills</div>
                        <div id="heldList" class="space-y-1 max-h-56 overflow-y-auto custom-scroll">
                            <p class="text-xs text-gray-400 p-2 text-center">No bills on hold.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Category Filter Carousel / Tabs --}}
            <div class="px-3 py-2 bg-white/40 border-b border-indigo-50 flex items-center gap-1.5 overflow-x-auto custom-scroll shrink-0">
                <button onclick="filterCategory('all')"
                        id="cat-btn-all"
                        class="cat-filter-btn px-3 py-1.5 rounded-lg text-xs font-bold whitespace-nowrap bg-indigo-600 text-white shadow-sm transition">
                    All Items ({{ count($products) }})
                </button>
                @foreach($categories as $cat)
                    <button onclick="filterCategory('{{ $cat->id }}')"
                            id="cat-btn-{{ $cat->id }}"
                            class="cat-filter-btn px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap bg-white hover:bg-indigo-50 text-gray-700 hover:text-indigo-600 border border-indigo-100 shadow-sm transition">
                        {{ $cat->name }} ({{ $cat->products_count }})
                    </button>
                @endforeach
            </div>

            {{-- Products Grid --}}
            <div class="flex-1 p-3 overflow-y-auto custom-scroll">
                <div id="productsGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                    {{-- Rendered dynamically via JavaScript --}}
                </div>
                <div id="noProductsFound" class="hidden text-center py-16 text-gray-400">
                    <p class="text-3xl mb-2">🔍</p>
                    <p class="text-sm font-semibold text-gray-700">No products found</p>
                    <p class="text-xs text-gray-400 mt-1">Try another keyword or scan SKU</p>
                </div>
            </div>
        </section>

        {{-- RIGHT: The Counter Bill / Checkout Cart --}}
        <section class="w-96 lg:w-[420px] shrink-0 bg-white flex flex-col h-full border-l border-indigo-100 shadow-xl">

            {{-- Bill Header & Customer Selection --}}
            <div class="p-3 border-b border-indigo-100 bg-indigo-50/40 shrink-0 space-y-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <h2 class="text-xs font-black uppercase tracking-wider text-gray-800">Current Bill</h2>
                    </div>
                    <button onclick="toggleCustomerMode()" id="customerModeBtn" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-800">
                        + Add Customer Info
                    </button>
                </div>

                {{-- Default: Walk-in --}}
                <div id="walkInBox" class="flex items-center justify-between px-2.5 py-1.5 rounded-lg bg-white border border-indigo-100 text-xs shadow-sm">
                    <div class="flex items-center gap-2 text-gray-700">
                        <span class="text-base">👤</span>
                        <span class="font-medium">Walk-in Customer (Cash on spot)</span>
                    </div>
                </div>

                {{-- Customer Input Form (Toggleable) --}}
                <div id="customCustomerBox" class="hidden space-y-1.5 p-2 rounded-lg bg-white border border-indigo-200 shadow-sm">
                    <div class="flex gap-2">
                        <input type="text" id="custName" placeholder="Customer Name" class="flex-1 bg-gray-50 border border-gray-300 rounded-lg px-2.5 py-1 text-xs text-gray-800 placeholder-gray-400 focus:outline-none focus:border-indigo-600 focus:bg-white">
                        <input type="text" id="custPhone" placeholder="0300-0000000" class="w-32 bg-gray-50 border border-gray-300 rounded-lg px-2.5 py-1 text-xs text-gray-800 placeholder-gray-400 focus:outline-none focus:border-indigo-600 focus:bg-white">
                    </div>
                    <select id="custSelect" onchange="onSelectExistingCustomer(this)" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-2 py-1 text-[11px] text-gray-700 focus:outline-none focus:bg-white">
                        <option value="">-- Or select registered customer --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" data-name="{{ $c->name }}" data-phone="{{ $c->phone }}">{{ $c->name }} ({{ $c->phone }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Cart Items List (Scrollable) --}}
            <div class="flex-1 overflow-y-auto custom-scroll p-2 space-y-1.5" id="cartList">
                {{-- Dynamic items rendered here --}}
            </div>

            {{-- Empty Cart State --}}
            <div id="cartEmptyState" class="flex-1 flex flex-col items-center justify-center p-6 text-center text-gray-400">
                <div class="w-14 h-14 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-2xl mb-3">
                    🛒
                </div>
                <p class="text-sm font-bold text-gray-700">Counter Bill is Empty</p>
                <p class="text-xs text-gray-400 max-w-[200px] mt-1">Scan a product barcode or click items on the left catalog</p>
            </div>

            {{-- Bottom: Totals, Discounts, Payment & Checkout --}}
            <div class="p-3 bg-gray-50/90 border-t border-indigo-100 shrink-0 space-y-2.5">

                {{-- Subtotal & Discount row --}}
                <div class="space-y-1 text-xs text-gray-700">
                    <div class="flex justify-between">
                        <span>Subtotal:</span>
                        <span class="font-mono font-bold text-gray-900" id="billSubtotal">Rs 0</span>
                    </div>

                    {{-- Quick Discount input --}}
                    <div class="flex items-center justify-between gap-2 pt-1 border-t border-gray-200">
                        <span class="text-gray-500">Discount:</span>
                        <div class="flex items-center gap-1">
                            <input type="number" id="discountInput" value="0" min="0" oninput="calculateBill()" class="w-20 bg-white border border-gray-300 rounded px-2 py-0.5 text-right font-mono text-xs text-gray-900 focus:outline-none focus:border-indigo-600">
                            <span class="text-gray-500 font-mono text-[11px]">PKR</span>
                        </div>
                    </div>
                </div>

                {{-- Grand Total Display (Prominent Light Box) --}}
                <div class="p-3 rounded-xl bg-gradient-to-br from-indigo-50 to-cyan-50/60 border border-indigo-200 flex items-center justify-between">
                    <div>
                        <div class="text-[10px] uppercase font-black tracking-widest text-indigo-700">Grand Total</div>
                        <div class="text-[11px] text-gray-500" id="totalItemsLabel">0 items</div>
                    </div>
                    <div class="text-2xl font-black font-mono text-emerald-600" id="billGrandTotal">
                        Rs 0
                    </div>
                </div>

                {{-- Payment Method Tabs --}}
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Payment Method</label>
                    <div class="grid grid-cols-4 gap-1">
                        <button type="button" onclick="setPaymentMethod('cash')" id="pay-cash" class="pay-btn px-2 py-1.5 rounded-lg text-xs font-bold bg-emerald-600 text-white shadow-sm transition">
                            💵 Cash
                        </button>
                        <button type="button" onclick="setPaymentMethod('jazzcash')" id="pay-jazzcash" class="pay-btn px-2 py-1.5 rounded-lg text-xs font-semibold bg-white text-gray-700 hover:bg-gray-100 border border-gray-200 transition">
                            📱 JazzCash
                        </button>
                        <button type="button" onclick="setPaymentMethod('easypaisa')" id="pay-easypaisa" class="pay-btn px-2 py-1.5 rounded-lg text-xs font-semibold bg-white text-gray-700 hover:bg-gray-100 border border-gray-200 transition">
                            🟢 EasyPaisa
                        </button>
                        <button type="button" onclick="setPaymentMethod('card')" id="pay-card" class="pay-btn px-2 py-1.5 rounded-lg text-xs font-semibold bg-white text-gray-700 hover:bg-gray-100 border border-gray-200 transition">
                            💳 Card
                        </button>
                    </div>
                </div>

                {{-- Cash Tendered & Change Returned Section (Shown when payment is cash) --}}
                <div id="cashTenderSection" class="p-2.5 rounded-xl bg-white border border-gray-200 space-y-1.5 shadow-sm">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-gray-600">Cash Received:</span>
                        <div class="flex items-center gap-1">
                            <span class="text-gray-400 font-mono">Rs</span>
                            <input type="number" id="cashTendered" oninput="calculateBill()" placeholder="0" class="w-28 bg-gray-50 border border-gray-300 focus:border-emerald-600 focus:bg-white rounded px-2 py-1 text-right font-mono font-bold text-gray-900 focus:outline-none text-sm">
                        </div>
                    </div>

                    {{-- Quick Note Buttons (Pakistani Notes) --}}
                    <div class="flex items-center justify-between gap-1">
                        <button type="button" onclick="quickCash(100)" class="flex-1 py-1 rounded bg-gray-100 hover:bg-indigo-50 border border-gray-200 text-[10px] font-mono text-gray-700 font-bold transition">+100</button>
                        <button type="button" onclick="quickCash(500)" class="flex-1 py-1 rounded bg-gray-100 hover:bg-indigo-50 border border-gray-200 text-[10px] font-mono text-gray-700 font-bold transition">+500</button>
                        <button type="button" onclick="quickCash(1000)" class="flex-1 py-1 rounded bg-gray-100 hover:bg-indigo-50 border border-gray-200 text-[10px] font-mono text-gray-700 font-bold transition">+1000</button>
                        <button type="button" onclick="quickCash(5000)" class="flex-1 py-1 rounded bg-gray-100 hover:bg-indigo-50 border border-gray-200 text-[10px] font-mono text-gray-700 font-bold transition">+5000</button>
                        <button type="button" onclick="exactCash()" class="flex-1 py-1 rounded bg-emerald-100 hover:bg-emerald-200 border border-emerald-300 text-[10px] text-emerald-800 font-bold transition">Exact</button>
                    </div>

                    <div class="flex items-center justify-between pt-1 border-t border-gray-100 text-xs">
                        <span class="font-semibold text-gray-600">Change Due:</span>
                        <span class="font-mono font-black text-sm text-emerald-600" id="changeAmountDisplay">Rs 0</span>
                    </div>
                </div>

                {{-- Reference Input (For JazzCash, EasyPaisa, Card) --}}
                <div id="refSection" class="hidden">
                    <input type="text" id="paymentReference" placeholder="TID / Transaction Ref / Card Auth No." class="w-full bg-white border border-gray-300 rounded-lg px-2.5 py-1.5 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:border-indigo-600">
                </div>

                {{-- Action Buttons: Clear, Hold, Complete --}}
                <div class="flex items-center gap-2 pt-1">
                    <button type="button" onclick="clearCartConfirm()" title="Clear Bill (Esc)" class="p-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>

                    <button type="button" onclick="holdCurrentBill()" title="Hold Bill (F8)" class="px-3 py-2.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 text-xs font-bold transition flex items-center gap-1">
                        <span>Hold (F8)</span>
                    </button>

                    <button type="button" id="completeBtn" onclick="submitCheckout()" class="flex-1 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-black text-sm shadow-md glow-emerald transition flex items-center justify-center gap-2 active:scale-[0.99]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>COMPLETE SALE (F4)</span>
                    </button>
                </div>
            </div>
        </section>
    </main>

    {{-- SUCCESS & THERMAL RECEIPT MODAL (LIGHT THEME) --}}
    <div id="receiptModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white border border-indigo-100 rounded-2xl w-full max-w-md p-6 shadow-2xl animate-pop text-center space-y-4">
            <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 border border-emerald-200 flex items-center justify-center mx-auto text-3xl font-black">
                ✓
            </div>

            <div>
                <h3 class="text-lg font-black text-gray-900">Sale Completed!</h3>
                <p class="text-xs text-gray-500 mt-0.5">Order Number: <strong id="modalOrderNum" class="text-indigo-600 font-mono">ORD-XXXXXX</strong></p>
            </div>

            <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-200 text-xs space-y-2 text-left">
                <div class="flex justify-between text-gray-600">
                    <span>Customer:</span>
                    <strong class="text-gray-900" id="modalCustomer">Walk-in</strong>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Total Bill:</span>
                    <strong class="text-emerald-700 font-mono font-bold" id="modalTotal">Rs 0</strong>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Cash Received:</span>
                    <span class="text-gray-900 font-mono font-semibold" id="modalPaid">Rs 0</span>
                </div>
                <div class="flex justify-between text-gray-600 font-bold border-t border-gray-200 pt-1.5">
                    <span>Change Returned:</span>
                    <strong class="text-amber-700 font-mono text-sm" id="modalChange">Rs 0</strong>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="button" onclick="printReceipt()" class="flex-1 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow transition flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Print Receipt (80mm)
                </button>
                <button type="button" onclick="closeModalAndNewSale()" class="flex-1 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs shadow transition">
                    + Next Sale (Space)
                </button>
            </div>
        </div>
    </div>

    {{-- Hidden Thermal Print Iframe --}}
    <iframe id="printIframe" class="hidden"></iframe>

    {{-- CLIENT-SIDE POS LOGIC --}}
    <script>
        const ALL_PRODUCTS = @json($products);
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // State
        let currentCategory = 'all';
        let cart = [];
        let currentPaymentMethod = 'cash';
        let heldBills = JSON.parse(localStorage.getItem('pos_held_bills') || '[]');
        let lastReceiptUrl = '';

        document.addEventListener('DOMContentLoaded', () => {
            renderProducts();
            updateHeldBadge();
            startLiveClock();
            focusBarcode();
        });

        // Live Clock
        function startLiveClock() {
            const el = document.getElementById('liveClock');
            function update() {
                const d = new Date();
                el.innerText = d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            }
            update();
            setInterval(update, 1000);
        }

        // Fullscreen Toggle
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(() => {});
            } else {
                if (document.exitFullscreen) document.exitFullscreen();
            }
        }

        // Focus Barcode search input
        function focusBarcode() {
            const input = document.getElementById('barcodeInput');
            if (input) input.focus();
        }

        // Filter by Category
        function filterCategory(catId) {
            currentCategory = catId;
            document.querySelectorAll('.cat-filter-btn').forEach(b => {
                b.className = 'cat-filter-btn px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap bg-white hover:bg-indigo-50 text-gray-700 hover:text-indigo-600 border border-indigo-100 shadow-sm transition';
            });
            const activeBtn = document.getElementById(`cat-btn-${catId}`);
            if (activeBtn) {
                activeBtn.className = 'cat-filter-btn px-3 py-1.5 rounded-lg text-xs font-bold whitespace-nowrap bg-indigo-600 text-white shadow-sm transition';
            }
            renderProducts();
        }

        // Search and Render Products
        function renderProducts() {
            const search = document.getElementById('barcodeInput').value.toLowerCase().trim();
            const grid = document.getElementById('productsGrid');
            const noFound = document.getElementById('noProductsFound');

            const filtered = ALL_PRODUCTS.filter(p => {
                const matchCat = (currentCategory === 'all' || p.category_id == currentCategory);
                const matchSearch = search === '' ||
                    p.name.toLowerCase().includes(search) ||
                    (p.sku && p.sku.toLowerCase().includes(search));
                return matchCat && matchSearch;
            });

            grid.innerHTML = '';
            if (filtered.length === 0) {
                noFound.classList.remove('hidden');
                return;
            }
            noFound.classList.add('hidden');

            filtered.forEach(p => {
                const card = document.createElement('button');
                card.type = 'button';
                card.className = `pos-card text-left p-3 flex flex-col justify-between h-36 ${p.stock <= 0 ? 'opacity-45 cursor-not-allowed bg-gray-50' : ''}`;
                card.onclick = () => addToCart(p);
                card.disabled = p.stock <= 0;

                const hasDiscount = p.discount_price && p.discount_price < p.price;

                let stockBadge = `<span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 font-mono">${p.stock} in stock</span>`;
                if (p.stock <= 0) {
                    stockBadge = `<span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 font-mono">Out of stock</span>`;
                } else if (p.stock <= 5) {
                    stockBadge = `<span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 font-mono">${p.stock} left</span>`;
                }

                card.innerHTML = `
                    <div>
                        <div class="flex items-start justify-between gap-1 mb-1">
                            <span class="text-[10px] font-mono text-gray-400 truncate max-w-[90px]">${p.sku || 'ITEM'}</span>
                            ${stockBadge}
                        </div>
                        <h4 class="text-xs font-bold text-gray-900 line-clamp-2 leading-tight">${escapeHtml(p.name)}</h4>
                        <span class="text-[10px] text-gray-400 block truncate mt-0.5">${escapeHtml(p.category_name)}</span>
                    </div>
                    <div class="pt-2 border-t border-gray-100 flex items-baseline justify-between">
                        <div>
                            <span class="font-mono font-black text-sm text-emerald-600">Rs ${Math.round(p.final_price).toLocaleString()}</span>
                            ${hasDiscount ? `<span class="text-[10px] line-through text-gray-400 ml-1">Rs ${Math.round(p.price)}</span>` : ''}
                        </div>
                        <span class="text-xs font-black text-indigo-600 bg-indigo-50 w-5 h-5 rounded-full flex items-center justify-center">+</span>
                    </div>
                `;
                grid.appendChild(card);
            });
        }

        // Barcode / Scanner listener
        document.getElementById('barcodeInput').addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                const code = e.target.value.trim().toLowerCase();
                if (!code) return;

                let matched = ALL_PRODUCTS.find(p => p.sku && p.sku.toLowerCase() === code);
                if (!matched) {
                    matched = ALL_PRODUCTS.find(p => p.name.toLowerCase() === code);
                }
                if (!matched) {
                    const candidates = ALL_PRODUCTS.filter(p =>
                        p.name.toLowerCase().includes(code) ||
                        (p.sku && p.sku.toLowerCase().includes(code))
                    );
                    if (candidates.length === 1) matched = candidates[0];
                }

                if (matched) {
                    addToCart(matched);
                    e.target.value = '';
                    renderProducts();
                }
            }
        });

        document.getElementById('barcodeInput').addEventListener('input', () => {
            renderProducts();
        });

        // Add Product to Cart
        function addToCart(product) {
            if (product.stock <= 0) return;

            const existing = cart.find(item => item.id === product.id);
            if (existing) {
                if (existing.qty >= product.stock) {
                    showToast(`Cannot add more. Only ${product.stock} available in store.`);
                    return;
                }
                existing.qty++;
            } else {
                cart.push({
                    id: product.id,
                    name: product.name,
                    sku: product.sku,
                    price: product.price,
                    final_price: product.final_price,
                    stock: product.stock,
                    qty: 1
                });
            }
            renderCart();
        }

        // Update Item Quantity
        function updateItemQty(productId, delta) {
            const item = cart.find(i => i.id === productId);
            if (!item) return;

            const newQty = item.qty + delta;
            if (newQty <= 0) {
                removeFromCart(productId);
                return;
            }
            if (newQty > item.stock) {
                showToast(`Only ${item.stock} in stock.`);
                return;
            }
            item.qty = newQty;
            renderCart();
        }

        // Direct Item Quantity input
        function setItemQty(productId, val) {
            const item = cart.find(i => i.id === productId);
            if (!item) return;

            let qty = parseInt(val, 10);
            if (isNaN(qty) || qty <= 0) qty = 1;
            if (qty > item.stock) {
                qty = item.stock;
                showToast(`Adjusted to maximum available stock (${item.stock}).`);
            }
            item.qty = qty;
            renderCart();
        }

        // Remove item from Cart
        function removeFromCart(productId) {
            cart = cart.filter(i => i.id !== productId);
            renderCart();
        }

        // Clear cart with confirmation
        function clearCartConfirm() {
            if (cart.length === 0) return;
            if (confirm('Clear current counter bill?')) {
                cart = [];
                document.getElementById('discountInput').value = 0;
                document.getElementById('cashTendered').value = '';
                renderCart();
            }
        }

        // Render Cart DOM
        function renderCart() {
            const list = document.getElementById('cartList');
            const emptyState = document.getElementById('cartEmptyState');

            list.innerHTML = '';
            if (cart.length === 0) {
                emptyState.classList.remove('hidden');
                list.classList.add('hidden');
            } else {
                emptyState.classList.add('hidden');
                list.classList.remove('hidden');

                cart.forEach(item => {
                    const lineSub = item.final_price * item.qty;
                    const row = document.createElement('div');
                    row.className = 'p-2.5 rounded-xl bg-slate-50/90 border border-indigo-50 flex items-center justify-between gap-2 shadow-xs';

                    row.innerHTML = `
                        <div class="flex-1 min-w-0">
                            <h5 class="text-xs font-bold text-gray-900 truncate leading-tight">${escapeHtml(item.name)}</h5>
                            <div class="text-[11px] text-gray-500 font-mono mt-0.5">
                                Rs ${Math.round(item.final_price).toLocaleString()}
                                <span class="text-gray-400">×</span>
                                <span class="text-indigo-600 font-bold">${item.qty}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-1 shrink-0">
                            <button type="button" onclick="updateItemQty(${item.id}, -1)" class="w-6 h-6 rounded bg-white hover:bg-indigo-50 border border-gray-200 text-gray-700 text-xs font-bold flex items-center justify-center transition">−</button>
                            <input type="number" min="1" max="${item.stock}" value="${item.qty}" onchange="setItemQty(${item.id}, this.value)" class="w-9 bg-white border border-gray-300 rounded text-center text-xs font-mono font-bold text-gray-900 py-0.5 focus:outline-none focus:border-indigo-600">
                            <button type="button" onclick="updateItemQty(${item.id}, 1)" class="w-6 h-6 rounded bg-white hover:bg-indigo-50 border border-gray-200 text-gray-700 text-xs font-bold flex items-center justify-center transition">+</button>
                        </div>

                        <div class="text-right shrink-0 min-w-[65px]">
                            <div class="font-mono font-black text-xs text-emerald-600">Rs ${Math.round(lineSub).toLocaleString()}</div>
                            <button type="button" onclick="removeFromCart(${item.id})" class="text-[10px] text-rose-500 hover:text-rose-700 font-semibold mt-0.5">Remove</button>
                        </div>
                    `;
                    list.appendChild(row);
                });
            }

            calculateBill();
        }

        // Calculate Totals, Discounts, Tender & Change
        function calculateBill() {
            let subtotal = 0;
            let totalItems = 0;

            cart.forEach(item => {
                subtotal += item.final_price * item.qty;
                totalItems += item.qty;
            });

            const discountEl = document.getElementById('discountInput');
            let discount = parseFloat(discountEl.value) || 0;
            if (discount < 0) discount = 0;
            if (discount > subtotal) discount = subtotal;

            const grandTotal = Math.max(0, subtotal - discount);

            document.getElementById('billSubtotal').innerText = `Rs ${Math.round(subtotal).toLocaleString()}`;
            document.getElementById('billGrandTotal').innerText = `Rs ${Math.round(grandTotal).toLocaleString()}`;
            document.getElementById('totalItemsLabel').innerText = `${totalItems} items`;

            const tenderInput = document.getElementById('cashTendered');
            let tendered = parseFloat(tenderInput.value) || 0;
            let change = Math.max(0, tendered - grandTotal);

            const changeDisplay = document.getElementById('changeAmountDisplay');
            changeDisplay.innerText = `Rs ${Math.round(change).toLocaleString()}`;
            if (tendered < grandTotal && tendered > 0) {
                changeDisplay.innerText = `Short by Rs ${Math.round(grandTotal - tendered).toLocaleString()}`;
                changeDisplay.className = 'font-mono font-bold text-xs text-rose-600';
            } else {
                changeDisplay.className = 'font-mono font-black text-sm text-emerald-600';
            }
        }

        // Quick Cash Buttons
        function quickCash(amount) {
            const input = document.getElementById('cashTendered');
            let current = parseFloat(input.value) || 0;
            input.value = current + amount;
            calculateBill();
        }

        function exactCash() {
            let subtotal = 0;
            cart.forEach(item => subtotal += item.final_price * item.qty);
            const discount = parseFloat(document.getElementById('discountInput').value) || 0;
            const grandTotal = Math.max(0, subtotal - discount);
            document.getElementById('cashTendered').value = grandTotal;
            calculateBill();
        }

        // Payment Method Selector
        function setPaymentMethod(method) {
            currentPaymentMethod = method;

            document.querySelectorAll('.pay-btn').forEach(btn => {
                btn.className = 'pay-btn px-2 py-1.5 rounded-lg text-xs font-semibold bg-white text-gray-700 hover:bg-gray-100 border border-gray-200 transition';
            });

            const activeBtn = document.getElementById(`pay-${method}`);
            if (activeBtn) {
                activeBtn.className = 'pay-btn px-2 py-1.5 rounded-lg text-xs font-bold bg-emerald-600 text-white shadow-sm transition';
            }

            const cashSection = document.getElementById('cashTenderSection');
            const refSection = document.getElementById('refSection');

            if (method === 'cash') {
                cashSection.classList.remove('hidden');
                refSection.classList.add('hidden');
            } else {
                cashSection.classList.add('hidden');
                refSection.classList.remove('hidden');
            }
        }

        // Toggle Customer Info Box
        function toggleCustomerMode() {
            const walkIn = document.getElementById('walkInBox');
            const custom = document.getElementById('customCustomerBox');
            const btn = document.getElementById('customerModeBtn');

            if (custom.classList.contains('hidden')) {
                custom.classList.remove('hidden');
                walkIn.classList.add('hidden');
                btn.innerText = '← Walk-in Customer';
                document.getElementById('custName').focus();
            } else {
                custom.classList.add('hidden');
                walkIn.classList.remove('hidden');
                btn.innerText = '+ Add Customer Info';
                document.getElementById('custName').value = '';
                document.getElementById('custPhone').value = '';
                document.getElementById('custSelect').value = '';
            }
        }

        function onSelectExistingCustomer(select) {
            const opt = select.options[select.selectedIndex];
            if (opt && opt.value) {
                document.getElementById('custName').value = opt.dataset.name || '';
                document.getElementById('custPhone').value = opt.dataset.phone || '';
            }
        }

        // Hold / Park Bill Feature
        function holdCurrentBill() {
            if (cart.length === 0) {
                showToast('Cannot hold an empty bill.');
                return;
            }

            const heldItem = {
                id: Date.now(),
                time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
                items: [...cart],
                custName: document.getElementById('custName').value.trim() || 'Walk-in',
                custPhone: document.getElementById('custPhone').value.trim(),
                discount: document.getElementById('discountInput').value,
            };

            heldBills.push(heldItem);
            localStorage.setItem('pos_held_bills', JSON.stringify(heldBills));

            cart = [];
            document.getElementById('discountInput').value = 0;
            document.getElementById('cashTendered').value = '';
            renderCart();
            updateHeldBadge();
            showToast('Bill parked on hold! Ready for next customer.');
        }

        function updateHeldBadge() {
            document.getElementById('heldCountBadge').innerText = heldBills.length;
            const list = document.getElementById('heldList');
            list.innerHTML = '';

            if (heldBills.length === 0) {
                list.innerHTML = `<p class="text-xs text-gray-400 p-2 text-center">No bills on hold.</p>`;
                return;
            }

            heldBills.forEach((h, idx) => {
                const totalAmt = h.items.reduce((sum, i) => sum + (i.final_price * i.qty), 0);
                const div = document.createElement('div');
                div.className = 'p-2 rounded-lg bg-gray-50 hover:bg-indigo-50/50 border border-gray-200 flex items-center justify-between gap-2 text-xs';
                div.innerHTML = `
                    <div>
                        <div class="font-bold text-gray-900">#${idx+1} · ${escapeHtml(h.custName)}</div>
                        <div class="text-[10px] text-gray-500 font-mono">${h.items.length} items · Rs ${Math.round(totalAmt).toLocaleString()} (${h.time})</div>
                    </div>
                    <div class="flex items-center gap-1">
                        <button onclick="resumeHeldBill(${h.id})" class="px-2 py-1 rounded bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-[10px]">Resume</button>
                        <button onclick="deleteHeldBill(${h.id})" class="p-1 text-rose-500 hover:text-rose-700">✕</button>
                    </div>
                `;
                list.appendChild(div);
            });
        }

        function toggleHeldMenu() {
            document.getElementById('heldMenu').classList.toggle('hidden');
        }

        function resumeHeldBill(heldId) {
            const found = heldBills.find(h => h.id === heldId);
            if (!found) return;

            if (cart.length > 0 && !confirm('Resume held bill and overwrite current cart?')) {
                return;
            }

            cart = [...found.items];
            document.getElementById('discountInput').value = found.discount || 0;
            if (found.custName && found.custName !== 'Walk-in') {
                document.getElementById('customCustomerBox').classList.remove('hidden');
                document.getElementById('walkInBox').classList.add('hidden');
                document.getElementById('custName').value = found.custName;
                document.getElementById('custPhone').value = found.custPhone || '';
            }

            heldBills = heldBills.filter(h => h.id !== heldId);
            localStorage.setItem('pos_held_bills', JSON.stringify(heldBills));

            document.getElementById('heldMenu').classList.add('hidden');
            updateHeldBadge();
            renderCart();
            showToast('Held bill restored to counter.');
        }

        function deleteHeldBill(heldId) {
            heldBills = heldBills.filter(h => h.id !== heldId);
            localStorage.setItem('pos_held_bills', JSON.stringify(heldBills));
            updateHeldBadge();
        }

        // SUBMIT CHECKOUT
        async function submitCheckout() {
            if (cart.length === 0) {
                showToast('Cart is empty. Please add items to bill.');
                focusBarcode();
                return;
            }

            let subtotal = 0;
            cart.forEach(item => subtotal += item.final_price * item.qty);
            const discount = parseFloat(document.getElementById('discountInput').value) || 0;
            const grandTotal = Math.max(0, subtotal - discount);

            let paidAmount = parseFloat(document.getElementById('cashTendered').value) || 0;
            if (currentPaymentMethod !== 'cash') {
                paidAmount = grandTotal;
            } else {
                if (paidAmount < grandTotal) {
                    showToast(`Tendered cash (Rs ${paidAmount}) is less than total bill (Rs ${grandTotal}).`);
                    document.getElementById('cashTendered').focus();
                    return;
                }
            }

            const payload = {
                items: cart.map(i => ({ id: i.id, quantity: i.qty })),
                payment_method: currentPaymentMethod,
                paid_amount: paidAmount,
                discount_amount: discount,
                customer_name: document.getElementById('custName').value.trim(),
                customer_phone: document.getElementById('custPhone').value.trim(),
                customer_id: document.getElementById('custSelect').value || null,
                payment_reference: document.getElementById('paymentReference').value.trim() || null,
            };

            const completeBtn = document.getElementById('completeBtn');
            completeBtn.disabled = true;
            completeBtn.innerHTML = `<span>Processing...</span>`;

            try {
                const response = await fetch("{{ route('admin.pos.checkout') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Checkout failed.');
                }

                // Show Success & Receipt Modal
                lastReceiptUrl = data.receipt_url;
                document.getElementById('modalOrderNum').innerText = data.order.order_number;
                document.getElementById('modalCustomer').innerText = data.order.customer_name;
                document.getElementById('modalTotal').innerText = `Rs ${Math.round(data.order.total).toLocaleString()}`;
                document.getElementById('modalPaid').innerText = `Rs ${Math.round(data.order.paid_amount).toLocaleString()}`;
                document.getElementById('modalChange').innerText = `Rs ${Math.round(data.order.change_amount).toLocaleString()}`;

                document.getElementById('receiptModal').classList.remove('hidden');

                cart.forEach(item => {
                    const localProd = ALL_PRODUCTS.find(p => p.id === item.id);
                    if (localProd) localProd.stock -= item.qty;
                });
                renderProducts();

            } catch (err) {
                showToast(err.message);
            } finally {
                completeBtn.disabled = false;
                completeBtn.innerHTML = `
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>COMPLETE SALE (F4)</span>
                `;
            }
        }

        // Print thermal receipt in background iframe
        function printReceipt() {
            if (!lastReceiptUrl) return;
            const iframe = document.getElementById('printIframe');
            iframe.src = lastReceiptUrl;
            iframe.onload = () => {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            };
        }

        // Close Modal and Reset for next sale
        function closeModalAndNewSale() {
            document.getElementById('receiptModal').classList.add('hidden');
            cart = [];
            document.getElementById('discountInput').value = 0;
            document.getElementById('cashTendered').value = '';
            document.getElementById('paymentReference').value = '';
            document.getElementById('custName').value = '';
            document.getElementById('custPhone').value = '';
            document.getElementById('custSelect').value = '';
            document.getElementById('customCustomerBox').classList.add('hidden');
            document.getElementById('walkInBox').classList.remove('hidden');
            document.getElementById('customerModeBtn').innerText = '+ Add Customer Info';
            setPaymentMethod('cash');
            renderCart();
            focusBarcode();
        }

        // Keyboard Shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.key === 'F2') {
                e.preventDefault();
                focusBarcode();
            } else if (e.key === 'F4') {
                e.preventDefault();
                submitCheckout();
            } else if (e.key === 'F8') {
                e.preventDefault();
                holdCurrentBill();
            } else if (e.key === 'Escape') {
                const modal = document.getElementById('receiptModal');
                if (!modal.classList.contains('hidden')) {
                    closeModalAndNewSale();
                } else if (cart.length > 0) {
                    clearCartConfirm();
                }
            } else if (e.key === ' ' && !document.getElementById('receiptModal').classList.contains('hidden')) {
                if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA') {
                    e.preventDefault();
                    closeModalAndNewSale();
                }
            }
        });

        // Toast notifications
        function showToast(msg) {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 left-1/2 -translate-x-1/2 bg-rose-600 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-xl z-50 animate-pop';
            toast.innerText = msg;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3500);
        }

        function escapeHtml(str) {
            return String(str || '').replace(/[&<>"']/g, m => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
            }[m]));
        }
    </script>
</body>
</html>

@extends('layouts.admin')
@section('title', $purchaseOrder->reference_number)
@section('page-title', 'Purchase Order')
@section('content')

<div class="flex items-center gap-3 mb-6 anim-up">
    <a href="{{ route('admin.purchase-orders.index') }}" class="text-gray-400 hover:text-indigo-600 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <h2 class="text-lg font-black font-mono" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">{{ $purchaseOrder->reference_number }}</h2>

    @if(!in_array($purchaseOrder->status, ['received','cancelled']))
        <form action="{{ route('admin.purchase-orders.receive', $purchaseOrder) }}" method="POST" class="ml-auto" onsubmit="return confirm('Mark this PO as received? This will update product stock.')">
            @csrf
            <button type="submit" class="btn-primary inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Mark as Received
            </button>
        </form>
    @endif
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Info --}}
    <div class="glass-card p-5 rounded-2xl anim-up d1 space-y-3">
        <h3 class="font-bold text-gray-700 text-sm flex items-center gap-2">
            <span class="w-1 h-4 rounded-full inline-block" style="background:linear-gradient(180deg,#6366f1,#06b6d4)"></span>
            Order Info
        </h3>
        <div class="space-y-2 text-sm">
            <div><span class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Vendor:</span>
                <a href="{{ route('admin.vendors.show', $purchaseOrder->vendor) }}" class="text-indigo-600 font-semibold block">{{ $purchaseOrder->vendor->name }}</a>
            </div>
            <div><span class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Status:</span>
                @php $pillMap = ['draft'=>'pill-pending','ordered'=>'pill-active','received'=>'pill-delivered','cancelled'=>'pill-cancelled']; @endphp
                <span class="{{ $pillMap[$purchaseOrder->status] ?? 'pill-pending' }} mt-1 inline-block">{{ ucfirst($purchaseOrder->status) }}</span>
            </div>
            <div><span class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Created:</span>
                <p class="text-gray-700">{{ $purchaseOrder->created_at->format('d M Y, g:i A') }}</p>
            </div>
            @if($purchaseOrder->received_at)
            <div><span class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Received:</span>
                <p class="text-gray-700">{{ $purchaseOrder->received_at->format('d M Y, g:i A') }}</p>
            </div>
            @endif
            @if($purchaseOrder->notes)
            <div><span class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Notes:</span>
                <p class="text-gray-500 text-xs mt-0.5">{{ $purchaseOrder->notes }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Total --}}
    <div class="stat-card anim-up d2 flex flex-col items-center justify-center text-center">
        <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-3" style="background:#e0e7ff">
            <svg class="w-6 h-6" fill="none" stroke="#6366f1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
        </div>
        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Order Total</p>
        <p class="text-2xl font-black mt-1" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Rs {{ number_format($purchaseOrder->total_amount) }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $purchaseOrder->items->count() }} item(s)</p>
    </div>

    {{-- Vendor quick info --}}
    <div class="glass-card p-5 rounded-2xl anim-up d3 space-y-3">
        <h3 class="font-bold text-gray-700 text-sm flex items-center gap-2">
            <span class="w-1 h-4 rounded-full inline-block" style="background:linear-gradient(180deg,#6366f1,#06b6d4)"></span>
            Vendor Contact
        </h3>
        <div class="space-y-1 text-sm">
            @if($purchaseOrder->vendor->contact_person)
                <p class="text-gray-700 font-semibold">{{ $purchaseOrder->vendor->contact_person }}</p>
            @endif
            @if($purchaseOrder->vendor->phone)
                <p class="text-gray-500">{{ $purchaseOrder->vendor->phone }}</p>
            @endif
            @if($purchaseOrder->vendor->email)
                <p class="text-gray-500 text-xs">{{ $purchaseOrder->vendor->email }}</p>
            @endif
            @if($purchaseOrder->vendor->city)
                <p class="text-gray-400 text-xs">{{ $purchaseOrder->vendor->city }}</p>
            @endif
        </div>
    </div>
</div>

{{-- Items table --}}
<div class="glass-card rounded-2xl overflow-hidden anim-up d4">
    <div class="px-5 py-4 border-b border-gray-50">
        <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
            <span class="w-1 h-4 rounded-full inline-block" style="background:linear-gradient(180deg,#6366f1,#06b6d4)"></span>
            Order Items
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead style="background:linear-gradient(135deg,#f8f9ff,#f0f4ff)">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Qty</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Unit Cost</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($purchaseOrder->items as $item)
                    <tr class="hover:bg-indigo-50/30 transition-colors">
                        <td class="px-5 py-3.5 font-medium text-gray-800">
                            {{ $item->product->name ?? 'Deleted product' }}
                            @if($item->product)
                                <p class="text-xs text-gray-400 font-mono">{{ $item->product->sku }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <span class="font-bold text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-lg text-xs">{{ $item->quantity }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-right text-gray-600">Rs {{ number_format($item->unit_cost) }}</td>
                        <td class="px-5 py-3.5 text-right font-black text-sm" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                            Rs {{ number_format($item->total_cost) }}
                        </td>
                    </tr>
                @endforeach
                <tr style="background:linear-gradient(135deg,#f8f9ff,#f0f4ff)">
                    <td colspan="3" class="px-5 py-3.5 text-right font-bold text-gray-700">Grand Total</td>
                    <td class="px-5 py-3.5 text-right font-black text-base" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                        Rs {{ number_format($purchaseOrder->total_amount) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

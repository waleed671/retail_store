@extends('layouts.admin')
@section('title', $vendor->name)
@section('page-title', 'Vendor Detail')
@section('content')

<div class="flex items-center gap-3 mb-6 anim-up">
    <a href="{{ route('admin.vendors.index') }}" class="text-gray-400 hover:text-indigo-600 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <h2 class="text-lg font-black" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">{{ $vendor->name }}</h2>
    <a href="{{ route('admin.vendors.edit', $vendor) }}" class="ml-auto text-xs font-semibold text-amber-600 hover:text-amber-800 px-3 py-1.5 bg-amber-50 rounded-lg transition-colors">Edit</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Vendor info --}}
    <div class="glass-card p-5 rounded-2xl anim-up d1 space-y-3">
        <h3 class="font-bold text-gray-700 text-sm flex items-center gap-2">
            <span class="w-1 h-4 rounded-full inline-block" style="background:linear-gradient(180deg,#6366f1,#06b6d4)"></span>
            Vendor Info
        </h3>
        <div class="space-y-2 text-sm">
            @if($vendor->contact_person)
            <div><span class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Contact:</span><p class="text-gray-700 font-medium">{{ $vendor->contact_person }}</p></div>
            @endif
            @if($vendor->phone)
            <div><span class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Phone:</span><p class="text-gray-700">{{ $vendor->phone }}</p></div>
            @endif
            @if($vendor->email)
            <div><span class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Email:</span><p class="text-gray-700">{{ $vendor->email }}</p></div>
            @endif
            @if($vendor->city)
            <div><span class="text-xs text-gray-400 font-semibold uppercase tracking-wide">City:</span><p class="text-gray-700">{{ $vendor->city }}</p></div>
            @endif
            @if($vendor->address)
            <div><span class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Address:</span><p class="text-gray-700 text-xs">{{ $vendor->address }}</p></div>
            @endif
        </div>
    </div>

    {{-- Balance stat --}}
    <div class="stat-card anim-up d2 flex flex-col items-center justify-center text-center">
        <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-3" style="background:#e0e7ff">
            <svg class="w-6 h-6" fill="none" stroke="#6366f1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 1v8m0 0v1"/></svg>
        </div>
        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Current Balance</p>
        <p class="text-2xl font-black mt-1" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Rs {{ number_format($vendor->balance) }}</p>
    </div>

    {{-- Status + notes --}}
    <div class="glass-card p-5 rounded-2xl anim-up d3 space-y-3">
        <h3 class="font-bold text-gray-700 text-sm flex items-center gap-2">
            <span class="w-1 h-4 rounded-full inline-block" style="background:linear-gradient(180deg,#6366f1,#06b6d4)"></span>
            Status &amp; Notes
        </h3>
        <div>
            @if($vendor->is_active)
                <span class="pill-delivered">Active</span>
            @else
                <span class="pill-cancelled">Inactive</span>
            @endif
        </div>
        @if($vendor->notes)
            <p class="text-xs text-gray-500 leading-relaxed">{{ $vendor->notes }}</p>
        @endif
        <p class="text-xs text-gray-400">Member since {{ $vendor->created_at->format('d M Y') }}</p>
    </div>
</div>

{{-- Purchase history --}}
<div class="glass-card rounded-2xl overflow-hidden anim-up d4">
    <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
        <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
            <span class="w-1 h-4 rounded-full inline-block" style="background:linear-gradient(180deg,#6366f1,#06b6d4)"></span>
            Purchase Orders
        </h3>
        <a href="{{ route('admin.purchase-orders.create') }}?vendor_id={{ $vendor->id }}" class="btn-primary text-xs">New PO</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead style="background:linear-gradient(135deg,#f8f9ff,#f0f4ff)">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Reference</th>
                    <th class="text-center px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($purchaseOrders as $po)
                    <tr class="hover:bg-indigo-50/30 transition-colors">
                        <td class="px-5 py-3.5 font-mono font-semibold text-indigo-600 text-xs">{{ $po->reference_number }}</td>
                        <td class="px-5 py-3.5 text-center">
                            @php
                                $pillMap = ['draft'=>'pill-pending','ordered'=>'pill-active','received'=>'pill-delivered','cancelled'=>'pill-cancelled'];
                            @endphp
                            <span class="{{ $pillMap[$po->status] ?? 'pill-pending' }}">{{ ucfirst($po->status) }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-right font-bold text-sm" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                            Rs {{ number_format($po->total_amount) }}
                        </td>
                        <td class="px-5 py-3.5 text-xs text-gray-500">{{ $po->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ route('admin.purchase-orders.show', $po) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 px-2 py-1 bg-indigo-50 rounded-lg">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-8 text-center text-gray-400 text-sm">No purchase orders yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($purchaseOrders->hasPages())
        <div class="px-5 py-4 border-t border-gray-50">{{ $purchaseOrders->links() }}</div>
    @endif
</div>
@endsection

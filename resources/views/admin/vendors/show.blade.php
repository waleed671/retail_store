@extends('layouts.admin')
@section('title', $vendor->name)
@section('page-title', 'Vendor Detail')
@section('content')

@php
    $totalPurchased = $vendor->totalPurchased();
    $totalPaid      = $vendor->totalPaid();
    $currentBalance = $vendor->currentBalance();
@endphp

<div class="flex items-center gap-3 mb-6 anim-up">
    <a href="{{ route('admin.vendors.index') }}" class="text-gray-400 hover:text-indigo-600 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <h2 class="text-lg font-black" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">{{ $vendor->name }}</h2>
    <a href="{{ route('admin.vendors.edit', $vendor) }}" class="ml-auto text-xs font-semibold text-amber-600 hover:text-amber-800 px-3 py-1.5 bg-amber-50 rounded-lg transition-colors">Edit</a>
</div>

{{-- Top stat cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="stat-card anim-up d1 text-center">
        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wide">Opening Balance</p>
        <p class="text-xl font-black mt-1 text-gray-700">Rs {{ number_format($vendor->opening_balance, 2) }}</p>
    </div>
    <div class="stat-card anim-up d2 text-center">
        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wide">Total Purchased</p>
        <p class="text-xl font-black mt-1 text-orange-600">Rs {{ number_format($totalPurchased, 2) }}</p>
    </div>
    <div class="stat-card anim-up d3 text-center">
        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wide">Total Paid</p>
        <p class="text-xl font-black mt-1 text-green-600">Rs {{ number_format($totalPaid, 2) }}</p>
    </div>
    <div class="stat-card anim-up d4 text-center">
        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wide">Balance Due</p>
        <p class="text-xl font-black mt-1 {{ $currentBalance > 0 ? 'text-red-600' : 'text-green-600' }}">
            Rs {{ number_format($currentBalance, 2) }}
        </p>
        @if($currentBalance <= 0)
            <span class="text-[10px] text-green-500 font-semibold">Fully Paid ✓</span>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Vendor info --}}
    <div class="glass-card p-5 rounded-2xl anim-up space-y-3">
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
        <div class="pt-2 border-t border-gray-100">
            @if($vendor->is_active)
                <span class="pill-delivered">Active</span>
            @else
                <span class="pill-cancelled">Inactive</span>
            @endif
            <p class="text-xs text-gray-400 mt-1">Since {{ $vendor->created_at->format('d M Y') }}</p>
        </div>
    </div>

    {{-- Record Payment form --}}
    <div class="lg:col-span-2 glass-card p-5 rounded-2xl anim-up d1">
        <h3 class="font-bold text-gray-700 text-sm mb-4 flex items-center gap-2">
            <span class="w-1 h-4 rounded-full inline-block" style="background:linear-gradient(180deg,#10b981,#059669)"></span>
            Record Payment
        </h3>
        <form action="{{ route('admin.vendors.payments.store', $vendor) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Amount (Rs) <span class="text-red-400">*</span></label>
                    <input type="number" step="0.01" min="0.01" name="amount" value="{{ old('amount') }}" class="input-cyber" placeholder="0.00" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Payment Date <span class="text-red-400">*</span></label>
                    <input type="date" name="paid_at" value="{{ old('paid_at', now()->toDateString()) }}" class="input-cyber" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Payment Method <span class="text-red-400">*</span></label>
                    <select name="payment_method" class="input-cyber" required>
                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Reference / Cheque No.</label>
                    <input type="text" name="reference" value="{{ old('reference') }}" class="input-cyber" placeholder="Optional">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Notes</label>
                    <input type="text" name="notes" value="{{ old('notes') }}" class="input-cyber" placeholder="Optional notes">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn-primary text-sm">Record Payment</button>
            </div>
        </form>
    </div>
</div>

{{-- Payment History --}}
<div class="glass-card rounded-2xl overflow-hidden anim-up d2 mb-6">
    <div class="px-5 py-4 border-b border-gray-50">
        <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
            <span class="w-1 h-4 rounded-full inline-block" style="background:linear-gradient(180deg,#10b981,#059669)"></span>
            Payment History
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead style="background:linear-gradient(135deg,#f8f9ff,#f0f4ff)">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Method</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Reference</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Recorded By</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($payments as $payment)
                    <tr class="hover:bg-green-50/30 transition-colors">
                        <td class="px-5 py-3.5 text-xs text-gray-600">{{ $payment->paid_at->format('d M Y') }}</td>
                        <td class="px-5 py-3.5 text-right font-bold text-green-700">Rs {{ number_format($payment->amount, 2) }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-xs font-semibold rounded-md capitalize">{{ str_replace('_', ' ', $payment->payment_method) }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-gray-500">{{ $payment->reference ?: '—' }}</td>
                        <td class="px-5 py-3.5 text-xs text-gray-500">{{ $payment->recorder->name ?? 'System' }}</td>
                        <td class="px-5 py-3.5 text-right">
                            <form action="{{ route('admin.vendors.payments.destroy', [$vendor, $payment]) }}" method="POST"
                                  onsubmit="return confirm('Delete this payment record?')">
                                @csrf @method('DELETE')
                                <button class="text-xs font-semibold text-red-500 hover:text-red-700 px-2 py-1 bg-red-50 rounded-lg transition-colors">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400 text-sm">No payments recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())
        <div class="px-5 py-4 border-t border-gray-50">{{ $payments->links() }}</div>
    @endif
</div>

{{-- Purchase Orders --}}
<div class="glass-card rounded-2xl overflow-hidden anim-up d3">
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
                            @php $pillMap = ['draft'=>'pill-pending','ordered'=>'pill-active','received'=>'pill-delivered','cancelled'=>'pill-cancelled']; @endphp
                            <span class="{{ $pillMap[$po->status] ?? 'pill-pending' }}">{{ ucfirst($po->status) }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-right font-bold text-sm text-indigo-700">Rs {{ number_format($po->total_amount) }}</td>
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

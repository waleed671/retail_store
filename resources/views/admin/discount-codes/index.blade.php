@extends('layouts.admin')
@section('title', 'Discount Codes')
@section('page-title', 'Discount Codes')
@section('content')

<div class="flex items-center justify-between mb-6 anim-up">
    <div>
        <h2 class="text-lg font-black text-gray-900 flex items-center gap-2">
            <span class="w-1 h-5 rounded-full inline-block" style="background:linear-gradient(180deg,#6366f1,#06b6d4)"></span>
            <span style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Discount Codes</span>
        </h2>
        <p class="text-xs text-gray-400 mt-0.5 ml-3">{{ $discountCodes->total() }} codes</p>
    </div>
    <a href="{{ route('admin.discount-codes.create') }}" class="btn-primary inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Create Code
    </a>
</div>

<form method="GET" class="glass-card p-4 mb-6 anim-up d1 flex gap-3 items-end flex-wrap">
    <div class="flex-1 min-w-48">
        <label class="block text-xs font-semibold text-gray-500 mb-1">Search Code</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="SAVE10..." class="input-cyber">
    </div>
    <div class="w-36">
        <label class="block text-xs font-semibold text-gray-500 mb-1">Type</label>
        <select name="type" class="input-cyber">
            <option value="">All</option>
            <option value="percent" {{ request('type') === 'percent' ? 'selected' : '' }}>Percent</option>
            <option value="fixed" {{ request('type') === 'fixed' ? 'selected' : '' }}>Fixed</option>
        </select>
    </div>
    <button type="submit" class="btn-primary">Filter</button>
    @if(request()->hasAny(['search','type']))
        <a href="{{ route('admin.discount-codes.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-500 bg-gray-100 rounded-lg hover:bg-gray-200">Clear</a>
    @endif
</form>

<div class="glass-card rounded-2xl overflow-hidden anim-up d2">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead style="background:linear-gradient(135deg,#f8f9ff,#f0f4ff)">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Code</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Discount</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Usage</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Expires</th>
                    <th class="text-center px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($discountCodes as $code)
                    @php
                        $expired  = $code->expires_at && $code->expires_at->isPast();
                        $exhausted = $code->max_uses && $code->used_count >= $code->max_uses;
                        $isValid  = $code->is_active && !$expired && !$exhausted;
                    @endphp
                    <tr class="hover:bg-indigo-50/30 transition-colors">
                        <td class="px-5 py-3.5">
                            <span class="font-mono font-black text-indigo-600 bg-indigo-50 px-3 py-1 rounded-lg text-xs tracking-widest">{{ $code->code }}</span>
                        </td>
                        <td class="px-5 py-3.5 font-semibold text-gray-700">
                            @if($code->type === 'percent')
                                {{ $code->value }}% off
                            @else
                                Rs {{ number_format($code->value) }} off
                            @endif
                            @if($code->min_order_amount)
                                <p class="text-xs text-gray-400">Min order: Rs {{ number_format($code->min_order_amount) }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right text-sm">
                            <span class="font-bold text-gray-700">{{ $code->used_count }}</span>
                            @if($code->max_uses)
                                <span class="text-gray-400"> / {{ $code->max_uses }}</span>
                            @else
                                <span class="text-gray-400"> / &infin;</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-xs text-gray-500">
                            @if($code->expires_at)
                                <span class="{{ $expired ? 'text-red-500 font-semibold' : '' }}">{{ $code->expires_at->format('d M Y') }}</span>
                            @else
                                <span class="text-gray-400">Never</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            @if($isValid)
                                <span class="pill-delivered">Active</span>
                            @elseif(!$code->is_active)
                                <span class="pill-cancelled">Disabled</span>
                            @elseif($expired)
                                <span class="pill-pending">Expired</span>
                            @else
                                <span class="pill-cancelled">Exhausted</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.discount-codes.edit', $code) }}" class="text-xs font-semibold text-amber-600 hover:text-amber-800 px-2 py-1 bg-amber-50 rounded-lg">Edit</a>
                                <form action="{{ route('admin.discount-codes.destroy', $code) }}" method="POST" onsubmit="return confirm('Delete this discount code?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs font-semibold text-red-500 hover:text-red-700 px-2 py-1 bg-red-50 rounded-lg">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">
                        <div class="text-3xl mb-2">&#127991;</div>No discount codes yet.
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($discountCodes->hasPages())
        <div class="px-5 py-4 border-t border-gray-50">{{ $discountCodes->links() }}</div>
    @endif
</div>
@endsection

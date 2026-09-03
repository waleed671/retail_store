@extends('layouts.admin')
@section('title', 'Create Discount Code')
@section('page-title', 'Create Discount Code')
@section('content')

<div class="max-w-xl">
    <div class="flex items-center gap-3 mb-6 anim-up">
        <a href="{{ route('admin.discount-codes.index') }}" class="text-gray-400 hover:text-indigo-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h2 class="text-lg font-black" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">New Discount Code</h2>
    </div>

    <form action="{{ route('admin.discount-codes.store') }}" method="POST" class="glass-card p-6 rounded-2xl anim-up d1 space-y-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Code <span class="text-red-400">*</span></label>
                <input type="text" name="code" value="{{ old('code') }}" class="input-cyber uppercase" placeholder="e.g. SAVE10" required style="text-transform:uppercase">
                <p class="text-xs text-gray-400 mt-1">Will be auto-uppercased.</p>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Type <span class="text-red-400">*</span></label>
                <select name="type" class="input-cyber" required>
                    <option value="">Select...</option>
                    <option value="percent" {{ old('type') === 'percent' ? 'selected' : '' }}>Percent (%)</option>
                    <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Fixed Amount (Rs)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Value <span class="text-red-400">*</span></label>
                <input type="number" name="value" value="{{ old('value') }}" min="0" step="0.01" class="input-cyber" placeholder="e.g. 10" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Min Order Amount</label>
                <input type="number" name="min_order_amount" value="{{ old('min_order_amount') }}" min="0" step="0.01" class="input-cyber" placeholder="Optional">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Max Uses</label>
                <input type="number" name="max_uses" value="{{ old('max_uses') }}" min="1" class="input-cyber" placeholder="Unlimited">
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Expires At</label>
                <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}" class="input-cyber">
            </div>
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} class="w-4 h-4 accent-indigo-600">
                <label for="is_active" class="text-sm font-semibold text-gray-700">Active</label>
            </div>
        </div>

        <div class="cyber-divider"></div>
        <div class="flex gap-3">
            <button type="submit" class="btn-primary">Create Code</button>
            <a href="{{ route('admin.discount-codes.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-500 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</a>
        </div>
    </form>
</div>
@endsection

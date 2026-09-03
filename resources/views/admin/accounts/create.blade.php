@extends('layouts.admin')
@section('title', 'Add Account')
@section('page-title', 'Add Account')
@section('content')

<div class="max-w-lg">
    <div class="flex items-center gap-3 mb-6 anim-up">
        <a href="{{ route('admin.accounts.index') }}" class="text-gray-400 hover:text-indigo-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h2 class="text-lg font-black" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Add Account</h2>
    </div>

    <form action="{{ route('admin.accounts.store') }}" method="POST" class="glass-card p-6 rounded-2xl anim-up d1 space-y-5">
        @csrf
        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Account Code <span class="text-red-400">*</span></label>
                <input type="text" name="code" value="{{ old('code') }}" class="input-cyber" placeholder="e.g. 1005" required>
                <p class="text-[10px] text-gray-400 mt-1">Must be unique. Use 1xxx=Asset, 2xxx=Liability, etc.</p>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Account Type <span class="text-red-400">*</span></label>
                <select name="type" class="input-cyber" required>
                    <option value="">Select type…</option>
                    <option value="asset"     {{ old('type')=='asset'     ? 'selected' : '' }}>Asset</option>
                    <option value="liability" {{ old('type')=='liability' ? 'selected' : '' }}>Liability</option>
                    <option value="equity"    {{ old('type')=='equity'    ? 'selected' : '' }}>Equity</option>
                    <option value="income"    {{ old('type')=='income'    ? 'selected' : '' }}>Income</option>
                    <option value="expense"   {{ old('type')=='expense'   ? 'selected' : '' }}>Expense</option>
                </select>
            </div>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">Account Name <span class="text-red-400">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" class="input-cyber" placeholder="e.g. Petty Cash" required>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">Description</label>
            <textarea name="description" rows="2" class="input-cyber" placeholder="Optional description">{{ old('description') }}</textarea>
        </div>
        <div class="cyber-divider"></div>
        <div class="flex gap-3">
            <button type="submit" class="btn-primary">Create Account</button>
            <a href="{{ route('admin.accounts.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-500 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Cancel</a>
        </div>
    </form>
</div>
@endsection

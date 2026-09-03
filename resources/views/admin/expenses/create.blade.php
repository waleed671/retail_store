@extends('layouts.admin')
@section('title', 'Add Expense')
@section('page-title', 'Add Expense')
@section('content')

<div class="max-w-xl">
    <div class="flex items-center gap-3 mb-6 anim-up">
        <a href="{{ route('admin.expenses.index') }}" class="text-gray-400 hover:text-indigo-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h2 class="text-lg font-black" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Record Expense</h2>
    </div>

    <form action="{{ route('admin.expenses.store') }}" method="POST" class="glass-card p-6 rounded-2xl anim-up d1 space-y-5">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Title <span class="text-red-400">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" class="input-cyber" placeholder="e.g. Monthly rent" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Amount (Rs) <span class="text-red-400">*</span></label>
                <input type="number" name="amount" value="{{ old('amount') }}" min="0" step="0.01" class="input-cyber" placeholder="0.00" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Category <span class="text-red-400">*</span></label>
                <select name="category" class="input-cyber" required>
                    <option value="">Select...</option>
                    @foreach(\App\Models\Expense::$categories as $key => $label)
                        <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Date <span class="text-red-400">*</span></label>
                <input type="date" name="expense_date" value="{{ old('expense_date', now()->format('Y-m-d')) }}" class="input-cyber" required>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Description</label>
                <textarea name="description" rows="3" class="input-cyber" placeholder="Optional details...">{{ old('description') }}</textarea>
            </div>
        </div>
        <div class="cyber-divider"></div>
        <div class="flex gap-3">
            <button type="submit" class="btn-primary">Save Expense</button>
            <a href="{{ route('admin.expenses.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-500 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</a>
        </div>
    </form>
</div>
@endsection

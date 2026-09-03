@extends('layouts.admin')
@section('title', 'Expenses')
@section('page-title', 'Expenses')
@section('content')

<div class="flex items-center justify-between mb-6 anim-up">
    <div>
        <h2 class="text-lg font-black text-gray-900 flex items-center gap-2">
            <span class="w-1 h-5 rounded-full inline-block" style="background:linear-gradient(180deg,#6366f1,#06b6d4)"></span>
            <span style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Expenses</span>
        </h2>
        <p class="text-xs text-gray-400 mt-0.5 ml-3">{{ $expenses->total() }} records &mdash; Total: <strong class="text-gray-700">Rs {{ number_format($total) }}</strong></p>
    </div>
    <a href="{{ route('admin.expenses.create') }}" class="btn-primary inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Expense
    </a>
</div>

<form method="GET" class="glass-card p-4 mb-6 anim-up d1 flex gap-3 items-end flex-wrap">
    <div class="w-48">
        <label class="block text-xs font-semibold text-gray-500 mb-1">Category</label>
        <select name="category" class="input-cyber">
            <option value="">All Categories</option>
            @foreach(\App\Models\Expense::$categories as $key => $label)
                <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="w-44">
        <label class="block text-xs font-semibold text-gray-500 mb-1">Month</label>
        <input type="month" name="month" value="{{ request('month') }}" class="input-cyber">
    </div>
    <button type="submit" class="btn-primary">Filter</button>
    @if(request()->hasAny(['category','month']))
        <a href="{{ route('admin.expenses.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-500 bg-gray-100 rounded-lg hover:bg-gray-200">Clear</a>
    @endif
</form>

<div class="glass-card rounded-2xl overflow-hidden anim-up d2">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead style="background:linear-gradient(135deg,#f8f9ff,#f0f4ff)">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($expenses as $expense)
                    @php
                        $catColors = [
                            'rent'      => 'bg-blue-50 text-blue-600',
                            'utilities' => 'bg-yellow-50 text-yellow-600',
                            'salaries'  => 'bg-purple-50 text-purple-600',
                            'marketing' => 'bg-pink-50 text-pink-600',
                            'misc'      => 'bg-gray-100 text-gray-600',
                        ];
                    @endphp
                    <tr class="hover:bg-indigo-50/30 transition-colors">
                        <td class="px-5 py-3.5">
                            <p class="font-semibold text-gray-800">{{ $expense->title }}</p>
                            @if($expense->description)
                                <p class="text-xs text-gray-400">{{ Str::limit($expense->description, 60) }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-lg {{ $catColors[$expense->category] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ \App\Models\Expense::$categories[$expense->category] ?? $expense->category }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right font-bold text-sm text-red-600">
                            Rs {{ number_format($expense->amount) }}
                        </td>
                        <td class="px-5 py-3.5 text-xs text-gray-500">{{ $expense->expense_date->format('d M Y') }}</td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.expenses.edit', $expense) }}" class="text-xs font-semibold text-amber-600 hover:text-amber-800 px-2 py-1 bg-amber-50 rounded-lg">Edit</a>
                                <form action="{{ route('admin.expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('Delete this expense?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs font-semibold text-red-500 hover:text-red-700 px-2 py-1 bg-red-50 rounded-lg">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-12 text-center text-gray-400">
                        <div class="text-3xl mb-2">&#128176;</div>No expenses recorded.
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($expenses->hasPages())
        <div class="px-5 py-4 border-t border-gray-50">{{ $expenses->links() }}</div>
    @endif
</div>
@endsection

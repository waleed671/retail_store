@extends('layouts.admin')
@section('title', 'Edit Account')
@section('page-title', 'Edit Account')
@section('content')

<div class="max-w-lg">
    <div class="flex items-center gap-3 mb-6 anim-up">
        <a href="{{ route('admin.accounts.index') }}" class="text-gray-400 hover:text-indigo-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h2 class="text-lg font-black" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Edit: {{ $account->name }}</h2>
        @if($account->is_system)
            <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-full">System Account</span>
        @endif
    </div>

    <form action="{{ route('admin.accounts.update', $account) }}" method="POST" class="glass-card p-6 rounded-2xl anim-up d1 space-y-5">
        @csrf @method('PUT')

        @if($account->is_system)
            <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-700">
                ⚠️ This is a system account. Only the name and description can be changed.
            </div>
        @endif

        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Account Code <span class="text-red-400">*</span></label>
                <input type="text" name="code" value="{{ old('code', $account->code) }}" class="input-cyber"
                       {{ $account->is_system ? 'disabled' : 'required' }}>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Account Type <span class="text-red-400">*</span></label>
                <select name="type" class="input-cyber" {{ $account->is_system ? 'disabled' : 'required' }}>
                    @foreach(['asset','liability','equity','income','expense'] as $t)
                        <option value="{{ $t }}" {{ old('type', $account->type) == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">Account Name <span class="text-red-400">*</span></label>
            <input type="text" name="name" value="{{ old('name', $account->name) }}" class="input-cyber" required>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">Description</label>
            <textarea name="description" rows="2" class="input-cyber">{{ old('description', $account->description) }}</textarea>
        </div>
        <div class="cyber-divider"></div>
        <div class="flex gap-3">
            <button type="submit" class="btn-primary">Update Account</button>
            <a href="{{ route('admin.accounts.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-500 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Cancel</a>
        </div>
    </form>
</div>
@endsection

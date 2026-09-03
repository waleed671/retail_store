@extends('layouts.admin')
@section('title', 'Edit Unit')
@section('page-title', 'Edit Unit')
@section('content')

<div class="max-w-md">
    <div class="flex items-center gap-3 mb-6 anim-up">
        <a href="{{ route('admin.units.index') }}" class="text-gray-400 hover:text-indigo-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h2 class="text-lg font-black" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Edit: {{ $unit->name }}</h2>
    </div>

    <form action="{{ route('admin.units.update', $unit) }}" method="POST" class="glass-card p-6 rounded-2xl anim-up d1 space-y-5">
        @csrf @method('PUT')
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">Unit Name <span class="text-red-400">*</span></label>
            <input type="text" name="name" value="{{ old('name', $unit->name) }}" class="input-cyber" required>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">Abbreviation <span class="text-red-400">*</span></label>
            <input type="text" name="abbreviation" value="{{ old('abbreviation', $unit->abbreviation) }}" class="input-cyber" required>
        </div>
        <div class="cyber-divider"></div>
        <div class="flex gap-3">
            <button type="submit" class="btn-primary">Update Unit</button>
            <a href="{{ route('admin.units.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-500 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Cancel</a>
        </div>
    </form>
</div>
@endsection

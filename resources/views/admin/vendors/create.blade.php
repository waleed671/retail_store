@extends('layouts.admin')
@section('title', 'Add Vendor')
@section('page-title', 'Add Vendor')
@section('content')

<div class="max-w-2xl">
    <div class="flex items-center gap-3 mb-6 anim-up">
        <a href="{{ route('admin.vendors.index') }}" class="text-gray-400 hover:text-indigo-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h2 class="text-lg font-black" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">New Vendor</h2>
    </div>

    <form action="{{ route('admin.vendors.store') }}" method="POST" class="glass-card p-6 rounded-2xl anim-up d1 space-y-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Company Name <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="input-cyber" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Contact Person</label>
                <input type="text" name="contact_person" value="{{ old('contact_person') }}" class="input-cyber">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="input-cyber">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="input-cyber">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">City</label>
                <input type="text" name="city" value="{{ old('city') }}" class="input-cyber">
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Address</label>
                <textarea name="address" rows="2" class="input-cyber">{{ old('address') }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Notes</label>
                <textarea name="notes" rows="3" class="input-cyber" placeholder="Internal notes about this vendor...">{{ old('notes') }}</textarea>
            </div>
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} class="w-4 h-4 accent-indigo-600">
                <label for="is_active" class="text-sm font-semibold text-gray-700">Active vendor</label>
            </div>
        </div>

        <div class="cyber-divider"></div>
        <div class="flex gap-3">
            <button type="submit" class="btn-primary">Save Vendor</button>
            <a href="{{ route('admin.vendors.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-500 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Cancel</a>
        </div>
    </form>
</div>
@endsection

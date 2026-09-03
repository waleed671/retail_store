@extends('layouts.admin')
@section('title', 'Vendors')
@section('page-title', 'Vendors')
@section('content')

<div class="flex items-center justify-between mb-6 anim-up">
    <div>
        <h2 class="text-lg font-black text-gray-900 flex items-center gap-2">
            <span class="w-1 h-5 rounded-full inline-block" style="background:linear-gradient(180deg,#6366f1,#06b6d4)"></span>
            <span style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">All Vendors</span>
        </h2>
        <p class="text-xs text-gray-400 mt-0.5 ml-3">{{ $vendors->total() }} vendors total</p>
    </div>
    <a href="{{ route('admin.vendors.create') }}" class="btn-primary inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Vendor
    </a>
</div>

{{-- Search --}}
<form method="GET" class="glass-card p-4 mb-6 anim-up d1 flex gap-3 items-end flex-wrap">
    <div class="flex-1 min-w-48">
        <label class="block text-xs font-semibold text-gray-500 mb-1">Search</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, city..." class="input-cyber">
    </div>
    <button type="submit" class="btn-primary">Search</button>
    @if(request('search'))
        <a href="{{ route('admin.vendors.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-500 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Clear</a>
    @endif
</form>

<div class="glass-card rounded-2xl overflow-hidden anim-up d2">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead style="background:linear-gradient(135deg,#f8f9ff,#f0f4ff)">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Vendor</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Contact</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">City</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Balance</th>
                    <th class="text-center px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($vendors as $vendor)
                    <tr class="hover:bg-indigo-50/30 transition-colors">
                        <td class="px-5 py-3.5">
                            <a href="{{ route('admin.vendors.show', $vendor) }}" class="font-semibold text-indigo-600 hover:text-indigo-800">{{ $vendor->name }}</a>
                            @if($vendor->contact_person)
                                <p class="text-xs text-gray-400">{{ $vendor->contact_person }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-gray-600">
                            @if($vendor->phone)<p class="text-xs">{{ $vendor->phone }}</p>@endif
                            @if($vendor->email)<p class="text-xs text-gray-400">{{ $vendor->email }}</p>@endif
                        </td>
                        <td class="px-5 py-3.5 text-gray-600 text-xs">{{ $vendor->city ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-right font-bold text-sm" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                            Rs {{ number_format($vendor->balance) }}
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            @if($vendor->is_active)
                                <span class="pill-delivered">Active</span>
                            @else
                                <span class="pill-cancelled">Inactive</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.vendors.show', $vendor) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 px-2 py-1 bg-indigo-50 rounded-lg transition-colors">View</a>
                                <a href="{{ route('admin.vendors.edit', $vendor) }}" class="text-xs font-semibold text-amber-600 hover:text-amber-800 px-2 py-1 bg-amber-50 rounded-lg transition-colors">Edit</a>
                                <form action="{{ route('admin.vendors.destroy', $vendor) }}" method="POST" onsubmit="return confirm('Delete this vendor?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs font-semibold text-red-500 hover:text-red-700 px-2 py-1 bg-red-50 rounded-lg transition-colors">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                            <div class="text-3xl mb-2">&#127968;</div>
                            No vendors found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($vendors->hasPages())
        <div class="px-5 py-4 border-t border-gray-50">
            {{ $vendors->links() }}
        </div>
    @endif
</div>
@endsection

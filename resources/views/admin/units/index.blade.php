@extends('layouts.admin')
@section('title', 'Units')
@section('page-title', 'Units of Measure')
@section('content')

<div class="flex items-center justify-between mb-6 anim-up">
    <div>
        <h2 class="text-lg font-black" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Units of Measure</h2>
        <p class="text-xs text-gray-400 mt-0.5">Manage product units (Pcs, Kg, Box, etc.)</p>
    </div>
    <a href="{{ route('admin.units.create') }}" class="btn-primary text-sm">+ Add Unit</a>
</div>

<div class="glass-card rounded-2xl overflow-hidden anim-up d1">
    <table class="w-full text-sm">
        <thead style="background:linear-gradient(135deg,#f8f9ff,#f0f4ff)">
            <tr>
                <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
                <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Abbreviation</th>
                <th class="text-center px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Products</th>
                <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($units as $unit)
                <tr class="hover:bg-indigo-50/30 transition-colors">
                    <td class="px-5 py-3.5 font-semibold text-gray-800">{{ $unit->name }}</td>
                    <td class="px-5 py-3.5">
                        <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-md">{{ $unit->abbreviation }}</span>
                    </td>
                    <td class="px-5 py-3.5 text-center text-gray-500">{{ $unit->products_count }}</td>
                    <td class="px-5 py-3.5 text-right flex justify-end gap-2">
                        <a href="{{ route('admin.units.edit', $unit) }}" class="text-xs font-semibold text-amber-600 hover:text-amber-800 px-2 py-1 bg-amber-50 rounded-lg transition-colors">Edit</a>
                        <form action="{{ route('admin.units.destroy', $unit) }}" method="POST"
                              onsubmit="return confirm('Delete this unit?')">
                            @csrf @method('DELETE')
                            <button class="text-xs font-semibold text-red-500 hover:text-red-700 px-2 py-1 bg-red-50 rounded-lg transition-colors">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-5 py-10 text-center text-gray-400">No units yet. <a href="{{ route('admin.units.create') }}" class="text-indigo-500 underline">Add one</a>.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@extends('layouts.admin')
@section('title', 'Warehouses')
@section('page-title', 'Warehouses')
@section('content')

<div class="flex items-center justify-between mb-6 anim-up">
    <div>
        <h2 class="text-lg font-black" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Warehouses</h2>
        <p class="text-xs text-gray-400 mt-0.5">Manage storage locations for your inventory</p>
    </div>
    <a href="{{ route('admin.warehouses.create') }}" class="btn-primary text-sm">+ Add Warehouse</a>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 anim-up d1">
    @forelse($warehouses as $warehouse)
        <div class="glass-card p-5 rounded-2xl flex flex-col gap-3 hover:shadow-lg transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="font-bold text-gray-800 text-sm">{{ $warehouse->name }}</h3>
                    @if($warehouse->location)
                        <p class="text-xs text-gray-400 mt-0.5">📍 {{ $warehouse->location }}</p>
                    @endif
                </div>
                @if($warehouse->is_active)
                    <span class="pill-delivered">Active</span>
                @else
                    <span class="pill-cancelled">Inactive</span>
                @endif
            </div>
            <div class="flex items-center gap-4 text-center py-3 bg-indigo-50/50 rounded-xl">
                <div class="flex-1">
                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wide">SKUs</p>
                    <p class="text-xl font-black text-indigo-600">{{ $warehouse->stocks_count }}</p>
                </div>
                <div class="w-px h-8 bg-indigo-100"></div>
                <div class="flex-1">
                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wide">Total Units</p>
                    <p class="text-xl font-black text-indigo-600">{{ number_format($warehouse->totalItems()) }}</p>
                </div>
            </div>
            <div class="flex gap-2 mt-1">
                <a href="{{ route('admin.warehouses.show', $warehouse) }}" class="flex-1 text-center text-xs font-semibold text-indigo-600 hover:text-indigo-800 px-2 py-1.5 bg-indigo-50 rounded-lg transition-colors">View Stock</a>
                <a href="{{ route('admin.warehouses.edit', $warehouse) }}" class="flex-1 text-center text-xs font-semibold text-amber-600 hover:text-amber-800 px-2 py-1.5 bg-amber-50 rounded-lg transition-colors">Edit</a>
                <form action="{{ route('admin.warehouses.destroy', $warehouse) }}" method="POST"
                      onsubmit="return confirm('Delete {{ $warehouse->name }}?')">
                    @csrf @method('DELETE')
                    <button class="text-xs font-semibold text-red-500 hover:text-red-700 px-2 py-1.5 bg-red-50 rounded-lg transition-colors">Delete</button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-span-3 text-center py-16 text-gray-400">
            <p class="text-4xl mb-3">🏭</p>
            <p class="font-semibold">No warehouses yet.</p>
            <a href="{{ route('admin.warehouses.create') }}" class="text-indigo-500 underline text-sm">Add your first warehouse</a>
        </div>
    @endforelse
</div>
@endsection

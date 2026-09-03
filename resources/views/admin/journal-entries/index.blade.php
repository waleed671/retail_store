@extends('layouts.admin')
@section('title', 'Journal Entries')
@section('page-title', 'Journal Entries')
@section('content')

<div class="flex items-center justify-between mb-6 anim-up">
    <div>
        <h2 class="text-lg font-black" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Journal Entries</h2>
        <p class="text-xs text-gray-400 mt-0.5">All double-entry accounting vouchers</p>
    </div>
    <a href="{{ route('admin.journal-entries.create') }}" class="btn-primary text-sm">+ New Entry</a>
</div>

{{-- Filters --}}
<form method="GET" class="glass-card p-4 rounded-2xl mb-5 anim-up d1 flex flex-wrap gap-3 items-end">
    <div>
        <label class="block text-xs font-semibold text-gray-500 mb-1">Search</label>
        <input type="text" name="search" value="{{ request('search') }}" class="input-cyber" placeholder="Voucher / description…" style="width:200px">
    </div>
    <div>
        <label class="block text-xs font-semibold text-gray-500 mb-1">From Date</label>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="input-cyber" style="width:160px">
    </div>
    <div>
        <label class="block text-xs font-semibold text-gray-500 mb-1">To Date</label>
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="input-cyber" style="width:160px">
    </div>
    <button type="submit" class="btn-primary text-sm">Filter</button>
    <a href="{{ route('admin.journal-entries.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-500 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Reset</a>
</form>

<div class="glass-card rounded-2xl overflow-hidden anim-up d2">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead style="background:linear-gradient(135deg,#f8f9ff,#f0f4ff)">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Voucher #</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Reference</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">By</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($entries as $entry)
                    <tr class="hover:bg-indigo-50/30 transition-colors">
                        <td class="px-5 py-3.5 text-xs text-gray-500 whitespace-nowrap">{{ $entry->entry_date->format('d M Y') }}</td>
                        <td class="px-5 py-3.5 font-mono font-bold text-indigo-600 text-xs">{{ $entry->voucher_number }}</td>
                        <td class="px-5 py-3.5 text-gray-700 max-w-[200px] truncate">{{ $entry->description }}</td>
                        <td class="px-5 py-3.5 text-xs text-gray-400">
                            {{ $entry->reference_type ? $entry->reference_type . ' #' . $entry->reference_id : '—' }}
                        </td>
                        <td class="px-5 py-3.5 text-right font-bold text-gray-700">
                            Rs {{ number_format($entry->lines->where('type','debit')->sum('amount'), 2) }}
                        </td>
                        <td class="px-5 py-3.5 text-xs text-gray-500">{{ $entry->creator->name ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ route('admin.journal-entries.show', $entry) }}" class="text-xs font-semibold text-indigo-600 px-2 py-1 bg-indigo-50 rounded-lg hover:bg-indigo-100">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-5 py-10 text-center text-gray-400">No journal entries yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($entries->hasPages())
        <div class="px-5 py-4 border-t border-gray-50">{{ $entries->links() }}</div>
    @endif
</div>
@endsection

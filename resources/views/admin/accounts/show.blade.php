@extends('layouts.admin')
@section('title', $account->name . ' — Ledger')
@section('page-title', 'Account Ledger')
@section('content')

<div class="flex items-center gap-3 mb-6 anim-up">
    <a href="{{ route('admin.accounts.index') }}" class="text-gray-400 hover:text-indigo-600 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
        <h2 class="text-lg font-black" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
            {{ $account->code }} — {{ $account->name }}
        </h2>
        <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold {{ $account->type_badge }}">{{ ucfirst($account->type) }}</span>
    </div>
</div>

{{-- Balance summary --}}
<div class="grid grid-cols-3 gap-4 mb-6 anim-up d1">
    <div class="stat-card text-center">
        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">Total Debits</p>
        <p class="text-xl font-black text-blue-700 mt-1">Rs {{ number_format($totalDebit, 2) }}</p>
    </div>
    <div class="stat-card text-center">
        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">Total Credits</p>
        <p class="text-xl font-black text-purple-700 mt-1">Rs {{ number_format($totalCredit, 2) }}</p>
    </div>
    <div class="stat-card text-center">
        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">Net Balance</p>
        <p class="text-xl font-black {{ $balance >= 0 ? 'text-green-700' : 'text-red-600' }} mt-1">
            Rs {{ number_format(abs($balance), 2) }}
            <span class="text-sm font-normal">{{ $balance >= 0 ? 'Dr' : 'Cr' }}</span>
        </p>
    </div>
</div>

{{-- Ledger entries --}}
<div class="glass-card rounded-2xl overflow-hidden anim-up d2">
    <div class="px-5 py-4 border-b border-gray-50">
        <h3 class="font-bold text-gray-900 text-sm">Transaction History</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead style="background:linear-gradient(135deg,#f8f9ff,#f0f4ff)">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Voucher</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Note</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Debit</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Credit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($lines as $line)
                    <tr class="hover:bg-indigo-50/30 transition-colors">
                        <td class="px-5 py-3.5 text-xs text-gray-500 whitespace-nowrap">{{ $line->journalEntry->entry_date->format('d M Y') }}</td>
                        <td class="px-5 py-3.5">
                            <a href="{{ route('admin.journal-entries.show', $line->journalEntry) }}"
                               class="font-mono text-xs text-indigo-600 hover:underline">{{ $line->journalEntry->voucher_number }}</a>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 max-w-[220px] truncate">{{ $line->journalEntry->description }}</td>
                        <td class="px-5 py-3.5 text-xs text-gray-400 max-w-[160px] truncate">{{ $line->note ?: '—' }}</td>
                        <td class="px-5 py-3.5 text-right font-semibold {{ $line->type === 'debit' ? 'text-blue-700' : 'text-gray-300' }}">
                            {{ $line->type === 'debit' ? 'Rs ' . number_format($line->amount, 2) : '' }}
                        </td>
                        <td class="px-5 py-3.5 text-right font-semibold {{ $line->type === 'credit' ? 'text-purple-700' : 'text-gray-300' }}">
                            {{ $line->type === 'credit' ? 'Rs ' . number_format($line->amount, 2) : '' }}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">No transactions yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($lines->hasPages())
        <div class="px-5 py-4 border-t border-gray-50">{{ $lines->links() }}</div>
    @endif
</div>
@endsection

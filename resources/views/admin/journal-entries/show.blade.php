@extends('layouts.admin')
@section('title', $journalEntry->voucher_number)
@section('page-title', 'Journal Entry')
@section('content')

<div class="max-w-3xl">
    <div class="flex items-center gap-3 mb-6 anim-up">
        <a href="{{ route('admin.journal-entries.index') }}" class="text-gray-400 hover:text-indigo-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h2 class="text-lg font-black" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">{{ $journalEntry->voucher_number }}</h2>
    </div>

    <div class="glass-card p-6 rounded-2xl anim-up d1 space-y-4">
        {{-- Header info --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase">Date</p>
                <p class="font-semibold text-gray-700">{{ $journalEntry->entry_date->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase">Created By</p>
                <p class="font-semibold text-gray-700">{{ $journalEntry->creator->name ?? '—' }}</p>
            </div>
            @if($journalEntry->reference_type)
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase">Reference</p>
                <p class="font-semibold text-gray-700">{{ $journalEntry->reference_type }} #{{ $journalEntry->reference_id }}</p>
            </div>
            @endif
            <div class="col-span-2 sm:col-span-3">
                <p class="text-xs text-gray-400 font-semibold uppercase">Description</p>
                <p class="text-gray-700">{{ $journalEntry->description }}</p>
            </div>
        </div>

        <div class="cyber-divider"></div>

        {{-- Lines --}}
        <table class="w-full text-sm">
            <thead>
                <tr>
                    <th class="text-left pb-2 text-xs font-bold text-gray-400 uppercase">Account</th>
                    <th class="text-left pb-2 text-xs font-bold text-gray-400 uppercase">Note</th>
                    <th class="text-right pb-2 text-xs font-bold text-gray-400 uppercase">Debit</th>
                    <th class="text-right pb-2 text-xs font-bold text-gray-400 uppercase">Credit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($journalEntry->lines as $line)
                    <tr>
                        <td class="py-2.5">
                            <a href="{{ route('admin.accounts.show', $line->account) }}" class="font-semibold text-indigo-600 hover:underline">
                                {{ $line->account->code }} — {{ $line->account->name }}
                            </a>
                        </td>
                        <td class="py-2.5 text-xs text-gray-400">{{ $line->note ?: '—' }}</td>
                        <td class="py-2.5 text-right font-semibold {{ $line->type === 'debit' ? 'text-blue-700' : 'text-gray-200' }}">
                            {{ $line->type === 'debit' ? 'Rs ' . number_format($line->amount, 2) : '' }}
                        </td>
                        <td class="py-2.5 text-right font-semibold {{ $line->type === 'credit' ? 'text-purple-700' : 'text-gray-200' }}">
                            {{ $line->type === 'credit' ? 'Rs ' . number_format($line->amount, 2) : '' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="border-t-2 border-indigo-100">
                    <td colspan="2" class="pt-3 text-xs font-bold text-gray-500 uppercase">Totals</td>
                    <td class="pt-3 text-right font-black text-blue-700">Rs {{ number_format($journalEntry->totalDebit(), 2) }}</td>
                    <td class="pt-3 text-right font-black text-purple-700">Rs {{ number_format($journalEntry->totalCredit(), 2) }}</td>
                </tr>
            </tfoot>
        </table>

        @if(abs($journalEntry->totalDebit() - $journalEntry->totalCredit()) < 0.01)
            <p class="text-xs text-green-600 font-semibold flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Entry is balanced
            </p>
        @else
            <p class="text-xs text-red-600 font-semibold">⚠️ Entry is NOT balanced!</p>
        @endif
    </div>
</div>
@endsection

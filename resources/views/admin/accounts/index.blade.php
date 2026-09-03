@extends('layouts.admin')
@section('title', 'Chart of Accounts')
@section('page-title', 'Chart of Accounts')
@section('content')

@php
    $typeLabels = ['asset'=>'Assets','liability'=>'Liabilities','equity'=>'Equity','income'=>'Income','expense'=>'Expenses'];
    $typeIcons  = ['asset'=>'💰','liability'=>'📋','equity'=>'🏦','income'=>'📈','expense'=>'📉'];
@endphp

<div class="flex items-center justify-between mb-6 anim-up">
    <div>
        <h2 class="text-lg font-black" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Chart of Accounts</h2>
        <p class="text-xs text-gray-400 mt-0.5">All financial accounts — debits, credits, balances</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.journal-entries.create') }}" class="text-xs font-semibold text-green-700 px-3 py-1.5 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">+ Journal Entry</a>
        <a href="{{ route('admin.accounts.create') }}" class="btn-primary text-sm">+ Add Account</a>
    </div>
</div>

{{-- Summary cards --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6 anim-up d1">
    @foreach(['asset','liability','equity','income','expense'] as $type)
        @php $s = $summary[$type]; @endphp
        <div class="stat-card text-center">
            <p class="text-2xl mb-1">{{ $typeIcons[$type] }}</p>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">{{ $typeLabels[$type] }}</p>
            <p class="text-lg font-black mt-1 {{ in_array($type,['asset','income']) ? 'text-green-700' : 'text-red-600' }}">
                Rs {{ number_format(abs($s['net'])) }}
            </p>
            <p class="text-[10px] text-gray-400">{{ $grouped->get($type,collect())->count() }} accounts</p>
        </div>
    @endforeach
</div>

{{-- Accounts by type --}}
@foreach(['asset','liability','equity','income','expense'] as $type)
    @php $typeAccounts = $grouped->get($type, collect()); @endphp
    @if($typeAccounts->count())
        <div class="glass-card rounded-2xl overflow-hidden anim-up mb-5">
            <div class="px-5 py-3 border-b border-gray-50 flex items-center gap-2"
                 style="background:linear-gradient(135deg,#f8f9ff,#f0f4ff)">
                <span>{{ $typeIcons[$type] }}</span>
                <h3 class="font-bold text-gray-900 text-sm">{{ $typeLabels[$type] }}</h3>
                <span class="ml-auto text-xs text-gray-400">{{ $typeAccounts->count() }} account(s)</span>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th class="text-left px-5 py-2.5 text-xs font-bold text-gray-400 uppercase">Code</th>
                        <th class="text-left px-5 py-2.5 text-xs font-bold text-gray-400 uppercase">Name</th>
                        <th class="text-right px-5 py-2.5 text-xs font-bold text-gray-400 uppercase">Debit Total</th>
                        <th class="text-right px-5 py-2.5 text-xs font-bold text-gray-400 uppercase">Credit Total</th>
                        <th class="text-right px-5 py-2.5 text-xs font-bold text-gray-400 uppercase">Balance</th>
                        <th class="text-right px-5 py-2.5 text-xs font-bold text-gray-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($typeAccounts as $account)
                        @php
                            $deb = (float)\App\Models\JournalEntryLine::where('account_id',$account->id)->where('type','debit')->sum('amount');
                            $cre = (float)\App\Models\JournalEntryLine::where('account_id',$account->id)->where('type','credit')->sum('amount');
                            $bal = $deb - $cre;
                        @endphp
                        <tr class="hover:bg-indigo-50/30 transition-colors">
                            <td class="px-5 py-3 font-mono text-xs text-indigo-600 font-bold">{{ $account->code }}</td>
                            <td class="px-5 py-3">
                                <div class="font-semibold text-gray-800">{{ $account->name }}</div>
                                @if($account->description)
                                    <div class="text-[10px] text-gray-400">{{ $account->description }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-right text-gray-600">Rs {{ number_format($deb, 2) }}</td>
                            <td class="px-5 py-3 text-right text-gray-600">Rs {{ number_format($cre, 2) }}</td>
                            <td class="px-5 py-3 text-right font-bold {{ $bal >= 0 ? 'text-green-700' : 'text-red-600' }}">
                                Rs {{ number_format(abs($bal), 2) }}
                                <span class="text-[10px] font-normal">{{ $bal >= 0 ? 'Dr' : 'Cr' }}</span>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <div class="flex justify-end gap-1.5">
                                    <a href="{{ route('admin.accounts.show', $account) }}" class="text-xs font-semibold text-indigo-600 px-2 py-1 bg-indigo-50 rounded-lg hover:bg-indigo-100">Ledger</a>
                                    <a href="{{ route('admin.accounts.edit', $account) }}" class="text-xs font-semibold text-amber-600 px-2 py-1 bg-amber-50 rounded-lg hover:bg-amber-100">Edit</a>
                                    @unless($account->is_system)
                                        <form action="{{ route('admin.accounts.destroy', $account) }}" method="POST"
                                              onsubmit="return confirm('Delete account {{ $account->name }}?')">
                                            @csrf @method('DELETE')
                                            <button class="text-xs font-semibold text-red-500 px-2 py-1 bg-red-50 rounded-lg hover:bg-red-100">Del</button>
                                        </form>
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endforeach
@endsection

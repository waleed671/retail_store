@extends('layouts.admin')
@section('title', 'New Journal Entry')
@section('page-title', 'New Journal Entry')
@section('content')

<div class="max-w-4xl">
    <div class="flex items-center gap-3 mb-6 anim-up">
        <a href="{{ route('admin.journal-entries.index') }}" class="text-gray-400 hover:text-indigo-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h2 class="text-lg font-black" style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">New Journal Entry</h2>
    </div>

    <form action="{{ route('admin.journal-entries.store') }}" method="POST" class="space-y-5" id="je-form">
        @csrf

        <div class="glass-card p-6 rounded-2xl anim-up d1">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Entry Date <span class="text-red-400">*</span></label>
                    <input type="date" name="entry_date" value="{{ old('entry_date', now()->toDateString()) }}" class="input-cyber" required>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Description / Narration <span class="text-red-400">*</span></label>
                    <input type="text" name="description" value="{{ old('description') }}" class="input-cyber" placeholder="e.g. Cash received from sales, Rent paid for October…" required>
                </div>
            </div>
        </div>

        {{-- Lines --}}
        <div class="glass-card rounded-2xl overflow-hidden anim-up d2">
            <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
                <h3 class="font-bold text-gray-900 text-sm">Entry Lines</h3>
                <div class="flex items-center gap-3">
                    <span class="text-xs text-gray-500">Balance: <span id="balance-indicator" class="font-bold text-red-500">0.00</span></span>
                    <button type="button" onclick="addLine()" class="text-xs font-semibold text-indigo-600 px-3 py-1.5 bg-indigo-50 rounded-lg hover:bg-indigo-100">+ Add Line</button>
                </div>
            </div>
            <div class="p-4">
                <table class="w-full text-sm" id="lines-table">
                    <thead>
                        <tr>
                            <th class="text-left pb-2 text-xs font-bold text-gray-400 uppercase">Account</th>
                            <th class="text-center pb-2 text-xs font-bold text-gray-400 uppercase w-24">Type</th>
                            <th class="text-right pb-2 text-xs font-bold text-gray-400 uppercase w-36">Amount (Rs)</th>
                            <th class="pb-2 text-xs font-bold text-gray-400 uppercase w-48">Note</th>
                            <th class="w-8"></th>
                        </tr>
                    </thead>
                    <tbody id="lines-body">
                        {{-- Pre-fill from old() if validation failed --}}
                        @if(old('lines'))
                            @foreach(old('lines') as $i => $line)
                                <tr class="line-row">
                                    <td class="pr-2 pb-2">
                                        <select name="lines[{{ $i }}][account_id]" class="input-cyber account-select" required>
                                            <option value="">Select account…</option>
                                            @foreach($accounts as $acc)
                                                <option value="{{ $acc->id }}" {{ $line['account_id'] == $acc->id ? 'selected' : '' }}>
                                                    {{ $acc->code }} — {{ $acc->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-2 pb-2">
                                        <select name="lines[{{ $i }}][type]" class="input-cyber type-select" onchange="updateBalance()" required>
                                            <option value="debit"  {{ ($line['type'] ?? '') == 'debit'  ? 'selected' : '' }}>Debit</option>
                                            <option value="credit" {{ ($line['type'] ?? '') == 'credit' ? 'selected' : '' }}>Credit</option>
                                        </select>
                                    </td>
                                    <td class="px-2 pb-2">
                                        <input type="number" step="0.01" min="0.01" name="lines[{{ $i }}][amount]"
                                               value="{{ $line['amount'] ?? '' }}" class="input-cyber amount-input text-right"
                                               onchange="updateBalance()" required>
                                    </td>
                                    <td class="pl-2 pb-2">
                                        <input type="text" name="lines[{{ $i }}][note]" value="{{ $line['note'] ?? '' }}" class="input-cyber" placeholder="Optional note">
                                    </td>
                                    <td class="pb-2 pl-1">
                                        <button type="button" onclick="removeLine(this)" class="text-red-400 hover:text-red-600 text-lg leading-none">×</button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

                {{-- Totals row --}}
                <div class="mt-4 pt-4 border-t border-gray-100 flex justify-end gap-8 text-sm">
                    <div class="text-right">
                        <p class="text-xs text-gray-400 font-semibold uppercase">Total Debits</p>
                        <p class="font-black text-blue-700" id="total-debit">Rs 0.00</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-400 font-semibold uppercase">Total Credits</p>
                        <p class="font-black text-purple-700" id="total-credit">Rs 0.00</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-400 font-semibold uppercase">Difference</p>
                        <p class="font-black" id="difference">Rs 0.00</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-3 anim-up d3">
            <button type="submit" class="btn-primary">Save Journal Entry</button>
            <a href="{{ route('admin.journal-entries.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-500 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Cancel</a>
        </div>
    </form>
</div>

<script>
const accounts = @json($accounts->map(fn($a) => ['id' => $a->id, 'label' => $a->code . ' — ' . $a->name]));
let lineIndex = {{ old('lines') ? count(old('lines')) : 0 }};

function buildAccountOptions(selectedId = null) {
    return accounts.map(a =>
        `<option value="${a.id}" ${a.id == selectedId ? 'selected' : ''}>${a.label}</option>`
    ).join('');
}

function addLine() {
    const tbody = document.getElementById('lines-body');
    const row = document.createElement('tr');
    row.className = 'line-row';
    row.innerHTML = `
        <td class="pr-2 pb-2">
            <select name="lines[${lineIndex}][account_id]" class="input-cyber account-select" required>
                <option value="">Select account…</option>
                ${buildAccountOptions()}
            </select>
        </td>
        <td class="px-2 pb-2">
            <select name="lines[${lineIndex}][type]" class="input-cyber type-select" onchange="updateBalance()" required>
                <option value="debit">Debit</option>
                <option value="credit">Credit</option>
            </select>
        </td>
        <td class="px-2 pb-2">
            <input type="number" step="0.01" min="0.01" name="lines[${lineIndex}][amount]"
                   class="input-cyber amount-input text-right" onchange="updateBalance()" required>
        </td>
        <td class="pl-2 pb-2">
            <input type="text" name="lines[${lineIndex}][note]" class="input-cyber" placeholder="Optional note">
        </td>
        <td class="pb-2 pl-1">
            <button type="button" onclick="removeLine(this)" class="text-red-400 hover:text-red-600 text-lg leading-none">×</button>
        </td>
    `;
    tbody.appendChild(row);
    lineIndex++;
    updateBalance();
}

function removeLine(btn) {
    const rows = document.querySelectorAll('.line-row');
    if (rows.length <= 2) { alert('A journal entry needs at least 2 lines.'); return; }
    btn.closest('tr').remove();
    updateBalance();
}

function updateBalance() {
    let debit = 0, credit = 0;
    document.querySelectorAll('.line-row').forEach(row => {
        const type   = row.querySelector('.type-select')?.value;
        const amount = parseFloat(row.querySelector('.amount-input')?.value) || 0;
        if (type === 'debit')  debit  += amount;
        if (type === 'credit') credit += amount;
    });
    const diff = Math.abs(debit - credit);
    document.getElementById('total-debit').textContent  = 'Rs ' + debit.toFixed(2);
    document.getElementById('total-credit').textContent = 'Rs ' + credit.toFixed(2);
    const diffEl = document.getElementById('difference');
    diffEl.textContent = 'Rs ' + diff.toFixed(2);
    diffEl.className   = diff < 0.01 ? 'font-black text-green-600' : 'font-black text-red-600';
    document.getElementById('balance-indicator').textContent = diff.toFixed(2);
    document.getElementById('balance-indicator').className   = diff < 0.01 ? 'font-bold text-green-600' : 'font-bold text-red-500';
}

// Start with 2 blank lines if none pre-filled
document.addEventListener('DOMContentLoaded', function() {
    if (lineIndex === 0) { addLine(); addLine(); }
    else { updateBalance(); }
});
</script>
@endsection

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\JournalEntryLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $accounts = Account::withCount('lines')
            ->when($request->type, fn ($q, $t) => $q->where('type', $t))
            ->orderBy('code')
            ->get();

        // Group by type for the chart of accounts layout
        $grouped = $accounts->groupBy('type');

        // Summary: total debits, total credits, net by type
        $summary = [];
        foreach (['asset', 'liability', 'equity', 'income', 'expense'] as $type) {
            $typeAccounts = $grouped->get($type, collect());
            $debits  = 0;
            $credits = 0;
            foreach ($typeAccounts as $acc) {
                $debits  += (float) JournalEntryLine::where('account_id', $acc->id)->where('type', 'debit')->sum('amount');
                $credits += (float) JournalEntryLine::where('account_id', $acc->id)->where('type', 'credit')->sum('amount');
            }
            $summary[$type] = ['debits' => $debits, 'credits' => $credits, 'net' => $debits - $credits];
        }

        return view('admin.accounts.index', compact('accounts', 'grouped', 'summary'));
    }

    public function create()
    {
        return view('admin.accounts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'        => 'required|string|max:20|unique:accounts,code',
            'name'        => 'required|string|max:255',
            'type'        => 'required|in:asset,liability,equity,income,expense',
            'description' => 'nullable|string|max:500',
        ]);

        Account::create([
            'code'        => $request->code,
            'name'        => $request->name,
            'type'        => $request->type,
            'description' => $request->description,
            'is_system'   => false,
            'is_active'   => true,
        ]);

        return redirect()->route('admin.accounts.index')->with('success', 'Account created successfully.');
    }

    public function show(Account $account)
    {
        $lines = $account->lines()
            ->with('journalEntry')
            ->latest()
            ->paginate(30);

        $totalDebit  = (float) $account->lines()->where('type', 'debit')->sum('amount');
        $totalCredit = (float) $account->lines()->where('type', 'credit')->sum('amount');
        $balance     = $totalDebit - $totalCredit;

        return view('admin.accounts.show', compact('account', 'lines', 'totalDebit', 'totalCredit', 'balance'));
    }

    public function edit(Account $account)
    {
        return view('admin.accounts.edit', compact('account'));
    }

    public function update(Request $request, Account $account)
    {
        $request->validate([
            'code'        => 'required|string|max:20|unique:accounts,code,' . $account->id,
            'name'        => 'required|string|max:255',
            'type'        => 'required|in:asset,liability,equity,income,expense',
            'description' => 'nullable|string|max:500',
        ]);

        // System accounts: allow name/description edit but not type/code changes
        if ($account->is_system) {
            $account->update([
                'name'        => $request->name,
                'description' => $request->description,
            ]);
        } else {
            $account->update($request->only('code', 'name', 'type', 'description'));
        }

        return redirect()->route('admin.accounts.index')->with('success', 'Account updated.');
    }

    public function destroy(Account $account)
    {
        if ($account->is_system) {
            return back()->with('error', 'System accounts cannot be deleted.');
        }
        if ($account->lines()->exists()) {
            return back()->with('error', 'Cannot delete an account with journal entries.');
        }

        $account->delete();
        return back()->with('success', 'Account deleted.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JournalEntryController extends Controller
{
    public function index(Request $request)
    {
        $entries = JournalEntry::with(['lines.account', 'creator'])
            ->when($request->search, fn ($q, $s) =>
                $q->where('voucher_number', 'like', "%$s%")
                  ->orWhere('description', 'like', "%$s%"))
            ->when($request->date_from, fn ($q, $d) => $q->where('entry_date', '>=', $d))
            ->when($request->date_to,   fn ($q, $d) => $q->where('entry_date', '<=', $d))
            ->latest('entry_date')
            ->paginate(25)
            ->withQueryString();

        return view('admin.journal-entries.index', compact('entries'));
    }

    public function create()
    {
        $accounts = Account::where('is_active', true)->orderBy('code')->get();
        return view('admin.journal-entries.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'entry_date'          => 'required|date',
            'description'         => 'required|string|max:500',
            'lines'               => 'required|array|min:2',
            'lines.*.account_id'  => 'required|exists:accounts,id',
            'lines.*.type'        => 'required|in:debit,credit',
            'lines.*.amount'      => 'required|numeric|min:0.01',
            'lines.*.note'        => 'nullable|string|max:255',
        ]);

        // Validate that debits == credits
        $totalDebit  = collect($request->lines)->where('type', 'debit')->sum('amount');
        $totalCredit = collect($request->lines)->where('type', 'credit')->sum('amount');

        if (abs($totalDebit - $totalCredit) > 0.01) {
            return back()->withInput()
                ->with('error', "Journal entry is not balanced. Debits (Rs " . number_format($totalDebit, 2) . ") must equal Credits (Rs " . number_format($totalCredit, 2) . ").");
        }

        DB::transaction(function () use ($request) {
            $entry = JournalEntry::create([
                'voucher_number' => JournalEntry::generateVoucherNumber(),
                'entry_date'     => $request->entry_date,
                'description'    => $request->description,
                'reference_type' => $request->reference_type,
                'reference_id'   => $request->reference_id,
                'created_by'     => Auth::id(),
            ]);

            foreach ($request->lines as $line) {
                if (empty($line['account_id']) || empty($line['amount'])) continue;

                JournalEntryLine::create([
                    'journal_entry_id' => $entry->id,
                    'account_id'       => $line['account_id'],
                    'type'             => $line['type'],
                    'amount'           => $line['amount'],
                    'note'             => $line['note'] ?? null,
                ]);
            }
        });

        return redirect()->route('admin.journal-entries.index')
            ->with('success', 'Journal entry recorded successfully.');
    }

    public function show(JournalEntry $journalEntry)
    {
        $journalEntry->load(['lines.account', 'creator']);
        return view('admin.journal-entries.show', compact('journalEntry'));
    }
}

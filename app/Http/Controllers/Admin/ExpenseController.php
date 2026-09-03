<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::query()
            ->when($request->category, fn ($q, $c) => $q->where('category', $c))
            ->when($request->month, fn ($q, $m) => $q->whereYear('expense_date', substr($m, 0, 4))
                ->whereMonth('expense_date', substr($m, 5, 2)))
            ->latest('expense_date');

        $total    = (clone $query)->sum('amount');
        $expenses = $query->paginate(20)->withQueryString();

        return view('admin.expenses.index', compact('expenses', 'total'));
    }

    public function create()
    {
        return view('admin.expenses.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'amount'       => 'required|numeric|min:0',
            'category'     => 'required|in:rent,utilities,salaries,marketing,misc',
            'description'  => 'nullable|string',
            'expense_date' => 'required|date',
        ]);

        Expense::create($data);

        return redirect()->route('admin.expenses.index')
            ->with('success', 'Expense recorded successfully.');
    }

    public function edit(Expense $expense)
    {
        return view('admin.expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'amount'       => 'required|numeric|min:0',
            'category'     => 'required|in:rent,utilities,salaries,marketing,misc',
            'description'  => 'nullable|string',
            'expense_date' => 'required|date',
        ]);

        $expense->update($data);

        return redirect()->route('admin.expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('admin.expenses.index')
            ->with('success', 'Expense deleted.');
    }
}

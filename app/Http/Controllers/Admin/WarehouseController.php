<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\Product;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::withCount('stocks')->get();
        return view('admin.warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        return view('admin.warehouses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255|unique:warehouses,name',
            'location'  => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        Warehouse::create([
            'name'      => $request->name,
            'location'  => $request->location,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.warehouses.index')->with('success', 'Warehouse created successfully.');
    }

    public function show(Warehouse $warehouse)
    {
        $stocks = $warehouse->stocks()
            ->with('product.category', 'product.unit')
            ->orderByDesc('quantity')
            ->paginate(30);

        return view('admin.warehouses.show', compact('warehouse', 'stocks'));
    }

    public function edit(Warehouse $warehouse)
    {
        return view('admin.warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name'      => 'required|string|max:255|unique:warehouses,name,' . $warehouse->id,
            'location'  => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $warehouse->update([
            'name'      => $request->name,
            'location'  => $request->location,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.warehouses.index')->with('success', 'Warehouse updated.');
    }

    public function destroy(Warehouse $warehouse)
    {
        if ($warehouse->stocks()->where('quantity', '>', 0)->exists()) {
            return back()->with('error', 'Cannot delete a warehouse that still has stock. Transfer or adjust stock first.');
        }

        $warehouse->delete();
        return back()->with('success', 'Warehouse deleted.');
    }
}

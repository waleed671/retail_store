<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $movements = StockMovement::with(['product', 'fromWarehouse', 'toWarehouse', 'creator'])
            ->when($request->type, fn ($q, $t) => $q->where('type', $t))
            ->when($request->product_id, fn ($q, $id) => $q->where('product_id', $id))
            ->when($request->warehouse_id, fn ($q, $id) =>
                $q->where('from_warehouse_id', $id)->orWhere('to_warehouse_id', $id))
            ->latest()
            ->paginate(30)
            ->withQueryString();

        $warehouses = Warehouse::orderBy('name')->get();
        $products   = Product::orderBy('name')->get(['id', 'name', 'sku']);

        return view('admin.stock-movements.index', compact('movements', 'warehouses', 'products'));
    }

    public function create(Request $request)
    {
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $products   = Product::where('is_active', true)->with('unit')->orderBy('name')->get();

        // Pre-select source warehouse if passed via query string
        $fromWarehouseId = $request->input('from');

        return view('admin.stock-movements.create', compact('warehouses', 'products', 'fromWarehouseId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id'   => 'required|exists:warehouses,id|different:from_warehouse_id',
            'product_id'        => 'required|exists:products,id',
            'quantity'          => 'required|integer|min:1',
            'notes'             => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($request) {
            $fromId     = $request->from_warehouse_id;
            $toId       = $request->to_warehouse_id;
            $productId  = $request->product_id;
            $qty        = (int) $request->quantity;

            // Lock both source stock rows to prevent race conditions
            $fromStock = WarehouseStock::where('warehouse_id', $fromId)
                ->where('product_id', $productId)
                ->lockForUpdate()
                ->first();

            $available = $fromStock ? $fromStock->quantity : 0;

            if ($available < $qty) {
                throw new \Exception("Insufficient stock in source warehouse. Available: {$available}, Requested: {$qty}.");
            }

            // Decrement source
            $fromStock->decrement('quantity', $qty);

            // Increment destination (create row if needed)
            WarehouseStock::updateOrCreate(
                ['warehouse_id' => $toId, 'product_id' => $productId],
                ['quantity' => 0]
            );
            WarehouseStock::where('warehouse_id', $toId)
                ->where('product_id', $productId)
                ->increment('quantity', $qty);

            // Log the movement
            StockMovement::create([
                'type'              => 'transfer',
                'from_warehouse_id' => $fromId,
                'to_warehouse_id'   => $toId,
                'product_id'        => $productId,
                'quantity'          => $qty,
                'notes'             => $request->notes,
                'created_by'        => Auth::id(),
            ]);
        });

        return redirect()->route('admin.stock-movements.index')
            ->with('success', 'Stock transferred successfully.');
    }

    /**
     * Get available stock for a product in a warehouse — used by the create form via AJAX.
     */
    public function available(Request $request)
    {
        $warehouseId = $request->input('warehouse_id');
        $productId   = $request->input('product_id');

        $stock = WarehouseStock::where('warehouse_id', $warehouseId)
            ->where('product_id', $productId)
            ->value('quantity');

        return response()->json(['available' => (int) $stock]);
    }
}

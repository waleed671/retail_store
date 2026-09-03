<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $purchaseOrders = PurchaseOrder::with('vendor')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->search, fn ($q, $s) => $q->where('reference_number', 'like', "%$s%")
                ->orWhereHas('vendor', fn ($v) => $v->where('name', 'like', "%$s%")))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.purchase-orders.index', compact('purchaseOrders'));
    }

    public function create()
    {
        $vendors  = Vendor::where('is_active', true)->orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('admin.purchase-orders.create', compact('vendors', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vendor_id'            => 'required|exists:vendors,id',
            'notes'                => 'nullable|string',
            'items'                => 'required|array|min:1',
            'items.*.product_id'   => 'required|exists:products,id',
            'items.*.quantity'     => 'required|integer|min:1',
            'items.*.unit_cost'    => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $total = 0;
            $items = [];

            foreach ($request->items as $item) {
                $lineTotal = $item['quantity'] * $item['unit_cost'];
                $total    += $lineTotal;
                $items[]   = [
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'unit_cost'  => $item['unit_cost'],
                    'total_cost' => $lineTotal,
                ];
            }

            $po = PurchaseOrder::create([
                'vendor_id'        => $request->vendor_id,
                'reference_number' => PurchaseOrder::generateReference(),
                'status'           => 'draft',
                'notes'            => $request->notes,
                'total_amount'     => $total,
            ]);

            $po->items()->createMany($items);
        });

        return redirect()->route('admin.purchase-orders.index')
            ->with('success', 'Purchase order created successfully.');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('vendor', 'items.product');

        return view('admin.purchase-orders.show', compact('purchaseOrder'));
    }

    public function receive(Request $request, PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status === 'received') {
            return back()->with('error', 'This purchase order has already been received.');
        }

        if ($purchaseOrder->status === 'cancelled') {
            return back()->with('error', 'Cannot receive a cancelled purchase order.');
        }

        DB::transaction(function () use ($purchaseOrder) {
            $purchaseOrder->load('items');

            foreach ($purchaseOrder->items as $item) {
                Product::where('id', $item->product_id)
                    ->increment('stock', $item->quantity);
            }

            $purchaseOrder->update([
                'status'      => 'received',
                'received_at' => now(),
            ]);
        });

        return redirect()->route('admin.purchase-orders.show', $purchaseOrder)
            ->with('success', 'Purchase order marked as received and stock updated.');
    }
}

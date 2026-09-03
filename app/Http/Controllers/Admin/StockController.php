<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockAdjustment;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%$s%")
                ->orWhere('sku', 'like', "%$s%"))
            ->when($request->stock_level, function ($q, $level) {
                match ($level) {
                    'low'    => $q->where('stock', '<=', 5),
                    'medium' => $q->whereBetween('stock', [6, 20]),
                    'high'   => $q->where('stock', '>', 20),
                    default  => null,
                };
            })
            ->orderBy('stock')
            ->paginate(30)
            ->withQueryString();

        return view('admin.stock.index', compact('products'));
    }

    public function adjust(Request $request, Product $product)
    {
        $data = $request->validate([
            'adjustment' => 'required|integer|not_in:0',
            'reason'     => 'required|string|max:255',
        ]);

        $newStock = $product->stock + $data['adjustment'];

        if ($newStock < 0) {
            return back()->with('error', 'Stock cannot go below zero. Current stock: ' . $product->stock);
        }

        StockAdjustment::create([
            'product_id'  => $product->id,
            'adjustment'  => $data['adjustment'],
            'reason'      => $data['reason'],
            'adjusted_by' => auth()->id(),
        ]);

        $product->update(['stock' => $newStock]);

        return back()->with('success', "Stock for \"{$product->name}\" adjusted by {$data['adjustment']}. New stock: {$newStock}");
    }
}

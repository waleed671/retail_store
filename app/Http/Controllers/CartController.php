<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected Cart $cart)
    {
    }

    public function index()
    {
        $items = $this->cart->items();
        $subtotal = $this->cart->subtotal();

        return view('cart.index', compact('items', 'subtotal'));
    }

    public function add(Request $request, Product $product)
    {
        $data = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        if (! $product->is_active || $product->stock < 1) {
            return back()->with('error', 'This product is currently out of stock.');
        }

        $this->cart->add($product, $data['quantity'] ?? 1);

        return back()->with('success', "{$product->name} added to your cart.");
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:50'],
        ]);

        $this->cart->update($product->id, $data['quantity']);

        return back()->with('success', 'Cart updated.');
    }

    public function remove(Product $product)
    {
        $this->cart->remove($product->id);

        return back()->with('success', 'Item removed from cart.');
    }
}

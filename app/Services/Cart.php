<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

/**
 * Simple session-backed shopping cart.
 * Stored in the session as: ['product_id' => quantity, ...]
 * This keeps the cart working for guests without requiring a DB table,
 * and it survives across requests via the normal session cookie.
 */
class Cart
{
    protected const SESSION_KEY = 'cart';

    public function add(Product $product, int $quantity = 1): void
    {
        $cart = $this->raw();
        $current = $cart[$product->id] ?? 0;
        $newQty = max(1, min($product->stock, $current + $quantity));
        $cart[$product->id] = $newQty;
        Session::put(self::SESSION_KEY, $cart);
    }

    public function update(int $productId, int $quantity): void
    {
        $cart = $this->raw();

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $product = Product::find($productId);
            $max = $product ? $product->stock : $quantity;
            $cart[$productId] = min($quantity, max($max, 1));
        }

        Session::put(self::SESSION_KEY, $cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->raw();
        unset($cart[$productId]);
        Session::put(self::SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    protected function raw(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    /**
     * @return Collection<int, array{product: Product, quantity: int, subtotal: float}>
     */
    public function items(): Collection
    {
        $cart = $this->raw();

        if (empty($cart)) {
            return collect();
        }

        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');

        return collect($cart)->map(function ($quantity, $productId) use ($products) {
            $product = $products->get($productId);

            if (! $product) {
                return null;
            }

            $quantity = min($quantity, max($product->stock, 0));

            return [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => round($product->final_price * $quantity, 2),
            ];
        })->filter()->values();
    }

    public function itemCount(): int
    {
        return (int) collect($this->raw())->sum();
    }

    public function subtotal(): float
    {
        return (float) $this->items()->sum('subtotal');
    }

    public function isEmpty(): bool
    {
        return empty($this->raw());
    }
}

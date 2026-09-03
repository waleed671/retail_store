<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(protected Cart $cart)
    {
    }

    public function index()
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $items = $this->cart->items();
        $subtotal = $this->cart->subtotal();
        $shippingFee = $subtotal >= (float) config('app.store.free_shipping_threshold', 5000)
            ? 0
            : (float) (config('app.store.shipping_fee') ?? config('app.store.shipping_flat_fee', 200));
        $total = $subtotal + $shippingFee;

        $user = Auth::user();

        return view('checkout.index', compact('items', 'subtotal', 'shippingFee', 'total', 'user'));
    }

    public function store(Request $request)
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'shipping_address' => ['required', 'string', 'max:1000'],
            'city' => ['required', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', 'in:cod,bank_transfer'],
        ]);

        $items = $this->cart->items();

        // Re-validate stock right before placing the order to avoid overselling.
        foreach ($items as $item) {
            if ($item['quantity'] > $item['product']->stock) {
                return back()->withInput()->with('error', "Sorry, only {$item['product']->stock} of {$item['product']->name} left in stock.");
            }
        }

        $subtotal = $this->cart->subtotal();
        $shippingFee = $subtotal >= (float) config('app.store.free_shipping_threshold', 5000)
            ? 0
            : (float) (config('app.store.shipping_fee') ?? config('app.store.shipping_flat_fee', 200));
        $total = $subtotal + $shippingFee;

        $order = DB::transaction(function () use ($data, $items, $subtotal, $shippingFee, $total) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => Order::generateOrderNumber(),
                'status' => 'pending',
                'payment_method' => $data['payment_method'],
                'payment_status' => 'unpaid',
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'total' => $total,
                'customer_name' => $data['customer_name'],
                'customer_phone' => $data['customer_phone'],
                'customer_email' => $data['customer_email'] ?? null,
                'shipping_address' => $data['shipping_address'],
                'city' => $data['city'],
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'product_price' => $item['product']->final_price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                ]);

                $item['product']->decrement('stock', $item['quantity']);
            }

            return $order;
        });

        $this->cart->clear();

        return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully! We will contact you shortly to confirm.');
    }
}

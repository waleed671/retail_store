<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()->latest()->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        $order->load('items.product');

        return view('orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);
        abort_unless($order->canBeCancelled(), 400);

        $order->update(['status' => 'cancelled', 'cancelled_at' => now()]);

        foreach ($order->items as $item) {
            $item->product?->increment('stock', $item->quantity);
        }

        return back()->with('success', 'Order cancelled.');
    }
}

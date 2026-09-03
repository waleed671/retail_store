<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $items = Auth::user()->wishlists()->with('product')->latest()->get();

        return view('wishlist.index', compact('items'));
    }

    public function toggle(Product $product)
    {
        $existing = Wishlist::where('user_id', Auth::id())->where('product_id', $product->id)->first();

        if ($existing) {
            $existing->delete();
            $message = 'Removed from wishlist.';
        } else {
            Wishlist::create(['user_id' => Auth::id(), 'product_id' => $product->id]);
            $message = 'Added to wishlist.';
        }

        return back()->with('success', $message);
    }
}

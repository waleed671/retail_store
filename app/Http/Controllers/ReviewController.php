<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        Review::updateOrCreate(
            ['product_id' => $product->id, 'user_id' => Auth::id()],
            ['rating' => $data['rating'], 'comment' => $data['comment'] ?? null]
        );

        return back()->with('success', 'Thanks for your review!');
    }

    public function destroy(Review $review)
    {
        abort_unless($review->user_id === Auth::id(), 403);

        $review->delete();

        return back()->with('success', 'Review removed.');
    }
}

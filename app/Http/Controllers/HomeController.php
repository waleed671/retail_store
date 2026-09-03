<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Product::active()->featured()->inStock()->latest()->take(8)->get();
        $newArrivals = Product::active()->latest()->take(8)->get();
        $categories = Category::active()->whereNull('parent_id')->orderBy('sort_order')->take(8)->get();

        return view('home', compact('featured', 'newArrivals', 'categories'));
    }
}

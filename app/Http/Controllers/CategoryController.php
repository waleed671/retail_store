<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        abort_unless($category->is_active, 404);

        $products = $category->products()->active()->latest()->paginate(12);

        return view('categories.show', compact('category', 'products'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
use Illuminate\Http\Request;

class DiscountCodeController extends Controller
{
    public function index(Request $request)
    {
        $discountCodes = DiscountCode::query()
            ->when($request->search, fn ($q, $s) => $q->where('code', 'like', "%$s%"))
            ->when($request->type, fn ($q, $t) => $q->where('type', $t))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.discount-codes.index', compact('discountCodes'));
    }

    public function create()
    {
        return view('admin.discount-codes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'             => 'required|string|max:50|unique:discount_codes,code',
            'type'             => 'required|in:percent,fixed',
            'value'            => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses'         => 'nullable|integer|min:1',
            'expires_at'       => 'nullable|date|after:today',
            'is_active'        => 'boolean',
        ]);

        $data['code']      = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active', true);

        DiscountCode::create($data);

        return redirect()->route('admin.discount-codes.index')
            ->with('success', 'Discount code created successfully.');
    }

    public function edit(DiscountCode $discountCode)
    {
        return view('admin.discount-codes.edit', compact('discountCode'));
    }

    public function update(Request $request, DiscountCode $discountCode)
    {
        $data = $request->validate([
            'code'             => 'required|string|max:50|unique:discount_codes,code,' . $discountCode->id,
            'type'             => 'required|in:percent,fixed',
            'value'            => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses'         => 'nullable|integer|min:1',
            'expires_at'       => 'nullable|date',
            'is_active'        => 'boolean',
        ]);

        $data['code']      = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active');

        $discountCode->update($data);

        return redirect()->route('admin.discount-codes.index')
            ->with('success', 'Discount code updated successfully.');
    }

    public function destroy(DiscountCode $discountCode)
    {
        $discountCode->delete();

        return redirect()->route('admin.discount-codes.index')
            ->with('success', 'Discount code deleted.');
    }
}

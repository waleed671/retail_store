<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $vendors = Vendor::query()
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%$s%")
                ->orWhere('email', 'like', "%$s%")
                ->orWhere('city', 'like', "%$s%"))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.vendors.index', compact('vendors'));
    }

    public function create()
    {
        return view('admin.vendors.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'contact_person'  => 'nullable|string|max:255',
            'phone'           => 'nullable|string|max:50',
            'email'           => 'nullable|email|max:255',
            'address'         => 'nullable|string|max:500',
            'city'            => 'nullable|string|max:100',
            'opening_balance' => 'nullable|numeric|min:0',
            'notes'           => 'nullable|string',
            'is_active'       => 'boolean',
        ]);

        $data['is_active']       = $request->boolean('is_active', true);
        $data['opening_balance'] = $data['opening_balance'] ?? 0;

        Vendor::create($data);

        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor created successfully.');
    }

    public function show(Vendor $vendor)
    {
        $purchaseOrders = $vendor->purchaseOrders()
            ->latest()
            ->paginate(10, ['*'], 'po_page')
            ->withQueryString();

        $payments = $vendor->payments()
            ->with('recorder:id,name')
            ->latest('paid_at')
            ->paginate(10, ['*'], 'pay_page')
            ->withQueryString();

        return view('admin.vendors.show', compact('vendor', 'purchaseOrders', 'payments'));
    }

    public function edit(Vendor $vendor)
    {
        return view('admin.vendors.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'contact_person'  => 'nullable|string|max:255',
            'phone'           => 'nullable|string|max:50',
            'email'           => 'nullable|email|max:255',
            'address'         => 'nullable|string|max:500',
            'city'            => 'nullable|string|max:100',
            'opening_balance' => 'nullable|numeric|min:0',
            'notes'           => 'nullable|string',
            'is_active'       => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $vendor->update($data);

        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor updated successfully.');
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->delete();

        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor deleted.');
    }

    // ── Payments ───────────────────────────────────────────────────────────

    public function storePayment(Request $request, Vendor $vendor)
    {
        $request->validate([
            'amount'         => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bank_transfer,cheque',
            'reference'      => 'nullable|string|max:255',
            'notes'          => 'nullable|string|max:1000',
            'paid_at'        => 'required|date',
        ]);

        VendorPayment::create([
            'vendor_id'      => $vendor->id,
            'amount'         => $request->amount,
            'payment_method' => $request->payment_method,
            'reference'      => $request->reference,
            'notes'          => $request->notes,
            'paid_at'        => $request->paid_at,
            'recorded_by'    => Auth::id(),
        ]);

        return redirect()->route('admin.vendors.show', $vendor)
            ->with('success', 'Payment of Rs ' . number_format($request->amount) . ' recorded successfully.');
    }

    public function destroyPayment(Vendor $vendor, VendorPayment $payment)
    {
        abort_unless($payment->vendor_id === $vendor->id, 404);
        $payment->delete();

        return back()->with('success', 'Payment deleted.');
    }
}

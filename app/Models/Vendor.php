<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'contact_person', 'phone', 'email',
        'address', 'city', 'opening_balance', 'notes', 'is_active',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'is_active'       => 'boolean',
    ];

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function payments()
    {
        return $this->hasMany(VendorPayment::class);
    }

    /**
     * Total value of all received purchase orders.
     */
    public function totalPurchased(): float
    {
        return (float) $this->purchaseOrders()
            ->where('status', 'received')
            ->sum('total_amount');
    }

    /**
     * Total payments made to this vendor.
     */
    public function totalPaid(): float
    {
        return (float) $this->payments()->sum('amount');
    }

    /**
     * Current balance due:
     * opening_balance + received PO value − payments made
     */
    public function currentBalance(): float
    {
        return round(
            (float) $this->opening_balance + $this->totalPurchased() - $this->totalPaid(),
            2
        );
    }
}

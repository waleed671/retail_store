<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id', 'reference_number', 'status',
        'notes', 'total_amount', 'received_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'received_at'  => 'datetime',
    ];

    public static function generateReference(): string
    {
        do {
            $ref = 'PO-' . now()->format('ymd') . '-' . strtoupper(Str::random(5));
        } while (self::where('reference_number', $ref)->exists());

        return $ref;
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'from_warehouse_id', 'to_warehouse_id',
        'product_id', 'quantity', 'reference', 'notes', 'created_by',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'transfer'         => 'Transfer',
            'purchase_receipt' => 'Purchase Receipt',
            'sale'             => 'Sale',
            'adjustment'       => 'Adjustment',
            'return'           => 'Return',
            default            => ucfirst($this->type),
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'cashier_id', 'order_number', 'source', 'status', 'payment_method', 'payment_status',
        'subtotal', 'shipping_fee', 'discount_amount', 'total', 'paid_amount', 'change_amount',
        'payment_reference', 'customer_name', 'customer_phone', 'customer_email',
        'shipping_address', 'city', 'notes', 'cancelled_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'cancelled_at' => 'datetime',
    ];

    public static function generateOrderNumber(): string
    {
        do {
            $number = 'ORD-'.now()->format('ymd').'-'.strtoupper(Str::random(5));
        } while (self::where('order_number', $number)->exists());

        return $number;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopePos($query)
    {
        return $query->where('source', 'pos');
    }

    public function scopeOnline($query)
    {
        return $query->where('source', 'online');
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function statusLabel(): string
    {
        return ucfirst($this->status);
    }
}

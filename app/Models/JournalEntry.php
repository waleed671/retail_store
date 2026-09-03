<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JournalEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_number', 'entry_date', 'description',
        'reference_type', 'reference_id', 'created_by',
    ];

    protected $casts = [
        'entry_date' => 'date',
    ];

    public function lines()
    {
        return $this->hasMany(JournalEntryLine::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateVoucherNumber(): string
    {
        do {
            $number = 'JV-' . now()->format('ymd') . '-' . strtoupper(Str::random(5));
        } while (self::where('voucher_number', $number)->exists());

        return $number;
    }

    /**
     * Total debit side of this voucher (should equal total credit).
     */
    public function totalDebit(): float
    {
        return (float) $this->lines()->where('type', 'debit')->sum('amount');
    }

    public function totalCredit(): float
    {
        return (float) $this->lines()->where('type', 'credit')->sum('amount');
    }
}

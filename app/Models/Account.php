<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'type', 'description', 'is_system', 'is_active'];

    protected $casts = [
        'is_system' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Default account codes (used by auto-journal logic)
    const CASH_IN_HAND     = '1001';
    const BANK_ACCOUNT     = '1002';
    const ACCOUNTS_PAYABLE = '2001';
    const SALES_REVENUE    = '3001';
    const COST_OF_GOODS    = '4001';
    const GENERAL_EXPENSES = '4002';

    public function lines()
    {
        return $this->hasMany(JournalEntryLine::class);
    }

    /**
     * Net balance: sum of debits minus sum of credits.
     * For asset/expense accounts, positive = debit balance (normal).
     * For liability/income accounts, positive = credit balance (normal).
     */
    public function balance(): float
    {
        $debits  = (float) $this->lines()->where('type', 'debit')->sum('amount');
        $credits = (float) $this->lines()->where('type', 'credit')->sum('amount');

        return round($debits - $credits, 2);
    }

    public function getTypeBadgeAttribute(): string
    {
        return match($this->type) {
            'asset'     => 'bg-blue-50 text-blue-700',
            'liability' => 'bg-red-50 text-red-700',
            'equity'    => 'bg-purple-50 text-purple-700',
            'income'    => 'bg-green-50 text-green-700',
            'expense'   => 'bg-orange-50 text-orange-700',
            default     => 'bg-gray-50 text-gray-700',
        };
    }

    public static function findByCode(string $code): ?self
    {
        return self::where('code', $code)->first();
    }
}

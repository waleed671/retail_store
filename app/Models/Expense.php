<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'amount', 'category', 'description', 'expense_date',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'expense_date' => 'date',
    ];

    public static array $categories = [
        'rent'      => 'Rent',
        'utilities' => 'Utilities',
        'salaries'  => 'Salaries',
        'marketing' => 'Marketing',
        'misc'      => 'Miscellaneous',
    ];
}

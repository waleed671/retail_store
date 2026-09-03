<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            // Assets
            ['code' => '1001', 'name' => 'Cash in Hand',       'type' => 'asset',     'description' => 'Physical cash at counter/shop'],
            ['code' => '1002', 'name' => 'Bank Account',        'type' => 'asset',     'description' => 'Main business bank account'],
            ['code' => '1003', 'name' => 'Accounts Receivable', 'type' => 'asset',     'description' => 'Money owed by customers'],
            ['code' => '1004', 'name' => 'Inventory / Stock',   'type' => 'asset',     'description' => 'Value of goods in stock'],

            // Liabilities
            ['code' => '2001', 'name' => 'Accounts Payable',    'type' => 'liability', 'description' => 'Money owed to vendors/suppliers'],
            ['code' => '2002', 'name' => 'Tax Payable',         'type' => 'liability', 'description' => 'Taxes due to government'],

            // Equity
            ['code' => '3001', 'name' => "Owner's Capital",     'type' => 'equity',    'description' => 'Capital invested by owner'],
            ['code' => '3002', 'name' => 'Retained Earnings',   'type' => 'equity',    'description' => 'Accumulated profits'],

            // Income
            ['code' => '4001', 'name' => 'Sales Revenue',       'type' => 'income',    'description' => 'Revenue from product sales'],
            ['code' => '4002', 'name' => 'Other Income',        'type' => 'income',    'description' => 'Miscellaneous income'],

            // Expenses
            ['code' => '5001', 'name' => 'Cost of Goods Sold',  'type' => 'expense',   'description' => 'Direct cost of sold products'],
            ['code' => '5002', 'name' => 'Rent Expense',        'type' => 'expense',   'description' => 'Shop / warehouse rent'],
            ['code' => '5003', 'name' => 'Salary Expense',      'type' => 'expense',   'description' => 'Staff salaries'],
            ['code' => '5004', 'name' => 'Utility Expense',     'type' => 'expense',   'description' => 'Electricity, gas, internet'],
            ['code' => '5005', 'name' => 'Marketing Expense',   'type' => 'expense',   'description' => 'Advertising and promotions'],
            ['code' => '5006', 'name' => 'General Expense',     'type' => 'expense',   'description' => 'Miscellaneous operating expenses'],
        ];

        foreach ($accounts as $account) {
            Account::updateOrCreate(
                ['code' => $account['code']],
                array_merge($account, ['is_system' => true, 'is_active' => true])
            );
        }
    }
}

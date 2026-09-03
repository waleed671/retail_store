<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Use raw SQL for compatibility with MariaDB < 10.5.2
        DB::statement('ALTER TABLE `vendors` CHANGE `balance` `opening_balance` DECIMAL(12,2) NOT NULL DEFAULT 0');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE `vendors` CHANGE `opening_balance` `balance` DECIMAL(12,2) NOT NULL DEFAULT 0');
    }
};

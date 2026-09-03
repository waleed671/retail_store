<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('source', 20)->default('online')->after('order_number');
            $table->foreignId('cashier_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            $table->decimal('discount_amount', 12, 2)->default(0)->after('shipping_fee');
            $table->decimal('paid_amount', 12, 2)->default(0)->after('total');
            $table->decimal('change_amount', 12, 2)->default(0)->after('paid_amount');
            $table->string('payment_reference', 255)->nullable()->after('change_amount');
        });

        // Ensure payment_method accepts any POS methods (cash, jazzcash, easypaisa, card, bank_transfer, cod)
        DB::statement("ALTER TABLE orders MODIFY payment_method VARCHAR(50) NOT NULL DEFAULT 'cash'");
        // Ensure user_id can be nullable for guest walk-in counter sales
        DB::statement("ALTER TABLE orders MODIFY user_id BIGINT UNSIGNED NULL");
        // Ensure shipping_address and city can be nullable or have default for counter sales
        DB::statement("ALTER TABLE orders MODIFY shipping_address TEXT NULL");
        DB::statement("ALTER TABLE orders MODIFY city VARCHAR(100) NULL");
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['cashier_id']);
            $table->dropColumn([
                'source',
                'cashier_id',
                'discount_amount',
                'paid_amount',
                'change_amount',
                'payment_reference',
            ]);
        });
    }
};

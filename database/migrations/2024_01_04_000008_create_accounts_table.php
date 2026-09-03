<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();          // e.g. 1001, 2001
            $table->string('name');                    // e.g. Cash in Hand
            $table->enum('type', ['asset', 'liability', 'equity', 'income', 'expense']);
            $table->text('description')->nullable();
            $table->boolean('is_system')->default(false); // system accounts cannot be deleted
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};

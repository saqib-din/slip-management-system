<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('slips', function (Blueprint $table) {
            $table->string('tip', 255)->nullable()->change();
            $table->string('cash_trip', 255)->nullable()->change();
            $table->string('refund', 255)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('slips', function (Blueprint $table) {
            $table->decimal('tip', 10, 2)->nullable()->change();
            $table->decimal('cash_trip', 10, 2)->nullable()->change();
            $table->decimal('refund', 10, 2)->nullable()->change();
        });
    }
};

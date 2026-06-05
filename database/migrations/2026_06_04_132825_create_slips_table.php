<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slips', function (Blueprint $table) {

            $table->id();

            // Auto Generated Slip No
            $table->string('slip_no')->unique();

            // Basic Information
            $table->date('date');
            $table->string('site_no')->nullable();
            $table->string('time')->nullable();
            $table->string('lpo_no')->nullable();

            // Vehicle & Company
            $table->string('vehicle_no');
            $table->string('company');

            // Charges
            $table->decimal('tip', 12, 2)->default(0);
            $table->decimal('cash_trip', 12, 2)->default(0);
            $table->decimal('refund', 12, 2)->default(0);

            // Items
            $table->json('items')->nullable();

            // Receiver & Driver
            $table->string('receiver_name')->nullable();
            $table->string('driver')->nullable();

            // Remarks
            $table->text('remarks')->nullable();

            // User
            $table->string('created_by')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slips');
    }
};

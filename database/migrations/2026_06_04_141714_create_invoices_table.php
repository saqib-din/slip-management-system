<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {

            $table->id();

            // Invoice Info
            $table->string('invoice_no')->unique();
            $table->date('invoice_date');
            $table->time('invoice_time')->nullable();

            // Customer
            $table->string('customer_name');
            $table->string('vat_no')->nullable();

            // References
            $table->string('po_no')->nullable();
            $table->string('del_no')->nullable();
            $table->string('received_by')->nullable();

            // Items (JSON)
            $table->json('items')->nullable();

            /*
            Example:
            {
              material,
              unit,
              qty,
              rate,
              amount,
              tax,
              vat_amount,
              gross_amount
            }
            */

            // Totals
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('vat_total', 12, 2)->default(0);

            // 🔥 FINAL GROSS TOTAL
            $table->decimal('gross_total', 12, 2)->default(0);

            // Extra
            $table->text('amount_words')->nullable();

            // System
            $table->string('created_by')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};

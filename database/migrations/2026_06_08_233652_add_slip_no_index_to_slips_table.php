<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('slips', function (Blueprint $table) {
            $table->index('slip_no', 'slips_slip_no_index');
        });
    }

    public function down(): void
    {
        Schema::table('slips', function (Blueprint $table) {
            $table->dropIndex('slips_slip_no_index');
        });
    }
};

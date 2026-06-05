<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::table('slips', function (Blueprint $table) {
            $table->index('date',       'slips_date_index');
            $table->index('company',    'slips_company_index');
            $table->index('site_no',    'slips_site_no_index');
            $table->index('vehicle_no', 'slips_vehicle_no_index');
        });
    }

    public function down(): void
    {
        Schema::table('slips', function (Blueprint $table) {
            $table->dropIndex('slips_date_index');
            $table->dropIndex('slips_company_index');
            $table->dropIndex('slips_site_no_index');
            $table->dropIndex('slips_vehicle_no_index');
        });
    }
};

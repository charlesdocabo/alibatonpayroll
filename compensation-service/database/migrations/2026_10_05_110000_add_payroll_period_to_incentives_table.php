<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('incentives', function (Blueprint $table) {
            $table->string('payroll_period', 20)->nullable()->after('status');
            $table->string('approved_by', 100)->nullable()->after('payroll_period');
            $table->unsignedBigInteger('trip_id')->nullable()->after('approved_by');
            $table->string('trip_reference', 50)->nullable()->after('trip_id');
        });
    }

    public function down(): void
    {
        Schema::table('incentives', function (Blueprint $table) {
            $table->dropColumn(['payroll_period', 'approved_by', 'trip_id', 'trip_reference']);
        });
    }
};

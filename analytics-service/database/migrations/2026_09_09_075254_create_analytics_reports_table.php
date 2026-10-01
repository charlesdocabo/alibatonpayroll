<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics_reports', function (Blueprint $table) {
            $table->id();

            $table->string('report_type', 100);

            $table->integer('total_employees')->default(0);
            $table->decimal('total_payroll', 14, 2)->default(0);
            $table->decimal('total_benefits', 14, 2)->default(0);
            $table->decimal('total_claims', 14, 2)->default(0);
            $table->decimal('total_incentives', 14, 2)->default(0);

            $table->integer('active_employees')->default(0);
            $table->integer('pending_claims')->default(0);

            $table->date('report_date');

            $table->timestamps();

            $table->index('report_type');
            $table->index('report_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_reports');
    }
};
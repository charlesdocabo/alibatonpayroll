<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_disputes', function (Blueprint $table) {
            $table->id();

            // References to records in the Payroll service
            $table->unsignedBigInteger('payroll_id');
            $table->string('employee_id', 50);

            // Dispute details
            $table->string('dispute_type', 50);
            $table->string('subject', 150);
            $table->text('description');
            $table->decimal('claimed_amount', 12, 2)->nullable();

            // Review workflow
            $table->string('status', 30)->default('pending');
            $table->text('hr_response')->nullable();
            $table->string('reviewed_by', 100)->nullable();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            // Search indexes
            $table->index('payroll_id');
            $table->index('employee_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_disputes');
    }
};

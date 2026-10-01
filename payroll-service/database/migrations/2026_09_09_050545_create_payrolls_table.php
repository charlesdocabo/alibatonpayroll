<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();

            // Employee Service reference
            // No foreign key because this is a separate microservice database.
            $table->string('employee_id', 50);

            // Salary information
            $table->decimal('basic_salary', 12, 2);
            $table->decimal('overtime_hours', 8, 2)->default(0);
            $table->decimal('overtime_pay', 12, 2)->default(0);
            $table->decimal('allowances', 12, 2)->default(0);

            // Government deductions
            $table->decimal('sss_deduction', 12, 2)->default(0);
            $table->decimal('philhealth_deduction', 12, 2)->default(0);
            $table->decimal('pagibig_deduction', 12, 2)->default(0);

            // Other deductions
            $table->decimal('other_deductions', 12, 2)->default(0);

            // Payroll totals
            $table->decimal('gross_pay', 12, 2)->default(0);
            $table->decimal('total_deductions', 12, 2)->default(0);
            $table->decimal('net_pay', 12, 2)->default(0);

            // Payroll date
            $table->date('pay_date');

            $table->timestamps();

            // Helps search payroll records by employee.
            $table->index('employee_id');
            $table->index('pay_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('benefits', function (Blueprint $table) {
            $table->id();

            // Employee Service reference
            // No foreign key because this is a separate microservice database.
            $table->string('employee_id', 50);

            // Benefit information
            $table->string('benefit_type');
            $table->string('provider')->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // Status
            $table->enum('status', [
                'active',
                'inactive',
                'pending'
            ])->default('active');

            $table->text('description')->nullable();

            $table->timestamps();

            $table->index('employee_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('benefits');
    }
};
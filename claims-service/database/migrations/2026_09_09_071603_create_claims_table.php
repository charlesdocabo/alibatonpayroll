<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->id();

            // Logical reference to Employee Service
            $table->string('employee_id', 50);

            $table->string('claim_type', 100);
            $table->text('description')->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->date('claim_date');

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'paid'
            ])->default('pending');

            $table->timestamps();

            $table->index('employee_id');
            $table->index('status');
            $table->index('claim_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
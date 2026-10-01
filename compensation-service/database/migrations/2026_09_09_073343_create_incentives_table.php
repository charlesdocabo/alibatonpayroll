<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incentives', function (Blueprint $table) {
            $table->id();

            // Logical reference to Employee Service
            $table->string('employee_id', 50);

            $table->string('incentive_type', 100);
            $table->text('description')->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->date('incentive_date');

            $table->enum('status', [
                'pending',
                'approved',
                'released',
                'cancelled'
            ])->default('pending');

            $table->timestamps();

            $table->index('employee_id');
            $table->index('status');
            $table->index('incentive_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incentives');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('petty_cash_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('petty_cash_fund_id')
                ->constrained('petty_cash_funds')
                ->restrictOnDelete();

            $table->foreignId('claim_id')
                ->nullable()
                ->constrained('claims')
                ->restrictOnDelete();

            $table->enum('transaction_type', [
                'initial_fund',
                'fund_added',
                'claim_payment',
                'adjustment'
            ]);

            $table->enum('direction', ['in', 'out']);
            $table->decimal('amount', 12, 2);
            $table->decimal('balance_after', 12, 2);
            $table->string('reference_number', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('performed_by', 100)->nullable();
            $table->timestamps();

            $table->index(['transaction_type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('petty_cash_transactions');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->string('approved_by', 100)->nullable()->after('status');
            $table->text('approval_notes')->nullable()->after('approved_by');
            $table->timestamp('approved_at')->nullable()->after('approval_notes');
            $table->text('return_reason')->nullable()->after('approved_at');
            $table->string('receipt_path', 500)->nullable()->after('return_reason');
        });
    }

    public function down(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->dropColumn(['approved_by', 'approval_notes', 'approved_at', 'return_reason', 'receipt_path']);
        });
    }
};

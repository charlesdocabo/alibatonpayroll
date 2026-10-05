<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('benefits', function (Blueprint $table) {
            if (!Schema::hasColumn('benefits', 'coverage')) {
                $table->decimal('coverage', 12, 2)->nullable()->after('provider');
            }
            if (!Schema::hasColumn('benefits', 'membership_number')) {
                $table->string('membership_number', 100)->nullable()->after('coverage');
            }
        });
    }

    public function down(): void
    {
        Schema::table('benefits', function (Blueprint $table) {
            if (Schema::hasColumn('benefits', 'coverage')) {
                $table->dropColumn('coverage');
            }
            if (Schema::hasColumn('benefits', 'membership_number')) {
                $table->dropColumn('membership_number');
            }
        });
    }
};

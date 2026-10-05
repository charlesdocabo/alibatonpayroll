<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salary_grades', function (Blueprint $table) {
            $table->date('effective_date')->nullable()->after('maximum_salary');
        });
    }

    public function down(): void
    {
        Schema::table('salary_grades', function (Blueprint $table) {
            $table->dropColumn('effective_date');
        });
    }
};

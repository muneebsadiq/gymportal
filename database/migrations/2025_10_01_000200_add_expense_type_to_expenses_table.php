<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->string('expense_type')->nullable()->after('category');
            $table->foreignId('coach_id')->nullable()->after('expense_type')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['coach_id']);
            $table->dropColumn(['expense_type', 'coach_id']);
        });
    }
};

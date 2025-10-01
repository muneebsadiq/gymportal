<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coaches', function (Blueprint $table) {
            $table->decimal('salary', 10, 2)->nullable()->after('status');
            $table->decimal('commission_rate', 5, 2)->nullable()->default(0)->after('salary'); // percentage
        });
    }

    public function down(): void
    {
        Schema::table('coaches', function (Blueprint $table) {
            $table->dropColumn(['salary', 'commission_rate']);
        });
    }
};

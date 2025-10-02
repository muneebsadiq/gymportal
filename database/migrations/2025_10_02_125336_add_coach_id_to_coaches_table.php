<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('coaches', function (Blueprint $table) {
            $table->string('coach_id')->unique()->nullable()->after('id');
        });
        
        // Generate coach_id for existing coaches
        $coaches = \App\Models\Coach::whereNull('coach_id')->get();
        foreach ($coaches as $coach) {
            $coach->coach_id = 'TRN' . str_pad($coach->id, 4, '0', STR_PAD_LEFT);
            $coach->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coaches', function (Blueprint $table) {
            $table->dropColumn('coach_id');
        });
    }
};

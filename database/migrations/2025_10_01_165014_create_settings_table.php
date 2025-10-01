<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('gym_name')->default('Fitness Gym');
            $table->string('gym_email')->nullable();
            $table->string('gym_phone')->nullable();
            $table->text('gym_address')->nullable();
            $table->string('currency')->default('PKR');
            $table->string('currency_symbol')->default('Rs');
            $table->string('timezone')->default('Asia/Karachi');
            $table->time('opening_time')->default('06:00:00');
            $table->time('closing_time')->default('22:00:00');
            $table->json('working_days')->nullable(); // ['monday', 'tuesday', etc.]
            $table->string('logo')->nullable();
            $table->text('about')->nullable();
            $table->timestamps();
        });
        
        // Insert default settings
        DB::table('settings')->insert([
            'gym_name' => 'Fitness Gym',
            'currency' => 'PKR',
            'currency_symbol' => 'Rs',
            'timezone' => 'Asia/Karachi',
            'opening_time' => '06:00:00',
            'closing_time' => '22:00:00',
            'working_days' => json_encode(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

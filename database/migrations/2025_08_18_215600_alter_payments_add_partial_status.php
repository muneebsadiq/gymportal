<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Add 'partial' to status enum
        DB::statement("ALTER TABLE `payments` MODIFY `status` ENUM('paid','pending','partial','overdue','cancelled') NOT NULL DEFAULT 'paid'");
    }

    public function down(): void
    {
        // Revert to previous enum without 'partial'
        DB::statement("ALTER TABLE `payments` MODIFY `status` ENUM('paid','pending','overdue','cancelled') NOT NULL DEFAULT 'paid'");
    }
};

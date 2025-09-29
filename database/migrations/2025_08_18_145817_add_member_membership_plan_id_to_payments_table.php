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
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'member_membership_plan_id')) {
                $table->unsignedBigInteger('member_membership_plan_id')->nullable()->after('membership_plan_id');
                $table->index('member_membership_plan_id', 'payments_member_membership_plan_id_index');
                $table->foreign('member_membership_plan_id', 'payments_member_membership_plan_id_foreign')
                    ->references('id')->on('member_membership_plan')
                    ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'member_membership_plan_id')) {
                $table->dropForeign('payments_member_membership_plan_id_foreign');
                $table->dropIndex('payments_member_membership_plan_id_index');
                $table->dropColumn('member_membership_plan_id');
            }
        });
    }
};

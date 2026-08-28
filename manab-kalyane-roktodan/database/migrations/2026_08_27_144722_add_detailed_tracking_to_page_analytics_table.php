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
        Schema::table('page_analytics', function (Blueprint $table) {
            $table->string('tracking_id')->nullable()->after('id');
            $table->foreignId('user_id')->nullable()->after('tracking_id')->constrained()->nullOnDelete();
            $table->string('user_name')->nullable()->after('user_id');
            $table->string('action_type')->default('page_view')->after('referrer');
            $table->text('target_details')->nullable()->after('action_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_analytics', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['tracking_id', 'user_id', 'user_name', 'action_type', 'target_details']);
        });
    }
};

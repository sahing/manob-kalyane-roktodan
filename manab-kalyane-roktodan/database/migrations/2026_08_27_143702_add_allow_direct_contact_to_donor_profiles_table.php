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
        Schema::table('donor_profiles', function (Blueprint $table) {
            $table->boolean('allow_direct_contact')->default(true)->after('availability_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donor_profiles', function (Blueprint $table) {
            $table->dropColumn('allow_direct_contact');
        });
    }
};

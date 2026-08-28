<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donor_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('donor_profiles', 'donor_code')) {
                $table->string('donor_code')->nullable()->unique()->after('user_id');
            }
            if (!Schema::hasColumn('donor_profiles', 'donor_badge')) {
                $table->string('donor_badge')->default('Voluntary Life Saver')->after('donor_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('donor_profiles', function (Blueprint $table) {
            $table->dropColumn(['donor_code', 'donor_badge']);
        });
    }
};

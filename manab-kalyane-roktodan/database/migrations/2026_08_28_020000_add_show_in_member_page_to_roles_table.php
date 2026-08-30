<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('roles', 'show_in_member_page')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->boolean('show_in_member_page')->default(false)->after('is_system');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('roles', 'show_in_member_page')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->dropColumn('show_in_member_page');
            });
        }
    }
};

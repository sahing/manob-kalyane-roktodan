<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('assigned_email')->nullable()->after('email');
            $table->string('assigned_email_password')->nullable()->after('assigned_email');
            $table->string('assigned_email_login_url')->nullable()->default('https://webmail.mabia.in')->after('assigned_email_password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['assigned_email', 'assigned_email_password', 'assigned_email_login_url']);
        });
    }
};

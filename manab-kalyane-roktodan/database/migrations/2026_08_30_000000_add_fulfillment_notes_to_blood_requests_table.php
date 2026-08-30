<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blood_requests', function (Blueprint $table) {
            $table->text('fulfillment_notes')->nullable()->after('notes');
            $table->string('fulfilled_by_donor')->nullable()->after('fulfillment_notes');
        });
    }

    public function down(): void
    {
        Schema::table('blood_requests', function (Blueprint $table) {
            $table->dropColumn(['fulfillment_notes', 'fulfilled_by_donor']);
        });
    }
};

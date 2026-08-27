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
        // 1. Donor Profiles
        Schema::create('donor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('blood_group', 10);
            $table->enum('availability_status', ['available', 'unavailable'])->default('available');
            $table->enum('donor_type', ['regular', 'emergency'])->default('regular');
            $table->date('last_donation_date')->nullable();
            $table->string('village')->nullable();
            $table->string('block')->default('Bhagwangola-I');
            $table->string('district')->default('Murshidabad');
            $table->text('medical_notes')->nullable();
            $table->timestamps();
        });

        // 2. Blood Requests
        Schema::create('blood_requests', function (Blueprint $table) {
            $table->id();
            $table->string('patient_name');
            $table->string('blood_group', 10);
            $table->integer('units_required')->default(1);
            $table->string('hospital_name')->nullable();
            $table->string('location');
            $table->string('contact_number');
            $table->enum('status', ['pending', 'fulfilled', 'cancelled'])->default('pending');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3. Donations History
        Schema::create('donations_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('donation_date');
            $table->string('location')->nullable();
            $table->string('certificate_id')->nullable()->unique();
            $table->string('certificate_path')->nullable();
            $table->timestamps();
        });

        // 4. Members (Committee / Board)
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role_title');
            $table->string('phone')->nullable();
            $table->string('photo_url')->nullable();
            $table->text('bio')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 5. Donation Pledges / Payments
        Schema::create('donation_pledges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('donor_name');
            $table->string('phone')->nullable();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_type', ['one_time', 'weekly', 'monthly'])->default('one_time');
            $table->string('transaction_id')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 6. Hero Slides
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('subtitle')->nullable();
            $table->string('image_url');
            $table->string('button_text')->nullable();
            $table->string('button_link')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 7. Gallery
        Schema::create('gallery', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image_url');
            $table->string('category')->default('camps');
            $table->text('description')->nullable();
            $table->date('event_date')->nullable();
            $table->timestamps();
        });

        // 8. Visitor Inquiries
        Schema::create('visitor_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('purpose')->default('view_donor_contact');
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });

        // 9. Site Content Key-Value Settings
        Schema::create('site_content', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_content');
        Schema::dropIfExists('visitor_inquiries');
        Schema::dropIfExists('gallery');
        Schema::dropIfExists('hero_slides');
        Schema::dropIfExists('donation_pledges');
        Schema::dropIfExists('members');
        Schema::dropIfExists('donations_history');
        Schema::dropIfExists('blood_requests');
        Schema::dropIfExists('donor_profiles');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add counter columns to donor_stories if not present
        Schema::table('donor_stories', function (Blueprint $table) {
            if (!Schema::hasColumn('donor_stories', 'likes_count')) {
                $table->unsignedInteger('likes_count')->default(0)->after('photo_url');
            }
            if (!Schema::hasColumn('donor_stories', 'comments_count')) {
                $table->unsignedInteger('comments_count')->default(0)->after('likes_count');
            }
            if (!Schema::hasColumn('donor_stories', 'shares_count')) {
                $table->unsignedInteger('shares_count')->default(0)->after('comments_count');
            }
        });

        // 2. Create Likes table
        Schema::create('donor_story_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('story_id')->constrained('donor_stories')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });

        // 3. Create Comments table
        Schema::create('donor_story_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('story_id')->constrained('donor_stories')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('user_name');
            $table->text('comment');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donor_story_comments');
        Schema::dropIfExists('donor_story_likes');
        Schema::table('donor_stories', function (Blueprint $table) {
            $table->dropColumn(['likes_count', 'comments_count', 'shares_count']);
        });
    }
};

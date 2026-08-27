<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonorStory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'donor_name',
        'blood_group',
        'title',
        'experience',
        'photo_url',
        'location',
        'status',
        'likes_count',
        'comments_count',
        'shares_count',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(DonorStoryLike::class, 'story_id');
    }

    public function comments()
    {
        return $this->hasMany(DonorStoryComment::class, 'story_id')->latest();
    }

    public function isLikedByUser($userId = null, $ip = null): bool
    {
        if ($userId) {
            return $this->likes()->where('user_id', $userId)->exists();
        }
        if ($ip) {
            return $this->likes()->where('ip_address', $ip)->exists();
        }
        return false;
    }
}

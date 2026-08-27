<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonorStoryComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'story_id',
        'user_id',
        'user_name',
        'comment',
    ];

    public function story()
    {
        return $this->belongsTo(DonorStory::class, 'story_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

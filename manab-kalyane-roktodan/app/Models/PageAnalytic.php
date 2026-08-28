<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracking_id',
        'user_id',
        'user_name',
        'url',
        'path',
        'ip_address',
        'user_agent',
        'device_type',
        'referrer',
        'action_type',
        'target_details',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'path',
        'ip_address',
        'user_agent',
        'device_type',
        'referrer',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $table = 'gallery';

    protected $fillable = [
        'title',
        'image_url',
        'category',
        'description',
        'event_date',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];
}

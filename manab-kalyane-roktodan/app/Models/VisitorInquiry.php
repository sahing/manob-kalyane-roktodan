<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorInquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'purpose',
        'ip_address',
        'session_id',
    ];
}

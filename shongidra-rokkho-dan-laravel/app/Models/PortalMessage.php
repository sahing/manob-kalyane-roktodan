<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortalMessage extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'sender_name',
        'sender_phone',
        'receiver_name',
        'receiver_phone',
        'blood_request_id',
        'message',
        'is_read',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function bloodRequest()
    {
        return $this->belongsTo(BloodRequest::class, 'blood_request_id');
    }
}

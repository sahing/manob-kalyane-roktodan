<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationPledge extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'donor_name',
        'phone',
        'amount',
        'payment_type',
        'transaction_id',
        'status',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

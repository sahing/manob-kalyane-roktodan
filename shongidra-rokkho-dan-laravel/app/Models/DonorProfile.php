<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DonorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'donor_code',
        'blood_group',
        'availability_status',
        'donor_type',
        'donor_badge',
        'last_donation_date',
        'village',
        'block',
        'district',
        'medical_notes',
    ];

    protected $casts = [
        'last_donation_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getIsEligibleAttribute(): bool
    {
        if (!$this->last_donation_date) {
            return true;
        }
        return Carbon::parse($this->last_donation_date)->diffInDays(now()) >= 90;
    }

    public function getNextEligibleDateAttribute()
    {
        if (!$this->last_donation_date) {
            return now();
        }
        return Carbon::parse($this->last_donation_date)->addDays(90);
    }

    public static function generateUniqueDonorCode($id)
    {
        return 'MKRD-' . str_pad($id, 5, '0', STR_PAD_LEFT);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($profile) {
            if (empty($profile->donor_code)) {
                $profile->donor_code = self::generateUniqueDonorCode($profile->id);
                $profile->saveQuietly();
            }
        });
    }
}

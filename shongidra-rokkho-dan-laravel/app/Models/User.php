<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'avatar_url',
        'role',
        'password',
        'referral_code',
        'referred_by_id',
        'loyalty_points',
        'assigned_email',
        'assigned_email_password',
        'assigned_email_login_url',
    ];

    public function donorProfile()
    {
        return $this->hasOne(DonorProfile::class);
    }

    public function donations()
    {
        return $this->hasMany(DonationHistory::class);
    }

    public function bloodRequests()
    {
        return $this->hasMany(BloodRequest::class, 'created_by');
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by_id');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by_id');
    }

    public function roleModel()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function getRoleObject()
    {
        if ($this->roleModel) {
            return $this->roleModel;
        }
        Role::ensureDefaultRolesExist();
        return Role::where('name', $this->role)->first();
    }

    public function hasRole(string $roleName): bool
    {
        if ($this->role === $roleName) {
            return true;
        }
        $r = $this->getRoleObject();
        return $r && $r->name === $roleName;
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->role === 'admin') {
            return true;
        }
        $r = $this->getRoleObject();
        return $r ? $r->hasPermission($permission) : false;
    }

    public function canAccessAdmin(): bool
    {
        if ($this->role === 'admin') {
            return true;
        }
        $r = $this->getRoleObject();
        if (!$r || empty($r->permissions)) {
            return false;
        }
        return count(array_intersect($r->permissions, array_keys(Role::defaultPermissions()))) > 0;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->hasPermission('manage_users');
    }

    public function isMember(): bool
    {
        return in_array($this->role, ['admin', 'member']) || $this->canAccessAdmin();
    }

    public function getLoyaltyRankAttribute(): string
    {
        $points = $this->loyalty_points;
        if ($points >= 500) {
            return '🏆 Platinum Life Champion';
        } elseif ($points >= 250) {
            return '🥇 Gold Ambassador';
        } elseif ($points >= 100) {
            return '🥈 Silver Patron';
        } elseif ($points >= 50) {
            return '🥉 Bronze Supporter';
        }
        return '🩸 Member Donor';
    }

    public static function generateReferralCode($id)
    {
        return 'MKRD-REF-' . str_pad($id, 4, '0', STR_PAD_LEFT);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            if (empty($user->referral_code)) {
                $user->referral_code = self::generateReferralCode($user->id);
                $user->saveQuietly();
            }
        });
    }

    public function notifications()
    {
        return $this->hasMany(UserNotification::class)->latest();
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

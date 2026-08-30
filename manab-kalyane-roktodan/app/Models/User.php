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
        'role_id',
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
        Role::ensureDefaultRolesExist();

        $currentRoleName = strtolower(trim($this->role ?? ''));

        // 1. Check loaded relation first if matching
        if ($this->role_id && $this->relationLoaded('roleModel') && $this->roleModel) {
            if (!$currentRoleName || strtolower($this->roleModel->name) === $currentRoleName) {
                return $this->roleModel;
            }
        }

        // 2. Lookup by role name string
        if ($currentRoleName) {
            $r = Role::where('name', $currentRoleName)->first();
            if ($r) {
                return $r;
            }
        }

        // 3. Fallback: Lookup by role_id
        if ($this->role_id) {
            $r = Role::find($this->role_id);
            if ($r) {
                return $r;
            }
        }

        // 4. Default fallback to 'donor' role
        return Role::where('name', 'donor')->first();
    }

    public function hasRole(string $roleName): bool
    {
        $currentRole = strtolower(trim($this->role ?? ''));
        $targetRole = strtolower(trim($roleName));
        if ($currentRole === $targetRole) {
            return true;
        }
        $r = $this->getRoleObject();
        return $r && strtolower($r->name) === $targetRole;
    }

    public function hasPermission(string $permission): bool
    {
        $currentRole = strtolower(trim($this->role ?? ''));
        if ($currentRole === 'admin') {
            return true;
        }
        $r = $this->getRoleObject();
        if (!$r) {
            return false;
        }
        if (strtolower($r->name) === 'admin') {
            return true;
        }
        return $r->hasPermission($permission);
    }

    public function canAccessAdmin(): bool
    {
        $currentRole = strtolower(trim($this->role ?? ''));
        if ($currentRole === 'admin') {
            return true;
        }
        $r = $this->getRoleObject();
        if (!$r) {
            return false;
        }
        if (strtolower($r->name) === 'admin') {
            return true;
        }
        if (empty($r->permissions)) {
            return false;
        }
        return count(array_intersect($r->permissions, array_keys(Role::defaultPermissions()))) > 0;
    }

    public function isAdmin(): bool
    {
        return strtolower(trim($this->role ?? '')) === 'admin' || $this->hasPermission('manage_users');
    }

    public function isMember(): bool
    {
        return in_array(strtolower(trim($this->role ?? '')), ['admin', 'member']) || $this->canAccessAdmin();
    }

    public function canManageBloodRequest(BloodRequest $bloodRequest): bool
    {
        if ($this->isAdmin() || $this->isMember() || $this->canAccessAdmin()) {
            return true;
        }
        return (int)$this->id === (int)$bloodRequest->created_by;
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

    /**
     * Automatically upgrade user role upon financial pledge or blood donation.
     */
    public function upgradeRoleAfterContribution(string $type = 'donation')
    {
        $currentRole = strtolower(trim($this->role ?? 'user'));

        if (in_array($currentRole, ['user', 'visitor', 'guest', ''])) {
            $donorRole = Role::where('name', 'donor')->first();
            
            $this->update([
                'role' => 'donor',
                'role_id' => $donorRole?->id,
            ]);

            UserNotification::create([
                'user_id' => $this->id,
                'title' => '🎉 Account Upgraded to Verified Donor!',
                'message' => "Thank you for your {$type}! Your account has been upgraded to Verified Voluntary Donor. You have unlocked VIP donor badges and search priority.",
                'type' => 'system',
                'action_url' => route('dashboard'),
            ]);
        }
    }

    public static function generateReferralCode($id)
    {
        return 'MKRD-REF-' . str_pad($id, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Sync committee member status when user role changes.
     */
    public function syncCommitteeMemberStatus()
    {
        $roleName = strtolower(trim($this->role ?? 'user'));
        $roleObj = $this->getRoleObject();
        $label = $roleObj?->label ?? ucfirst(str_replace('_', ' ', $roleName));

        // Roles configured to appear on /members page
        $committeeRoles = Role::where('show_in_member_page', true)->pluck('name')->toArray();

        $existingMember = Member::where('phone', $this->phone)
            ->orWhere('name', $this->name)
            ->first();

        if (in_array($roleName, $committeeRoles)) {
            $roleTitle = match($roleName) {
                'core_member' => 'Executive Core Member',
                'member' => 'Committee Member',
                'admin' => 'Administrator & Governing Lead',
                'finance_manager' => 'Finance & Pledge Coordinator',
                'blood_coordinator' => 'Emergency Blood Coordinator',
                'moderator' => 'Operations Moderator',
                default => $label
            };

            if ($existingMember) {
                $existingMember->update([
                    'name' => $this->name,
                    'role_title' => $roleTitle,
                    'phone' => $this->phone,
                    'is_active' => true,
                ]);
            } else {
                Member::create([
                    'name' => $this->name,
                    'role_title' => $roleTitle,
                    'phone' => $this->phone,
                    'sort_order' => Member::count() + 1,
                    'is_active' => true,
                ]);
            }
        } else {
            if ($existingMember) {
                $existingMember->update([
                    'is_active' => false,
                ]);
            }
        }
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($user) {
            Role::ensureDefaultRolesExist();

            if ($user->role) {
                $user->role = strtolower(trim($user->role));
            }

            if ($user->isDirty('role') && !$user->isDirty('role_id')) {
                $roleObj = Role::where('name', $user->role)->first();
                if ($roleObj) {
                    $user->role_id = $roleObj->id;
                }
            } elseif ($user->isDirty('role_id') && !$user->isDirty('role')) {
                $roleObj = Role::find($user->role_id);
                if ($roleObj) {
                    $user->role = $roleObj->name;
                }
            } elseif ($user->role_id) {
                $roleObj = Role::find($user->role_id);
                if ($roleObj && strtolower($roleObj->name) !== strtolower($user->role)) {
                    $matchingRole = Role::where('name', $user->role)->first();
                    if ($matchingRole) {
                        $user->role_id = $matchingRole->id;
                    } else {
                        $user->role = $roleObj->name;
                    }
                }
            }
        });

        static::saved(function ($user) {
            $user->unsetRelation('roleModel');
            $user->syncCommitteeMemberStatus();
            if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::id() === $user->id) {
                \Illuminate\Support\Facades\Auth::setUser($user->fresh(['roleModel']));
            }
        });

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

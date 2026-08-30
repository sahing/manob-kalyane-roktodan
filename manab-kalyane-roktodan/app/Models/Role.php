<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'label',
        'description',
        'permissions',
        'is_system',
        'show_in_member_page',
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_system' => 'boolean',
        'show_in_member_page' => 'boolean',
    ];

    public static function defaultPermissions(): array
    {
        return [
            'manage_users' => 'Manage Users & Assign Roles',
            'manage_finances' => 'Manage Financial Pledges & Cash Records',
            'manage_requests' => 'Manage Blood Requests & Verifications',
            'manage_content' => 'Manage Blogs, Stories, Gallery & SEO',
            'send_broadcasts' => 'Send Bulk Notifications & Reminders',
            'view_analytics' => 'View Page Analytics & Visitor Audit Logs',
            'record_donations' => 'Record Official Blood Donations',
        ];
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public static function ensureDefaultRolesExist(): void
    {
        $allPermissions = array_keys(self::defaultPermissions());

        $defaults = [
            [
                'name' => 'admin',
                'label' => 'Super Administrator',
                'description' => 'Full administrative access across all modules, permissions, and settings.',
                'permissions' => $allPermissions,
                'is_system' => true,
                'show_in_member_page' => true,
            ],
            [
                'name' => 'moderator',
                'label' => 'Content & Member Moderator',
                'description' => 'Manages user accounts, blood requests, blogs, stories, and notifications.',
                'permissions' => ['manage_users', 'manage_requests', 'manage_content', 'send_broadcasts', 'record_donations'],
                'is_system' => false,
                'show_in_member_page' => true,
            ],
            [
                'name' => 'finance_manager',
                'label' => 'Finance & Pledge Manager',
                'description' => 'Tracks financial donations, verifies UPI/Cash pledges, and manages society funds.',
                'permissions' => ['manage_finances', 'send_broadcasts', 'view_analytics'],
                'is_system' => false,
                'show_in_member_page' => true,
            ],
            [
                'name' => 'blood_coordinator',
                'label' => 'Blood Emergency Coordinator',
                'description' => 'Coordinates urgent blood requests, records donations, and broadcasts emergency calls.',
                'permissions' => ['manage_requests', 'send_broadcasts', 'record_donations'],
                'is_system' => false,
                'show_in_member_page' => true,
            ],
            [
                'name' => 'user',
                'label' => 'General Visitor / Supporter',
                'description' => 'Default role for newly registered supporters and referral visitors.',
                'permissions' => [],
                'is_system' => true,
                'show_in_member_page' => false,
            ],
            [
                'name' => 'guest',
                'label' => 'Guest Requester',
                'description' => 'Guest user account created via non-login emergency blood request.',
                'permissions' => [],
                'is_system' => true,
                'show_in_member_page' => false,
            ],
            [
                'name' => 'donor',
                'label' => 'Verified Voluntary Donor',
                'description' => 'Active blood donor & financial contributor with VIP badges & search priority.',
                'permissions' => [],
                'is_system' => true,
                'show_in_member_page' => false,
            ],
            [
                'name' => 'member',
                'label' => 'Society Committee Member',
                'description' => 'Official society committee member.',
                'permissions' => [],
                'is_system' => false,
                'show_in_member_page' => true,
            ],
            [
                'name' => 'core_member',
                'label' => 'Executive Core Member',
                'description' => 'Core governing committee member with decision-making access.',
                'permissions' => [],
                'is_system' => false,
                'show_in_member_page' => true,
            ],
        ];

        foreach ($defaults as $data) {
            $role = self::where('name', $data['name'])->first();
            if (!$role) {
                self::create($data);
            } else {
                if ($role->show_in_member_page !== $data['show_in_member_page'] && !in_array($data['name'], ['user', 'donor'])) {
                    $role->update(['show_in_member_page' => $data['show_in_member_page']]);
                }
            }
        }
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->name === 'admin') {
            return true;
        }

        return is_array($this->permissions) && in_array($permission, $this->permissions);
    }
}

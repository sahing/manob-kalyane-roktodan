<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteMenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'location',
        'parent_id',
        'title',
        'url',
        'target',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(SiteMenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(SiteMenuItem::class, 'parent_id')->where('is_active', true)->orderBy('sort_order');
    }

    public static function ensureDefaultMenuItems(): void
    {
        if (self::count() > 0) {
            return;
        }

        $headerItems = [
            ['title' => 'Home', 'url' => '/', 'sort_order' => 1],
            ['title' => 'Find Donors', 'url' => '/search', 'sort_order' => 2],
            ['title' => 'Requests', 'url' => '/requests', 'sort_order' => 3],
            ['title' => 'Health & Blog', 'url' => '/blog', 'sort_order' => 4],
            ['title' => 'Donor Stories', 'url' => '/stories', 'sort_order' => 5],
            ['title' => 'Donate / Support', 'url' => '/donate', 'sort_order' => 6],
            ['title' => 'Gallery', 'url' => '/gallery', 'sort_order' => 7],
            ['title' => 'Committee', 'url' => '/members', 'sort_order' => 8],
        ];

        foreach ($headerItems as $item) {
            self::create(array_merge($item, ['location' => 'header', 'is_active' => true]));
        }

        $footerItems = [
            ['title' => 'About Society', 'url' => '/#about', 'sort_order' => 1],
            ['title' => 'Search Blood Donors', 'url' => '/search', 'sort_order' => 2],
            ['title' => 'Emergency Requests', 'url' => '/requests', 'sort_order' => 3],
            ['title' => 'Blog & Health', 'url' => '/blog', 'sort_order' => 4],
            ['title' => 'Donor Stories', 'url' => '/stories', 'sort_order' => 5],
            ['title' => 'Donate / Support Us', 'url' => '/donate', 'sort_order' => 6],
            ['title' => 'Board Members', 'url' => '/members', 'sort_order' => 7],
            ['title' => 'Blood Donation Camps', 'url' => '/gallery', 'sort_order' => 8],
            ['title' => 'Privacy Policy', 'url' => '/p/privacy-policy', 'sort_order' => 9],
            ['title' => 'Terms of Service', 'url' => '/p/terms-of-service', 'sort_order' => 10],
        ];

        foreach ($footerItems as $item) {
            self::create(array_merge($item, ['location' => 'footer', 'is_active' => true]));
        }
    }
}

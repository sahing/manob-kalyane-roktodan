<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'featured_image',
        'meta_title',
        'meta_description',
        'status',
        'sort_order',
    ];

    public static function ensureDefaultPages(): void
    {
        if (self::count() > 0) {
            return;
        }

        self::create([
            'title' => 'Privacy Policy',
            'slug' => 'privacy-policy',
            'content' => '<h2>Privacy Policy for Manab Kalyane Rokto Dan</h2><p>Your privacy is important to us. Donor phone numbers and personal contact information are kept strictly confidential unless direct donor contact permission is explicitly enabled by the registered voluntary donor.</p>',
            'status' => 'published',
            'meta_title' => 'Privacy Policy — Manab Kalyane Rokto Dan',
            'meta_description' => 'Official privacy policy and donor data protection policies of Manab Kalyane Rokto Dan voluntary blood network.',
        ]);

        self::create([
            'title' => 'Terms of Service',
            'slug' => 'terms-of-service',
            'content' => '<h2>Terms of Service</h2><p>Manab Kalyane Rokto Dan operates as a non-profit voluntary blood donor coordination network in Bhagwangola. We do not sell blood or charge money for blood matching.</p>',
            'status' => 'published',
            'meta_title' => 'Terms of Service — Manab Kalyane Rokto Dan',
            'meta_description' => 'Terms and operating guidelines for voluntary blood request postings and donor interactions.',
        ]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'title',
        'subtitle',
        'content',
        'button_text',
        'button_url',
        'secondary_button_text',
        'secondary_button_url',
        'image_url',
        'icon',
        'sort_order',
        'is_visible',
        'is_custom',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'is_custom' => 'boolean',
    ];

    public static function ensureDefaultSections(): void
    {
        if (self::count() > 0) {
            return;
        }

        $defaults = [
            [
                'key' => 'hero',
                'title' => 'Saving Lives in Bhagwangola Through Voluntary Blood Donation',
                'subtitle' => 'Connect directly with verified voluntary blood donors across Bhagwangola-I & II and Murshidabad in critical emergency situations.',
                'content' => 'Join our voluntary blood donation network, register as a donor, or post an urgent blood request for hospital patients.',
                'button_text' => '🩸 Search Blood Donors',
                'button_url' => '/search',
                'secondary_button_text' => '🚨 Request Blood',
                'secondary_button_url' => '/request-blood',
                'sort_order' => 1,
                'is_visible' => true,
                'is_custom' => false,
            ],
            [
                'key' => 'stats',
                'title' => 'Our Community Impact Statistics',
                'subtitle' => 'Real-time numbers of lives saved and active donors in our Bhagwangola network.',
                'content' => 'Total registered voluntary donors, successful blood donations recorded, and emergency requests fulfilled.',
                'sort_order' => 2,
                'is_visible' => true,
                'is_custom' => false,
            ],
            [
                'key' => 'why_donate',
                'title' => 'Why Donate Blood Voluntary?',
                'subtitle' => 'One blood donation can save up to 3 precious lives in emergency medical situations.',
                'content' => 'Voluntary blood donation builds a resilient health ecosystem. Regular donations refresh red blood cells and support emergency surgeries, thalassemia patients, and accident victims.',
                'button_text' => 'Become a Registered Donor',
                'button_url' => '/register',
                'sort_order' => 3,
                'is_visible' => true,
                'is_custom' => false,
            ],
            [
                'key' => 'stories',
                'title' => 'Heartwarming Donor & Survivor Stories',
                'subtitle' => 'Read real life-saving journeys shared by our heroes and grateful patient families.',
                'content' => 'Inspiring stories from Murshidabad blood donors and emergency surgery survivors.',
                'button_text' => 'View All Stories',
                'button_url' => '/stories',
                'sort_order' => 4,
                'is_visible' => true,
                'is_custom' => false,
            ],
            [
                'key' => 'cta',
                'title' => 'Support Our Emergency Blood Network Today',
                'subtitle' => 'Whether as a blood donor or financial supporter, your contribution directly saves lives.',
                'content' => 'Help us maintain free blood helpline operations, donor camp logistics, and patient assistance.',
                'button_text' => '💰 Donate Funds',
                'button_url' => '/pledge-financial',
                'secondary_button_text' => '📞 Contact Helpline',
                'secondary_button_url' => 'tel:919732733947',
                'sort_order' => 5,
                'is_visible' => true,
                'is_custom' => false,
            ],
        ];

        foreach ($defaults as $sec) {
            self::create($sec);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SeoSetting;
use App\Models\BlogPost;

class SeoSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'page_key' => 'home',
                'page_name' => 'Homepage',
                'meta_title' => 'Manab Kalyane Rokto Dan — Bhagwangola Voluntary Blood Network',
                'meta_description' => 'Find verified voluntary blood donors across Bhagwangola-I, Bhagwangola-II, Lalgola and Murshidabad District. Fast emergency blood search, 24/7 helpline.',
                'meta_keywords' => 'blood donor Bhagwangola, Murshidabad blood bank, emergency blood request, voluntary blood donation',
            ],
            [
                'page_key' => 'donors.search',
                'page_name' => 'Search Donors Page',
                'meta_title' => 'Search Voluntary Blood Donors — Bhagwangola & Murshidabad',
                'meta_description' => 'Filter voluntary blood donors by blood group (A+, B+, O+, AB+, etc.) and location across Bhagwangola and Murshidabad.',
                'meta_keywords' => 'search blood donors, A positive blood Bhagwangola, O negative donor Murshidabad',
            ],
            [
                'page_key' => 'requests.index',
                'page_name' => 'Emergency Blood Requests',
                'meta_title' => 'Live Emergency Blood Patient Requests — Murshidabad Hospitals',
                'meta_description' => 'View active patient emergency blood requests in Bhagwangola RH, Murshidabad Medical College and surrounding hospitals.',
                'meta_keywords' => 'emergency blood needed, hospital blood request Bhagwangola, patient blood alert',
            ],
            [
                'page_key' => 'donate',
                'page_name' => 'Donate & Support Page',
                'meta_title' => 'Support Our Voluntary Blood Service — Donate & Pledge',
                'meta_description' => 'Support Bhagwangola voluntary blood movement with UPI contributions. Help organize awareness drives and maintain 24/7 helpline.',
                'meta_keywords' => 'donate blood society, Bhagwangola blood helpline support, voluntary blood donation pledge',
            ],
            [
                'page_key' => 'stories.index',
                'page_name' => 'Donor Stories & Experiences',
                'meta_title' => 'Inspiring Voluntary Blood Donor Stories & Photos',
                'meta_description' => 'Read real experiences and view photos shared by voluntary blood donors from Bhagwangola and Murshidabad.',
                'meta_keywords' => 'donor stories, blood donation experience, Bhagwangola blood camp photos',
            ],
            [
                'page_key' => 'gallery',
                'page_name' => 'Gallery & Photo Archive',
                'meta_title' => 'Photo Gallery — Blood Donation Camps & Events',
                'meta_description' => 'Browse photos of voluntary blood donation camps, awareness rallies, and volunteer recognitions in Bhagwangola.',
                'meta_keywords' => 'blood donation photos, Bhagwangola rally, blood drive gallery',
            ],
            [
                'page_key' => 'members',
                'page_name' => 'Committee Members',
                'meta_title' => 'Board Members & Executive Committee — Manab Kalyane Rokto Dan',
                'meta_description' => 'Meet the committee members and organizers leading the voluntary blood movement in Bhagwangola, Murshidabad.',
                'meta_keywords' => 'committee members, blood society leaders, Bhagwangola volunteers',
            ],
            [
                'page_key' => 'blog.index',
                'page_name' => 'Blog & Awareness Articles',
                'meta_title' => 'Blood Donation Blog & Health Guidance — Bhagwangola',
                'meta_description' => 'Articles, health tips, eligibility guidelines, and myths vs facts about voluntary blood donation.',
                'meta_keywords' => 'blood donation tips, who can donate blood, blood health benefits',
            ],
        ];

        foreach ($pages as $p) {
            SeoSetting::updateOrCreate(['page_key' => $p['page_key']], $p);
        }

        // Seed Sample Blog Posts
        $samplePosts = [
            [
                'title' => 'Why Voluntary Blood Donation is Essential for Bhagwangola Community',
                'slug' => 'why-voluntary-blood-donation-is-essential-for-bhagwangola',
                'category' => 'Community Awareness',
                'excerpt' => 'Emergency medical cases require rapid access to blood. Learn how voluntary donors in Bhagwangola save lives without financial exploitation.',
                'content' => 'Voluntary blood donation is the cornerstone of a safe and reliable healthcare system. In rural and semi-urban hubs like Bhagwangola and surrounding Murshidabad blocks, immediate availability of blood for thalassaemia patients, accident victims, and maternal emergencies can mean the difference between life and death.

### Key Benefits of Donating Blood:
1. **Saves Lives**: A single pint of blood can save up to 3 lives.
2. **Promotes Cell Renewal**: Donating triggers body mechanisms to synthesize new blood cells.
3. **Cardiovascular Health**: Regular voluntary donation helps regulate iron balance in the bloodstream.

Join our platform today to register as a voluntary blood donor in Bhagwangola-I and Bhagwangola-II.',
                'author_name' => 'Dr. A. Mondal',
                'is_published' => true,
                'published_at' => now(),
                'views_count' => 142,
                'meta_title' => 'Why Voluntary Blood Donation is Essential for Bhagwangola',
                'meta_description' => 'Learn how voluntary blood donation saves lives in Bhagwangola & Murshidabad without commercial fees.',
                'meta_keywords' => 'blood donation Bhagwangola, thalassaemia blood support, Murshidabad blood helpline',
            ],
            [
                'title' => 'Eligibility Guide: Can You Donate Blood Today?',
                'slug' => 'eligibility-guide-can-you-donate-blood-today',
                'category' => 'Health & Eligibility',
                'excerpt' => 'Check the basic age, weight, hemoglobin, and medical criteria required before donating blood at our camps.',
                'content' => 'Before visiting a blood donation camp or responding to an emergency call in Bhagwangola, review these standard donor eligibility criteria:

### Basic Criteria:
- **Age**: 18 to 65 years old.
- **Weight**: Minimum 45 kg for females and 50 kg for males.
- **Hemoglobin**: At least 12.5 g/dL.
- **Donation Interval**: 90 days (3 months) for males and 120 days (4 months) for females.

### When to Postpone Donation:
- If you have taken antibiotics within the last 14 days.
- If you received a tattoo or body piercing within 6 months.
- If you have recently recovered from viral fever or jaundice.',
                'author_name' => 'Manab Kalyane Rokto Dan',
                'is_published' => true,
                'published_at' => now()->subDays(2),
                'views_count' => 98,
                'meta_title' => 'Blood Donation Eligibility Checklist — Bhagwangola',
                'meta_description' => 'Complete donor eligibility guide including weight, age, hemoglobin level, and recovery periods.',
                'meta_keywords' => 'who can donate blood, blood donor eligibility, hemoglobin requirements',
            ]
        ];

        foreach ($samplePosts as $post) {
            BlogPost::updateOrCreate(['slug' => $post['slug']], $post);
        }
    }
}

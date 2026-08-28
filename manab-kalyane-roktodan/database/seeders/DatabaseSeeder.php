<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\DonorProfile;
use App\Models\BloodRequest;
use App\Models\Member;
use App\Models\HeroSlide;
use App\Models\Gallery;
use App\Models\DonorStory;
use App\Models\SiteContent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@manobkalyane.org'],
            [
                'name' => 'Admin Bhagwangola',
                'phone' => '9876543210',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
            ]
        );

        // 2. Demo Donors
        $donorsData = [
            [
                'name' => 'Rahim Ali',
                'email' => 'rahim@gmail.com',
                'phone' => '9832145678',
                'blood_group' => 'O+',
                'village' => 'Bhagwangola',
                'block' => 'Bhagwangola-I',
                'district' => 'Murshidabad',
                'availability_status' => 'available',
                'last_donation' => '2026-04-10',
            ],
            [
                'name' => 'Subhashish Roy',
                'email' => 'subhashish@gmail.com',
                'phone' => '9734123456',
                'blood_group' => 'A+',
                'village' => 'Subarnapur',
                'block' => 'Bhagwangola-I',
                'district' => 'Murshidabad',
                'availability_status' => 'available',
                'last_donation' => '2026-02-01',
            ],
            [
                'name' => 'Aslam Hossain',
                'email' => 'aslam@gmail.com',
                'phone' => '9609876543',
                'blood_group' => 'B+',
                'village' => 'Akhoriganj',
                'block' => 'Bhagwangola-II',
                'district' => 'Murshidabad',
                'availability_status' => 'available',
                'last_donation' => null,
            ],
            [
                'name' => 'Debabrata Sen',
                'email' => 'debabrata@gmail.com',
                'phone' => '9434567890',
                'blood_group' => 'AB+',
                'village' => 'Lalgola Road',
                'block' => 'Bhagwangola-I',
                'district' => 'Murshidabad',
                'availability_status' => 'available',
                'last_donation' => '2026-05-15',
            ],
            [
                'name' => 'Imran Khan',
                'email' => 'imran@gmail.com',
                'phone' => '9123456780',
                'blood_group' => 'O-',
                'village' => 'Kalisera',
                'block' => 'Bhagwangola-II',
                'district' => 'Murshidabad',
                'availability_status' => 'available',
                'last_donation' => null,
            ],
        ];

        foreach ($donorsData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'role' => 'donor',
                    'password' => Hash::make('password'),
                ]
            );

            DonorProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'blood_group' => $data['blood_group'],
                    'availability_status' => $data['availability_status'],
                    'donor_type' => 'emergency',
                    'last_donation_date' => $data['last_donation'],
                    'village' => $data['village'],
                    'block' => $data['block'],
                    'district' => $data['district'],
                ]
            );
        }

        // 3. Demo Emergency Blood Requests
        BloodRequest::firstOrCreate(
            ['patient_name' => 'Smt. Anjali Ghosh'],
            [
                'blood_group' => 'O+',
                'units_required' => 2,
                'hospital_name' => 'Murshidabad Medical College & Hospital',
                'location' => 'Berhampore, Murshidabad',
                'contact_number' => '9832001122',
                'status' => 'pending',
                'created_by' => $admin->id,
                'notes' => 'Urgent requirement for surgery tomorrow morning.',
            ]
        );

        BloodRequest::firstOrCreate(
            ['patient_name' => 'Md. Faruk Sheikh'],
            [
                'blood_group' => 'B+',
                'units_required' => 1,
                'hospital_name' => 'Bhagwangola Rural Hospital',
                'location' => 'Bhagwangola',
                'contact_number' => '9734998877',
                'status' => 'pending',
                'created_by' => $admin->id,
                'notes' => 'Thalassemia patient regular transfusion.',
            ]
        );

        // 4. Board Members
        $members = [
            ['name' => 'Sk. Sahil', 'role_title' => 'President', 'phone' => '+91 98000 00001', 'sort_order' => 1],
            ['name' => 'Dr. A. K. Roy', 'role_title' => 'Vice President', 'phone' => '+91 98000 00002', 'sort_order' => 2],
            ['name' => 'Tariqul Islam', 'role_title' => 'General Secretary', 'phone' => '+91 98000 00003', 'sort_order' => 3],
            ['name' => 'Partha Sarathi Biswas', 'role_title' => 'Treasurer', 'phone' => '+91 98000 00004', 'sort_order' => 4],
        ];

        foreach ($members as $m) {
            Member::firstOrCreate(['name' => $m['name']], $m);
        }

        // 5. Hero Slides
        HeroSlide::firstOrCreate(
            ['title' => 'Every Drop Saves a Life in Bhagwangola'],
            [
                'subtitle' => 'Join Murshidabad’s most dedicated voluntary blood donor network.',
                'image_url' => 'https://images.unsplash.com/photo-1615461066841-6116e61058f4?auto=format&fit=crop&q=80&w=1200',
                'button_text' => 'Register as Donor',
                'button_link' => '/register',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        HeroSlide::firstOrCreate(
            ['title' => 'Emergency Blood Requirement?'],
            [
                'subtitle' => 'Search verified local donors in Bhagwangola & Murshidabad within seconds.',
                'image_url' => 'https://images.unsplash.com/photo-1579154204601-01588f351e67?auto=format&fit=crop&q=80&w=1200',
                'button_text' => 'Find Donors Now',
                'button_link' => '/search',
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        // 6. Demo Donor Stories & Experiences
        DonorStory::firstOrCreate(
            ['title' => 'My 5th Voluntary Blood Donation at Bhagwangola Camp'],
            [
                'donor_name' => 'Subhashish Roy',
                'blood_group' => 'A+',
                'experience' => "Donating blood at Bhagwangola camp was seamless. Knowing that 350ml of my blood could give someone a second chance at life gives me immense happiness. I encourage all youth in Murshidabad to step forward!",
                'photo_url' => 'https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?auto=format&fit=crop&q=80&w=800',
                'location' => 'Bhagwangola-I',
                'status' => 'approved',
            ]
        );

        DonorStory::firstOrCreate(
            ['title' => 'Midnight Emergency Blood Donation for a Patient'],
            [
                'donor_name' => 'Rahim Ali',
                'blood_group' => 'O+',
                'experience' => "Got a request through Manab Kalyane Rokto Dan at 11 PM for emergency O+ blood at Murshidabad Medical College. Reached the hospital within 20 minutes. Seeing the family relief was priceless.",
                'photo_url' => 'https://images.unsplash.com/photo-1615461066841-6116e61058f4?auto=format&fit=crop&q=80&w=800',
                'location' => 'Subarnapur, Bhagwangola',
                'status' => 'approved',
            ]
        );

        // 7. Site Content Settings
        SiteContent::setValue('organization_name', 'Manab Kalyane Rokto Dan');
        SiteContent::setValue('tagline', 'Bhagwangola Voluntary Blood Donation Society');
        SiteContent::setValue('helpline_phone', '+91 98321 00000');
        SiteContent::setValue('helpline_whatsapp', '919832100000');
        SiteContent::setValue('upi_id', 'manobkalyan@upi');
        SiteContent::setValue('upi_payee_name', 'Manab Kalyane Rokto Dan');
        SiteContent::setValue('about_text', 'Manab Kalyane Rokto Dan is a voluntary organization based in Bhagwangola, Murshidabad, dedicated to making blood accessible to every person in medical need without delay or financial burden.');
    }
}

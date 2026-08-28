<?php

namespace App\Http\Controllers;

use App\Models\DonorProfile;
use App\Models\BloodRequest;
use App\Models\DonationHistory;
use App\Models\HeroSlide;
use App\Models\Member;
use App\Models\Gallery;
use App\Models\DonorStory;
use App\Models\SiteContent;

class HomeController extends Controller
{
    public function index()
    {
        $slides = HeroSlide::where('is_active', true)->orderBy('sort_order')->get();

        $stats = [
            'total_donors' => DonorProfile::count(),
            'pending_requests' => BloodRequest::where('status', 'pending')->count(),
            'total_donations' => DonationHistory::count(),
            'fulfilled_requests' => BloodRequest::where('status', 'fulfilled')->count(),
        ];

        $pendingRequests = BloodRequest::where('status', 'pending')
            ->latest()
            ->take(6)
            ->get();

        $members = Member::where('is_active', true)->orderBy('sort_order')->get();
        $recentGallery = Gallery::latest()->take(6)->get();
        $stories = DonorStory::where('status', 'approved')->latest()->take(3)->get();

        $siteContent = [
            'organization_name' => SiteContent::getValue('organization_name', 'Manab Kalyane Rokto Dan'),
            'tagline' => SiteContent::getValue('tagline', 'Bhagwangola Voluntary Blood Donation Society'),
            'helpline_phone' => SiteContent::getValue('helpline_phone', '+91 98321 00000'),
            'helpline_whatsapp' => SiteContent::getValue('helpline_whatsapp', '919832100000'),
            'about_text' => SiteContent::getValue('about_text', 'Manab Kalyane Rokto Dan is dedicated to serving Bhagwangola and Murshidabad with instant blood donation assistance.'),
        ];

        return view('home', compact('slides', 'stats', 'pendingRequests', 'members', 'recentGallery', 'stories', 'siteContent'));
    }
}

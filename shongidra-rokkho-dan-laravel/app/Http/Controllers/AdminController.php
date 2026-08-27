<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DonorProfile;
use App\Models\BloodRequest;
use App\Models\DonationHistory;
use App\Models\Member;
use App\Models\HeroSlide;
use App\Models\Gallery;
use App\Models\DonorStory;
use App\Models\VisitorInquiry;
use App\Models\SiteContent;
use App\Models\BlogPost;
use App\Models\SeoSetting;
use App\Models\PageAnalytic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_donors' => DonorProfile::count(),
            'pending_requests' => BloodRequest::where('status', 'pending')->count(),
            'total_donations' => DonationHistory::count(),
            'total_inquiries' => VisitorInquiry::count(),
            'total_stories' => DonorStory::count(),
            'total_blog_posts' => BlogPost::count(),
            'total_page_views' => PageAnalytic::count(),
        ];

        $recentRequests = BloodRequest::latest()->take(10)->get();
        $users = User::with('donorProfile')->latest()->take(20)->get();
        $members = Member::orderBy('sort_order')->get();
        $slides = HeroSlide::orderBy('sort_order')->get();
        $gallery = Gallery::latest()->take(12)->get();
        $inquiries = VisitorInquiry::latest()->take(25)->get();
        $stories = DonorStory::latest()->get();
        $blogPosts = BlogPost::latest()->get();
        $seoSettings = SeoSetting::all();

        // Analytics Datasets
        $analytics = [
            'total_views' => PageAnalytic::count(),
            'unique_visitors' => PageAnalytic::distinct('ip_address')->count('ip_address'),
            'today_views' => PageAnalytic::whereDate('created_at', today())->count(),
            'top_pages' => PageAnalytic::select('path', DB::raw('count(*) as total'))
                                ->groupBy('path')
                                ->orderByDesc('total')
                                ->take(5)->get(),
            'device_breakdown' => PageAnalytic::select('device_type', DB::raw('count(*) as total'))
                                        ->groupBy('device_type')->get(),
            'recent_traffic' => PageAnalytic::latest()->take(20)->get(),
        ];

        $siteContent = [
            'organization_name' => SiteContent::getValue('organization_name'),
            'helpline_phone' => SiteContent::getValue('helpline_phone'),
            'helpline_whatsapp' => SiteContent::getValue('helpline_whatsapp'),
            'upi_id' => SiteContent::getValue('upi_id'),
            'about_text' => SiteContent::getValue('about_text'),
        ];

        return view('admin.index', compact(
            'stats', 'recentRequests', 'users', 'members', 'slides', 
            'gallery', 'inquiries', 'stories', 'siteContent', 
            'blogPosts', 'seoSettings', 'analytics'
        ));
    }

    public function getLiveInquiries()
    {
        $inquiries = VisitorInquiry::latest()->take(25)->get()->map(function ($inq) {
            return [
                'id' => $inq->id,
                'name' => $inq->name,
                'phone' => $inq->phone,
                'purpose' => $inq->purpose,
                'ip_address' => $inq->ip_address ?? 'Unknown IP',
                'session_id' => $inq->session_id ? substr($inq->session_id, 0, 16) . '...' : 'Session N/A',
                'time_ago' => $inq->created_at->diffForHumans(),
                'logged_at' => $inq->created_at->format('d M Y, h:i:s A'),
            ];
        });

        return response()->json([
            'status' => 'success',
            'count' => VisitorInquiry::count(),
            'inquiries' => $inquiries,
        ]);
    }

    public function storeBlogPost(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'cover_image' => 'nullable|url',
            'author_name' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'og_image' => 'nullable|url',
        ]);

        $validated['author_id'] = Auth::id();
        $validated['author_name'] = $validated['author_name'] ?: 'Manab Kalyane Rokto Dan';
        $validated['is_published'] = true;
        $validated['published_at'] = now();

        BlogPost::create($validated);

        return back()->with('success', 'Blog article published with SEO metadata!');
    }

    public function deleteBlogPost($id)
    {
        BlogPost::findOrFail($id)->delete();
        return back()->with('success', 'Blog post removed.');
    }

    public function updateSeoSetting(Request $request, $id)
    {
        $setting = SeoSetting::findOrFail($id);
        $validated = $request->validate([
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|string|max:255',
            'og_image' => 'nullable|url',
        ]);

        $setting->update($validated);

        return back()->with('success', "SEO Settings updated for '{$setting->page_name}'.");
    }

    public function updateUserRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'role' => 'required|in:admin,member,donor',
        ]);

        $user->update(['role' => $validated['role']]);
        return back()->with('success', "Role for {$user->name} updated to {$validated['role']}.");
    }

    public function recordDonation(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'donation_date' => 'required|date',
            'location' => 'nullable|string|max:255',
        ]);

        $certId = 'CERT-' . strtoupper(substr(md5(uniqid()), 0, 8));

        DonationHistory::create([
            'user_id' => $validated['user_id'],
            'donation_date' => $validated['donation_date'],
            'location' => $validated['location'],
            'certificate_id' => $certId,
        ]);

        DonorProfile::where('user_id', $validated['user_id'])
            ->update(['last_donation_date' => $validated['donation_date']]);

        $donorUser = User::find($validated['user_id']);
        if ($donorUser) {
            $donorUser->increment('loyalty_points', 100);
        }

        return back()->with('success', "Donation recorded successfully! Certificate ID: {$certId} (+100 Loyalty Points awarded)");
    }

    public function storeSlide(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'image_url' => 'nullable|url',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5000',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
        ]);

        $imageUrl = $validated['image_url'] ?? null;

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('slides', 'public');
            $imageUrl = asset('storage/' . $path);
        }

        if (!$imageUrl) {
            return back()->with('error', 'Please provide an image URL or upload an image file.');
        }

        HeroSlide::create([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'image_url' => $imageUrl,
            'button_text' => $validated['button_text'] ?? null,
            'button_link' => $validated['button_link'] ?? null,
            'sort_order' => HeroSlide::count() + 1,
            'is_active' => true,
        ]);

        return back()->with('success', 'Background carousel slide added successfully.');
    }

    public function deleteSlide($id)
    {
        HeroSlide::findOrFail($id)->delete();
        return back()->with('success', 'Slide deleted.');
    }

    public function storeGallery(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image_url' => 'nullable|url',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5000',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        $imageUrl = $validated['image_url'] ?? null;

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('gallery', 'public');
            $imageUrl = asset('storage/' . $path);
        }

        if (!$imageUrl) {
            return back()->with('error', 'Please provide an image URL or upload an image file.');
        }

        Gallery::create([
            'title' => $validated['title'],
            'image_url' => $imageUrl,
            'category' => $validated['category'],
            'description' => $validated['description'] ?? null,
        ]);

        return back()->with('success', 'Gallery image added successfully.');
    }

    public function deleteGallery($id)
    {
        Gallery::findOrFail($id)->delete();
        return back()->with('success', 'Gallery item removed.');
    }

    public function deleteStory($id)
    {
        DonorStory::findOrFail($id)->delete();
        return back()->with('success', 'Donor story deleted.');
    }

    public function updateSettings(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            SiteContent::setValue($key, $value);
        }
        return back()->with('success', 'Site settings updated successfully.');
    }
}

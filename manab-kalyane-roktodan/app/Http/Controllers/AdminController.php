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
use App\Models\DonationPledge;
use App\Models\UserNotification;
use App\Models\SiteContent;
use App\Models\BlogPost;
use App\Models\SeoSetting;
use App\Models\PageAnalytic;
use App\Models\Role;
use App\Models\SiteMenuItem;
use App\Models\HomepageSection;
use App\Models\CmsPage;
use App\Models\MediaAsset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index(Request $request)
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

        // Search & Pagination for Registered Users Table
        $userSearch = trim($request->input('search_user', ''));
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [5, 10, 15, 25, 50, 100])) {
            $perPage = 10;
        }

        $userQuery = User::with(['donorProfile', 'roleModel']);

        if ($userSearch !== '') {
            $userQuery->where(function($q) use ($userSearch) {
                $q->where('name', 'LIKE', "%{$userSearch}%")
                  ->orWhere('email', 'LIKE', "%{$userSearch}%")
                  ->orWhere('phone', 'LIKE', "%{$userSearch}%")
                  ->orWhereHas('donorProfile', function($dp) use ($userSearch) {
                      $dp->where('donor_code', 'LIKE', "%{$userSearch}%")
                         ->orWhere('block', 'LIKE', "%{$userSearch}%")
                         ->orWhere('blood_group', 'LIKE', "%{$userSearch}%");
                  });
            });
        }

        $users = $userQuery->latest()->paginate($perPage, ['*'], 'users_page')->withQueryString();
        $recentRequests = BloodRequest::latest()->take(10)->get();
        $members = Member::orderBy('sort_order')->get();
        $slides = HeroSlide::orderBy('sort_order')->get();
        $gallery = Gallery::latest()->take(12)->get();
        $inquiries = VisitorInquiry::latest()->take(25)->get();
        $stories = DonorStory::latest()->get();
        $blogPosts = BlogPost::latest()->get();
        $seoSettings = SeoSetting::all();
        $financialPledges = DonationPledge::latest()->get();
        $totalFinancialRaised = DonationPledge::where('status', 'verified')->sum('amount');

        // Analytics Datasets
        $analytics = [
            'total_views' => PageAnalytic::count(),
            'unique_visitors' => PageAnalytic::distinct('ip_address')->count('ip_address'),
            'unique_tracking_ids' => PageAnalytic::whereNotNull('tracking_id')->distinct('tracking_id')->count('tracking_id'),
            'donor_contact_clicks' => PageAnalytic::whereIn('action_type', ['contact_donor_phone_call', 'contact_donor_whatsapp', 'inquire_via_society'])->count(),
            'today_views' => PageAnalytic::whereDate('created_at', today())->count(),
            'top_pages' => PageAnalytic::select('path', DB::raw('count(*) as total'))
                                ->groupBy('path')
                                ->orderByDesc('total')
                                ->take(5)->get(),
            'device_breakdown' => PageAnalytic::select('device_type', DB::raw('count(*) as total'))
                                        ->groupBy('device_type')->get(),
            'recent_traffic' => PageAnalytic::latest()->take(50)->get(),
            'donor_contacts' => PageAnalytic::whereIn('action_type', ['contact_donor_phone_call', 'contact_donor_whatsapp', 'inquire_via_society'])->latest()->take(30)->get(),
        ];

        $defaultFooterMsg = 'Manab Kalyane Rokto Dan is a voluntary organization based in Bhagwangola, Murshidabad, dedicated to making blood accessible to every person in medical need without delay or financial burden.';

        $siteContent = [
            'organization_name' => SiteContent::getValue('organization_name'),
            'helpline_phone' => SiteContent::getValue('helpline_phone'),
            'helpline_whatsapp' => SiteContent::getValue('helpline_whatsapp'),
            'top_bar_location' => SiteContent::getValue('top_bar_location', 'Murshidabad, West Bengal'),
            'whatsapp_button_text' => SiteContent::getValue('whatsapp_button_text', 'WhatsApp'),
            'upi_id' => SiteContent::getValue('upi_id'),
            'about_text' => SiteContent::getValue('about_text', $defaultFooterMsg),
            'footer_text' => SiteContent::getValue('footer_text', $defaultFooterMsg),
            'social_facebook' => SiteContent::getValue('social_facebook', 'https://facebook.com'),
            'social_instagram' => SiteContent::getValue('social_instagram', 'https://instagram.com'),
            'social_whatsapp' => SiteContent::getValue('social_whatsapp', 'https://wa.me/919832100000'),
            'social_youtube' => SiteContent::getValue('social_youtube', 'https://youtube.com'),
            'social_twitter' => SiteContent::getValue('social_twitter', 'https://x.com'),
        ];

        Role::ensureDefaultRolesExist();
        SiteMenuItem::ensureDefaultMenuItems();
        HomepageSection::ensureDefaultSections();
        CmsPage::ensureDefaultPages();

        $roles = Role::withCount('users')->get();
        $availablePermissions = Role::defaultPermissions();
        $menuItems = SiteMenuItem::orderBy('sort_order')->get();
        $homepageSections = HomepageSection::orderBy('sort_order')->get();
        $cmsPages = CmsPage::latest()->get();
        $mediaAssets = MediaAsset::latest()->get();

        $branding = [
            'site_logo' => SiteContent::getValue('site_logo'),
            'site_dark_logo' => SiteContent::getValue('site_dark_logo'),
            'site_favicon' => SiteContent::getValue('site_favicon'),
            'site_tagline' => SiteContent::getValue('site_tagline'),
            'custom_css' => SiteContent::getValue('custom_css'),
            'custom_js' => SiteContent::getValue('custom_js'),
            'enable_custom_css' => SiteContent::getValue('enable_custom_css', '1'),
        ];

        return view('admin.index', compact(
            'stats', 'recentRequests', 'users', 'members', 'slides', 
            'gallery', 'inquiries', 'stories', 'siteContent', 
            'blogPosts', 'seoSettings', 'analytics', 'financialPledges', 'totalFinancialRaised',
            'roles', 'availablePermissions', 'menuItems', 'homepageSections', 'cmsPages', 'mediaAssets', 'branding'
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
            'role' => 'required|string|exists:roles,name',
        ]);

        $roleObj = Role::where('name', $validated['role'])->first();

        $user->update([
            'role' => $validated['role'],
            'role_id' => $roleObj?->id,
        ]);

        return back()->with('success', "Role for {$user->name} updated to " . ($roleObj?->label ?? $validated['role']) . " successfully.");
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
            $donorUser->upgradeRoleAfterContribution('official blood donation');
        }

        return back()->with('success', "Blood donation recorded successfully! Certificate ID: {$certId} (+100 Loyalty Points awarded & Account upgraded to Voluntary Donor status)");
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

    public function updateUser(Request $request, $id)
    {
        $user = User::with('donorProfile')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20|unique:users,phone,' . $user->id,
            'role' => 'required|string|exists:roles,name',
            'loyalty_points' => 'required|integer|min:0',
            'password' => 'nullable|string|min:6',
            'blood_group' => 'required|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'availability_status' => 'required|in:available,unavailable',
            'allow_direct_contact' => 'nullable|boolean',
            'donor_type' => 'required|in:regular,emergency',
            'village' => 'nullable|string|max:255',
            'block' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'last_donation_date' => 'nullable|date',
            'assigned_email' => 'nullable|string|max:255',
            'assigned_email_password' => 'nullable|string|max:255',
            'assigned_email_login_url' => 'nullable|string|max:255',
        ]);

        $roleObj = Role::where('name', strtolower($validated['role']))->first();

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => strtolower($validated['role']),
            'role_id' => $roleObj?->id,
            'loyalty_points' => $validated['loyalty_points'],
            'assigned_email' => $request->input('assigned_email'),
            'assigned_email_password' => $request->input('assigned_email_password'),
            'assigned_email_login_url' => $request->input('assigned_email_login_url') ?: 'https://webmail.mabia.in',
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);

        if (Auth::check() && Auth::id() === $user->id) {
            Auth::setUser($user->fresh(['roleModel']));
        }

        DonorProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'blood_group' => $validated['blood_group'],
                'availability_status' => $validated['availability_status'],
                'allow_direct_contact' => $request->boolean('allow_direct_contact', false),
                'donor_type' => $validated['donor_type'],
                'village' => $validated['village'] ?? null,
                'block' => $validated['block'],
                'district' => $validated['district'],
                'last_donation_date' => $validated['last_donation_date'] ?? null,
            ]
        );

        return back()->with('success', "User '{$user->name}' profile and password updated successfully!");
    }

    public function updatePledgeStatus(Request $request, $id)
    {
        $pledge = DonationPledge::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,verified,rejected',
        ]);

        $pledge->update(['status' => $validated['status']]);

        return back()->with('success', "Financial pledge status updated to '{$validated['status']}' successfully.");
    }

    public function recordPledge(Request $request)
    {
        $validated = $request->validate([
            'donor_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'amount' => 'required|numeric|min:1',
            'payment_type' => 'required|in:one_time,weekly,monthly',
            'transaction_id' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        $validated['status'] = 'verified';

        DonationPledge::create($validated);

        return back()->with('success', 'Manual financial donation recorded as verified successfully!');
    }

    public function sendBulkReminder(Request $request)
    {
        $validated = $request->validate([
            'target' => 'required|string',
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'type' => 'required|in:blood_reminder,financial_reminder,announcement',
            'action_url' => 'nullable|string|max:255',
        ]);

        $query = User::query();

        if ($validated['target'] === 'eligible_only') {
            $query->whereHas('donorProfile', function ($q) {
                $q->whereNull('last_donation_date')
                  ->orWhere('last_donation_date', '<=', now()->subDays(90));
            });
        } elseif (in_array($validated['target'], ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])) {
            $query->whereHas('donorProfile', function ($q) use ($validated) {
                $q->where('blood_group', $validated['target']);
            });
        }

        $users = $query->get();
        $sentCount = 0;

        foreach ($users as $user) {
            UserNotification::create([
                'user_id' => $user->id,
                'title' => $validated['title'],
                'message' => $validated['message'],
                'type' => $validated['type'],
                'action_url' => $validated['action_url'] ?? null,
                'is_read' => false,
            ]);
            $sentCount++;
        }

        return back()->with('success', "Bulk notification successfully sent to {$sentCount} registered users!");
    }

    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'id' => 'nullable|exists:roles,id',
            'name' => 'required|string|max:100|alpha_dash|unique:roles,name,' . ($request->id ?? 'NULL'),
            'label' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
        ]);

        $permissions = $validated['permissions'] ?? [];

        $role = Role::updateOrCreate(
            ['id' => $request->id],
            [
                'name' => strtolower($validated['name']),
                'label' => $validated['label'],
                'description' => $validated['description'] ?? null,
                'permissions' => array_values($permissions),
                'is_system' => false,
            ]
        );

        return back()->with('success', "Role '{$role->label}' saved with updated permissions successfully!");
    }

    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);

        if ($role->is_system || in_array($role->name, ['admin', 'donor'])) {
            return back()->with('error', "System core role '{$role->label}' cannot be deleted!");
        }

        $role->delete();

        return back()->with('success', "Role '{$role->label}' deleted successfully!");
    }

    public function assignUserRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'role_name' => 'required|string|exists:roles,name',
        ]);

        $role = Role::where('name', strtolower($validated['role_name']))->firstOrFail();

        $user->update([
            'role_id' => $role->id,
            'role' => $role->name,
        ]);

        if (Auth::check() && Auth::id() === $user->id) {
            Auth::setUser($user->fresh(['roleModel']));
        }

        return back()->with('success', "User '{$user->name}' role updated to '{$role->label}' successfully!");
    }
}

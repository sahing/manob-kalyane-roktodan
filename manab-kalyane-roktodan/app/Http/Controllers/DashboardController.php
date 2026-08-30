<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DonorProfile;
use App\Models\DonationHistory;
use App\Models\BloodRequest;
use App\Models\UserNotification;
use App\Models\SiteContent;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'guest') {
            return redirect()->route('requests.index')->with('info', 'Guest accounts do not have access to the donor dashboard. Please register as a verified donor to access full dashboard features.');
        }

        $user = Auth::user()->load(['donorProfile', 'donations', 'bloodRequests', 'notifications']);

        $lastDonation = $user->donorProfile?->last_donation_date;
        $daysSince = $lastDonation ? now()->diffInDays($lastDonation) : null;
        $isEligible = !$lastDonation || $daysSince >= 90;
        $nextEligibleDate = $lastDonation ? $lastDonation->copy()->addDays(90) : now();

        $notifications = $user->notifications;
        $unreadNotificationsCount = $notifications->where('is_read', false)->count();

        return view('dashboard.index', compact('user', 'daysSince', 'isEligible', 'nextEligibleDate', 'notifications', 'unreadNotificationsCount'));
    }

    public function profile()
    {
        $user = Auth::user()->load('donorProfile');
        return view('dashboard.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'avatar_url' => 'nullable|url|max:2048',
            'blood_group' => 'required|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'availability_status' => 'required|in:available,unavailable',
            'allow_direct_contact' => 'nullable|boolean',
            'donor_type' => 'required|in:regular,emergency',
            'village' => 'nullable|string|max:255',
            'block' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'last_donation_date' => 'nullable|date',
            'medical_notes' => 'nullable|string|max:1000',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'],
            'avatar_url' => $validated['avatar_url'] ?? null,
        ]);

        $profile = DonorProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'blood_group' => $validated['blood_group'],
                'availability_status' => $validated['availability_status'],
                'allow_direct_contact' => $request->boolean('allow_direct_contact', false),
                'donor_type' => $validated['donor_type'],
                'village' => $validated['village'],
                'block' => $validated['block'],
                'district' => $validated['district'],
                'last_donation_date' => $validated['last_donation_date'],
                'medical_notes' => $validated['medical_notes'],
            ]
        );

        if (empty($profile->donor_code)) {
            $profile->donor_code = DonorProfile::generateUniqueDonorCode($profile->id);
            $profile->save();
        }

        return redirect()->route('dashboard')->with('success', 'Your donor profile has been updated successfully.');
    }

    public function card()
    {
        $user = Auth::user()->load(['donorProfile', 'donations']);
        if (!$user->donorProfile) {
            return redirect()->route('dashboard.profile')->with('info', 'Please complete your donor profile first.');
        }

        $cardId = $user->donorProfile->donor_code ?: DonorProfile::generateUniqueDonorCode($user->donorProfile->id);
        $totalDonations = $user->donations->count();
        $verificationUrl = route('verify.show', ['code' => $cardId]);
        $helplinePhone = SiteContent::getValue('helpline_phone', '+91 98321 00000');

        $avatarDataUri = null;
        if (!empty($user->avatar_url) && filter_var($user->avatar_url, FILTER_VALIDATE_URL)) {
            try {
                $ctx = stream_context_create([
                    'http' => [
                        'timeout' => 1.5,
                        'ignore_errors' => true,
                        'header' => "User-Agent: Mozilla/5.0\r\n"
                    ]
                ]);
                $imgData = @file_get_contents($user->avatar_url, false, $ctx);
                if ($imgData !== false && strlen($imgData) > 0) {
                    $mimeType = 'image/png';
                    if (function_exists('finfo_open')) {
                        $finfo = @finfo_open(FILEINFO_MIME_TYPE);
                        if ($finfo) {
                            $mimeType = @finfo_buffer($finfo, $imgData) ?: 'image/png';
                            @finfo_close($finfo);
                        }
                    }
                    $avatarDataUri = 'data:' . $mimeType . ';base64,' . base64_encode($imgData);
                }
            } catch (\Throwable $e) {
                $avatarDataUri = null;
            }
        }

        return view('dashboard.card', compact('user', 'cardId', 'totalDonations', 'verificationUrl', 'helplinePhone', 'avatarDataUri'));
    }



    public function showCertificate($id)
    {
        $donation = DonationHistory::with(['user.donorProfile', 'user.donations'])->findOrFail($id);

        if (!Auth::user()->isAdmin() && $donation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to certificate.');
        }

        $verificationUrl = route('verify.show', ['code' => $donation->certificate_id]);

        return view('dashboard.certificate', compact('donation', 'verificationUrl'));
    }

    public function markNotificationAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->update(['is_read' => true]);

        if ($notification->action_url) {
            return redirect($notification->action_url);
        }

        return back()->with('success', 'Notification marked as read.');
    }
}

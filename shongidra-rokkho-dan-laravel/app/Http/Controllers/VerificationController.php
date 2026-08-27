<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DonorProfile;
use App\Models\DonationHistory;
use App\Models\SeoSetting;

class VerificationController extends Controller
{
    public function index(Request $request)
    {
        $code = trim($request->get('code', ''));
        if (!empty($code)) {
            return redirect()->route('verify.show', ['code' => $code]);
        }

        $seo = SeoSetting::getMetaForPage(
            'verify.index',
            'Verify Donor ID Card & Certificate — Manab Kalyane Rokto Dan',
            'Public verification portal for voluntary blood donor ID cards and official donation certificates in Bhagwangola & Murshidabad.'
        );

        return view('verify.index', compact('seo'));
    }

    public function verify($code)
    {
        $code = trim($code);
        $donor = null;
        $donation = null;
        $type = null;

        // 1. Search for Certificate ID e.g. CERT-XXXXXX
        if (str_starts_with(strtoupper($code), 'CERT-')) {
            $donation = DonationHistory::with(['user.donorProfile', 'user.donations'])
                ->where('certificate_id', strtoupper($code))
                ->first();
            if ($donation) {
                $type = 'certificate';
                $donor = $donation->user->donorProfile;
            }
        }

        // 2. Search for Donor Code e.g. MKRD-00001 or ID
        if (!$donor) {
            $donor = DonorProfile::with(['user', 'user.donations'])
                ->where('donor_code', strtoupper($code))
                ->orWhere('id', $code)
                ->first();
            if ($donor) {
                $type = 'donor_card';
            }
        }

        if (!$donor && !$donation) {
            return view('verify.not_found', compact('code'));
        }

        $user = $donor ? $donor->user : $donation->user;
        $totalDonations = $user ? $user->donations()->count() : 0;
        
        // Calculate Donor Level / Badge
        $badge = 'Voluntary Life Saver';
        if ($totalDonations >= 10) {
            $badge = '🏆 Gold Honor Donor';
        } elseif ($totalDonations >= 5) {
            $badge = '🥇 Silver Star Donor';
        } elseif ($totalDonations >= 1) {
            $badge = '🩸 Verified Life Saver';
        }

        $seo = [
            'title' => 'Official Verification: ' . ($user->name ?? 'Donor') . ' — Manab Kalyane Rokto Dan',
            'description' => 'Verified voluntary donor profile and certificate record for ' . ($user->name ?? 'Donor') . ' in Bhagwangola.',
            'keywords' => 'verify donor id, blood certificate verification, Bhagwangola donor card',
            'canonical' => url()->current(),
        ];

        return view('verify.show', compact('donor', 'donation', 'user', 'type', 'totalDonations', 'badge', 'code', 'seo'));
    }
}

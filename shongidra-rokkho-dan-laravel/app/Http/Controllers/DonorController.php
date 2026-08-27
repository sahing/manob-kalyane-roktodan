<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DonorProfile;
use App\Models\VisitorInquiry;
use Illuminate\Support\Facades\Auth;

class DonorController extends Controller
{
    public function search(Request $request)
    {
        $group = $request->query('blood_group');
        $district = $request->query('district', 'Murshidabad');
        $block = $request->query('block');
        $availableOnly = $request->boolean('available_only', true);

        $query = DonorProfile::with('user');

        if ($group) {
            $query->where('blood_group', $group);
        }

        if ($district) {
            $query->where('district', 'LIKE', "%{$district}%");
        }

        if ($block) {
            $query->where('block', 'LIKE', "%{$block}%");
        }

        if ($availableOnly) {
            $query->where('availability_status', 'available');
        }

        $donors = $query->orderByRaw('last_donation_date IS NULL DESC, last_donation_date ASC')->paginate(12);

        $inquiryGatePassed = Auth::check() || session()->has('inquiry_passed');

        return view('donors.search', compact('donors', 'group', 'district', 'block', 'availableOnly', 'inquiryGatePassed'));
    }

    public function show($id)
    {
        $donor = DonorProfile::with(['user', 'user.donations'])->findOrFail($id);
        $inquiryGatePassed = Auth::check() || session()->has('inquiry_passed');

        return view('donors.show', compact('donor', 'inquiryGatePassed'));
    }

    public function submitInquiry(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'donor_name' => 'nullable|string|max:255',
            'blood_group' => 'nullable|string|max:10',
            'purpose' => 'nullable|string|max:255',
        ]);

        $donorInfo = '';
        if (!empty($validated['donor_name'])) {
            $donorInfo = " for Donor: {$validated['donor_name']}" . (!empty($validated['blood_group']) ? " ({$validated['blood_group']})" : '');
        }

        $purpose = $validated['purpose'] ?? ("Request Donor Contact" . $donorInfo);

        VisitorInquiry::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'purpose' => $purpose,
            'ip_address' => $request->ip(),
            'session_id' => session()->getId(),
        ]);

        session()->put('inquiry_passed', true);

        return back()->with('success', 'Visitor session verified. Donor contact details unlocked.');
    }
}

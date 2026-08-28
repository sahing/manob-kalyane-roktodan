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
        $district = $request->query('district');
        $block = $request->query('block');
        $keyword = $request->query('q');
        $availableOnly = $request->boolean('available_only', true);
        
        // Has user submitted a search action?
        $hasSearched = $request->has('blood_group') || $request->has('block') || $request->has('q') || $request->has('searched') || $request->has('all');

        $donors = null;

        if ($hasSearched) {
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

            if ($keyword) {
                $query->where(function($q) use ($keyword) {
                    $q->whereHas('user', function($u) use ($keyword) {
                        $u->where('name', 'LIKE', "%{$keyword}%")
                          ->orWhere('phone', 'LIKE', "%{$keyword}%");
                    })
                    ->orWhere('village', 'LIKE', "%{$keyword}%")
                    ->orWhere('block', 'LIKE', "%{$keyword}%");
                });
            }

            if ($availableOnly) {
                $query->where('availability_status', 'available');
            }

            // Paginate 20 donors per page
            $donors = $query->orderByRaw('last_donation_date IS NULL DESC, last_donation_date ASC')->paginate(20);
        }

        $inquiryGatePassed = Auth::check() || session()->has('inquiry_passed');

        // Handle AJAX auto-infinite scroll requests
        if ($request->ajax() && $donors) {
            $cardsHtml = view('donors._cards', compact('donors', 'inquiryGatePassed'))->render();
            return response()->json([
                'html' => $cardsHtml,
                'next_page_url' => $donors->nextPageUrl(),
                'has_more' => $donors->hasMorePages(),
            ]);
        }

        return view('donors.search', compact('donors', 'group', 'district', 'block', 'keyword', 'availableOnly', 'inquiryGatePassed', 'hasSearched'));
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

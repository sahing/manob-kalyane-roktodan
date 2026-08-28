<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DonationPledge;
use App\Models\SiteContent;
use Illuminate\Support\Facades\Auth;

class PledgeController extends Controller
{
    public function index()
    {
        $upiId = SiteContent::getValue('upi_id', 'manobkalyan@upi');
        $payeeName = SiteContent::getValue('upi_payee_name', 'Manab Kalyane Rokto Dan');

        return view('donate', compact('upiId', 'payeeName'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'donor_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'amount' => 'required|numeric|min:1',
            'payment_type' => 'required|in:one_time,weekly,monthly',
            'transaction_id' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        DonationPledge::create($validated);

        return back()->with('success', 'Thank you! Your contribution pledge has been recorded for admin verification.');
    }
}

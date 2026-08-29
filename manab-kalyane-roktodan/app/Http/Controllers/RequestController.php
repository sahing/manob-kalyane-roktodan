<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BloodRequest;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function index()
    {
        $pending = BloodRequest::where('status', 'pending')->latest()->paginate(15);
        $fulfilled = BloodRequest::where('status', 'fulfilled')->latest()->take(5)->get();
        $inquiryGatePassed = Auth::check() || session()->has('inquiry_passed');

        return view('requests.index', compact('pending', 'fulfilled', 'inquiryGatePassed'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_name' => 'required|string|max:255',
            'blood_group' => 'required|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'units_required' => 'required|integer|min:1|max:10',
            'needed_by_date' => 'nullable|date',
            'hospital_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = 'pending';

        BloodRequest::create($validated);

        return redirect()->route('requests.index')->with('success', 'Emergency blood request created successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $bloodRequest = BloodRequest::findOrFail($id);

        if (!Auth::user()->isAdmin() && $bloodRequest->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,fulfilled,cancelled',
        ]);

        $bloodRequest->update(['status' => $validated['status']]);

        return back()->with('success', "Blood request status updated to {$validated['status']}.");
    }
}

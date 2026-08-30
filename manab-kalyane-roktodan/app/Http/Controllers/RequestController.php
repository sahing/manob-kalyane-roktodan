<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BloodRequest;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RequestController extends Controller
{
    public function index()
    {
        $pending = BloodRequest::where('status', 'pending')->latest()->paginate(15);
        $fulfilled = BloodRequest::where('status', 'fulfilled')->latest()->take(12)->get();
        $inquiryGatePassed = Auth::check() || session()->has('inquiry_passed');

        return view('requests.index', compact('pending', 'fulfilled', 'inquiryGatePassed'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_name' => 'required|string|max:255',
            'requester_name' => 'nullable|string|max:255',
            'blood_group' => 'required|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'units_required' => 'required|integer|min:1|max:10',
            'needed_by_date' => 'nullable|date',
            'hospital_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ]);

        $userId = Auth::id();

        // If not logged in, find or create guest user account with phone as password
        if (!$userId) {
            $phone = trim($validated['contact_number']);
            $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
            $guestName = trim($request->input('requester_name') ?: $validated['patient_name']);

            $user = User::where('phone', $phone)
                ->orWhere(function ($q) use ($cleanPhone) {
                    if (!empty($cleanPhone)) {
                        $q->where('phone', $cleanPhone);
                    }
                })->first();

            if (!$user) {
                Role::ensureDefaultRolesExist();
                $guestRole = Role::where('name', 'guest')->first();

                $user = User::create([
                    'name' => $guestName,
                    'phone' => $phone,
                    'role' => 'guest',
                    'role_id' => $guestRole?->id,
                    'password' => Hash::make($phone), // Password set to same mobile number
                ]);
            }

            Auth::login($user);
            $request->session()->regenerate();
            $userId = $user->id;
        }

        $bloodRequest = BloodRequest::create([
            'patient_name' => $validated['patient_name'],
            'blood_group' => $validated['blood_group'],
            'units_required' => $validated['units_required'],
            'needed_by_date' => $validated['needed_by_date'] ?? null,
            'hospital_name' => $validated['hospital_name'],
            'location' => $validated['location'],
            'contact_number' => $validated['contact_number'],
            'notes' => $validated['notes'] ?? null,
            'created_by' => $userId,
            'status' => 'pending',
        ]);

        return redirect()->route('requests.index')->with([
            'success' => 'Emergency blood request published successfully!',
            'new_request_share' => [
                'id' => $bloodRequest->id,
                'patient_name' => $bloodRequest->patient_name,
                'blood_group' => $bloodRequest->blood_group,
                'units_required' => $bloodRequest->units_required,
                'hospital_name' => $bloodRequest->hospital_name,
                'location' => $bloodRequest->location,
                'contact_number' => $bloodRequest->contact_number,
                'needed_by_date' => $bloodRequest->needed_by_date ? date('d M Y', strtotime($bloodRequest->needed_by_date)) : 'As Soon As Possible',
                'notes' => $bloodRequest->notes,
                'url' => route('requests.index') . '#request-' . $bloodRequest->id,
            ]
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $bloodRequest = BloodRequest::findOrFail($id);

        if (!Auth::check() || !Auth::user()->canManageBloodRequest($bloodRequest)) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,fulfilled,cancelled',
            'fulfillment_notes' => 'nullable|string|max:1000',
            'fulfilled_by_donor' => 'nullable|string|max:255',
        ]);

        $updateData = ['status' => $validated['status']];
        if (isset($validated['fulfillment_notes'])) {
            $updateData['fulfillment_notes'] = $validated['fulfillment_notes'];
        }
        if (isset($validated['fulfilled_by_donor'])) {
            $updateData['fulfilled_by_donor'] = $validated['fulfilled_by_donor'];
        }

        $bloodRequest->update($updateData);

        $msg = $validated['status'] === 'fulfilled' 
            ? 'Emergency request marked as FULFILLED! Thank you for sharing your experience.' 
            : "Request status updated to {$validated['status']}.";

        return back()->with('success', $msg);
    }

    public function update(Request $request, $id)
    {
        $bloodRequest = BloodRequest::findOrFail($id);

        if (!Auth::check() || !Auth::user()->canManageBloodRequest($bloodRequest)) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'patient_name' => 'required|string|max:255',
            'blood_group' => 'required|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'units_required' => 'required|integer|min:1|max:10',
            'needed_by_date' => 'nullable|date',
            'hospital_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'status' => 'required|in:pending,fulfilled,cancelled',
            'notes' => 'nullable|string|max:1000',
            'fulfillment_notes' => 'nullable|string|max:1000',
            'fulfilled_by_donor' => 'nullable|string|max:255',
        ]);

        $bloodRequest->update($validated);

        if ($bloodRequest->status === 'fulfilled') {
            return back()->with('fulfilled_share', [
                'id' => $bloodRequest->id,
                'patient_name' => $bloodRequest->patient_name,
                'blood_group' => $bloodRequest->blood_group,
                'hospital_name' => $bloodRequest->hospital_name,
                'location' => $bloodRequest->location,
                'fulfilled_by_donor' => $bloodRequest->fulfilled_by_donor,
                'fulfillment_notes' => $bloodRequest->fulfillment_notes,
                'url' => route('requests.index') . '#request-' . $bloodRequest->id,
            ])->with('success', 'Blood request set to Fulfilled & feedback saved successfully!');
        }

        return back()->with('success', 'Blood request updated successfully.');
    }
}

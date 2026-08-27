<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DonorProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))->with('success', 'Logged in successfully.');
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.'])->onlyInput('email');
    }

    public function showRegister(Request $request)
    {
        $refCode = strtoupper(trim($request->get('ref', '')));
        return view('auth.register', compact('refCode'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'blood_group' => 'required|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'village' => 'nullable|string|max:255',
            'block' => 'nullable|string|max:255',
            'ref_code' => 'nullable|string|max:50',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $inviter = null;
        if (!empty($validated['ref_code'])) {
            $inviter = User::where('referral_code', strtoupper(trim($validated['ref_code'])))->first();
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => 'donor',
            'referred_by_id' => $inviter?->id,
            'loyalty_points' => $inviter ? 20 : 0, // 20 Bonus points for using an invite code
            'password' => Hash::make($validated['password']),
        ]);

        DonorProfile::create([
            'user_id' => $user->id,
            'blood_group' => $validated['blood_group'],
            'village' => $validated['village'] ?? null,
            'block' => $validated['block'] ?? 'Bhagwangola-I',
            'district' => 'Murshidabad',
            'availability_status' => 'available',
        ]);

        // Reward the inviter with +50 Loyalty Points
        if ($inviter) {
            $inviter->increment('loyalty_points', 50);
            if ($inviter->donorProfile) {
                $inviter->donorProfile->increment('referrals_count');
            }
        }

        Auth::login($user);

        $msg = $inviter
            ? "Registration completed! You earned 20 Bonus Loyalty Points for joining via {$inviter->name}'s invitation."
            : "Registration completed! Welcome to Manab Kalyane Rokto Dan.";

        return redirect()->route('dashboard')->with('success', $msg);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logged out successfully.');
    }
}

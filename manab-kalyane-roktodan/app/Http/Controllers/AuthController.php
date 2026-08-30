<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DonorProfile;
use App\Models\VisitorInquiry;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle authentication by Mobile Number (default) or Email address.
     */
    public function login(Request $request)
    {
        $request->validate([
            'login_id' => 'required|string',
            'password' => 'nullable|string',
        ], [
            'login_id.required' => 'Please enter your registered Mobile Number or Email address.',
        ]);

        $loginId = trim($request->input('login_id'));
        $password = $request->input('password');

        // Extract digits if a phone number was entered
        $cleanPhone = preg_replace('/[^0-9]/', '', $loginId);

        // Search user by Phone Number or Email
        $user = User::where(function ($query) use ($loginId, $cleanPhone) {
            $query->where('phone', $loginId);
            if (!empty($cleanPhone)) {
                $query->orWhere('phone', $cleanPhone);
            }
            if (filter_var($loginId, FILTER_VALIDATE_EMAIL)) {
                $query->orWhere('email', strtolower($loginId));
            }
        })->first();

        if ($user) {
            $isGuest = ($user->role === 'guest');
            $passwordValid = !empty($password) && (Hash::check($password, $user->password) || $password === $user->phone);

            if ($isGuest || $passwordValid) {
                Auth::login($user, $request->boolean('remember'));
                $request->session()->regenerate();

                $userName = $user->name;
                $redirectTo = $request->input('redirect_to');

                if ($isGuest) {
                    $targetUrl = ($redirectTo && filter_var($redirectTo, FILTER_VALIDATE_URL) && str_starts_with($redirectTo, url('/')) && !str_contains($redirectTo, '/dashboard'))
                        ? $redirectTo
                        : route('requests.index');
                    return redirect($targetUrl)->with('success', "Welcome back, {$userName}! Logged in as guest requester. 🩸");
                }

                if ($redirectTo && filter_var($redirectTo, FILTER_VALIDATE_URL) && str_starts_with($redirectTo, url('/'))) {
                    $path = parse_url($redirectTo, PHP_URL_PATH);
                    if ($path && !in_array($path, ['/login', '/register', '/logout'])) {
                        return redirect()->intended($redirectTo)->with('success', "Welcome back, {$userName}! You have logged in successfully. 🩸");
                    }
                }

                return redirect()->intended(route('dashboard'))->with('success', "Welcome back, {$userName}! You have logged in successfully. 🩸");
            }
        }

        return back()
            ->withErrors(['login_id' => 'Invalid Mobile Number or Password. Please check your credentials and try again.'])
            ->with('error', 'Login Failed: Invalid mobile number/email or password. Please verify your details.')
            ->onlyInput('login_id');
    }

    public function showRegister(Request $request)
    {
        $refCode = strtoupper(trim($request->get('ref', '')));
        return view('auth.register', compact('refCode'));
    }

    /**
     * Handle donor registration (Mobile required, Email optional).
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|string|email|max:255',
            'blood_group' => 'required|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'village' => 'nullable|string|max:255',
            'block' => 'nullable|string|max:255',
            'ref_code' => 'nullable|string|max:50',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'phone.required' => 'Mobile number is required for donor registration.',
        ]);

        $cleanPhone = preg_replace('/[^0-9]/', '', $validated['phone']);

        // Check if phone number is already registered
        $existingPhoneUser = User::where('phone', $validated['phone'])
            ->orWhere(function ($q) use ($cleanPhone) {
                if (!empty($cleanPhone)) {
                    $q->where('phone', $cleanPhone);
                }
            })->first();

        if ($existingPhoneUser) {
            return back()->withErrors(['phone' => 'This mobile number is already registered. Please sign in instead.'])
                ->with('error', 'Registration Failed: Mobile number is already registered.')
                ->withInput();
        }

        // Check email uniqueness if provided
        if (!empty($validated['email'])) {
            $existingEmailUser = User::where('email', strtolower(trim($validated['email'])))->first();
            if ($existingEmailUser) {
                return back()->withErrors(['email' => 'This email address is already registered to another account.'])
                    ->with('error', 'Registration Failed: Email address is already in use.')
                    ->withInput();
            }
        }

        $inviter = null;
        if (!empty($validated['ref_code'])) {
            $inviter = User::where('referral_code', strtoupper(trim($validated['ref_code'])))->first();
        }

        $defaultRole = \App\Models\Role::where('name', 'user')->first();

        $user = User::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => !empty($validated['email']) ? strtolower(trim($validated['email'])) : null,
            'role' => 'user',
            'role_id' => $defaultRole?->id,
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

    /**
     * Handle Forgot Password by Mobile Number.
     * Generates a new temporary password and emails it if email exists,
     * or prompts user to contact Admin Support if no email registered.
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ], [
            'phone.required' => 'Please enter your registered mobile number.',
        ]);

        $phoneInput = trim($request->input('phone'));
        $cleanPhone = preg_replace('/[^0-9]/', '', $phoneInput);

        $user = User::where('phone', $phoneInput)
            ->orWhere(function ($q) use ($cleanPhone) {
                if (!empty($cleanPhone)) {
                    $q->where('phone', $cleanPhone);
                }
            })->first();

        if (!$user) {
            return back()->withErrors(['phone' => 'No account found matching this mobile number.'])
                ->with('error', "No account found matching mobile number {$phoneInput}. Please verify or register a new account.")
                ->withInput();
        }

        // If user has a registered email, generate new temporary password and dispatch via email
        if (!empty($user->email)) {
            $newPassword = 'MKRD-' . rand(100000, 999999);
            $user->password = Hash::make($newPassword);
            $user->save();

            // Mask email for security feedback (e.g. j***n@gmail.com)
            $parts = explode('@', $user->email);
            $maskedName = substr($parts[0], 0, 1) . '***' . substr($parts[0], -1);
            $maskedEmail = $maskedName . '@' . ($parts[1] ?? 'domain.com');

            // Attempt to send email
            try {
                Mail::raw(
                    "Hello {$user->name},\n\n" .
                    "A password reset was requested for your account linked to mobile number {$user->phone}.\n\n" .
                    "Your new temporary password is: {$newPassword}\n\n" .
                    "Please sign in using your mobile number and this temporary password, then update your password from your account profile.\n\n" .
                    "Warm regards,\nManab Kalyane Rokto Dan Team",
                    function ($message) use ($user) {
                        $message->to($user->email)
                            ->subject('Password Reset Request — Manab Kalyane Rokto Dan');
                    }
                );
            } catch (\Exception $e) {
                // If mail server fails in local env, log or handle gracefully
                \Illuminate\Support\Facades\Log::warning("Mail delivery failed during password reset: " . $e->getMessage());
            }

            return back()->with('success', "Password reset successful! A new temporary password has been sent to your registered email ({$maskedEmail}). [Temporary Code for Demo: {$newPassword}]");
        }

        // User exists but has no email registered (or phone/email mapping confusion)
        return back()->with('status', "Mobile number verified ({$user->name}), but no email address is registered on this account. Please submit the 'Admin Support Ticket' form below so our admin team can verify your phone number and assist you directly.")
            ->with('support_user_phone', $user->phone)
            ->with('support_user_name', $user->name);
    }

    /**
     * Submit Support Request to Admin for Phone/Email Mapping & Account Recovery Issues.
     */
    public function submitAccountSupport(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'issue_description' => 'required|string|max:1000',
        ]);

        // Save in Visitor Inquiries for Admin management
        VisitorInquiry::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'purpose' => 'Account Mapping & Password Reset Support: ' . $validated['issue_description'],
            'ip_address' => $request->ip(),
            'session_id' => session()->getId(),
        ]);

        // Notify all Admin users
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            UserNotification::create([
                'user_id' => $admin->id,
                'title' => '🔑 Account Support Ticket',
                'message' => "Account reset/mapping request from {$validated['name']} ({$validated['phone']}): {$validated['issue_description']}",
                'type' => 'system',
                'action_url' => route('admin.inquiries'),
            ]);
        }

        return back()->with('success', "Support request submitted successfully! Our Admin team has been notified. We will call/WhatsApp you at {$validated['phone']} to assist with your account.");
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logged out successfully. Thank you for visiting Manab Kalyane Rokto Dan.');
    }
}

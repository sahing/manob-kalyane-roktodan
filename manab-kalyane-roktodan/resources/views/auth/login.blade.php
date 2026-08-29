@extends('layouts.app')

@section('title', 'Sign In / Register — Manab Kalyane Rokto Dan')

@section('content')
<div class="max-w-md mx-auto px-4 py-12" x-data="{ mode: '{{ session('status') ? 'support' : (($errors->has('name') || $errors->has('phone') || $errors->has('password_confirmation') || old('block')) ? 'signup' : 'login') }}' }">
    <div class="glass-card p-8 rounded-3xl border border-slate-300 dark:border-slate-800 shadow-2xl">
        <div class="text-center mb-6">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-brand-700 to-rose-500 text-white flex items-center justify-center mx-auto mb-3 shadow-lg glow-red">
                <svg class="w-7 h-7 fill-current animate-heartbeat" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path></svg>
            </div>
            
            <!-- Segmented Toggle Bar -->
            <div class="flex items-center justify-center p-1 rounded-2xl bg-slate-200 dark:bg-slate-900 border border-slate-300 dark:border-slate-800 max-w-xs mx-auto mb-2">
                <button type="button" @click="mode = 'login'" :class="mode === 'login' ? 'bg-rose-600 text-white font-extrabold shadow-md' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="w-1/2 py-2 rounded-xl text-xs transition duration-200">
                    Sign In
                </button>
                <button type="button" @click="mode = 'signup'" :class="mode === 'signup' ? 'bg-rose-600 text-white font-extrabold shadow-md' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="w-1/2 py-2 rounded-xl text-xs transition duration-200">
                    Register Account
                </button>
            </div>
        </div>

        <!-- Global Error Alerts -->
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-600 dark:text-rose-400 text-xs space-y-1.5 shadow-sm">
                <div class="font-extrabold text-xs uppercase tracking-wider flex items-center gap-1.5 text-rose-700 dark:text-rose-300">
                    <span>⚠️</span> Authentication Error
                </div>
                <ul class="list-disc list-inside space-y-1 font-semibold">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-600 dark:text-rose-400 text-xs font-bold flex items-center gap-2 shadow-sm">
                <span>⚠️</span> {{ session('error') }}
            </div>
        @endif

        <!-- Login Form (Mobile Number Default) -->
        <div x-show="mode === 'login'">
            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase flex items-center justify-between">
                        <span>📱 Mobile Number (or Email)</span>
                        <span class="text-[10px] text-rose-500 font-bold lowercase">Required</span>
                    </label>
                    <input type="text" name="login_id" value="{{ old('login_id') }}" required autofocus placeholder="e.g. 9832100000 or email" class="w-full bg-slate-100 dark:bg-slate-900 border @error('login_id') border-rose-500 focus:ring-rose-500 @else border-slate-300 dark:border-slate-700 focus:border-rose-500 @enderror rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none font-medium">
                    @error('login_id')
                        <span class="text-rose-500 text-[11px] font-bold mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase">Password</label>
                        <button type="button" @click="mode = 'forgot'" class="text-[11px] font-bold text-rose-600 dark:text-rose-400 hover:underline">Forgot Password?</button>
                    </div>
                    <input type="password" name="password" required class="w-full bg-slate-100 dark:bg-slate-900 border @error('password') border-rose-500 focus:ring-rose-500 @else border-slate-300 dark:border-slate-700 focus:border-rose-500 @enderror rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none">
                    @error('password')
                        <span class="text-rose-500 text-[11px] font-bold mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded bg-slate-900 border-slate-700 text-rose-600 focus:ring-rose-500">
                        <span>Remember Me</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-brand-600 to-rose-600 text-white font-extrabold py-3 rounded-xl hover:opacity-95 shadow-md transition">
                    Sign In via Mobile / Email
                </button>
            </form>

            <div class="mt-6 pt-4 border-t border-slate-200 dark:border-slate-800 text-center text-xs text-slate-500 dark:text-slate-400">
                Don't have an account yet?
                <button type="button" @click="mode = 'signup'" class="text-rose-600 dark:text-rose-400 font-bold hover:underline">Register New Account</button>
            </div>
        </div>

        <!-- Register Form (Email Optional) -->
        <div x-show="mode === 'signup'">
            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-slate-100 dark:bg-slate-900 border @error('name') border-rose-500 @else border-slate-300 dark:border-slate-700 @enderror rounded-xl px-4 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                    @error('name')
                        <span class="text-rose-500 text-[11px] font-bold mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">📱 Phone (Required)</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="10-digit mobile" class="w-full bg-slate-100 dark:bg-slate-900 border @error('phone') border-rose-500 @else border-slate-300 dark:border-slate-700 @enderror rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-medium">
                        @error('phone')
                            <span class="text-rose-500 text-[11px] font-bold mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">✉️ Email (Optional)</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Optional" class="w-full bg-slate-100 dark:bg-slate-900 border @error('email') border-rose-500 @else border-slate-300 dark:border-slate-700 @enderror rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                        @error('email')
                            <span class="text-rose-500 text-[11px] font-bold mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Blood Group</label>
                        <select name="blood_group" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-bold">
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $g)
                                <option value="{{ $g }}" {{ old('blood_group') == $g ? 'selected' : '' }}>{{ $g }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Block</label>
                        <select name="block" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-medium">
                            <option value="Bhagwangola-I" {{ old('block') == 'Bhagwangola-I' ? 'selected' : '' }}>Bhagwangola-I</option>
                            <option value="Bhagwangola-II" {{ old('block') == 'Bhagwangola-II' ? 'selected' : '' }}>Bhagwangola-II</option>
                            <option value="Lalgola" {{ old('block') == 'Lalgola' ? 'selected' : '' }}>Lalgola</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Referral Code (Optional)</label>
                    <input type="text" name="ref_code" value="{{ old('ref_code') }}" placeholder="e.g. MKRD-REF-0001" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-xs font-mono font-bold text-slate-900 dark:text-white uppercase focus:outline-none focus:border-rose-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Password</label>
                        <input type="password" name="password" required class="w-full bg-slate-100 dark:bg-slate-900 border @error('password') border-rose-500 @else border-slate-300 dark:border-slate-700 @enderror rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Confirm</label>
                        <input type="password" name="password_confirmation" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                    </div>
                </div>
                @error('password')
                    <span class="text-rose-500 text-[11px] font-bold block">{{ $message }}</span>
                @enderror
                <button type="submit" class="w-full bg-gradient-to-r from-brand-600 to-rose-600 text-white font-bold py-3 rounded-xl hover:opacity-95 shadow-md transition">
                    Create Voluntary Account
                </button>
                <div class="mt-4 text-center text-xs text-slate-500 dark:text-slate-400">
                    Already registered? <button type="button" @click="mode = 'login'" class="text-rose-600 dark:text-rose-400 font-bold hover:underline">Sign In</button>
                </div>
            </form>
        </div>

        <!-- Forgot Password View (Mobile Number Lookup) -->
        <div x-show="mode === 'forgot'" x-cloak>
            <div class="mb-4 text-center">
                <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center justify-center gap-1.5">
                    <span>🔑</span> Reset Password via Mobile
                </h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">
                    Enter your registered 10-digit mobile number below. A new password reset code will be generated and dispatched to your linked email address.
                </p>
            </div>

            <form action="{{ route('password.forgot') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Registered Mobile Number</label>
                    <input type="tel" name="phone" required placeholder="Enter 10-digit registered phone" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-mono font-bold">
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-brand-600 to-rose-600 text-white font-extrabold py-3 rounded-xl hover:opacity-95 shadow-md transition">
                    Reset & Send Password to Email
                </button>
            </form>

            <div class="mt-6 pt-4 border-t border-slate-200 dark:border-slate-800 text-center space-y-2">
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    No email registered or confusion mapping phone & email?
                </p>
                <button type="button" @click="mode = 'support'" class="w-full py-2.5 rounded-xl text-xs font-bold bg-amber-500/20 text-amber-700 dark:text-amber-300 border border-amber-500/30 hover:bg-amber-500/30 transition">
                    💬 Contact Admin Support / Request Account Mapping
                </button>
                <button type="button" @click="mode = 'login'" class="text-xs text-slate-500 hover:text-slate-900 dark:hover:text-white block mx-auto mt-2">
                    ← Back to Sign In
                </button>
            </div>
        </div>

        <!-- Admin Support Request View -->
        <div x-show="mode === 'support'" x-cloak>
            <div class="mb-4 text-center">
                <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center justify-center gap-1.5">
                    <span>🛡️</span> Admin Account Recovery Ticket
                </h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">
                    If you have confusion with your registered mobile/email mapping, submit a support ticket below. Our Admin team will verify your phone number and contact you directly.
                </p>
            </div>

            <form action="{{ route('support.account-issue') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase">Your Full Name</label>
                    <input type="text" name="name" value="{{ session('support_user_name') }}" required placeholder="Enter your full name" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase">Your Mobile Number</label>
                    <input type="tel" name="phone" value="{{ session('support_user_phone') }}" required placeholder="10-digit mobile number" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-mono font-bold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase">Issue Details / Email Confusion</label>
                    <textarea name="issue_description" rows="3" required placeholder="Describe the issue (e.g., I lost access to my email address or need my mobile number mapped to my account)..." class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500"></textarea>
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-amber-600 to-rose-600 text-white font-extrabold py-3 rounded-xl hover:opacity-95 shadow-md transition">
                    Submit Verification Ticket to Admin
                </button>
                <button type="button" @click="mode = 'login'" class="w-full text-xs text-slate-500 hover:text-slate-900 dark:hover:text-white text-center mt-2 block">
                    ← Back to Sign In
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

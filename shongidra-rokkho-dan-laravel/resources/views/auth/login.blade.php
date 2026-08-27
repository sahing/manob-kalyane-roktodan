@extends('layouts.app')

@section('title', 'Sign In / Register — Manab Kalyane Rokto Dan')

@section('content')
<div class="max-w-md mx-auto px-4 py-12" x-data="{ mode: 'login' }">
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

        <!-- Login Form -->
        <div x-show="mode === 'login'">
            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Password</label>
                    <input type="password" name="password" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>

                <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded bg-slate-900 border-slate-700 text-rose-600 focus:ring-rose-500">
                        <span>Remember Me</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-brand-600 to-rose-600 text-white font-extrabold py-3 rounded-xl hover:opacity-95 shadow-md transition">
                    Sign In to Account
                </button>
            </form>

            <div class="mt-6 pt-4 border-t border-slate-200 dark:border-slate-800 text-center text-xs text-slate-500 dark:text-slate-400">
                Don't have an account?
                <button type="button" @click="mode = 'signup'" class="text-rose-600 dark:text-rose-400 font-bold hover:underline">Register New Account</button>
            </div>
        </div>

        <!-- Register Form -->
        <div x-show="mode === 'signup'">
            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Full Name</label>
                    <input type="text" name="name" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Email</label>
                        <input type="email" name="email" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Phone</label>
                        <input type="tel" name="phone" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Blood Group</label>
                        <select name="blood_group" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-bold">
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $g)
                                <option value="{{ $g }}">{{ $g }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Block</label>
                        <select name="block" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-medium">
                            <option value="Bhagwangola-I">Bhagwangola-I</option>
                            <option value="Bhagwangola-II">Bhagwangola-II</option>
                            <option value="Lalgola">Lalgola</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Referral Code (Optional)</label>
                    <input type="text" name="ref_code" placeholder="e.g. MKRD-REF-0001" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-xs font-mono font-bold text-slate-900 dark:text-white uppercase focus:outline-none focus:border-rose-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Password</label>
                        <input type="password" name="password" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Confirm</label>
                        <input type="password" name="password_confirmation" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                    </div>
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-brand-600 to-rose-600 text-white font-bold py-3 rounded-xl hover:opacity-95 shadow-md transition">
                    Create Voluntary Account
                </button>
                <div class="mt-4 text-center text-xs text-slate-500 dark:text-slate-400">
                    Already registered? <button type="button" @click="mode = 'login'" class="text-rose-600 dark:text-rose-400 font-bold hover:underline">Sign In</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

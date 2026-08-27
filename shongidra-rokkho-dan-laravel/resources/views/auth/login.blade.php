@extends('layouts.app')

@section('title', 'Login — Manab Kalyane Rokto Dan')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="glass-card p-8 rounded-2xl border border-slate-800 shadow-2xl">
        <div class="text-center mb-8">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-brand-700 to-rose-500 text-white flex items-center justify-center mx-auto mb-3 shadow-md glow-red">
                <svg class="w-7 h-7 fill-current" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path></svg>
            </div>
            <h1 class="text-2xl font-extrabold text-white">Donor Portal Login</h1>
            <p class="text-xs text-slate-400 mt-1">Access your dashboard, donation history, & donor badge.</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5 uppercase">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-rose-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5 uppercase">Password</label>
                <input type="password" name="password" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-rose-500">
            </div>

            <div class="flex items-center justify-between text-xs text-slate-400">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded bg-slate-900 border-slate-700 text-rose-600 focus:ring-rose-500">
                    <span>Remember Me</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-brand-600 to-rose-600 text-white font-bold py-3 rounded-xl hover:opacity-95 shadow-md transition">
                Sign In to Dashboard
            </button>
        </form>

        <div class="mt-6 pt-4 border-t border-slate-800 text-center text-xs text-slate-400">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-rose-400 font-bold hover:underline">Register as Donor</a>
        </div>
    </div>
</div>
@endsection

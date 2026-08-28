@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center max-w-2xl mx-auto mb-10">
        <span class="inline-block px-3.5 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 mb-2">
            Public Authenticity Portal
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">Verify Donor ID Card or Certificate</h1>
        <p class="text-sm text-slate-600 dark:text-slate-400 mt-2 leading-relaxed">Enter a unique Donor ID (e.g. <code class="font-mono font-bold text-rose-600">MKRD-00001</code>) or Certificate Reference (e.g. <code class="font-mono font-bold text-rose-600">CERT-8F3A2B1C</code>) to instantly verify authenticity.</p>
    </div>

    <div class="glass-card max-w-xl mx-auto p-8 rounded-3xl shadow-2xl border border-slate-300 dark:border-slate-800">
        <form action="{{ route('verify.index') }}" method="GET" class="space-y-4">
            <div>
                <label class="block text-xs font-extrabold uppercase text-slate-700 dark:text-slate-300 mb-2">Donor ID / Certificate Reference</label>
                <div class="relative">
                    <input type="text" name="code" placeholder="Enter MKRD-00001 or CERT-XXXXXX" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-2xl px-5 py-3.5 text-base font-mono font-bold text-slate-900 dark:text-white uppercase tracking-wider focus:outline-none focus:border-rose-500">
                    <button type="submit" class="absolute right-2 top-2 bottom-2 px-5 rounded-xl font-extrabold text-xs bg-gradient-to-r from-brand-600 to-rose-600 text-white shadow-md hover:opacity-95 transition">
                        Verify Now 🔍
                    </button>
                </div>
            </div>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-800 text-center text-xs text-slate-500">
            <span class="inline-flex items-center gap-1 font-semibold text-emerald-600 dark:text-emerald-400">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                Cryptographically Signed Verification System
            </span>
            <p class="mt-1">Maintained by Manab Kalyane Rokto Dan Voluntary Committee, Bhagwangola, Murshidabad.</p>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Action Bar -->
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('verify.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 dark:hover:text-white">← Search Another ID</a>
        <button onclick="window.print()" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-900 dark:bg-slate-800 text-white hover:bg-rose-600 shadow-md transition flex items-center gap-1.5">
            🖨️ Print Verification Record
        </button>
    </div>

    <!-- Official Verified Card Container -->
    <div class="glass-card rounded-3xl p-8 sm:p-12 shadow-2xl border-2 border-emerald-500/40 relative overflow-hidden">
        
        <!-- Ambient Verified Background Glow -->
        <div class="absolute -top-24 -right-24 w-72 h-72 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Verification Banner Header -->
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-2xl p-4 sm:p-6 mb-8 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-xl">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 rounded-full bg-white text-emerald-600 flex items-center justify-center font-black text-2xl shadow-inner">
                    ✓
                </div>
                <div>
                    <span class="text-xs uppercase font-extrabold tracking-widest text-emerald-100 block">Official Verification Status</span>
                    <h2 class="text-xl sm:text-2xl font-black tracking-tight">AUTHENTIC & VERIFIED DONOR</h2>
                </div>
            </div>
            <div class="text-center sm:text-right">
                <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-white/20 text-white border border-white/30 font-mono">
                    REF: {{ strtoupper($code) }}
                </span>
                <span class="text-[10px] text-emerald-100 block mt-1">Verified on {{ now()->format('d M Y, h:i A') }}</span>
            </div>
        </div>

        <!-- Main Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center mb-8">
            
            <!-- Left Info Column -->
            <div class="md:col-span-2 space-y-4">
                <div>
                    <span class="text-xs uppercase font-bold text-slate-500 dark:text-slate-400 block">Registered Donor Name</span>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white mt-0.5">{{ $user->name }}</h3>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-2">
                    <div>
                        <span class="text-[11px] uppercase font-bold text-slate-500 dark:text-slate-400 block">Donor ID Code</span>
                        <span class="text-base font-mono font-extrabold text-rose-600 dark:text-rose-400">{{ $donor->donor_code ?? ('MKRD-' . str_pad($user->id, 5, '0', STR_PAD_LEFT)) }}</span>
                    </div>

                    <div>
                        <span class="text-[11px] uppercase font-bold text-slate-500 dark:text-slate-400 block">Donor Honor Rank</span>
                        <span class="text-sm font-extrabold text-emerald-600 dark:text-emerald-400">{{ $badge }}</span>
                    </div>

                    <div>
                        <span class="text-[11px] uppercase font-bold text-slate-500 dark:text-slate-400 block">Location / Block</span>
                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $donor->block ?? 'Bhagwangola' }}, Murshidabad</span>
                    </div>

                    <div>
                        <span class="text-[11px] uppercase font-bold text-slate-500 dark:text-slate-400 block">Total Lifetime Donations</span>
                        <span class="text-sm font-extrabold text-slate-900 dark:text-white">{{ $totalDonations }} Times Verified</span>
                    </div>
                </div>

                @if($donation)
                    <div class="mt-4 p-4 rounded-2xl bg-slate-100 dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 space-y-1">
                        <span class="text-[10px] uppercase font-extrabold text-rose-600 dark:text-rose-400 block">Specific Certificate Info</span>
                        <div class="text-xs font-bold text-slate-800 dark:text-slate-200">Certificate ID: <span class="font-mono text-rose-600">{{ $donation->certificate_id }}</span></div>
                        <div class="text-xs text-slate-600 dark:text-slate-400">Donation Date: {{ $donation->donation_date ? $donation->donation_date->format('d M Y') : 'Recorded' }}</div>
                        <div class="text-xs text-slate-600 dark:text-slate-400">Location: {{ $donation->location ?: 'Bhagwangola Blood Camp' }}</div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Blood Group Badge & Verification QR -->
            <div class="text-center flex flex-col items-center justify-center p-6 rounded-3xl bg-slate-100 dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800">
                <div class="w-24 h-24 rounded-3xl bg-gradient-to-tr from-rose-600 to-brand-700 text-white font-black text-4xl flex items-center justify-center shadow-2xl border-2 border-rose-400/40 mb-3 glow-red">
                    {{ $donor->blood_group ?? 'A+' }}
                </div>
                <span class="text-xs uppercase font-extrabold text-slate-600 dark:text-slate-300 block mb-4">Verified Blood Group</span>

                <!-- QR Code Seal -->
                <div class="bg-white p-3 rounded-2xl border border-slate-300 shadow-md">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=110x110&data={{ urlencode(url()->current()) }}" alt="Verification QR" class="w-24 h-24">
                </div>
                <span class="text-[10px] font-mono text-slate-500 mt-2">Scan to re-verify anytime</span>
            </div>
        </div>

        <!-- Footnote & Official Stamp -->
        <div class="pt-6 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
            <div>
                <span class="font-bold text-slate-800 dark:text-slate-200 block">Issued By: Manab Kalyane Rokto Dan Society</span>
                <span>Bhagwangola-I & Bhagwangola-II Voluntary Network, Murshidabad.</span>
            </div>
            <div class="text-right">
                <span class="px-3 py-1.5 rounded-xl text-[11px] font-extrabold bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 inline-block">
                    ✓ Official Electronic Seal
                </span>
            </div>
        </div>

    </div>
</div>
@endsection

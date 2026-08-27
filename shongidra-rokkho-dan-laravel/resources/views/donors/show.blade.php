@extends('layouts.app')

@section('title', $donor->user->name . ' (' . $donor->blood_group . ') — Verified Voluntary Blood Donor Profile')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ inquiryModal: false }">
    
    <!-- Back to Search Link -->
    <div class="mb-6">
        <a href="{{ route('donors.search') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 transition">
            <svg class="w-4 h-4 fill-none stroke-current" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Back to Donor Search</span>
        </a>
    </div>

    <!-- Main Donor Profile Card -->
    <div class="glass-card rounded-3xl overflow-hidden shadow-2xl border border-slate-300 dark:border-slate-800 mb-8 relative">
        <!-- Top Gradient Hero Cover -->
        <div class="h-36 sm:h-44 bg-gradient-to-r from-rose-900 via-brand-800 to-rose-950 relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-rose-500/20 rounded-full blur-2xl"></div>
            <div class="absolute top-4 right-4 flex items-center gap-2">
                @if(!$donor->allow_direct_contact)
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase bg-amber-950/80 text-amber-300 border border-amber-500/40 backdrop-blur-md">
                        🔒 Contact via Society Only
                    </span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase bg-slate-950/70 text-emerald-300 border border-emerald-500/40 backdrop-blur-md">
                        ✓ Verified Voluntary Donor
                    </span>
                @endif
            </div>
        </div>

        <div class="p-6 sm:p-8 pt-0 relative">
            <!-- Avatar & Primary Info -->
            <div class="flex flex-col sm:flex-row items-center sm:items-end justify-between -mt-16 sm:-mt-20 gap-4 mb-6 pb-6 border-b border-slate-200 dark:border-slate-800">
                <div class="flex flex-col sm:flex-row items-center sm:items-end gap-4 text-center sm:text-left">
                    <!-- Profile Avatar Image -->
                    <div class="relative">
                        <img src="{{ $donor->user->avatar_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($donor->user->name) . '&background=e11d48&color=fff&size=200&bold=true' }}" 
                             alt="{{ $donor->user->name }}" 
                             class="w-28 h-28 sm:w-36 sm:h-36 rounded-3xl object-cover border-4 border-white dark:border-slate-900 shadow-2xl bg-slate-900">
                        <span class="absolute -bottom-2 -right-2 w-10 h-10 rounded-2xl bg-gradient-to-tr from-brand-700 to-rose-600 text-white font-extrabold text-sm flex items-center justify-center shadow-lg glow-red border-2 border-white dark:border-slate-900">
                            {{ $donor->blood_group }}
                        </span>
                    </div>

                    <div class="space-y-1">
                        <div class="flex items-center justify-center sm:justify-start gap-2">
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                                {{ $donor->user->name }}
                            </h1>
                            <span class="text-emerald-500 text-lg" title="Verified Donor">✓</span>
                        </div>
                        
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 text-xs font-bold">
                            <span class="px-2.5 py-0.5 rounded-lg bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 font-mono">
                                {{ $donor->donor_code ?: 'MKRD-DONOR-' . str_pad($donor->id, 5, '0', STR_PAD_LEFT) }}
                            </span>
                            <span class="text-slate-400">•</span>
                            <span class="text-amber-500 font-extrabold">{{ $donor->user->loyalty_rank }}</span>
                        </div>

                        <p class="text-xs text-slate-600 dark:text-slate-400">
                            📍 {{ $donor->village ? $donor->village . ', ' : '' }}{{ $donor->block }}, {{ $donor->district }}
                        </p>
                    </div>
                </div>

                <!-- Call / Request Action Buttons -->
                <div class="w-full sm:w-auto flex items-center gap-3">
                    @if(!$donor->allow_direct_contact)
                        <button @click="inquiryModal = true" class="w-full sm:w-auto px-6 py-3 rounded-2xl font-extrabold text-xs bg-slate-800 text-amber-300 border border-amber-500/30 hover:bg-slate-700 transition flex items-center justify-center gap-2 shadow-lg">
                            <span>🛡️ Request Contact via Society</span>
                        </button>
                    @elseif($inquiryGatePassed)
                        <a href="tel:{{ $donor->user->phone }}" class="flex-1 sm:flex-none px-6 py-3 rounded-2xl font-extrabold text-xs bg-emerald-600 hover:bg-emerald-500 text-white transition flex items-center justify-center gap-2 shadow-lg">
                            <svg class="w-4 h-4 fill-none stroke-current" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span>Call {{ $donor->user->phone }}</span>
                        </a>
                        <a href="https://wa.me/91{{ $donor->user->phone }}?text=Hello%20{{ urlencode($donor->user->name) }},%20urgent%20blood%20request%20from%20Manab%20Kalyane%20Rokto%20Dan" target="_blank" class="px-4 py-3 rounded-2xl font-extrabold text-xs bg-slate-200 dark:bg-slate-800 text-emerald-600 dark:text-emerald-400 hover:bg-slate-300 dark:hover:bg-slate-700 transition">
                            WhatsApp
                        </a>
                    @else
                        <button @click="inquiryModal = true" class="w-full sm:w-auto px-6 py-3 rounded-2xl font-extrabold text-xs bg-gradient-to-r from-brand-600 to-rose-600 text-white shadow-lg glow-red hover:opacity-95 transition flex items-center justify-center gap-2">
                            <span>🩸 Request Contact Info</span>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Profile Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="glass-card p-4 rounded-2xl border border-slate-200 dark:border-slate-800 text-center">
                    <span class="text-xs text-slate-500 dark:text-slate-400 block font-semibold mb-1">Blood Group</span>
                    <span class="text-2xl font-extrabold text-rose-600 dark:text-rose-400">{{ $donor->blood_group }}</span>
                </div>
                <div class="glass-card p-4 rounded-2xl border border-slate-200 dark:border-slate-800 text-center">
                    <span class="text-xs text-slate-500 dark:text-slate-400 block font-semibold mb-1">Eligibility Status</span>
                    @if($donor->is_eligible)
                        <span class="text-xs font-extrabold text-emerald-600 dark:text-emerald-400 flex items-center justify-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Ready to Donate
                        </span>
                    @else
                        <span class="text-xs font-bold text-amber-500">Wait until {{ $donor->next_eligible_date?->format('d M Y') }}</span>
                    @endif
                </div>
                <div class="glass-card p-4 rounded-2xl border border-slate-200 dark:border-slate-800 text-center">
                    <span class="text-xs text-slate-500 dark:text-slate-400 block font-semibold mb-1">Total Donations</span>
                    <span class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ $donor->user->donations->count() }} Times</span>
                </div>
                <div class="glass-card p-4 rounded-2xl border border-slate-200 dark:border-slate-800 text-center">
                    <span class="text-xs text-slate-500 dark:text-slate-400 block font-semibold mb-1">Privacy Mode</span>
                    <span class="text-xs font-extrabold {{ $donor->allow_direct_contact ? 'text-emerald-500' : 'text-amber-500' }}">
                        {{ $donor->allow_direct_contact ? 'Direct Contact' : '🔒 Society Helpline' }}
                    </span>
                </div>
            </div>

            <!-- Official Donation Records Section -->
            <div>
                <h3 class="text-lg font-extrabold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <span>📜 Official Donation History</span>
                </h3>

                @if($donor->user->donations->count() > 0)
                    <div class="space-y-3">
                        @foreach($donor->user->donations as $don)
                            <div class="glass-card p-4 rounded-2xl flex items-center justify-between border border-slate-200 dark:border-slate-800">
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">{{ $don->location ?: 'Bhagwangola Rural Hospital' }}</h4>
                                    <span class="text-xs text-slate-500 block">Donated on {{ $don->donation_date?->format('d M Y') }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="px-3 py-1 rounded-full text-[11px] font-extrabold bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30">
                                        Certificate Verified
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-6 text-center glass-card rounded-2xl text-xs text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-800">
                        No official donation logs recorded yet. Registered as active voluntary donor.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Inquiry Gate Modal -->
    <div x-show="inquiryModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
        <div class="glass-card max-w-md w-full p-6 sm:p-8 rounded-3xl border border-slate-300 dark:border-slate-800 relative shadow-2xl">
            <button @click="inquiryModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-900 dark:hover:text-white text-2xl font-bold">&times;</button>

            <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-1">Request Donor Contact</h3>
            <p class="text-xs text-slate-600 dark:text-slate-400 mb-4">Please provide your details to request contact for <span class="text-rose-600 dark:text-rose-400 font-bold">{{ $donor->user->name }} ({{ $donor->blood_group }})</span>.</p>

            <form action="{{ route('inquiry.submit') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="donor_name" value="{{ $donor->user->name }}">
                <input type="hidden" name="blood_group" value="{{ $donor->blood_group }}">

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase">Your Full Name</label>
                    <input type="text" name="name" required placeholder="Enter your full name" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase">Your Phone Number</label>
                    <input type="tel" name="phone" required placeholder="Enter 10-digit mobile number" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-brand-600 to-rose-600 text-white font-bold py-3 rounded-xl hover:opacity-95 shadow-md transition">
                    Submit & Request Donor
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

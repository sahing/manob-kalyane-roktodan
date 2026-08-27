@extends('layouts.app')

@section('title', 'Donor Dashboard — Manab Kalyane Rokto Dan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ storyModal: false }">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Welcome, {{ $user->name }}</h1>
            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Role: <span class="uppercase font-bold text-rose-600 dark:text-rose-400">{{ $user->role }}</span> | Phone: {{ $user->phone }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <button @click="storyModal = true" class="px-4 py-2.5 rounded-xl font-bold text-xs bg-gradient-to-r from-brand-600 to-rose-600 text-white shadow-md hover:opacity-95 transition flex items-center gap-1.5">
                ✍️ Share Experience & Photo
            </button>
            <a href="{{ route('dashboard.card') }}" class="px-4 py-2.5 rounded-xl font-bold text-xs bg-slate-200 dark:bg-slate-800 text-rose-600 dark:text-rose-300 border border-slate-300 dark:border-slate-700 hover:bg-slate-300 dark:hover:bg-slate-700 transition">
                🪪 Digital Donor Card
            </a>
            <a href="{{ route('dashboard.profile') }}" class="px-4 py-2.5 rounded-xl font-bold text-xs bg-slate-200 dark:bg-slate-800 text-slate-800 dark:text-slate-300 border border-slate-300 dark:border-slate-700 hover:bg-slate-300 dark:hover:bg-slate-700 transition">
                Edit Profile
            </a>
        </div>
    </div>

    <!-- Eligibility Box -->
    <div class="glass-card p-6 rounded-2xl mb-8 grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-brand-700 to-rose-500 text-white font-black text-2xl flex items-center justify-center shadow-lg glow-red">
                {{ $user->donorProfile?->blood_group ?? 'N/A' }}
            </div>
            <div>
                <span class="text-xs uppercase text-slate-500 dark:text-slate-400 font-semibold block">Blood Group</span>
                <span class="text-lg font-bold text-slate-900 dark:text-white">{{ $user->donorProfile?->blood_group ?? 'Not Set' }}</span>
            </div>
        </div>

        <div class="text-center md:border-x border-slate-200 dark:border-slate-800 px-4">
            <span class="text-xs uppercase text-slate-500 dark:text-slate-400 font-semibold block mb-1">Donation Eligibility Status</span>
            @if($isEligible)
                <span class="inline-block px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-500/20 text-emerald-800 dark:text-emerald-300 border border-emerald-500/30">
                    ✓ ELIGIBLE TO DONATE BLOOD NOW
                </span>
            @else
                <span class="inline-block px-3 py-1 rounded-full text-xs font-extrabold bg-amber-500/20 text-amber-800 dark:text-amber-300 border border-amber-500/30">
                    Next Eligible Date: {{ $nextEligibleDate->format('d M Y') }}
                </span>
            @endif
        </div>

        <div class="text-right">
            <span class="text-xs uppercase text-slate-500 dark:text-slate-400 font-semibold block mb-1">Total Logged Donations</span>
            <span class="text-3xl font-black text-slate-900 dark:text-white">{{ count($user->donations) }} Times</span>
        </div>
    </div>

    <!-- Inspire Others Banner -->
    <div class="glass-card p-6 rounded-2xl border-rose-900/30 bg-gradient-to-r from-rose-950/20 via-slate-900 to-slate-950 mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-extrabold text-white flex items-center gap-2">
                <span>📸</span> Inspire Other Donors in Bhagwangola
            </h3>
            <p class="text-xs text-slate-300 mt-1">Have you recently donated blood or attended a voluntary camp? Share your photo and experience to motivate fellow youth!</p>
        </div>
        <div>
            <button @click="storyModal = true" class="px-5 py-2.5 rounded-xl font-extrabold text-xs bg-rose-600 hover:bg-rose-500 text-white transition shadow-md whitespace-nowrap">
                + Write Story & Upload Photo
            </button>
        </div>
    </div>

    <!-- Donation History -->
    <div class="glass-card p-6 rounded-2xl">
        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Your Blood Donation History</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700 dark:text-slate-300">
                <thead class="text-[11px] uppercase bg-slate-100 dark:bg-slate-900/80 text-slate-600 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="p-3">Date</th>
                        <th class="p-3">Location / Camp</th>
                        <th class="p-3">Certificate ID</th>
                        <th class="p-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($user->donations as $donation)
                        <tr class="hover:bg-slate-100 dark:hover:bg-slate-900/50">
                            <td class="p-3 font-semibold text-slate-900 dark:text-white">{{ $donation->donation_date->format('d M Y') }}</td>
                            <td class="p-3">{{ $donation->location ?? 'Bhagwangola Voluntary Camp' }}</td>
                            <td class="p-3 font-mono text-rose-600 dark:text-rose-400 font-bold">{{ $donation->certificate_id ?? 'N/A' }}</td>
                            <td class="p-3 text-right">
                                <a href="{{ route('dashboard.certificate', $donation->id) }}" target="_blank" class="px-3 py-1.5 rounded-lg font-bold text-xs bg-slate-200 dark:bg-slate-800 text-rose-600 dark:text-rose-300 border border-slate-300 dark:border-slate-700 hover:bg-slate-300 dark:hover:bg-slate-700 transition">
                                    📜 View Certificate
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-slate-500">
                                No recorded blood donations yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Share Experience Modal -->
    <div x-show="storyModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
        <div class="glass-card max-w-lg w-full p-6 sm:p-8 rounded-2xl border border-slate-300 dark:border-slate-800 relative shadow-2xl overflow-y-auto max-h-[90vh]">
            <button @click="storyModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-900 dark:hover:text-white text-2xl font-bold">&times;</button>

            <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-1 flex items-center gap-2">
                <span class="text-rose-600 dark:text-rose-500">✍️</span> Share Your Donation Experience
            </h3>
            <p class="text-xs text-slate-600 dark:text-slate-400 mb-6">Inspire community members in Bhagwangola & Murshidabad to become voluntary donors.</p>

            <form action="{{ route('stories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase">Your Name</label>
                        <input type="text" name="donor_name" value="{{ $user->name }}" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase">Blood Group</label>
                        <input type="text" name="blood_group" value="{{ $user->donorProfile?->blood_group }}" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white font-bold">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase">Story Headline / Title</label>
                    <input type="text" name="title" required placeholder="e.g. My 5th Blood Donation Camp at Bhagwangola" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase">Location / Village</label>
                    <input type="text" name="location" value="{{ $user->donorProfile?->village ?? 'Bhagwangola' }}" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase">Your Experience / Message</label>
                    <textarea name="experience" rows="4" required placeholder="Share how you felt, why you donate blood, or how easy the process was..." class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase">Upload Photo (Optional)</label>
                    <input type="file" name="photo_file" accept="image/*" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-700 dark:text-slate-300 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-600 file:text-white hover:file:bg-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase">OR Image URL</label>
                    <input type="url" name="photo_url" placeholder="https://images.unsplash.com/..." class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-brand-600 to-rose-600 text-white font-extrabold py-3.5 rounded-xl hover:opacity-95 shadow-xl glow-red transition">
                    Publish My Donation Experience 🩸
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

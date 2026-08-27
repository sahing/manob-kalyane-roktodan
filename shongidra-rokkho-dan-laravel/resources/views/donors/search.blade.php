@extends('layouts.app')

@section('title', 'Search Verified Blood Donors — Manab Kalyane Rokto Dan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ inquiryModal: false, selectedDonorName: '', selectedDonorBloodGroup: '' }">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Find Verified Blood Donors</h1>
        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Search active voluntary donors in Bhagwangola and Murshidabad District.</p>
    </div>

    <!-- Filter Card -->
    <form action="{{ route('donors.search') }}" method="GET" class="glass-card p-6 rounded-2xl mb-10">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Blood Group</label>
                <select name="blood_group" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-bold">
                    <option value="">All Blood Groups</option>
                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $g)
                        <option value="{{ $g }}" {{ $group === $g ? 'selected' : '' }}>{{ $g }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Block</label>
                <select name="block" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                    <option value="">All Bhagwangola Blocks</option>
                    <option value="Bhagwangola-I" {{ $block === 'Bhagwangola-I' ? 'selected' : '' }}>Bhagwangola-I</option>
                    <option value="Bhagwangola-II" {{ $block === 'Bhagwangola-II' ? 'selected' : '' }}>Bhagwangola-II</option>
                    <option value="Lalgola" {{ $block === 'Lalgola' ? 'selected' : '' }}>Lalgola</option>
                    <option value="Raninagar" {{ $block === 'Raninagar' ? 'selected' : '' }}>Raninagar</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">District</label>
                <input type="text" name="district" value="{{ $district }}" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
            </div>
            <div>
                <button type="submit" class="w-full bg-brand-600 hover:bg-brand-500 text-white font-bold py-2.5 px-6 rounded-xl transition shadow">
                    Filter Donors
                </button>
            </div>
        </div>
    </form>

    <!-- Donors Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($donors as $donor)
            <div class="glass-card p-6 rounded-2xl flex flex-col justify-between hover:border-rose-500/50 transition">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-brand-800 to-rose-600 text-white font-extrabold text-lg flex items-center justify-center shadow-md">
                                {{ $donor->blood_group }}
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ $donor->user->name }}</h3>
                                <span class="text-xs text-slate-500 dark:text-slate-400 block">{{ $donor->village ? $donor->village . ', ' : '' }}{{ $donor->block }}</span>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $donor->availability_status === 'available' ? 'bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30' : 'bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400' }}">
                            {{ $donor->availability_status }}
                        </span>
                    </div>

                    <div class="space-y-2 text-xs text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-900/60 p-3 rounded-xl border border-slate-200 dark:border-slate-800/80 mb-4">
                        <div class="flex justify-between">
                            <span class="text-slate-500 dark:text-slate-400">Eligibility:</span>
                            @if($donor->is_eligible)
                                <span class="text-emerald-600 dark:text-emerald-400 font-bold">Eligible to donate</span>
                            @else
                                <span class="text-slate-500 dark:text-slate-400">Wait until {{ $donor->next_eligible_date?->format('d M Y') }}</span>
                            @endif
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500 dark:text-slate-400">Last Donation:</span>
                            <span class="font-medium text-slate-800 dark:text-slate-200">{{ $donor->last_donation_date ? $donor->last_donation_date->format('d M Y') : 'Never / Not logged' }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    @if($inquiryGatePassed)
                        <div class="flex items-center space-x-2 w-full justify-between">
                            <a href="tel:{{ $donor->user->phone }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-500 text-white transition flex items-center gap-1.5 shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                Call {{ $donor->user->phone }}
                            </a>
                            <a href="https://wa.me/91{{ $donor->user->phone }}?text=Hello%20{{ urlencode($donor->user->name) }},%20urgent%20blood%20request%20from%20Manab%20Kalyane%20Rokto%20Dan" target="_blank" class="px-3 py-2 rounded-xl text-xs font-bold bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-emerald-600 dark:text-emerald-400 transition">
                                WhatsApp
                            </a>
                        </div>
                    @else
                        <button @click="selectedDonorName = '{{ addslashes($donor->user->name) }}'; selectedDonorBloodGroup = '{{ $donor->blood_group }}'; inquiryModal = true" class="w-full py-2.5 rounded-xl text-xs font-bold bg-gradient-to-r from-brand-600 to-rose-600 text-white shadow-lg glow-red hover:opacity-95 transition flex items-center justify-center space-x-2">
                            <span>🩸 Request Donor</span>
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center p-12 glass-card rounded-2xl text-slate-500 dark:text-slate-400">
                No matching blood donors found for the selected criteria.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $donors->links() }}
    </div>

    <!-- Inquiry Gate Modal -->
    <div x-show="inquiryModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
        <div class="glass-card max-w-md w-full p-6 rounded-2xl border border-slate-300 dark:border-slate-800 relative shadow-2xl">
            <button @click="inquiryModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-900 dark:hover:text-white text-2xl font-bold">&times;</button>

            <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-1">Request Donor Contact</h3>
            <p class="text-xs text-slate-600 dark:text-slate-400 mb-4">Please provide your details to request contact info for <span class="text-rose-600 dark:text-rose-400 font-bold" x-text="selectedDonorName ? selectedDonorName + ' (' + selectedDonorBloodGroup + ')' : 'verified donor'"></span>.</p>

            <form action="{{ route('inquiry.submit') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="donor_name" :value="selectedDonorName">
                <input type="hidden" name="blood_group" :value="selectedDonorBloodGroup">

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

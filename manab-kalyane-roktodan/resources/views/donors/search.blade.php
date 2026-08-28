@extends('layouts.app')

@section('title', 'Find Verified Blood Donors — Manab Kalyane Rokto Dan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ inquiryModal: false, selectedDonorName: '', selectedDonorBloodGroup: '', loadingMore: false, nextPageUrl: '{{ $donors ? $donors->nextPageUrl() : '' }}' }">
    
    <!-- Title Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Find Verified Blood Donors</h1>
            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Search active voluntary donors in Bhagwangola and Murshidabad District.</p>
        </div>

        <!-- Quick Action shortcut -->
        <div>
            <a href="{{ route('donors.search', ['all' => 1]) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-slate-200 dark:bg-slate-800 text-slate-800 dark:text-slate-200 hover:bg-slate-300 dark:hover:bg-slate-700 transition">
                <span>📋 Browse All Active Donors</span>
            </a>
        </div>
    </div>

    <!-- Filter Card (Sleek Clean Original Design) -->
    <form action="{{ route('donors.search') }}" method="GET" class="glass-card p-6 rounded-2xl mb-8 shadow-lg">
        <input type="hidden" name="searched" value="1">
        
        <!-- Quick Blood Group Pills Bar -->
        <div class="mb-5 pb-4 border-b border-slate-200 dark:border-slate-800">
            <span class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wider">Quick Select Blood Group:</span>
            <div class="flex flex-wrap items-center gap-2">
                @foreach(['A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-'] as $bg)
                    <a href="{{ route('donors.search', ['blood_group' => $bg, 'searched' => 1]) }}" 
                       class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold border transition shadow-sm flex items-center gap-1 {{ $group === $bg ? 'bg-rose-600 text-white border-rose-600 shadow-rose-500/30' : 'bg-slate-100 dark:bg-slate-900 text-slate-800 dark:text-slate-200 border-slate-300 dark:border-slate-700 hover:border-rose-500 hover:text-rose-600' }}">
                        <span>🩸</span>
                        <span>{{ $bg }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Blood Group</label>
                <select name="blood_group" class="searchable-select w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white font-bold" data-searchable="true">
                    <option value="">All Blood Groups</option>
                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $g)
                        <option value="{{ $g }}" {{ $group === $g ? 'selected' : '' }}>{{ $g }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Block</label>
                <select name="block" class="searchable-select w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white" data-searchable="true">
                    <option value="">All Bhagwangola Blocks</option>
                    <option value="Bhagwangola-I" {{ $block === 'Bhagwangola-I' ? 'selected' : '' }}>Bhagwangola-I</option>
                    <option value="Bhagwangola-II" {{ $block === 'Bhagwangola-II' ? 'selected' : '' }}>Bhagwangola-II</option>
                    <option value="Lalgola" {{ $block === 'Lalgola' ? 'selected' : '' }}>Lalgola</option>
                    <option value="Raninagar" {{ $block === 'Raninagar' ? 'selected' : '' }}>Raninagar</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Keyword / District</label>
                <input type="text" name="q" value="{{ $keyword }}" placeholder="e.g. Donor name, village or district..." class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
            </div>
            <div>
                <button type="submit" class="w-full bg-brand-600 hover:bg-brand-500 text-white font-bold py-2.5 px-6 rounded-xl transition shadow flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <span>Filter Donors</span>
                </button>
            </div>
        </div>
    </form>

    <!-- INITIAL STATE: WHEN USER HAS NOT SEARCHED YET -->
    @if(!$hasSearched)
        <div class="glass-card p-10 sm:p-14 rounded-2xl text-center max-w-2xl mx-auto shadow-xl border border-slate-200 dark:border-slate-800">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-brand-700 to-rose-500 text-white flex items-center justify-center mx-auto mb-4 shadow-lg glow-red animate-heartbeat">
                <svg class="w-8 h-8 fill-current" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path></svg>
            </div>
            
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white mb-2">
                Select Criteria to Search Verified Donors
            </h2>
            <p class="text-xs text-slate-600 dark:text-slate-400 mb-6 leading-relaxed max-w-lg mx-auto">
                Please select your required <strong>Blood Group</strong> or <strong>Block Location</strong> above and click <span class="text-rose-600 dark:text-rose-400 font-bold">Filter Donors</span> to display matching verified voluntary donors.
            </p>

            <a href="{{ route('donors.search', ['all' => 1]) }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-brand-600 to-rose-600 text-white font-extrabold text-xs shadow-lg glow-red hover:opacity-95 transition">
                <span>🩸 Show All Available Donors</span>
            </a>
        </div>

    @else
        <!-- RESULTS STATE: SHOW UNIFORM DONOR CARDS -->
        <div class="mb-5 flex items-center justify-between">
            <div class="text-xs text-slate-700 dark:text-slate-300 font-bold">
                Showing <span class="text-rose-600 dark:text-rose-400 font-extrabold text-sm">{{ number_format($donors->total()) }}</span> Verified Donor(s)
                @if($group) for Blood Group <span class="px-2 py-0.5 rounded bg-rose-600 text-white font-extrabold text-xs">{{ $group }}</span> @endif
                @if($block) in <span class="text-slate-900 dark:text-white font-extrabold">{{ $block }}</span> @endif
            </div>
        </div>

        @if($donors->count() > 0)
            <!-- Donors Uniform Grid -->
            <div id="donor-cards-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @include('donors._cards', ['donors' => $donors])
            </div>

            <!-- AUTO-SCROLL / INFINITE SCROLL LOADING INDICATOR -->
            <div id="infinite-scroll-trigger" class="py-10 text-center">
                <template x-if="loadingMore">
                    <div class="inline-flex items-center gap-3 px-6 py-2.5 rounded-full bg-slate-900 text-white text-xs font-bold shadow-xl border border-slate-700">
                        <svg class="w-4 h-4 animate-spin text-rose-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span>Loading next batch of donors...</span>
                    </div>
                </template>
            </div>
        @else
            <div class="glass-card p-10 rounded-2xl text-center max-w-lg mx-auto text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-800">
                <div class="text-3xl mb-2">🔍</div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white mb-1">No Matching Donors Found</h3>
                <p class="text-xs mb-5">No donors match your selected criteria right now. Try expanding your search or selecting a different block.</p>
                <a href="{{ route('donors.search', ['all' => 1]) }}" class="inline-block px-5 py-2 rounded-xl bg-brand-600 text-white font-bold text-xs shadow-md hover:bg-brand-500 transition">
                    View All Available Donors
                </a>
            </div>
        @endif
    @endif

    <!-- Inquiry Gate Modal -->
    <div x-show="inquiryModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
        <div class="glass-card max-w-md w-full p-6 sm:p-8 rounded-3xl border border-slate-300 dark:border-slate-800 relative shadow-2xl">
            <button @click="inquiryModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-900 dark:hover:text-white text-2xl font-bold">&times;</button>

            <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-1">Request Donor Contact</h3>
            <p class="text-xs text-slate-600 dark:text-slate-400 mb-4">Please provide your details to unlock contact info for <span class="text-rose-600 dark:text-rose-400 font-bold" x-text="selectedDonorName ? selectedDonorName + ' (' + selectedDonorBloodGroup + ')' : 'verified donor'"></span>.</p>

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

<!-- AUTO INFINITE SCROLL SCRIPT -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const trigger = document.getElementById('infinite-scroll-trigger');
        const grid = document.getElementById('donor-cards-grid');
        if (!trigger || !grid) return;

        let nextPageUrl = '{{ $donors ? $donors->nextPageUrl() : "" }}';
        let isFetching = false;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && nextPageUrl && !isFetching) {
                    loadMoreDonors();
                }
            });
        }, { rootMargin: '200px' });

        observer.observe(trigger);

        function loadMoreDonors() {
            if (!nextPageUrl || isFetching) return;
            isFetching = true;
            
            const alpineData = Alpine.$data(document.querySelector('[x-data]'));
            if (alpineData) alpineData.loadingMore = true;

            fetch(nextPageUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.html) {
                    grid.insertAdjacentHTML('beforeend', data.html);
                }
                nextPageUrl = data.next_page_url;
            })
            .catch(err => console.error('Error auto-loading donors:', err))
            .finally(() => {
                isFetching = false;
                if (alpineData) alpineData.loadingMore = false;
                if (!nextPageUrl) {
                    observer.unobserve(trigger);
                }
            });
        }
    });
</script>
@endsection

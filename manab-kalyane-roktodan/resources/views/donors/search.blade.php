@extends('layouts.app')

@section('title', 'Find Verified Blood Donors — Manab Kalyane Rokto Dan')

@section('content')
<div class="max-w-7xl mx-auto px-2.5 sm:px-6 lg:px-8 py-3 sm:py-6" 
     x-data="{ 
        viewMode: 'grid', 
        filterOpen: {{ ($block || $keyword) ? 'true' : 'false' }}, 
        inquiryModal: false, 
        selectedDonorName: '', 
        selectedDonorBloodGroup: '', 
        loadingMore: false, 
        nextPageUrl: '{{ $donors ? $donors->nextPageUrl() : '' }}' 
     }">
    
    <!-- Compact Top Bar: Header & Controls -->
    <div class="mb-3 flex items-center justify-between gap-2">
        <div class="flex items-center gap-2 min-w-0">
            <h1 class="text-lg sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight truncate">Find Donors</h1>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 shrink-0">
                {{ number_format($donors ? $donors->total() : 0) }} Active
            </span>
        </div>

        <!-- View Mode Switcher & Filter Toggle -->
        <div class="flex items-center gap-1.5 shrink-0">
            <!-- View Mode Switcher -->
            <div class="bg-slate-200 dark:bg-slate-800 p-0.5 rounded-xl flex items-center gap-0.5 border border-slate-300 dark:border-slate-700 text-xs">
                <button type="button" @click="viewMode = 'grid'" 
                        :class="viewMode === 'grid' ? 'bg-white dark:bg-slate-900 text-rose-600 dark:text-rose-400 shadow-sm font-extrabold' : 'text-slate-600 dark:text-slate-400 font-semibold'" 
                        class="px-2 py-1 rounded-lg transition text-xs">
                    <span>📱</span>
                </button>
                <button type="button" @click="viewMode = 'list'" 
                        :class="viewMode === 'list' ? 'bg-white dark:bg-slate-900 text-rose-600 dark:text-rose-400 shadow-sm font-extrabold' : 'text-slate-600 dark:text-slate-400 font-semibold'" 
                        class="px-2 py-1 rounded-lg transition text-xs">
                    <span>📋</span>
                </button>
            </div>

            <!-- Toggle Detailed Filters -->
            <button type="button" @click="filterOpen = !filterOpen" 
                    :class="filterOpen ? 'bg-rose-600 text-white border-rose-600' : 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700'"
                    class="px-2.5 py-1 rounded-xl text-xs font-bold border transition flex items-center gap-1">
                <span>⚙️ Filter</span>
            </button>
        </div>
    </div>

    <!-- Quick Blood Group Horizontal Pills Bar -->
    <div class="mb-3 overflow-x-auto whitespace-nowrap pb-1.5 pt-0.5 no-scrollbar flex items-center gap-1.5 border-b border-slate-200 dark:border-slate-800/80">
        <a href="{{ route('donors.search', ['searched' => 1]) }}" 
           class="px-2.5 py-1 rounded-xl text-xs font-extrabold border transition shrink-0 flex items-center gap-1 {{ empty($group) ? 'bg-rose-600 text-white border-rose-600 shadow-sm' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700 hover:border-rose-500' }}">
            <span>🩸 All</span>
        </a>
        @foreach(['A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-'] as $bg)
            <a href="{{ route('donors.search', array_merge(request()->query(), ['blood_group' => $bg, 'searched' => 1])) }}" 
               class="px-2.5 py-1 rounded-xl text-xs font-extrabold border transition shrink-0 flex items-center gap-1 {{ $group === $bg ? 'bg-rose-600 text-white border-rose-600 shadow-sm' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700 hover:border-rose-500' }}">
                <span>{{ $bg }}</span>
            </a>
        @endforeach
    </div>

    <!-- Collapsible Compact Detailed Filter Form -->
    <form x-show="filterOpen" x-cloak x-transition action="{{ route('donors.search') }}" method="GET" class="glass-card p-3 rounded-2xl mb-3 shadow-md border border-slate-200 dark:border-slate-800">
        <input type="hidden" name="searched" value="1">

        <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-2 items-end">
            <div>
                <label class="block text-[10px] font-extrabold text-slate-500 dark:text-slate-400 mb-0.5 uppercase tracking-wider">Blood Group</label>
                <select name="blood_group" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-2.5 py-1.5 text-xs font-extrabold text-slate-900 dark:text-white">
                    <option value="">All Blood Groups</option>
                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $g)
                        <option value="{{ $g }}" {{ $group === $g ? 'selected' : '' }}>{{ $g }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-extrabold text-slate-500 dark:text-slate-400 mb-0.5 uppercase tracking-wider">Block / Location</label>
                <select name="block" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-2.5 py-1.5 text-xs font-bold text-slate-900 dark:text-white">
                    <option value="">All Bhagwangola Blocks</option>
                    <option value="Bhagwangola-I" {{ $block === 'Bhagwangola-I' ? 'selected' : '' }}>Bhagwangola-I</option>
                    <option value="Bhagwangola-II" {{ $block === 'Bhagwangola-II' ? 'selected' : '' }}>Bhagwangola-II</option>
                    <option value="Lalgola" {{ $block === 'Lalgola' ? 'selected' : '' }}>Lalgola</option>
                    <option value="Raninagar" {{ $block === 'Raninagar' ? 'selected' : '' }}>Raninagar</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-extrabold text-slate-500 dark:text-slate-400 mb-0.5 uppercase tracking-wider">Keyword Search</label>
                <input type="text" name="q" value="{{ $keyword }}" placeholder="Name, village, code..." class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-2.5 py-1.5 text-xs text-slate-900 dark:text-white font-medium focus:outline-none focus:border-rose-500">
            </div>

            <div class="flex items-center gap-1.5">
                <button type="submit" class="flex-1 bg-gradient-to-r from-brand-600 to-rose-600 hover:opacity-95 text-white font-extrabold py-1.5 px-3 rounded-xl transition shadow text-xs flex items-center justify-center gap-1">
                    <svg class="w-3.5 h-3.5 stroke-current" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <span>Apply Filter</span>
                </button>
                @if($group || $block || $keyword)
                    <a href="{{ route('donors.search', ['searched' => 1]) }}" class="px-2.5 py-1.5 rounded-xl bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-bold hover:text-rose-600 transition" title="Clear Filters">
                        ✕
                    </a>
                @endif
            </div>
        </div>
    </form>

    <!-- Active Filter Tags Row (Only when filters are active) -->
    @if($group || $block || $keyword)
        <div class="mb-3 flex items-center gap-1.5 flex-wrap text-xs">
            <span class="text-[10px] text-slate-500 dark:text-slate-400 font-extrabold uppercase tracking-wider">Active:</span>
            @if($group)
                <span class="px-2 py-0.5 rounded-lg bg-rose-600 text-white font-extrabold text-[10px] flex items-center gap-1">
                    🩸 {{ $group }}
                </span>
            @endif
            @if($block)
                <span class="px-2 py-0.5 rounded-lg bg-slate-800 text-slate-200 font-extrabold text-[10px] flex items-center gap-1">
                    📍 {{ $block }}
                </span>
            @endif
            @if($keyword)
                <span class="px-2 py-0.5 rounded-lg bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-[10px]">
                    "{{ $keyword }}"
                </span>
            @endif
            <a href="{{ route('donors.search', ['searched' => 1]) }}" class="text-[10px] text-rose-500 font-bold hover:underline ml-1">
                Reset
            </a>
        </div>
    @endif

    <!-- RESULTS CONTAINER (Dynamic Compact Grid / List) -->
    @if($donors && $donors->count() > 0)
        <div id="donor-cards-grid" 
             :class="{ 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5 sm:gap-3.5': viewMode === 'grid', 'flex flex-col space-y-2': viewMode === 'list' }">
            @include('donors._cards', ['donors' => $donors])
        </div>

        <!-- AUTO-SCROLL / INFINITE SCROLL LOADING INDICATOR -->
        <div id="infinite-scroll-trigger" class="py-6 text-center">
            <template x-if="loadingMore">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-900 text-white text-[11px] font-bold shadow-lg border border-slate-700">
                    <svg class="w-3.5 h-3.5 animate-spin text-rose-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span>Loading donors...</span>
                </div>
            </template>
        </div>
    @else
        <div class="glass-card p-6 rounded-2xl text-center max-w-md mx-auto text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-800 shadow-md">
            <div class="text-2xl mb-1">🔍</div>
            <h3 class="text-xs font-extrabold text-slate-900 dark:text-white mb-1">No Donors Found</h3>
            <p class="text-[11px] mb-3">No verified donors match your filter criteria.</p>
            <a href="{{ route('donors.search', ['searched' => 1]) }}" class="inline-block px-3 py-1.5 rounded-xl bg-rose-600 text-white font-bold text-xs shadow hover:bg-rose-500 transition">
                Reset Filters
            </a>
        </div>
    @endif

    <!-- Inquiry Gate Modal -->
    <div x-show="inquiryModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
        <div class="glass-card max-w-md w-full p-4 sm:p-6 rounded-3xl border border-slate-300 dark:border-slate-800 relative shadow-2xl">
            <button @click="inquiryModal = false" class="absolute top-3 right-3 text-slate-400 hover:text-slate-900 dark:hover:text-white text-xl font-bold">&times;</button>

            <h3 class="text-base font-extrabold text-slate-900 dark:text-white mb-0.5">Request Donor Contact</h3>
            <p class="text-[11px] text-slate-600 dark:text-slate-400 mb-3">Provide your info to unlock contact for <span class="text-rose-600 dark:text-rose-400 font-bold" x-text="selectedDonorName ? selectedDonorName + ' (' + selectedDonorBloodGroup + ')' : 'donor'"></span>.</p>

            <form action="{{ route('inquiry.submit') }}" method="POST" class="space-y-3">
                @csrf
                <input type="hidden" name="donor_name" :value="selectedDonorName">
                <input type="hidden" name="blood_group" :value="selectedDonorBloodGroup">

                <div>
                    <label class="block text-[10px] font-extrabold text-slate-700 dark:text-slate-300 mb-1 uppercase">Your Name</label>
                    <input type="text" name="name" required placeholder="Enter full name" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-1.5 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-700 dark:text-slate-300 mb-1 uppercase">Your Mobile Number</label>
                    <input type="tel" name="phone" required placeholder="Enter 10-digit phone" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-1.5 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-brand-600 to-rose-600 text-white font-extrabold py-2 rounded-xl hover:opacity-95 shadow transition text-xs">
                    Unlock Donor Contact
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

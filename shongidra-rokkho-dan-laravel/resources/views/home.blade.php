@extends('layouts.app')

@section('title', 'Manab Kalyane Rokto Dan — Voluntary Blood Service Bhagwangola')

@section('content')
<!-- CREATIVE NON-CAROUSEL HERO & BLOOD SEARCH HUB -->
<div class="relative min-h-[620px] bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 border-b border-slate-800/80 overflow-hidden py-16 flex items-center" x-data="{ selectedGroup: '', selectedBlock: 'Bhagwangola-I', district: 'Murshidabad' }">
    
    <!-- Dynamic Decorative Glow Gradients & Floating Droplets -->
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 z-10 overflow-hidden">
        <div class="absolute -top-40 -left-40 w-96 h-96 rounded-full bg-rose-600/20 blur-3xl animate-blob"></div>
        <div class="absolute top-1/3 -right-40 w-96 h-96 rounded-full bg-brand-600/20 blur-3xl animate-blob" style="animation-delay: 3s;"></div>
        <div class="absolute -bottom-40 left-1/3 w-96 h-96 rounded-full bg-rose-500/15 blur-3xl animate-blob" style="animation-delay: 5s;"></div>

        <!-- Animated Floating Blood Droplets -->
        <div class="absolute left-[8%] top-[20%] text-rose-500/30 animate-float">
            <svg class="w-10 h-10 fill-current drop-shadow-md" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path></svg>
        </div>
        <div class="absolute right-[12%] top-[25%] text-rose-500/25 animate-drift" style="animation-delay: 1.5s;">
            <svg class="w-8 h-8 fill-current drop-shadow-md" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path></svg>
        </div>
        <div class="absolute right-[22%] bottom-[18%] text-rose-500/30 animate-heartbeat">
            <svg class="w-9 h-9 fill-current drop-shadow-md" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path></svg>
        </div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full z-20">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            
            <!-- Left Side: High-Impact Hero Headline & Live Status -->
            @php
                $heroSec = \App\Models\HomepageSection::where('key', 'hero')->where('is_visible', true)->first();
                $ctaSec = \App\Models\HomepageSection::where('key', 'cta')->where('is_visible', true)->first();
            @endphp
            <div class="lg:col-span-5 space-y-6 text-center lg:text-left">
                <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full text-xs font-black uppercase tracking-wider bg-rose-600/20 border border-rose-500/40 text-rose-300 shadow-lg backdrop-blur-md">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-ping"></span>
                    {{ $heroSec->subtitle ?? 'Bhagwangola Voluntary Blood Network' }}
                </div>

                <h1 class="text-3xl sm:text-4xl md:text-5xl font-heading font-extrabold text-white leading-tight tracking-tight drop-shadow-xl">
                    {{ $heroSec->title ?? 'Saving Lives in Bhagwangola Through Voluntary Blood Donation' }}
                </h1>

                <p class="text-sm sm:text-base text-slate-300 leading-relaxed font-medium">
                    {{ $heroSec->content ?? 'Direct access to verified voluntary blood donors across Bhagwangola-I, Bhagwangola-II, Lalgola & Murshidabad District. 100% free & non-commercial.' }}
                </p>

                <!-- Feature Highlights Badges -->
                <div class="pt-2 flex flex-wrap items-center justify-center lg:justify-start gap-3">
                    <span class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-slate-900/90 text-emerald-400 border border-slate-800 shadow-sm flex items-center gap-1.5">
                        <span class="text-base">⚡</span> 24/7 Helpline
                    </span>
                    <span class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-slate-900/90 text-rose-300 border border-slate-800 shadow-sm flex items-center gap-1.5">
                        <span class="text-base">🛡️</span> Verified Contact
                    </span>
                    <span class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-slate-900/90 text-amber-300 border border-slate-800 shadow-sm flex items-center gap-1.5">
                        <span class="text-base">📍</span> Bhagwangola RH
                    </span>
                </div>

                <!-- Emergency Patient Call CTA -->
                <div class="pt-4 flex flex-wrap items-center justify-center lg:justify-start gap-4">
                    <a href="{{ $heroSec->button_url ?? route('requests.index') }}" class="px-6 py-3.5 rounded-2xl text-xs font-extrabold bg-gradient-to-r from-brand-600 to-rose-600 text-white shadow-xl glow-red hover:opacity-95 transition flex items-center gap-2 transform hover:-translate-y-0.5">
                        <span>{{ $heroSec->button_text ?? 'Emergency Patient Requests 🚨' }}</span>
                    </a>
                    <a href="{{ $heroSec->secondary_button_url ?? 'tel:+919832100000' }}" class="px-5 py-3.5 rounded-2xl text-xs font-bold bg-slate-900/90 text-slate-200 border border-slate-700 hover:bg-slate-800 shadow-md backdrop-blur-md transition flex items-center gap-2">
                        <span>{{ $heroSec->secondary_button_text ?? '📞 Call Helpline' }}</span>
                    </a>
                </div>
            </div>

            <!-- Right Side: CREATIVE INTERACTIVE BLOOD SEARCH HUB (NON-CAROUSEL) -->
            <div class="lg:col-span-7">
                <div class="bg-slate-950/85 backdrop-blur-2xl border border-slate-700/80 p-6 sm:p-8 rounded-3xl shadow-2xl space-y-6 relative overflow-hidden">
                    
                    <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                        <div>
                            <h2 class="text-lg font-extrabold text-white flex items-center gap-2">
                                <span class="text-rose-500 text-xl">🩸</span> Creative Blood Finder Hub
                            </h2>
                            <p class="text-xs text-slate-400">Select blood group & location to locate nearby verified donors instantly</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            Active Directory
                        </span>
                    </div>

                    <form action="{{ route('donors.search') }}" method="GET" class="space-y-6">
                        
                        <!-- Visual Blood Group Selector Pills -->
                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-slate-300 mb-3 flex items-center justify-between">
                                <span>1. Select Required Blood Group</span>
                                <span x-text="selectedGroup ? ('Selected: ' + selectedGroup) : 'Any Blood Group'" class="text-rose-400 font-bold"></span>
                            </label>

                            <input type="hidden" name="blood_group" :value="selectedGroup">

                            <div class="grid grid-cols-4 sm:grid-cols-8 gap-2">
                                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $g)
                                    <button type="button"
                                            @click="selectedGroup = (selectedGroup === '{{ $g }}' ? '' : '{{ $g }}')"
                                            :class="selectedGroup === '{{ $g }}' ? 'bg-gradient-to-tr from-brand-600 to-rose-600 text-white font-black border-rose-400 shadow-lg scale-105 glow-red' : 'bg-slate-900/90 text-slate-300 border-slate-700 hover:border-rose-500/60 hover:text-white'"
                                            class="py-3.5 px-2 rounded-2xl border text-sm font-extrabold transition-all duration-200 flex flex-col items-center justify-center space-y-1">
                                        <span class="text-xs opacity-75">🩸</span>
                                        <span>{{ $g }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Location & District Controls -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-black uppercase tracking-wider text-slate-300 mb-2">2. Block / Station</label>
                                <select name="block" x-model="selectedBlock" class="w-full bg-slate-900 border border-slate-700 rounded-2xl px-4 py-3 text-sm text-white focus:outline-none focus:border-rose-500 font-medium">
                                    <option value="">All Bhagwangola Blocks</option>
                                    <option value="Bhagwangola-I">Bhagwangola-I</option>
                                    <option value="Bhagwangola-II">Bhagwangola-II</option>
                                    <option value="Lalgola">Lalgola</option>
                                    <option value="Raninagar">Raninagar</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-black uppercase tracking-wider text-slate-300 mb-2">3. District</label>
                                <input type="text" name="district" x-model="district" class="w-full bg-slate-900 border border-slate-700 rounded-2xl px-4 py-3 text-sm text-white focus:outline-none focus:border-rose-500 font-medium">
                            </div>
                        </div>

                        <!-- Search Button -->
                        <div class="pt-2">
                            <button type="submit" class="w-full bg-gradient-to-r from-brand-600 via-rose-600 to-rose-700 hover:from-brand-500 hover:to-rose-600 text-white font-extrabold py-4 px-8 rounded-2xl shadow-2xl glow-red hover:opacity-95 transition transform hover:-translate-y-0.5 flex items-center justify-center space-x-3 text-base">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                                <span>Search Verified Donors Now</span>
                            </button>
                        </div>
                    </form>

                    <!-- Live Ticker Bar -->
                    <div class="pt-3 border-t border-slate-800 flex items-center justify-between text-[11px] text-slate-400 font-medium">
                        <span class="flex items-center gap-1.5 text-emerald-400">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                            Verified Donors Active in Bhagwangola
                        </span>
                        <span class="text-slate-400 font-mono">Murshidabad Network</span>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- Stats Strip -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <div class="glass-card p-6 rounded-2xl text-center relative overflow-hidden group hover:border-rose-500/50 transition">
            <div class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white mb-1 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">{{ number_format($stats['total_donors']) }}</div>
            <div class="text-xs uppercase font-bold tracking-wider text-slate-500 dark:text-slate-400">Registered Donors</div>
        </div>
        <div class="glass-card p-6 rounded-2xl text-center relative overflow-hidden group hover:border-rose-500/50 transition">
            <div class="text-3xl md:text-4xl font-extrabold text-rose-600 dark:text-rose-400 mb-1">{{ number_format($stats['pending_requests']) }}</div>
            <div class="text-xs uppercase font-bold tracking-wider text-slate-500 dark:text-slate-400">Pending Requests</div>
        </div>
        <div class="glass-card p-6 rounded-2xl text-center relative overflow-hidden group hover:border-rose-500/50 transition">
            <div class="text-3xl md:text-4xl font-extrabold text-emerald-600 dark:text-emerald-400 mb-1">{{ number_format($stats['fulfilled_requests']) }}</div>
            <div class="text-xs uppercase font-bold tracking-wider text-slate-500 dark:text-slate-400">Lives Saved</div>
        </div>
        <div class="glass-card p-6 rounded-2xl text-center relative overflow-hidden group hover:border-rose-500/50 transition">
            <div class="text-3xl md:text-4xl font-extrabold text-amber-600 dark:text-amber-400 mb-1">{{ number_format($stats['total_donations']) }}</div>
            <div class="text-xs uppercase font-bold tracking-wider text-slate-500 dark:text-slate-400">Total Donations</div>
        </div>
    </div>
</div>

<!-- OPTIONAL: COMMUNITY CAROUSEL SHOWCASE (if Admin added slides in DB) -->
@if(count($slides) > 0)
    <div class="bg-slate-100 dark:bg-gradient-to-b dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 py-16 border-t border-slate-200 dark:border-slate-800/60" x-data="{ activeSlide: 0, total: {{ count($slides) }} }" x-init="if(total > 1) { setInterval(() => { activeSlide = (activeSlide + 1) % total }, 5000) }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <span class="inline-block rounded-full bg-rose-500/10 border border-rose-500/20 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400 mb-2">
                    Voluntary Movement Highlights
                </span>
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Awareness Drives & Camps</h2>
            </div>

            <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-slate-300 dark:border-slate-800 min-h-[400px]">
                @foreach($slides as $index => $slide)
                    <div x-show="activeSlide === {{ $index }}"
                         x-transition:enter="transition ease-out duration-700"
                         x-transition:enter-start="opacity-0 scale-105"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-500"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="absolute inset-0 w-full h-full">
                        <img src="{{ $slide->image_url }}" alt="{{ $slide->title }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                        <div class="absolute bottom-8 left-8 right-8 text-white space-y-2 max-w-xl">
                            <h3 class="text-2xl font-bold">{{ $slide->title }}</h3>
                            <p class="text-xs text-slate-300 font-medium">{{ $slide->subtitle }}</p>
                        </div>
                    </div>
                @endforeach

                @if(count($slides) > 1)
                    <div class="absolute bottom-6 right-8 z-20 flex space-x-2">
                        @foreach($slides as $index => $slide)
                            <button @click="activeSlide = {{ $index }}" :class="activeSlide === {{ $index }} ? 'w-8 bg-rose-500' : 'w-2 bg-slate-600'" class="h-2 rounded-full transition-all duration-300"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif

<!-- SECTION 1: How It Works -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 border-t border-slate-200 dark:border-slate-800/60">
    <div class="text-center max-w-2xl mx-auto mb-12">
        <span class="inline-block rounded-full bg-rose-500/10 border border-rose-500/20 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400 mb-2">
            Simple, Fast, Transparent
        </span>
        <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">How It Works</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass-card p-8 rounded-2xl hover:-translate-y-1 hover:shadow-xl transition duration-300">
            <div class="w-12 h-12 rounded-xl bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 flex items-center justify-center font-bold text-xl mb-4">
                1
            </div>
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Register as a Donor</h3>
            <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                Create a free account, select your blood group and village location to be visible in emergency searches.
            </p>
        </div>

        <div class="glass-card p-8 rounded-2xl hover:-translate-y-1 hover:shadow-xl transition duration-300">
            <div class="w-12 h-12 rounded-xl bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 flex items-center justify-center font-bold text-xl mb-4">
                2
            </div>
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Search by Blood Group</h3>
            <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                Filter verified voluntary donors across Bhagwangola, Lalgola and Murshidabad — call or WhatsApp instantly.
            </p>
        </div>

        <div class="glass-card p-8 rounded-2xl hover:-translate-y-1 hover:shadow-xl transition duration-300">
            <div class="w-12 h-12 rounded-xl bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 flex items-center justify-center font-bold text-xl mb-4">
                3
            </div>
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Post Emergency Request</h3>
            <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                In critical hospital emergencies, submit patient details to broadcast to our entire local donor network.
            </p>
        </div>
    </div>
</div>

<!-- SECTION 2: Why Choose Us -->
<div class="bg-slate-100 dark:bg-gradient-to-b dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 py-16 border-t border-slate-200 dark:border-slate-800/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="inline-block rounded-full bg-rose-500/10 border border-rose-500/20 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400 mb-2">
                Why Choose Us
            </span>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Trusted by Hundreds in Bhagwangola</h2>
            <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">A community-driven voluntary blood network built on speed, safety, and care.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="glass-card p-6 rounded-2xl hover:-translate-y-1 transition duration-300">
                <div class="w-10 h-10 rounded-xl bg-rose-600/20 text-rose-600 dark:text-rose-400 flex items-center justify-center mb-4 font-bold">⚡</div>
                <h3 class="font-bold text-slate-900 dark:text-white mb-1">24/7 Response</h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">Emergency requests reach nearby donors instantly, day or night.</p>
            </div>
            <div class="glass-card p-6 rounded-2xl hover:-translate-y-1 transition duration-300">
                <div class="w-10 h-10 rounded-xl bg-rose-600/20 text-rose-600 dark:text-rose-400 flex items-center justify-center mb-4 font-bold">🛡️</div>
                <h3 class="font-bold text-slate-900 dark:text-white mb-1">Verified Donors</h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">Every donor profile is reviewed for eligibility and contact accuracy.</p>
            </div>
            <div class="glass-card p-6 rounded-2xl hover:-translate-y-1 transition duration-300">
                <div class="w-10 h-10 rounded-xl bg-rose-600/20 text-rose-600 dark:text-rose-400 flex items-center justify-center mb-4 font-bold">❤️</div>
                <h3 class="font-bold text-slate-900 dark:text-white mb-1">100% Voluntary</h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">No paid donors. Pure community spirit and voluntary human service.</p>
            </div>
            <div class="glass-card p-6 rounded-2xl hover:-translate-y-1 transition duration-300">
                <div class="w-10 h-10 rounded-xl bg-rose-600/20 text-rose-600 dark:text-rose-400 flex items-center justify-center mb-4 font-bold">📍</div>
                <h3 class="font-bold text-slate-900 dark:text-white mb-1">Local Network</h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">Donors mapped across Bhagwangola-I, Bhagwangola-II & Lalgola for fastest reach.</p>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 3: Live Emergency Requests Grid -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 border-t border-slate-200 dark:border-slate-800/60">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                <span class="w-3.5 h-3.5 rounded-full bg-rose-500 animate-ping"></span>
                Live Emergency Blood Requests
            </h2>
            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Patients requiring urgent blood in Murshidabad hospitals.</p>
        </div>
        <a href="{{ route('requests.index') }}" class="text-xs font-bold text-rose-600 dark:text-rose-400 hover:underline">View All Requests →</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($pendingRequests as $req)
            <div class="glass-card p-6 rounded-2xl border-rose-900/20 dark:border-rose-900/40 hover:border-rose-600/60 transition shadow-lg flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="w-12 h-12 rounded-2xl bg-rose-600/10 dark:bg-rose-600/20 border border-rose-500/40 text-rose-600 dark:text-rose-400 font-extrabold text-xl flex items-center justify-center shadow-md">
                            {{ $req->blood_group }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-amber-500/20 text-amber-800 dark:text-amber-300 border border-amber-500/30">
                            {{ $req->units_required }} Unit(s) Needed
                        </span>
                    </div>

                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">{{ $req->patient_name }}</h3>
                    <p class="text-xs text-slate-700 dark:text-slate-300 mb-3 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        {{ $req->hospital_name }} ({{ $req->location }})
                    </p>

                    @if($req->notes)
                        <p class="text-xs text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-900/80 p-3 rounded-xl border border-slate-200 dark:border-slate-800/80 mb-4 line-clamp-2">
                            "{{ $req->notes }}"
                        </p>
                    @endif
                </div>

                <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <span class="text-[11px] text-slate-500">Posted {{ $req->created_at->diffForHumans() }}</span>
                    <a href="tel:{{ $req->contact_number }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-500 text-white transition flex items-center gap-1.5 shadow">
                        Call {{ $req->contact_number }}
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center p-8 glass-card rounded-2xl text-slate-500 dark:text-slate-400 text-sm">
                No emergency blood requests currently pending.
            </div>
        @endforelse
    </div>
</div>

<!-- SECTION 4: Inspiring Donor Experiences & Photos -->
@if(count($stories) > 0)
    <div class="bg-slate-100 dark:bg-gradient-to-b dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 py-16 border-t border-slate-200 dark:border-slate-800/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <span class="inline-block rounded-full bg-rose-500/10 border border-rose-500/20 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400 mb-2">
                        Community Inspiration
                    </span>
                    <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Donor Stories & Experiences</h2>
                </div>
                <a href="{{ route('stories.index') }}" class="text-xs font-bold text-rose-600 dark:text-rose-400 hover:underline flex items-center gap-1">
                    <span>Explore All Stories</span> →
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($stories as $st)
                    <div class="glass-card rounded-2xl overflow-hidden shadow-xl flex flex-col justify-between group hover:border-rose-600/60 transition">
                        <div>
                            @if($st->photo_url)
                                <div class="relative h-48 overflow-hidden bg-slate-900">
                                    <img src="{{ $st->photo_url }}" alt="{{ $st->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    @if($st->blood_group)
                                        <span class="absolute top-3 right-3 px-2.5 py-1 rounded-xl text-xs font-black bg-rose-600 text-white shadow-md">
                                            {{ $st->blood_group }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <div class="p-6">
                                <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">
                                    "{{ $st->title }}"
                                </h3>
                                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed line-clamp-3 mb-4">
                                    {{ $st->experience }}
                                </p>
                            </div>
                        </div>

                        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-800 dark:text-slate-200">✍️ {{ $st->donor_name }}</span>
                            <span class="text-slate-500 dark:text-slate-400 text-[11px]">{{ $st->location }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

<!-- SECTION 5: Testimonials -->
<div class="py-16 border-t border-slate-200 dark:border-slate-800/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="inline-block rounded-full bg-rose-500/10 border border-rose-500/20 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400 mb-2">
                Testimonials
            </span>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Voices from Our Community</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="glass-card p-6 rounded-2xl relative">
                <div class="flex text-amber-500 text-sm mb-3">★★★★★</div>
                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
                    "Within 30 minutes of posting an emergency request, a voluntary donor reached Murshidabad Medical College. They saved my father's life."
                </p>
                <div class="border-t border-slate-200 dark:border-slate-800 pt-3">
                    <div class="font-bold text-slate-900 dark:text-white text-sm">Rahim Sheikh</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400">Recipient's Family, Bhagwangola</div>
                </div>
            </div>

            <div class="glass-card p-6 rounded-2xl relative">
                <div class="flex text-amber-500 text-sm mb-3">★★★★★</div>
                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
                    "Donating blood through this platform feels deeply meaningful. The team coordinates everything smoothly and respects donor privacy."
                </p>
                <div class="border-t border-slate-200 dark:border-slate-800 pt-3">
                    <div class="font-bold text-slate-900 dark:text-white text-sm">Sumaiya Khatun</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400">Voluntary Donor (B+), Lalgola</div>
                </div>
            </div>

            <div class="glass-card p-6 rounded-2xl relative">
                <div class="flex text-amber-500 text-sm mb-3">★★★★★</div>
                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
                    "A reliable lifeline for critical hospital patients. The directory accuracy and rapid response make it indispensable in Murshidabad."
                </p>
                <div class="border-t border-slate-200 dark:border-slate-800 pt-3">
                    <div class="font-bold text-slate-900 dark:text-white text-sm">Dr. A. Mondal</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400">Local Physician, Berhampore</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 6: Certified & Supported Partners -->
<div class="py-14 border-t border-slate-200 dark:border-slate-800/60 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-2xl mx-auto mb-8">
        <span class="inline-block rounded-full bg-rose-500/10 border border-rose-500/20 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400 mb-1">
            Certified & Supported By
        </span>
        <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white">Recognized Community Partners</h2>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="glass-card p-4 rounded-xl flex items-center space-x-3">
            <span class="text-2xl">🏛️</span>
            <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Govt. of West Bengal</span>
        </div>
        <div class="glass-card p-4 rounded-xl flex items-center space-x-3">
            <span class="text-2xl">🩸</span>
            <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Blood Bank Society</span>
        </div>
        <div class="glass-card p-4 rounded-xl flex items-center space-x-3">
            <span class="text-2xl">🏥</span>
            <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Murshidabad Health Dept.</span>
        </div>
        <div class="glass-card p-4 rounded-xl flex items-center space-x-3">
            <span class="text-2xl">❤️</span>
            <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Red Cross Volunteers</span>
        </div>
    </div>
</div>

<!-- SECTION 7: Find Us / Centre Location Google Map -->
<div class="bg-slate-100 dark:bg-slate-900/60 py-16 border-t border-slate-200 dark:border-slate-800/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
        <div>
            <span class="inline-block rounded-full bg-rose-500/10 border border-rose-500/20 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400 mb-2">
                Find Us
            </span>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-3">Visit Our Centre in Bhagwangola</h2>
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-6 leading-relaxed">
                Walk in for donor registration assistance, awareness materials, or to coordinate an emergency blood request directly with our committee members.
            </p>

            <ul class="space-y-3 text-xs text-slate-700 dark:text-slate-300">
                <li class="flex items-center gap-3">
                    <span class="text-rose-600 dark:text-rose-500 font-bold text-base">📍</span>
                    <span>Bhagwangola, Murshidabad, West Bengal 742135</span>
                </li>
                <li class="flex items-center gap-3">
                    <span class="text-rose-600 dark:text-rose-500 font-bold text-base">📞</span>
                    <span>Helpline 24/7: <a href="tel:+919832100000" class="text-slate-900 dark:text-white font-bold hover:underline">+91 98321 00000</a></span>
                </li>
                <li class="flex items-center gap-3">
                    <span class="text-rose-600 dark:text-rose-500 font-bold text-base">⏰</span>
                    <span>Office Hours: 9:00 AM – 8:00 PM (Everyday)</span>
                </li>
            </ul>
        </div>

        <div class="rounded-2xl overflow-hidden border border-slate-300 dark:border-slate-800 shadow-2xl">
            <iframe
                title="Bhagwangola Location Map"
                src="https://www.google.com/maps?q=Bhagwangola,Murshidabad,West+Bengal&output=embed"
                class="w-full h-[320px] border-0"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</div>

<!-- SECTION 8: Call to Action (CTA) Banner -->
<div class="relative py-20 border-t border-slate-200 dark:border-slate-800/80 overflow-hidden bg-gradient-to-r from-rose-900 via-slate-900 to-rose-950 dark:from-brand-950 dark:via-slate-900 dark:to-brand-950 text-center">
    <div class="max-w-4xl mx-auto px-4 space-y-4 relative z-10">
        <div class="w-12 h-12 rounded-2xl bg-rose-600 text-white flex items-center justify-center mx-auto mb-2 shadow-lg glow-red animate-heartbeat">
            <svg class="w-7 h-7 fill-current" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path></svg>
        </div>
        <h2 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight">Be the Reason Someone Lives Today</h2>
        <p class="text-sm md:text-base text-slate-200 max-w-xl mx-auto leading-relaxed">
            Healthy adults can donate blood every 90 days. One voluntary donation can save up to three lives.
        </p>
        <div class="pt-4 flex justify-center gap-4">
            @auth
                <a href="{{ route('dashboard') }}" class="px-8 py-3.5 rounded-xl font-bold text-sm bg-gradient-to-r from-brand-600 to-rose-600 text-white shadow-xl glow-red hover:opacity-95 transition">
                    Go to Your Dashboard
                </a>
            @else
                <button @click="authModal = true; authMode = 'signup'" class="px-8 py-3.5 rounded-xl font-bold text-sm bg-gradient-to-r from-brand-600 to-rose-600 text-white shadow-xl glow-red hover:opacity-95 transition">
                    Become a Donor Now
                </button>
            @endauth
        </div>
    </div>
</div>
@endsection

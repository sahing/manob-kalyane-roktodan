@extends('layouts.app')

@section('title', 'Emergency Blood Requests — Manab Kalyane Rokto Dan')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-10" x-data="{ 
    newRequestModal: false, 
    viewMode: 'grid', 
    inquiryModal: false, 
    selectedPatientName: '', 
    selectedBloodGroup: '',
    activeTab: 'pending',
    manageModal: false,
    editReq: { id: '', patient_name: '', blood_group: 'A+', units_required: 1, needed_by_date: '', hospital_name: '', location: '', contact_number: '', status: 'pending', notes: '', fulfillment_notes: '', fulfilled_by_donor: '' }
}">
    
    <!-- Top Header & Compact Mobile Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <div>
            <h1 class="text-lg sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                <span class="w-2.5 h-2.5 sm:w-3.5 sm:h-3.5 rounded-full bg-rose-500 animate-ping shrink-0"></span>
                <span>Emergency Blood Requests</span>
            </h1>
            <p class="text-[11px] sm:text-sm text-slate-600 dark:text-slate-400 mt-0.5">Live requests & verified blood donation success stories in Murshidabad.</p>
        </div>

        <div class="flex items-center justify-between sm:justify-end gap-2 shrink-0">
            <!-- View Mode Switcher Buttons -->
            <div class="flex items-center bg-slate-200 dark:bg-slate-900 p-1 rounded-xl border border-slate-300 dark:border-slate-800 space-x-0.5">
                <button @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-brand-600 text-white font-bold shadow-md' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" class="p-1.5 sm:px-3 sm:py-1.5 rounded-lg text-xs flex items-center gap-1 transition" title="Card View">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span class="hidden sm:inline">Card View</span>
                </button>
                <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-brand-600 text-white font-bold shadow-md' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" class="p-1.5 sm:px-3 sm:py-1.5 rounded-lg text-xs flex items-center gap-1 transition" title="List View">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    <span class="hidden sm:inline">List View</span>
                </button>
            </div>

            <button @click="newRequestModal = true" class="px-3.5 py-2 sm:px-5 sm:py-2.5 rounded-xl font-extrabold text-xs bg-gradient-to-r from-brand-600 to-rose-600 text-white shadow-lg glow-red hover:opacity-95 transition flex items-center gap-1 whitespace-nowrap">
                <span>+ Post Request</span>
            </button>
        </div>
    </div>

    <!-- Main Navigation Tabs: Sleek 2-Column Segment Switcher -->
    <div class="grid grid-cols-2 gap-1 bg-slate-200/80 dark:bg-slate-900/90 p-1 rounded-2xl border border-slate-300 dark:border-slate-800/80 mb-5">
        <button @click="activeTab = 'pending'" :class="activeTab === 'pending' ? 'bg-rose-600 text-white font-extrabold shadow-md' : 'text-slate-600 dark:text-slate-400 font-semibold hover:text-slate-900 dark:hover:text-white'" class="py-2 px-2 rounded-xl text-xs sm:text-sm flex items-center justify-center gap-1.5 transition">
            <span>🚨 Active Emergencies</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-white/20 font-mono">{{ $pending->total() }}</span>
        </button>
        <button @click="activeTab = 'fulfilled'" :class="activeTab === 'fulfilled' ? 'bg-emerald-600 text-white font-extrabold shadow-md' : 'text-slate-600 dark:text-slate-400 font-semibold hover:text-slate-900 dark:hover:text-white'" class="py-2 px-2 rounded-xl text-xs sm:text-sm flex items-center justify-center gap-1.5 transition">
            <span>✅ Fulfilled Stories</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-white/20 font-mono">{{ $fulfilled->count() }}</span>
        </button>
    </div>

    <!-- Active Requests: CARD VIEW MODE -->
    <div x-show="activeTab === 'pending' && viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-6 mb-8 sm:mb-12">
        @forelse($pending as $req)
            <div id="request-{{ $req->id }}" class="glass-card p-4 sm:p-6 rounded-2xl border-rose-900/20 dark:border-rose-900/30 hover:border-rose-600/60 transition shadow-lg flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="w-12 h-12 rounded-2xl bg-rose-600/10 dark:bg-rose-600/20 border border-rose-500/40 text-rose-600 dark:text-rose-400 font-extrabold text-xl flex items-center justify-center shadow-md">
                            {{ $req->blood_group }}
                        </span>
                        <div class="flex items-center gap-1.5 flex-wrap justify-end">
                            <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-amber-500/20 text-amber-800 dark:text-amber-300 border border-amber-500/30">
                                {{ $req->units_required }} Unit(s) Needed
                            </span>
                            @if(Auth::check() && Auth::user()->canManageBloodRequest($req))
                                <button type="button" 
                                    @click="editReq = {
                                        id: '{{ $req->id }}',
                                        patient_name: '{{ addslashes($req->patient_name) }}',
                                        blood_group: '{{ $req->blood_group }}',
                                        units_required: '{{ $req->units_required }}',
                                        needed_by_date: '{{ $req->needed_by_date ? $req->needed_by_date->format('Y-m-d') : '' }}',
                                        hospital_name: '{{ addslashes($req->hospital_name) }}',
                                        location: '{{ addslashes($req->location) }}',
                                        contact_number: '{{ $req->contact_number }}',
                                        status: 'fulfilled',
                                        notes: '{{ addslashes($req->notes ?? '') }}',
                                        fulfillment_notes: '{{ addslashes($req->fulfillment_notes ?? '') }}',
                                        fulfilled_by_donor: '{{ addslashes($req->fulfilled_by_donor ?? '') }}'
                                    }; manageModal = true"
                                    class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white transition text-xs font-extrabold flex items-center gap-1 shadow-sm" title="Mark as Blood Arranged / Fulfilled">
                                    ✅ Mark Fulfilled
                                </button>

                                <button type="button" 
                                    @click="editReq = {
                                        id: '{{ $req->id }}',
                                        patient_name: '{{ addslashes($req->patient_name) }}',
                                        blood_group: '{{ $req->blood_group }}',
                                        units_required: '{{ $req->units_required }}',
                                        needed_by_date: '{{ $req->needed_by_date ? $req->needed_by_date->format('Y-m-d') : '' }}',
                                        hospital_name: '{{ addslashes($req->hospital_name) }}',
                                        location: '{{ addslashes($req->location) }}',
                                        contact_number: '{{ $req->contact_number }}',
                                        status: '{{ $req->status }}',
                                        notes: '{{ addslashes($req->notes ?? '') }}',
                                        fulfillment_notes: '{{ addslashes($req->fulfillment_notes ?? '') }}',
                                        fulfilled_by_donor: '{{ addslashes($req->fulfilled_by_donor ?? '') }}'
                                    }; manageModal = true"
                                    class="p-1.5 rounded-lg bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-rose-600 hover:text-white transition text-xs font-bold" title="Edit Request Details">
                                    ⚙️ Edit
                                </button>
                            @endif
                        </div>
                    </div>

                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">{{ $req->patient_name }}</h3>
                    <p class="text-xs text-slate-700 dark:text-slate-300 mb-1">Hospital: <strong class="text-slate-900 dark:text-white">{{ $req->hospital_name }}</strong></p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">Location: {{ $req->location }}</p>

                    <!-- REQUIREMENT DATE BADGE -->
                    <div class="mb-4 p-2.5 rounded-xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-between">
                        <span class="text-xs font-extrabold text-rose-600 dark:text-rose-400 flex items-center gap-1.5 uppercase">
                            <span>📅</span> Requirement Date:
                        </span>
                        <span class="text-xs font-extrabold text-slate-900 dark:text-white font-mono bg-white dark:bg-slate-900 px-2.5 py-1 rounded-lg shadow-sm border border-slate-200 dark:border-slate-800">
                            {{ $req->needed_by_date ? $req->needed_by_date->format('d M Y') : $req->created_at->format('d M Y') }}
                        </span>
                    </div>

                    @if($req->notes)
                        <p class="text-xs text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-900/80 p-3 rounded-xl border border-slate-200 dark:border-slate-800 mb-4">
                            "{{ $req->notes }}"
                        </p>
                    @endif
                </div>
                <div class="pt-4 border-t border-slate-200 dark:border-slate-800 space-y-2.5">
                    @php
                        $cardShareText = "🚨 *EMERGENCY BLOOD NEEDED* 🩸\n\n"
                            . "📌 *Patient Name:* " . $req->patient_name . "\n"
                            . "🩸 *Blood Group:* *" . $req->blood_group . "* (URGENT)\n"
                            . "🔢 *Units Required:* " . $req->units_required . " Bag(s)\n"
                            . "📅 *Date Needed:* " . ($req->needed_by_date ? $req->needed_by_date->format('d M Y') : 'ASAP') . "\n"
                            . "🏥 *Hospital:* " . $req->hospital_name . "\n"
                            . "📍 *Location:* " . $req->location . "\n"
                            . "📞 *Contact Number:* " . $req->contact_number . "\n";
                            if (!empty($req->notes)) {
                                $cardShareText .= "💬 *Notes:* " . $req->notes . "\n";
                            }
                        $cardShareText .= "\n🙏 *Please share to help find verified donors quickly!*\n\n"
                            . "🌐 *Click link to view details & respond:* \n" . route('requests.index') . '#request-' . $req->id;
                    @endphp

                    <!-- Primary Action Buttons -->
                    <div class="flex items-center justify-between gap-2">
                        <button type="button" onclick="window.openPortalChat(this)"
                            data-request-id="{{ $req->id }}"
                            data-patient-name="{{ $req->patient_name }}"
                            data-blood-group="{{ $req->blood_group }}"
                            data-units="{{ $req->units_required }}"
                            data-hospital="{{ $req->hospital_name }}"
                            data-location="{{ $req->location }}"
                            data-phone="{{ $req->contact_number }}"
                            data-notes="{{ $req->notes ?? '' }}"
                            class="flex-1 px-3 py-2.5 rounded-xl text-xs font-extrabold bg-gradient-to-r from-rose-600 to-brand-600 text-white hover:opacity-95 transition flex items-center justify-center gap-1.5 shadow-md">
                            👁️ View Details & Portal Chat
                        </button>
                        
                        @if($inquiryGatePassed)
                            <a href="tel:{{ $req->contact_number }}" class="px-3 py-2.5 rounded-xl text-xs font-extrabold bg-emerald-600 hover:bg-emerald-500 text-white transition flex items-center gap-1 shadow-md">
                                📞 Call
                            </a>
                        @else
                            <button type="button" @click="selectedPatientName = '{{ addslashes($req->patient_name) }}'; selectedBloodGroup = '{{ $req->blood_group }}'; inquiryModal = true" class="px-3 py-2.5 rounded-xl text-xs font-extrabold bg-emerald-600 hover:bg-emerald-500 text-white transition flex items-center gap-1 shadow-md">
                                📞 Call
                            </button>
                        @endif
                    </div>

                    <!-- Prominent Broadcast Button -->
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($cardShareText) }}" target="_blank" 
                       class="w-full bg-emerald-600/10 hover:bg-emerald-600/20 text-emerald-700 dark:text-emerald-300 border border-emerald-500/40 rounded-xl px-3 py-2.5 text-xs font-extrabold flex items-center justify-center gap-2 transition hover:shadow-md group">
                        <span class="text-sm">📢</span>
                        <span>Broadcast Request to WhatsApp Groups</span>
                        <svg class="w-4 h-4 ml-auto text-emerald-600 dark:text-emerald-400 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center p-12 glass-card rounded-2xl text-slate-500 dark:text-slate-400 text-sm">
                No active pending blood requests at this moment.
            </div>
        @endforelse
    </div>

    <!-- Active Requests: LIST VIEW MODE -->
    <div x-show="activeTab === 'pending' && viewMode === 'list'" x-cloak class="glass-card rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden mb-12 shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700 dark:text-slate-300 divide-y divide-slate-200 dark:border-slate-800">
                <thead class="bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 uppercase text-[11px] font-bold">
                    <tr>
                        <th class="p-4">Blood Group</th>
                        <th class="p-4">Patient Name</th>
                        <th class="p-4">Units Needed</th>
                        <th class="p-4">Date of Requirement</th>
                        <th class="p-4">Hospital & Location</th>
                        <th class="p-4">Posted Date</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800/80">
                    @forelse($pending as $req)
                        <tr class="hover:bg-slate-100/60 dark:hover:bg-slate-900/60 transition">
                            <td class="p-4">
                                <span class="w-10 h-10 rounded-xl bg-rose-600/10 dark:bg-rose-600/20 border border-rose-500/40 text-rose-600 dark:text-rose-400 font-extrabold text-base flex items-center justify-center">
                                    {{ $req->blood_group }}
                                </span>
                            </td>
                            <td class="p-4 font-bold text-slate-900 dark:text-white text-sm">
                                {{ $req->patient_name }}
                                @if($req->notes)
                                    <span class="block text-xs font-normal text-slate-500 dark:text-slate-400 line-clamp-1">"{{ $req->notes }}"</span>
                                @endif
                            </td>
                            <td class="p-4 font-bold text-amber-600 dark:text-amber-300">
                                {{ $req->units_required }} Unit(s)
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-extrabold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/30 font-mono inline-block">
                                    📅 {{ $req->needed_by_date ? $req->needed_by_date->format('d M Y') : $req->created_at->format('d M Y') }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="font-semibold text-slate-900 dark:text-white block">{{ $req->hospital_name }}</span>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400">{{ $req->location }}</span>
                            </td>
                            <td class="p-4 text-slate-500 dark:text-slate-400 font-medium">
                                {{ $req->created_at->diffForHumans() }}
                            </td>
                            <td class="p-4 text-right">
                                <div class="inline-flex items-center space-x-1.5">
                                    <button type="button" onclick="window.openPortalChat(this)"
                                        data-request-id="{{ $req->id }}"
                                        data-patient-name="{{ $req->patient_name }}"
                                        data-blood-group="{{ $req->blood_group }}"
                                        data-units="{{ $req->units_required }}"
                                        data-hospital="{{ $req->hospital_name }}"
                                        data-location="{{ $req->location }}"
                                        data-phone="{{ $req->contact_number }}"
                                        data-notes="{{ $req->notes ?? '' }}"
                                        class="px-2.5 py-1.5 rounded-lg text-xs font-bold bg-gradient-to-r from-rose-600 to-brand-600 text-white hover:opacity-95 transition flex items-center gap-1 shadow">
                                        👁️ View Details
                                    </button>
                                    
                                    @if(Auth::check() && Auth::user()->canManageBloodRequest($req))
                                        <button type="button" 
                                            @click="editReq = {
                                                id: '{{ $req->id }}',
                                                patient_name: '{{ addslashes($req->patient_name) }}',
                                                blood_group: '{{ $req->blood_group }}',
                                                units_required: '{{ $req->units_required }}',
                                                needed_by_date: '{{ $req->needed_by_date ? $req->needed_by_date->format('Y-m-d') : '' }}',
                                                hospital_name: '{{ addslashes($req->hospital_name) }}',
                                                location: '{{ addslashes($req->location) }}',
                                                contact_number: '{{ $req->contact_number }}',
                                                status: '{{ $req->status }}',
                                                notes: '{{ addslashes($req->notes ?? '') }}',
                                                fulfillment_notes: '{{ addslashes($req->fulfillment_notes ?? '') }}',
                                                fulfilled_by_donor: '{{ addslashes($req->fulfilled_by_donor ?? '') }}'
                                            }; manageModal = true"
                                            class="px-2.5 py-1.5 rounded-lg text-xs font-bold bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-rose-600 hover:text-white transition">
                                            ⚙️ Status
                                        </button>
                                    @endif

                                    @php
                                        $listShareText = "🚨 *EMERGENCY BLOOD NEEDED* 🩸\n\n"
                                            . "📌 *Patient Name:* " . $req->patient_name . "\n"
                                            . "🩸 *Blood Group:* *" . $req->blood_group . "* (URGENT)\n"
                                            . "🔢 *Units Required:* " . $req->units_required . " Bag(s)\n"
                                            . "📅 *Date Needed:* " . ($req->needed_by_date ? $req->needed_by_date->format('d M Y') : 'ASAP') . "\n"
                                            . "🏥 *Hospital:* " . $req->hospital_name . "\n"
                                            . "📍 *Location:* " . $req->location . "\n"
                                            . "📞 *Contact Number:* " . $req->contact_number . "\n";
                                            if (!empty($req->notes)) {
                                                $listShareText .= "💬 *Notes:* " . $req->notes . "\n";
                                            }
                                        $listShareText .= "\n🙏 *Please share to help find verified donors quickly!*\n\n"
                                            . "🌐 *Click link to view details & respond:* \n" . route('requests.index') . '#request-' . $req->id;
                                    @endphp
                                    <a href="https://api.whatsapp.com/send?text={{ urlencode($listShareText) }}" target="_blank" class="px-2.5 py-1.5 rounded-lg text-xs font-extrabold bg-emerald-600/15 hover:bg-emerald-600/25 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 transition flex items-center gap-1" title="Broadcast Request to WhatsApp Groups">
                                        <span>📢</span> Share
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-500">
                                No active pending blood requests.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- FULFILLED REQUESTS & SUCCESS STORIES TAB VIEW -->
    <div x-show="activeTab === 'fulfilled'" x-cloak class="space-y-6 mb-12">
        <div class="p-4 sm:p-6 bg-emerald-950/20 border border-emerald-500/30 rounded-3xl backdrop-blur-md shadow-lg flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-2xl font-black shadow-inner">
                    ✨
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-extrabold text-slate-900 dark:text-white">
                        Fulfilled Emergency Blood Requests
                    </h3>
                    <p class="text-xs text-slate-600 dark:text-slate-400">Successfully arranged blood requests & community donor experiences in Bhagwangola & Murshidabad.</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-6">
            @forelse($fulfilled as $fReq)
                <div id="request-{{ $fReq->id }}" class="glass-card p-4 sm:p-6 rounded-2xl border-emerald-500/30 hover:border-emerald-500/60 transition shadow-lg flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="w-12 h-12 rounded-2xl bg-emerald-500/20 border border-emerald-500/40 text-emerald-500 dark:text-emerald-400 font-extrabold text-xl flex items-center justify-center shadow-md">
                                {{ $fReq->blood_group }}
                            </span>
                            <span class="px-3 py-1 rounded-full text-[11px] font-extrabold bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-500/40 flex items-center gap-1">
                                ✅ Blood Arranged
                            </span>
                        </div>

                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">{{ $fReq->patient_name }}</h3>
                        <p class="text-xs text-slate-700 dark:text-slate-300 mb-1">Hospital: <strong class="text-slate-900 dark:text-white">{{ $fReq->hospital_name }}</strong></p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">Location: {{ $fReq->location }}</p>

                        <!-- FULFILLMENT EXPERIENCE STORY -->
                        @if($fReq->fulfillment_notes)
                            <div class="p-3.5 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-xs text-emerald-800 dark:text-emerald-200 space-y-1 mb-4 shadow-sm">
                                <div class="font-bold flex items-center gap-1 uppercase tracking-wider text-[10px] text-emerald-600 dark:text-emerald-400">
                                    💬 Donor & Requester Experience:
                                </div>
                                <p class="italic leading-relaxed font-medium">"{{ $fReq->fulfillment_notes }}"</p>
                                @if($fReq->fulfilled_by_donor)
                                    <div class="text-[11px] font-extrabold text-emerald-700 dark:text-emerald-300 mt-1">
                                        — Arranged / Donated by: {{ $fReq->fulfilled_by_donor }}
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="p-3 bg-slate-100 dark:bg-slate-900/60 rounded-xl text-[11px] text-slate-500 dark:text-slate-400 italic mb-4">
                                Blood successfully donated! Experience story pending.
                            </div>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-slate-200 dark:border-slate-800 space-y-2">
                        @php
                            $fulfilledCardShareMsg = "✨ *BLOOD DONATION SUCCESS STORY* 🩸\n\n"
                                . "🙏 Heartfelt thanks to our hero donor(s) & community volunteers!\n\n"
                                . "📌 *Patient Name:* " . $fReq->patient_name . "\n"
                                . "🩸 *Blood Group:* *" . $fReq->blood_group . "*\n"
                                . "🏥 *Hospital:* " . $fReq->hospital_name . " (" . $fReq->location . ")\n";
                            if (!empty($fReq->fulfilled_by_donor)) {
                                $fulfilledCardShareMsg .= "👤 *Donated / Arranged by:* " . $fReq->fulfilled_by_donor . "\n";
                            }
                            if (!empty($fReq->fulfillment_notes)) {
                                $fulfilledCardShareMsg .= "\n💬 *Requester Feedback & Experience:*\n\"" . $fReq->fulfillment_notes . "\"\n";
                            }
                            $fulfilledCardShareMsg .= "\n❤️ *Together we save lives in Bhagwangola & Murshidabad!*\n\n"
                                . "🌐 *Read full story on Manab Kalyane Rokto Dan:* \n" . route('requests.index') . '#request-' . $fReq->id;
                        @endphp

                        <div class="flex items-center justify-between gap-2">
                            <a href="https://api.whatsapp.com/send?text={{ urlencode($fulfilledCardShareMsg) }}" target="_blank" 
                               class="flex-1 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl px-3 py-2 text-xs font-extrabold flex items-center justify-center gap-1.5 transition shadow">
                                <span>📢</span>
                                <span>Share Story</span>
                            </a>

                            @if(Auth::check() && Auth::user()->canManageBloodRequest($fReq))
                                <button type="button" 
                                    @click="editReq = {
                                        id: '{{ $fReq->id }}',
                                        patient_name: '{{ addslashes($fReq->patient_name) }}',
                                        blood_group: '{{ $fReq->blood_group }}',
                                        units_required: '{{ $fReq->units_required }}',
                                        needed_by_date: '{{ $fReq->needed_by_date ? $fReq->needed_by_date->format('Y-m-d') : '' }}',
                                        hospital_name: '{{ addslashes($fReq->hospital_name) }}',
                                        location: '{{ addslashes($fReq->location) }}',
                                        contact_number: '{{ $fReq->contact_number }}',
                                        status: '{{ $fReq->status }}',
                                        notes: '{{ addslashes($fReq->notes ?? '') }}',
                                        fulfillment_notes: '{{ addslashes($fReq->fulfillment_notes ?? '') }}',
                                        fulfilled_by_donor: '{{ addslashes($fReq->fulfilled_by_donor ?? '') }}'
                                    }; manageModal = true"
                                    class="px-3 py-2 rounded-xl text-xs font-bold bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-rose-600 hover:text-white transition shadow-sm" title="Submit or Edit Feedback">
                                    ✍️ Feedback
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center p-12 glass-card rounded-2xl text-slate-500 dark:text-slate-400 text-sm">
                    No fulfilled emergency requests recorded yet.
                </div>
            @endforelse
        </div>
    </div>

    <!-- New Request Modal -->
    <div x-show="newRequestModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-950/80 backdrop-blur-md">
        <div class="glass-card max-w-lg w-full max-h-[90vh] overflow-y-auto p-5 sm:p-7 rounded-3xl border border-slate-300 dark:border-slate-800 relative shadow-2xl custom-scrollbar">
            
            <!-- Sticky Header -->
            <div class="flex items-start justify-between pb-3 mb-4 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="text-rose-500 animate-pulse">🚨</span> Post Emergency Request
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Notify verified donors instantly in Murshidabad</p>
                </div>
                <button @click="newRequestModal = false" class="p-1.5 rounded-full bg-slate-200 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 transition font-bold text-lg leading-none">
                    &times;
                </button>
            </div>

            <form action="{{ route('requests.store') }}" method="POST" class="space-y-4">
                @csrf
                @guest
                    <div class="p-3.5 bg-rose-500/10 border border-rose-500/30 rounded-2xl text-xs text-rose-700 dark:text-rose-300 space-y-1 shadow-sm">
                        <div class="font-extrabold flex items-center gap-1.5 uppercase tracking-wider text-rose-800 dark:text-rose-200">
                            <span>💡</span> Guest Request (No Password Required)
                        </div>
                        <p class="leading-relaxed text-[11px]">No prior account needed! An account will be created automatically using your mobile number for future password-less access.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Your Full Name (Requester)</label>
                        <input type="text" name="requester_name" required placeholder="Enter your full name" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-medium">
                    </div>
                @endguest

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Patient Name</label>
                        <input type="text" name="patient_name" required placeholder="Patient full name" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Blood Group Required</label>
                        <select name="blood_group" required class="searchable-select w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-extrabold" data-searchable="true">
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $g)
                                <option value="{{ $g }}">{{ $g }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Units Required</label>
                        <input type="number" name="units_required" value="1" min="1" max="10" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Needed Date</label>
                        <input type="date" name="needed_by_date" value="{{ date('Y-m-d') }}" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-bold">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Contact Mobile Phone Number</label>
                    <input type="tel" name="contact_number" value="{{ Auth::check() ? Auth::user()->phone : '' }}" required placeholder="10-digit mobile number" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-mono font-bold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Hospital Name</label>
                    <input type="text" name="hospital_name" placeholder="e.g. Murshidabad Medical College" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Hospital Location / City</label>
                    <input type="text" name="location" placeholder="e.g. Berhampore, Murshidabad" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Additional Notes (Optional)</label>
                    <textarea name="notes" rows="2" placeholder="Emergency surgery / Thalassemia transfusion details..." class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-medium"></textarea>
                </div>

                <!-- Sticky / Prominent Action Button -->
                <div class="pt-2 sticky bottom-0 bg-slate-900/90 backdrop-blur-md pb-1 border-t border-slate-800/80">
                    <button type="submit" class="w-full bg-gradient-to-r from-brand-600 to-rose-600 text-white font-extrabold py-3.5 rounded-xl hover:opacity-95 shadow-xl glow-red transition flex items-center justify-center gap-2 text-sm uppercase tracking-wider">
                        <span>📢 Publish Emergency Request</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Inquiry Gate Modal for Unlocking Contact Phone -->
    <div x-show="inquiryModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
        <div class="glass-card max-w-md w-full p-6 sm:p-8 rounded-3xl border border-slate-300 dark:border-slate-800 relative shadow-2xl">
            <button @click="inquiryModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-900 dark:hover:text-white text-2xl font-bold">&times;</button>

            <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-1">Enter Phone to Connect</h3>
            <p class="text-xs text-slate-600 dark:text-slate-400 mb-4">Please enter your contact details to connect via Call or WhatsApp with <span class="text-rose-600 dark:text-rose-400 font-bold" x-text="selectedPatientName ? selectedPatientName + ' (' + selectedBloodGroup + ')' : 'emergency blood request'"></span>.</p>

            <form action="{{ route('inquiry.submit') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="donor_name" :value="selectedPatientName">
                <input type="hidden" name="blood_group" :value="selectedBloodGroup">

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase">Your Full Name</label>
                    <input type="text" name="name" required placeholder="Enter your full name" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase">Your Phone Number</label>
                    <input type="tel" name="phone" required placeholder="Enter 10-digit mobile number" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-brand-600 to-rose-600 text-white font-bold py-3 rounded-xl hover:opacity-95 shadow-md transition">
                    Unlock & Connect Directly
                </button>
            </form>
        </div>
    </div>

    <!-- Social Media & WhatsApp Sharing Modal (Triggered on Post Creation) -->
    @if(session('new_request_share'))
        @php
            $share = session('new_request_share');
            $shareMsg = "🚨 *EMERGENCY BLOOD NEEDED* 🩸\n\n"
                . "📌 *Patient Name:* " . $share['patient_name'] . "\n"
                . "🩸 *Blood Group:* *" . $share['blood_group'] . "* (URGENT)\n"
                . "🔢 *Units Required:* " . $share['units_required'] . " Bag(s)\n"
                . "📅 *Date Needed:* " . $share['needed_by_date'] . "\n"
                . "🏥 *Hospital:* " . $share['hospital_name'] . "\n"
                . "📍 *Location:* " . $share['location'] . "\n"
                . "📞 *Contact Number:* " . $share['contact_number'] . "\n";
                if (!empty($share['notes'])) {
                    $shareMsg .= "💬 *Notes:* " . $share['notes'] . "\n";
                }
            $shareMsg .= "\n🙏 *Please share to help find verified donors quickly!*\n\n"
                . "🌐 *Click link to view details & respond:* \n" . $share['url'];

            $waUrl = 'https://api.whatsapp.com/send?text=' . urlencode($shareMsg);
            $tgUrl = 'https://t.me/share/url?url=' . urlencode($share['url']) . '&text=' . urlencode($shareMsg);
            $fbUrl = 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($share['url']);
            $twUrl = 'https://twitter.com/intent/tweet?text=' . urlencode($shareMsg);
        @endphp

        <div x-data="{ shareModal: true, copied: false }" x-show="shareModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-950/85 backdrop-blur-md">
            <div class="glass-card max-w-lg w-full max-h-[90vh] overflow-y-auto p-5 sm:p-7 rounded-3xl border border-slate-300 dark:border-slate-800 relative shadow-2xl space-y-5 custom-scrollbar">
                
                <div class="flex items-start justify-between pb-3 border-b border-slate-200 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 text-emerald-500 flex items-center justify-center text-2xl font-black shadow-inner">
                            ✅
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                                Request Published!
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Share immediately to reach active donors</p>
                        </div>
                    </div>
                    <button @click="shareModal = false" class="p-1.5 rounded-full bg-slate-200 dark:bg-slate-800 text-slate-500 hover:text-rose-500 transition font-bold text-lg leading-none">&times;</button>
                </div>

                <!-- WhatsApp Direct Share Banner -->
                <a href="{{ $waUrl }}" target="_blank" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold p-4 rounded-2xl shadow-xl flex items-center justify-center gap-3 transition transform hover:-translate-y-0.5 glow-green group">
                    <span class="text-2xl">💬</span>
                    <div class="text-left">
                        <div class="text-sm uppercase tracking-wider">Share directly on WhatsApp</div>
                        <div class="text-[11px] font-normal text-emerald-100">Send pre-formatted emergency post to contacts & groups</div>
                    </div>
                    <svg class="w-5 h-5 ml-auto group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>

                <!-- Other Social Media Quick Links -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2.5">
                        Share via Other Platforms
                    </label>
                    <div class="grid grid-cols-3 gap-2.5">
                        <a href="{{ $fbUrl }}" target="_blank" class="p-3 bg-blue-600/10 hover:bg-blue-600/20 text-blue-600 dark:text-blue-400 border border-blue-600/30 rounded-xl text-xs font-extrabold flex items-center justify-center gap-2 transition">
                            <span>📘</span> Facebook
                        </a>
                        <a href="{{ $tgUrl }}" target="_blank" class="p-3 bg-sky-500/10 hover:bg-sky-500/20 text-sky-600 dark:text-sky-400 border border-sky-500/30 rounded-xl text-xs font-extrabold flex items-center justify-center gap-2 transition">
                            <span>✈️</span> Telegram
                        </a>
                        <a href="{{ $twUrl }}" target="_blank" class="p-3 bg-slate-800 hover:bg-slate-700 text-white border border-slate-700 rounded-xl text-xs font-extrabold flex items-center justify-center gap-2 transition">
                            <span>🐦</span> X / Twitter
                        </a>
                    </div>
                </div>

                <!-- Formatted WhatsApp Message Preview -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                            Formatted Message Preview
                        </label>
                        <button @click="navigator.clipboard.writeText(`{{ addslashes($shareMsg) }}`); copied = true; setTimeout(() => copied = false, 2500)" class="text-xs font-bold text-rose-500 hover:underline flex items-center gap-1">
                            <span x-text="copied ? '✓ Copied!' : '📋 Copy Text'"></span>
                        </button>
                    </div>
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-3.5 text-xs text-slate-300 font-mono whitespace-pre-wrap leading-relaxed max-h-44 overflow-y-auto select-all shadow-inner">
{{ $shareMsg }}
                    </div>
                </div>

                <div class="pt-2 flex items-center justify-between gap-3 border-t border-slate-800">
                    <button @click="shareModal = false" class="w-full bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold py-3 rounded-xl transition text-xs">
                        Done / Close
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- AUTOMATIC FULFILLED EXPERIENCE SOCIAL SHARE MODAL -->
    @if(session('fulfilled_share'))
        @php
            $fShare = session('fulfilled_share');
            $fShareMsg = "✨ *BLOOD DONATION SUCCESS STORY* 🩸\n\n"
                . "🙏 Heartfelt thanks to our hero donor(s) & community volunteers!\n\n"
                . "📌 *Patient Name:* " . $fShare['patient_name'] . "\n"
                . "🩸 *Blood Group:* *" . $fShare['blood_group'] . "*\n"
                . "🏥 *Hospital:* " . $fShare['hospital_name'] . " (" . $fShare['location'] . ")\n";
                if (!empty($fShare['fulfilled_by_donor'])) {
                    $fShareMsg .= "👤 *Donated / Arranged by:* " . $fShare['fulfilled_by_donor'] . "\n";
                }
                if (!empty($fShare['fulfillment_notes'])) {
                    $fShareMsg .= "\n💬 *Requester Feedback & Experience:*\n\"" . $fShare['fulfillment_notes'] . "\"\n";
                }
            $fShareMsg .= "\n❤️ *Together we save lives in Bhagwangola & Murshidabad!*\n\n"
                . "🌐 *Read full story on Manab Kalyane Rokto Dan:* \n" . $fShare['url'];

            $fWaUrl = 'https://api.whatsapp.com/send?text=' . urlencode($fShareMsg);
            $fTgUrl = 'https://t.me/share/url?url=' . urlencode($fShare['url']) . '&text=' . urlencode($fShareMsg);
            $fFbUrl = 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($fShare['url']);
            $fTwUrl = 'https://twitter.com/intent/tweet?text=' . urlencode($fShareMsg);
        @endphp

        <div x-data="{ fShareModal: true, fCopied: false }" x-show="fShareModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-950/85 backdrop-blur-md">
            <div class="glass-card max-w-lg w-full max-h-[90vh] overflow-y-auto p-5 sm:p-7 rounded-3xl border border-emerald-500/40 relative shadow-2xl space-y-5 custom-scrollbar">
                
                <div class="flex items-start justify-between pb-3 border-b border-slate-200 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-2xl font-black shadow-inner">
                            🎉
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                                Success Story & Feedback Saved!
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Share this blood donation success story to inspire the community</p>
                        </div>
                    </div>
                    <button @click="fShareModal = false" class="p-1.5 rounded-full bg-slate-200 dark:bg-slate-800 text-slate-500 hover:text-emerald-500 transition font-bold text-lg leading-none">&times;</button>
                </div>

                <!-- WhatsApp Direct Share Banner -->
                <a href="{{ $fWaUrl }}" target="_blank" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold p-4 rounded-2xl shadow-xl flex items-center justify-center gap-3 transition transform hover:-translate-y-0.5 glow-green group">
                    <span class="text-2xl">💬</span>
                    <div class="text-left">
                        <div class="text-sm uppercase tracking-wider">Share Success Story on WhatsApp</div>
                        <div class="text-[11px] font-normal text-emerald-100">Broadcast gratitude & donor experience to groups</div>
                    </div>
                    <svg class="w-5 h-5 ml-auto group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>

                <!-- Other Social Media Quick Links -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2.5">
                        Share via Other Social Platforms
                    </label>
                    <div class="grid grid-cols-3 gap-2.5">
                        <a href="{{ $fFbUrl }}" target="_blank" class="p-3 bg-blue-600/10 hover:bg-blue-600/20 text-blue-600 dark:text-blue-400 border border-blue-600/30 rounded-xl text-xs font-extrabold flex items-center justify-center gap-2 transition">
                            <span>📘</span> Facebook
                        </a>
                        <a href="{{ $fTgUrl }}" target="_blank" class="p-3 bg-sky-500/10 hover:bg-sky-500/20 text-sky-600 dark:text-sky-400 border border-sky-500/30 rounded-xl text-xs font-extrabold flex items-center justify-center gap-2 transition">
                            <span>✈️</span> Telegram
                        </a>
                        <a href="{{ $fTwUrl }}" target="_blank" class="p-3 bg-slate-800 hover:bg-slate-700 text-white border border-slate-700 rounded-xl text-xs font-extrabold flex items-center justify-center gap-2 transition">
                            <span>🐦</span> X / Twitter
                        </a>
                    </div>
                </div>

                <!-- Formatted Success Story Message Preview -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                            Formatted Message Preview
                        </label>
                        <button @click="navigator.clipboard.writeText(`{{ addslashes($fShareMsg) }}`); fCopied = true; setTimeout(() => fCopied = false, 2500)" class="text-xs font-bold text-emerald-500 hover:underline flex items-center gap-1">
                            <span x-text="fCopied ? '✓ Copied!' : '📋 Copy Text'"></span>
                        </button>
                    </div>
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-3.5 text-xs text-slate-300 font-mono whitespace-pre-wrap leading-relaxed max-h-44 overflow-y-auto select-all shadow-inner">
{{ $fShareMsg }}
                    </div>
                </div>

                <div class="pt-2 flex items-center justify-between gap-3 border-t border-slate-800">
                    <button @click="fShareModal = false" class="w-full bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold py-3 rounded-xl transition text-xs">
                        Done / Close
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Manage Request & Status Modal -->
    <div x-show="manageModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-950/85 backdrop-blur-md">
        <div class="glass-card max-w-lg w-full max-h-[90vh] overflow-y-auto p-5 sm:p-7 rounded-3xl border border-slate-300 dark:border-slate-800 relative shadow-2xl space-y-4 custom-scrollbar">
            
            <div class="flex items-start justify-between pb-3 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <h3 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                        <span>⚙️</span> Manage Request & Status
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Update request status, patient details, and donor experience story</p>
                </div>
                <button @click="manageModal = false" class="p-1.5 rounded-full bg-slate-200 dark:bg-slate-800 text-slate-500 hover:text-rose-500 transition font-bold text-lg leading-none">&times;</button>
            </div>

            <form :action="'{{ url('/requests') }}/' + editReq.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Request Status Selector Tiles -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wider">
                        Request Status
                    </label>
                    <div class="grid grid-cols-3 gap-2 sm:gap-2.5 mb-3">
                        <button type="button" @click="editReq.status = 'pending'" 
                            :class="editReq.status === 'pending' ? 'ring-2 ring-rose-500 bg-rose-500/20 text-rose-700 dark:text-rose-300 font-extrabold border-rose-500/50 shadow-md' : 'bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 font-semibold border-slate-300 dark:border-slate-800 hover:bg-slate-200 dark:hover:bg-slate-800'" 
                            class="p-2.5 sm:p-3 rounded-2xl text-[11px] sm:text-xs text-center border transition flex flex-col items-center justify-center gap-1 cursor-pointer">
                            <span class="text-base sm:text-lg">🚨</span>
                            <span class="leading-tight">Active Emergency</span>
                        </button>

                        <button type="button" @click="editReq.status = 'fulfilled'" 
                            :class="editReq.status === 'fulfilled' ? 'ring-2 ring-emerald-500 bg-emerald-500/20 text-emerald-800 dark:text-emerald-300 font-extrabold border-emerald-500/50 shadow-md' : 'bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 font-semibold border-slate-300 dark:border-slate-800 hover:bg-slate-200 dark:hover:bg-slate-800'" 
                            class="p-2.5 sm:p-3 rounded-2xl text-[11px] sm:text-xs text-center border transition flex flex-col items-center justify-center gap-1 cursor-pointer">
                            <span class="text-base sm:text-lg">✅</span>
                            <span class="leading-tight">Blood Arranged (Fulfilled)</span>
                        </button>

                        <button type="button" @click="editReq.status = 'cancelled'" 
                            :class="editReq.status === 'cancelled' ? 'ring-2 ring-slate-400 bg-slate-500/20 text-slate-800 dark:text-slate-200 font-extrabold border-slate-400/50 shadow-md' : 'bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 font-semibold border-slate-300 dark:border-slate-800 hover:bg-slate-200 dark:hover:bg-slate-800'" 
                            class="p-2.5 sm:p-3 rounded-2xl text-[11px] sm:text-xs text-center border transition flex flex-col items-center justify-center gap-1 cursor-pointer">
                            <span class="text-base sm:text-lg">❌</span>
                            <span class="leading-tight">Cancelled / Closed</span>
                        </button>
                    </div>

                    <input type="hidden" name="status" :value="editReq.status">
                </div>

                <!-- Fulfillment / Experience Sharing Section (Visible when status is fulfilled or editing) -->
                <div x-show="editReq.status === 'fulfilled'" class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl space-y-3">
                    <div class="text-xs font-extrabold text-emerald-800 dark:text-emerald-300 uppercase tracking-wider flex items-center gap-1.5">
                        <span>✨</span> Share Donor Experience / Thank You Note
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Donor Name / Arranged By (Optional)</label>
                        <input type="text" name="fulfilled_by_donor" x-model="editReq.fulfilled_by_donor" placeholder="e.g. Arif Hossain / RK Volunteers" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Donor Experience / Note</label>
                        <textarea name="fulfillment_notes" x-model="editReq.fulfillment_notes" rows="3" placeholder="Share how blood was arranged or express gratitude to the donor..." class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500"></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase tracking-wider">Patient Name</label>
                        <input type="text" name="patient_name" x-model="editReq.patient_name" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase tracking-wider">Blood Group</label>
                        <select name="blood_group" x-model="editReq.blood_group" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-bold">
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                <option value="{{ $bg }}">{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase tracking-wider">Units Required</label>
                        <input type="number" name="units_required" x-model="editReq.units_required" min="1" max="10" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase tracking-wider">Date Needed</label>
                        <input type="date" name="needed_by_date" x-model="editReq.needed_by_date" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-mono">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase tracking-wider">Hospital Name</label>
                    <input type="text" name="hospital_name" x-model="editReq.hospital_name" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase tracking-wider">Location / Block</label>
                        <input type="text" name="location" x-model="editReq.location" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase tracking-wider">Contact Number</label>
                        <input type="text" name="contact_number" x-model="editReq.contact_number" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-mono">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase tracking-wider">Emergency Notes</label>
                    <textarea name="notes" x-model="editReq.notes" rows="2" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-rose-500"></textarea>
                </div>

                <div class="pt-3 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between gap-3">
                    <button type="button" @click="manageModal = false" class="px-4 py-2.5 rounded-xl text-xs font-bold bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-300 transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-extrabold bg-rose-600 hover:bg-rose-500 text-white shadow-lg transition">
                        Save Changes & Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

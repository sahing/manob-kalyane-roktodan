@extends('layouts.app')

@section('title', 'Emergency Blood Requests — Manab Kalyane Rokto Dan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ newRequestModal: false, viewMode: 'grid' }">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                <span class="w-3.5 h-3.5 rounded-full bg-rose-500 animate-ping"></span>
                Emergency Blood Requests
            </h1>
            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Live requests from patients in Bhagwangola & Murshidabad hospitals.</p>
        </div>

        <div class="flex items-center space-x-3">
            <!-- View Mode Switcher Buttons -->
            <div class="flex items-center bg-slate-200 dark:bg-slate-900 p-1.5 rounded-xl border border-slate-300 dark:border-slate-800 space-x-1">
                <button @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-brand-600 text-white font-bold shadow-md' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" class="px-3 py-1.5 rounded-lg text-xs flex items-center gap-1.5 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span>Card View</span>
                </button>
                <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-brand-600 text-white font-bold shadow-md' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" class="px-3 py-1.5 rounded-lg text-xs flex items-center gap-1.5 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    <span>List View</span>
                </button>
            </div>

            @auth
                <button @click="newRequestModal = true" class="px-5 py-2.5 rounded-xl font-bold text-xs bg-gradient-to-r from-brand-600 to-rose-600 text-white shadow-lg glow-red hover:opacity-95 transition">
                    + Post Emergency Request
                </button>
            @else
                <button @click="authModal = true; authMode = 'login'" class="px-5 py-2.5 rounded-xl font-bold text-xs bg-slate-800 text-white border border-slate-700 hover:bg-slate-700 transition">
                    Login to Post Request
                </button>
            @endauth
        </div>
    </div>

    <!-- Active Requests: CARD VIEW MODE -->
    <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        @forelse($pending as $req)
            <div class="glass-card p-6 rounded-2xl border-rose-900/20 dark:border-rose-900/30 hover:border-rose-600/60 transition shadow-lg flex flex-col justify-between">
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
                </div>                <div class="pt-4 border-t border-slate-200 dark:border-slate-800 space-y-3">
                    <div class="flex items-center justify-between gap-1.5 flex-wrap">
                        <button type="button" onclick="openPortalChat({{ json_encode([
                            'requestId' => $req->id,
                            'patientName' => $req->patient_name,
                            'bloodGroup' => $req->blood_group,
                            'units' => $req->units_required,
                            'hospital' => $req->hospital_name,
                            'location' => $req->location,
                            'phone' => $req->contact_number,
                            'notes' => $req->notes ?? ''
                        ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) }})" class="px-3 py-2 rounded-xl text-xs font-extrabold bg-gradient-to-r from-rose-600 to-brand-600 text-white hover:opacity-95 transition flex items-center gap-1 shadow-md">
                            👁️ View Details & Portal Chat
                        </button>
                        <a href="tel:{{ $req->contact_number }}" class="px-2.5 py-2 rounded-xl text-xs font-extrabold bg-emerald-600 hover:bg-emerald-500 text-white transition flex items-center gap-1 shadow-md">
                            📞 Call
                        </a>
                        <a href="https://wa.me/91{{ preg_replace('/[^0-9]/', '', $req->contact_number) }}?text=Hello,%20responding%20from%20Manab%20Kalyane%20Rokto%20Dan%20regarding%20your%20emergency%20blood%20request%20for%20{{ urlencode($req->patient_name) }}%20({{ urlencode($req->blood_group) }})" target="_blank" class="px-2.5 py-2 rounded-xl text-xs font-extrabold bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 hover:bg-emerald-500/30 transition flex items-center gap-1">
                            💬 WA
                        </a>
                        <a href="https://wa.me/?text={{ urlencode('*EMERGENCY BLOOD REQUIRED* %0A*Patient:* ' . $req->patient_name . ' %0A*Blood Group:* ' . $req->blood_group . ' (' . $req->units_required . ' units) %0A*Hospital:* ' . $req->hospital_name . ', ' . $req->location . ' %0A*Contact:* ' . $req->contact_number) }}" target="_blank" class="px-2 py-2 rounded-xl text-xs font-extrabold bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-700 transition" title="Broadcast Request to Groups">
                            📢
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center p-12 glass-card rounded-2xl text-slate-500 dark:text-slate-400 text-sm">
                No active pending blood requests at this moment.
            </div>
        @endforelse
    </div>

    <!-- Active Requests: LIST VIEW MODE -->
    <div x-show="viewMode === 'list'" x-cloak class="glass-card rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden mb-12 shadow-xl">
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
                                    <button type="button" onclick="openPortalChat({{ json_encode([
                                        'requestId' => $req->id,
                                        'patientName' => $req->patient_name,
                                        'bloodGroup' => $req->blood_group,
                                        'units' => $req->units_required,
                                        'hospital' => $req->hospital_name,
                                        'location' => $req->location,
                                        'phone' => $req->contact_number,
                                        'notes' => $req->notes ?? ''
                                    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) }})" class="px-2.5 py-1.5 rounded-lg text-xs font-bold bg-gradient-to-r from-rose-600 to-brand-600 text-white hover:opacity-95 transition flex items-center gap-1 shadow">
                                        👁️ View Details & Chat
                                    </button>            </button>
                                    <a href="tel:{{ $req->contact_number }}" class="px-2.5 py-1.5 rounded-lg text-xs font-bold bg-emerald-600 hover:bg-emerald-500 text-white transition flex items-center gap-1">
                                        📞 Call
                                    </a>
                                    <a href="https://wa.me/91{{ preg_replace('/[^0-9]/', '', $req->contact_number) }}?text=Hello,%20responding%20from%20Manab%20Kalyane%20Rokto%20Dan%20regarding%20your%20emergency%20blood%20request%20for%20{{ urlencode($req->patient_name) }}%20({{ urlencode($req->blood_group) }})" target="_blank" class="px-2.5 py-1.5 rounded-lg text-xs font-bold bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 hover:bg-emerald-500/30 transition flex items-center gap-1">
                                        💬 WA
                                    </a>
                                    <a href="https://wa.me/?text={{ urlencode('*EMERGENCY BLOOD REQUIRED* %0A*Patient:* ' . $req->patient_name . ' %0A*Blood Group:* ' . $req->blood_group . ' (' . $req->units_required . ' units) %0A*Hospital:* ' . $req->hospital_name . ', ' . $req->location . ' %0A*Contact:* ' . $req->contact_number) }}" target="_blank" class="px-2.5 py-1.5 rounded-lg text-xs font-bold bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-300 transition">
                                        📢 Share
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-500">
                                No active pending blood requests.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- New Request Modal -->
    <div x-show="newRequestModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
        <div class="glass-card max-w-lg w-full p-6 rounded-2xl border border-slate-300 dark:border-slate-800 relative">
            <button @click="newRequestModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-900 dark:hover:text-white">&times;</button>

            <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-1">Post Emergency Blood Request</h3>
            <p class="text-xs text-slate-600 dark:text-slate-400 mb-6">Fill in patient details to notify verified local donors.</p>

            <form action="{{ route('requests.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Patient Name</label>
                        <input type="text" name="patient_name" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Blood Group</label>
                        <select name="blood_group" required class="searchable-select w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500" data-searchable="true">
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $g)
                                <option value="{{ $g }}">{{ $g }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Units Required</label>
                        <input type="number" name="units_required" value="1" min="1" max="10" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Requirement Date (Date Needed)</label>
                        <input type="date" name="needed_by_date" value="{{ date('Y-m-d') }}" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-bold">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Contact Phone Number</label>
                    <input type="tel" name="contact_number" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Hospital Name</label>
                    <input type="text" name="hospital_name" placeholder="e.g. Murshidabad Medical College" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Hospital Location / City</label>
                    <input type="text" name="location" placeholder="e.g. Berhampore, Murshidabad" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Additional Notes (Optional)</label>
                    <textarea name="notes" rows="2" placeholder="Emergency surgery / Thalassemia transfusion details..." class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500"></textarea>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-brand-600 to-rose-600 text-white font-bold py-3 rounded-xl hover:opacity-95 shadow-md transition">
                    Publish Emergency Request
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@foreach($donors as $donor)
    <div class="glass-card p-5 rounded-2xl flex flex-col justify-between h-full min-h-[270px] hover:border-rose-500/50 transition duration-300 shadow-md hover:shadow-xl group relative">
        <div>
            <!-- Card Header: Avatar, Name, Code & Blood Group Badge -->
            <div class="flex items-start justify-between gap-2 mb-3">
                <div class="flex items-center space-x-3 min-w-0">
                    <!-- Profile Avatar Image -->
                    <a href="{{ route('donors.show', $donor->id) }}" class="relative shrink-0 group-hover:scale-105 transition duration-300">
                        <img src="{{ $donor->user->avatar_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($donor->user->name) . '&background=e11d48&color=fff&size=100&bold=true' }}" 
                             alt="{{ $donor->user->name }}" 
                             class="w-12 h-12 rounded-xl object-cover border-2 border-rose-500/40 shadow bg-slate-900">
                        <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-gradient-to-tr from-brand-700 to-rose-600 text-white font-extrabold text-[9px] flex items-center justify-center border border-white dark:border-slate-900 shadow">
                            {{ $donor->blood_group }}
                        </span>
                    </a>

                    <!-- Name & Donor Code -->
                    <div class="min-w-0">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('donors.show', $donor->id) }}" class="text-base font-extrabold text-slate-900 dark:text-white truncate hover:text-rose-600 dark:hover:text-rose-400 transition" title="{{ $donor->user->name }}">
                                {{ $donor->user->name }}
                            </a>
                            <span class="text-emerald-500 text-xs font-bold shrink-0" title="Verified Voluntary Donor">✓</span>
                        </div>
                        
                        <div class="flex items-center gap-1.5 text-[11px] font-semibold text-slate-500 dark:text-slate-400">
                            <span class="font-mono text-[10px] bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 px-1.5 py-0.5 rounded font-bold">
                                {{ $donor->donor_code ?: 'MKRD-' . str_pad($donor->id, 5, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Availability & Privacy Badge -->
                <div class="flex flex-col items-end gap-1">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase shrink-0 {{ $donor->availability_status === 'available' ? 'bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30' : 'bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400' }}">
                        {{ $donor->availability_status }}
                    </span>
                    @if(!$donor->allow_direct_contact)
                        <span class="text-[9px] font-bold text-amber-600 dark:text-amber-400 flex items-center gap-0.5" title="Direct contact hidden by donor">
                            🔒 Protected
                        </span>
                    @endif
                </div>
            </div>

            <!-- Location & Stats Container -->
            <div class="space-y-1.5 text-xs text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-900/60 p-3 rounded-xl border border-slate-200 dark:border-slate-800/80 mb-3">
                <div class="flex justify-between items-center text-[11px]">
                    <span class="text-slate-500 dark:text-slate-400">Location:</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200 truncate max-w-[140px]" title="{{ $donor->village ? $donor->village . ', ' : '' }}{{ $donor->block }}">
                        📍 {{ $donor->village ? $donor->village . ', ' : '' }}{{ $donor->block }}
                    </span>
                </div>
                <div class="flex justify-between items-center text-[11px]">
                    <span class="text-slate-500 dark:text-slate-400">Eligibility:</span>
                    @if($donor->is_eligible)
                        <span class="text-emerald-600 dark:text-emerald-400 font-extrabold flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Eligible
                        </span>
                    @else
                        <span class="text-slate-500 dark:text-slate-400 font-medium">Wait until {{ $donor->next_eligible_date?->format('d M Y') }}</span>
                    @endif
                </div>
                <div class="flex justify-between items-center text-[11px]">
                    <span class="text-slate-500 dark:text-slate-400">Honor Rank:</span>
                    <span class="font-extrabold text-amber-500 text-[11px] truncate max-w-[140px]">{{ $donor->user->loyalty_rank }}</span>
                </div>
            </div>
        </div>

        <!-- Action Buttons with Privacy Enforcement -->
        <div class="pt-2.5 border-t border-slate-200 dark:border-slate-800 mt-auto flex items-center gap-2">
            <a href="{{ route('donors.show', $donor->id) }}" class="px-2.5 py-2 rounded-xl text-xs font-bold bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-700 transition" title="View Full Profile">
                👤 Profile
            </a>

            @if(!$donor->allow_direct_contact)
                <!-- Donor opted out of direct contact -->
                <button data-donor-name="{{ $donor->user->name }}" data-blood-group="{{ $donor->blood_group }}" @click="selectedDonorName = '{{ addslashes($donor->user->name) }}'; selectedDonorBloodGroup = '{{ $donor->blood_group }}'; inquiryModal = true" class="inquire-btn flex-1 py-2 rounded-xl text-xs font-extrabold bg-slate-800 hover:bg-slate-700 text-amber-300 border border-amber-500/30 transition flex items-center justify-center space-x-1 shadow">
                    <span>🛡️ Request via Society</span>
                </button>
            @elseif($inquiryGatePassed)
                <a href="tel:{{ $donor->user->phone }}" data-donor-name="{{ $donor->user->name }}" data-blood-group="{{ $donor->blood_group }}" class="flex-1 py-2 rounded-xl text-xs font-extrabold bg-emerald-600 hover:bg-emerald-500 text-white transition flex items-center justify-center gap-1 shadow">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    <span>Call {{ $donor->user->phone }}</span>
                </a>
            @else
                <button data-donor-name="{{ $donor->user->name }}" data-blood-group="{{ $donor->blood_group }}" @click="selectedDonorName = '{{ addslashes($donor->user->name) }}'; selectedDonorBloodGroup = '{{ $donor->blood_group }}'; inquiryModal = true" class="inquire-btn flex-1 py-2 rounded-xl text-xs font-extrabold bg-gradient-to-r from-brand-600 to-rose-600 text-white shadow-md glow-red hover:opacity-95 transition flex items-center justify-center space-x-1">
                    <span>🩸 Request Contact</span>
                </button>
            @endif
        </div>
    </div>
@endforeach

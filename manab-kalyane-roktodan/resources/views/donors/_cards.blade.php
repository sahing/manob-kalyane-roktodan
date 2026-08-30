@foreach($donors as $donor)
    <!-- Donor Card (Dynamic Compact Grid & List Layout) -->
    <div class="glass-card p-3 sm:p-4 rounded-2xl flex flex-col justify-between hover:border-rose-500/50 transition duration-200 shadow-sm hover:shadow-md group relative border border-slate-200 dark:border-slate-800"
         :class="{ 'flex-col': viewMode === 'grid', 'sm:flex-row sm:items-center': viewMode === 'list' }">
        
        <div class="flex-1 min-w-0" :class="{ 'mb-2.5': viewMode === 'grid', 'mb-2 sm:mb-0 sm:mr-4': viewMode === 'list' }">
            <!-- Header: Avatar, Name, Code & Blood Group Badge -->
            <div class="flex items-center justify-between gap-2 mb-2">
                <div class="flex items-center space-x-2.5 min-w-0">
                    <!-- Profile Avatar Image with Blood Group Overlay -->
                    <a href="{{ route('donors.show', $donor->id) }}" class="relative shrink-0 group-hover:scale-105 transition duration-200">
                        <img src="{{ $donor->user->avatar_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($donor->user->name) . '&background=e11d48&color=fff&size=80&bold=true' }}" 
                             alt="{{ $donor->user->name }}" 
                             class="w-10 h-10 rounded-xl object-cover border border-rose-500/40 shadow bg-slate-900">
                        <span class="absolute -bottom-1 -right-1 px-1 py-0.2 rounded bg-gradient-to-tr from-brand-700 to-rose-600 text-white font-extrabold text-[9px] border border-white dark:border-slate-900 shadow">
                            {{ $donor->blood_group }}
                        </span>
                    </a>

                    <!-- Name & Donor Code -->
                    <div class="min-w-0">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('donors.show', $donor->id) }}" class="text-xs sm:text-sm font-extrabold text-slate-900 dark:text-white truncate hover:text-rose-600 dark:hover:text-rose-400 transition" title="{{ $donor->user->name }}">
                                {{ $donor->user->name }}
                            </a>
                            <span class="text-emerald-500 text-[11px] font-bold shrink-0" title="Verified Voluntary Donor">✓</span>
                        </div>
                        
                        <div class="flex items-center gap-1.5 text-[10px] font-semibold text-slate-500 dark:text-slate-400">
                            <span class="font-mono text-[9px] bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 px-1 py-0.5 rounded font-bold">
                                {{ $donor->donor_code ?: 'MKRD-' . str_pad($donor->id, 5, '0', STR_PAD_LEFT) }}
                            </span>
                            @if($donor->user->loyalty_rank)
                                <span class="text-amber-500 font-extrabold text-[9px]">⭐ {{ $donor->user->loyalty_rank }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Availability Badge -->
                <div class="flex flex-col items-end gap-0.5 shrink-0">
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase {{ $donor->availability_status === 'available' ? 'bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30' : 'bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400' }}">
                        {{ $donor->availability_status }}
                    </span>
                    @if(!$donor->allow_direct_contact)
                        <span class="text-[9px] font-bold text-amber-600 dark:text-amber-400 flex items-center gap-0.5" title="Direct contact hidden by donor">
                            🔒 Protected
                        </span>
                    @endif
                </div>
            </div>

            <!-- Compact Info Bar (Location & Eligibility) -->
            <div class="flex items-center justify-between text-[11px] text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-900/60 px-2.5 py-1 rounded-xl border border-slate-200 dark:border-slate-800/80 gap-2">
                <span class="truncate font-semibold text-[10px] sm:text-[11px]" title="{{ $donor->village ? $donor->village . ', ' : '' }}{{ $donor->block }}">
                    📍 {{ $donor->village ? $donor->village . ', ' : '' }}{{ $donor->block }}
                </span>
                @if($donor->is_eligible)
                    <span class="text-emerald-600 dark:text-emerald-400 font-extrabold text-[9px] sm:text-[10px] shrink-0 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Eligible
                    </span>
                @else
                    <span class="text-slate-500 dark:text-slate-400 text-[9px] shrink-0 font-medium">Wait {{ $donor->next_eligible_date?->format('d M') }}</span>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-1.5 shrink-0" :class="{ 'pt-2 border-t border-slate-200 dark:border-slate-800': viewMode === 'grid', 'pt-0': viewMode === 'list' }">
            <a href="{{ route('donors.show', $donor->id) }}" class="px-2.5 py-1.5 rounded-xl text-xs font-bold bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-700 transition" title="View Full Profile">
                Profile
            </a>

            @if(!$donor->allow_direct_contact)
                <button data-donor-name="{{ $donor->user->name }}" data-blood-group="{{ $donor->blood_group }}" @click="selectedDonorName = '{{ addslashes($donor->user->name) }}'; selectedDonorBloodGroup = '{{ $donor->blood_group }}'; inquiryModal = true" class="inquire-btn py-1.5 px-3 rounded-xl text-xs font-extrabold bg-slate-800 hover:bg-slate-700 text-amber-300 border border-amber-500/30 transition flex items-center justify-center space-x-1 shadow" :class="{ 'w-full': viewMode === 'grid' }">
                    <span>🛡️ Request Contact</span>
                </button>
            @elseif($inquiryGatePassed)
                <a href="tel:{{ $donor->user->phone }}" data-donor-name="{{ $donor->user->name }}" data-blood-group="{{ $donor->blood_group }}" class="py-1.5 px-3 rounded-xl text-xs font-extrabold bg-emerald-600 hover:bg-emerald-500 text-white transition flex items-center justify-center gap-1 shadow" :class="{ 'w-full': viewMode === 'grid' }">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    <span>Call {{ $donor->user->phone }}</span>
                </a>
            @else
                <button data-donor-name="{{ $donor->user->name }}" data-blood-group="{{ $donor->blood_group }}" @click="selectedDonorName = '{{ addslashes($donor->user->name) }}'; selectedDonorBloodGroup = '{{ $donor->blood_group }}'; inquiryModal = true" class="inquire-btn py-1.5 px-3 rounded-xl text-xs font-extrabold bg-gradient-to-r from-brand-600 to-rose-600 text-white shadow-md hover:opacity-95 transition flex items-center justify-center space-x-1" :class="{ 'w-full': viewMode === 'grid' }">
                    <span>🩸 Contact</span>
                </button>
            @endif
        </div>
    </div>
@endforeach

@extends('layouts.app')

@section('title', 'Edit Profile — Manab Kalyane Rokto Dan')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-10">
    <div class="glass-card p-8 rounded-2xl border border-slate-300 dark:border-slate-800 shadow-xl">
        <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white mb-2">Edit Donor Profile & Privacy</h1>
        <p class="text-xs text-slate-600 dark:text-slate-400 mb-6">Keep your contact details, availability, and privacy preferences up-to-date.</p>

        <form action="{{ route('dashboard.profile.update') }}" method="POST" class="space-y-4" x-data="{ avatarUrl: '{{ old('avatar_url', $user->avatar_url) }}' }">
            @csrf
            
            <!-- PROFILE AVATAR / IMAGE URL SECTION -->
            <div class="p-4 rounded-2xl bg-slate-100 dark:bg-slate-900/90 border border-slate-300 dark:border-slate-700/80 mb-4">
                <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase">
                        📸 Profile Image / Avatar Photo URL
                    </label>
                    <a href="https://imgbb.com/upload" target="_blank" rel="noopener noreferrer" 
                       class="inline-flex items-center gap-1.5 text-[11px] font-extrabold text-rose-600 dark:text-rose-400 hover:text-rose-500 bg-rose-500/10 hover:bg-rose-500/20 px-2.5 py-1 rounded-lg border border-rose-500/20 transition group relative"
                       title="Upload your photo to ImgBB to get a direct image link">
                        <span>📤 Upload Photo to ImgBB</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>

                        <!-- Tooltip Hover Popup -->
                        <span class="absolute right-0 bottom-full mb-2 hidden group-hover:block w-60 p-2.5 rounded-xl bg-slate-900 text-slate-100 text-[10px] font-medium shadow-2xl border border-slate-700 z-30 leading-snug pointer-events-none">
                            💡 Opens ImgBB in a new tab. Select your image, click <b>Upload</b>, copy the <b>Direct Link</b>, and paste it in the field below!
                        </span>
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    <div class="relative shrink-0">
                        <template x-if="avatarUrl">
                            <img :src="avatarUrl" alt="Avatar Preview" class="w-16 h-16 rounded-2xl object-cover border-2 border-rose-500 shadow-md">
                        </template>
                        <template x-if="!avatarUrl">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-brand-600 to-rose-600 text-white font-extrabold text-xl flex items-center justify-center border-2 border-slate-300 dark:border-slate-700 shadow-md">
                                {{ $user->donorProfile?->blood_group ?: substr($user->name, 0, 1) }}
                            </div>
                        </template>
                    </div>
                    <div class="flex-1 space-y-1.5">
                        <input type="url" name="avatar_url" x-model="avatarUrl" placeholder="https://i.ibb.co/your-image-link.jpg" class="w-full bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-mono">
                        <p class="text-[10px] text-slate-500">Paste direct image link (e.g. <code>https://i.ibb.co/...</code> or Unsplash link).</p>
                    </div>
                </div>

                <!-- Quick Presets -->
                <div class="mt-3 pt-3 border-t border-slate-200 dark:border-slate-800 flex items-center gap-2 flex-wrap">
                    <span class="text-[10px] font-bold text-slate-500 uppercase">Sample Avatars:</span>
                    <button type="button" @click="avatarUrl = 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=300&q=80'" class="px-2 py-1 rounded-lg text-[10px] font-bold bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-rose-500 hover:text-white transition">Avatar 1</button>
                    <button type="button" @click="avatarUrl = 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80'" class="px-2 py-1 rounded-lg text-[10px] font-bold bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-rose-500 hover:text-white transition">Avatar 2</button>
                    <button type="button" @click="avatarUrl = 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=300&q=80'" class="px-2 py-1 rounded-lg text-[10px] font-bold bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-rose-500 hover:text-white transition">Avatar 3</button>
                    <button type="button" @click="avatarUrl = ''" class="px-2 py-1 rounded-lg text-[10px] font-bold text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-slate-800 transition">Remove Photo</button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Email Address (Optional)</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="name@example.com" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Phone Number</label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Blood Group</label>
                    <select name="blood_group" required class="searchable-select w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-bold">
                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $g)
                            <option value="{{ $g }}" {{ ($user->donorProfile?->blood_group ?? '') === $g ? 'selected' : '' }}>{{ $g }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- PRIVACY CONTROL BOX -->
            <div class="p-4 rounded-2xl bg-slate-100 dark:bg-slate-900/90 border border-slate-300 dark:border-slate-700/80 space-y-2">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <label class="text-xs font-extrabold text-slate-900 dark:text-white uppercase block">
                            🔒 Allow Direct Phone / WhatsApp Contact
                        </label>
                        <p class="text-[11px] text-slate-600 dark:text-slate-400">Control whether patients can contact your mobile directly or through society helpline.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                        <input type="checkbox" name="allow_direct_contact" value="1" {{ ($user->donorProfile?->allow_direct_contact ?? true) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-300 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                    </label>
                </div>
                <span class="text-[10px] text-slate-500 dark:text-slate-400 block italic">
                    Enabled: Verified patients can call/WhatsApp your number directly.<br>
                    Disabled: Your number is kept private; requests go to society helpline.
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Availability Status</label>
                    <select name="availability_status" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                        <option value="available" {{ ($user->donorProfile?->availability_status ?? 'available') === 'available' ? 'selected' : '' }}>Available for Emergency Call</option>
                        <option value="unavailable" {{ ($user->donorProfile?->availability_status ?? '') === 'unavailable' ? 'selected' : '' }}>Currently Unavailable</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Donor Classification</label>
                    <select name="donor_type" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                        <option value="regular" {{ ($user->donorProfile?->donor_type ?? 'regular') === 'regular' ? 'selected' : '' }}>Regular Donor</option>
                        <option value="emergency" {{ ($user->donorProfile?->donor_type ?? '') === 'emergency' ? 'selected' : '' }}>Emergency On-Call Donor</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Village / Area</label>
                    <input type="text" name="village" value="{{ old('village', $user->donorProfile?->village) }}" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Block</label>
                    <input type="text" name="block" value="{{ old('block', $user->donorProfile?->block ?? 'Bhagwangola-I') }}" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">District</label>
                    <input type="text" name="district" value="{{ old('district', $user->donorProfile?->district ?? 'Murshidabad') }}" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Last Donation Date</label>
                <input type="date" name="last_donation_date" value="{{ old('last_donation_date', $user->donorProfile?->last_donation_date?->format('Y-m-d')) }}" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Medical Notes (Optional)</label>
                <textarea name="medical_notes" rows="2" placeholder="Any health allergies or blood pressure notes..." class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">{{ old('medical_notes', $user->donorProfile?->medical_notes) }}</textarea>
            </div>

            <div class="pt-4 flex items-center space-x-4">
                <button type="submit" class="w-full bg-gradient-to-r from-brand-600 to-rose-600 hover:opacity-95 text-white font-extrabold py-3 rounded-xl shadow-md transition glow-red">
                    Save Profile & Privacy Settings
                </button>
                <a href="{{ route('dashboard') }}" class="px-6 py-3 rounded-xl font-bold text-sm bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-700 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

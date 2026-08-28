@extends('layouts.app')

@section('title', 'Donor Dashboard — Manab Kalyane Rokto Dan')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-6 sm:py-10" x-data="{ storyModal: false, copied: false }">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6 sm:mb-8">
        <div class="flex items-center gap-3 min-w-0">
            @if($user->avatar_url)
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl object-cover border-2 border-rose-500 shadow-md shrink-0">
            @else
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-gradient-to-tr from-brand-600 to-rose-600 text-white font-extrabold text-lg sm:text-xl flex items-center justify-center border-2 border-slate-300 dark:border-slate-700 shadow-md shrink-0">
                    {{ $user->donorProfile?->blood_group ?: substr($user->name, 0, 1) }}
                </div>
            @endif
            <div class="min-w-0">
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-black text-slate-900 dark:text-white tracking-tight leading-snug">Welcome, {{ $user->name }}</h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-0.5 flex items-center gap-2 flex-wrap">
                    <span>Role:</span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-rose-500/20 text-rose-600 dark:text-rose-400 border border-rose-500/30">
                        {{ $user->getRoleObject()->label ?? ucfirst($user->role) }}
                    </span>
                    <span class="text-slate-400">|</span>
                    <span>Phone: {{ $user->phone }}</span>
                </p>
            </div>
        </div>
        <div class="flex items-center space-x-2 sm:space-x-3 flex-wrap">
            <button @click="storyModal = true" class="px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl font-bold text-xs bg-gradient-to-r from-brand-600 to-rose-600 text-white shadow-md hover:opacity-95 transition flex items-center gap-1.5">
                ✍️ Share Experience & Photo
            </button>
            <a href="{{ route('dashboard.card') }}" class="px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl font-bold text-xs bg-slate-200 dark:bg-slate-800 text-rose-600 dark:text-rose-300 border border-slate-300 dark:border-slate-700 hover:bg-slate-300 dark:hover:bg-slate-700 transition">
                🪪 Digital Donor Card
            </a>
            <a href="{{ route('dashboard.profile') }}" class="px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl font-bold text-xs bg-slate-200 dark:bg-slate-800 text-slate-800 dark:text-slate-300 border border-slate-300 dark:border-slate-700 hover:bg-slate-300 dark:hover:bg-slate-700 transition">
                Edit Profile
            </a>
        </div>
    </div>

    <!-- Loyalty Rewards & Referral Hub -->
    <div class="glass-card p-4 sm:p-6 rounded-2xl sm:rounded-3xl mb-6 sm:mb-8 bg-gradient-to-r from-slate-900 via-rose-950/40 to-slate-900 border border-rose-500/30 text-white shadow-2xl">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-center">
            
            <!-- Points & Rank Badge -->
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-amber-500 to-rose-600 text-white font-black text-2xl flex items-center justify-center shadow-lg border border-amber-300/40">
                    ⭐
                </div>
                <div>
                    <span class="text-xs uppercase font-extrabold text-amber-400 block tracking-wider">Member Loyalty Points</span>
                    <span class="text-3xl font-black text-white">{{ $user->loyalty_points }} <span class="text-xs text-slate-400 font-semibold">Pts</span></span>
                    <div class="mt-1">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-500/20 text-rose-300 border border-rose-500/40 uppercase">
                            {{ $user->loyalty_rank }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Referral Stats -->
            <div class="text-center lg:border-x border-slate-800 px-4 space-y-1">
                <span class="text-xs uppercase text-slate-400 font-bold block">Invited Members Count</span>
                <span class="text-2xl font-extrabold text-emerald-400">{{ $user->donorProfile?->referrals_count ?? $user->referrals->count() }} Members Joined</span>
                <p class="text-[11px] text-slate-400">Earn +50 Loyalty Points for every new member who registers using your link!</p>
            </div>

            <!-- Share Referral Link -->
            <div class="space-y-2">
                <span class="text-xs uppercase text-slate-300 font-extrabold block">Your Personal Invite Link</span>
                <div class="flex items-center gap-2">
                    <input type="text" readonly value="{{ route('register') . '?ref=' . $user->referral_code }}" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-xs font-mono text-rose-300 font-bold focus:outline-none">
                    <button @click="navigator.clipboard.writeText('{{ route('register') . '?ref=' . $user->referral_code }}'); copied = true; setTimeout(() => copied = false, 3000)" class="px-3 py-2 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-500 text-white whitespace-nowrap">
                        <span x-text="copied ? '✓ Copied!' : '📋 Copy'"></span>
                    </button>
                </div>
                <a href="https://api.whatsapp.com/send?text={{ urlencode('Join me as a voluntary blood donor on Manab Kalyane Rokto Dan! Register here: ' . route('register') . '?ref=' . $user->referral_code) }}" target="_blank" class="block w-full text-center py-2 rounded-xl text-xs font-extrabold bg-[#25D366] text-white hover:opacity-90 transition shadow">
                    📱 Invite Friends via WhatsApp (+50 Pts)
                </a>
            </div>

        </div>
    </div>

    <!-- Notification Center Box -->
    <div class="glass-card p-6 rounded-3xl mb-8 border border-slate-200 dark:border-slate-800 shadow-xl">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <span class="text-xl">🔔</span>
                <h3 class="text-lg font-extrabold text-slate-900 dark:text-white">Profile Notifications & Reminders</h3>
                @if($unreadNotificationsCount > 0)
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-600 text-white animate-pulse">
                        {{ $unreadNotificationsCount }} New
                    </span>
                @endif
            </div>
            <span class="text-xs text-slate-500 font-semibold">{{ count($notifications) }} Total Notifications</span>
        </div>

        <div class="space-y-3">
            @forelse($notifications as $notif)
                <div class="p-4 rounded-2xl border transition flex flex-col sm:flex-row sm:items-center justify-between gap-3 {{ $notif->is_read ? 'bg-slate-100/50 dark:bg-slate-900/40 border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400' : 'bg-rose-500/10 dark:bg-rose-950/30 border-rose-500/30 text-slate-900 dark:text-white shadow-md' }}">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-bold shrink-0 {{ $notif->type === 'blood_reminder' ? 'bg-rose-600 text-white' : ($notif->type === 'financial_reminder' ? 'bg-emerald-600 text-white' : 'bg-indigo-600 text-white') }}">
                            {{ $notif->type === 'blood_reminder' ? '🩸' : ($notif->type === 'financial_reminder' ? '💰' : '📢') }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="font-extrabold text-sm text-slate-900 dark:text-white">{{ $notif->title }}</h4>
                                @if(!$notif->is_read)
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-700 dark:text-slate-300 mt-0.5 leading-relaxed">{{ $notif->message }}</p>
                            <span class="text-[10px] text-slate-400 font-mono mt-1 block">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 self-end sm:self-center">
                        @if($notif->action_url)
                            <a href="{{ $notif->action_url }}" class="px-3 py-1.5 rounded-xl font-bold text-xs bg-rose-600 hover:bg-rose-500 text-white shadow transition">
                                View Action ➔
                            </a>
                        @endif

                        @if(!$notif->is_read)
                            <form action="{{ route('dashboard.notifications.read', $notif->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 rounded-xl font-bold text-xs bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-700 transition">
                                    ✓ Mark Read
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-slate-500 text-xs font-semibold">
                    You have no active notifications or reminders.
                </div>
            @endforelse
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

    <!-- Official Member Assigned Email & Credentials Hub -->
    <div class="glass-card p-6 sm:p-8 rounded-3xl mb-8 border border-indigo-500/30 bg-gradient-to-r from-indigo-950/40 via-slate-900 to-slate-950 text-white shadow-2xl relative overflow-hidden" x-data="{ showPass: false, passCopied: false, emailCopied: false }">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2 max-w-xl">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-indigo-500/20 text-indigo-300 border border-indigo-500/40 uppercase tracking-wider">
                        Official Member Access
                    </span>
                    <span class="text-xs text-slate-400 font-semibold">• Exclusive to Registered Donors</span>
                </div>
                <h3 class="text-xl sm:text-2xl font-black text-white flex items-center gap-2">
                    <span>📧</span> Official Member Webmail Credentials
                </h3>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Access your assigned official organization email mailbox to stay informed about emergency blood donation calls, donor verification, and official society announcements.
                </p>
            </div>

            @if(!empty($user->assigned_email))
                <div class="p-4 rounded-2xl bg-slate-950/80 border border-indigo-500/30 space-y-3 min-w-[280px]">
                    <!-- Assigned Email -->
                    <div>
                        <span class="text-[10px] font-extrabold uppercase text-indigo-400 tracking-wider block mb-1">Allocated Email ID</span>
                        <div class="flex items-center justify-between gap-2 bg-slate-900 px-3 py-2 rounded-xl border border-slate-800">
                            <span class="text-xs font-mono font-bold text-white truncate">{{ $user->assigned_email }}</span>
                            <button @click="navigator.clipboard.writeText('{{ $user->assigned_email }}'); emailCopied = true; setTimeout(() => emailCopied = false, 2500)" class="text-[11px] font-bold text-indigo-400 hover:text-indigo-300 shrink-0">
                                <span x-text="emailCopied ? '✓ Copied' : '📋 Copy'"></span>
                            </button>
                        </div>
                    </div>

                    <!-- Webmail Password -->
                    <div>
                        <span class="text-[10px] font-extrabold uppercase text-indigo-400 tracking-wider block mb-1">Webmail Access Password</span>
                        <div class="flex items-center justify-between gap-2 bg-slate-900 px-3 py-2 rounded-xl border border-slate-800">
                            <span class="text-xs font-mono font-bold text-amber-400" x-text="showPass ? '{{ $user->assigned_email_password }}' : '••••••••••••'"></span>
                            <div class="flex items-center gap-2 shrink-0">
                                <button @click="showPass = !showPass" class="text-xs text-slate-400 hover:text-white" :title="showPass ? 'Hide Password' : 'Reveal Password'">
                                    <span x-text="showPass ? '🙈 Hide' : '👁️ Reveal'"></span>
                                </button>
                                <button @click="navigator.clipboard.writeText('{{ $user->assigned_email_password }}'); passCopied = true; setTimeout(() => passCopied = false, 2500)" class="text-[11px] font-bold text-emerald-400 hover:text-emerald-300">
                                    <span x-text="passCopied ? '✓ Copied' : '📋 Copy'"></span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Login Button -->
                    <a href="{{ $user->assigned_email_login_url ?: 'https://webmail.mabia.in' }}" target="_blank" rel="noopener noreferrer" class="block w-full text-center py-2.5 rounded-xl font-extrabold text-xs bg-gradient-to-r from-indigo-600 to-rose-600 text-white hover:opacity-95 transition shadow-lg glow-red">
                        🚀 Open Webmail Portal Login ➔
                    </a>
                </div>
            @else
                <div class="p-4 rounded-2xl bg-slate-900/90 border border-slate-800 text-center space-y-2 max-w-sm">
                    <span class="text-2xl block">⏳</span>
                    <h4 class="text-xs font-extrabold text-white uppercase">Official Email Pending Allocation</h4>
                    <p class="text-[11px] text-slate-400 leading-relaxed">
                        Your official donor member email and webmail login credentials will be assigned by the administrator shortly.
                    </p>
                </div>
            @endif
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

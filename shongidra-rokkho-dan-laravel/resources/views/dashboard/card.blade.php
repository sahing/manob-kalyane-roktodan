@extends('layouts.app')

@section('title', 'Official Digital Donor ID Card — Manab Kalyane Rokto Dan')

@section('content')
<div class="max-w-xl mx-auto px-4 py-12 text-center" x-data="{ side: 'front' }">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 dark:hover:text-white">← Back to Dashboard</a>
        <div class="flex items-center gap-2">
            <button @click="side = (side === 'front' ? 'back' : 'front')" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-200 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-300 dark:border-slate-700 hover:bg-slate-300 transition">
                🔄 Flip Card (<span x-text="side === 'front' ? 'Back Side' : 'Front Side'"></span>)
            </button>
            <button onclick="window.print()" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-rose-600 text-white shadow-md hover:bg-rose-500 transition">
                🖨️ Print ID Card
            </button>
        </div>
    </div>

    <!-- FRONT SIDE OF DIGITAL DONOR ID CARD -->
    <div x-show="side === 'front'" class="relative bg-gradient-to-br from-slate-900 via-rose-950 to-slate-950 border-2 border-rose-500/60 rounded-3xl p-6 sm:p-8 text-left shadow-2xl overflow-hidden glow-red transform transition duration-500">
        <!-- Watermark Icon -->
        <div class="absolute -right-8 -bottom-8 w-48 h-48 bg-rose-600/10 rounded-full blur-2xl pointer-events-none"></div>

        <!-- Card Header -->
        <div class="flex items-center justify-between border-b border-slate-800/90 pb-4 mb-5">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-700 to-rose-500 text-white flex items-center justify-center font-black shadow-lg">
                    <svg class="w-6 h-6 fill-current animate-heartbeat" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path></svg>
                </div>
                <div>
                    <span class="text-xs font-extrabold text-white uppercase tracking-wider block leading-tight">Manab Kalyane Rokto Dan</span>
                    <span class="text-[10px] font-bold text-rose-400 block uppercase tracking-widest">Official Voluntary Donor Card</span>
                </div>
            </div>
            <span class="px-2.5 py-1 rounded-full text-[9px] font-extrabold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 uppercase tracking-wider">
                ✓ VERIFIED
            </span>
        </div>

        <!-- Cardholder Details Grid -->
        <div class="grid grid-cols-3 gap-4 items-center mb-6">
            <div class="col-span-2 space-y-2">
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Donor Name</span>
                    <h2 class="text-xl font-black text-white leading-tight tracking-tight">{{ $user->name }}</h2>
                </div>

                <div class="flex items-center gap-3 text-xs">
                    <div>
                        <span class="text-[9px] uppercase font-bold text-slate-400 block">Unique Donor ID</span>
                        <span class="font-mono text-rose-400 font-extrabold text-sm">{{ $cardId }}</span>
                    </div>
                    <div>
                        <span class="text-[9px] uppercase font-bold text-slate-400 block">Total Donations</span>
                        <span class="font-extrabold text-white">{{ $totalDonations }} Times</span>
                    </div>
                </div>

                <div>
                    <span class="text-[9px] uppercase font-bold text-slate-400 block">Block & District</span>
                    <span class="text-xs font-bold text-slate-200">{{ $user->donorProfile?->block ?? 'Bhagwangola' }}, Murshidabad</span>
                </div>
            </div>

            <!-- Blood Group Stamp -->
            <div class="text-center">
                <div class="w-20 h-20 mx-auto rounded-2xl bg-gradient-to-tr from-rose-600 to-brand-700 text-white font-black text-3xl flex items-center justify-center shadow-2xl border-2 border-rose-400/50 glow-red">
                    {{ $user->donorProfile?->blood_group ?? 'A+' }}
                </div>
                <span class="text-[9px] uppercase font-extrabold text-slate-400 block mt-1.5 tracking-wider">Blood Group</span>
            </div>
        </div>

        <!-- Bottom Footer with Dynamic QR Code -->
        <div class="pt-4 border-t border-slate-800/90 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-white p-1.5 rounded-xl border border-slate-700 shadow-md">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($verificationUrl) }}" alt="Donor QR Code" class="w-14 h-14">
                </div>
                <div class="text-[10px] text-slate-400 leading-tight">
                    <span class="font-bold text-slate-300 block mb-0.5">Scan to Verify Authenticity</span>
                    <span class="font-mono text-[9px] text-rose-400">/verify/{{ $cardId }}</span>
                </div>
            </div>

            <div class="text-right text-[10px] text-slate-400">
                <span class="block text-slate-300 font-bold">Helpline: +91 98321 00000</span>
                <span class="text-rose-400 font-semibold">Bhagwangola Society</span>
            </div>
        </div>
    </div>

    <!-- BACK SIDE OF DIGITAL DONOR ID CARD -->
    <div x-show="side === 'back'" x-cloak class="relative bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 border-2 border-slate-700 rounded-3xl p-6 sm:p-8 text-left shadow-2xl overflow-hidden transform transition duration-500">
        <div class="border-b border-slate-800 pb-3 mb-4 flex justify-between items-center">
            <span class="text-xs font-bold text-white uppercase tracking-wider">Emergency Guidelines & Terms</span>
            <span class="text-[10px] font-mono text-slate-400">ID: {{ $cardId }}</span>
        </div>

        <ul class="text-xs text-slate-300 space-y-2 mb-6 leading-relaxed">
            <li class="flex items-start gap-2">
                <span class="text-rose-500 font-bold">•</span>
                <span>This digital card confirms voluntary registration with Manab Kalyane Rokto Dan, Bhagwangola.</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="text-rose-500 font-bold">•</span>
                <span>Cardholder consents to be contacted during emergency blood requests in Murshidabad District.</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="text-rose-500 font-bold">•</span>
                <span>Scan the front QR code to inspect live verified donation history and certificate records.</span>
            </li>
        </ul>

        <div class="pt-4 border-t border-slate-800 text-center text-xs text-slate-400">
            <p class="font-bold text-white mb-1">Manab Kalyane Rokto Dan Helpline: +91 98321 00000</p>
            <p class="text-[10px]">Bhagwangola-I & Bhagwangola-II Voluntary Blood Network</p>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Official Digital Donor ID Card — Manab Kalyane Rokto Dan')

@section('content')
<!-- Include html2canvas for high-resolution PNG generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" crossorigin="anonymous"></script>

<style>
    @media print {
        @page {
            size: A4 portrait;
            margin: 10mm;
        }
        body {
            background: #ffffff !important;
            color: #000000 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        nav, header, footer, .no-print {
            display: none !important;
        }
        
        .cr80-card-wrapper {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: wrap !important;
            gap: 8mm !important;
            justify-content: center !important;
            align-items: center !important;
            margin-top: 10mm !important;
        }

        /* STANDARD CR80 CREDIT CARD PRINT DIMENSIONS (85.6mm x 53.98mm) */
        .cr80-card {
            width: 85.6mm !important;
            height: 53.98mm !important;
            min-width: 85.6mm !important;
            min-height: 53.98mm !important;
            max-width: 85.6mm !important;
            max-height: 53.98mm !important;
            border-radius: 3.18mm !important;
            box-sizing: border-box !important;
            overflow: hidden !important;
            padding: 3.5mm 4.5mm !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            page-break-inside: avoid !important;
            border: 1px solid #1e293b !important;
            background: linear-gradient(135deg, #0f172a 0%, #450a0a 100%) !important;
            color: #ffffff !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            box-shadow: none !important;
            transform: none !important;
        }

        .cr80-card [x-show] {
            display: flex !important;
        }
    }
</style>

<div class="max-w-3xl mx-auto px-4 py-8 text-center" x-data="{ side: 'front', shareModal: false }">
    
    <!-- Controls Header (Hidden on Print) -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3 no-print">
        <a href="{{ route('dashboard') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 dark:hover:text-white flex items-center gap-1">
            ← Back to Dashboard
        </a>

        <div class="flex flex-wrap items-center gap-2">
            <!-- Flip Card -->
            <button @click="side = (side === 'front' ? 'back' : 'front')" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-200 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-300 dark:border-slate-700 hover:bg-slate-300 transition">
                🔄 Flip Card (<span x-text="side === 'front' ? 'Back' : 'Front'"></span>)
            </button>

            <!-- Download PNG Button -->
            <button onclick="downloadCardPNG()" class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold bg-emerald-600 hover:bg-emerald-500 text-white shadow-md transition flex items-center gap-1.5">
                <span>🖼️</span>
                <span>Download HD Image</span>
            </button>

            <!-- Share Modal Button -->
            <button @click="shareModal = true" class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold bg-rose-600 hover:bg-rose-500 text-white shadow-md transition flex items-center gap-1.5">
                <span>📲</span>
                <span>Share Card</span>
            </button>

            <!-- Print Button -->
            <button onclick="window.print()" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-800 text-slate-200 hover:bg-slate-700 transition flex items-center gap-1">
                🖨️ Print (CR80)
            </button>
        </div>
    </div>

    <!-- CARD CONTAINER -->
    <div class="cr80-card-wrapper my-4">
        
        <!-- FRONT SIDE DIGITAL DONOR ID CARD -->
        <div id="card-front-side" x-show="side === 'front'" class="cr80-card relative bg-gradient-to-br from-slate-900 via-rose-950 to-slate-950 border-2 border-rose-500/60 rounded-3xl p-5 sm:p-6 text-left shadow-2xl overflow-hidden glow-red transform transition duration-500 mx-auto w-full max-w-[420px] aspect-[1.586/1]">
            
            <!-- Metallic Watermark Badge -->
            <div class="absolute -right-8 -bottom-8 w-36 h-36 rounded-full bg-rose-600/10 border border-rose-500/20 pointer-events-none"></div>

            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-800/90 pb-2 mb-2 relative z-10">
                <div class="flex items-center space-x-2">
                    <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-brand-700 to-rose-500 text-white flex items-center justify-center font-black shadow shrink-0">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path></svg>
                    </div>
                    <div>
                        <span class="text-[11px] font-black text-white uppercase tracking-wider block leading-none">Manab Kalyane Rokto Dan</span>
                        <span class="text-[8px] font-extrabold text-rose-400 block uppercase tracking-widest mt-0.5">Official Voluntary Donor Card</span>
                    </div>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[8px] font-black bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 uppercase tracking-wider">
                    ✓ VERIFIED
                </span>
            </div>

            <!-- Card Details Grid -->
            <div class="flex items-center justify-between gap-3 mb-2 relative z-10">
                <div class="flex items-center gap-3">
                    @if($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" crossorigin="anonymous" alt="{{ $user->name }}" class="w-14 h-14 rounded-xl object-cover border-2 border-rose-500 shadow shrink-0">
                    @else
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-brand-700 to-rose-500 text-white font-black text-base flex items-center justify-center border border-rose-400/40 shadow shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="space-y-0.5">
                        <span class="text-[8px] uppercase font-bold text-slate-400 block leading-none">Donor Name</span>
                        <h2 class="text-sm sm:text-base font-black text-white leading-tight tracking-tight truncate max-w-[170px]">{{ $user->name }}</h2>
                        <div class="flex items-center gap-2 text-[9px]">
                            <span class="font-mono text-rose-400 font-bold">{{ $cardId }}</span>
                            <span class="text-slate-400">•</span>
                            <span class="text-slate-300 font-bold">{{ $totalDonations }} Donation(s)</span>
                        </div>
                        <span class="text-[8px] font-semibold text-slate-300 block truncate max-w-[170px]">📍 {{ $user->donorProfile?->block ?? 'Bhagwangola' }}, Murshidabad</span>
                    </div>
                </div>

                <!-- Blood Group Badge -->
                <div class="text-center shrink-0">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-rose-600 to-brand-700 text-white font-black text-lg flex items-center justify-center shadow-lg border border-rose-400/50">
                        {{ $user->donorProfile?->blood_group ?? 'A+' }}
                    </div>
                    <span class="text-[7px] uppercase font-black text-slate-400 block mt-1 tracking-wider">Group</span>
                </div>
            </div>

            <!-- Card Footer with QR Code -->
            <div class="pt-2 border-t border-slate-800/90 flex items-center justify-between relative z-10">
                <div class="flex items-center gap-2">
                    <div class="bg-white p-1 rounded-lg border border-slate-700 shadow shrink-0">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($verificationUrl) }}" crossorigin="anonymous" alt="QR Code" class="w-8 h-8">
                    </div>
                    <div class="text-[8px] text-slate-400 leading-tight">
                        <span class="font-bold text-slate-200 block">Scan to Verify</span>
                        <span class="font-mono text-[7px] text-rose-400">/verify/{{ $cardId }}</span>
                    </div>
                </div>

                <div class="text-right text-[8px] text-slate-400 leading-tight">
                    <span class="block text-slate-200 font-bold">Helpline: {{ $helplinePhone }}</span>
                    <span class="text-rose-400 font-semibold">Bhagwangola Voluntary Network</span>
                </div>
            </div>
        </div>

        <!-- BACK SIDE DIGITAL DONOR ID CARD -->
        <div id="card-back-side" x-show="side === 'back'" class="cr80-card relative bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 border-2 border-slate-700 rounded-3xl p-5 sm:p-6 text-left shadow-2xl overflow-hidden transform transition duration-500 mx-auto w-full max-w-[420px] aspect-[1.586/1]">
            <div class="border-b border-slate-800 pb-1.5 mb-2 flex justify-between items-center">
                <span class="text-[10px] font-extrabold text-white uppercase tracking-wider">Emergency Terms & Info</span>
                <span class="text-[8px] font-mono text-slate-400">ID: {{ $cardId }}</span>
            </div>

            <ul class="text-[9px] text-slate-300 space-y-1 mb-3 leading-snug">
                <li class="flex items-start gap-1.5">
                    <span class="text-rose-500 font-bold">•</span>
                    <span>Official voluntary donor identity issued by Manab Kalyane Rokto Dan.</span>
                </li>
                <li class="flex items-start gap-1.5">
                    <span class="text-rose-500 font-bold">•</span>
                    <span>Holder agrees to emergency on-call contact for critical blood requests.</span>
                </li>
                <li class="flex items-start gap-1.5">
                    <span class="text-rose-500 font-bold">•</span>
                    <span>Scan front QR code to verify donation history and digital certificate logs.</span>
                </li>
            </ul>

            <div class="pt-2 border-t border-slate-800 text-center text-[8px] text-slate-400">
                <p class="font-bold text-white">Helpline: {{ $helplinePhone }}</p>
                <p class="text-[7px]">Bhagwangola-I & Bhagwangola-II Voluntary Blood Network</p>
            </div>
        </div>

    </div>

    <!-- Social Share Modal -->
    <div x-show="shareModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md no-print">
        <div class="glass-card max-w-md w-full p-6 rounded-3xl border border-slate-300 dark:border-slate-800 relative shadow-2xl text-left">
            <button @click="shareModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-900 dark:hover:text-white text-xl font-bold">&times;</button>

            <h3 class="text-lg font-extrabold text-slate-900 dark:text-white mb-1">Share Your Verified Donor Card</h3>
            <p class="text-xs text-slate-600 dark:text-slate-400 mb-4">Inspire your friends and family by sharing your official donor card on social media!</p>

            @php
                $shareText = "I am a proud verified voluntary blood donor with Manab Kalyane Rokto Dan! Blood Group: " . ($user->donorProfile?->blood_group ?? 'A+') . " | Donor Code: " . $cardId . ". Check my official verified donor card here:";
                $whatsappUrl = "https://api.whatsapp.com/send?text=" . urlencode($shareText . "\n" . $verificationUrl);
                $facebookUrl = "https://www.facebook.com/sharer/sharer.php?u=" . urlencode($verificationUrl);
                $telegramUrl = "https://t.me/share/url?url=" . urlencode($verificationUrl) . "&text=" . urlencode($shareText);
                $twitterUrl  = "https://twitter.com/intent/tweet?text=" . urlencode($shareText) . "&url=" . urlencode($verificationUrl);
            @endphp

            <div class="space-y-2.5">
                <!-- WhatsApp -->
                <a href="{{ $whatsappUrl }}" target="_blank" class="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold text-xs flex items-center justify-center gap-2 shadow">
                    <span>💬</span> Share to WhatsApp
                </a>

                <!-- Telegram -->
                <a href="{{ $telegramUrl }}" target="_blank" class="w-full py-2.5 px-4 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-extrabold text-xs flex items-center justify-center gap-2 shadow">
                    <span>✈️</span> Share to Telegram
                </a>

                <!-- Facebook -->
                <a href="{{ $facebookUrl }}" target="_blank" class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-extrabold text-xs flex items-center justify-center gap-2 shadow">
                    <span>📘</span> Share to Facebook
                </a>

                <!-- Twitter -->
                <a href="{{ $twitterUrl }}" target="_blank" class="w-full py-2.5 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-extrabold text-xs flex items-center justify-center gap-2 shadow">
                    <span>🐦</span> Share to X / Twitter
                </a>
            </div>
        </div>
    </div>
    
    <!-- Printable Instructions Tip -->
    <div class="mt-6 p-4 rounded-2xl bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-800 text-xs text-slate-600 dark:text-slate-400 no-print max-w-lg mx-auto">
        <span class="font-extrabold text-slate-900 dark:text-white block mb-1">💡 Printing & Download Advice:</span>
        <p>Click <b>Download HD Image</b> to save a high-res PNG image directly to your phone/PC. For plastic CR80 credit card printing, click <b>Print</b> and set Scale to 100%.</p>
    </div>
</div>

<!-- JS Script for Client-Side High-Res PNG Download -->
<script>
    function downloadCardPNG() {
        const side = Alpine.$data(document.querySelector('[x-data]')).side;
        const cardElement = document.getElementById(side === 'front' ? 'card-front-side' : 'card-back-side');
        if (!cardElement) return;

        const originalText = event.currentTarget.innerHTML;
        event.currentTarget.innerHTML = '⏳ Generating HD Image...';

        html2canvas(cardElement, {
            scale: 3, // High DPI resolution (300 DPI)
            useCORS: true,
            allowTaint: true,
            backgroundColor: null
        }).then(canvas => {
            const link = document.createElement('a');
            link.download = `Donor_Card_${side.toUpperCase()}_{{ $cardId }}.png`;
            link.href = canvas.toDataURL('image/png', 1.0);
            link.click();
            event.currentTarget.innerHTML = originalText;
        }).catch(err => {
            console.error('Download error:', err);
            event.currentTarget.innerHTML = originalText;
            alert('Unable to generate PNG image. Please use Print to save as PDF.');
        });
    }
</script>
@endsection

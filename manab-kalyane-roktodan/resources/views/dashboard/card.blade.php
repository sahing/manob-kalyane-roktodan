@extends('layouts.app')

@section('title', 'Official Digital Donor ID Card — Manab Kalyane Rokto Dan')

@section('content')
<!-- Include html-to-image, html2canvas & qrcodejs for 100% reliable PNG generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html-to-image/1.11.11/html-to-image.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" crossorigin="anonymous"></script>

<style>
    /* CR80 Proportions for Perfect Consistency Across Screen, Image Export & Print */
    .donor-card-box {
        width: 100%;
        max-width: 410px;
        aspect-ratio: 1.586 / 1;
        box-sizing: border-box;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    @media print {
        @page {
            size: A4 portrait;
            margin: 15mm 10mm;
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

        .print-cards-wrapper {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8mm !important;
            margin-top: 10mm !important;
            background: transparent !important;
            padding: 0 !important;
        }

        /* CR80 Physical Print Specs: 85.6mm x 53.98mm */
        .donor-card-box {
            width: 85.6mm !important;
            height: 53.98mm !important;
            min-width: 85.6mm !important;
            min-height: 53.98mm !important;
            max-width: 85.6mm !important;
            max-height: 53.98mm !important;
            border-radius: 3.18mm !important;
            padding: 3.5mm 4.5mm !important;
            box-shadow: none !important;
            transform: none !important;
            page-break-inside: avoid !important;
        }

        /* Strictly respect selected view mode during printing */
        [style*="display: none"], [x-cloak] {
            display: none !important;
        }
    }
</style>

<div class="max-w-4xl mx-auto px-3 sm:px-6 py-6 sm:py-10 text-center" x-data="{ viewMode: 'both', shareModal: false }">
    
    <!-- Controls Header (Hidden on Print) -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3 no-print">
        <a href="{{ route('dashboard') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 dark:hover:text-white flex items-center gap-1">
            ← Back to Dashboard
        </a>

        <div class="flex flex-wrap items-center gap-2">
            <!-- View Selector Tabs -->
            <div class="bg-slate-200 dark:bg-slate-800 p-1 rounded-xl flex items-center gap-1 border border-slate-300 dark:border-slate-700 text-xs">
                <button type="button" @click="viewMode = 'both'" :class="viewMode === 'both' ? 'bg-white dark:bg-slate-900 text-rose-600 dark:text-rose-400 font-extrabold shadow-sm' : 'text-slate-600 dark:text-slate-400 font-semibold'" class="px-2.5 py-1 rounded-lg transition">
                    Both Sides
                </button>
                <button type="button" @click="viewMode = 'front'" :class="viewMode === 'front' ? 'bg-white dark:bg-slate-900 text-rose-600 dark:text-rose-400 font-extrabold shadow-sm' : 'text-slate-600 dark:text-slate-400 font-semibold'" class="px-2.5 py-1 rounded-lg transition">
                    Front Side
                </button>
                <button type="button" @click="viewMode = 'back'" :class="viewMode === 'back' ? 'bg-white dark:bg-slate-900 text-rose-600 dark:text-rose-400 font-extrabold shadow-sm' : 'text-slate-600 dark:text-slate-400 font-semibold'" class="px-2.5 py-1 rounded-lg transition">
                    Back Side
                </button>
            </div>

            <!-- Download HD PNG Image Button -->
            <button type="button" onclick="downloadActiveCardPNG(this)" class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold bg-emerald-600 hover:bg-emerald-500 text-white shadow-md transition flex items-center gap-1.5">
                <span>🖼️</span>
                <span x-text="viewMode === 'front' ? 'Download Front PNG' : (viewMode === 'back' ? 'Download Back PNG' : 'Download Both PNG')">Download HD Image</span>
            </button>

            <!-- Share Card Button -->
            <button type="button" @click="shareModal = true" class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold bg-rose-600 hover:bg-rose-500 text-white shadow-md transition flex items-center gap-1.5">
                <span>📲</span> Share Card
            </button>

            <!-- Print Selected Card Button -->
            <button type="button" onclick="window.print()" class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-slate-800 text-slate-200 hover:bg-slate-700 transition flex items-center gap-1">
                <span>🖨️</span>
                <span x-text="viewMode === 'front' ? 'Print Front Card' : (viewMode === 'back' ? 'Print Back Card' : 'Print Both Cards')">Print Card (CR80)</span>
            </button>
        </div>
    </div>

    <!-- CARDS CONTAINER (GRID ON SCREEN, STACKED IN PRINT) -->
    <div id="cards-export-container" class="print-cards-wrapper grid grid-cols-1 md:grid-cols-2 gap-6 items-center justify-center my-4 bg-slate-950 p-4 rounded-3xl">
        
        <!-- FRONT SIDE DIGITAL DONOR ID CARD -->
        <div id="card-front-side" 
             x-show="viewMode === 'both' || viewMode === 'front'" 
             class="donor-card-box relative bg-gradient-to-br from-slate-900 via-rose-950 to-slate-950 border-2 border-rose-500/60 rounded-3xl p-4 sm:p-5 text-left shadow-2xl overflow-hidden transform transition duration-300 mx-auto flex flex-col justify-between">
            
            <!-- Background Metallic Watermark Seal -->
            <div class="absolute -right-8 -bottom-8 w-36 h-36 rounded-full bg-rose-600/10 border border-rose-500/20 pointer-events-none"></div>

            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-800/90 pb-1.5 mb-2 relative z-10">
                <div class="flex items-center space-x-2">
                    <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-gradient-to-tr from-brand-700 to-rose-500 text-white flex items-center justify-center font-black shadow shrink-0">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path></svg>
                    </div>
                    <div>
                        <span class="text-[10px] sm:text-[11px] font-black text-white uppercase tracking-wider block leading-none">Manab Kalyane Rokto Dan</span>
                        <span class="text-[7px] sm:text-[8px] font-extrabold text-rose-400 block uppercase tracking-widest mt-0.5">Official Voluntary Donor Card</span>
                    </div>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[8px] font-black bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 uppercase tracking-wider shrink-0">
                    ✓ VERIFIED
                </span>
            </div>

            <!-- Card Body Grid -->
            <div class="flex items-center justify-between gap-2.5 mb-2 relative z-10">
                <div class="flex items-center gap-2.5 min-w-0">
                    @if(!empty($avatarDataUri))
                        <img src="{{ $avatarDataUri }}" alt="{{ $user->name }}" class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl object-cover border-2 border-rose-500 shadow shrink-0">
                    @elseif($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" crossorigin="anonymous" alt="{{ $user->name }}" class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl object-cover border-2 border-rose-500 shadow shrink-0">
                    @else
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-tr from-brand-700 to-rose-500 text-white font-black text-sm sm:text-base flex items-center justify-center border border-rose-400/40 shadow shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="space-y-0.5 min-w-0">
                        <span class="text-[7px] sm:text-[8px] uppercase font-bold text-slate-400 block leading-none">Donor Name</span>
                        <h2 class="text-xs sm:text-sm font-black text-white leading-tight tracking-tight truncate max-w-[160px]">{{ $user->name }}</h2>
                        <div class="flex items-center gap-1.5 text-[8px] sm:text-[9px]">
                            <span class="font-mono text-rose-400 font-bold">{{ $cardId }}</span>
                            <span class="text-slate-400">•</span>
                            <span class="text-slate-300 font-bold">{{ $totalDonations }} Donation(s)</span>
                        </div>
                        <span class="text-[7px] sm:text-[8px] font-semibold text-slate-300 block truncate max-w-[160px]">📍 {{ $user->donorProfile?->block ?? 'Bhagwangola' }}, Murshidabad</span>
                    </div>
                </div>

                <!-- Blood Group Badge -->
                <div class="text-center shrink-0">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-tr from-rose-600 to-brand-700 text-white font-black text-base sm:text-lg flex items-center justify-center shadow-lg border border-rose-400/50">
                        {{ $user->donorProfile?->blood_group ?? 'A+' }}
                    </div>
                    <span class="text-[7px] uppercase font-black text-slate-400 block mt-0.5 tracking-wider">Group</span>
                </div>
            </div>

            <!-- Card Footer with Verification QR Code -->
            <div class="pt-1.5 border-t border-slate-800/90 flex items-center justify-between relative z-10">
                <div class="flex items-center gap-2">
                    <!-- Inline QR Code Container -->
                    <div class="bg-white p-1 rounded-lg border border-slate-700 shadow shrink-0">
                        <div id="qrcode-box" class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($verificationUrl) }}" crossorigin="anonymous" alt="QR Code" class="w-7 h-7 sm:w-8 sm:h-8">
                        </div>
                    </div>
                    <div class="text-[7px] sm:text-[8px] text-slate-400 leading-tight">
                        <span class="font-bold text-slate-200 block">Scan to Verify</span>
                        <span class="font-mono text-[7px] text-rose-400">/verify/{{ $cardId }}</span>
                    </div>
                </div>

                <div class="text-right text-[7px] sm:text-[8px] text-slate-400 leading-tight">
                    <span class="block text-slate-200 font-bold">Helpline: {{ $helplinePhone }}</span>
                    <span class="text-rose-400 font-semibold">Bhagwangola Voluntary Network</span>
                </div>
            </div>
        </div>

        <!-- BACK SIDE DIGITAL DONOR ID CARD -->
        <div id="card-back-side" 
             x-show="viewMode === 'both' || viewMode === 'back'" 
             class="donor-card-box relative bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 border-2 border-slate-700 rounded-3xl p-4 sm:p-5 text-left shadow-2xl overflow-hidden transform transition duration-300 mx-auto flex flex-col justify-between">
            
            <div class="border-b border-slate-800 pb-1.5 mb-2 flex justify-between items-center">
                <span class="text-[9px] sm:text-[10px] font-extrabold text-white uppercase tracking-wider">Emergency Terms & Donor Guidelines</span>
                <span class="text-[8px] font-mono text-slate-400">ID: {{ $cardId }}</span>
            </div>

            <ul class="text-[8px] sm:text-[9px] text-slate-300 space-y-1 mb-2 leading-snug">
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
                <a href="{{ $whatsappUrl }}" target="_blank" class="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold text-xs flex items-center justify-center gap-2 shadow">
                    <span>💬</span> Share to WhatsApp
                </a>
                <a href="{{ $telegramUrl }}" target="_blank" class="w-full py-2.5 px-4 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-extrabold text-xs flex items-center justify-center gap-2 shadow">
                    <span>✈️</span> Share to Telegram
                </a>
                <a href="{{ $facebookUrl }}" target="_blank" class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-extrabold text-xs flex items-center justify-center gap-2 shadow">
                    <span>📘</span> Share to Facebook
                </a>
                <a href="{{ $twitterUrl }}" target="_blank" class="w-full py-2.5 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-extrabold text-xs flex items-center justify-center gap-2 shadow">
                    <span>🐦</span> Share to X / Twitter
                </a>
            </div>
        </div>
    </div>
    
    <!-- Printable Instructions Tip -->
    <div class="mt-6 p-4 rounded-2xl bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-800 text-xs text-slate-600 dark:text-slate-400 no-print max-w-lg mx-auto">
        <span class="font-extrabold text-slate-900 dark:text-white block mb-1">💡 Printing & Image Download Info:</span>
        <p>Selected card side(s) will print automatically at standard CR80 card dimensions (85.6mm × 53.98mm). Switch tabs above to print or download Front, Back, or Both sides.</p>
    </div>
</div>

<!-- High-Res PNG Downloader Engine -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const qrContainer = document.getElementById("qrcode-box");
        if (qrContainer && typeof QRCode !== 'undefined') {
            try {
                qrContainer.innerHTML = '';
                new QRCode(qrContainer, {
                    text: "{{ $verificationUrl }}",
                    width: 32,
                    height: 32,
                    colorDark : "#0f172a",
                    colorLight : "#ffffff",
                    correctLevel : QRCode.CorrectLevel.M
                });
            } catch (e) {
                console.warn("QR Code JS fallback triggered:", e);
            }
        }
    });

    async function downloadActiveCardPNG(btnElement) {
        let viewMode = 'both';
        try {
            const rootEl = document.querySelector('[x-data]');
            if (rootEl && window.Alpine && typeof window.Alpine.$data === 'function') {
                const alpineData = window.Alpine.$data(rootEl);
                if (alpineData && alpineData.viewMode) {
                    viewMode = alpineData.viewMode;
                }
            }
        } catch(e) {}

        viewMode = String(viewMode || 'both');

        let exportTarget;
        if (viewMode === 'front') {
            exportTarget = document.getElementById('card-front-side');
        } else if (viewMode === 'back') {
            exportTarget = document.getElementById('card-back-side');
        } else {
            exportTarget = document.getElementById('cards-export-container');
        }

        if (!exportTarget) return;

        const originalText = btnElement ? btnElement.innerHTML : 'Download HD Image';
        if (btnElement) btnElement.innerHTML = '⏳ Exporting HD PNG...';

        const safeModeStr = viewMode.toUpperCase();
        const fileName = `Donor_Card_${safeModeStr}_{{ $cardId }}.png`;

        // Trigger file download helper
        const triggerDownload = (dataUrl) => {
            const link = document.createElement('a');
            link.download = fileName;
            link.href = dataUrl;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        };


        // Engine 1: html-to-image (Uses native browser SVG foreignObject for pixel-perfect render)
        if (typeof htmlToImage !== 'undefined') {
            try {
                const dataUrl = await htmlToImage.toPng(exportTarget, {
                    quality: 0.95,
                    pixelRatio: 2,
                    backgroundColor: viewMode === 'both' ? '#020617' : null,
                    filter: (node) => !node.classList || !node.classList.contains('no-print')
                });
                triggerDownload(dataUrl);
                if (btnElement) btnElement.innerHTML = originalText;
                return;
            } catch (err1) {
                console.warn('htmlToImage failed, trying html2canvas fallback:', err1);
            }
        }

        // Engine 2: html2canvas fallback
        if (typeof html2canvas !== 'undefined') {
            try {
                const canvas = await html2canvas(exportTarget, {
                    scale: 2,
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: viewMode === 'both' ? '#020617' : null,
                    ignoreElements: (node) => node.classList && node.classList.contains('no-print')
                });
                const dataUrl = canvas.toDataURL('image/png');
                triggerDownload(dataUrl);
                if (btnElement) btnElement.innerHTML = originalText;
                return;
            } catch (err2) {
                console.warn('html2canvas failed:', err2);
            }
        }

        // Engine 3: Print to PDF fallback
        if (btnElement) btnElement.innerHTML = originalText;
        alert("Unable to capture image automatically. Opening print window to save as PDF...");
        window.print();
    }
</script>
@endsection

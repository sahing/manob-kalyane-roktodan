<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Certificate of Honor — {{ $donation->user->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;900&family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-cinzel { font-family: 'Cinzel', serif; }
        [x-cloak] { display: none !important; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; color: black !important; padding: 0 !important; }
            .cert-box { border-width: 6px !important; box-shadow: none !important; }
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-900 min-h-screen flex flex-col items-center justify-center p-4 sm:p-8" x-data="{ shareModal: false }">

    <!-- Top Toolbar (Hidden on Print) -->
    <div class="no-print mb-6 flex flex-wrap items-center justify-center gap-3">
        <a href="{{ route('dashboard') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-800 text-slate-300 hover:text-white border border-slate-700">
            ← Back to Dashboard
        </a>

        <!-- Download HD Image -->
        <button onclick="downloadCertPNG()" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold text-xs shadow-xl transition flex items-center gap-1.5">
            <span>🖼️</span> Download HD Image (PNG)
        </button>

        <!-- Share Certificate -->
        <button @click="shareModal = true" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-extrabold text-xs shadow-xl transition flex items-center gap-1.5">
            <span>💬</span> Share Certificate
        </button>

        <!-- Print PDF -->
        <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-extrabold text-xs transition flex items-center gap-1.5">
            🖨️ Print / Save PDF
        </button>
    </div>

    <!-- Official Certificate Frame -->
    <div id="certificate-print-box" class="cert-box bg-gradient-to-b from-amber-50 via-white to-amber-50/80 text-slate-900 border-8 border-double border-amber-700 max-w-4xl w-full p-6 sm:p-14 rounded-3xl shadow-2xl relative overflow-hidden">
        
        <!-- Header Ornaments -->
        <div class="flex items-center justify-between border-b-2 border-amber-800/20 pb-4 mb-6">
            <div>
                <span class="text-[11px] font-mono font-bold text-amber-900 block">CERTIFICATE REF: {{ $donation->certificate_id }}</span>
                <span class="text-[10px] text-slate-500 font-semibold">Verify online at {{ route('verify.show', $donation->certificate_id) }}</span>
            </div>
            <div class="text-right">
                <span class="text-xs font-black text-rose-900 uppercase tracking-wider block">Manab Kalyane Rokto Dan</span>
                <span class="text-[9px] font-bold text-amber-800 uppercase block">Bhagwangola Voluntary Society</span>
            </div>
        </div>

        <div class="text-center space-y-4">
            <!-- Emblem Seal -->
            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gradient-to-tr from-rose-700 to-rose-900 text-white mx-auto flex items-center justify-center text-3xl sm:text-4xl shadow-xl border-4 border-amber-400">
                🩸
            </div>

            <span class="inline-block text-[10px] sm:text-xs uppercase tracking-[0.35em] font-extrabold text-amber-900 bg-amber-200/50 px-4 py-1 rounded-full border border-amber-300">
                Official Certificate of Appreciation
            </span>

            <h1 class="text-2xl sm:text-4xl font-extrabold font-cinzel text-rose-950 tracking-tight">
                PROUD VOLUNTARY BLOOD DONOR
            </h1>

            <p class="text-[10px] sm:text-xs uppercase tracking-widest text-slate-600 font-bold pt-1">This is proudly presented to</p>
            
            <div class="text-2xl sm:text-4xl font-black text-slate-900 font-cinzel border-b-2 border-slate-300 pb-2 max-w-lg mx-auto text-rose-900">
                {{ $donation->user->name }}
            </div>

            <!-- Member Loyalty & Honor Badge Row -->
            <div class="flex items-center justify-center gap-2 sm:gap-3 pt-1">
                <span class="px-3 py-1 rounded-full text-[11px] font-extrabold bg-amber-500/20 text-amber-900 border border-amber-500/40">
                    ⭐ Member Loyalty Points: {{ $donation->user->loyalty_points }} Pts
                </span>
                <span class="px-3 py-1 rounded-full text-[11px] font-extrabold bg-rose-500/20 text-rose-900 border border-rose-500/40">
                    {{ $donation->user->loyalty_rank }}
                </span>
            </div>

            <p class="text-xs sm:text-sm text-slate-700 max-w-xl mx-auto leading-relaxed pt-2">
                In heartfelt recognition of your noble voluntary blood donation on <strong class="text-rose-950 font-extrabold">{{ $donation->donation_date ? $donation->donation_date->format('F d, Y') : 'Record' }}</strong> at {{ $donation->location ?? 'Bhagwangola Rural Hospital' }}. Your selfless contribution and dedication to inspiring other voluntary members has saved human lives.
            </p>

            <!-- Verification QR Code & Official Seals -->
            <div class="pt-6 grid grid-cols-3 gap-4 sm:gap-6 items-end max-w-2xl mx-auto text-xs">
                
                <!-- Left: President Signature -->
                <div class="text-center border-t-2 border-slate-400 pt-2">
                    <div class="font-bold text-slate-900 font-cinzel text-[11px] sm:text-xs">President</div>
                    <div class="text-[9px] sm:text-[10px] text-slate-600 font-semibold">Manab Kalyane Rokto Dan</div>
                </div>

                <!-- Center: Verification QR Code -->
                <div class="text-center flex flex-col items-center justify-center">
                    <div class="bg-white p-1.5 rounded-xl border border-slate-300 shadow-md">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data={{ urlencode($verificationUrl) }}" crossorigin="anonymous" alt="Verification QR" class="w-12 h-12 sm:w-16 sm:h-16">
                    </div>
                    <span class="text-[8px] sm:text-[9px] font-mono text-slate-500 mt-1">Scan to Verify</span>
                </div>

                <!-- Right: General Secretary -->
                <div class="text-center border-t-2 border-slate-400 pt-2">
                    <div class="font-bold text-slate-900 font-cinzel text-[11px] sm:text-xs">General Secretary</div>
                    <div class="text-[9px] sm:text-[10px] text-slate-600 font-semibold">Bhagwangola Society</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Social Share Modal -->
    <div x-show="shareModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md no-print">
        <div class="bg-white dark:bg-slate-900 max-w-md w-full p-6 rounded-3xl border border-slate-300 dark:border-slate-800 relative shadow-2xl text-left">
            <button @click="shareModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-900 dark:hover:text-white text-xl font-bold">&times;</button>

            <h3 class="text-lg font-extrabold text-slate-900 dark:text-white mb-1">Share Honor Certificate</h3>
            <p class="text-xs text-slate-600 dark:text-slate-400 mb-4">Share your verified blood donation certificate with friends and family!</p>

            @php
                $certShareText = "I have been awarded an Official Certificate of Honor for voluntary blood donation at Manab Kalyane Rokto Dan! Certificate Ref: " . $donation->certificate_id . ". Verify online here:";
                $certWhatsappUrl = "https://api.whatsapp.com/send?text=" . urlencode($certShareText . "\n" . $verificationUrl);
                $certFacebookUrl = "https://www.facebook.com/sharer/sharer.php?u=" . urlencode($verificationUrl);
                $certTelegramUrl = "https://t.me/share/url?url=" . urlencode($verificationUrl) . "&text=" . urlencode($certShareText);
                $certTwitterUrl  = "https://twitter.com/intent/tweet?text=" . urlencode($certShareText) . "&url=" . urlencode($verificationUrl);
            @endphp

            <div class="space-y-2.5">
                <a href="{{ $certWhatsappUrl }}" target="_blank" class="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold text-xs flex items-center justify-center gap-2 shadow">
                    <span>💬</span> Share to WhatsApp
                </a>
                <a href="{{ $certTelegramUrl }}" target="_blank" class="w-full py-2.5 px-4 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-extrabold text-xs flex items-center justify-center gap-2 shadow">
                    <span>✈️</span> Share to Telegram
                </a>
                <a href="{{ $certFacebookUrl }}" target="_blank" class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-extrabold text-xs flex items-center justify-center gap-2 shadow">
                    <span>📘</span> Share to Facebook
                </a>
                <a href="{{ $certTwitterUrl }}" target="_blank" class="w-full py-2.5 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-extrabold text-xs flex items-center justify-center gap-2 shadow">
                    <span>🐦</span> Share to X / Twitter
                </a>
            </div>
        </div>
    </div>

    <!-- JS Script for High Resolution Certificate Image Generation -->
    <script>
        function downloadCertPNG() {
            const certBox = document.getElementById('certificate-print-box');
            if (!certBox) return;

            const btn = event.currentTarget;
            const originalText = btn.innerHTML;
            btn.innerHTML = '⏳ Generating HD Image...';

            html2canvas(certBox, {
                scale: 3, // High DPI resolution (300 DPI)
                useCORS: true,
                allowTaint: true,
                backgroundColor: '#ffffff'
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = `Certificate_${'{{ $donation->certificate_id }}'}.png`;
                link.href = canvas.toDataURL('image/png', 1.0);
                link.click();
                btn.innerHTML = originalText;
            }).catch(err => {
                console.error('Download error:', err);
                btn.innerHTML = originalText;
                alert('Unable to generate PNG image. Please use Print / Save PDF.');
            });
        }
    </script>
</body>
</html>

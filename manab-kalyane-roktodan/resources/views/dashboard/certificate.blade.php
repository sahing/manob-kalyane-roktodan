<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Certificate of Honor — {{ $donation->user->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;900&family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-cinzel { font-family: 'Cinzel', serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; color: black !important; padding: 0 !important; }
            .cert-box { border-width: 6px !important; box-shadow: none !important; }
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-900 min-h-screen flex flex-col items-center justify-center p-4 sm:p-8">

    <!-- Top Toolbar -->
    <div class="no-print mb-6 flex items-center gap-4">
        <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-800 text-slate-300 hover:text-white border border-slate-700">
            ← Back to Dashboard
        </a>
        <button onclick="window.print()" class="px-6 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-extrabold text-xs shadow-xl transition flex items-center gap-2">
            🖨️ Print / Download Official Certificate (PDF)
        </button>
    </div>

    <!-- Official Certificate Frame -->
    <div class="cert-box bg-gradient-to-b from-amber-50 via-white to-amber-50/80 text-slate-900 border-8 border-double border-amber-700 max-w-4xl w-full p-8 sm:p-14 rounded-3xl shadow-2xl relative overflow-hidden">
        
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
            <div class="w-20 h-20 rounded-full bg-gradient-to-tr from-rose-700 to-rose-900 text-white mx-auto flex items-center justify-center text-4xl shadow-xl border-4 border-amber-400">
                🩸
            </div>

            <span class="inline-block text-xs uppercase tracking-[0.35em] font-extrabold text-amber-900 bg-amber-200/50 px-4 py-1 rounded-full border border-amber-300">
                Official Certificate of Appreciation
            </span>

            <h1 class="text-3xl sm:text-4xl font-extrabold font-cinzel text-rose-950 tracking-tight">
                PROUD VOLUNTARY BLOOD DONOR
            </h1>

            <p class="text-xs uppercase tracking-widest text-slate-600 font-bold pt-2">This is proudly presented to</p>
            
            <div class="text-3xl sm:text-4xl font-black text-slate-900 font-cinzel border-b-2 border-slate-300 pb-2 max-w-lg mx-auto text-rose-900">
                {{ $donation->user->name }}
            </div>

            <!-- Member Loyalty & Honor Badge Row -->
            <div class="flex items-center justify-center gap-3 pt-1">
                <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-amber-500/20 text-amber-900 border border-amber-500/40">
                    ⭐ Member Loyalty Points: {{ $donation->user->loyalty_points }} Pts
                </span>
                <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-rose-500/20 text-rose-900 border border-rose-500/40">
                    {{ $donation->user->loyalty_rank }}
                </span>
            </div>

            <p class="text-xs sm:text-sm text-slate-700 max-w-xl mx-auto leading-relaxed pt-2">
                In heartfelt recognition of your noble voluntary blood donation on <strong class="text-rose-950 font-extrabold">{{ $donation->donation_date ? $donation->donation_date->format('F d, Y') : 'Record' }}</strong> at {{ $donation->location ?? 'Bhagwangola Rural Hospital' }}. Your selfless contribution and dedication to inspiring other voluntary members has saved human lives.
            </p>

            <!-- Verification QR Code & Official Seals -->
            <div class="pt-6 grid grid-cols-3 gap-6 items-end max-w-2xl mx-auto text-xs">
                
                <!-- Left: President Signature -->
                <div class="text-center border-t-2 border-slate-400 pt-2">
                    <div class="font-bold text-slate-900 font-cinzel">President</div>
                    <div class="text-[10px] text-slate-600 font-semibold">Manab Kalyane Rokto Dan</div>
                </div>

                <!-- Center: Verification QR Code -->
                <div class="text-center flex flex-col items-center justify-center">
                    <div class="bg-white p-2 rounded-xl border border-slate-300 shadow-md">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data={{ urlencode($verificationUrl) }}" alt="Verification QR" class="w-16 h-16">
                    </div>
                    <span class="text-[9px] font-mono text-slate-500 mt-1">Scan to Verify Record</span>
                </div>

                <!-- Right: General Secretary -->
                <div class="text-center border-t-2 border-slate-400 pt-2">
                    <div class="font-bold text-slate-900 font-cinzel">General Secretary</div>
                    <div class="text-[10px] text-slate-600 font-semibold">Bhagwangola Society</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

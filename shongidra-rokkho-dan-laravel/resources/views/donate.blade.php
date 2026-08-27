@extends('layouts.app')

@section('title', 'Donate & Support Voluntary Blood Service — Manab Kalyane Rokto Dan')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('donateApp', () => ({
        amount: 500,
        upiId: "{{ $upiId }}",
        payeeName: "{{ $payeeName }}",
        copied: false,

        get upiUri() {
            return `upi://pay?pa=${encodeURIComponent(this.upiId)}&pn=${encodeURIComponent(this.payeeName)}&am=${this.amount || 0}&cu=INR`;
        },

        get qrImageUrl() {
            return `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(this.upiUri)}`;
        },

        setAmount(val) {
            this.amount = val;
            this.$nextTick(() => this.renderQrCanvas());
        },

        copyUpi() {
            navigator.clipboard.writeText(this.upiId);
            this.copied = true;
            setTimeout(() => this.copied = false, 2500);
        },

        renderQrCanvas() {
            const container = document.getElementById('qrcode-canvas-box');
            if (!container) return;
            container.innerHTML = '';
            
            if (typeof QRCode !== 'undefined') {
                try {
                    new QRCode(container, {
                        text: this.upiUri,
                        width: 220,
                        height: 220,
                        colorDark: "#0f172a",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.M
                    });
                    return;
                } catch(e) {}
            }
            
            // Fallback IMG if QRCode library fails
            const img = document.createElement('img');
            img.src = this.qrImageUrl;
            img.alt = 'UPI Payment QR Code';
            img.className = 'w-52 h-52 object-contain mx-auto rounded-xl';
            container.appendChild(img);
        },

        init() {
            this.$nextTick(() => {
                this.renderQrCanvas();
            });
        }
    }));
});
</script>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="donateApp">
    
    <!-- Hero Header -->
    <div class="text-center max-w-2xl mx-auto mb-10">
        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-wider bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/30 mb-3">
            <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse"></span>
            Voluntary Life-Saving Contribution
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">Support Our Emergency Blood Network</h1>
        <p class="text-sm text-slate-600 dark:text-slate-400 mt-2 leading-relaxed">Your voluntary donations fund blood donation camps, 24/7 emergency helplines, donor certificates, and emergency blood transport across Bhagwangola & Murshidabad.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Instant UPI Payment & Dynamic QR Code Box (5 Cols) -->
        <div class="lg:col-span-5 glass-card p-6 sm:p-8 rounded-3xl text-center shadow-2xl border border-slate-300 dark:border-slate-800 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-center gap-2 mb-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-500/20 text-emerald-800 dark:text-emerald-300 border border-emerald-500/30">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                        Live UPI Payment QR
                    </span>
                </div>

                <!-- Dynamic QR Code Container -->
                <div class="bg-white p-5 rounded-3xl inline-block shadow-2xl border-2 border-slate-200 dark:border-slate-700 mb-4 relative">
                    <div id="qrcode-canvas-box" class="w-52 h-52 mx-auto flex items-center justify-center">
                        <img :src="qrImageUrl" alt="UPI QR Code" class="w-52 h-52 object-contain mx-auto">
                    </div>
                    <div class="text-[11px] text-slate-700 font-bold mt-2 font-mono">
                        Scan with GPay, PhonePe, Paytm, BHIM
                    </div>
                </div>

                <!-- Live Amount Display -->
                <div class="mb-4 p-3 rounded-2xl bg-rose-500/10 dark:bg-rose-500/20 border border-rose-500/30 text-xs font-bold text-rose-700 dark:text-rose-300">
                    Contribution Amount: <span class="text-xl font-black text-rose-600 dark:text-rose-400" x-text="'₹' + (amount || 0)"></span>
                </div>

                <!-- UPI Details & 1-Click Copy -->
                <div class="p-4 rounded-2xl bg-slate-100 dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 space-y-2">
                    <div class="text-[10px] text-slate-500 dark:text-slate-400 uppercase tracking-widest font-extrabold">Official UPI ID</div>
                    <div class="flex items-center justify-center gap-2">
                        <span class="text-sm font-black text-slate-900 dark:text-white font-mono select-all" x-text="upiId"></span>
                        <button type="button" @click="copyUpi()" class="px-3 py-1 rounded-xl text-xs font-extrabold bg-rose-600 text-white hover:bg-rose-500 transition shadow">
                            <span x-text="copied ? '✓ Copied' : '📋 Copy'"></span>
                        </button>
                    </div>
                    <div class="text-xs text-slate-600 dark:text-slate-400">Account Name: <strong>{{ $payeeName }}</strong></div>
                </div>
            </div>

            <!-- Deep Link Action Button -->
            <div class="mt-6 pt-4 border-t border-slate-200 dark:border-slate-800 space-y-3">
                <a :href="upiUri" class="w-full py-3.5 px-4 rounded-2xl text-xs font-extrabold bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white shadow-xl flex items-center justify-center gap-2 transition transform hover:-translate-y-0.5">
                    <span>📱 Pay directly via GPay / PhonePe / Paytm</span>
                </a>
                <p class="text-[11px] text-slate-500 dark:text-slate-400">
                    Direct transfer to society bank account. Zero transaction fees.
                </p>
            </div>
        </div>

        <!-- Right: Contribution Pledge Form (7 Cols) -->
        <div class="lg:col-span-7 glass-card p-6 sm:p-8 rounded-3xl shadow-2xl border border-slate-300 dark:border-slate-800">
            <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white mb-1">Record Contribution Pledge</h3>
            <p class="text-xs text-slate-600 dark:text-slate-400 mb-6">After making your UPI payment, fill out this form so our finance team can verify and issue an official acknowledgment.</p>

            <form action="{{ route('donate.store') }}" method="POST" class="space-y-5">
                @csrf
                
                <!-- Quick Amount Selector -->
                <div>
                    <label class="block text-xs font-extrabold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wider">Select Contribution Amount (₹)</label>
                    <div class="grid grid-cols-4 gap-2 mb-3">
                        <button type="button" @click="setAmount(100)" :class="amount == 100 ? 'bg-rose-600 text-white font-extrabold shadow-md border-rose-500' : 'bg-slate-100 dark:bg-slate-900 text-slate-800 dark:text-slate-200 border-slate-300 dark:border-slate-800'" class="py-2.5 text-xs rounded-xl border font-bold transition">₹100</button>
                        <button type="button" @click="setAmount(250)" :class="amount == 250 ? 'bg-rose-600 text-white font-extrabold shadow-md border-rose-500' : 'bg-slate-100 dark:bg-slate-900 text-slate-800 dark:text-slate-200 border-slate-300 dark:border-slate-800'" class="py-2.5 text-xs rounded-xl border font-bold transition">₹250</button>
                        <button type="button" @click="setAmount(500)" :class="amount == 500 ? 'bg-rose-600 text-white font-extrabold shadow-md border-rose-500' : 'bg-slate-100 dark:bg-slate-900 text-slate-800 dark:text-slate-200 border-slate-300 dark:border-slate-800'" class="py-2.5 text-xs rounded-xl border font-bold transition">₹500</button>
                        <button type="button" @click="setAmount(1000)" :class="amount == 1000 ? 'bg-rose-600 text-white font-extrabold shadow-md border-rose-500' : 'bg-slate-100 dark:bg-slate-900 text-slate-800 dark:text-slate-200 border-slate-300 dark:border-slate-800'" class="py-2.5 text-xs rounded-xl border font-bold transition">₹1000</button>
                    </div>
                    <div class="relative">
                        <span class="absolute left-4 top-3 text-slate-500 dark:text-slate-400 font-extrabold text-sm">₹</span>
                        <input type="number" name="amount" x-model="amount" @input="renderQrCanvas()" required min="1" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl pl-8 pr-4 py-2.5 text-base text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-extrabold">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Contribution Type</label>
                        <select name="payment_type" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-bold">
                            <option value="one_time">One-Time Contribution</option>
                            <option value="weekly">Weekly Support</option>
                            <option value="monthly">Monthly Voluntary Subscription</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">UPI Transaction Ref ID</label>
                        <input type="text" name="transaction_id" placeholder="e.g. 423489123049" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Your Full Name</label>
                        <input type="text" name="donor_name" value="{{ Auth::check() ? Auth::user()->name : '' }}" required placeholder="e.g. Tariqul Islam" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Phone Number</label>
                        <input type="tel" name="phone" value="{{ Auth::check() ? Auth::user()->phone : '' }}" required placeholder="e.g. 9832100000" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Message / Notes (Optional)</label>
                    <textarea name="notes" rows="2" placeholder="Any message or instructions for the society..." class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl p-3 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-rose-500"></textarea>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-brand-600 via-rose-600 to-rose-700 text-white font-extrabold py-3.5 rounded-xl hover:opacity-95 shadow-xl glow-red transition text-sm">
                    Submit Contribution Record 🩸
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

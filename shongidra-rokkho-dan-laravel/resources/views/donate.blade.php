@extends('layouts.app')

@section('title', 'Donate & Support Voluntary Blood Service — Manab Kalyane Rokto Dan')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="donateApp()">
    <div class="text-center max-w-2xl mx-auto mb-10">
        <span class="inline-block px-3.5 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 mb-2">
            Voluntary Contribution
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">Support Our Life-Saving Mission</h1>
        <p class="text-sm text-slate-600 dark:text-slate-400 mt-2 leading-relaxed">Your voluntary contribution helps organize blood donation drives, manage 24/7 emergency helplines, and issue digital certificates to donors in Bhagwangola & Murshidabad.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- Interactive & Dynamic UPI QR Box -->
        <div class="glass-card p-6 sm:p-8 rounded-3xl text-center flex flex-col justify-between shadow-2xl border border-slate-300 dark:border-slate-800">
            <div>
                <div class="flex items-center justify-center gap-2 mb-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-500/20 text-emerald-800 dark:text-emerald-300 border border-emerald-500/30">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Official UPI Payment QR
                    </span>
                </div>

                <!-- QR Container with Canvas + Image Fallback -->
                <div class="bg-white p-5 rounded-2xl inline-block shadow-2xl border border-slate-200 dark:border-slate-700 mb-4 relative group">
                    <div id="qrcode-container" class="w-52 h-52 mx-auto flex items-center justify-center">
                        <img :src="qrImageUrl" alt="UPI QR Code" class="w-52 h-52 object-contain" x-on:error="qrFallback()">
                    </div>
                    <div class="text-[10px] text-slate-500 mt-2 font-mono">Scan with GPay, PhonePe, Paytm, BHIM</div>
                </div>

                <!-- UPI Details & Copy Button -->
                <div class="space-y-1">
                    <div class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider font-bold">UPI ID</div>
                    <div class="flex items-center justify-center gap-2">
                        <span class="text-base font-black text-rose-600 dark:text-rose-400 font-mono select-all" x-text="upiId"></span>
                        <button type="button" @click="copyUpi()" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-700 hover:bg-slate-300 dark:hover:bg-slate-700 transition">
                            <span x-text="copied ? '✓ Copied' : '📋 Copy'"></span>
                        </button>
                    </div>
                    <div class="text-xs text-slate-600 dark:text-slate-400">Payee: <strong>{{ $payeeName }}</strong></div>
                </div>

                <!-- Dynamic Amount Indicator -->
                <div class="mt-4 p-3 rounded-xl bg-slate-100 dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 text-xs font-medium text-slate-700 dark:text-slate-300">
                    Pre-filled Amount: <strong class="text-rose-600 dark:text-rose-400 font-extrabold text-sm" x-text="'₹' + (amount || 0)"></strong>
                </div>
            </div>

            <!-- Direct Mobile Deep Link Button -->
            <div class="mt-6 pt-4 border-t border-slate-200 dark:border-slate-800 space-y-3">
                <a :href="upiDeepLink" class="w-full py-3.5 px-4 rounded-xl text-xs font-extrabold bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white shadow-lg flex items-center justify-center gap-2 transition transform hover:-translate-y-0.5">
                    <span>📱 Open UPI App (GPay / PhonePe / Paytm)</span>
                </a>
                <p class="text-[11px] text-slate-500 dark:text-slate-400">
                    Direct bank transfer to Manab Kalyane Rokto Dan voluntary fund.
                </p>
            </div>
        </div>

        <!-- Pledge Form -->
        <div class="glass-card p-6 sm:p-8 rounded-3xl shadow-2xl border border-slate-300 dark:border-slate-800">
            <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-1">Record Contribution Pledge</h3>
            <p class="text-xs text-slate-600 dark:text-slate-400 mb-6">Enter your transaction details so our team can issue an official acknowledgment.</p>

            <form action="{{ route('donate.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Contribution Type</label>
                    <select name="payment_type" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-medium">
                        <option value="one_time">One-Time Contribution</option>
                        <option value="weekly">Weekly Support</option>
                        <option value="monthly">Monthly Voluntary Subscription</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Select Amount (₹)</label>
                    <div class="grid grid-cols-4 gap-2 mb-3">
                        <button type="button" @click="setAmount(100)" :class="amount == 100 ? 'bg-gradient-to-r from-brand-600 to-rose-600 text-white font-bold shadow-md' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-800'" class="py-2.5 text-xs rounded-xl border transition">₹100</button>
                        <button type="button" @click="setAmount(250)" :class="amount == 250 ? 'bg-gradient-to-r from-brand-600 to-rose-600 text-white font-bold shadow-md' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-800'" class="py-2.5 text-xs rounded-xl border transition">₹250</button>
                        <button type="button" @click="setAmount(500)" :class="amount == 500 ? 'bg-gradient-to-r from-brand-600 to-rose-600 text-white font-bold shadow-md' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-800'" class="py-2.5 text-xs rounded-xl border transition">₹500</button>
                        <button type="button" @click="setAmount(1000)" :class="amount == 1000 ? 'bg-gradient-to-r from-brand-600 to-rose-600 text-white font-bold shadow-md' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-800'" class="py-2.5 text-xs rounded-xl border transition">₹1000</button>
                    </div>
                    <input type="number" name="amount" x-model="amount" required min="1" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-bold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Your Full Name</label>
                    <input type="text" name="donor_name" value="{{ Auth::check() ? Auth::user()->name : '' }}" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Phone Number</label>
                    <input type="tel" name="phone" value="{{ Auth::check() ? Auth::user()->phone : '' }}" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">UPI Transaction Ref ID (Optional)</label>
                    <input type="text" name="transaction_id" placeholder="e.g. 423489123049" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-brand-600 via-rose-600 to-rose-700 text-white font-extrabold py-3.5 rounded-xl hover:opacity-95 shadow-xl glow-red transition">
                    Submit Contribution Record 🩸
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Pure JS QRCode Library & Alpine Controller -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
function donateApp() {
    return {
        amount: 500,
        upiId: "{{ $upiId }}",
        payeeName: "{{ $payeeName }}",
        copied: false,

        get upiUri() {
            return `upi://pay?pa=${encodeURIComponent(this.upiId)}&pn=${encodeURIComponent(this.payeeName)}&am=${this.amount || 0}&cu=INR`;
        },

        get upiDeepLink() {
            return this.upiUri;
        },

        get qrImageUrl() {
            return `https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=${encodeURIComponent(this.upiUri)}`;
        },

        setAmount(val) {
            this.amount = val;
            this.renderQrCanvas();
        },

        copyUpi() {
            navigator.clipboard.writeText(this.upiId);
            this.copied = true;
            setTimeout(() => this.copied = false, 2500);
        },

        qrFallback() {
            // Backup to Client-Side QR Generator if image fails to load
            this.renderQrCanvas();
        },

        renderQrCanvas() {
            const container = document.getElementById('qrcode-container');
            if (!container) return;
            
            // Try client-side QRCode.js if available
            if (typeof QRCode !== 'undefined') {
                container.innerHTML = '';
                new QRCode(container, {
                    text: this.upiUri,
                    width: 200,
                    height: 200,
                    colorDark : "#0f172a",
                    colorLight : "#ffffff",
                    correctLevel : QRCode.CorrectLevel.M
                });
            } else {
                // Secondary fallback URL
                container.innerHTML = `<img src="https://quickchart.io/qr?text=${encodeURIComponent(this.upiUri)}&size=220" alt="UPI QR" class="w-52 h-52 object-contain mx-auto">`;
            }
        },

        init() {
            setTimeout(() => {
                this.renderQrCanvas();
            }, 300);
        }
    }
}
</script>
@endsection

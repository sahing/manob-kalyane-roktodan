@extends('layouts.app')

@section('title', 'Admin Command Center — Manab Kalyane Rokto Dan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="adminDashboard()">
    
    <!-- Admin Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 pb-6 border-b border-slate-200 dark:border-slate-800 gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-700 dark:text-amber-300 border border-amber-500/40 mb-2">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                Admin Control Room
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">System Control & Operations</h1>
            <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Manage donor inquiries, financial donors, blood donation reminders, user credentials, and SEO settings.</p>
        </div>

        <!-- Quick Stats Overview Bar -->
        <div class="flex flex-wrap items-center gap-3">
            <div class="px-4 py-2 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-center">
                <span class="text-xs text-slate-500 dark:text-slate-400 block font-semibold">Financial Funds Raised</span>
                <span class="text-lg font-extrabold text-emerald-600 dark:text-emerald-400">₹{{ number_format($totalFinancialRaised) }}</span>
            </div>
            <div class="px-4 py-2 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-center">
                <span class="text-xs text-slate-500 dark:text-slate-400 block font-semibold">Live Inquiries</span>
                <span class="text-lg font-extrabold text-rose-600 dark:text-rose-400" x-text="inquiriesCount"></span>
            </div>
            <div class="px-4 py-2 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-center">
                <span class="text-xs text-slate-500 dark:text-slate-400 block font-semibold">Total Pageviews</span>
                <span class="text-lg font-extrabold text-indigo-600 dark:text-indigo-400">{{ number_format($stats['total_page_views']) }}</span>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex items-center space-x-2 overflow-x-auto pb-4 mb-8 text-xs font-bold border-b border-slate-200 dark:border-slate-800">
        <button @click="tab = 'inquiries'" :class="tab === 'inquiries' ? 'bg-rose-600 text-white shadow-lg glow-red' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800'" class="px-4 py-2.5 rounded-xl transition whitespace-nowrap flex items-center gap-2">
            <span>🚨 Real-Time Inquiries</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] bg-white/20 text-white" x-text="inquiriesCount"></span>
        </button>

        <button @click="tab = 'pledges'" :class="tab === 'pledges' ? 'bg-rose-600 text-white shadow-lg glow-red' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800'" class="px-4 py-2.5 rounded-xl transition whitespace-nowrap flex items-center gap-2">
            <span>💰 Financial Donors & Pledges</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] bg-emerald-500 text-white">{{ count($financialPledges) }}</span>
        </button>

        <button @click="tab = 'users'" :class="tab === 'users' ? 'bg-rose-600 text-white shadow-lg glow-red' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800'" class="px-4 py-2.5 rounded-xl transition whitespace-nowrap flex items-center gap-2">
            <span>👥 Registered Users & Blood Reminders</span>
        </button>

        <button @click="tab = 'blog'" :class="tab === 'blog' ? 'bg-rose-600 text-white shadow-lg glow-red' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800'" class="px-4 py-2.5 rounded-xl transition whitespace-nowrap">
            📝 Blog & Articles
        </button>

        <button @click="tab = 'seo'" :class="tab === 'seo' ? 'bg-rose-600 text-white shadow-lg glow-red' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800'" class="px-4 py-2.5 rounded-xl transition whitespace-nowrap">
            🔍 SEO Metadata
        </button>

        <button @click="tab = 'analytics'" :class="tab === 'analytics' ? 'bg-rose-600 text-white shadow-lg glow-red' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800'" class="px-4 py-2.5 rounded-xl transition whitespace-nowrap">
            📊 Page Analytics
        </button>

        <button @click="tab = 'donations'" :class="tab === 'donations' ? 'bg-rose-600 text-white shadow-lg glow-red' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800'" class="px-4 py-2.5 rounded-xl transition whitespace-nowrap">
            🩸 Record Blood Donation
        </button>

        <button @click="tab = 'settings'" :class="tab === 'settings' ? 'bg-rose-600 text-white shadow-lg glow-red' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800'" class="px-4 py-2.5 rounded-xl transition whitespace-nowrap">
            ⚙️ Site Settings
        </button>

        <button @click="tab = 'roles'" :class="tab === 'roles' ? 'bg-rose-600 text-white shadow-lg glow-red' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800'" class="px-4 py-2.5 rounded-xl transition whitespace-nowrap flex items-center gap-2">
            <span>🛡️ Roles & Permissions</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] bg-amber-500 text-white font-extrabold">{{ count($roles) }}</span>
        </button>

        <!-- CMS CUSTOMIZATION SUITE TABS -->
        <button @click="tab = 'branding'" :class="tab === 'branding' ? 'bg-rose-600 text-white shadow-lg glow-red' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800'" class="px-4 py-2.5 rounded-xl transition whitespace-nowrap">
            🎨 Logo & Branding
        </button>

        <button @click="tab = 'menu'" :class="tab === 'menu' ? 'bg-rose-600 text-white shadow-lg glow-red' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800'" class="px-4 py-2.5 rounded-xl transition whitespace-nowrap">
            📌 Menu Manager
        </button>

        <button @click="tab = 'homepage'" :class="tab === 'homepage' ? 'bg-rose-600 text-white shadow-lg glow-red' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800'" class="px-4 py-2.5 rounded-xl transition whitespace-nowrap">
            🧩 Homepage Builder
        </button>

        <button @click="tab = 'pages'" :class="tab === 'pages' ? 'bg-rose-600 text-white shadow-lg glow-red' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800'" class="px-4 py-2.5 rounded-xl transition whitespace-nowrap">
            📄 Dynamic Pages
        </button>

        <button @click="tab = 'media'" :class="tab === 'media' ? 'bg-rose-600 text-white shadow-lg glow-red' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800'" class="px-4 py-2.5 rounded-xl transition whitespace-nowrap">
            📁 Media Library
        </button>

        <button @click="tab = 'customcode'" :class="tab === 'customcode' ? 'bg-rose-600 text-white shadow-lg glow-red' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800'" class="px-4 py-2.5 rounded-xl transition whitespace-nowrap">
            💻 Custom CSS
        </button>
    </div>

    <!-- TAB 1: Real-Time Visitor Inquiries -->
    <div x-show="tab === 'inquiries'" x-cloak class="space-y-6">
        <div class="glass-card rounded-3xl overflow-hidden shadow-xl border border-slate-200 dark:border-slate-800">
            <div class="p-4 sm:p-6 bg-slate-100 dark:bg-slate-900/90 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-extrabold text-slate-900 dark:text-white">Real-Time Contact & Donor Request Inquiries</h3>
                    <p class="text-xs text-slate-500">Live feeds update automatically every 4 seconds.</p>
                </div>
                <button @click="fetchInquiries()" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-rose-500/20 text-rose-600 dark:text-rose-400 hover:bg-rose-500/30 transition">
                    🔄 Refresh Now
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-100 dark:bg-slate-900/90 text-slate-700 dark:text-slate-300 uppercase tracking-wider font-bold">
                        <tr>
                            <th class="p-3.5">Time Logged</th>
                            <th class="p-3.5">Visitor Name</th>
                            <th class="p-3.5">Phone Number</th>
                            <th class="p-3.5">Purpose / Request Details</th>
                            <th class="p-3.5">IP Address</th>
                            <th class="p-3.5">Session Reference</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800 font-medium">
                        <template x-for="inq in inquiries" :key="inq.id">
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition">
                                <td class="p-3.5 whitespace-nowrap text-slate-500 dark:text-slate-400">
                                    <div class="font-bold text-slate-900 dark:text-slate-200" x-text="inq.time_ago"></div>
                                    <div class="text-[10px]" x-text="inq.logged_at"></div>
                                </td>
                                <td class="p-3.5 font-bold text-slate-900 dark:text-white" x-text="inq.name"></td>
                                <td class="p-3.5">
                                    <a :href="'tel:' + inq.phone" class="text-rose-600 dark:text-rose-400 font-bold hover:underline" x-text="inq.phone"></a>
                                </td>
                                <td class="p-3.5 text-slate-700 dark:text-slate-300 max-w-xs truncate" x-text="inq.purpose || 'Contact Donor Request'"></td>
                                <td class="p-3.5 font-mono text-[11px] text-slate-600 dark:text-slate-400" x-text="inq.ip_address"></td>
                                <td class="p-3.5 font-mono text-[10px] text-slate-500" x-text="inq.session_id"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 2: FINANCIAL DONORS & PLEDGES MANAGEMENT -->
    <div x-show="tab === 'pledges'" x-cloak class="space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="glass-card p-6 rounded-3xl text-center border border-emerald-500/20">
                <span class="text-xs uppercase font-bold text-slate-500 block mb-1">Total Verified Financial Donations</span>
                <span class="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400">₹{{ number_format($totalFinancialRaised) }}</span>
            </div>
            <div class="glass-card p-6 rounded-3xl text-center border border-slate-200 dark:border-slate-800">
                <span class="text-xs uppercase font-bold text-slate-500 block mb-1">Total Financial Pledges Recorded</span>
                <span class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ count($financialPledges) }}</span>
            </div>
            <div class="glass-card p-6 rounded-3xl text-center border border-amber-500/20">
                <span class="text-xs uppercase font-bold text-slate-500 block mb-1">Pending Verification Pledges</span>
                <span class="text-3xl font-extrabold text-amber-500">{{ $financialPledges->where('status', 'pending')->count() }}</span>
            </div>
        </div>

        <!-- Add Manual Financial Contribution Form -->
        <div class="glass-card p-6 sm:p-8 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-800">
            <h3 class="text-lg font-extrabold text-slate-900 dark:text-white mb-2">Record Manual Financial Donation</h3>
            <p class="text-xs text-slate-500 mb-4">Log cash, direct UPI, or bank transfer contributions directly into society funds.</p>

            <form action="{{ route('admin.pledges.record') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Donor Name *</label>
                        <input type="text" name="donor_name" required placeholder="e.g. Subhashish Roy" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2 text-xs text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Phone Number *</label>
                        <input type="tel" name="phone" required placeholder="e.g. 9876543210" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2 text-xs text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Amount (₹) *</label>
                        <input type="number" name="amount" min="1" required placeholder="500" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2 text-xs text-slate-900 dark:text-white font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Frequency *</label>
                        <select name="payment_type" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2 text-xs text-slate-900 dark:text-white">
                            <option value="one_time">One-Time Support</option>
                            <option value="monthly">Monthly Recurring</option>
                            <option value="weekly">Weekly Support</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Transaction Ref / UTR / Cash</label>
                        <input type="text" name="transaction_id" placeholder="e.g. UPI/629102849102 or Cash Handover" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2 text-xs text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Donor Notes / Purpose</label>
                        <input type="text" name="notes" placeholder="e.g. Emergency oxygen support fund" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2 text-xs text-slate-900 dark:text-white">
                    </div>
                </div>

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold py-2.5 rounded-xl shadow-md transition">
                    Record Financial Donation 💰
                </button>
            </form>
        </div>

        <!-- Financial Donors List Table -->
        <div class="glass-card rounded-3xl overflow-hidden shadow-xl border border-slate-200 dark:border-slate-800">
            <div class="p-4 bg-slate-100 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 font-bold text-sm text-slate-900 dark:text-white">
                Financial Donors & Contribution Pledges
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-100 dark:bg-slate-900/90 text-slate-700 dark:text-slate-300 uppercase tracking-wider font-bold">
                        <tr>
                            <th class="p-3.5">Date & Time</th>
                            <th class="p-3.5">Financial Donor</th>
                            <th class="p-3.5">Amount & Frequency</th>
                            <th class="p-3.5">Transaction Ref</th>
                            <th class="p-3.5">Status</th>
                            <th class="p-3.5 text-right">Actions & Reminders</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800 font-medium">
                        @foreach($financialPledges as $pledge)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition">
                                <td class="p-3.5 whitespace-nowrap text-slate-500">
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $pledge->created_at->format('d M Y') }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $pledge->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="p-3.5 font-bold text-slate-900 dark:text-white">
                                    <div>{{ $pledge->donor_name }}</div>
                                    <a href="tel:{{ $pledge->phone }}" class="text-rose-600 dark:text-rose-400 text-[11px] hover:underline">{{ $pledge->phone }}</a>
                                </td>
                                <td class="p-3.5">
                                    <span class="text-base font-extrabold text-emerald-600 dark:text-emerald-400">₹{{ number_format($pledge->amount) }}</span>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase ml-1 {{ $pledge->payment_type === 'monthly' ? 'bg-indigo-500/20 text-indigo-400' : 'bg-slate-200 dark:bg-slate-800 text-slate-400' }}">
                                        {{ $pledge->payment_type }}
                                    </span>
                                </td>
                                <td class="p-3.5 font-mono text-slate-700 dark:text-slate-300">
                                    {{ $pledge->transaction_id ?: 'N/A' }}
                                </td>
                                <td class="p-3.5">
                                    @if($pledge->status === 'verified')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-emerald-500/20 text-emerald-600 dark:text-emerald-300 border border-emerald-500/30">
                                            ✓ Verified & Received
                                        </span>
                                    @elseif($pledge->status === 'rejected')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-rose-500/20 text-rose-600 dark:text-rose-300">
                                            Cancelled
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-amber-500/20 text-amber-600 border border-amber-500/30 animate-pulse">
                                            Pending Verification
                                        </span>
                                    @endif
                                </td>
                                <td class="p-3.5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Send WhatsApp Financial Reminder Button -->
                                        @php
                                            $pledgeReminderText = rawurlencode("Hello " . $pledge->donor_name . ", thank you for supporting Manab Kalyane Rokto Dan Emergency Blood Network. This is a gentle reminder regarding your " . $pledge->payment_type . " contribution pledge of ₹" . number_format($pledge->amount) . ". Your generosity helps save lives! Direct UPI: " . ($siteContent['upi_id'] ?? 'manobkalyan@upi'));
                                        @endphp
                                        <a href="https://wa.me/91{{ $pledge->phone }}?text={{ $pledgeReminderText }}" target="_blank" class="px-2.5 py-1 rounded-xl text-xs font-bold bg-slate-200 dark:bg-slate-800 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-600 hover:text-white transition flex items-center gap-1" title="Send WhatsApp Financial Reminder">
                                            <span>💌 Send Reminder</span>
                                        </a>

                                        @if($pledge->status === 'pending')
                                            <form action="{{ route('admin.pledges.status', $pledge->id) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="status" value="verified">
                                                <button type="submit" class="px-2.5 py-1 rounded-xl text-xs font-extrabold bg-emerald-600 text-white hover:bg-emerald-500 shadow">
                                                    Verify
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 3: Registered Users & Blood Reminders -->
    <div x-show="tab === 'users'" x-cloak class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Registered Users & Voluntary Blood Donors</h3>
                <p class="text-xs text-slate-500">Edit donor profiles, reset user passwords, send bulk in-app notifications, and trigger WhatsApp reminders.</p>
            </div>
        </div>

        <!-- Broadcast Bulk In-App Notification & Reminder Form -->
        <div class="glass-card p-6 sm:p-8 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-800 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                        <span>📢 Broadcast Bulk Notification & Donor Reminder</span>
                    </h4>
                    <p class="text-xs text-slate-500">Send in-app notifications directly to user profiles & dashboards.</p>
                </div>
            </div>

            <form action="{{ route('admin.reminders.bulk') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Target Recipient Donors *</label>
                        <select name="target" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2 text-xs text-slate-900 dark:text-white font-bold">
                            <option value="all">All Registered Users / Donors</option>
                            <option value="eligible_only">Eligible Donors Only (90+ Days)</option>
                            <optgroup label="Filter by Blood Group">
                                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                    <option value="{{ $bg }}">Target {{ $bg }} Donors Only</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Notification Type *</label>
                        <select name="type" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2 text-xs text-slate-900 dark:text-white font-bold">
                            <option value="blood_reminder">🩸 Voluntary Blood Donation Reminder</option>
                            <option value="financial_reminder">💰 Financial Contribution Call</option>
                            <option value="announcement">📢 General Society Announcement</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Target Action Route (Optional)</label>
                        <input type="text" name="action_url" placeholder="e.g. /search or /donate" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2 text-xs text-slate-900 dark:text-white">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Notification Headline / Title *</label>
                    <input type="text" name="title" required placeholder="e.g. 🚨 Urgent Blood Call: O+ Blood Needed in Bhagwangola" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2 text-xs text-slate-900 dark:text-white font-bold">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Notification Message *</label>
                    <textarea name="message" rows="2" required placeholder="Write the reminder message visible on the donor's dashboard..." class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2 text-xs text-slate-900 dark:text-white"></textarea>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-brand-600 to-rose-600 hover:opacity-95 text-white font-extrabold py-3 rounded-xl shadow-lg transition glow-red flex items-center justify-center gap-2">
                    <span>🚀 Broadcast Bulk In-App Notification</span>
                </button>
            </form>
        </div>

        <div class="glass-card rounded-3xl overflow-hidden shadow-xl border border-slate-200 dark:border-slate-800">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 uppercase font-bold">
                        <tr>
                            <th class="p-3.5">User / Donor</th>
                            <th class="p-3.5">Contact Phone & Email</th>
                            <th class="p-3.5">Blood & Location</th>
                            <th class="p-3.5">Eligibility Status</th>
                            <th class="p-3.5">Loyalty</th>
                            <th class="p-3.5 text-right">Actions & Reminders</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                        @foreach($users as $u)
                            @php
                                $profile = $u->donorProfile;
                                $isEligible = $profile?->is_eligible ?? true;
                                $bloodReminderMsg = rawurlencode("Hello " . $u->name . ", you are currently eligible to donate voluntary blood (" . ($profile->blood_group ?? 'O+') . ")! Patient emergency blood requests need support in Bhagwangola. Please consider donating again. - Manab Kalyane Rokto Dan");
                            @endphp
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition">
                                <td class="p-3.5 font-bold text-slate-900 dark:text-white">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-8 h-8 rounded-lg bg-rose-600 text-white font-extrabold flex items-center justify-center text-xs">
                                            {{ $profile->blood_group ?? substr($u->name, 0, 1) }}
                                        </div>
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-extrabold text-sm">{{ $u->name }}</span>
                                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase {{ $u->role === 'admin' ? 'bg-amber-500/20 text-amber-500 border border-amber-500/30' : 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300' }}">
                                                    {{ $u->getRoleObject()->label ?? $u->role }}
                                                </span>
                                            </div>
                                            <div class="text-[10px] text-slate-500 font-mono">{{ $profile->donor_code ?? 'MKRD-' . str_pad($u->id, 5, '0', STR_PAD_LEFT) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-3.5 text-slate-700 dark:text-slate-300">
                                    <div class="font-bold text-rose-600 dark:text-rose-400">{{ $u->phone }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $u->email ?: 'No email registered' }}</div>
                                </td>
                                <td class="p-3.5">
                                    <span class="px-2 py-0.5 rounded bg-rose-500/20 text-rose-600 dark:text-rose-400 font-extrabold">{{ $profile->blood_group ?? 'N/A' }}</span>
                                    <span class="text-[11px] text-slate-500 block mt-0.5">📍 {{ $profile->block ?? 'Bhagwangola' }}</span>
                                </td>
                                <td class="p-3.5">
                                    @if($isEligible)
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold text-emerald-600 dark:text-emerald-400 bg-emerald-500/20 border border-emerald-500/30 flex items-center gap-1 w-max">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Eligible to Donate
                                        </span>
                                    @else
                                        <span class="text-[11px] font-bold text-amber-500">Wait until {{ $profile->next_eligible_date?->format('d M Y') }}</span>
                                    @endif
                                </td>
                                <td class="p-3.5 font-bold text-amber-500">
                                    {{ $u->loyalty_points }} pts
                                </td>
                                <td class="p-3.5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Send WhatsApp Blood Donation Reminder Button -->
                                        @if($isEligible)
                                            <a href="https://wa.me/91{{ $u->phone }}?text={{ $bloodReminderMsg }}" target="_blank" class="px-2.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold text-xs shadow flex items-center gap-1" title="Send WhatsApp Blood Donation Reminder">
                                                <span>📲 Remind Blood</span>
                                            </a>
                                        @endif

                                        <button @click="openEditUserModal({{ json_encode([
                                            'id' => $u->id,
                                            'name' => $u->name,
                                            'email' => $u->email,
                                            'phone' => $u->phone,
                                            'role' => $u->role,
                                            'loyalty_points' => $u->loyalty_points,
                                            'blood_group' => $profile->blood_group ?? 'O+',
                                            'availability_status' => $profile->availability_status ?? 'available',
                                            'allow_direct_contact' => $profile->allow_direct_contact ?? true,
                                            'donor_type' => $profile->donor_type ?? 'regular',
                                            'village' => $profile->village ?? '',
                                            'block' => $profile->block ?? 'Bhagwangola-I',
                                            'district' => $profile->district ?? 'Murshidabad',
                                            'last_donation_date' => $profile?->last_donation_date?->format('Y-m-d') ?? ''
                                        ]) }})" class="px-2.5 py-1.5 rounded-xl bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-700 font-bold text-xs transition">
                                            ✏️ Edit
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 4: Blog & Articles Management -->
    <div x-show="tab === 'blog'" x-cloak class="space-y-8">
        <div class="glass-card p-6 sm:p-8 rounded-3xl shadow-xl">
            <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-2">Publish New Blog Article with SEO</h3>
            <form action="{{ route('admin.blog.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Article Title</label>
                        <input type="text" name="title" required placeholder="e.g. Voluntary Blood Donation Guidelines in Bhagwangola" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Category</label>
                        <select name="category" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                            <option value="Community Awareness">Community Awareness</option>
                            <option value="Health & Eligibility">Health & Eligibility</option>
                            <option value="Medical Guidelines">Medical Guidelines</option>
                            <option value="Donor Stories">Donor Stories</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Cover Image URL</label>
                        <input type="url" name="cover_image" placeholder="https://images.unsplash.com/photo-..." class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Author Name</label>
                        <input type="text" name="author_name" value="Manab Kalyane Rokto Dan" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Article Excerpt</label>
                    <input type="text" name="excerpt" placeholder="Short description for preview cards..." class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Article Content (HTML / Markdown)</label>
                    <textarea name="content" rows="6" required placeholder="Write article content here..." class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500"></textarea>
                </div>

                <button type="submit" class="w-full bg-rose-600 hover:bg-rose-500 text-white font-extrabold py-3 rounded-xl shadow-lg transition">
                    Publish Article Now 📰
                </button>
            </form>
        </div>
    </div>

    <!-- TAB 5: SEO Metadata Control -->
    <div x-show="tab === 'seo'" x-cloak class="space-y-8">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Manage Page SEO Metadata</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($seoSettings as $seo)
                <div class="glass-card p-6 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-800 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase bg-rose-500/20 text-rose-600 dark:text-rose-400">
                            Page: {{ $seo->page_name }}
                        </span>
                        <span class="text-[10px] text-slate-500 font-mono">{{ $seo->canonical_url }}</span>
                    </div>

                    <form action="{{ route('admin.seo.update', $seo->id) }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-[11px] font-bold uppercase text-slate-600 dark:text-slate-400 mb-1">Meta Title</label>
                            <input type="text" name="meta_title" value="{{ $seo->meta_title }}" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold uppercase text-slate-600 dark:text-slate-400 mb-1">Meta Description</label>
                            <textarea name="meta_description" rows="2" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">{{ $seo->meta_description }}</textarea>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold uppercase text-slate-600 dark:text-slate-400 mb-1">Meta Keywords</label>
                            <input type="text" name="meta_keywords" value="{{ $seo->meta_keywords }}" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                        </div>

                        <button type="submit" class="w-full py-2 rounded-xl text-xs font-bold bg-slate-900 dark:bg-slate-800 text-white hover:bg-rose-600 transition">
                            Save SEO Changes
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

    <!-- TAB 6: Analytics & Visitor Tracking -->
    <div x-show="tab === 'analytics'" x-cloak class="space-y-8">
        <!-- Overview Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="glass-card p-6 rounded-3xl text-center border border-indigo-500/20">
                <div class="text-xs uppercase font-bold text-slate-500 mb-1">Total Page Views</div>
                <div class="text-3xl font-extrabold text-indigo-600 dark:text-indigo-400">{{ number_format($analytics['total_views']) }}</div>
            </div>
            <div class="glass-card p-6 rounded-3xl text-center border border-emerald-500/20">
                <div class="text-xs uppercase font-bold text-slate-500 mb-1">Unique Visitors (Cookies)</div>
                <div class="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ number_format($analytics['unique_tracking_ids']) }}</div>
                <div class="text-[10px] text-slate-400 mt-1">IP Addresses: {{ number_format($analytics['unique_visitors']) }}</div>
            </div>
            <div class="glass-card p-6 rounded-3xl text-center border border-rose-500/20">
                <div class="text-xs uppercase font-bold text-slate-500 mb-1">Donor Contact Clicks</div>
                <div class="text-3xl font-extrabold text-rose-600 dark:text-rose-400">{{ number_format($analytics['donor_contact_clicks']) }}</div>
                <div class="text-[10px] text-slate-400 mt-1">Calls, WhatsApp & Inquiries</div>
            </div>
            <div class="glass-card p-6 rounded-3xl text-center border border-amber-500/20">
                <div class="text-xs uppercase font-bold text-slate-500 mb-1">Today's Total Traffic</div>
                <div class="text-3xl font-extrabold text-amber-500">{{ number_format($analytics['today_views']) }}</div>
            </div>
        </div>

        <!-- 1. Donor Contact Clicks & Contacted Donors Audit Log -->
        <div class="glass-card rounded-3xl overflow-hidden shadow-xl border border-rose-500/30">
            <div class="p-4 bg-gradient-to-r from-rose-950/40 via-slate-900 to-slate-950 border-b border-rose-500/30 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-extrabold text-white flex items-center gap-2">
                        <span>🩸</span> Donor Contact Audit Log (Who Contacted Whom for Blood)
                    </h3>
                    <p class="text-[11px] text-rose-300/80">Logs phone calls, WhatsApp inquiries, and society requests initiated by guests & registered users.</p>
                </div>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-500/20 text-rose-300 border border-rose-500/40">
                    {{ count($analytics['donor_contacts']) }} Inquiries Logged
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 uppercase font-bold">
                        <tr>
                            <th class="p-3.5">Time Logged</th>
                            <th class="p-3.5">Visitor / User Name</th>
                            <th class="p-3.5">Tracking ID</th>
                            <th class="p-3.5">Interaction Type</th>
                            <th class="p-3.5">Target Contacted Details</th>
                            <th class="p-3.5">IP & Device</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800 font-medium">
                        @forelse($analytics['donor_contacts'] as $contact)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition">
                                <td class="p-3.5 whitespace-nowrap text-slate-500">
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $contact->created_at->format('d M Y') }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $contact->created_at->format('h:i:s A') }} ({{ $contact->created_at->diffForHumans() }})</div>
                                </td>
                                <td class="p-3.5 font-bold text-slate-900 dark:text-white">
                                    {{ $contact->user_name ?: 'Guest Visitor' }}
                                </td>
                                <td class="p-3.5 font-mono text-[11px]">
                                    <span class="px-2 py-0.5 rounded bg-slate-200 dark:bg-slate-800 font-bold text-rose-600 dark:text-rose-400">
                                        {{ $contact->tracking_id ?: 'VT-ANONYMOUS' }}
                                    </span>
                                </td>
                                <td class="p-3.5">
                                    @if($contact->action_type === 'contact_donor_phone_call')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30">
                                            📞 Phone Call
                                        </span>
                                    @elseif($contact->action_type === 'contact_donor_whatsapp')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                            💬 WhatsApp
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-amber-500/20 text-amber-500">
                                            🛡️ Society Helpline
                                        </span>
                                    @endif
                                </td>
                                <td class="p-3.5 font-bold text-rose-600 dark:text-rose-300">
                                    {{ $contact->target_details }}
                                </td>
                                <td class="p-3.5 text-slate-500 text-[11px]">
                                    <div class="font-mono">{{ $contact->ip_address }}</div>
                                    <div class="text-[10px] uppercase font-bold text-slate-400">{{ $contact->device_type }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-6 text-center text-slate-500 text-xs">
                                    No donor contact clicks logged yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. Detailed Page Analytics & Visitor Audit Log -->
        <div class="glass-card rounded-3xl overflow-hidden shadow-xl border border-slate-200 dark:border-slate-800">
            <div class="p-4 bg-slate-100 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">Real-Time Visitor Journey Audit Log (50 Recent Pageviews & Actions)</h3>
                    <p class="text-xs text-slate-500">Tracks registered users and non-registered guests with unique tracking IDs.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 uppercase font-bold">
                        <tr>
                            <th class="p-3.5">Time Logged</th>
                            <th class="p-3.5">Tracking ID</th>
                            <th class="p-3.5">Visitor Profile</th>
                            <th class="p-3.5">Page Path</th>
                            <th class="p-3.5">Action & Target Details</th>
                            <th class="p-3.5">IP & Device</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800 font-medium">
                        @foreach($analytics['recent_traffic'] as $traffic)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition">
                                <td class="p-3.5 whitespace-nowrap text-slate-500">
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $traffic->created_at->format('d M Y') }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $traffic->created_at->format('h:i:s A') }}</div>
                                </td>
                                <td class="p-3.5 font-mono text-[11px]">
                                    <span class="px-2 py-0.5 rounded bg-slate-200 dark:bg-slate-800 font-bold text-indigo-600 dark:text-indigo-400">
                                        {{ $traffic->tracking_id ?: 'VT-GUEST' }}
                                    </span>
                                </td>
                                <td class="p-3.5 font-bold text-slate-900 dark:text-white">
                                    <div>{{ $traffic->user_name ?: 'Guest Visitor' }}</div>
                                    @if($traffic->user_id)
                                        <span class="text-[10px] text-emerald-500 font-bold">Registered User #{{ $traffic->user_id }}</span>
                                    @else
                                        <span class="text-[10px] text-slate-400 font-bold">Non-Registered Visitor</span>
                                    @endif
                                </td>
                                <td class="p-3.5 font-mono text-rose-600 dark:text-rose-400 font-bold">
                                    {{ $traffic->path }}
                                </td>
                                <td class="p-3.5 text-slate-700 dark:text-slate-300">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $traffic->action_type === 'page_view' ? 'bg-slate-200 dark:bg-slate-800 text-slate-500' : 'bg-rose-500/20 text-rose-400' }}">
                                        {{ $traffic->action_type }}
                                    </span>
                                    <div class="text-[11px] text-slate-500 mt-0.5 truncate max-w-xs">{{ $traffic->target_details }}</div>
                                </td>
                                <td class="p-3.5 text-slate-500 text-[11px]">
                                    <div class="font-mono">{{ $traffic->ip_address }}</div>
                                    <div class="text-[10px] uppercase font-bold text-slate-400">{{ $traffic->device_type }}</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 7: Record Blood Donation -->
    <div x-show="tab === 'donations'" x-cloak class="space-y-6">
        <div class="glass-card p-6 sm:p-8 rounded-3xl shadow-xl max-w-xl mx-auto">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Record Official Blood Donation</h3>
            <form action="{{ route('admin.donations.record') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Select Donor User</label>
                    <select name="user_id" required class="searchable-select w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white" data-searchable="true">
                        <option value="">Search Donor by Name or Phone...</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->phone }}) — {{ $u->donorProfile->blood_group ?? 'N/A' }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Donation Date</label>
                    <input type="date" name="donation_date" value="{{ date('Y-m-d') }}" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Hospital / Camp Location</label>
                    <input type="text" name="location" value="Bhagwangola Rural Hospital" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white">
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-brand-600 to-rose-600 text-white font-extrabold py-3 rounded-xl shadow-lg hover:opacity-95 transition glow-red">
                    Record Donation & Award 100 Points 🏅
                </button>
            </form>
        </div>
    </div>

    <!-- TAB 8: Site Settings -->
    <div x-show="tab === 'settings'" x-cloak class="space-y-6">
        <div class="glass-card p-6 sm:p-8 rounded-3xl shadow-xl max-w-xl mx-auto">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Global Site Settings & Contact Info</h3>
            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Organization Name</label>
                    <input type="text" name="organization_name" value="{{ $siteContent['organization_name'] }}" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Helpline Phone Number</label>
                    <input type="text" name="helpline_phone" value="{{ $siteContent['helpline_phone'] }}" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Official UPI ID</label>
                    <input type="text" name="upi_id" value="{{ $siteContent['upi_id'] }}" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2 text-xs">
                </div>
                <button type="submit" class="w-full py-3 rounded-xl font-bold bg-rose-600 text-white text-xs">Save Settings</button>
            </form>
        </div>
    </div>

    <!-- TAB 9: Roles & Permissions Control (RBAC) -->
    <div x-show="tab === 'roles'" x-cloak class="space-y-8" x-data="{ editingRole: { id: null, name: '', label: '', description: '', permissions: [] } }">
        
        <!-- Create / Edit Role Form Card -->
        <div class="glass-card p-6 sm:p-8 rounded-3xl shadow-xl border border-amber-500/30">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                        <span>🛡️</span> Role & Permission Configurator
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Create custom roles or update granular permissions for delegating administrative control.</p>
                </div>
                <button x-show="editingRole.id" @click="editingRole = { id: null, name: '', label: '', description: '', permissions: [] }" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-300">
                    + Create New Role
                </button>
            </div>

            <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="id" :value="editingRole.id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Role Title / Display Label *</label>
                        <input type="text" name="label" x-model="editingRole.label" required placeholder="e.g. Finance & Pledge Manager" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Role Slug / System Name *</label>
                        <input type="text" name="name" x-model="editingRole.name" required placeholder="e.g. finance_manager" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm font-mono text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Description</label>
                    <input type="text" name="description" x-model="editingRole.description" placeholder="Brief summary of duties and privileges assigned to this role..." class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>

                <!-- Checkboxes Grid for Permissions -->
                <div>
                    <label class="block text-xs font-extrabold uppercase text-slate-700 dark:text-slate-300 mb-2">
                        Granted Administrative Permissions Checkboxes
                    </label>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 p-4 rounded-2xl bg-slate-100 dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800">
                        @foreach($availablePermissions as $key => $title)
                            <label class="flex items-start space-x-3 p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950/60 cursor-pointer hover:border-rose-500/50 transition">
                                <input type="checkbox" name="permissions[]" value="{{ $key }}" :checked="editingRole.permissions && editingRole.permissions.includes('{{ $key }}')" class="mt-0.5 rounded border-slate-300 text-rose-600 focus:ring-rose-500 w-4 h-4">
                                <div>
                                    <span class="text-xs font-bold text-slate-900 dark:text-white block">{{ $title }}</span>
                                    <span class="text-[10px] text-slate-400 font-mono block">Key: {{ $key }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-brand-600 to-rose-600 text-white font-extrabold py-3.5 rounded-xl shadow-lg hover:opacity-95 transition glow-red flex items-center justify-center gap-2">
                    <span x-text="editingRole.id ? '💾 Update Role & Permissions' : '🛡️ Create New Custom Role'"></span>
                </button>
            </form>
        </div>

        <!-- System Roles Directory Table -->
        <div class="glass-card rounded-3xl overflow-hidden shadow-xl border border-slate-200 dark:border-slate-800">
            <div class="p-4 bg-slate-100 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">Configured Roles Directory & Assigned Users</h3>
                    <p class="text-xs text-slate-500">System core roles (Admin & Donor) are protected from deletion.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 uppercase font-bold">
                        <tr>
                            <th class="p-3.5">Role Title & Slug</th>
                            <th class="p-3.5">Role Description</th>
                            <th class="p-3.5">Assigned Users</th>
                            <th class="p-3.5">Granted Permissions Badges</th>
                            <th class="p-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800 font-medium">
                        @foreach($roles as $roleItem)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition">
                                <td class="p-3.5">
                                    <div class="font-extrabold text-sm text-slate-900 dark:text-white flex items-center gap-1.5">
                                        <span>{{ $roleItem->label }}</span>
                                        @if($roleItem->is_system)
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-amber-500/20 text-amber-500 border border-amber-500/30 uppercase">System</span>
                                        @endif
                                    </div>
                                    <div class="text-[10px] font-mono text-rose-600 dark:text-rose-400 font-bold mt-0.5">{{ $roleItem->name }}</div>
                                </td>
                                <td class="p-3.5 text-slate-600 dark:text-slate-400 max-w-xs leading-relaxed">
                                    {{ $roleItem->description ?: 'No description configured.' }}
                                </td>
                                <td class="p-3.5">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 border border-indigo-500/30">
                                        {{ $roleItem->users_count }} Members
                                    </span>
                                </td>
                                <td class="p-3.5">
                                    <div class="flex flex-wrap gap-1">
                                        @if($roleItem->name === 'admin')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30">
                                                ✓ ALL PERMISSIONS (SUPER ADMIN)
                                            </span>
                                        @elseif(!empty($roleItem->permissions))
                                            @foreach($roleItem->permissions as $perm)
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                                    {{ $availablePermissions[$perm] ?? $perm }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="text-[10px] text-slate-400 font-semibold">Standard Member Access (No Admin Privileges)</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-3.5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="editingRole = {{ json_encode([
                                            'id' => $roleItem->id,
                                            'name' => $roleItem->name,
                                            'label' => $roleItem->label,
                                            'description' => $roleItem->description,
                                            'permissions' => $roleItem->permissions ?? []
                                        ]) }}" class="px-2.5 py-1.5 rounded-xl bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-300 font-bold text-xs transition">
                                            ✏️ Edit Permissions
                                        </button>

                                        @if(!$roleItem->is_system && !in_array($roleItem->name, ['admin', 'donor']))
                                            <form action="{{ route('admin.roles.delete', $roleItem->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete role {{ $roleItem->label }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2 py-1.5 rounded-xl bg-rose-500/20 text-rose-600 hover:bg-rose-500/30 font-bold text-xs transition">
                                                    🗑️ Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 10: LOGO & BRANDING CMS -->
    <div x-show="tab === 'branding'" x-cloak class="space-y-8">
        <div class="glass-card p-6 sm:p-8 rounded-3xl shadow-xl border border-rose-500/30">
            <div class="mb-6">
                <h3 class="text-xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                    <span>🎨</span> Website Branding, Logo & Favicon Manager
                </h3>
                <p class="text-xs text-slate-500 mt-1">Upload brand assets that instantly update across the website header, footer, cards, login screens, and certificates.</p>
            </div>

            <form action="{{ route('admin.branding.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Organization Title *</label>
                        <input type="text" name="site_title" value="{{ $siteContent['organization_name'] }}" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Tagline / Subtitle</label>
                        <input type="text" name="tagline" value="{{ $branding['site_tagline'] ?? 'Bhagwangola Voluntary Society' }}" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-slate-200 dark:border-slate-800">
                    <!-- Light Mode Logo -->
                    <div class="p-4 rounded-2xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-3 text-center">
                        <label class="block text-xs font-extrabold uppercase text-slate-700 dark:text-slate-300">Light Mode Logo</label>
                        <div class="h-20 flex items-center justify-center bg-white rounded-xl p-2 border border-slate-200">
                            @if(!empty($branding['site_logo']))
                                <img src="{{ $branding['site_logo'] }}" alt="Logo" class="max-h-16 object-contain">
                            @else
                                <span class="text-xs text-slate-400 font-bold">No Custom Logo Uploaded</span>
                            @endif
                        </div>
                        <input type="file" name="logo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-600 file:text-white">
                    </div>

                    <!-- Dark Mode Logo -->
                    <div class="p-4 rounded-2xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-3 text-center">
                        <label class="block text-xs font-extrabold uppercase text-slate-700 dark:text-slate-300">Dark Mode Logo (Optional)</label>
                        <div class="h-20 flex items-center justify-center bg-slate-950 rounded-xl p-2 border border-slate-800">
                            @if(!empty($branding['site_dark_logo']))
                                <img src="{{ $branding['site_dark_logo'] }}" alt="Dark Logo" class="max-h-16 object-contain">
                            @else
                                <span class="text-xs text-slate-400 font-bold">Default Light Logo Used</span>
                            @endif
                        </div>
                        <input type="file" name="dark_logo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-600 file:text-white">
                    </div>

                    <!-- Favicon -->
                    <div class="p-4 rounded-2xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-3 text-center">
                        <label class="block text-xs font-extrabold uppercase text-slate-700 dark:text-slate-300">Browser Favicon</label>
                        <div class="h-20 flex items-center justify-center bg-white dark:bg-slate-950 rounded-xl p-2 border border-slate-200 dark:border-slate-800">
                            @if(!empty($branding['site_favicon']))
                                <img src="{{ $branding['site_favicon'] }}" alt="Favicon" class="w-10 h-10 object-contain">
                            @else
                                <span class="text-xs text-slate-400 font-bold">Default Browser Favicon</span>
                            @endif
                        </div>
                        <input type="file" name="favicon" accept="image/png,image/ico,image/svg+xml" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-600 file:text-white">
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl font-extrabold bg-gradient-to-r from-brand-600 to-rose-600 text-white text-sm shadow-lg glow-red">
                    💾 Save & Update Branding Assets
                </button>
            </form>
        </div>
    </div>

    <!-- TAB 11: MENU MANAGER CMS -->
    <div x-show="tab === 'menu'" x-cloak class="space-y-8" x-data="{ editingMenu: { id: null, location: 'header', parent_id: '', title: '', url: '', target: '_self', sort_order: 0, is_active: true } }">
        
        <!-- Add / Edit Menu Item Card -->
        <div class="glass-card p-6 sm:p-8 rounded-3xl shadow-xl border border-indigo-500/30">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                        <span>📌</span> Navigation Menu Manager
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Manage header & footer navigation links, internal pages, custom external links, and dropdown submenus.</p>
                </div>
                <button x-show="editingMenu.id" @click="editingMenu = { id: null, location: 'header', parent_id: '', title: '', url: '', target: '_self', sort_order: 0, is_active: true }" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                    + Add New Menu Link
                </button>
            </div>

            <form action="{{ route('admin.menu.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="id" :value="editingMenu.id">

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Menu Location *</label>
                        <select name="location" x-model="editingMenu.location" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs text-slate-900 dark:text-white font-bold">
                            <option value="header">Header Main Navigation</option>
                            <option value="footer">Footer Quick Links</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Parent Dropdown Menu (Submenu)</label>
                        <select name="parent_id" x-model="editingMenu.parent_id" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs text-slate-900 dark:text-white">
                            <option value="">None (Top-Level Item)</option>
                            @foreach($menuItems->whereNull('parent_id')->where('location', 'header') as $parentCandidate)
                                <option value="{{ $parentCandidate->id }}">{{ $parentCandidate->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Link Display Title *</label>
                        <input type="text" name="title" x-model="editingMenu.title" required placeholder="e.g. Volunteer Gallery" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs text-slate-900 dark:text-white">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Target URL / Route *</label>
                        <input type="text" name="url" x-model="editingMenu.url" required placeholder="e.g. /gallery or https://example.com" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs font-mono text-slate-900 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Open Behavior</label>
                        <select name="target" x-model="editingMenu.target" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs text-slate-900 dark:text-white">
                            <option value="_self">Same Tab (_self)</option>
                            <option value="_blank">New Tab (_blank)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Sort Priority Order</label>
                        <input type="number" name="sort_order" x-model="editingMenu.sort_order" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs text-slate-900 dark:text-white">
                    </div>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl font-bold bg-indigo-600 text-white text-xs hover:bg-indigo-700 transition">
                    <span x-text="editingMenu.id ? '💾 Update Menu Link' : '📌 Add Menu Item'"></span>
                </button>
            </form>
        </div>

        <!-- Menu Directory Table -->
        <div class="glass-card rounded-3xl overflow-hidden shadow-xl border border-slate-200 dark:border-slate-800">
            <div class="p-4 bg-slate-100 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800">
                <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">Active Menu Navigation Links Directory</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 uppercase font-bold">
                        <tr>
                            <th class="p-3.5">Location</th>
                            <th class="p-3.5">Title</th>
                            <th class="p-3.5">URL / Route</th>
                            <th class="p-3.5">Order</th>
                            <th class="p-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800 font-medium">
                        @foreach($menuItems as $m)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition">
                                <td class="p-3.5">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase {{ $m->location === 'header' ? 'bg-indigo-500/20 text-indigo-400' : 'bg-amber-500/20 text-amber-400' }}">
                                        {{ $m->location }}
                                    </span>
                                </td>
                                <td class="p-3.5 font-bold text-slate-900 dark:text-white">
                                    @if($m->parent_id) <span class="text-slate-400 ml-2">↳ </span> @endif
                                    {{ $m->title }}
                                </td>
                                <td class="p-3.5 font-mono text-slate-500">{{ $m->url }}</td>
                                <td class="p-3.5 font-bold">{{ $m->sort_order }}</td>
                                <td class="p-3.5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="editingMenu = {{ json_encode([
                                            'id' => $m->id,
                                            'location' => $m->location,
                                            'parent_id' => $m->parent_id,
                                            'title' => $m->title,
                                            'url' => $m->url,
                                            'target' => $m->target,
                                            'sort_order' => $m->sort_order,
                                            'is_active' => $m->is_active
                                        ]) }}" class="px-2.5 py-1 rounded-lg bg-slate-200 dark:bg-slate-800 text-xs font-bold">✏️ Edit</button>

                                        <form action="{{ route('admin.menu.delete', $m->id) }}" method="POST" onsubmit="return confirm('Delete this menu item?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2 py-1 rounded-lg bg-rose-500/20 text-rose-500 text-xs font-bold">🗑️</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 12: HOMEPAGE BUILDER & SECTION MANAGER -->
    <div x-show="tab === 'homepage'" x-cloak class="space-y-8" x-data="{ editingSec: { id: null, key: '', title: '', subtitle: '', content: '', button_text: '', button_url: '', secondary_button_text: '', secondary_button_url: '', sort_order: 0, is_visible: true } }">
        <div class="glass-card p-6 sm:p-8 rounded-3xl shadow-xl border border-emerald-500/30">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                        <span>🧩</span> Dynamic Homepage Section Builder
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Edit homepage titles, descriptions, button links, background images, and section visibility.</p>
                </div>
            </div>

            <form action="{{ route('admin.sections.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="id" :value="editingSec.id">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Section Key / Unique Identifier *</label>
                        <input type="text" name="key" x-model="editingSec.key" required placeholder="e.g. hero, stats, why_donate" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs font-mono text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Section Headline / Title *</label>
                        <input type="text" name="title" x-model="editingSec.title" required placeholder="e.g. Saving Lives in Bhagwangola" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs text-slate-900 dark:text-white">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Subtitle / Badge</label>
                    <input type="text" name="subtitle" x-model="editingSec.subtitle" placeholder="Sub-heading description text" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs text-slate-900 dark:text-white">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Main Body Content</label>
                    <textarea name="content" x-model="editingSec.content" rows="3" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl p-4 text-xs text-slate-900 dark:text-white"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Primary Button Text</label>
                        <input type="text" name="button_text" x-model="editingSec.button_text" placeholder="e.g. Search Blood Donors" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Primary Button URL</label>
                        <input type="text" name="button_url" x-model="editingSec.button_url" placeholder="e.g. /search" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs font-mono">
                    </div>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl font-bold bg-emerald-600 text-white text-xs">
                    <span x-text="editingSec.id ? '💾 Save Section Changes' : '🧩 Create Homepage Section'"></span>
                </button>
            </form>

            <div class="mt-6 border-t border-slate-200 dark:border-slate-800 pt-4">
                <h4 class="text-xs font-extrabold uppercase text-slate-700 dark:text-slate-300 mb-3">Configured Homepage Sections Directory</h4>
                <div class="space-y-2">
                    @foreach($homepageSections as $sec)
                        <div class="p-3 rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-between">
                            <div>
                                <span class="font-extrabold text-xs text-slate-900 dark:text-white">{{ $sec->title }}</span>
                                <span class="text-[10px] font-mono text-rose-500 ml-2">({{ $sec->key }})</span>
                            </div>
                            <button @click="editingSec = {{ json_encode([
                                'id' => $sec->id,
                                'key' => $sec->key,
                                'title' => $sec->title,
                                'subtitle' => $sec->subtitle,
                                'content' => $sec->content,
                                'button_text' => $sec->button_text,
                                'button_url' => $sec->button_url,
                                'secondary_button_text' => $sec->secondary_button_text,
                                'secondary_button_url' => $sec->secondary_button_url,
                                'sort_order' => $sec->sort_order,
                                'is_visible' => $sec->is_visible
                            ]) }}" class="px-3 py-1 bg-slate-200 dark:bg-slate-800 rounded-lg text-xs font-bold">✏️ Edit</button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 13: DYNAMIC DEDICATED CMS PAGES -->
    <div x-show="tab === 'pages'" x-cloak class="space-y-8" x-data="{ editingPage: { id: null, title: '', slug: '', status: 'published', meta_title: '', meta_description: '', content: '' } }">
        <div class="glass-card p-6 sm:p-8 rounded-3xl shadow-xl border border-cyan-500/30">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                        <span>📄</span> Dynamic CMS Pages Manager
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Create custom standalone pages (e.g. Privacy Policy, Terms, Camp Reports) rendered at /p/{slug}.</p>
                </div>
            </div>

            <form action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="id" :value="editingPage.id">

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Page Title *</label>
                        <input type="text" name="title" x-model="editingPage.title" required placeholder="e.g. Privacy Policy" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">URL Slug *</label>
                        <input type="text" name="slug" x-model="editingPage.slug" required placeholder="e.g. privacy-policy" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs font-mono text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Status</label>
                        <select name="status" x-model="editingPage.status" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs font-bold">
                            <option value="published">Published Live</option>
                            <option value="draft">Draft (Hidden)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Page Body Content HTML/Text *</label>
                    <textarea name="content" x-model="editingPage.content" rows="6" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl p-4 text-xs font-mono text-slate-900 dark:text-white"></textarea>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl font-bold bg-cyan-600 text-white text-xs">
                    <span x-text="editingPage.id ? '💾 Update CMS Page' : '📄 Publish New Page'"></span>
                </button>
            </form>

            <div class="mt-6 border-t border-slate-200 dark:border-slate-800 pt-4">
                <h4 class="text-xs font-extrabold uppercase text-slate-700 dark:text-slate-300 mb-3">Published CMS Pages Directory</h4>
                <div class="space-y-2">
                    @foreach($cmsPages as $p)
                        <div class="p-3 rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-between">
                            <div>
                                <span class="font-extrabold text-xs text-slate-900 dark:text-white">{{ $p->title }}</span>
                                <a href="{{ route('pages.show', $p->slug) }}" target="_blank" class="text-[10px] text-indigo-400 font-mono underline ml-2">/p/{{ $p->slug }} 🔗</a>
                            </div>
                            <div class="flex items-center gap-2">
                                <button @click="editingPage = {{ json_encode([
                                    'id' => $p->id,
                                    'title' => $p->title,
                                    'slug' => $p->slug,
                                    'status' => $p->status,
                                    'meta_title' => $p->meta_title,
                                    'meta_description' => $p->meta_description,
                                    'content' => $p->content
                                ]) }}" class="px-2.5 py-1 bg-slate-200 dark:bg-slate-800 rounded-lg text-xs font-bold">✏️ Edit</button>
                                <form action="{{ route('admin.pages.delete', $p->id) }}" method="POST" onsubmit="return confirm('Delete page?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1 bg-rose-500/20 text-rose-500 rounded-lg text-xs font-bold">🗑️</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 14: MEDIA LIBRARY -->
    <div x-show="tab === 'media'" x-cloak class="space-y-8">
        <div class="glass-card p-6 sm:p-8 rounded-3xl shadow-xl border border-purple-500/30">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                        <span>📁</span> Centralized Media Library
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Upload images, icons, and document assets to reuse across all CMS sections.</p>
                </div>
            </div>

            <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center gap-4 p-4 rounded-2xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                @csrf
                <input type="file" name="file" required class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-600 file:text-white">
                <input type="text" name="alt_text" placeholder="Image description / alt text" class="w-full bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2 text-xs">
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl font-bold bg-purple-600 text-white text-xs shrink-0">Upload to Library</button>
            </form>

            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4 mt-6">
                @foreach($mediaAssets as $media)
                    <div class="group relative rounded-2xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-2 overflow-hidden shadow-sm">
                        <img src="{{ $media->url }}" alt="{{ $media->alt_text }}" class="w-full h-24 object-cover rounded-xl">
                        <div class="mt-2 text-[10px] font-mono text-slate-400 truncate">{{ $media->filename }}</div>
                        <div class="flex items-center justify-between mt-1">
                            <button onclick="navigator.clipboard.writeText('{{ $media->url }}'); alert('URL Copied!')" class="text-[9px] font-bold text-indigo-400">Copy URL</button>
                            <form action="{{ route('admin.media.delete', $media->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[9px] font-bold text-rose-500">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- TAB 15: CUSTOM CSS & SCRIPTING -->
    <div x-show="tab === 'customcode'" x-cloak class="space-y-8">
        <div class="glass-card p-6 sm:p-8 rounded-3xl shadow-xl border border-rose-500/30">
            <div class="mb-4">
                <h3 class="text-xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                    <span>💻</span> Custom CSS & Scripting Injector
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Inject custom styling rules without touching theme template files.</p>
            </div>

            <form action="{{ route('admin.custom-code.update') }}" method="POST" class="space-y-4">
                @csrf
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="enable_custom_css" value="1" {{ ($branding['enable_custom_css'] ?? '1') === '1' ? 'checked' : '' }} class="w-4 h-4 text-rose-600 rounded">
                    <label class="text-xs font-bold text-slate-900 dark:text-white">Enable Custom CSS Injection on Frontend</label>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Custom CSS Rules</label>
                    <textarea name="custom_css" rows="8" placeholder="/* Enter custom CSS rules here */&#10;.custom-badge { background: #e11d48; color: white; }" class="w-full bg-slate-900 text-emerald-400 border border-slate-700 rounded-2xl p-4 text-xs font-mono">{{ $branding['custom_css'] ?? '' }}</textarea>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl font-extrabold bg-gradient-to-r from-brand-600 to-rose-600 text-white text-xs shadow-lg glow-red">
                    💾 Save Custom CSS Settings
                </button>
            </form>
        </div>
    </div>

    <!-- EDIT USER & CREDENTIALS MODAL -->
    <div x-show="editUserModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
        <div class="glass-card max-w-xl w-full p-6 sm:p-8 rounded-3xl border border-slate-300 dark:border-slate-800 relative shadow-2xl overflow-y-auto max-h-[90vh]">
            <button @click="editUserModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-900 dark:hover:text-white text-2xl font-bold">&times;</button>

            <div class="mb-6">
                <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">Edit Registered User & Credentials</h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Update profile details, contact number, donor location, or set a new password.</p>
            </div>

            <form :action="'/admin/users/' + editUser.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase">Full Name *</label>
                    <input type="text" name="name" x-model="editUser.name" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase">Phone Number *</label>
                        <input type="tel" name="phone" x-model="editUser.phone" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase">Email Address</label>
                        <input type="email" name="email" x-model="editUser.email" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                    </div>
                </div>

                <!-- PASSWORD RESET FIELD -->
                <div class="p-3 rounded-2xl bg-amber-500/10 border border-amber-500/30">
                    <label class="block text-xs font-extrabold text-amber-600 dark:text-amber-400 mb-1 uppercase">
                        🔑 Reset User Password
                    </label>
                    <input type="password" name="password" placeholder="Leave blank to keep existing password" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                    <span class="text-[10px] text-amber-500 font-medium mt-1 block">Enter a new password (min 6 chars) to reset this user's password directly.</span>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase">Assigned System Role *</label>
                        <select name="role" x-model="editUser.role" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-bold">
                            @foreach($roles as $rOption)
                                <option value="{{ $rOption->name }}">{{ $rOption->label }} ({{ $rOption->name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase">Loyalty Points</label>
                        <input type="number" name="loyalty_points" x-model="editUser.loyalty_points" min="0" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-bold">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase">Blood Group *</label>
                        <select name="blood_group" x-model="editUser.blood_group" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-bold">
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $g)
                                <option value="{{ $g }}">{{ $g }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase">Availability</label>
                        <select name="availability_status" x-model="editUser.availability_status" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                            <option value="available">Available</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase">Village</label>
                        <input type="text" name="village" x-model="editUser.village" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase">Block</label>
                        <input type="text" name="block" x-model="editUser.block" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase">District</label>
                        <input type="text" name="district" x-model="editUser.district" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase">Donor Classification</label>
                    <select name="donor_type" x-model="editUser.donor_type" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white">
                        <option value="regular">Regular Donor</option>
                        <option value="emergency">Emergency On-Call</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-brand-600 to-rose-600 text-white font-extrabold py-3 rounded-xl shadow-lg transition glow-red">
                    Update User & Save Changes 💾
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function adminDashboard() {
    return {
        tab: 'inquiries',
        inquiriesCount: {{ count($inquiries) }},
        inquiries: @json($inquiries),
        editUserModal: false,
        editUser: {},

        openEditUserModal(userData) {
            this.editUser = userData;
            this.editUserModal = true;
        },

        fetchInquiries() {
            fetch("{{ route('admin.inquiries.live') }}")
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        this.inquiriesCount = data.count;
                        this.inquiries = data.inquiries;
                    }
                })
                .catch(err => console.log('Poll error:', err));
        },

        init() {
            setInterval(() => {
                this.fetchInquiries();
            }, 4000);
        }
    }
}
</script>
@endsection

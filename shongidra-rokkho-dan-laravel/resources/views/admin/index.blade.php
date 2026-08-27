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
            <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Manage donor inquiries, blog posts, SEO settings, site traffic analytics, slides, and user roles.</p>
        </div>

        <!-- Quick Stats Overview Bar -->
        <div class="flex flex-wrap items-center gap-3">
            <div class="px-4 py-2 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-center">
                <span class="text-xs text-slate-500 dark:text-slate-400 block font-semibold">Live Inquiries</span>
                <span class="text-lg font-extrabold text-rose-600 dark:text-rose-400" x-text="inquiriesCount"></span>
            </div>
            <div class="px-4 py-2 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-center">
                <span class="text-xs text-slate-500 dark:text-slate-400 block font-semibold">Total Pageviews</span>
                <span class="text-lg font-extrabold text-indigo-600 dark:text-indigo-400">{{ number_format($stats['total_page_views']) }}</span>
            </div>
            <div class="px-4 py-2 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-center">
                <span class="text-xs text-slate-500 dark:text-slate-400 block font-semibold">Registered Donors</span>
                <span class="text-lg font-extrabold text-emerald-600 dark:text-emerald-400">{{ number_format($stats['total_donors']) }}</span>
            </div>
        </div>
    </div>

    <!-- Admin Navigation Tabs -->
    <div class="flex items-center space-x-2 border-b border-slate-200 dark:border-slate-800 mb-8 overflow-x-auto pb-2">
        <button @click="tab = 'inquiries'" :class="tab === 'inquiries' ? 'border-rose-600 text-rose-600 dark:text-rose-400 font-extrabold' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" class="py-2.5 px-4 border-b-2 text-xs uppercase tracking-wider transition whitespace-nowrap flex items-center gap-2">
            <span>⚡ Live Inquiries</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] bg-rose-600 text-white" x-text="inquiriesCount"></span>
        </button>

        <button @click="tab = 'blog'" :class="tab === 'blog' ? 'border-rose-600 text-rose-600 dark:text-rose-400 font-extrabold' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" class="py-2.5 px-4 border-b-2 text-xs uppercase tracking-wider transition whitespace-nowrap">
            📝 Blog & Articles ({{ count($blogPosts) }})
        </button>

        <button @click="tab = 'seo'" :class="tab === 'seo' ? 'border-rose-600 text-rose-600 dark:text-rose-400 font-extrabold' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" class="py-2.5 px-4 border-b-2 text-xs uppercase tracking-wider transition whitespace-nowrap">
            🔍 Page SEO Settings ({{ count($seoSettings) }})
        </button>

        <button @click="tab = 'analytics'" :class="tab === 'analytics' ? 'border-rose-600 text-rose-600 dark:text-rose-400 font-extrabold' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" class="py-2.5 px-4 border-b-2 text-xs uppercase tracking-wider transition whitespace-nowrap">
            📊 Page Analytics
        </button>

        <button @click="tab = 'users'" :class="tab === 'users' ? 'border-rose-600 text-rose-600 dark:text-rose-400 font-extrabold' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" class="py-2.5 px-4 border-b-2 text-xs uppercase tracking-wider transition whitespace-nowrap">
            👥 Users & Roles
        </button>

        <button @click="tab = 'donations'" :class="tab === 'donations' ? 'border-rose-600 text-rose-600 dark:text-rose-400 font-extrabold' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" class="py-2.5 px-4 border-b-2 text-xs uppercase tracking-wider transition whitespace-nowrap">
            🩸 Record Donation
        </button>

        <button @click="tab = 'slides'" :class="tab === 'slides' ? 'border-rose-600 text-rose-600 dark:text-rose-400 font-extrabold' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" class="py-2.5 px-4 border-b-2 text-xs uppercase tracking-wider transition whitespace-nowrap">
            🖼️ Carousel Slides
        </button>

        <button @click="tab = 'gallery'" :class="tab === 'gallery' ? 'border-rose-600 text-rose-600 dark:text-rose-400 font-extrabold' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" class="py-2.5 px-4 border-b-2 text-xs uppercase tracking-wider transition whitespace-nowrap">
            📷 Gallery Photos
        </button>

        <button @click="tab = 'settings'" :class="tab === 'settings' ? 'border-rose-600 text-rose-600 dark:text-rose-400 font-extrabold' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" class="py-2.5 px-4 border-b-2 text-xs uppercase tracking-wider transition whitespace-nowrap">
            ⚙️ Site Content Settings
        </button>
    </div>

    <!-- TAB 1: Real-Time Live Visitor Inquiries -->
    <div x-show="tab === 'inquiries'" x-cloak class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Real-Time Inquiries Feed</h3>
                <p class="text-xs text-slate-500">Auto-refreshing every 4 seconds. Logs IP address and session ID of visitors requesting donor contact.</p>
            </div>
            <button @click="fetchInquiries()" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-700 hover:bg-slate-300 dark:hover:bg-slate-700 transition">
                🔄 Manual Refresh
            </button>
        </div>

        <div class="glass-card rounded-2xl overflow-hidden shadow-xl border border-slate-200 dark:border-slate-800">
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

    <!-- TAB 2: Blog & Articles Management (with SEO) -->
    <div x-show="tab === 'blog'" x-cloak class="space-y-8">
        <!-- Add Blog Post Form -->
        <div class="glass-card p-6 sm:p-8 rounded-3xl shadow-xl">
            <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-2">Publish New Blog Article with SEO</h3>
            <p class="text-xs text-slate-500 mb-6">Articles published here will appear on the public `/blog` route with optimized search engine tags.</p>

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
                    <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Article Excerpt / Short Summary</label>
                    <textarea name="excerpt" rows="2" placeholder="Short description shown on blog preview cards..." class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Full Article Content</label>
                    <textarea name="content" rows="6" required placeholder="Write the article content..." class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-mono text-xs"></textarea>
                </div>

                <!-- Article Specific SEO Accordion -->
                <div class="pt-4 border-t border-slate-200 dark:border-slate-800">
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-rose-600 dark:text-rose-400 mb-3">🔍 SEO Metadata Settings for this Article</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">SEO Title Tag (meta title)</label>
                            <input type="text" name="meta_title" placeholder="Custom SEO title for Google..." class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">SEO Keywords (comma separated)</label>
                            <input type="text" name="meta_keywords" placeholder="e.g. blood donation, Bhagwangola, Murshidabad" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">SEO Meta Description</label>
                            <input type="text" name="meta_description" placeholder="Short description for Google search results snippet..." class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                        </div>
                    </div>
                </div>

                <button type="submit" class="px-6 py-3 rounded-xl text-xs font-extrabold bg-gradient-to-r from-brand-600 to-rose-600 text-white shadow-lg hover:opacity-95">
                    Publish Article 🚀
                </button>
            </form>
        </div>

        <!-- Existing Blog Posts Table -->
        <div class="glass-card rounded-3xl overflow-hidden shadow-xl border border-slate-200 dark:border-slate-800">
            <div class="p-4 bg-slate-100 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 font-bold text-sm text-slate-900 dark:text-white">
                Published Blog Articles
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-950 text-slate-700 dark:text-slate-300 uppercase tracking-wider font-bold">
                        <tr>
                            <th class="p-3.5">Title & Slug</th>
                            <th class="p-3.5">Category</th>
                            <th class="p-3.5">Views</th>
                            <th class="p-3.5">Published Date</th>
                            <th class="p-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                        @foreach($blogPosts as $post)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50">
                                <td class="p-3.5">
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $post->title }}</div>
                                    <div class="text-[10px] text-slate-500 font-mono">/blog/{{ $post->slug }}</div>
                                </td>
                                <td class="p-3.5 font-bold text-rose-600 dark:text-rose-400">{{ $post->category }}</td>
                                <td class="p-3.5 font-bold">{{ number_format($post->views_count) }}</td>
                                <td class="p-3.5 text-slate-500">{{ $post->created_at->format('d M Y') }}</td>
                                <td class="p-3.5 text-right">
                                    <form action="{{ route('admin.blog.delete', $post->id) }}" method="POST" onsubmit="return confirm('Delete this post?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:underline font-bold">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 3: Per-Page SEO Settings Management -->
    <div x-show="tab === 'seo'" x-cloak class="space-y-6">
        <div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-white">Per-Page SEO Meta Settings</h3>
            <p class="text-xs text-slate-500">Configure Meta Title, Meta Description, Keywords, and OpenGraph Image tags for every route page on the site.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($seoSettings as $seo)
                <div class="glass-card p-6 rounded-3xl shadow-lg border border-slate-200 dark:border-slate-800">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-200 dark:border-slate-800">
                        <span class="font-extrabold text-sm text-slate-900 dark:text-white">{{ $seo->page_name }}</span>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-mono bg-slate-200 dark:bg-slate-800 text-rose-600 dark:text-rose-400">
                            Key: {{ $seo->page_key }}
                        </span>
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

    <!-- TAB 4: Page Analytics Traffic Dashboard -->
    <div x-show="tab === 'analytics'" x-cloak class="space-y-8">
        <!-- Analytics Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="glass-card p-6 rounded-3xl text-center">
                <div class="text-xs uppercase font-bold text-slate-500 mb-1">Total Page Views</div>
                <div class="text-3xl font-extrabold text-indigo-600 dark:text-indigo-400">{{ number_format($analytics['total_views']) }}</div>
            </div>
            <div class="glass-card p-6 rounded-3xl text-center">
                <div class="text-xs uppercase font-bold text-slate-500 mb-1">Unique Visitor IPs</div>
                <div class="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ number_format($analytics['unique_visitors']) }}</div>
            </div>
            <div class="glass-card p-6 rounded-3xl text-center">
                <div class="text-xs uppercase font-bold text-slate-500 mb-1">Today's Traffic</div>
                <div class="text-3xl font-extrabold text-rose-600 dark:text-rose-400">{{ number_format($analytics['today_views']) }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Top Visited Pages -->
            <div class="glass-card p-6 rounded-3xl shadow-xl">
                <h4 class="text-base font-extrabold text-slate-900 dark:text-white mb-4">Top 5 Visited Pages</h4>
                <div class="space-y-3">
                    @foreach($analytics['top_pages'] as $top)
                        <div class="flex items-center justify-between text-xs p-3 rounded-2xl bg-slate-100 dark:bg-slate-900/80">
                            <span class="font-mono font-bold text-slate-800 dark:text-slate-200">{{ $top->path }}</span>
                            <span class="px-3 py-1 rounded-full bg-rose-600/20 text-rose-600 dark:text-rose-400 font-extrabold">{{ number_format($top->total) }} views</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Device Breakdown -->
            <div class="glass-card p-6 rounded-3xl shadow-xl">
                <h4 class="text-base font-extrabold text-slate-900 dark:text-white mb-4">Traffic Device Breakdown</h4>
                <div class="space-y-3">
                    @foreach($analytics['device_breakdown'] as $dev)
                        <div class="flex items-center justify-between text-xs p-3 rounded-2xl bg-slate-100 dark:bg-slate-900/80">
                            <span class="font-bold text-slate-800 dark:text-slate-200 uppercase">📱 {{ $dev->device_type }}</span>
                            <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 font-extrabold">{{ number_format($dev->total) }} visits</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Visitor Traffic Logs -->
        <div class="glass-card rounded-3xl overflow-hidden shadow-xl border border-slate-200 dark:border-slate-800">
            <div class="p-4 bg-slate-100 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 font-bold text-sm text-slate-900 dark:text-white">
                Recent Traffic Activity Log
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-950 text-slate-700 dark:text-slate-300 uppercase tracking-wider font-bold">
                        <tr>
                            <th class="p-3.5">Time Ago</th>
                            <th class="p-3.5">Page Path</th>
                            <th class="p-3.5">IP Address</th>
                            <th class="p-3.5">Device</th>
                            <th class="p-3.5">Referrer</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                        @foreach($analytics['recent_traffic'] as $log)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50">
                                <td class="p-3.5 text-slate-500 whitespace-nowrap">{{ $log->created_at->diffForHumans() }}</td>
                                <td class="p-3.5 font-mono font-bold text-slate-900 dark:text-white">{{ $log->path }}</td>
                                <td class="p-3.5 font-mono text-slate-600 dark:text-slate-400">{{ $log->ip_address }}</td>
                                <td class="p-3.5 font-bold uppercase text-rose-600 dark:text-rose-400">{{ $log->device_type }}</td>
                                <td class="p-3.5 text-slate-500 truncate max-w-xs">{{ $log->referrer ?: 'Direct' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 5: Users & Roles Management -->
    <div x-show="tab === 'users'" x-cloak class="space-y-6">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Registered Users & Role Management</h3>
        <div class="glass-card rounded-3xl overflow-hidden shadow-xl border border-slate-200 dark:border-slate-800">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 uppercase font-bold">
                        <tr>
                            <th class="p-3.5">Name</th>
                            <th class="p-3.5">Email / Phone</th>
                            <th class="p-3.5">Current Role</th>
                            <th class="p-3.5 text-right">Update Role</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                        @foreach($users as $u)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50">
                                <td class="p-3.5 font-bold text-slate-900 dark:text-white">{{ $u->name }}</td>
                                <td class="p-3.5 text-slate-500">{{ $u->email }} / {{ $u->phone }}</td>
                                <td class="p-3.5">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase {{ $u->isAdmin() ? 'bg-amber-500/20 text-amber-600' : 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300' }}">
                                        {{ $u->role }}
                                    </span>
                                </td>
                                <td class="p-3.5 text-right">
                                    <form action="{{ route('admin.users.role', $u->id) }}" method="POST" class="inline-flex gap-2">
                                        @csrf
                                        <select name="role" class="bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg px-2 py-1 text-xs">
                                            <option value="donor" {{ $u->role === 'donor' ? 'selected' : '' }}>Donor</option>
                                            <option value="member" {{ $u->role === 'member' ? 'selected' : '' }}>Member</option>
                                            <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                        <button type="submit" class="px-3 py-1 rounded-lg bg-rose-600 text-white font-bold text-xs">Update</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 6: Record Donation -->
    <div x-show="tab === 'donations'" x-cloak class="space-y-6">
        <div class="glass-card p-6 sm:p-8 rounded-3xl shadow-xl max-w-xl mx-auto">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Record Official Blood Donation</h3>
            <form action="{{ route('admin.donations.record') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Select Donor User</label>
                    <select name="user_id" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white">
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
                    <input type="text" name="location" placeholder="e.g. Bhagwangola Rural Hospital" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white">
                </div>
                <button type="submit" class="w-full py-3 rounded-xl font-extrabold bg-gradient-to-r from-brand-600 to-rose-600 text-white shadow-lg">
                    Generate Donation Record & Certificate 📜
                </button>
            </form>
        </div>
    </div>

    <!-- TAB 7: Slides -->
    <div x-show="tab === 'slides'" x-cloak class="space-y-6">
        <div class="glass-card p-6 rounded-3xl shadow-xl">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Add Background Slide</h3>
            <form action="{{ route('admin.slides.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <input type="text" name="title" placeholder="Slide Title" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2 text-xs">
                <input type="text" name="subtitle" placeholder="Slide Subtitle" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2 text-xs">
                <input type="url" name="image_url" placeholder="Image URL (or upload below)" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2 text-xs">
                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-rose-600 text-white">Upload Slide</button>
            </form>
        </div>
    </div>

    <!-- TAB 8: Gallery -->
    <div x-show="tab === 'gallery'" x-cloak class="space-y-6">
        <div class="glass-card p-6 rounded-3xl shadow-xl">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Add Gallery Photo</h3>
            <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <input type="text" name="title" placeholder="Photo Title" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2 text-xs">
                <input type="text" name="category" placeholder="Category e.g. Camps, Awareness" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2 text-xs">
                <input type="url" name="image_url" placeholder="Image URL" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2 text-xs">
                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-rose-600 text-white">Add Photo</button>
            </form>
        </div>
    </div>

    <!-- TAB 9: Settings -->
    <div x-show="tab === 'settings'" x-cloak class="space-y-6">
        <div class="glass-card p-6 rounded-3xl shadow-xl max-w-xl mx-auto">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Site Content & Contact Settings</h3>
            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Organization Name</label>
                    <input type="text" name="organization_name" value="{{ $siteContent['organization_name'] }}" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 dark:text-slate-300 mb-1">Helpline Phone</label>
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

</div>

<script>
function adminDashboard() {
    return {
        tab: 'inquiries',
        inquiriesCount: {{ count($inquiries) }},
        inquiries: @json($inquiries),

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

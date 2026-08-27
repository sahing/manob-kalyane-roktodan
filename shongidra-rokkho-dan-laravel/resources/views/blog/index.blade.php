@extends('layouts.app')

@section('content')
<!-- BLOG HERO -->
<div class="bg-gradient-to-b from-slate-900 via-slate-950 to-slate-900 text-white py-14 border-b border-slate-800 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="inline-block px-3.5 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-rose-500/20 text-rose-300 border border-rose-500/30 mb-3">
            Awareness & Guidance
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight">Blood Donation Blog & Health Insights</h1>
        <p class="text-sm sm:text-base text-slate-300 max-w-2xl mx-auto mt-3 leading-relaxed">
            Discover health guidelines, medical facts, eligibility checklists, and voluntary movement stories from Bhagwangola & Murshidabad.
        </p>

        <!-- Search Bar -->
        <form action="{{ route('blog.index') }}" method="GET" class="mt-8 max-w-xl mx-auto flex items-center gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search articles by title or keyword..." class="w-full bg-slate-800/90 border border-slate-700 rounded-2xl px-5 py-3.5 text-sm text-white placeholder-slate-400 focus:outline-none focus:border-rose-500">
            <button type="submit" class="px-6 py-3.5 rounded-2xl font-bold text-xs bg-rose-600 hover:bg-rose-500 text-white shadow-lg transition">
                Search
            </button>
        </form>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <!-- Category Tabs -->
    <div class="flex flex-wrap items-center gap-2 mb-10 pb-4 border-b border-slate-200 dark:border-slate-800">
        <a href="{{ route('blog.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ !request('category') ? 'bg-rose-600 text-white shadow-md' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800' }}">
            All Categories
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('blog.index', ['category' => $cat]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ request('category') == $cat ? 'bg-rose-600 text-white shadow-md' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800' }}">
                {{ $cat }}
            </a>
        @endforeach
    </div>

    <!-- Posts Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @forelse($posts as $post)
            <div class="glass-card rounded-3xl overflow-hidden shadow-xl flex flex-col justify-between group hover:border-rose-600/60 transition duration-300">
                <div>
                    <!-- Image -->
                    <div class="relative h-52 overflow-hidden bg-slate-900">
                        @if($post->cover_image)
                            <img src="{{ $post->cover_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-slate-900 via-rose-950 to-slate-900 flex items-center justify-center p-6 text-center">
                                <span class="text-4xl">🩸</span>
                            </div>
                        @endif
                        <span class="absolute top-4 left-4 px-3 py-1 rounded-xl text-[11px] font-extrabold bg-rose-600 text-white shadow-md">
                            {{ $post->category }}
                        </span>
                    </div>

                    <!-- Post Body -->
                    <div class="p-6">
                        <div class="flex items-center gap-3 text-[11px] text-slate-500 dark:text-slate-400 mb-2 font-medium">
                            <span>📅 {{ $post->created_at->format('M d, Y') }}</span>
                            <span>•</span>
                            <span>👁️ {{ number_format($post->views_count) }} Views</span>
                        </div>

                        <h2 class="text-lg font-extrabold text-slate-900 dark:text-white mb-3 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors line-clamp-2">
                            <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                        </h2>

                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed line-clamp-3 mb-4">
                            {{ $post->excerpt ?: Str::limit(strip_tags($post->content), 120) }}
                        </p>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800/80 bg-slate-50 dark:bg-slate-950/40 flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">✍️ {{ $post->author_name }}</span>
                    <a href="{{ route('blog.show', $post->slug) }}" class="text-xs font-extrabold text-rose-600 dark:text-rose-400 hover:underline">
                        Read Article →
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-16 glass-card rounded-3xl text-slate-500 dark:text-slate-400">
                <span class="text-4xl block mb-2">📰</span>
                <p class="font-bold">No articles found matching your criteria.</p>
                <a href="{{ route('blog.index') }}" class="text-xs font-bold text-rose-600 dark:text-rose-400 hover:underline mt-2 inline-block">View All Articles</a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-10">
        {{ $posts->links() }}
    </div>
</div>
@endsection

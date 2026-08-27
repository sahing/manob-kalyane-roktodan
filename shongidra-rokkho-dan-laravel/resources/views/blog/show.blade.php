@extends('layouts.app')

@section('content')
<!-- Article Header -->
<div class="bg-gradient-to-b from-slate-900 via-slate-950 to-slate-900 text-white py-14 border-b border-slate-800">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('blog.index') }}" class="text-xs font-bold text-slate-400 hover:text-rose-400">← Back to Blog</a>
            <span class="text-slate-600">•</span>
            <span class="px-3 py-0.5 rounded-full text-xs font-extrabold bg-rose-600/30 text-rose-300 border border-rose-500/30">
                {{ $post->category }}
            </span>
        </div>

        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight leading-tight mb-4">
            {{ $post->title }}
        </h1>

        <div class="flex flex-wrap items-center gap-4 text-xs text-slate-300 font-medium pt-2 border-t border-slate-800">
            <span class="flex items-center gap-1.5">
                <span class="w-7 h-7 rounded-full bg-rose-600 text-white flex items-center justify-center font-bold text-xs">✍️</span>
                <span>{{ $post->author_name }}</span>
            </span>
            <span>•</span>
            <span>📅 {{ $post->created_at->format('F d, Y') }}</span>
            <span>•</span>
            <span>👁️ {{ number_format($post->views_count) }} Views</span>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Cover Image -->
    @if($post->cover_image)
        <div class="rounded-3xl overflow-hidden shadow-2xl mb-10 border border-slate-200 dark:border-slate-800">
            <img src="{{ $post->cover_image }}" alt="{{ $post->title }}" class="w-full max-h-[480px] object-cover">
        </div>
    @endif

    <!-- Content Body -->
    <div class="glass-card p-6 sm:p-10 rounded-3xl mb-12 shadow-xl border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 leading-relaxed space-y-4 text-base font-normal">
        {!! nl2br(e($post->content)) !!}
    </div>

    <!-- Share & Social Buttons -->
    <div class="p-6 rounded-2xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex flex-wrap items-center justify-between gap-4 mb-12">
        <span class="text-xs font-extrabold uppercase tracking-wider text-slate-700 dark:text-slate-300">Share This Article:</span>
        <div class="flex items-center gap-2">
            <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ') }}{{ urlencode(url()->current()) }}" target="_blank" class="px-4 py-2 rounded-xl text-xs font-bold bg-[#25D366] text-white hover:opacity-90 shadow-md">
                WhatsApp
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="px-4 py-2 rounded-xl text-xs font-bold bg-[#1877F2] text-white hover:opacity-90 shadow-md">
                Facebook
            </a>
            <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" class="px-4 py-2 rounded-xl text-xs font-bold bg-[#1DA1F2] text-white hover:opacity-90 shadow-md">
                Twitter
            </a>
        </div>
    </div>

    <!-- Related Articles -->
    @if(count($relatedPosts) > 0)
        <div class="pt-8 border-t border-slate-200 dark:border-slate-800">
            <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-6">Related Articles</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedPosts as $rel)
                    <div class="glass-card p-5 rounded-2xl shadow-md hover:border-rose-600/60 transition">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400 block mb-1">{{ $rel->category }}</span>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white line-clamp-2 mb-2">
                            <a href="{{ route('blog.show', $rel->slug) }}" class="hover:text-rose-600 transition">{{ $rel->title }}</a>
                        </h4>
                        <span class="text-[11px] text-slate-500">📅 {{ $rel->created_at->format('M d, Y') }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<!-- Schema.org Article Structured Data for Google SEO -->
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "BlogPosting",
  "headline": "{{ addslashes($post->title) }}",
  "image": "{{ $post->cover_image ?: asset('images/og-default.jpg') }}",
  "author": {
    "@@type": "Organization",
    "name": "{{ addslashes($post->author_name) }}"
  },
  "publisher": {
    "@@type": "Organization",
    "name": "Manab Kalyane Rokto Dan"
  },
  "datePublished": "{{ $post->created_at->toIso8601String() }}",
  "description": "{{ addslashes($post->excerpt ?: substr(strip_tags($post->content), 0, 150)) }}"
}
</script>
@endsection

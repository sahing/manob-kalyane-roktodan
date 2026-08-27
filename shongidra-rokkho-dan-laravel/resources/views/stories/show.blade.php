@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{
    liked: {{ $story->user_has_liked ? 'true' : 'false' }},
    likesCount: {{ $story->likes_count }},
    sharesCount: {{ $story->shares_count }},
    copied: false,
    shareModalOpen: false,

    toggleLike() {
        fetch('{{ route('stories.like', $story->id) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                this.liked = data.liked;
                this.likesCount = data.likes_count;
            }
        });
    },

    trackShare() {
        fetch('{{ route('stories.share', $story->id) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                this.sharesCount = data.shares_count;
            }
        });
    },

    copyLink() {
        navigator.clipboard.writeText('{{ route('stories.show', $story->id) }}');
        this.copied = true;
        this.trackShare();
        setTimeout(() => this.copied = false, 3000);
    },

    nativeShare() {
        if (navigator.share) {
            navigator.share({
                title: '{{ addslashes($story->title) }}',
                text: 'Read this voluntary donor story from {{ addslashes($story->donor_name) }} on Manab Kalyane Rokto Dan',
                url: '{{ route('stories.show', $story->id) }}'
            }).then(() => this.trackShare()).catch(() => {});
        } else {
            this.shareModalOpen = true;
        }
    }
}">
    <!-- Navigation Bar -->
    <div class="flex items-center justify-between mb-8">
        <a href="{{ route('stories.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 dark:hover:text-white flex items-center gap-1">
            ← Back to Donor Stories
        </a>
        <div class="flex items-center gap-3">
            <!-- Dynamic Like Button -->
            <button @click="toggleLike()" class="px-4 py-2 rounded-xl text-xs font-extrabold transition shadow-md flex items-center gap-2"
                :class="liked ? 'bg-rose-600 text-white' : 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-rose-500 hover:text-white'">
                <span x-text="liked ? '❤️ Liked' : '🤍 Like'"></span>
                <span class="px-2 py-0.5 rounded-full text-[10px] bg-black/20" x-text="likesCount"></span>
            </button>

            <!-- Native Share / Share Modal Button -->
            <button @click="nativeShare()" class="px-4 py-2 rounded-xl text-xs font-extrabold bg-brand-600 text-white shadow-md hover:bg-brand-700 transition flex items-center gap-2">
                <span>🔗 Share Story</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] bg-black/20" x-text="sharesCount"></span>
            </button>
        </div>
    </div>

    <!-- Main Story Card -->
    <div class="glass-card rounded-3xl p-8 sm:p-12 shadow-2xl border border-slate-200 dark:border-slate-800 mb-12">
        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6 border-b border-slate-200 dark:border-slate-800 pb-6">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-rose-600 to-brand-700 text-white font-black text-xl flex items-center justify-center shadow-lg border border-rose-400/40">
                    {{ $story->blood_group ?: '🩸' }}
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight mb-1">
                        {{ $story->title }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500 font-semibold">
                        <span>By <strong>{{ $story->donor_name }}</strong></span>
                        <span>•</span>
                        <span>📍 {{ $story->location ?: 'Bhagwangola' }}</span>
                        <span>•</span>
                        <span>📅 {{ $story->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                Verified Story
            </span>
        </div>

        <!-- Featured Photo -->
        @if($story->photo_url)
            <div class="rounded-3xl overflow-hidden shadow-2xl mb-8 border border-slate-200 dark:border-slate-800">
                <img src="{{ $story->photo_url }}" alt="{{ $story->title }}" class="w-full max-h-[500px] object-cover">
            </div>
        @endif

        <!-- Story Content Body -->
        <div class="text-base text-slate-800 dark:text-slate-200 leading-relaxed space-y-4 font-normal mb-8">
            {!! nl2br(e($story->experience)) !!}
        </div>

        <!-- Share Options Toolbar -->
        <div class="p-6 rounded-2xl bg-slate-100 dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800">
            <span class="text-xs font-extrabold uppercase tracking-wider text-slate-700 dark:text-slate-300 block mb-3">
                Share this inspiring story on social media:
            </span>
            <div class="flex flex-wrap items-center gap-2.5">
                <!-- WhatsApp Share (Individual URL) -->
                <a href="https://api.whatsapp.com/send?text={{ urlencode($story->title . ' - Read here: ' . route('stories.show', $story->id)) }}" target="_blank" @click="trackShare()" class="px-3.5 py-2 rounded-xl text-xs font-extrabold bg-[#25D366] text-white hover:opacity-90 transition shadow-md flex items-center gap-1.5">
                    WhatsApp
                </a>

                <!-- Facebook Share (Individual URL) -->
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('stories.show', $story->id)) }}" target="_blank" @click="trackShare()" class="px-3.5 py-2 rounded-xl text-xs font-extrabold bg-[#1877F2] text-white hover:opacity-90 transition shadow-md flex items-center gap-1.5">
                    Facebook
                </a>

                <!-- Messenger Share -->
                <a href="fb-messenger://share?link={{ urlencode(route('stories.show', $story->id)) }}" target="_blank" @click="trackShare()" class="px-3.5 py-2 rounded-xl text-xs font-extrabold bg-[#0084FF] text-white hover:opacity-90 transition shadow-md flex items-center gap-1.5">
                    Messenger
                </a>

                <!-- Telegram Share -->
                <a href="https://t.me/share/url?url={{ urlencode(route('stories.show', $story->id)) }}&text={{ urlencode($story->title) }}" target="_blank" @click="trackShare()" class="px-3.5 py-2 rounded-xl text-xs font-extrabold bg-[#229ED9] text-white hover:opacity-90 transition shadow-md flex items-center gap-1.5">
                    Telegram
                </a>

                <!-- X / Twitter Share -->
                <a href="https://twitter.com/intent/tweet?text={{ urlencode($story->title) }}&url={{ urlencode(route('stories.show', $story->id)) }}" target="_blank" @click="trackShare()" class="px-3.5 py-2 rounded-xl text-xs font-extrabold bg-[#000000] text-white hover:opacity-90 transition shadow-md flex items-center gap-1.5">
                    X / Twitter
                </a>

                <!-- Copy Link Button -->
                <button @click="copyLink()" class="px-3.5 py-2 rounded-xl text-xs font-extrabold bg-slate-700 text-white hover:bg-slate-600 transition shadow-md flex items-center gap-1.5">
                    <span x-text="copied ? '✓ Copied!' : '📋 Copy Link'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- COMMENTS SECTION -->
    <div id="comments" class="glass-card rounded-3xl p-8 sm:p-10 shadow-xl border border-slate-200 dark:border-slate-800">
        <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-200 dark:border-slate-800">
            <h3 class="text-xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                💬 Comments
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-500/20 text-rose-600 dark:text-rose-400">
                    {{ $story->comments->count() }}
                </span>
            </h3>
        </div>

        <!-- Add Comment Form -->
        @auth
            <form action="{{ route('stories.comments.store', $story->id) }}" method="POST" class="mb-10">
                @csrf
                <div class="mb-3">
                    <label class="block text-xs font-bold uppercase text-slate-600 dark:text-slate-400 mb-2">Leave a Encouraging Comment as {{ Auth::user()->name }}</label>
                    <textarea name="comment" rows="3" required placeholder="Write a supportive comment..." class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-2xl p-4 text-sm font-medium text-slate-900 dark:text-white focus:outline-none focus:border-rose-500"></textarea>
                </div>
                <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-extrabold bg-rose-600 text-white hover:bg-rose-500 shadow-md transition">
                    Post Comment 💬
                </button>
            </form>
        @else
            <div class="p-6 rounded-2xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-center mb-10">
                <p class="text-xs font-bold text-slate-600 dark:text-slate-400 mb-3">Please log in to leave a comment on this story.</p>
                <a href="{{ route('login') }}" class="inline-block px-5 py-2 rounded-xl text-xs font-extrabold bg-rose-600 text-white shadow-md hover:bg-rose-500">
                    Log In to Comment
                </a>
            </div>
        @endauth

        <!-- Comments Feed -->
        <div class="space-y-6">
            @forelse($story->comments as $comment)
                <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80" x-data="{ editing: false }">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-full bg-rose-600 text-white flex items-center justify-center font-bold text-xs">
                                {{ strtoupper(substr($comment->user_name, 0, 1)) }}
                            </div>
                            <div>
                                <span class="text-xs font-extrabold text-slate-900 dark:text-white block leading-none">{{ $comment->user_name }}</span>
                                <span class="text-[10px] text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <!-- Author/Admin Actions -->
                        @if(Auth::id() === $comment->user_id || (Auth::check() && Auth::user()->isAdmin()))
                            <div class="flex items-center gap-2">
                                @if(Auth::id() === $comment->user_id)
                                    <button @click="editing = !editing" class="text-[11px] font-bold text-slate-400 hover:text-slate-200">
                                        ✏️ Edit
                                    </button>
                                @endif
                                <form action="{{ route('stories.comments.delete', $comment->id) }}" method="POST" onsubmit="return confirm('Delete this comment?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[11px] font-bold text-rose-500 hover:text-rose-400">
                                        🗑️ Delete
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <!-- Comment Body / Edit View -->
                    <div x-show="!editing">
                        <p class="text-xs text-slate-700 dark:text-slate-300 font-normal leading-relaxed pl-10">
                            {{ $comment->comment }}
                        </p>
                    </div>

                    @if(Auth::id() === $comment->user_id)
                        <div x-show="editing" x-cloak class="pl-10 mt-3">
                            <form action="{{ route('stories.comments.update', $comment->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <textarea name="comment" rows="2" required class="w-full bg-slate-100 dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-xl p-3 text-xs text-slate-900 dark:text-white mb-2">{{ $comment->comment }}</textarea>
                                <div class="flex gap-2">
                                    <button type="submit" class="px-3 py-1 rounded-lg text-xs font-bold bg-emerald-600 text-white">Save</button>
                                    <button type="button" @click="editing = false" class="px-3 py-1 rounded-lg text-xs font-bold bg-slate-700 text-white">Cancel</button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-center text-xs text-slate-500 py-6">No comments yet. Be the first to encourage {{ $story->donor_name }}!</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Donor Stories & Experiences — Manab Kalyane Rokto Dan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ shareModal: false }">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 pb-8 border-b border-slate-800">
        <div>
            <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-rose-500/20 text-rose-300 border border-rose-500/30 mb-3">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                Community Voices & Inspiration
            </span>
            <h1 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight">Donor Stories & Experiences</h1>
            <p class="text-sm text-slate-400 mt-2 max-w-2xl">Read real stories from voluntary blood donors across Bhagwangola & Murshidabad who give the gift of life.</p>
        </div>

        <div>
            <button @click="shareModal = true" class="px-6 py-3.5 rounded-xl font-extrabold text-sm bg-gradient-to-r from-brand-600 to-rose-600 text-white shadow-xl glow-red hover:opacity-95 transition flex items-center space-x-2 transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                <span>Share Your Experience & Photo</span>
            </button>
        </div>
    </div>

    <!-- Stories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($stories as $story)
            <div class="glass-card rounded-2xl border border-slate-800/80 overflow-hidden hover:border-rose-900/60 transition shadow-xl flex flex-col justify-between group">
                <div>
                    <!-- Optional Donor Photo -->
                    @if($story->photo_url)
                        <div class="relative h-56 overflow-hidden bg-slate-900">
                            <img src="{{ $story->photo_url }}" alt="{{ $story->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent"></div>
                            @if($story->blood_group)
                                <span class="absolute top-3 right-3 px-3 py-1 rounded-xl text-xs font-black bg-rose-600 text-white shadow-md border border-rose-500/40">
                                    {{ $story->blood_group }}
                                </span>
                            @endif
                        </div>
                    @endif

                    <div class="p-6">
                        <div class="flex items-center space-x-3 mb-3">
                            @if(!$story->photo_url)
                                <div class="w-11 h-11 rounded-xl bg-rose-600/20 border border-rose-500/30 text-rose-400 font-extrabold text-base flex items-center justify-center shadow-md">
                                    {{ $story->blood_group ?? '🩸' }}
                                </div>
                            @endif
                            <div>
                                <h3 class="text-sm font-bold text-white">{{ $story->donor_name }}</h3>
                                <span class="text-[11px] text-slate-400 flex items-center gap-1">
                                    📍 {{ $story->location ?? 'Bhagwangola' }}
                                </span>
                            </div>
                        </div>

                        <h2 class="text-base font-extrabold text-white mb-2 group-hover:text-rose-400 transition-colors">
                            "{{ $story->title }}"
                        </h2>
                        
                        <p class="text-xs text-slate-300 leading-relaxed bg-slate-900/60 p-4 rounded-xl border border-slate-800/60 whitespace-pre-line">
                            {{ $story->experience }}
                        </p>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-800/80 bg-slate-950/40 flex items-center justify-between text-[11px] text-slate-500">
                    <span>Shared {{ $story->created_at->diffForHumans() }}</span>
                    <span class="text-rose-400 font-bold">♥ Verified Donor</span>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center p-12 glass-card rounded-2xl text-slate-400">
                <div class="w-14 h-14 rounded-2xl bg-rose-500/10 text-rose-400 border border-rose-500/20 flex items-center justify-center mx-auto mb-3 text-2xl font-bold">
                    ✍️
                </div>
                <h3 class="text-lg font-bold text-white mb-1">No Donor Stories Shared Yet</h3>
                <p class="text-xs text-slate-400 mb-4">Be the first voluntary donor to share your donation story and inspire others!</p>
                <button @click="shareModal = true" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-500 text-white transition">
                    Share Your Story Now
                </button>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $stories->links() }}
    </div>

    <!-- Share Experience Modal -->
    <div x-show="shareModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
        <div class="glass-card max-w-lg w-full p-6 sm:p-8 rounded-2xl border border-slate-800 relative shadow-2xl overflow-y-auto max-h-[90vh]">
            <button @click="shareModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-white text-2xl font-bold">&times;</button>

            <h3 class="text-xl font-extrabold text-white mb-1 flex items-center gap-2">
                <span class="text-rose-500">✍️</span> Share Your Donation Experience
            </h3>
            <p class="text-xs text-slate-400 mb-6">Inspire community members in Bhagwangola & Murshidabad to become voluntary donors.</p>

            <form action="{{ route('stories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1 uppercase">Your Name</label>
                        <input type="text" name="donor_name" value="{{ Auth::user()?->name }}" required placeholder="e.g. Tariqul Islam" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1 uppercase">Blood Group</label>
                        <select name="blood_group" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-rose-500 font-bold">
                            <option value="">Select Blood Group</option>
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $g)
                                <option value="{{ $g }}" {{ Auth::user()?->donorProfile?->blood_group === $g ? 'selected' : '' }}>{{ $g }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1 uppercase">Story Headline / Title</label>
                    <input type="text" name="title" required placeholder="e.g. My 5th Blood Donation Camp at Bhagwangola" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-rose-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1 uppercase">Location / Village</label>
                    <input type="text" name="location" placeholder="e.g. Bhagwangola / Murshidabad Medical College" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-rose-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1 uppercase">Your Experience / Message</label>
                    <textarea name="experience" rows="4" required placeholder="Share how you felt, why you donate blood, or how easy the process was..." class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-rose-500"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1 uppercase">Upload Photo (Optional)</label>
                    <input type="file" name="photo_file" accept="image/*" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-300 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-600 file:text-white hover:file:bg-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1 uppercase">OR Image URL</label>
                    <input type="url" name="photo_url" placeholder="https://images.unsplash.com/..." class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-rose-500">
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-brand-600 to-rose-600 text-white font-extrabold py-3.5 rounded-xl hover:opacity-95 shadow-xl glow-red transition">
                    Publish My Donation Experience 🩸
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

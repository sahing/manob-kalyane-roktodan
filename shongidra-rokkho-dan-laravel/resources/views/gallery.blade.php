@extends('layouts.app')

@section('title', 'Photo Gallery & Blood Donation Camps — Manab Kalyane Rokto Dan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ lightbox: false, activeImg: '', activeTitle: '', addGalleryModal: false, uploadType: 'url' }">
    
    <!-- Top Bar Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Photo Gallery</h1>
            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Snapshots from voluntary blood donation drives, camps, and community events.</p>
        </div>

        <div class="flex items-center gap-3">
            <!-- Category Pills -->
            <div class="flex items-center space-x-1.5 bg-slate-200 dark:bg-slate-900 p-1.5 rounded-xl border border-slate-300 dark:border-slate-800 text-xs overflow-x-auto">
                <a href="{{ route('gallery') }}" class="px-3 py-1.5 rounded-lg font-bold whitespace-nowrap {{ empty($category) ? 'bg-rose-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">All</a>
                @foreach($categories as $cat)
                    <a href="{{ route('gallery', ['category' => $cat]) }}" class="px-3 py-1.5 rounded-lg font-bold capitalize whitespace-nowrap {{ $category === $cat ? 'bg-rose-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">{{ $cat }}</a>
                @endforeach
            </div>

            <!-- ADD GALLERY PHOTO CARD BUTTON -->
            @auth
                <button @click="addGalleryModal = true" class="px-4 py-2.5 rounded-xl text-xs font-extrabold bg-gradient-to-r from-brand-600 to-rose-600 text-white shadow-lg glow-red hover:opacity-95 transition flex items-center gap-1.5 shrink-0">
                    <svg class="w-4 h-4 fill-none stroke-current" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    <span>+ Add Photo Card</span>
                </button>
            @else
                <button @click="authModal = true; authMode = 'login'" class="px-4 py-2.5 rounded-xl text-xs font-extrabold bg-gradient-to-r from-brand-600 to-rose-600 text-white shadow-lg glow-red hover:opacity-95 transition flex items-center gap-1.5 shrink-0">
                    <svg class="w-4 h-4 fill-none stroke-current" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    <span>+ Add Photo Card</span>
                </button>
            @endauth
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @forelse($items as $item)
            <div class="glass-card rounded-2xl overflow-hidden group cursor-pointer hover:border-rose-500/50 transition duration-300 shadow-lg relative flex flex-col justify-between">
                <div @click="lightbox = true; activeImg = '{{ $item->image_url }}'; activeTitle = '{{ addslashes($item->title) }}'">
                    <div class="relative aspect-video overflow-hidden bg-slate-900">
                        <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-slate-950/80 text-rose-300 backdrop-blur-sm border border-slate-800">
                            {{ $item->category }}
                        </span>
                        @if(Auth::check() && Auth::user()->isAdmin())
                            <form action="{{ route('admin.gallery.delete', $item->id) }}" method="POST" class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition duration-200" @click.stop>
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Remove photo card?')" class="p-1.5 rounded-xl bg-rose-600/90 hover:bg-rose-600 text-white text-xs font-bold shadow-md">
                                    🗑️ Delete
                                </button>
                            </form>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-sm font-extrabold text-slate-900 dark:text-white group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">{{ $item->title }}</h3>
                        @if($item->description)
                            <p class="text-xs text-slate-600 dark:text-slate-400 mt-1 line-clamp-2">{{ $item->description }}</p>
                        @endif
                        @if($item->event_date)
                            <span class="text-[11px] text-slate-500 dark:text-slate-400 block mt-2 font-medium">📅 {{ $item->event_date->format('d M Y') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center p-12 glass-card rounded-2xl text-slate-500 dark:text-slate-400 text-sm">
                No photo cards in gallery yet. Be the first to add one!
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $items->links() }}
    </div>

    <!-- ADD GALLERY PHOTO CARD MODAL -->
    <div x-show="addGalleryModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
        <div class="glass-card max-w-lg w-full p-6 sm:p-8 rounded-3xl border border-slate-300 dark:border-slate-800 relative shadow-2xl overflow-y-auto max-h-[90vh]">
            <button @click="addGalleryModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-900 dark:hover:text-white text-2xl font-bold">&times;</button>

            <div class="mb-6">
                <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">Add New Photo Card</h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Upload a photo or provide an image link from blood donation camps & awareness drives.</p>
            </div>

            <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Photo Title *</label>
                    <input type="text" name="title" required placeholder="e.g. Bhagwangola Voluntary Blood Drive 2026" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Category *</label>
                        <select name="category" required class="searchable-select w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-bold" data-searchable="true">
                            <option value="camps">Blood Camps</option>
                            <option value="awareness">Awareness</option>
                            <option value="events">Events & Meetings</option>
                            <option value="donors">Donor Felicitation</option>
                            <option value="community">Community Service</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Event Date</label>
                        <input type="date" name="event_date" value="{{ date('Y-m-d') }}" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                    </div>
                </div>

                <!-- Input Type Toggle (URL vs File Upload) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Image Source</label>
                    <div class="flex items-center p-1 rounded-xl bg-slate-200 dark:bg-slate-900 border border-slate-300 dark:border-slate-800 mb-3">
                        <button type="button" @click="uploadType = 'url'" :class="uploadType === 'url' ? 'bg-rose-600 text-white font-extrabold shadow' : 'text-slate-600 dark:text-slate-400 font-bold'" class="w-1/2 py-1.5 rounded-lg text-xs transition">
                            Direct Image URL
                        </button>
                        <button type="button" @click="uploadType = 'file'" :class="uploadType === 'file' ? 'bg-rose-600 text-white font-extrabold shadow' : 'text-slate-600 dark:text-slate-400 font-bold'" class="w-1/2 py-1.5 rounded-lg text-xs transition">
                            Upload Image File
                        </button>
                    </div>

                    <div x-show="uploadType === 'url'">
                        <input type="url" name="image_url" placeholder="https://example.com/photo.jpg" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                    </div>
                    <div x-show="uploadType === 'file'">
                        <input type="file" name="image_file" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-600 file:text-white hover:file:bg-rose-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Description (Optional)</label>
                    <textarea name="description" rows="2" placeholder="Brief details about the camp or photo card..." class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500"></textarea>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-brand-600 to-rose-600 text-white font-extrabold py-3 rounded-xl hover:opacity-95 shadow-lg transition glow-red">
                    Publish Photo Card 📸
                </button>
            </form>
        </div>
    </div>

    <!-- Lightbox Modal -->
    <div x-show="lightbox" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/90 backdrop-blur-md">
        <button @click="lightbox = false" class="absolute top-6 right-6 text-white text-3xl font-bold hover:text-rose-400">&times;</button>
        <div class="max-w-4xl w-full text-center">
            <img :src="activeImg" class="max-h-[80vh] mx-auto rounded-2xl shadow-2xl border border-slate-800">
            <h4 class="text-lg font-bold text-white mt-4" x-text="activeTitle"></h4>
        </div>
    </div>
</div>
@endsection

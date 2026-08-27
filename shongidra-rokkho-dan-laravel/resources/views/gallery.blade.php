@extends('layouts.app')

@section('title', 'Photo Gallery & Blood Donation Camps — Manab Kalyane Rokto Dan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ lightbox: false, activeImg: '', activeTitle: '' }">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Photo Gallery</h1>
            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Snapshots from our voluntary blood donation drives and community awareness events.</p>
        </div>

        <div class="flex items-center space-x-2 bg-slate-200 dark:bg-slate-900 p-1.5 rounded-xl border border-slate-300 dark:border-slate-800 text-xs">
            <a href="{{ route('gallery') }}" class="px-3 py-1.5 rounded-lg font-semibold {{ empty($category) ? 'bg-brand-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">All</a>
            @foreach($categories as $cat)
                <a href="{{ route('gallery', ['category' => $cat]) }}" class="px-3 py-1.5 rounded-lg font-semibold capitalize {{ $category === $cat ? 'bg-brand-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">{{ $cat }}</a>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @forelse($items as $item)
            <div @click="lightbox = true; activeImg = '{{ $item->image_url }}'; activeTitle = '{{ addslashes($item->title) }}'" class="glass-card rounded-2xl overflow-hidden group cursor-pointer hover:border-brand-600/50 transition shadow-lg">
                <div class="relative aspect-video overflow-hidden bg-slate-900">
                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-slate-950/80 text-rose-300 backdrop-blur-sm border border-slate-800">
                        {{ $item->category }}
                    </span>
                </div>
                <div class="p-4">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">{{ $item->title }}</h3>
                    @if($item->event_date)
                        <span class="text-[11px] text-slate-500 block mt-1">{{ $item->event_date->format('d M Y') }}</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center p-12 glass-card rounded-2xl text-slate-500 dark:text-slate-400 text-sm">
                No photos in gallery.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $items->links() }}
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

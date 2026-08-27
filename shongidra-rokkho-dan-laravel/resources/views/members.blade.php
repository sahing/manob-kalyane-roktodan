@extends('layouts.app')

@section('title', 'Committee Members — Manab Kalyane Rokto Dan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="text-center max-w-2xl mx-auto mb-12">
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Our Governing Committee</h1>
        <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">Dedicated organizers leading the voluntary blood donation network in Bhagwangola, Murshidabad.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($members as $m)
            <div class="glass-card p-6 rounded-2xl text-center hover:border-brand-600/40 transition flex flex-col justify-between">
                <div>
                    <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-tr from-brand-700 to-rose-500 text-white font-extrabold text-3xl flex items-center justify-center mb-4 border-4 border-slate-200 dark:border-slate-800 shadow-lg glow-red">
                        {{ strtoupper(substr($m->name, 0, 1)) }}
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-0.5">{{ $m->name }}</h3>
                    <div class="text-xs font-semibold text-rose-600 dark:text-rose-400 uppercase tracking-wider mb-3">{{ $m->role_title }}</div>

                    @if($m->bio)
                        <p class="text-xs text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-900/60 p-3 rounded-xl border border-slate-200 dark:border-slate-800 mb-4">
                            {{ $m->bio }}
                        </p>
                    @endif
                </div>

                @if($m->phone)
                    <div class="pt-3 border-t border-slate-200 dark:border-slate-800/80">
                        <a href="tel:{{ $m->phone }}" class="text-xs font-bold text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white flex items-center justify-center gap-1">
                            <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            {{ $m->phone }}
                        </a>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection

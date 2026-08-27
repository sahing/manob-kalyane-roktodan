@extends('layouts.app')

@section('title', $page->meta_title ?: $page->title . ' — Manab Kalyane Rokto Dan')
@section('meta_description', $page->meta_description)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="glass-card p-6 sm:p-10 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-800">
        @if($page->featured_image)
            <img src="{{ $page->featured_image }}" alt="{{ $page->title }}" class="w-full h-64 object-cover rounded-2xl mb-8 shadow-md">
        @endif

        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-6">{{ $page->title }}</h1>
        
        <div class="prose dark:prose-invert max-w-none text-slate-700 dark:text-slate-300 leading-relaxed text-sm sm:text-base space-y-4">
            {!! $page->content !!}
        </div>
    </div>
</div>
@endsection

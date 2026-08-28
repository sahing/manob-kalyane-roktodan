@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto px-4 py-20 text-center">
    <div class="glass-card p-8 sm:p-12 rounded-3xl shadow-2xl border border-rose-500/30">
        <div class="w-16 h-16 rounded-full bg-rose-500/20 text-rose-600 dark:text-rose-400 flex items-center justify-center font-black text-3xl mx-auto mb-4">
            ✕
        </div>
        <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white mb-2">Record Not Found</h2>
        <p class="text-xs text-slate-600 dark:text-slate-400 mb-6 leading-relaxed">
            No active donor record or certificate was found matching the code: <strong class="font-mono text-rose-600 dark:text-rose-400 text-sm">{{ $code }}</strong>.
        </p>

        <div class="space-y-3">
            <a href="{{ route('verify.index') }}" class="block w-full py-3 rounded-xl text-xs font-bold bg-rose-600 text-white shadow-lg hover:bg-rose-500 transition">
                Try Another Search Code
            </a>
            <a href="{{ route('home') }}" class="block text-xs font-bold text-slate-500 hover:underline">
                Return to Homepage
            </a>
        </div>
    </div>
</div>
@endsection

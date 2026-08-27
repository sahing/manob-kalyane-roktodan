<!DOCTYPE html>
<html lang="en" class="scroll-smooth" x-data="{ darkMode: localStorage.getItem('theme') === 'light' ? false : (localStorage.getItem('theme') === 'dark' ? true : true) }" :class="{ 'dark': darkMode }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($seo['title']) ? $seo['title'] : View::yieldContent('title', 'Manab Kalyane Rokto Dan — Bhagwangola Voluntary Blood Service') }}</title>
    <meta name="description" content="{{ isset($seo['description']) ? $seo['description'] : 'Bhagwangola voluntary blood donation network. Search donors by blood group, post emergency requests, and save lives in Murshidabad.' }}">
    <meta name="keywords" content="{{ isset($seo['keywords']) ? $seo['keywords'] : 'blood donation, Bhagwangola blood donor, Murshidabad voluntary blood, emergency blood request' }}">
    @if(isset($seo['canonical']))
        <link rel="canonical" href="{{ $seo['canonical'] }}">
    @endif

    <!-- OpenGraph & Social Media SEO -->
    <meta property="og:site_name" content="Manab Kalyane Rokto Dan">
    <meta property="og:title" content="{{ isset($seo['title']) ? $seo['title'] : View::yieldContent('title') }}">
    <meta property="og:description" content="{{ isset($seo['description']) ? $seo['description'] : '' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @if(isset($seo['og_image']))
        <meta property="og:image" content="{{ $seo['og_image'] }}">
    @endif
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@500;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        heading: ['"Outfit"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#fff1f2',
                            100: '#ffe4e6',
                            500: '#f43f5e',
                            600: '#e11d48',
                            700: '#be123c',
                            800: '#9f1239',
                            900: '#881337',
                            950: '#4c0519',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }

        /* Smooth Custom Animations */
        @keyframes float-slow {
            0%, 100% { transform: translateY(0) translateX(0); }
            50% { transform: translateY(-18px) translateX(8px); }
        }
        @keyframes drift {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(12px, -10px) rotate(6deg); }
            66% { transform: translate(-10px, 8px) rotate(-4deg); }
        }
        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            15% { transform: scale(1.15); }
            30% { transform: scale(1); }
            45% { transform: scale(1.1); }
            60% { transform: scale(1); }
        }
        @keyframes drip {
            0% { transform: translateY(-6px); opacity: 0; }
            20% { opacity: 1; }
            100% { transform: translateY(28px); opacity: 0; }
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        @keyframes blob {
            0%, 100% { border-radius: 42% 58% 63% 37% / 45% 38% 62% 55%; }
            50% { border-radius: 58% 42% 37% 63% / 55% 62% 38% 45%; }
        }

        .animate-float { animation: float-slow 7s ease-in-out infinite; }
        .animate-drift { animation: drift 11s ease-in-out infinite; }
        .animate-heartbeat { animation: heartbeat 1.6s ease-in-out infinite; transform-origin: center; }
        .animate-blob { animation: blob 9s ease-in-out infinite; }

        .drip-droplet {
            position: absolute;
            width: 10px;
            height: 14px;
            background: #e11d48;
            border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
            filter: drop-shadow(0 2px 4px rgba(225, 29, 72, 0.5));
            opacity: 0;
            animation: drip 2.6s ease-in infinite;
        }
        .drip-droplet::before {
            content: "";
            position: absolute;
            left: 50%;
            top: -6px;
            width: 6px;
            height: 8px;
            transform: translateX(-50%);
            background: #e11d48;
            clip-path: polygon(50% 0, 100% 100%, 0 100%);
        }

        .shimmer-text {
            background: linear-gradient(90deg, #ffffff 0%, #f43f5e 50%, #ffffff 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: shimmer 4s linear infinite;
        }

        .ornament-blob {
            position: absolute;
            background: radial-gradient(circle at 30% 30%, rgba(225, 29, 72, 0.25), transparent 70%);
            filter: blur(40px);
            pointer-events: none;
            z-index: 0;
        }

        /* Responsive Light & Dark Mode Utility Styles */
        .glass-nav {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }
        .dark .glass-nav {
            background: rgba(15, 23, 42, 0.92);
            border-bottom: 1px solid rgba(30, 41, 59, 0.8);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }
        .dark .glass-card {
            background: rgba(30, 41, 59, 0.65);
            border: 1px solid rgba(51, 65, 85, 0.8);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        }

        .glow-red {
            box-shadow: 0 0 25px -5px rgba(225, 29, 72, 0.4);
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-sans antialiased min-h-screen flex flex-col selection:bg-brand-600 selection:text-white transition-colors duration-300" x-data="{ authModal: false, authMode: 'login', shareOpen: false }">

    <!-- Top Helpline Bar -->
    <div class="bg-gradient-to-r from-rose-900 via-slate-900 to-rose-950 dark:from-brand-950 dark:via-slate-900 dark:to-brand-950 text-white text-xs py-2 px-4 shadow-sm">
        <div class="max-w-7xl mx-auto flex justify-between items-center text-slate-100">
            <div class="flex items-center space-x-4">
                <span class="flex items-center space-x-1 font-semibold">
                    <svg class="w-3.5 h-3.5 text-rose-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    <span>Bhagwangola 24/7 Helpline: <a href="tel:+919832100000" class="text-white hover:text-rose-300 font-extrabold">+91 98321 00000</a></span>
                </span>
                <span class="hidden sm:inline-block text-slate-400">|</span>
                <span class="hidden sm:inline-flex items-center space-x-1">
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>Murshidabad, West Bengal</span>
                </span>
            </div>

            <div class="flex items-center space-x-3">
                <a href="https://wa.me/919832100000?text=Emergency%20Blood%20Help" target="_blank" class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 hover:bg-emerald-500/30 transition text-[11px] font-semibold">
                    <span>WhatsApp Helpline</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-40 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-700 via-rose-600 to-rose-500 text-white flex items-center justify-center shadow-lg group-hover:scale-105 transition duration-300 glow-red">
                        <svg class="w-6 h-6 fill-current animate-heartbeat" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path></svg>
                    </div>
                    <div>
                        <span class="font-heading font-extrabold text-lg tracking-tight text-slate-900 dark:text-white group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors block leading-tight">
                            Manab Kalyane Rokto Dan
                        </span>
                        <span class="text-[10px] uppercase font-bold tracking-widest text-rose-600 dark:text-rose-400 block">
                            Bhagwangola Voluntary Society
                        </span>
                    </div>
                </a>

                <!-- Desktop Nav Links -->
                <nav class="hidden lg:flex items-center space-x-1 text-sm font-semibold text-slate-700 dark:text-slate-300">
                    <a href="{{ route('home') }}" class="px-3.5 py-2 rounded-xl transition hover:text-slate-900 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-800/60 {{ request()->routeIs('home') ? 'text-rose-600 dark:text-white bg-rose-50 dark:bg-slate-800/80 font-bold' : '' }}">Home</a>
                    <a href="{{ route('donors.search') }}" class="px-3.5 py-2 rounded-xl transition hover:text-slate-900 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-800/60 {{ request()->routeIs('donors.search') ? 'text-rose-600 dark:text-white bg-rose-50 dark:bg-slate-800/80 font-bold' : '' }}">Find Donors</a>
                    <a href="{{ route('requests.index') }}" class="px-3.5 py-2 rounded-xl transition hover:text-slate-900 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-800/60 {{ request()->routeIs('requests.index') ? 'text-rose-600 dark:text-white bg-rose-50 dark:bg-slate-800/80 font-bold' : '' }}">Blood Requests</a>
                    <a href="{{ route('blog.index') }}" class="px-3.5 py-2 rounded-xl transition hover:text-slate-900 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-800/60 {{ request()->routeIs('blog.*') ? 'text-rose-600 dark:text-white bg-rose-50 dark:bg-slate-800/80 font-bold' : '' }}">Blog & Health</a>
                    <a href="{{ route('stories.index') }}" class="px-3.5 py-2 rounded-xl transition hover:text-slate-900 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-800/60 {{ request()->routeIs('stories.index') ? 'text-rose-600 dark:text-white bg-rose-50 dark:bg-slate-800/80 font-bold' : '' }}">Donor Stories</a>
                    <a href="{{ route('donate') }}" class="px-3.5 py-2 rounded-xl transition hover:text-slate-900 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-800/60 {{ request()->routeIs('donate') ? 'text-rose-600 dark:text-white bg-rose-50 dark:bg-slate-800/80 font-bold' : '' }}">Donate / Pledge</a>
                    <a href="{{ route('members') }}" class="px-3.5 py-2 rounded-xl transition hover:text-slate-900 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-800/60 {{ request()->routeIs('members') ? 'text-rose-600 dark:text-white bg-rose-50 dark:bg-slate-800/80 font-bold' : '' }}">Committee</a>
                    <a href="{{ route('gallery') }}" class="px-3.5 py-2 rounded-xl transition hover:text-slate-900 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-800/60 {{ request()->routeIs('gallery') ? 'text-rose-600 dark:text-white bg-rose-50 dark:bg-slate-800/80 font-bold' : '' }}">Gallery</a>
                </nav>

                <!-- Auth & Theme Toggle Buttons -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <!-- Light / Dark Theme Switch Button -->
                    <button @click="darkMode = !darkMode" type="button" class="p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-800/90 hover:bg-slate-200 dark:hover:bg-slate-700 transition flex items-center gap-1.5 text-xs font-bold shadow-sm" title="Toggle Light or Dark Theme">
                        <template x-if="darkMode">
                            <span class="flex items-center gap-1.5 text-amber-300">
                                <span>☀️</span> <span class="hidden md:inline text-xs font-bold text-amber-200">Light</span>
                            </span>
                        </template>
                        <template x-if="!darkMode">
                            <span class="flex items-center gap-1.5 text-slate-800">
                                <span>🌙</span> <span class="hidden md:inline text-xs font-bold text-slate-800">Dark</span>
                            </span>
                        </template>
                    </button>

                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-xl text-xs font-bold bg-amber-500/20 text-amber-700 dark:text-amber-300 border border-amber-500/40 hover:bg-amber-500/30 transition flex items-center gap-1">
                                ⚙️ <span class="hidden sm:inline">Admin</span>
                            </a>
                        @endif
                        <a href="{{ route('dashboard') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-200 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-300 dark:border-slate-700 hover:bg-slate-300 dark:hover:bg-slate-700 transition">
                            Dashboard
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-2 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition">
                                Logout
                            </button>
                        </form>
                    @else
                        <button @click="authModal = true; authMode = 'login'" class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition">
                            Login
                        </button>
                        <button @click="authModal = true; authMode = 'signup'" class="px-4 py-2 rounded-xl text-xs font-extrabold bg-gradient-to-r from-brand-600 to-rose-600 text-white shadow-md hover:opacity-95 transition">
                            Register Donor
                        </button>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Alerts -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-emerald-500/20 border border-emerald-500/40 text-emerald-800 dark:text-emerald-300 px-4 py-3 rounded-xl text-xs font-semibold flex items-center justify-between shadow-lg">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:text-slate-900 font-bold">&times;</button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-rose-500/20 border border-rose-500/40 text-rose-800 dark:text-rose-300 px-4 py-3 rounded-xl text-xs font-semibold flex items-center justify-between shadow-lg">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="text-rose-600 dark:text-rose-400 hover:text-slate-900 font-bold">&times;</button>
            </div>
        </div>
    @endif

    <!-- Page Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Floating Actions Widget (WhatsApp & Expandable Share Menu) -->
    <div class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-3">
        <!-- Expanded Share Menu -->
        <div x-show="shareOpen" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" class="flex flex-col items-end gap-2 mb-2">
            <a href="https://api.whatsapp.com/send?text={{ urlencode('Manab Kalyane Rokto Dan — Bhagwangola Voluntary Blood Platform ') }}{{ urlencode(url()->current()) }}" target="_blank" class="flex items-center gap-2 rounded-full px-3.5 py-2 text-xs font-bold text-white bg-[#25D366] hover:bg-[#1ebe5b] shadow-lg transition transform hover:scale-105">
                <span>WhatsApp</span>
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="flex items-center gap-2 rounded-full px-3.5 py-2 text-xs font-bold text-white bg-[#1877F2] hover:bg-[#166fe5] shadow-lg transition transform hover:scale-105">
                <span>Facebook</span>
            </a>
            <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}&text={{ urlencode('Manab Kalyane Rokto Dan') }}" target="_blank" class="flex items-center gap-2 rounded-full px-3.5 py-2 text-xs font-bold text-white bg-[#26A5E4] hover:bg-[#1e97d4] shadow-lg transition transform hover:scale-105">
                <span>Telegram</span>
            </a>
        </div>

        <!-- Share Toggle Button -->
        <button @click="shareOpen = !shareOpen" class="flex h-12 w-12 items-center justify-center rounded-full bg-white dark:bg-slate-800 text-rose-600 dark:text-rose-400 border border-slate-300 dark:border-slate-700 shadow-xl transition-all duration-300 hover:scale-110 hover:bg-slate-100 dark:hover:bg-slate-700">
            <span x-show="!shareOpen" class="text-lg font-bold">🔗</span>
            <span x-show="shareOpen" x-cloak class="text-lg font-bold">&times;</span>
        </button>

        <!-- Direct WhatsApp Chat Floating Button -->
        <a href="https://wa.me/919832100000?text=Emergency%20Blood%20Request" target="_blank" class="flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] text-white shadow-2xl transition-all duration-300 hover:scale-110 hover:shadow-emerald-500/50 glow-red">
            <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-1.002 3.66 3.745-.983z"/></svg>
        </a>
    </div>

    <!-- Auth Dialog Modal -->
    <div x-show="authModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
        <div class="glass-card max-w-md w-full p-8 rounded-3xl border border-slate-300 dark:border-slate-800 relative shadow-2xl">
            <button @click="authModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-900 dark:hover:text-white text-2xl font-bold">&times;</button>

            <!-- Modal Header -->
            <div class="text-center mb-6">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-brand-700 to-rose-500 text-white flex items-center justify-center mx-auto mb-3 shadow-lg glow-red">
                    <svg class="w-7 h-7 fill-current animate-heartbeat" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path></svg>
                </div>
                <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white" x-text="authMode === 'login' ? 'Sign In to Account' : 'Register as Voluntary Donor'"></h3>
            </div>

            <!-- Login Form -->
            <template x-if="authMode === 'login'">
                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Email Address</label>
                        <input type="email" name="email" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase">Password</label>
                        <input type="password" name="password" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-brand-600 to-rose-600 text-white font-bold py-3 rounded-xl hover:opacity-95 shadow-md transition">
                        Sign In
                    </button>
                    <div class="text-center text-xs text-slate-500 dark:text-slate-400 mt-4">
                        Don't have an account? <button type="button" @click="authMode = 'signup'" class="text-rose-600 dark:text-rose-400 font-bold hover:underline">Register Now</button>
                    </div>
                </form>
            </template>

            <!-- Signup Form -->
            <template x-if="authMode === 'signup'">
                <form action="{{ route('register') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Full Name</label>
                        <input type="text" name="name" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Email</label>
                            <input type="email" name="email" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Phone</label>
                            <input type="tel" name="phone" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Blood Group</label>
                            <select name="blood_group" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $g)
                                    <option value="{{ $g }}">{{ $g }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Block</label>
                            <select name="block" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                                <option value="Bhagwangola-I">Bhagwangola-I</option>
                                <option value="Bhagwangola-II">Bhagwangola-II</option>
                                <option value="Lalgola">Lalgola</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Password</label>
                            <input type="password" name="password" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Confirm</label>
                            <input type="password" name="password_confirmation" required class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-brand-600 to-rose-600 text-white font-bold py-3 rounded-xl hover:opacity-95 shadow-md transition">
                        Create Voluntary Account
                    </button>
                    <div class="text-center text-xs text-slate-500 dark:text-slate-400 mt-3">
                        Already registered? <button type="button" @click="authMode = 'login'" class="text-rose-600 dark:text-rose-400 font-bold hover:underline">Sign In</button>
                    </div>
                </form>
            </template>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-slate-900 dark:bg-slate-950 border-t border-slate-200 dark:border-slate-800 text-slate-400 py-12 text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="space-y-4">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-lg bg-rose-600 text-white flex items-center justify-center font-bold">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path></svg>
                    </div>
                    <span class="font-bold text-white text-base">Manab Kalyane Rokto Dan</span>
                </div>
                <p class="text-xs leading-relaxed text-slate-400">
                    Voluntary Blood Donor Network serving Bhagwangola-I, Bhagwangola-II, Lalgola & Murshidabad District. Saving lives 24/7.
                </p>
            </div>

            <div>
                <h4 class="font-bold text-white mb-3 text-xs uppercase tracking-wider">Quick Navigation</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('donors.search') }}" class="hover:text-rose-400 transition">Search Blood Donors</a></li>
                    <li><a href="{{ route('requests.index') }}" class="hover:text-rose-400 transition">Emergency Requests</a></li>
                    <li><a href="{{ route('blog.index') }}" class="hover:text-rose-400 transition">Blog & Awareness</a></li>
                    <li><a href="{{ route('stories.index') }}" class="hover:text-rose-400 transition">Donor Stories & Experiences</a></li>
                    <li><a href="{{ route('donate') }}" class="hover:text-rose-400 transition">Donate / Support Us</a></li>
                    <li><a href="{{ route('members') }}" class="hover:text-rose-400 transition">Board Members</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-white mb-3 text-xs uppercase tracking-wider">Blood Groups</h4>
                <div class="grid grid-cols-4 gap-1.5 text-center text-xs font-bold">
                    @foreach(['A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-'] as $bg)
                        <a href="{{ route('donors.search', ['blood_group' => $bg]) }}" class="bg-slate-800 hover:bg-brand-600 hover:text-white p-1.5 rounded-lg border border-slate-700 text-rose-300 transition">
                            {{ $bg }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div>
                <h4 class="font-bold text-white mb-3 text-xs uppercase tracking-wider">Emergency Contact</h4>
                <p class="text-xs text-slate-400 mb-2">Bhagwangola Helpline:</p>
                <a href="tel:+919832100000" class="text-base font-extrabold text-white hover:text-rose-400 transition block mb-1">+91 98321 00000</a>
                <p class="text-[11px] text-slate-500">Available 24 hours a day, 7 days a week.</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 mt-8 pt-6 border-t border-slate-800 text-center text-xs text-slate-400">
            © {{ date('Y') }} Manab Kalyane Rokto Dan — Bhagwangola Voluntary Blood Service. All rights reserved.
        </div>
    </footer>
</body>
</html>

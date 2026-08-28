<!DOCTYPE html>
<html lang="en" class="scroll-smooth" x-data="{ darkMode: localStorage.getItem('theme') === 'light' ? false : (localStorage.getItem('theme') === 'dark' ? true : true) }" :class="{ 'dark': darkMode }" x-init="$watch('darkMode', val => localStorage.setItem('theme',val ? 'dark' : 'light'))">
<head>
    @php
        $cmsLogo = \App\Models\SiteContent::getValue('site_logo');
        $cmsDarkLogo = \App\Models\SiteContent::getValue('site_dark_logo');
        $cmsFavicon = \App\Models\SiteContent::getValue('site_favicon');
        $cmsCustomCss = \App\Models\SiteContent::getValue('custom_css');
        $cmsEnableCss = \App\Models\SiteContent::getValue('enable_custom_css', '1');
        $cmsOrganizationName = \App\Models\SiteContent::getValue('organization_name', 'Manab Kalyane Rokto Dan');
        $cmsTagline = \App\Models\SiteContent::getValue('site_tagline', 'Bhagwangola Voluntary Society');
        $cmsHelplinePhone = \App\Models\SiteContent::getValue('helpline_phone', '+91 98321 00000');
        $cmsHelplineWhatsapp = \App\Models\SiteContent::getValue('helpline_whatsapp', '919832100000');
        $cmsTopBarLocation = \App\Models\SiteContent::getValue('top_bar_location', 'Murshidabad, West Bengal');
        $cmsWhatsappBtnText = \App\Models\SiteContent::getValue('whatsapp_button_text', 'WhatsApp');
        $cmsUpiId = \App\Models\SiteContent::getValue('upi_id', 'manobkalyan@upi');
        $defaultFooterMsg = 'Manab Kalyane Rokto Dan is a voluntary organization based in Bhagwangola, Murshidabad, dedicated to making blood accessible to every person in medical need without delay or financial burden.';
        $cmsAboutText = \App\Models\SiteContent::getValue('about_text', $defaultFooterMsg);
        $cmsFooterText = \App\Models\SiteContent::getValue('footer_text', $defaultFooterMsg);
        $cmsSocialFacebook = \App\Models\SiteContent::getValue('social_facebook', 'https://facebook.com');
        $cmsSocialInstagram = \App\Models\SiteContent::getValue('social_instagram', 'https://instagram.com');
        $cmsSocialWhatsapp = \App\Models\SiteContent::getValue('social_whatsapp', 'https://wa.me/919832100000');
        $cmsSocialYoutube = \App\Models\SiteContent::getValue('social_youtube', 'https://youtube.com');
        $cmsSocialTwitter = \App\Models\SiteContent::getValue('social_twitter', 'https://x.com');
        
        $headerMenuItems = \App\Models\SiteMenuItem::where('location', 'header')
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('sort_order')
            ->get();

        $footerMenuItems = \App\Models\SiteMenuItem::where('location', 'footer')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    @endphp

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($seo['title']) ? $seo['title'] : View::yieldContent('title', 'Manab Kalyane Rokto Dan — Bhagwangola Voluntary Blood Service') }}</title>
    <meta name="description" content="{{ isset($seo['description']) ? $seo['description'] : 'Bhagwangola voluntary blood donation network. Search donors by blood group, post emergency requests, and save lives in Murshidabad.' }}">
    <meta name="keywords" content="{{ isset($seo['keywords']) ? $seo['keywords'] : 'blood donation, Bhagwangola blood donor, Murshidabad voluntary blood, emergency blood request' }}">

    @if($cmsFavicon)
        <link rel="icon" href="{{ $cmsFavicon }}">
    @endif

    @if($cmsEnableCss === '1' && !empty($cmsCustomCss))
        <style>
            {!! $cmsCustomCss !!}
        </style>
    @endif

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
    
    <!-- Tom Select CSS & JS for Searchable Select Dropdowns -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

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

        .animate-float { animation: float-slow 7s ease-in-out infinite; }
        .animate-drift { animation: drift 11s ease-in-out infinite; }
        .animate-heartbeat { animation: heartbeat 1.6s ease-in-out infinite; transform-origin: center; }

        .glass-nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }
        .dark .glass-nav {
            background: rgba(15, 23, 42, 0.95);
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

        /* Custom Tom Select Styling */
        .ts-control {
            background-color: transparent !important;
            border-radius: 0.75rem !important;
            padding: 0.6rem 1rem !important;
            border: 1px solid #cbd5e1 !important;
            color: inherit !important;
            font-size: 0.875rem !important;
            box-shadow: none !important;
        }
        .dark .ts-control {
            border-color: #334155 !important;
            color: #ffffff !important;
            background-color: #0f172a !important;
        }
        .ts-dropdown {
            border-radius: 0.75rem !important;
            overflow: hidden !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3) !important;
            background-color: #ffffff !important;
            color: #0f172a !important;
            border: 1px solid #cbd5e1 !important;
            z-index: 100 !important;
            margin-top: 4px !important;
        }
        .dark .ts-dropdown {
            background-color: #0f172a !important;
            color: #ffffff !important;
            border-color: #334155 !important;
        }
        .ts-dropdown .option {
            padding: 0.6rem 1rem !important;
            font-size: 0.875rem !important;
        }
        .ts-dropdown .option.active, .ts-dropdown .option:hover {
            background-color: #e11d48 !important;
            color: #ffffff !important;
        }
        .dark .ts-dropdown .option.active, .dark .ts-dropdown .option:hover {
            background-color: #e11d48 !important;
            color: #ffffff !important;
        }
        .ts-control input {
            color: inherit !important;
        }

        /* Micro-Screen (< 320px / <= 268px) Ultra Fluid Font Scaling & Overflow Safeguard */
        @media (max-width: 320px) {
            html {
                font-size: 13px !important;
            }
            body {
                overflow-x: hidden !important;
                word-break: break-word !important;
            }
            .font-heading, header h1, header span {
                font-size: clamp(0.7rem, 4vw, 0.95rem) !important;
            }
            h1 {
                font-size: clamp(0.95rem, 5.5vw, 1.35rem) !important;
            }
            h2 {
                font-size: clamp(0.85rem, 4.5vw, 1.15rem) !important;
            }
            .text-xs {
                font-size: clamp(0.625rem, 3.2vw, 0.75rem) !important;
            }
            .px-4, .px-3 {
                padding-left: 0.35rem !important;
                padding-right: 0.35rem !important;
            }
        }

        @media (max-width: 270px) {
            html {
                font-size: 11px !important;
            }
            .font-heading, header span {
                font-size: clamp(0.6rem, 3.8vw, 0.8rem) !important;
            }
            h1 {
                font-size: clamp(0.85rem, 5vw, 1.1rem) !important;
            }
            .text-xs, button, a {
                font-size: 0.625rem !important;
                padding-left: 0.25rem !important;
                padding-right: 0.25rem !important;
            }
            /* Hide Brand Text below 268px screen width and display on hover via Tooltip */
            .brand-text-container {
                display: none !important;
            }
            .brand-micro-tooltip {
                display: none;
            }
            .group:hover .brand-micro-tooltip, .group:focus .brand-micro-tooltip {
                display: block !important;
            }
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-sans antialiased min-h-screen flex flex-col selection:bg-brand-600 selection:text-white transition-colors duration-300" x-data="{ authModal: false, authMode: 'login', shareOpen: false, mobileMenu: false, chatModalOpen: false, chatState: { requestId: null, phone: '', name: '', patientName: '', bloodGroup: '', hospital: '', location: '', notes: '', units: 1, messages: [], newMsg: '', loading: false } }">

    <!-- Top Helpline Bar -->
    <div class="bg-gradient-to-r from-rose-900 via-slate-900 to-rose-950 dark:from-brand-950 dark:via-slate-900 dark:to-brand-950 text-white text-xs py-2 px-3 sm:px-6 lg:px-8 shadow-sm relative z-50 overflow-hidden">
        <div class="max-w-7xl mx-auto flex justify-between items-center text-slate-100 gap-2">
            <div class="flex items-center space-x-2 sm:space-x-4 min-w-0 truncate">
                <span class="flex items-center space-x-1 font-bold text-[11px] sm:text-xs truncate">
                    <svg class="w-3.5 h-3.5 text-rose-400 animate-pulse shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    <span class="truncate">Helpline: <a href="tel:{{ preg_replace('/[^0-9+]/', '', $cmsHelplinePhone) }}" class="text-white hover:text-rose-300 font-extrabold">{{ $cmsHelplinePhone }}</a></span>
                </span>
                <span class="hidden md:inline-block text-slate-400">|</span>
                <span class="hidden md:inline-flex items-center space-x-1 text-xs font-medium">
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>{{ $cmsTopBarLocation }}</span>
                </span>
            </div>

            <div class="flex items-center space-x-2 shrink-0">
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $cmsHelplineWhatsapp ?: $cmsHelplinePhone) }}?text=Emergency%20Blood%20Help" target="_blank" class="inline-flex items-center space-x-1.5 px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 hover:bg-emerald-500/30 transition text-[11px] font-bold whitespace-nowrap">
                    <svg class="w-3.5 h-3.5 fill-current text-emerald-400 shrink-0" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    <span>{{ $cmsWhatsappBtnText }}</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-40 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-1.5 sm:gap-2">
                
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="flex items-center space-x-2.5 shrink-0 group relative" title="{{ $cmsOrganizationName }} — {{ $cmsTagline }}">
                    @if($cmsLogo)
                        <img src="{{ $cmsLogo }}" alt="{{ $cmsOrganizationName }}" class="h-8 sm:h-9 w-auto object-contain transition duration-300 group-hover:scale-105 shrink-0" :class="{ 'hidden': darkMode && '{{ $cmsDarkLogo }}' !== '' }">
                        @if($cmsDarkLogo)
                            <img src="{{ $cmsDarkLogo }}" alt="{{ $cmsOrganizationName }}" class="h-8 sm:h-9 w-auto object-contain transition duration-300 group-hover:scale-105 hidden shrink-0" :class="{ 'hidden': !darkMode }">
                        @endif
                    @else
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-gradient-to-tr from-brand-700 via-rose-600 to-rose-500 text-white flex items-center justify-center shadow-lg group-hover:scale-105 transition duration-300 glow-red shrink-0">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 fill-current animate-heartbeat" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path></svg>
                        </div>
                    @endif
                    <div class="leading-tight brand-text-container">
                        <span class="font-heading font-black text-sm sm:text-base lg:text-lg tracking-tight text-slate-900 dark:text-white group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors block whitespace-nowrap">
                            {{ $cmsOrganizationName }}
                        </span>
                        <span class="text-[9px] sm:text-[10px] uppercase font-extrabold tracking-wider text-rose-600 dark:text-rose-400 hidden sm:block whitespace-nowrap">
                            {{ $cmsTagline }}
                        </span>
                    </div>

                    <!-- Micro-screen Hover Tooltip (< 268px width) -->
                    <div class="brand-micro-tooltip hidden absolute left-0 top-full mt-1.5 w-max max-w-[200px] p-2 rounded-xl bg-slate-900 text-white text-[10px] font-extrabold shadow-2xl border border-slate-700 z-50 pointer-events-none leading-tight">
                        {{ $cmsOrganizationName }}
                    </div>
                </a>

                <!-- Dynamic Desktop Navigation Bar (CMS Menu Manager) -->
                <nav class="hidden lg:flex items-center space-x-0.5 xl:space-x-1 text-[13px] xl:text-xs font-extrabold text-slate-700 dark:text-slate-300">
                    @if($headerMenuItems->count() > 0)
                        @foreach($headerMenuItems as $mItem)
                            @if($mItem->children->count() > 0)
                                <!-- Dropdown Menu Item -->
                                <div class="relative" x-data="{ open: false }" @mouseleave="open = false">
                                    <button @click="open = !open" @mouseover="open = true" class="px-2.5 xl:px-3 py-1.5 rounded-xl whitespace-nowrap transition flex items-center gap-1 hover:text-slate-900 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-800/60">
                                        <span>{{ $mItem->title }}</span>
                                        <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                    <div x-show="open" x-cloak class="absolute left-0 mt-1 w-48 rounded-2xl bg-white dark:bg-slate-900 shadow-xl border border-slate-200 dark:border-slate-800 py-2 z-50">
                                        @foreach($mItem->children as $child)
                                            <a href="{{ $child->url }}" target="{{ $child->target }}" class="block px-4 py-2 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-rose-600 hover:text-white transition">
                                                {{ $child->title }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <a href="{{ $mItem->url }}" target="{{ $mItem->target }}" class="px-2.5 xl:px-3 py-1.5 rounded-xl whitespace-nowrap transition hover:text-slate-900 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-800/60 {{ request()->is(ltrim($mItem->url, '/')) ? 'text-rose-600 dark:text-white bg-rose-50 dark:bg-slate-800/80 font-extrabold' : '' }}">
                                    {{ $mItem->title }}
                                </a>
                            @endif
                        @endforeach
                    @else
                        <a href="{{ route('home') }}" class="px-2.5 xl:px-3 py-1.5 rounded-xl whitespace-nowrap transition hover:text-slate-900 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-800/60">Home</a>
                        <a href="{{ route('donors.search') }}" class="px-2.5 xl:px-3 py-1.5 rounded-xl whitespace-nowrap transition hover:text-slate-900 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-800/60">Find Donors</a>
                        <a href="{{ route('requests.index') }}" class="px-2.5 xl:px-3 py-1.5 rounded-xl whitespace-nowrap transition hover:text-slate-900 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-800/60">Requests</a>
                        <a href="{{ route('blog.index') }}" class="px-2.5 xl:px-3 py-1.5 rounded-xl whitespace-nowrap transition hover:text-slate-900 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-800/60">Health</a>
                        <a href="{{ route('stories.index') }}" class="px-2.5 xl:px-3 py-1.5 rounded-xl whitespace-nowrap transition hover:text-slate-900 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-800/60">Stories</a>
                        <a href="{{ route('donate') }}" class="px-2.5 xl:px-3 py-1.5 rounded-xl whitespace-nowrap transition hover:text-slate-900 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-800/60">Donate</a>
                        <a href="{{ route('members') }}" class="px-2.5 xl:px-3 py-1.5 rounded-xl whitespace-nowrap transition hover:text-slate-900 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-800/60">Committee</a>
                        <a href="{{ route('gallery') }}" class="px-2.5 xl:px-3 py-1.5 rounded-xl whitespace-nowrap transition hover:text-slate-900 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-800/60">Gallery</a>
                    @endif
                </nav>

                <!-- Auth, Theme Toggle & Mobile Hamburger -->
                <div class="flex items-center space-x-1 sm:space-x-2 shrink-0">
                    <!-- Light / Dark Theme Switch Button -->
                    <button @click="darkMode = !darkMode" type="button" class="p-1.5 sm:p-2 rounded-xl border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-800/90 hover:bg-slate-200 dark:hover:bg-slate-700 transition flex items-center gap-1 text-xs font-bold shadow-sm" title="Toggle Light or Dark Theme">
                        <template x-if="darkMode">
                            <span class="flex items-center gap-1 text-amber-300">
                                <span>☀️</span> <span class="hidden sm:inline text-xs font-bold text-amber-200">Light</span>
                            </span>
                        </template>
                        <template x-if="!darkMode">
                            <span class="flex items-center gap-1 text-slate-800">
                                <span>🌙</span> <span class="hidden sm:inline text-xs font-bold text-slate-800">Dark</span>
                            </span>
                        </template>
                    </button>

                    @auth
                        @php
                            $unreadCount = Auth::user()->notifications()->where('is_read', false)->count();
                            $user = Auth::user();
                            $bloodGroup = $user->donorProfile?->blood_group;
                        @endphp
                        
                        <!-- Notifications Bell Icon -->
                        <a href="{{ route('dashboard') }}" class="relative p-1.5 sm:p-2 rounded-xl text-xs font-bold bg-slate-200 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-300 dark:border-slate-700 hover:bg-slate-300 dark:hover:bg-slate-700 transition flex items-center justify-center" title="Notifications">
                            <span class="text-sm">🔔</span>
                            @if($unreadCount > 0)
                                <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-rose-600 text-white text-[9px] font-extrabold flex items-center justify-center animate-bounce">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </a>

                        <!-- USER PROFILE & ADMIN ACCOUNT DROPDOWN (ICON-ONLY FOR MAX SPACE) -->
                        <div class="relative" x-data="{ userDropdown: false }" @click.outside="userDropdown = false">
                            <button @click="userDropdown = !userDropdown" type="button" class="p-1 sm:p-1.5 rounded-xl text-xs font-bold bg-slate-200 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-300 dark:border-slate-700 hover:bg-slate-300 dark:hover:bg-slate-700 transition flex items-center gap-1 shadow-sm" title="{{ $user->name }} ({{ $user->role }})">
                                @if($user->avatar_url)
                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg object-cover shrink-0 border border-rose-500/50">
                                @else
                                    <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-gradient-to-tr from-brand-700 to-rose-500 text-white text-[11px] font-extrabold flex items-center justify-center uppercase shrink-0 shadow-sm">
                                        {{ $bloodGroup ?: substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                                <svg class="w-3.5 h-3.5 text-slate-500 transition-transform duration-200" :class="{ 'rotate-180': userDropdown }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu Box -->
                            <div x-show="userDropdown" x-cloak 
                                 x-transition:enter="transition ease-out duration-150" 
                                 x-transition:enter-start="opacity-0 scale-95 translate-y-1" 
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
                                 x-transition:leave="transition ease-in duration-100" 
                                 x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
                                 x-transition:leave-end="opacity-0 scale-95 translate-y-1" 
                                 class="absolute right-0 mt-2 w-56 rounded-2xl bg-white dark:bg-slate-900 shadow-2xl border border-slate-200 dark:border-slate-800 py-2 z-50 overflow-hidden">
                                
                                <!-- User Info Header -->
                                <div class="px-4 py-3 bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200 dark:border-slate-800">
                                    <div class="font-extrabold text-sm text-slate-900 dark:text-white truncate">{{ $user->name }}</div>
                                    <div class="text-[11px] text-slate-500 truncate">{{ $user->email ?: $user->phone }}</div>
                                    <div class="mt-1.5 flex items-center gap-1.5 flex-wrap">
                                        @if($user->canAccessAdmin())
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-amber-500/20 text-amber-600 dark:text-amber-300 border border-amber-500/30">
                                                🛡️ {{ $user->getRoleObject()->label ?? 'Admin Access' }}
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 border border-indigo-500/30">
                                                👤 {{ $user->getRoleObject()->label ?? ucfirst($user->role) }}
                                            </span>
                                        @endif
                                        @if($bloodGroup)
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-rose-500/20 text-rose-600 dark:text-rose-400">
                                                🩸 Donor ({{ $bloodGroup }})
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Menu Options -->
                                <div class="py-1">
                                    @if($user->canAccessAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-amber-700 dark:text-amber-300 hover:bg-amber-500/10 transition">
                                            <span>⚙️</span>
                                            <span>Admin Command Center</span>
                                        </a>
                                        <div class="border-t border-slate-100 dark:border-slate-800/80 my-1"></div>
                                    @endif

                                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-rose-600 hover:text-white transition">
                                        <span>📊</span>
                                        <span>User Dashboard</span>
                                    </a>

                                    <a href="{{ route('dashboard.profile') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-rose-600 hover:text-white transition">
                                        <span>👤</span>
                                        <span>My Profile</span>
                                    </a>

                                    <a href="{{ route('dashboard.card') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-rose-600 hover:text-white transition">
                                        <span>🎴</span>
                                        <span>Donor ID Card</span>
                                    </a>
                                </div>

                                <!-- Logout Action -->
                                <div class="border-t border-slate-200 dark:border-slate-800 pt-1">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-slate-800 transition text-left">
                                            <span>🚪</span>
                                            <span>Sign Out / Logout</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- SINGLE COMBINED AUTH BUTTON IN MENU -->
                        <button @click="authModal = true; authMode = 'login'" class="px-3.5 py-2 rounded-xl text-xs font-extrabold bg-gradient-to-r from-brand-600 to-rose-600 text-white shadow-md hover:opacity-95 transition flex items-center gap-1">
                            <span>Login</span>
                        </button>
                    @endauth

                    <!-- Mobile Hamburger Button -->
                    <button @click="mobileMenu = !mobileMenu" type="button" class="lg:hidden p-2 rounded-xl border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-800/90 hover:bg-slate-200 dark:hover:bg-slate-700 transition" aria-label="Toggle Navigation Menu">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Drawer -->
        <div x-show="mobileMenu" x-cloak 
             @click.outside="mobileMenu = false"
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0 -translate-y-2" 
             x-transition:enter-end="opacity-100 translate-y-0" 
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="lg:hidden glass-card border-t border-slate-200 dark:border-slate-800 px-4 py-4 space-y-2 text-sm font-bold max-h-[80vh] overflow-y-auto shadow-2xl">
            
            @if($headerMenuItems->count() > 0)
                @foreach($headerMenuItems as $mItem)
                    @if($mItem->children->count() > 0)
                        <!-- Mobile Accordion Menu Item -->
                        <div x-data="{ childOpen: false }" class="space-y-1">
                            <button @click="childOpen = !childOpen" type="button" class="w-full flex items-center justify-between px-3 py-2 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-800 dark:text-slate-200 transition">
                                <span>{{ $mItem->title }}</span>
                                <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': childOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="childOpen" x-cloak class="pl-4 space-y-1 border-l-2 border-rose-500/40 ml-2">
                                @foreach($mItem->children as $child)
                                    <a href="{{ $child->url }}" target="{{ $child->target }}" @click="mobileMenu = false" class="block px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-rose-600 hover:text-white transition">
                                        {{ $child->title }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ $mItem->url }}" target="{{ $mItem->target }}" @click="mobileMenu = false" class="block px-3 py-2 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-800 dark:text-slate-200 transition {{ request()->is(ltrim($mItem->url, '/')) ? 'text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-slate-800/80 font-extrabold' : '' }}">
                            {{ $mItem->title }}
                        </a>
                    @endif
                @endforeach
            @else
                <a href="{{ route('home') }}" @click="mobileMenu = false" class="block px-3 py-2 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-800 dark:text-slate-200">Home</a>
                <a href="{{ route('donors.search') }}" @click="mobileMenu = false" class="block px-3 py-2 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-800 dark:text-slate-200">Find Donors</a>
                <a href="{{ route('requests.index') }}" @click="mobileMenu = false" class="block px-3 py-2 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-800 dark:text-slate-200">Requests</a>
                <a href="{{ route('blog.index') }}" @click="mobileMenu = false" class="block px-3 py-2 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-800 dark:text-slate-200">Health & Blog</a>
                <a href="{{ route('stories.index') }}" @click="mobileMenu = false" class="block px-3 py-2 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-800 dark:text-slate-200">Donor Stories</a>
                <a href="{{ route('donate') }}" @click="mobileMenu = false" class="block px-3 py-2 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-800 dark:text-slate-200">Donate / Support</a>
                <a href="{{ route('members') }}" @click="mobileMenu = false" class="block px-3 py-2 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-800 dark:text-slate-200">Committee</a>
                <a href="{{ route('gallery') }}" @click="mobileMenu = false" class="block px-3 py-2 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-800 dark:text-slate-200">Gallery</a>
            @endif

            @auth
                <div class="pt-2 border-t border-slate-200 dark:border-slate-800 space-y-1">
                    <div class="px-3 py-1 text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Account Operations</div>
                    @if(Auth::user()->canAccessAdmin())
                        <a href="{{ route('admin.dashboard') }}" @click="mobileMenu = false" class="block px-3 py-2 rounded-xl bg-amber-500/20 text-amber-700 dark:text-amber-300 font-extrabold border border-amber-500/30">⚙️ Admin Command Center</a>
                    @endif
                    <a href="{{ route('dashboard') }}" @click="mobileMenu = false" class="block px-3 py-2 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-800 dark:text-slate-200">📊 Dashboard</a>
                    <a href="{{ route('dashboard.profile') }}" @click="mobileMenu = false" class="block px-3 py-2 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-800 dark:text-slate-200">👤 My Profile</a>
                    <a href="{{ route('dashboard.card') }}" @click="mobileMenu = false" class="block px-3 py-2 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-800 dark:text-slate-200">🎴 Donor ID Card</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 rounded-xl text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-slate-800 font-bold">🚪 Logout</button>
                    </form>
                </div>
            @else
                <div class="pt-2 border-t border-slate-200 dark:border-slate-800">
                    <button @click="authModal = true; authMode = 'login'; mobileMenu = false" class="w-full py-2.5 rounded-xl font-extrabold bg-gradient-to-r from-brand-600 to-rose-600 text-white text-center shadow-md">
                        Login / Sign Up
                    </button>
                </div>
            @endauth
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

    <!-- Auth Dialog Modal with Toggle Tabs -->
    <div x-show="authModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
        <div class="glass-card max-w-md w-full p-6 sm:p-8 rounded-3xl border border-slate-300 dark:border-slate-800 relative shadow-2xl overflow-y-auto max-h-[90vh]">
            <button @click="authModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-900 dark:hover:text-white text-2xl font-bold">&times;</button>

            <!-- Modal Header -->
            <div class="text-center mb-6">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-brand-700 to-rose-500 text-white flex items-center justify-center mx-auto mb-3 shadow-lg glow-red">
                    <svg class="w-7 h-7 fill-current animate-heartbeat" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path></svg>
                </div>
                
                <!-- TOGGLE TAB BUTTONS -->
                <div class="flex items-center justify-center p-1 rounded-2xl bg-slate-200 dark:bg-slate-900 border border-slate-300 dark:border-slate-800 max-w-xs mx-auto mb-2">
                    <button @click="authMode = 'login'" :class="authMode === 'login' ? 'bg-rose-600 text-white font-extrabold shadow-md' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="w-1/2 py-2 rounded-xl text-xs transition duration-200">
                        Sign In
                    </button>
                    <button @click="authMode = 'signup'" :class="authMode === 'signup' ? 'bg-rose-600 text-white font-extrabold shadow-md' : 'text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white'" class="w-1/2 py-2 rounded-xl text-xs transition duration-200">
                        Register Account
                    </button>
                </div>
            </div>

            <!-- Login Form -->
            <div x-show="authMode === 'login'">
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
                        Sign In to Account
                    </button>
                    <div class="text-center text-xs text-slate-500 dark:text-slate-400 mt-4">
                        Don't have an account yet? <button type="button" @click="authMode = 'signup'" class="text-rose-600 dark:text-rose-400 font-bold hover:underline">Register New Account</button>
                    </div>
                </form>
            </div>

            <!-- Signup Form -->
            <div x-show="authMode === 'signup'">
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
                            <select name="blood_group" required class="searchable-select w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-bold">
                                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $g)
                                    <option value="{{ $g }}">{{ $g }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Block</label>
                            <select name="block" required class="searchable-select w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-rose-500 font-medium">
                                <option value="Bhagwangola-I">Bhagwangola-I</option>
                                <option value="Bhagwangola-II">Bhagwangola-II</option>
                                <option value="Lalgola">Lalgola</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Referral Code (Optional)</label>
                        <input type="text" name="ref_code" placeholder="e.g. MKRD-REF-0001" class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2 text-xs font-mono font-bold text-slate-900 dark:text-white uppercase focus:outline-none focus:border-rose-500">
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
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-slate-900 dark:bg-slate-950 border-t border-slate-200 dark:border-slate-800 text-slate-400 py-12 text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="space-y-4">
                <div class="flex items-center space-x-2">
                    @if($cmsLogo)
                        <img src="{{ $cmsLogo }}" alt="{{ $cmsOrganizationName }}" class="h-8 w-auto object-contain shrink-0">
                    @else
                        <div class="w-8 h-8 rounded-lg bg-rose-600 text-white flex items-center justify-center font-bold shrink-0">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path></svg>
                        </div>
                    @endif
                    <span class="font-bold text-white text-base">{{ $cmsOrganizationName }}</span>
                </div>
                <p class="text-xs leading-relaxed text-slate-400">
                    {{ $cmsAboutText ?: $cmsFooterText }}
                </p>
                @if($cmsUpiId)
                    <div class="pt-1">
                        <span class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider block">Official UPI ID:</span>
                        <span class="text-xs font-mono font-bold text-rose-400 bg-slate-800/80 px-2 py-0.5 rounded-lg border border-slate-700 inline-block mt-0.5">{{ $cmsUpiId }}</span>
                    </div>
                @endif

                <!-- Dynamic Social Media Handles -->
                <div class="pt-2 flex items-center space-x-2.5 flex-wrap">
                    @if($cmsSocialFacebook && $cmsSocialFacebook !== '#')
                        <a href="{{ $cmsSocialFacebook }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-[#1877F2] text-slate-300 hover:text-white flex items-center justify-center transition shadow" title="Facebook Page">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                    @endif
                    @if($cmsSocialInstagram && $cmsSocialInstagram !== '#')
                        <a href="{{ $cmsSocialInstagram }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-[#E4405F] text-slate-300 hover:text-white flex items-center justify-center transition shadow" title="Instagram Profile">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    @endif
                    @if($cmsSocialWhatsapp && $cmsSocialWhatsapp !== '#')
                        <a href="{{ $cmsSocialWhatsapp }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-[#25D366] text-slate-300 hover:text-white flex items-center justify-center transition shadow" title="WhatsApp Channel / Group">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-1.002 3.66 3.745-.983z"/></svg>
                        </a>
                    @endif
                    @if($cmsSocialYoutube && $cmsSocialYoutube !== '#')
                        <a href="{{ $cmsSocialYoutube }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-[#FF0000] text-slate-300 hover:text-white flex items-center justify-center transition shadow" title="YouTube Channel">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                    @endif
                    @if($cmsSocialTwitter && $cmsSocialTwitter !== '#')
                        <a href="{{ $cmsSocialTwitter }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-black text-slate-300 hover:text-white flex items-center justify-center transition shadow" title="Twitter / X Profile">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                    @endif
                </div>
            </div>

            <div>
                <h4 class="font-bold text-white mb-3 text-xs uppercase tracking-wider">Quick Navigation</h4>
                <ul class="space-y-2 text-xs">
                    @if($footerMenuItems->count() > 0)
                        @foreach($footerMenuItems as $fItem)
                            <li><a href="{{ $fItem->url }}" target="{{ $fItem->target }}" class="hover:text-rose-400 transition">{{ $fItem->title }}</a></li>
                        @endforeach
                    @else
                        <li><a href="{{ route('donors.search') }}" class="hover:text-rose-400 transition">Search Blood Donors</a></li>
                        <li><a href="{{ route('requests.index') }}" class="hover:text-rose-400 transition">Emergency Requests</a></li>
                        <li><a href="{{ route('blog.index') }}" class="hover:text-rose-400 transition">Blog & Health</a></li>
                        <li><a href="{{ route('stories.index') }}" class="hover:text-rose-400 transition">Donor Stories & Experiences</a></li>
                        <li><a href="{{ route('donate') }}" class="hover:text-rose-400 transition">Donate / Support Us</a></li>
                        <li><a href="{{ route('members') }}" class="hover:text-rose-400 transition">Board Members</a></li>
                    @endif
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
                <p class="text-xs text-slate-400 mb-2">Society Emergency Helpline:</p>
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $cmsHelplinePhone) }}" class="text-base font-extrabold text-white hover:text-rose-400 transition block mb-1">{{ $cmsHelplinePhone }}</a>
                <p class="text-[11px] text-slate-500">Available 24 hours a day, 7 days a week.</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 mt-8 pt-6 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-2 text-center sm:text-left text-xs text-slate-400">
            <div>
                © {{ date('Y') }} {{ $cmsOrganizationName }}. All rights reserved.
            </div>
            <div class="font-medium text-slate-400">
                Designed by : <a href="https://mabia.in" target="_blank" rel="noopener noreferrer" class="font-bold text-rose-400 hover:text-rose-300 hover:underline transition">mabia.in</a>
            </div>
        </div>
    </footer>

    <!-- Global Searchable Select Initializer Script -->
    <script>
        function initSearchableSelects() {
            if (typeof TomSelect === 'undefined') return;
            document.querySelectorAll('select.searchable-select, select[data-searchable="true"]').forEach(el => {
                if (!el.tomselect) {
                    new TomSelect(el, {
                        create: false,
                        maxOptions: 2000,
                        sortField: { field: "text", direction: "asc" },
                        placeholder: el.getAttribute('placeholder') || 'Type to search or select option...',
                    });
                }
            });
        }
        document.addEventListener('DOMContentLoaded', () => {
            initSearchableSelects();
            setTimeout(initSearchableSelects, 600);
        });
        window.initSearchableSelects = initSearchableSelects;
    </script>

    <!-- Global Visitor Click Analytics Tracking Script -->
    <script>
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a, button');
            if (!link) return;

            const href = link.getAttribute('href') || '';
            const text = link.innerText ? link.innerText.trim() : '';
            const donorName = link.dataset.donorName || link.getAttribute('data-donor-name');
            const bloodGroup = link.dataset.bloodGroup || link.getAttribute('data-blood-group');

            let actionType = null;
            let targetDetails = null;

            if (href.startsWith('tel:')) {
                actionType = 'contact_donor_phone_call';
                targetDetails = 'Initiated phone call to ' + (donorName ? donorName + ' (' + (bloodGroup || '') + ')' : href);
            } else if (href.includes('wa.me') || href.includes('whatsapp.com')) {
                actionType = 'contact_donor_whatsapp';
                targetDetails = 'Initiated WhatsApp inquiry to ' + (donorName ? donorName + ' (' + (bloodGroup || '') + ')' : href);
            } else if (link.classList.contains('inquire-btn') || text.includes('Inquire') || text.includes('Society Helpline')) {
                actionType = 'inquire_via_society';
                targetDetails = 'Inquired via society helpline for donor ' + (donorName || text);
            } else if (href.includes('/donate') || text.includes('Donate')) {
                actionType = 'donate_pledge_click';
                targetDetails = 'Clicked financial contribution button: ' + text;
            }

            if (actionType) {
                try {
                    navigator.sendBeacon('/analytics/log-click', new Blob([JSON.stringify({
                        action_type: actionType,
                        target_details: targetDetails,
                        path: window.location.pathname,
                        _token: '{{ csrf_token() }}'
                    })], { type: 'application/json' }));
                } catch (err) {}
    <!-- Request Detail & In-Portal Live Chat Modal -->
    <div x-show="chatModalOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-3 sm:p-4 bg-slate-950/80 backdrop-blur-md">
        <div class="glass-card max-w-2xl w-full rounded-3xl border border-slate-300 dark:border-slate-800 shadow-2xl overflow-hidden flex flex-col max-h-[90vh] relative" @click.away="chatModalOpen = false">
            
            <!-- Modal Header -->
            <div class="p-4 sm:p-5 bg-gradient-to-r from-slate-900 via-rose-950 to-slate-900 text-white flex items-center justify-between border-b border-slate-800 shrink-0">
                <div class="flex items-center space-x-3 min-w-0">
                    <template x-if="chatState.bloodGroup">
                        <span class="w-10 h-10 rounded-2xl bg-rose-600/30 border border-rose-500/50 text-rose-300 font-black text-sm flex items-center justify-center shrink-0" x-text="chatState.bloodGroup"></span>
                    </template>
                    <template x-if="!chatState.bloodGroup">
                        <span class="w-10 h-10 rounded-2xl bg-emerald-600/30 border border-emerald-500/50 text-emerald-300 font-bold text-sm flex items-center justify-center shrink-0">💬</span>
                    </template>
                    <div class="min-w-0">
                        <h3 class="text-sm sm:text-base font-extrabold text-white truncate" x-text="chatState.patientName ? 'Request Details & Chat: ' + chatState.patientName : 'Member Chat: ' + chatState.name"></h3>
                        <p class="text-xs text-slate-300 truncate" x-text="chatState.hospital ? chatState.hospital + ' • ' + chatState.location : 'Phone: ' + (chatState.phone || 'N/A')"></p>
                    </div>
                </div>
                
                <button @click="chatModalOpen = false" class="w-8 h-8 rounded-full bg-slate-800 text-slate-400 hover:text-white flex items-center justify-center text-lg transition shrink-0">&times;</button>
            </div>

            <!-- Request Full Details Card (If Blood Request) -->
            <template x-if="chatState.patientName">
                <div class="p-4 bg-slate-100 dark:bg-slate-900/90 border-b border-slate-200 dark:border-slate-800 text-xs space-y-2 shrink-0">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <span class="font-bold text-slate-900 dark:text-white">Patient Name:</span>
                            <span class="text-slate-700 dark:text-slate-300 font-semibold" x-text="chatState.patientName"></span>
                            <span class="ml-2 font-bold text-rose-600 dark:text-rose-400" x-text="'(' + chatState.bloodGroup + ' • ' + chatState.units + ' Unit)'"></span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <template x-if="chatState.phone">
                                <a :href="'tel:' + chatState.phone" class="px-2.5 py-1 rounded-xl text-[11px] font-extrabold bg-emerald-600 text-white hover:bg-emerald-500 transition">📞 Call Phone</a>
                            </template>
                            <template x-if="chatState.phone">
                                <a :href="'https://wa.me/91' + chatState.phone.replace(/[^0-9]/g, '') + '?text=Hello,%20responding%20to%20blood%20request%20for%20' + encodeURIComponent(chatState.patientName)" target="_blank" class="px-2.5 py-1 rounded-xl text-[11px] font-extrabold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 hover:bg-emerald-500/30 transition">💬 WhatsApp</a>
                            </template>
                        </div>
                    </div>
                    <div x-show="chatState.hospital" class="text-slate-600 dark:text-slate-400">
                        🏥 <strong>Hospital & Location:</strong> <span x-text="chatState.hospital"></span> (<span x-text="chatState.location"></span>)
                    </div>
                    <div x-show="chatState.notes" class="bg-white dark:bg-slate-950 p-2.5 rounded-xl text-slate-700 dark:text-slate-300 italic border border-slate-200 dark:border-slate-800" x-text="'&quot;' + chatState.notes + '&quot;'"></div>
                </div>
            </template>

            <!-- Conversation History Messages Stream -->
            <div id="chatMessagesScroll" class="flex-1 p-4 overflow-y-auto space-y-3 min-h-[220px] max-h-[350px] bg-slate-50 dark:bg-slate-950/80">
                <template x-if="chatState.loading">
                    <div class="text-center py-8 text-xs text-slate-400">Loading conversation history thread...</div>
                </template>

                <template x-if="!chatState.loading && chatState.messages.length === 0">
                    <div class="text-center py-8 text-xs text-slate-400 space-y-1">
                        <div class="text-2xl">💬</div>
                        <div class="font-bold text-slate-700 dark:text-slate-300">No portal messages in this thread yet.</div>
                        <div class="text-[11px]">Type below to start chatting directly through the portal!</div>
                    </div>
                </template>

                <template x-for="msg in chatState.messages" :key="msg.id">
                    <div :class="msg.is_mine ? 'flex flex-col items-end' : 'flex flex-col items-start'">
                        <div class="text-[10px] text-slate-400 font-medium mb-0.5" x-text="msg.sender_name + ' • ' + msg.created_at_formatted"></div>
                        <div :class="msg.is_mine ? 'bg-gradient-to-r from-rose-600 to-brand-600 text-white rounded-2xl rounded-tr-none' : 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-800 rounded-2xl rounded-tl-none'" class="p-3 text-xs shadow-sm max-w-[85%] leading-relaxed font-medium" x-text="msg.message"></div>
                    </div>
                </template>
            </div>

            <!-- Send Message Input Box -->
            <div class="p-3 sm:p-4 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 shrink-0">
                <form @submit.prevent="sendPortalMessage()" class="flex items-center space-x-2">
                    <input type="text" x-model="chatState.newMsg" placeholder="Type your message to communicate via portal..." class="flex-1 bg-slate-100 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-4 py-2.5 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-rose-500">
                    <button type="submit" class="px-4 py-2.5 rounded-xl text-xs font-extrabold bg-gradient-to-r from-brand-600 to-rose-600 text-white shadow-md hover:opacity-95 transition glow-red shrink-0 flex items-center gap-1">
                        Send 📤
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript Helper Functions for Portal Chat & Request Details -->
    <script>
        window.openPortalChat = function(details) {
            const appEl = document.body;
            if (!appEl || !window.Alpine) return;
            const alpine = Alpine.$data(appEl);
            if (!alpine) return;
            alpine.chatState.requestId = details.requestId || null;
            alpine.chatState.phone = details.phone || '';
            alpine.chatState.name = details.name || details.patientName || 'Member';
            alpine.chatState.patientName = details.patientName || '';
            alpine.chatState.bloodGroup = details.bloodGroup || '';
            alpine.chatState.hospital = details.hospital || '';
            alpine.chatState.location = details.location || '';
            alpine.chatState.notes = details.notes || '';
            alpine.chatState.units = details.units || 1;
            alpine.chatState.messages = [];
            alpine.chatState.newMsg = '';
            alpine.chatState.loading = true;
            alpine.chatModalOpen = true;

            fetch('/api/chat/messages?blood_request_id=' + (details.requestId || '') + '&phone=' + encodeURIComponent(details.phone || ''))
                .then(res => res.json())
                .then(data => {
                    alpine.chatState.messages = data.messages || [];
                    alpine.chatState.loading = false;
                    setTimeout(() => {
                        const box = document.getElementById('chatMessagesScroll');
                        if (box) box.scrollTop = box.scrollHeight;
                    }, 100);
                })
                .catch(() => { alpine.chatState.loading = false; });
        };

        window.sendPortalMessage = function() {
            const appEl = document.body;
            if (!appEl || !window.Alpine) return;
            const alpine = Alpine.$data(appEl);
            if (!alpine || !alpine.chatState.newMsg.trim()) return;

            const payload = {
                message: alpine.chatState.newMsg,
                blood_request_id: alpine.chatState.requestId,
                receiver_phone: alpine.chatState.phone,
                receiver_name: alpine.chatState.name,
                _token: '{{ csrf_token() }}'
            };

            const textSent = alpine.chatState.newMsg;
            alpine.chatState.newMsg = '';

            fetch('/api/chat/send', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(res => {
                if (res.success && res.data) {
                    alpine.chatState.messages.push(res.data);
                    setTimeout(() => {
                        const box = document.getElementById('chatMessagesScroll');
                        if (box) box.scrollTop = box.scrollHeight;
                    }, 100);
                }
            });
        };
    </script>
</body>
</html>

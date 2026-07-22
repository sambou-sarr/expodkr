<!DOCTYPE html>
<html lang="fr" x-data="adminApp()" x-init="init()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') – ExpoDakar Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --blue-night:    #0A1628;
            --blue-deep:     #0D2145;
            --blue-electric: #2563EB;
            --gold:          #C9A84C;
            --gold-light:    #E8C96A;
            --sidebar-w:     256px;
            --sidebar-mini:  72px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #F1F5F9;
            color: #0F172A;
            -webkit-font-smoothing: antialiased;
        }

        [x-cloak] { display: none !important; }

        /* ── Sidebar ─────────────────────────────────────────── */
        #sidebar {
            width: var(--sidebar-w);
            background: var(--blue-night);
            transition: width .3s cubic-bezier(.4,0,.2,1);
            overflow: hidden;
        }
        #sidebar.mini { width: var(--sidebar-mini); }

        /* Glow actif */
        .nav-active {
            background: linear-gradient(135deg, var(--blue-electric), #1d4ed8);
            box-shadow: 0 4px 20px rgba(37,99,235,.35);
        }
        .nav-active .nav-dot { opacity: 1; }
        .nav-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: white; opacity: 0;
            flex-shrink: 0;
            transition: opacity .2s;
        }

        /* Sidebar nav link */
        .nav-link {
            display: flex; align-items: center; gap: .75rem;
            padding: .625rem .875rem;
            border-radius: .875rem;
            font-size: .8rem; font-weight: 500;
            color: rgba(255,255,255,.6);
            transition: background .2s, color .2s, transform .15s;
            position: relative; white-space: nowrap;
        }
        .nav-link:hover { background: rgba(255,255,255,.07); color: white; transform: translateX(2px); }
        .nav-link.nav-active { color: white; }

        /* Nav group label */
        .nav-group {
            font-size: .62rem; font-weight: 700;
            letter-spacing: .12em; text-transform: uppercase;
            color: rgba(255,255,255,.25);
            padding: 0 .875rem;
            margin: 1.5rem 0 .5rem;
            white-space: nowrap;
            transition: opacity .2s;
        }
        #sidebar.mini .nav-group { opacity: 0; }
        #sidebar.mini .nav-label { display: none; }

        /* Logo glow */
        .logo-glow {
            box-shadow: 0 0 20px rgba(255,255,255,.25), 0 0 40px rgba(37,99,235,.3);
            transition: box-shadow .3s;
        }
        .logo-glow:hover { box-shadow: 0 0 30px rgba(255,255,255,.45), 0 0 60px rgba(37,99,235,.5); }

        /* ── Topbar ──────────────────────────────────────────── */
        #topbar {
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            background: rgba(255,255,255,.88);
            border-bottom: 1px solid rgba(0,0,0,.06);
            box-shadow: 0 1px 20px rgba(0,0,0,.06);
        }

        /* ── Stat cards ──────────────────────────────────────── */
        .stat-card {
            border-radius: 1.25rem;
            background: white;
            border: 1px solid rgba(0,0,0,.06);
            box-shadow: 0 2px 16px rgba(0,0,0,.05);
            transition: transform .28s ease, box-shadow .28s ease;
            overflow: hidden;
        }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(0,0,0,.1); }

        /* ── Chart card ──────────────────────────────────────── */
        .chart-card {
            background: white;
            border-radius: 1.25rem;
            border: 1px solid rgba(0,0,0,.06);
            box-shadow: 0 2px 16px rgba(0,0,0,.05);
        }

        /* ── Table ───────────────────────────────────────────── */
        .table-row { transition: background .15s; }
        .table-row:hover { background: #F8FAFC; }

        /* ── Scrollbar ───────────────────────────────────────── */
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 99px; }

        /* ── Gold gradient text ──────────────────────────────── */
        .text-gold {
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ── Notification badge pulse ─────────────────────────── */
        .notif-pulse { animation: notifPulse 2s ease-in-out infinite; }
        @keyframes notifPulse { 0%,100%{box-shadow:0 0 0 0 rgba(239,68,68,.4)} 50%{box-shadow:0 0 0 6px rgba(239,68,68,0)} }

        /* ── Mobile overlay ──────────────────────────────────── */
        #sidebar-overlay { display: none; }
        @media (max-width: 1023px) {
            #sidebar { position: fixed; top:0; left:0; height:100vh; z-index:60; }
            #sidebar-overlay { display: block; }
            #main-content { margin-left: 0 !important; }
        }
    </style>
</head>
<body>
<div class="min-h-screen flex" x-data="adminApp()" x-init="init()">

    {{-- ══════════════════════════════════════════════════════════
         SIDEBAR
         ══════════════════════════════════════════════════════════ --}}
    <aside id="sidebar" :class="{ 'mini': !sidebarOpen, '-translate-x-full lg:translate-x-0': !sidebarOpen && isMobile }"
           class="fixed top-0 left-0 h-screen z-60 flex flex-col transition-all duration-300">

        {{-- Header sidebar --}}
        <div class="flex items-center justify-between px-4 py-5 flex-shrink-0"
             style="border-bottom: 1px solid rgba(255,255,255,.07);">

            {{-- Logo --}}
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 min-w-0">
                <img src="https://res.cloudinary.com/dstbqtuxm/image/upload/v1782085416/ChatGPT_Image_Jun_21__2026__07_24_51_PM-removebg-preview_zi77k0.png"
                     alt="Logo ExpoDakar"
                     class="logo-glow w-9 h-9 rounded-xl object-contain flex-shrink-0">
                <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     class="nav-label">
                    <p class="font-bold text-white text-sm leading-tight">ExpoDakar</p>
                    <p class="text-xs" style="color:rgba(255,255,255,.35);">Admin Panel</p>
                </div>
            </a>

            {{-- Toggle --}}
            <button @click="sidebarOpen = !sidebarOpen; localStorage.setItem('sidebar', sidebarOpen)"
                    class="flex items-center justify-center w-7 h-7 rounded-lg transition-colors flex-shrink-0"
                    style="color:rgba(255,255,255,.4);"
                    onmouseover="this.style.background='rgba(255,255,255,.08)'; this.style.color='white'"
                    onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,.4)'"
                    aria-label="Réduire la sidebar">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/>
                </svg>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-3 pb-4" style="scrollbar-width: none;">
            @php $currentRoute = request()->route()->getName(); @endphp

            {{-- Groupe GÉNÉRAL --}}
            <div class="nav-group">Général</div>

            <a href="{{ route('dashboard') }}"
               class="nav-link {{ $currentRoute === 'dashboard' ? 'nav-active' : '' }}">
                <span class="nav-dot"></span>

                <span class="nav-label">Dashboard</span>
            </a>

            {{-- Groupe GESTION --}}
            <div class="nav-group">Gestion</div>

            <a href="{{ route('users.index') }}"
               class="nav-link {{ str_contains($currentRoute, 'users') ? 'nav-active' : '' }}">
                <span class="nav-dot"></span>

                <span class="nav-label">Utilisateurs</span>
            </a>

            <a href="{{ route('events.index') }}"
               class="nav-link {{ str_contains($currentRoute, 'events') ? 'nav-active' : '' }}">
                <span class="nav-dot"></span>

                <span class="nav-label">Événements</span>
            </a>

            <a href="{{ route('exposants.index') }}"
               class="nav-link {{ str_contains($currentRoute, 'exposants') ? 'nav-active' : '' }}">
                <span class="nav-dot"></span>

                <span class="nav-label">Exposants</span>
            </a>

            <a href="{{ route('categories.index') }}"
               class="nav-link {{ str_contains($currentRoute, 'categories') ? 'nav-active' : '' }}">
                <span class="nav-dot"></span>
   
                <span class="nav-label">Catégories</span>
            </a>

        </nav>

        {{-- Bas sidebar – Avatar utilisateur --}}
        <div class="flex-shrink-0 px-3 py-4" style="border-top: 1px solid rgba(255,255,255,.07);">
            <a href="{{ route('profile.edit') }}"
               class="flex items-center gap-3 p-2 rounded-xl transition-colors group"
               style="color:rgba(255,255,255,.6);"
               onmouseover="this.style.background='rgba(255,255,255,.06)'; this.style.color='white'"
               onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,.6)'">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=2563EB&color=fff&size=80"
                     alt="{{ Auth::user()->name }}"
                     class="w-8 h-8 rounded-lg flex-shrink-0">
                <div class="nav-label min-w-0">
                    <p class="text-xs font-semibold text-white truncate leading-tight">{{ Auth::user()->name }}</p>
                    <p class="text-xs" style="color:rgba(255,255,255,.35);">Administrateur</p>
                </div>
            </a>
        </div>
    </aside>

    {{-- Overlay mobile --}}
    <div id="sidebar-overlay"
         x-show="sidebarOpen && isMobile"
         x-cloak
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/50 z-50 lg:hidden"
         style="backdrop-filter: blur(2px);">
    </div>


    {{-- ══════════════════════════════════════════════════════════
         MAIN CONTENT
         ══════════════════════════════════════════════════════════ --}}
    <div id="main-content"
         class="flex-1 flex flex-col min-h-screen transition-all duration-300"
         :style="sidebarOpen ? 'margin-left: var(--sidebar-w)' : 'margin-left: var(--sidebar-mini)'">

        {{-- ════════════════════════════════════════════════════
             TOPBAR
             ════════════════════════════════════════════════════ --}}
        <header id="topbar" class="sticky top-0 z-40 px-6 py-0 flex items-center justify-between"
                style="height: 64px;">

            {{-- Gauche : bouton mobile + titre --}}
            <div class="flex items-center gap-4 min-w-0">

                {{-- Burger mobile --}}
                <button @click="sidebarOpen = !sidebarOpen"
                        class="lg:hidden flex items-center justify-center w-9 h-9 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-100 transition-colors"
                        aria-label="Menu">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/>
                    </svg>
                </button>

                {{-- Titre page --}}
                <div class="min-w-0">
                    <h1 class="font-bold text-lg text-slate-800 leading-tight truncate">
                        @yield('title', 'Tableau de bord')
                    </h1>
                    <p class="text-xs text-slate-400 leading-tight truncate">
                        @yield('subtitle', 'Bienvenue sur ExpoDakar Admin')
                    </p>
                </div>
            </div>

            {{-- Droite : date + recherche + notifs + profil --}}
            <div class="flex items-center gap-3">

                {{-- Date --}}
                <div class="hidden md:flex items-center gap-1.5 text-xs text-slate-400 bg-slate-50 border border-slate-100 px-3 py-1.5 rounded-lg">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75"/>
                    </svg>
                    <span x-text="currentDate">{{ now()->translatedFormat('d M Y') }}</span>
                </div>

                {{-- Recherche rapide --}}
                <div x-data="{ searchOpen: false }" class="relative">
                    <button @click="searchOpen = !searchOpen"
                            class="flex items-center gap-2 text-xs text-slate-500 bg-slate-50 border border-slate-200 hover:border-slate-300 px-3 py-2 rounded-xl transition-colors"
                            aria-label="Recherche">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607z"/>
                        </svg>
                        <span class="hidden sm:inline">Rechercher…</span>
                        <kbd class="hidden sm:inline text-slate-300 font-mono text-xs border border-slate-200 px-1 rounded">⌘K</kbd>
                    </button>
                    <div x-show="searchOpen" x-cloak @click.outside="searchOpen = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         class="absolute right-0 top-12 w-80 bg-white rounded-2xl shadow-xl border border-slate-100 p-4 z-50">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607z"/>
                            </svg>
                            <input type="text" placeholder="Rechercher un événement, utilisateur…"
                                   class="w-full pl-9 pr-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                        </div>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <a href="{{ route('events.index') }}"  class="text-xs px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">Événements</a>
                            <a href="{{ route('users.index') }}"   class="text-xs px-3 py-1.5 rounded-lg bg-slate-50 text-slate-600 hover:bg-slate-100 transition-colors">Utilisateurs</a>
                            <a href="{{ route('exposants.index') }}" class="text-xs px-3 py-1.5 rounded-lg bg-slate-50 text-slate-600 hover:bg-slate-100 transition-colors">Exposants</a>
                        </div>
                    </div>
                </div>

                {{-- Notifications --}}
                <div x-data="{ notifOpen: false }" class="relative">
                    <button @click="notifOpen = !notifOpen"
                            class="relative flex items-center justify-center w-9 h-9 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-500 transition-colors"
                            aria-label="Notifications">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/>
                        </svg>
                        <span class="notif-pulse absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold">3</span>
                    </button>

                    <div x-show="notifOpen" x-cloak @click.outside="notifOpen = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         class="absolute right-0 top-12 w-80 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden z-50">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-50">
                            <p class="text-sm font-semibold text-slate-800">Notifications</p>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-red-50 text-red-600 font-semibold">3 nouvelles</span>
                        </div>
                        @foreach([
                            ['🎪','Nouvel événement créé','Forum Tech Dakar 2025','2 min'],
                            ['👤','Nouvel utilisateur inscrit','Aminata Diallo vient de rejoindre','15 min'],
                            ['🏢','Exposant vérifié','TechHub Dakar validé','1h'],
                        ] as [$icon, $title, $desc, $time])
                        <div class="flex items-start gap-3 px-4 py-3 border-b border-slate-50 hover:bg-slate-50 transition-colors cursor-pointer">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg flex-shrink-0" style="background:#F1F5F9;">{{ $icon }}</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-slate-800">{{ $title }}</p>
                                <p class="text-xs text-slate-400 truncate">{{ $desc }}</p>
                            </div>
                            <span class="text-xs text-slate-300 flex-shrink-0">{{ $time }}</span>
                        </div>
                        @endforeach
                        <div class="px-4 py-3 text-center">
                            <a href="#" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Voir toutes les notifications</a>
                        </div>
                    </div>
                </div>

                {{-- Profile dropdown --}}
                <div class="relative" x-data="{ profileOpen: false }">
                    <button @click="profileOpen = !profileOpen"
                            class="flex items-center gap-2.5 pl-1 pr-3 py-1 rounded-xl border border-slate-200 bg-slate-50 hover:bg-white hover:shadow-sm transition-all"
                            :aria-expanded="profileOpen">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=2563EB&color=fff&size=80"
                             alt="{{ Auth::user()->name }}"
                             class="w-7 h-7 rounded-lg">
                        <div class="text-left hidden sm:block">
                            <p class="text-xs font-semibold text-slate-700 leading-tight">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-400 leading-tight">Admin</p>
                        </div>
                        <svg class="w-3.5 h-3.5 text-slate-400 hidden sm:block transition-transform duration-200"
                             :class="profileOpen ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </button>

                    <div x-show="profileOpen" x-cloak @click.outside="profileOpen = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         class="absolute right-0 top-12 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden z-50">

                        {{-- User info --}}
                        <div class="px-4 py-3 border-b border-slate-50">
                            <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-400">{{ Auth::user()->email }}</p>
                        </div>

                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                </svg>
                                Mon profil
                            </a>
                            <a href="{{ route('dashboard') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6Z"/>
                                </svg>
                                Dashboard
                            </a>
                        </div>

                        <div class="py-1 border-t border-slate-50">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
                                    </svg>
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </header>

        {{-- ════════════════════════════════════════════════════
             CONTENT
             ════════════════════════════════════════════════════ --}}
        <main class="flex-1 p-6 lg:p-8">

            {{-- Flash messages --}}
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-init="setTimeout(() => show = false, 4000)"
                 class="flex items-center gap-3 px-4 py-3 mb-6 rounded-xl text-sm font-medium text-emerald-700"
                 style="background:#ECFDF5; border:1px solid #A7F3D0;">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                {{ session('success') }}
                <button @click="show = false" class="ml-auto text-emerald-600 hover:text-emerald-800" aria-label="Fermer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            @endif

            @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-cloak
                 x-init="setTimeout(() => show = false, 5000)"
                 class="flex items-center gap-3 px-4 py-3 mb-6 rounded-xl text-sm font-medium text-red-700"
                 style="background:#FEF2F2; border:1px solid #FECACA;">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
                </svg>
                {{ session('error') }}
                <button @click="show = false" class="ml-auto text-red-600 hover:text-red-800" aria-label="Fermer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            @endif

            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="px-6 lg:px-8 py-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2">
            <p class="text-xs text-slate-400">© {{ date('Y') }} ExpoDakar Admin · Tous droits réservés</p>
            <p class="text-xs text-slate-300">Laravel 12 · Blade · Tailwind CSS</p>
        </footer>

    </div>
    {{-- /main-content --}}

</div>

{{-- ══════════════════════════════════════════════════════════════
     CHART.JS GLOBAL CONFIG
     ══════════════════════════════════════════════════════════════ --}}
<script>
    // Global Chart.js defaults
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof Chart !== 'undefined') {
            Chart.defaults.font.family = 'Inter';
            Chart.defaults.font.size   = 12;
            Chart.defaults.color       = '#94A3B8';
            Chart.defaults.plugins.legend.display = false;
        }

        // Revenue chart (si l'élément existe sur la page)
        const revenueEl = document.getElementById('revenueChart');
        if (revenueEl) {
            new Chart(revenueEl, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
                    datasets: [{
                        label: 'Revenus (FCFA)',
                        data: [400000, 650000, 900000, 750000, 1200000, 1500000],
                        borderColor: '#2563EB',
                        backgroundColor: 'rgba(37,99,235,.08)',
                        fill: true,
                        tension: 0.45,
                        borderWidth: 2.5,
                        pointBackgroundColor: '#2563EB',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0F172A',
                            titleFont: { weight: '600' },
                            padding: 12,
                            cornerRadius: 10,
                            callbacks: {
                                label: ctx => ' ' + ctx.parsed.y.toLocaleString('fr-FR') + ' FCFA'
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, border: { display: false } },
                        y: {
                            grid: { color: 'rgba(0,0,0,.04)' },
                            border: { display: false },
                            ticks: { callback: v => (v/1000)+'k' }
                        }
                    }
                }
            });
        }
    });
</script>

{{-- Alpine.js App --}}
<script>
    function adminApp() {
        return {
            sidebarOpen: true,
            isMobile: false,
            currentDate: '',

            init() {
                // Restore sidebar state from localStorage
                const saved = localStorage.getItem('sidebar');
                if (saved !== null) this.sidebarOpen = saved === 'true';

                // Responsive check
                this.checkMobile();
                window.addEventListener('resize', () => this.checkMobile());

                // Date
                const now = new Date();
                this.currentDate = now.toLocaleDateString('fr-FR', {
                    day: 'numeric', month: 'short', year: 'numeric'
                });
            },

            checkMobile() {
                this.isMobile = window.innerWidth < 1024;
                if (this.isMobile) this.sidebarOpen = false;
            }
        };
    }
</script>

</body>
</html>
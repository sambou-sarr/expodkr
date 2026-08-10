{{--
|--------------------------------------------------------------------------
| ExpoDKR – Tous les événements (Admin / Visiteur)
| Route : user.events.index  |  Variables : $events (paginate), $categories, $exposants
| Laravel 12 • Blade • Tailwind CSS CDN • Alpine.js 3
|--------------------------------------------------------------------------
--}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tous les événements – ExpoDKR</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --blue-night:    #0A1628;
            --blue-deep:     #0D2145;
            --blue-electric: #1E5FD8;
            --gold:          #C9A84C;
            --gold-light:    #E8C96A;
            --pearl:         #F7F8FC;
            --gray-soft:     #EEF0F6;
            --gray-mid:      #8892A4;
            --danger:        #DC2626;
            --success:       #10B981;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--pearl);
            color: var(--blue-night);
            -webkit-font-smoothing: antialiased;
        }

        .font-display { font-family: 'Instrument Serif', serif; }
        [x-cloak]     { display: none !important; }

        .text-gold-gradient {
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ── Navbar ──────────────────────────────────────────── */
        .navbar-solid { background: var(--blue-night); box-shadow: 0 2px 24px rgba(10,22,40,.18); }

        /* ── Glass panel ─────────────────────────────────────── */
        .glass {
            background: rgba(255,255,255,.78);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,.6);
        }

        /* ── Event card ──────────────────────────────────────── */
        .event-card {
            transition: transform .3s cubic-bezier(.2,.8,.2,1), box-shadow .3s ease;
        }
        .event-card:hover {
            transform: scale(1.03);
            box-shadow: 0 20px 50px rgba(30,95,216,.18), 0 0 0 1px rgba(30,95,216,.08);
        }
        .event-img-wrap { overflow: hidden; }
        .event-img-wrap img { transition: transform .5s ease; }
        .event-card:hover .event-img-wrap img { transform: scale(1.08); }

        /* ── Reveal ──────────────────────────────────────────── */
        .reveal { opacity: 0; transform: translateY(20px); transition: opacity .55s ease, transform .55s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }

        /* ── Status badges ───────────────────────────────────── */
        .badge-live {
            background: #FEF2F2; color: #DC2626;
        }
        .badge-live .dot { background: #DC2626; animation: livePulse 1.4s infinite; }
        @keyframes livePulse { 0%,100%{opacity:1; transform:scale(1)} 50%{opacity:.5; transform:scale(1.3)} }
        .badge-upcoming { background: #ECFDF5; color: #059669; }
        .badge-past     { background: var(--gray-soft); color: var(--gray-mid); }

        /* ── Action buttons in card footer ───────────────────── */
        .btn-edit:hover   { background: var(--blue-soft, #EFF6FF); border-color: var(--blue-electric); color: var(--blue-electric); }
        .btn-delete:hover { background: #FEF2F2; border-color: var(--danger); color: var(--danger); }

        /* ── Filter pill ─────────────────────────────────────── */
        .filter-select {
            appearance: none;
            background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="%238892A4" stroke-width="2"%3e%3cpath stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/%3e%3c/svg%3e');
            background-repeat: no-repeat;
            background-position: right .75rem center;
            background-size: 1rem;
        }

        /* ── Skeleton loading ─────────────────────────────────── */
        .skeleton {
            background: linear-gradient(90deg, #EEF0F6 25%, #F7F8FC 37%, #EEF0F6 63%);
            background-size: 400% 100%;
            animation: skeletonShimmer 1.4s ease infinite;
        }
        @keyframes skeletonShimmer {
            0%   { background-position: 100% 50%; }
            100% { background-position: 0 50%; }
        }

        /* ── Search focus ────────────────────────────────────── */
        .search-input:focus { outline: none; box-shadow: 0 0 0 3px rgba(30,95,216,.18); border-color: var(--blue-electric); }

        /* ── Scrollbar ────────────────────────────────────────── */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--pearl); }
        ::-webkit-scrollbar-thumb { background: var(--blue-electric); border-radius: 99px; }

        *:focus-visible { outline: 2px solid var(--blue-electric); outline-offset: 2px; border-radius: 6px; }

        /* ── Pagination ───────────────────────────────────────── */
        .page-link {
            transition: background .2s, color .2s, border-color .2s;
        }
    </style>
</head>
<body>

<div x-data="{
        searchOpen: false,
        filtersOpen: false,
    }">


{{-- ══════════════════════════════════════════════════════════════
     NAVBAR img
     ══════════════════════════════════════════════════════════════ --}}
<header class="navbar-solid sticky top-0 z-50" role="banner">
    <div class="max-w-7xl mx-auto px-6 lg:px-16">
        <div class="flex items-center justify-between h-18 py-3">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="ExpoDKR – Accueil">
                    <span style="display:flex;align-items:center;justify-content:center;width:2.25rem;height:2.25rem;border-radius:.625rem;background:background: linear-gradient(135deg, #3B82F6, #1E5FD8);">
            <img src="https://res.cloudinary.com/dstbqtuxm/image/upload/v1786364683/ChatGPT_Image_10_ao%C3%BBt_2026__02_24_21-removebg-preview_spadbb.png" alt="Logo ExpoDakar" class="h-12 w-auto object-contain">

            </span>
                <span class="font-display text-xl text-white">Expo<span class="text-gold-gradient">DKR</span></span>
            </a>

            {{-- Nav desktop --}}
            <nav class="hidden lg:flex items-center gap-7" aria-label="Navigation principale">
                <a href="{{ route('user.events.index') }}"    class="text-sm font-medium text-white border-b border-white/30 pb-0.5">Événements</a>
                <a href="" class="text-sm font-medium text-white/70 hover:text-white transition-colors">Exposants</a>
                <a href="/#categories"                   class="text-sm font-medium text-white/70 hover:text-white transition-colors">Catégories</a>
            </nav>

            {{-- Actions --}}
   

            <button class="lg:hidden w-9 h-9 flex items-center justify-center rounded-lg text-white hover:bg-white/10" aria-label="Menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>
</header>


{{-- ══════════════════════════════════════════════════════════════
     HEADER DE PAGE
     ══════════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden" style="background: linear-gradient(180deg, var(--blue-night) 0%, var(--blue-deep) 65%, var(--pearl) 100%);">

    {{-- Décor grille dorée --}}
    <div class="absolute inset-0 opacity-10" aria-hidden="true"
         style="background-image: linear-gradient(rgba(196,168,76,.4) 1px,transparent 1px), linear-gradient(90deg,rgba(196,168,76,.4) 1px,transparent 1px); background-size: 56px 56px;"></div>
    <div class="absolute -top-20 -right-20 w-96 h-96 rounded-full opacity-15" style="background: var(--blue-electric); filter: blur(100px);" aria-hidden="true"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-16 pt-14 pb-24">

        {{-- Titre + sous-titre --}}
        <div class="max-w-2xl mb-10">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-white/15 bg-white/5 backdrop-blur-sm mb-5">
                <span class="w-1.5 h-1.5 rounded-full" style="background: var(--gold);"></span>
                <span class="text-xs font-semibold tracking-widest uppercase" style="color: var(--gold-light);">Catalogue ExpoDKR</span>
            </div>
            <h1 class="font-display text-4xl lg:text-5xl text-white leading-tight mb-3">
                Tous les événements
            </h1>
            <p class="text-white/55 text-base">
                Découvrez les événements disponibles en temps réel sur la plateforme.
            </p>
        </div>

        {{-- Top action bar : recherche + boutons --}}
        <div class="flex flex-col lg:flex-row lg:items-center gap-4">

            {{-- Recherche --}}
            <form action="{{ route('user.events.index') }}" method="GET" class="flex-1" role="search" aria-label="Rechercher un événement">
                <div class="relative">
                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none" aria-hidden="true">
                        <svg class="w-5 h-5 text-white/40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607z"/>
                        </svg>
                    </div>
                    <input type="search" name="q" value="{{ request('q') }}"
                           placeholder="Rechercher par titre, lieu, exposant…"
                           class="search-input w-full pl-12 pr-4 py-4 rounded-2xl bg-white/10 backdrop-blur text-white placeholder-white/40 border border-white/15 text-sm transition"
                           aria-label="Recherche">
                </div>
            </form>

            {{-- Boutons d'action --}}
            <div class="flex items-center gap-3 flex-wrap">
                <a href="/"
                   class="inline-flex items-center gap-2 px-5 py-3.5 rounded-2xl text-sm font-semibold border border-white/20 text-white transition-all hover:bg-white/10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                    </svg>
                    Retour 
                </a>
            </div>
        </div>

        {{-- Filtres --}}
        <form action="{{ route('user.events.index') }}" method="GET" class="flex flex-wrap items-center gap-3 mt-6">
            <input type="hidden" name="q" value="{{ request('q') }}">

            {{-- Catégorie --}}
            <select name="categorie" onchange="this.form.submit()"
                    class="filter-select text-sm font-medium px-4 py-2.5 pr-9 rounded-xl border border-white/15 bg-white/10 text-white backdrop-blur cursor-pointer transition hover:bg-white/15">
                <option value="" class="text-black">Toutes les catégories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" class="text-black" @selected(request('categorie') == $cat->id)>{{ $cat->nom }}</option>
                @endforeach
            </select>

            {{-- Date --}}
            <select name="periode" onchange="this.form.submit()"
                    class="filter-select text-sm font-medium px-4 py-2.5 pr-9 rounded-xl border border-white/15 bg-white/10 text-white backdrop-blur cursor-pointer transition hover:bg-white/15">
                <option value="" class="text-black">Toutes les dates</option>
                <option value="upcoming" class="text-black" @selected(request('periode') == 'upcoming')>À venir</option>
                <option value="ongoing"  class="text-black" @selected(request('periode') == 'ongoing')>En cours</option>
                <option value="past"     class="text-black" @selected(request('periode') == 'past')>Terminés</option>
            </select>

            {{-- Prix --}}
            <select name="prix" onchange="this.form.submit()"
                    class="filter-select text-sm font-medium px-4 py-2.5 pr-9 rounded-xl border border-white/15 bg-white/10 text-white backdrop-blur cursor-pointer transition hover:bg-white/15">
                <option value="" class="text-black">Tous les tarifs</option>
                <option value="free"  class="text-black" @selected(request('prix') == 'free')>Gratuit</option>
                <option value="paid"  class="text-black" @selected(request('prix') == 'paid')>Payant</option>
            </select>

            {{-- Exposant --}}
            <select name="exposant" onchange="this.form.submit()"
                    class="filter-select text-sm font-medium px-4 py-2.5 pr-9 rounded-xl border border-white/15 bg-white/10 text-white backdrop-blur cursor-pointer transition hover:bg-white/15">
                <option value="" class="text-black">Tous les exposants</option>
                @foreach($exposants as $exp)
                <option value="{{ $exp->id }}" class="text-black" @selected(request('exposant') == $exp->id)>{{ $exp->nom }}</option>
                @endforeach
            </select>

            {{-- Reset filtres --}}
            @if(request()->anyFilled(['categorie','periode','prix','exposant','q']))
            <a href="{{ route('user.events.index') }}"
               class="text-xs font-semibold px-3 py-2.5 rounded-xl text-white/60 hover:text-white transition-colors flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
                Réinitialiser
            </a>
            @endif
        </form>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     SKELETON LOADING (affiché brièvement via Alpine au chargement)
     ══════════════════════════════════════════════════════════════ --}}
<div x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 350)">

    <div x-show="loading" x-cloak class="max-w-7xl mx-auto px-6 lg:px-16 -mt-10 relative z-10 pb-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-7">
            @for ($i = 0; $i < 6; $i++)
            <div class="rounded-2xl overflow-hidden bg-white" style="box-shadow: 0 4px 24px rgba(10,22,40,.06);">
                <div class="skeleton h-48"></div>
                <div class="p-6 flex flex-col gap-3">
                    <div class="skeleton h-3 w-1/3 rounded-full"></div>
                    <div class="skeleton h-4 w-4/5 rounded-full"></div>
                    <div class="skeleton h-3 w-2/3 rounded-full"></div>
                    <div class="skeleton h-9 w-full rounded-xl mt-2"></div>
                </div>
            </div>
            @endfor
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════════════
         LISTE DES ÉVÉNEMENTS
         ══════════════════════════════════════════════════════════════ --}}
    <main x-show="!loading" x-cloak class="max-w-7xl mx-auto px-6 lg:px-16 -mt-10 relative z-10 pb-20">

        {{-- Compteur résultats --}}
        <div class="flex items-center justify-between mb-6">
            <p class="text-sm" style="color: var(--gray-mid);">
                <span class="font-semibold" style="color: var(--blue-night);">{{ $events->total() ?? $events->count() }}</span>
                événement{{ ($events->total() ?? $events->count()) > 1 ? 's' : '' }} trouvé{{ ($events->total() ?? $events->count()) > 1 ? 's' : '' }}
            </p>
        </div>

        @forelse($events as $idx => $event)
        @php
            $now   = now();
            $debut = \Carbon\Carbon::parse($event->date_debut);
            $fin   = \Carbon\Carbon::parse($event->date_fin);
            if ($now->lt($debut)) {
                $statusLabel = 'À venir'; $statusClass = 'badge-upcoming'; $statusDot = '#059669';
            } elseif ($now->between($debut, $fin)) {
                $statusLabel = 'LIVE'; $statusClass = 'badge-live'; $statusDot = '#DC2626';
            } else {
                $statusLabel = 'Terminé'; $statusClass = 'badge-past'; $statusDot = '#9CA3AF';
            }
        @endphp

        @if($loop->first)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-7">
        @endif

            {{-- ════════════════════════════════════════════════
                 CARD ÉVÉNEMENT
                 ════════════════════════════════════════════════ --}}
            <article class="event-card reveal bg-white rounded-2xl overflow-hidden flex flex-col"
                      style="box-shadow: 0 4px 24px rgba(10,22,40,.07); transition-delay: {{ ($idx % 6) * 0.06 }}s;"
                      aria-label="{{ $event->titre }}">

                {{-- Image cover --}}
                <div class="event-img-wrap relative h-48">
                    @if($event->image)
                        <img src="{{ $event->image }}" alt="{{ $event->titre }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center"
                             style="background: linear-gradient(135deg, var(--blue-night), var(--blue-electric));">
                            <svg class="w-10 h-10 text-white/25" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Z"/>
                            </svg>
                        </div>
                    @endif

                    {{-- Badge catégorie --}}
                    @if($event->categorie)
                    <span class="absolute top-3 left-3 px-2.5 py-1 text-xs font-semibold rounded-full backdrop-blur-sm"
                          style="background: rgba(10,22,40,.72); color: var(--gold-light);">
                        {{ $event->categorie->nom }}
                    </span>
                    @endif

                    {{-- Badge date --}}
                    <div class="absolute top-3 right-3 flex flex-col items-center justify-center w-11 h-11 rounded-xl bg-white shadow-md">
                        <span class="text-xs font-bold leading-none" style="color: var(--blue-electric);">
                            {{ $debut->format('d') }}
                        </span>
                        <span class="text-xs uppercase leading-none mt-0.5" style="color: var(--gray-mid);">
                            {{ $debut->translatedFormat('M') }}
                        </span>
                    </div>

                    {{-- Badge statut --}}
                    <span class="{{ $statusClass }} absolute bottom-3 left-3 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold">
                        <span class="dot w-1.5 h-1.5 rounded-full" style="background: {{ $statusDot }};"></span>
                        {{ $statusLabel }}
                    </span>
                </div>

                {{-- Corps card --}}
                <div class="p-6 flex flex-col flex-1">

                    {{-- Exposant --}}
                    @if($event->exposant)
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-6 h-6 rounded-full overflow-hidden flex items-center justify-center flex-shrink-0"
                             style="background: var(--pearl);">
                            @if($event->exposant->logo)
                                <img src="{{ $event->exposant->logo }}" alt="" class="w-full h-full object-cover">
                            @else
                                <span class="text-xs font-bold" style="color: var(--blue-electric);">
                                    {{ strtoupper(substr($event->exposant->nom, 0, 1)) }}
                                </span>
                            @endif
                        </div>
                        <span class="text-xs font-medium" style="color: var(--gray-mid);">{{ $event->exposant->nom }}</span>
                    </div>
                    @endif

                    {{-- Titre --}}
                    <h3 class="font-semibold text-base leading-snug mb-2 line-clamp-2" style="color: var(--blue-night);">
                        {{ $event->titre }}
                    </h3>

                    {{-- Lieu --}}
                    <div class="flex items-center gap-1.5 text-xs mb-3" style="color: var(--gray-mid);">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                        </svg>
                        {{ $event->lieu }}
                    </div>

                    {{-- Description courte --}}
                    @if($event->description)
                    <p class="text-sm leading-relaxed mb-5 flex-1" style="color: #6B7280;">
                        {{ Str::limit($event->description, 120) }}
                    </p>
                    @else
                    <div class="flex-1 mb-5"></div>
                    @endif

                    {{-- Footer actions --}}
                    <div class="flex items-center gap-2 pt-4 border-t" style="border-color: var(--gray-soft);">
                        <a href="{{ route('user.events.show', $event->id) }}"
                           class="flex-1 text-center py-2.5 rounded-xl text-xs font-semibold transition-all hover:brightness-110 active:scale-95"
                           style="background: linear-gradient(135deg, var(--blue-electric), #1248b0); color: white;">
                            Voir détails
                        </a>
                    </div>
                </div>
            </article>

        @if($loop->last)
        </div>
        @endif

        @empty
        {{-- ════════════════════════════════════════════════
             ÉTAT VIDE
             ════════════════════════════════════════════════ --}}
        <div class="flex flex-col items-center justify-center text-center py-24 px-6">
            <div class="w-20 h-20 rounded-2xl flex items-center justify-center mb-6" style="background: var(--gray-soft);">
                <svg class="w-9 h-9" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="color: var(--gray-mid);" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                </svg>
            </div>
            <h3 class="font-display text-2xl mb-2" style="color: var(--blue-night);">
                Aucun événement disponible pour le moment
            </h3>
            <p class="text-sm mb-8 max-w-sm" style="color: var(--gray-mid);">
                @if(request()->anyFilled(['q','categorie','periode','prix','exposant']))
                    Essayez de modifier vos filtres ou votre recherche pour trouver des événements.
                @else
                    Revenez bientôt — de nouveaux événements seront publiés régulièrement.
                @endif
            </p>
            <div class="flex items-center gap-3">
                @if(request()->anyFilled(['q','categorie','periode','prix','exposant']))
                <a href="{{ route('user.events.index') }}"
                   class="px-5 py-2.5 rounded-xl text-sm font-semibold border transition-colors hover:bg-gray-50"
                   style="border-color: var(--gray-soft); color: var(--blue-night);">
                    Réinitialiser les filtres
                </a>
                @endif
                <a href="{{ route('events.create') }}"
                   class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all hover:brightness-110"
                   style="background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: var(--blue-night);">
                    Créer un événement
                </a>
            </div>
        </div>
        @endforelse


        {{-- ══════════════════════════════════════════════════════════════
             PAGINATION
             ══════════════════════════════════════════════════════════════ --}}
        @if(method_exists($events, 'links') && $events->hasPages())
        <nav class="flex items-center justify-center gap-2 mt-14" aria-label="Pagination">

            {{-- Précédent --}}
            @if($events->onFirstPage())
                <span class="page-link w-10 h-10 flex items-center justify-center rounded-xl border cursor-not-allowed"
                      style="border-color: var(--gray-soft); color: var(--gray-mid); opacity:.5;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                    </svg>
                </span>
            @else
                <a href="{{ $events->previousPageUrl() }}"
                   class="page-link w-10 h-10 flex items-center justify-center rounded-xl border hover:border-blue-300"
                   style="border-color: var(--gray-soft); color: var(--blue-night);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                    </svg>
                </a>
            @endif

            {{-- Numéros de page --}}
            @foreach($events->getUrlRange(max(1, $events->currentPage() - 2), min($events->lastPage(), $events->currentPage() + 2)) as $page => $url)
                @if($page == $events->currentPage())
                    <span class="page-link w-10 h-10 flex items-center justify-center rounded-xl text-sm font-semibold text-white"
                          style="background: linear-gradient(135deg, var(--blue-electric), #1248b0);">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}"
                       class="page-link w-10 h-10 flex items-center justify-center rounded-xl border text-sm font-medium hover:border-blue-300"
                       style="border-color: var(--gray-soft); color: var(--blue-night);">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Suivant --}}
            @if($events->hasMorePages())
                <a href="{{ $events->nextPageUrl() }}"
                   class="page-link w-10 h-10 flex items-center justify-center rounded-xl border hover:border-blue-300"
                   style="border-color: var(--gray-soft); color: var(--blue-night);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                    </svg>
                </a>
            @else
                <span class="page-link w-10 h-10 flex items-center justify-center rounded-xl border cursor-not-allowed"
                      style="border-color: var(--gray-soft); color: var(--gray-mid); opacity:.5;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                    </svg>
                </span>
            @endif
        </nav>
        @endif

    </main>
</div>
{{-- /skeleton wrapper --}}


{{-- ══════════════════════════════════════════════════════════════
     FOOTER MINIMAL
     ══════════════════════════════════════════════════════════════ --}}
<footer class="border-t" style="border-color: var(--gray-soft); background: white;" role="contentinfo">
    <div class="max-w-7xl mx-auto px-6 lg:px-16 py-8 flex flex-wrap items-center justify-between gap-4">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5" aria-label="ExpoDKR">
            <span style="display:flex;align-items:center;justify-content:center;width:2.25rem;height:2.25rem;border-radius:.625rem;background:background: linear-gradient(135deg, #3B82F6, #1E5FD8);">
             <img src="https://res.cloudinary.com/dstbqtuxm/image/upload/v1782085416/ChatGPT_Image_Jun_21__2026__07_24_51_PM-removebg-preview_zi77k0.png"  alt="Logo ExpoDakar" class="h-12 w-auto object-contain">

            </span>
            <span class="font-display text-lg" style="color: var(--blue-night);">Expo<span style="color: var(--blue-electric);">DKR</span></span>
        </a>
        <p class="text-xs" style="color: var(--gray-mid);">© {{ date('Y') }} ExpoDKR · Tous droits réservés</p>
    </div>
</footer>

</div>
{{-- /app x-data --}}


{{-- Script reveal scroll --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const observeReveal = () => {
        const els = document.querySelectorAll('.reveal:not(.visible)');
        if ('IntersectionObserver' in window) {
            const io = new IntersectionObserver((entries) => {
                entries.forEach(e => {
                    if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); }
                });
            }, { threshold: 0.08 });
            els.forEach(el => io.observe(el));
        } else {
            els.forEach(el => el.classList.add('visible'));
        }
    };
    // Petit délai pour laisser le skeleton disparaître avant d'observer
    setTimeout(observeReveal, 400);
});
</script>

</body>
</html>
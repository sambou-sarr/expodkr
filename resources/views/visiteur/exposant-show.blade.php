{{--
|--------------------------------------------------------------------------
| ExpoDakar – Page Détail Exposant (standalone)
| Route : exposants.show  |  Variable : $exposant
| Relations disponibles : $exposant->evenements, $exposant->categorie
| Laravel 12 • Blade • Tailwind CSS CDN • Alpine.js 3
|--------------------------------------------------------------------------
--}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ Str::limit($exposant->description ?? '', 155) }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:title"       content="{{ $exposant->nom }} – ExpoDakar">
    <meta property="og:description" content="{{ Str::limit($exposant->description ?? '', 155) }}">
    @if($exposant->logo)
    <meta property="og:image"       content="{{ $exposant->logo }}">
    @endif
    <title>{{ $exposant->nom }} – ExpoDakar</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #fff;
            color: var(--blue-night);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .font-display { font-family: 'Instrument Serif', serif; }
        [x-cloak]     { display: none !important; }

        /* Gold gradient */
        .text-gold-gradient {
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Navbar */
        .navbar-transparent { background: transparent; }
        .navbar-solid       { background: var(--blue-night); box-shadow: 0 2px 24px rgba(10,22,40,.18); }

        /* Reveal */
        .reveal { opacity: 0; transform: translateY(24px); transition: opacity .65s ease, transform .65s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .reveal-d1 { transition-delay: .08s; }
        .reveal-d2 { transition-delay: .16s; }
        .reveal-d3 { transition-delay: .24s; }

        /* Card lift */
        .card-lift { transition: transform .28s ease, box-shadow .28s ease; }
        .card-lift:hover { transform: translateY(-5px); box-shadow: 0 20px 50px rgba(10,22,40,.12); }

        /* Event card image */
        .event-img-wrap { overflow: hidden; }
        .event-img-wrap img { transition: transform .45s ease; }
        .event-card:hover .event-img-wrap img { transform: scale(1.06); }

        /* Section eyebrow */
        .eyebrow {
            font-size: .7rem;
            font-weight: 600;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--gold);
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--pearl); }
        ::-webkit-scrollbar-thumb { background: var(--blue-electric); border-radius: 99px; }

        *:focus-visible { outline: 2px solid var(--blue-electric); outline-offset: 3px; border-radius: 6px; }
    </style>
</head>
<body>


{{-- ══════════════════════════════════════════════════════════════
     NAVBAR
     ══════════════════════════════════════════════════════════════ --}}
<header
    x-data="{
        open: false,
        scrolled: false,
        init() {
            window.addEventListener('scroll', () => { this.scrolled = window.scrollY > 60; }, { passive: true });
        }
    }"
    :class="scrolled ? 'navbar-solid' : 'navbar-transparent'"
    class="fixed inset-x-0 top-0 z-50 transition-all duration-300"
    role="banner"
>
    <div class="max-w-7xl mx-auto px-6 lg:px-16">
        <div class="flex items-center justify-between h-20">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="ExpoDakar – Accueil">
                <img src="https://res.cloudinary.com/dstbqtuxm/image/upload/v1782085416/ChatGPT_Image_Jun_21__2026__07_24_51_PM-removebg-preview_zi77k0.png"
                     alt="Logo ExpoDakar" class="h-10 w-auto object-contain">
                <span class="font-display text-2xl text-white">
                    Expo<span class="text-gold-gradient">Dakar</span>
                </span>
            </a>

            {{-- Nav desktop --}}
            <nav class="hidden lg:flex items-center gap-8" aria-label="Navigation principale">
                <a href="{{ route('events.index') }}"    class="text-sm font-medium text-white/80 hover:text-white transition-colors">Événements</a>
                <a href="{{ route('exposants.index') }}" class="text-sm font-medium text-white transition-colors border-b border-white/30 pb-0.5">Exposants</a>
                <a href="/#categories"                   class="text-sm font-medium text-white/80 hover:text-white transition-colors">Catégories</a>
                <a href="/#faq"                          class="text-sm font-medium text-white/80 hover:text-white transition-colors">FAQ</a>
            </nav>

            {{-- CTA desktop --}}
            <div class="hidden lg:flex items-center gap-3">
                @guest
                    <a href="{{ route('login') }}"
                       class="text-sm font-medium text-white/80 hover:text-white px-4 py-2 rounded-lg hover:bg-white/10 transition-colors">
                        Connexion
                    </a>
                    <a href="{{ route('register') }}"
                       class="text-sm font-semibold text-white px-5 py-2.5 rounded-xl transition-all"
                       style="background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: var(--blue-night);">
                        S'inscrire
                    </a>
                @endguest
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="text-sm font-semibold text-white px-5 py-2.5 rounded-xl"
                       style="background: linear-gradient(135deg, var(--blue-electric), #1248b0);">
                        Mon espace
                    </a>
                @endauth
            </div>

            {{-- Burger mobile --}}
            <button @click="open = !open"
                    class="lg:hidden flex items-center justify-center w-10 h-10 rounded-lg text-white hover:bg-white/10 transition"
                    :aria-expanded="open" aria-label="Menu">
                <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="open" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Menu mobile --}}
    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="lg:hidden border-t border-white/10"
         style="background: var(--blue-night);">
        <nav class="flex flex-col gap-1 px-6 py-4">
            <a href="{{ route('events.index') }}"    @click="open=false" class="px-4 py-3 text-sm font-medium text-white/80 hover:text-white rounded-lg hover:bg-white/10 transition">Événements</a>
            <a href="{{ route('exposants.index') }}" @click="open=false" class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-white/10">Exposants</a>
            <a href="/#categories"                   @click="open=false" class="px-4 py-3 text-sm font-medium text-white/80 hover:text-white rounded-lg hover:bg-white/10 transition">Catégories</a>
            <a href="/#faq"                          @click="open=false" class="px-4 py-3 text-sm font-medium text-white/80 hover:text-white rounded-lg hover:bg-white/10 transition">FAQ</a>
            <hr class="border-white/10 my-2">
            @guest
                <a href="{{ route('login') }}"    class="px-4 py-3 text-sm font-medium text-white/80 rounded-lg hover:bg-white/10 transition">Connexion</a>
                <a href="{{ route('register') }}" class="mt-1 px-4 py-3 text-sm font-semibold text-center rounded-xl" style="background:linear-gradient(135deg,var(--gold),var(--gold-light));color:var(--blue-night);">S'inscrire</a>
            @endguest
            @auth
                <a href="{{ route('dashboard') }}" class="px-4 py-3 text-sm font-semibold text-center text-white rounded-xl" style="background:linear-gradient(135deg,var(--blue-electric),#1248b0);">Mon espace</a>
            @endauth
        </nav>
    </div>
</header>


{{-- ══════════════════════════════════════════════════════════════
     HERO – Bannière exposant
     ══════════════════════════════════════════════════════════════ --}}
<section class="relative min-h-[60vh] flex flex-col justify-end overflow-hidden"
         style="background: var(--blue-night);"
         x-data="{ visible: false }"
         x-init="setTimeout(() => visible = true, 80)"
         aria-label="Profil exposant">

    {{-- Fond décoratif --}}
    <div class="absolute inset-0 z-0" aria-hidden="true">
        {{-- Grille dorée --}}
        <div class="absolute inset-0 opacity-20"
             style="background-image: linear-gradient(rgba(196,168,76,.15) 1px,transparent 1px), linear-gradient(90deg,rgba(196,168,76,.15) 1px,transparent 1px); background-size: 60px 60px;"></div>
        {{-- Glow bleu --}}
        <div class="absolute -bottom-32 -right-32 w-[500px] h-[500px] rounded-full opacity-15"
             style="background: var(--blue-electric); filter: blur(120px);"></div>
        {{-- Glow doré --}}
        <div class="absolute top-20 -left-20 w-[350px] h-[350px] rounded-full opacity-10"
             style="background: var(--gold); filter: blur(100px);"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-16 pt-36 pb-16 w-full">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 mb-8 text-xs text-white/45" aria-label="Fil d'Ariane"
             :style="visible ? 'opacity:1;transform:translateY(0)' : 'opacity:0;transform:translateY(10px)'"
             style="transition: opacity .6s ease, transform .6s ease;">
            <a href="{{ route('home') }}" class="hover:text-white/80 transition-colors">Accueil</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
            </svg>
            <a href="{{ route('exposants.index') }}" class="hover:text-white/80 transition-colors">Exposants</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
            </svg>
            <span class="text-white/70">{{ Str::limit($exposant->nom, 30) }}</span>
        </nav>

        {{-- Profil hero --}}
        <div class="flex flex-col lg:flex-row lg:items-end gap-8"
             :style="visible ? 'opacity:1;transform:translateY(0)' : 'opacity:0;transform:translateY(20px)'"
             style="transition: opacity .75s ease .1s, transform .75s ease .1s;">

            {{-- Avatar / Logo --}}
            <div class="flex-shrink-0">
                @if($exposant->logo)
                    <div class="w-28 h-28 lg:w-36 lg:h-36 rounded-3xl overflow-hidden border-4 border-white/15 shadow-2xl"
                         style="background: white;">
                        <img src="{{ Storage::url($exposant->logo) }}"
                             alt="Logo {{ $exposant->nom }}"
                             class="w-full h-full object-contain p-3">
                    </div>
                @else
                    <div class="w-28 h-28 lg:w-36 lg:h-36 rounded-3xl flex items-center justify-center border-4 border-white/15 shadow-2xl"
                         style="background: linear-gradient(135deg, var(--blue-electric), var(--blue-deep));">
                        <span class="font-display text-5xl lg:text-6xl text-white">
                            {{ strtoupper(substr($exposant->nom, 0, 1)) }}
                        </span>
                    </div>
                @endif
            </div>

            {{-- Infos principales --}}
            <div class="flex-1 min-w-0">

                {{-- Secteur badge --}}
                @if($exposant->secteur ?? $exposant->secteur_activite ?? null)
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-white/15 bg-white/5 backdrop-blur-sm mb-4">
                    <span class="w-1.5 h-1.5 rounded-full" style="background: var(--gold);"></span>
                    <span class="text-xs font-semibold tracking-widest uppercase" style="color: var(--gold-light);">
                        {{ $exposant->secteur ?? $exposant->secteur_activite }}
                    </span>
                </div>
                @endif

                {{-- Nom --}}
                <h1 class="font-display text-4xl lg:text-6xl text-white leading-tight mb-4">
                    {{ $exposant->nom ?? $exposant->nom_entreprise }}
                </h1>

                {{-- Méta --}}
                <div class="flex flex-wrap items-center gap-5">
                    @if($exposant->responsable)
                    <div class="flex items-center gap-2 text-white/65 text-sm">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                        </svg>
                        {{ $exposant->responsable }}
                    </div>
                    @endif

                    @if(isset($exposant->evenements) && $exposant->evenements->count())
                    <div class="flex items-center gap-2 text-white/65 text-sm">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5"/>
                        </svg>
                        {{ $exposant->evenements->count() }} événement{{ $exposant->evenements->count() > 1 ? 's' : '' }}
                    </div>
                    @endif

                    @if($exposant->site_web)
                    <a href="{{ $exposant->site_web }}" target="_blank" rel="noopener noreferrer"
                       class="flex items-center gap-2 text-sm font-medium transition-colors"
                       style="color: var(--gold-light);"
                       onmouseover="this.style.color='white'" onmouseout="this.style.color='var(--gold-light)'">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/>
                        </svg>
                        {{ parse_url($exposant->site_web, PHP_URL_HOST) ?? $exposant->site_web }}
                    </a>
                    @endif
                </div>
            </div>

            {{-- Actions rapides desktop --}}
            <div class="hidden lg:flex flex-col gap-3 flex-shrink-0">
                @if($exposant->site_web)
                <a href="{{ $exposant->site_web }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-semibold transition-all hover:brightness-110"
                   style="background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: var(--blue-night);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3"/>
                    </svg>
                    Visiter le site
                </a>
                @endif
                @if($exposant->email)
                <a href="mailto:{{ $exposant->email }}"
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-semibold border border-white/20 text-white transition-all hover:bg-white/10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                    </svg>
                    Contacter
                </a>
                @endif
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     LAYOUT PRINCIPAL
     ══════════════════════════════════════════════════════════════ --}}
<div class="max-w-7xl mx-auto px-6 lg:px-16 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">


        {{-- ────────────────────────────────────────────────────
             COLONNE PRINCIPALE (gauche × 2)
             ──────────────────────────────────────────────────── --}}
        <div class="lg:col-span-2 flex flex-col gap-12">


            {{-- ══════════════════════════════════════════════════
                 SECTION : À propos
                 ══════════════════════════════════════════════════ --}}
            @if($exposant->description)
            <section class="reveal" aria-label="Description">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-1 h-8 rounded-full" style="background: linear-gradient(to bottom, var(--blue-electric), var(--blue-night));" aria-hidden="true"></div>
                    <h2 class="font-display text-2xl lg:text-3xl" style="color: var(--blue-night);">À propos</h2>
                </div>
                <div class="text-base leading-relaxed" style="color: #374151; max-width: 68ch;">
                    {!! nl2br(e($exposant->description)) !!}
                </div>
            </section>
            @endif


            {{-- ══════════════════════════════════════════════════
                 SECTION : Événements de l'exposant
                 ══════════════════════════════════════════════════ --}}
            @if(isset($exposant->evenements) && $exposant->evenements->count())
            <section class="reveal reveal-d1" aria-label="Événements organisés">

                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-8 rounded-full" style="background: linear-gradient(to bottom, var(--blue-electric), var(--blue-night));" aria-hidden="true"></div>
                        <h2 class="font-display text-2xl lg:text-3xl" style="color: var(--blue-night);">
                            Événements organisés
                        </h2>
                    </div>
                    <span class="text-xs font-semibold px-3 py-1 rounded-full" style="background: rgba(30,95,216,.08); color: var(--blue-electric);">
                        {{ $exposant->evenements->count() }} au total
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach($exposant->evenements as $idx => $evenement)
                    <article class="event-card card-lift bg-white rounded-2xl overflow-hidden border reveal reveal-d{{ ($idx % 2) + 1 }}"
                             style="border-color: var(--gray-soft); box-shadow: 0 4px 20px rgba(10,22,40,.06);"
                             aria-label="{{ $evenement->titre }}">

                        {{-- Image --}}
                        <div class="event-img-wrap relative h-44">
                            @if($evenement->image)
                                <img src="{{ Storage::url($evenement->image) }}"
                                     alt="{{ $evenement->titre }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center"
                                     style="background: linear-gradient(135deg, var(--blue-night), var(--blue-electric));">
                                    <svg class="w-10 h-10 text-white/25" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Z"/>
                                    </svg>
                                </div>
                            @endif

                            {{-- Badge catégorie --}}
                            @if($evenement->categorie)
                            <span class="absolute top-3 left-3 px-2.5 py-1 text-xs font-semibold rounded-full backdrop-blur-sm"
                                  style="background: rgba(10,22,40,.72); color: var(--gold-light);">
                                {{ $evenement->categorie->nom }}
                            </span>
                            @endif

                            {{-- Badge date --}}
                            <div class="absolute top-3 right-3 flex flex-col items-center justify-center w-11 h-11 rounded-xl bg-white shadow-md">
                                <span class="text-xs font-bold leading-none" style="color: var(--blue-electric);">
                                    {{ \Carbon\Carbon::parse($evenement->date_debut)->format('d') }}
                                </span>
                                <span class="text-xs uppercase leading-none mt-0.5" style="color: var(--gray-mid);">
                                    {{ \Carbon\Carbon::parse($evenement->date_debut)->translatedFormat('M') }}
                                </span>
                            </div>

                            {{-- Statut --}}
                            @php
                                $now   = now();
                                $debut = \Carbon\Carbon::parse($evenement->date_debut);
                                $fin   = \Carbon\Carbon::parse($evenement->date_fin);
                                if ($now->lt($debut))                  { $sl = 'À venir';  $sc = '#10B981'; $sb = '#ECFDF5'; }
                                elseif ($now->between($debut, $fin))   { $sl = 'En cours'; $sc = '#C2410C'; $sb = '#FFF7ED'; }
                                else                                   { $sl = 'Terminé';  $sc = '#9CA3AF'; $sb = '#F3F4F6'; }
                            @endphp
                            <span class="absolute bottom-3 left-3 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold"
                                  style="background: {{ $sb }}; color: {{ $sc }};">
                                <span class="w-1.5 h-1.5 rounded-full" style="background: {{ $sc }};"></span>
                                {{ $sl }}
                            </span>
                        </div>

                        {{-- Corps --}}
                        <div class="p-5">
                            <h3 class="font-semibold text-sm leading-snug mb-3 line-clamp-2" style="color: var(--blue-night);">
                                {{ $evenement->titre }}
                            </h3>

                            <div class="flex items-center gap-4 text-xs mb-4" style="color: var(--gray-mid);">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                                    </svg>
                                    {{ $evenement->lieu }}
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($evenement->date_debut)->diffInDays($evenement->date_fin) + 1 }}j
                                </span>
                            </div>

                            <a href="{{ route('events.show', $evenement->id) }}"
                               class="block w-full text-center py-2.5 rounded-xl text-xs font-semibold transition-all hover:brightness-110 active:scale-95"
                               style="background: linear-gradient(135deg, var(--blue-electric), #1248b0); color: white;">
                                Voir l'événement
                            </a>
                        </div>
                    </article>
                    @endforeach
                </div>
            </section>
            @endif


            {{-- ══════════════════════════════════════════════════
                 SECTION : Galerie (si photos disponibles)
                 ══════════════════════════════════════════════════ --}}
            @if(isset($exposant->galerie) && $exposant->galerie->count())
            <section class="reveal reveal-d2" aria-label="Galerie photos">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-1 h-8 rounded-full" style="background: linear-gradient(to bottom, var(--blue-electric), var(--blue-night));" aria-hidden="true"></div>
                    <h2 class="font-display text-2xl lg:text-3xl" style="color: var(--blue-night);">Galerie</h2>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($exposant->galerie->take(6) as $photo)
                    <div class="aspect-square rounded-xl overflow-hidden">
                        <img src="{{ $photo->url }}" alt="Photo {{ $exposant->nom }}"
                             class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                    </div>
                    @endforeach
                </div>
            </section>
            @endif

        </div>
        {{-- /COLONNE PRINCIPALE --}}


        {{-- ────────────────────────────────────────────────────
             SIDEBAR (droite × 1)
             ──────────────────────────────────────────────────── --}}
        <aside class="flex flex-col gap-6" aria-label="Informations de contact">
            <div style="position: sticky; top: 6rem;">


                {{-- ══════════════════════════════════════════════
                     Card : Coordonnées
                     ══════════════════════════════════════════════ --}}
                <div class="rounded-2xl overflow-hidden mb-5" style="box-shadow: 0 8px 32px rgba(10,22,40,.10); border: 1px solid var(--gray-soft);">

                    {{-- Header card --}}
                    <div class="px-6 py-5" style="background: linear-gradient(135deg, var(--blue-night), var(--blue-deep));">
                        <p class="eyebrow mb-1">Coordonnées</p>
                        <h3 class="font-display text-lg text-white leading-snug">
                            {{ $exposant->nom ?? $exposant->nom_entreprise }}
                        </h3>
                    </div>

                    {{-- Corps --}}
                    <div class="p-6 bg-white flex flex-col gap-4">

                        {{-- Responsable --}}
                        @if($exposant->responsable)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5"
                                 style="background: var(--pearl);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"
                                     style="color: var(--blue-electric);" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-medium mb-0.5" style="color: var(--gray-mid);">Responsable</p>
                                <p class="text-sm font-semibold" style="color: var(--blue-night);">{{ $exposant->responsable }}</p>
                            </div>
                        </div>
                        <hr style="border-color: var(--gray-soft);">
                        @endif

                        {{-- Téléphone --}}
                        @if($exposant->telephone)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5"
                                 style="background: var(--pearl);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"
                                     style="color: var(--blue-electric);" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 6z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-medium mb-0.5" style="color: var(--gray-mid);">Téléphone</p>
                                <a href="tel:{{ $exposant->telephone }}"
                                   class="text-sm font-semibold transition-colors"
                                   style="color: var(--blue-night);"
                                   onmouseover="this.style.color='var(--blue-electric)'"
                                   onmouseout="this.style.color='var(--blue-night)'">
                                    {{ $exposant->telephone }}
                                </a>
                            </div>
                        </div>
                        <hr style="border-color: var(--gray-soft);">
                        @endif

                        {{-- Email --}}
                        @if($exposant->email)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5"
                                 style="background: var(--pearl);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"
                                     style="color: var(--blue-electric);" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-medium mb-0.5" style="color: var(--gray-mid);">Email</p>
                                <a href="mailto:{{ $exposant->email }}"
                                   class="text-sm font-semibold transition-colors truncate block"
                                   style="color: var(--blue-night);"
                                   onmouseover="this.style.color='var(--blue-electric)'"
                                   onmouseout="this.style.color='var(--blue-night)'">
                                    {{ $exposant->email }}
                                </a>
                            </div>
                        </div>
                        @endif

                        {{-- Site web --}}
                        @if($exposant->site_web)
                        <hr style="border-color: var(--gray-soft);">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5"
                                 style="background: var(--pearl);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"
                                     style="color: var(--blue-electric);" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-medium mb-0.5" style="color: var(--gray-mid);">Site web</p>
                                <a href="{{ $exposant->site_web }}" target="_blank" rel="noopener noreferrer"
                                   class="text-sm font-semibold transition-colors truncate block"
                                   style="color: var(--blue-electric);"
                                   onmouseover="this.style.textDecoration='underline'"
                                   onmouseout="this.style.textDecoration='none'">
                                    {{ parse_url($exposant->site_web, PHP_URL_HOST) ?? $exposant->site_web }}
                                </a>
                            </div>
                        </div>
                        @endif

                        {{-- LinkedIn --}}
                        @if($exposant->linkedin ?? null)
                        <hr style="border-color: var(--gray-soft);">
                        <a href="{{ $exposant->linkedin }}" target="_blank" rel="noopener noreferrer"
                           class="flex items-center gap-3 transition-opacity hover:opacity-80">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                                 style="background: #0077B5;">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-medium" style="color: var(--gray-mid);">LinkedIn</p>
                                <p class="text-sm font-semibold" style="color: #0077B5;">Voir le profil</p>
                            </div>
                        </a>
                        @endif
                    </div>

                    {{-- CTA footer card --}}
                    <div class="px-6 pb-6 bg-white flex flex-col gap-2.5">
                        @if($exposant->site_web)
                        <a href="{{ $exposant->site_web }}" target="_blank" rel="noopener noreferrer"
                           class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-semibold transition-all hover:brightness-110 active:scale-95"
                           style="background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: var(--blue-night);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                            </svg>
                            Visiter le site web
                        </a>
                        @endif
                        @if($exposant->email)
                        <a href="mailto:{{ $exposant->email }}"
                           class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-semibold border transition-all hover:bg-gray-50 active:scale-95"
                           style="border-color: var(--gray-soft); color: var(--blue-night);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25"/>
                            </svg>
                            Envoyer un email
                        </a>
                        @endif
                        @if($exposant->telephone)
                        <a href="tel:{{ $exposant->telephone }}"
                           class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-semibold border transition-all hover:bg-gray-50 active:scale-95"
                           style="border-color: var(--gray-soft); color: var(--blue-night);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 6z"/>
                            </svg>
                            Appeler
                        </a>
                        @endif
                    </div>
                </div>


                {{-- ══════════════════════════════════════════════
                     Card : Retour liste
                     ══════════════════════════════════════════════ --}}
                <div class="rounded-2xl p-5 border" style="border-color: var(--gray-soft); background: var(--pearl);">
                    <p class="text-xs font-medium mb-3" style="color: var(--gray-mid);">Explorer d'autres exposants</p>
                    <a href="/"
                       class="flex items-center gap-2 text-sm font-semibold transition-colors group"
                       style="color: var(--blue-electric);"
                       onmouseover="this.style.color='var(--blue-night)'"
                       onmouseout="this.style.color='var(--blue-electric)'">
                        <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                        </svg>
                        Voir tous les exposants
                    </a>
                </div>

            </div>
        </aside>
        {{-- /SIDEBAR --}}

    </div>
</div>


{{-- ══════════════════════════════════════════════════════════════
     FOOTER MINIMAL
     ══════════════════════════════════════════════════════════════ --}}
<footer class="border-t" style="border-color: var(--gray-soft); background: var(--pearl);" role="contentinfo">
    <div class="max-w-7xl mx-auto px-6 lg:px-16 py-8 flex flex-wrap items-center justify-between gap-4">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5" aria-label="ExpoDakar">
            <span class="flex items-center justify-center w-7 h-7 rounded-lg"
                  style="background: linear-gradient(135deg, var(--blue-electric), var(--blue-night));">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18"/>
                </svg>
            </span>
            <span class="font-display text-lg" style="color: var(--blue-night);">
                Expo<span style="color: var(--blue-electric);">Dakar</span>
            </span>
        </a>
        <p class="text-xs" style="color: var(--gray-mid);">© {{ date('Y') }} ExpoDakar · Tous droits réservés</p>
        <nav class="flex gap-5" aria-label="Footer">
            <a href="{{ route('home') }}"            class="text-xs transition-colors" style="color: var(--gray-mid);" onmouseover="this.style.color='var(--blue-night)'" onmouseout="this.style.color='var(--gray-mid)'">Accueil</a>
            <a href="{{ route('events.index') }}"    class="text-xs transition-colors" style="color: var(--gray-mid);" onmouseover="this.style.color='var(--blue-night)'" onmouseout="this.style.color='var(--gray-mid)'">Événements</a>
            <a href="{{ route('exposants.index') }}" class="text-xs transition-colors" style="color: var(--gray-mid);" onmouseover="this.style.color='var(--blue-night)'" onmouseout="this.style.color='var(--gray-mid)'">Exposants</a>
        </nav>
    </div>
</footer>


{{-- Script reveal scroll --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const els = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); }
            });
        }, { threshold: 0.1 });
        els.forEach(el => io.observe(el));
    } else {
        els.forEach(el => el.classList.add('visible'));
    }
});
</script>

</body>
</html>
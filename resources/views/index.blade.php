<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExpoDakar - Événements Sénégal</title>

    <!-- Tailwind + Alpine -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font -->
    {{-- AVANT --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

{{-- APRÈS --}}
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --blue-night:    #0A1628;
            --blue-deep:     #0D2145;
            --blue-electric: #1E5FD8;
            --blue-light:    #3B82F6;
            --gold:          #C9A84C;
            --gold-light:    #E8C96A;
            --pearl:         #F7F8FC;
            --gray-soft:     #EEF0F6;
            --gray-mid:      #8892A4;
            --text-main:     #0A1628;
        }
        

      body {
        font-family: 'Inter', sans-serif;
        color: var(--text-main);
        background: #fff;
        overflow-x: hidden;

        /* 🔥 FIX TYPO PREMIUM */
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-rendering: optimizeLegibility;
        font-optical-sizing: auto;
        }

        .font-display { font-family: 'Instrument Serif', serif; }


        /* ── Navbar ─────────────────────────────────────────── */
        .navbar-transparent { background: transparent; }
        .navbar-solid       { background: var(--blue-night); box-shadow: 0 2px 24px rgba(10,22,40,.18); }

        /* ── Hero grid overlay ───────────────────────────────── */
        .hero-grid-overlay {
            background-image:
                linear-gradient(rgba(196,168,76,.12) 1px, transparent 1px),
                linear-gradient(90deg, rgba(196,168,76,.12) 1px, transparent 1px);
            background-size: 60px 60px;
            background-position: center center;
        }

        /* ── Gold gradient text ──────────────────────────────── */
        .text-gold-gradient {
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 50%, var(--gold) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ── Reveal animation ────────────────────────────────── */
        .reveal { opacity: 0; transform: translateY(32px); transition: opacity .7s ease, transform .7s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .reveal-delay-1 { transition-delay: .1s; }
        .reveal-delay-2 { transition-delay: .2s; }
        .reveal-delay-3 { transition-delay: .3s; }
        .reveal-delay-4 { transition-delay: .4s; }

        /* ── Card hover lift ─────────────────────────────────── */
        .card-lift {
            transition: transform .3s ease, box-shadow .3s ease;
        }
        .card-lift:hover {
            transform: translateY(-6px);
            box-shadow: 0 24px 60px rgba(10,22,40,.13);
        }

        /* ── Search bar ──────────────────────────────────────── */
        .search-input:focus { outline: none; box-shadow: 0 0 0 3px rgba(196,168,76,.3); }

        /* ── Stat counter ────────────────────────────────────── */
        .stat-card { border-top: 3px solid var(--gold); }

        /* ── Category pill ───────────────────────────────────── */
        .cat-pill {
            transition: background .25s, color .25s, border-color .25s, box-shadow .25s;
        }
        .cat-pill:hover, .cat-pill.active {
            background: var(--blue-electric);
            color: #fff;
            border-color: var(--blue-electric);
            box-shadow: 0 4px 16px rgba(30,95,216,.3);
        }

        /* ── Event card image zoom ───────────────────────────── */
        .event-img-wrap { overflow: hidden; }
        .event-img-wrap img { transition: transform .45s ease; }
        .event-card:hover .event-img-wrap img { transform: scale(1.06); }

        /* ── FAQ accordion ───────────────────────────────────── */
        [x-cloak] { display: none !important; }

        /* ── Partner logo grayscale → color ──────────────────── */
        .partner-logo { filter: grayscale(1) opacity(.5); transition: filter .3s; }
        .partner-logo:hover { filter: grayscale(0) opacity(1); }

        /* ── Section divider ─────────────────────────────────── */
        .section-eyebrow {
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--gold);
        }

        /* ── Newsletter input ────────────────────────────────── */
        .nl-input:focus { outline: none; box-shadow: 0 0 0 3px rgba(196,168,76,.35); }

        /* ── Scrollbar ───────────────────────────────────────── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--pearl); }
        ::-webkit-scrollbar-thumb { background: var(--blue-electric); border-radius: 99px; }
        /* ── Conteneur global uniforme ── */
        .container-main {
            max-width: 80rem;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
        @media (min-width: 1024px) {
            .container-main {
                padding-left: 4rem;
                padding-right: 4rem;
            }
        }
        /* ── Atout icons ── */
.atout-icon svg {
    width: 1.25rem;
    height: 1.25rem;
    stroke: white;
}

        /* ══════════════════════════════════════════════════
           ESPACES PUBLICITAIRES (bannières louables)
           ══════════════════════════════════════════════════ */
        .pub-slot {
            transition: box-shadow .25s ease, transform .25s ease;
        }
        .pub-slot:hover {
            box-shadow: 0 8px 28px rgba(10,22,40,.12);
        }
        .pub-slot-label {
            position: absolute;
            top: 6px;
            left: 6px;
            z-index: 2;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            background: rgba(10,22,40,.72);
            color: #fff;
            pointer-events: none;
        }
        .pub-empty {
            background:
                repeating-linear-gradient(45deg, var(--gray-soft), var(--gray-soft) 10px, #fff 10px, #fff 20px);
        }
    </style>
</head>

<body class="bg-slate-50">
<!-- APP -->
<div x-data="eventApp(@js($events), @js($categories))">

    {{-- ══════════════════════════════════════════════════════════════
         0. ESPACES PUBLICITAIRES — helper de rendu
         ──────────────────────────────────────────────────────────────
         Version STATIQUE : chaque zone est un placeholder. Pour publier
         une bannière, il suffit de renseigner "image" et "lien" dans le
         tableau $pubZones ci-dessous (ou, plus tard, de faire remonter
         ces mêmes clés depuis une table `publicites` en base).

         Nomenclature reprise du plan de bannières fourni (façon Seneweb) :
           - AP_HABILLAGE : habillage plein écran, page d'accueil (160×900 ×2)
           - TOP_A2M      : bannière haute page d'accueil     (970×250)
           - SPLH         : bannière horizontale spéciale     (1000×120)
           - A1R          : pavé latéral droit                (300×600)
           - BLOC_SPECIAL : bloc rectangle "premium"          (300×600)
           - B1L / B1R    : bannières basses gauche / droite  (300×250)
         Pages intérieures (fiche événement, fiche exposant…) : réutiliser
         le même helper avec API_HABILLAGE et A2M (728×90).
         ══════════════════════════════════════════════════════════════ --}}
    @php
        $pubZones = $pubZones ?? [];

        $pub = function (string $zone, int $w, int $h, string $label) use ($pubZones) {
            $data = $pubZones[$zone] ?? null;
            $img  = $data['image'] ?? null;
            $lien = $data['lien']  ?? '#';

            ob_start(); ?>
            <div class="pub-slot relative rounded-xl overflow-hidden border border-dashed w-full h-full <?= $img ? '' : 'pub-empty' ?>"
                 style="border-color: var(--gray-soft); background-color: var(--pearl);"
                 data-zone="<?= e($zone) ?>" data-size="<?= $w ?>x<?= $h ?>">
                <span class="pub-slot-label">Pub · <?= $w ?>×<?= $h ?></span>
                <a href="<?= e($lien) ?>" target="_blank" rel="noopener sponsored"
                   class="flex items-center justify-center w-full h-full" style="aspect-ratio: <?= $w ?>/<?= $h ?>;">
                    <?php if ($img): ?>
                        <img src="<?= e($img) ?>" alt="Publicité <?= e($label) ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="flex flex-col items-center justify-center gap-1 text-center px-3">
                            <span class="text-[10px] font-semibold uppercase tracking-widest" style="color: var(--gray-mid);">
                                Emplacement disponible
                            </span>
                            <span class="text-xs font-bold" style="color: var(--blue-night);"><?= e($label) ?></span>
                            <span class="text-[10px]" style="color: var(--gray-mid);"><?= $w ?> × <?= $h ?> px</span>
                        </div>
                    <?php endif; ?>
                </a>
            </div>
            <?php
            return ob_get_clean();
        };
    @endphp

    {{-- AP_HABILLAGE : deux colonnes fixes de part et d'autre du site, visibles uniquement sur très grands écrans --}}
    <div class="hidden 2xl:block fixed top-0 left-0 h-screen w-[160px] z-30" aria-hidden="true">
        {!! $pub('ap_habillage_gauche', 160, 900, 'Habillage gauche') !!}
    </div>
    <div class="hidden 2xl:block fixed top-0 right-0 h-screen w-[160px] z-30" aria-hidden="true">
        {!! $pub('ap_habillage_droite', 160, 900, 'Habillage droite') !!}
    </div>

    <!-- ================= NAVBAR ================= -->

{{--
|--------------------------------------------------------------------------
| ExpoDakar – Page d'accueil Premium
| Laravel 12 • Blade • Tailwind CSS v4 • Alpine.js 3
|--------------------------------------------------------------------------
--}}


{{-- ══════════════════════════════════════════════════════════════
     1. NAVBAR PREMIUM
     ══════════════════════════════════════════════════════════════ --}}
<header
    x-data="{
        open: false,
        scrolled: false,
        init() {
            window.addEventListener('scroll', () => {
                this.scrolled = window.scrollY > 60;
            });
        }
    }"
    :class="scrolled ? 'navbar-solid' : 'navbar-transparent'"
    class="fixed inset-x-0 top-0 z-50 transition-all duration-300"
    x-init="init()"
>
    <div class="max-w-7xl mx-auto px-6 lg:px-16">
        <div class="flex items-center justify-between h-20">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 group" aria-label="ExpoDakar accueil">
                
                <img src="https://res.cloudinary.com/dstbqtuxm/image/upload/v1782085416/ChatGPT_Image_Jun_21__2026__07_24_51_PM-removebg-preview_zi77k0.png"  alt="Logo ExpoDakar" class="h-12 w-auto object-contain">
    
     
                <span class="font-display text-2xl text-white">
                    Expo<span class="text-gold-gradient">Dakar</span>
                </span>
            </a>

            {{-- Navigation desktop --}}
            <nav class="hidden lg:flex items-center gap-8" aria-label="Navigation principale">
                <a href="#evenements" class="text-sm font-medium text-white/80 hover:text-white transition-colors">Événements</a>
                <a href="#categories"  class="text-sm font-medium text-white/80 hover:text-white transition-colors">Catégories</a>
                <a href="#exposants"   class="text-sm font-medium text-white/80 hover:text-white transition-colors">Exposants</a>
                <a href="#faq"         class="text-sm font-medium text-white/80 hover:text-white transition-colors">FAQ</a>
            </nav>

            {{-- CTA desktop --}}
            <div class="hidden lg:flex items-center gap-3">
                @guest
                    <a href="{{route('login')}}"
                       class="text-sm font-medium text-white/80 hover:text-white transition-colors px-4 py-2 rounded-lg hover:bg-white/10">
                        Connexion
                    </a>
                    <a href="{{route('register')}}"
                       class="text-sm font-semibold text-white px-5 py-2.5 rounded-xl transition-all duration-200"
                       style="background: linear-gradient(135deg, var(--gold), var(--gold-light));">
                        S'inscrire gratuitement
                    </a>
                @endguest

                @auth
                    <a href="{{route('account')}}"
                       class="text-sm font-semibold text-white px-5 py-2.5 rounded-xl transition-all duration-200"
                       style="background: linear-gradient(135deg, var(--blue-electric), #1248b0);">
                        Mon espace
                    </a>
                @endauth
            </div>

            {{-- Burger mobile --}}
            <button @click="open = !open"
                    class="lg:hidden flex items-center justify-center w-10 h-10 rounded-lg text-white hover:bg-white/10 transition"
                    aria-label="Ouvrir le menu"
                    :aria-expanded="open">
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
    <div x-show="open"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="lg:hidden border-t border-white/10"
         style="background: var(--blue-night);"
         role="dialog" aria-label="Menu mobile">
        <nav class="flex flex-col gap-1 px-6 py-4">
            <a href="#evenements" @click="open=false" class="px-4 py-3 text-sm font-medium text-white/80 hover:text-white rounded-lg hover:bg-white/10 transition">Événements</a>
            <a href="#categories"  @click="open=false" class="px-4 py-3 text-sm font-medium text-white/80 hover:text-white rounded-lg hover:bg-white/10 transition">Catégories</a>
            <a href="#exposants"   @click="open=false" class="px-4 py-3 text-sm font-medium text-white/80 hover:text-white rounded-lg hover:bg-white/10 transition">Exposants</a>
            <a href="#faq"         @click="open=false" class="px-4 py-3 text-sm font-medium text-white/80 hover:text-white rounded-lg hover:bg-white/10 transition">FAQ</a>
            <hr class="border-white/10 my-2">
            @guest
                <a href="{{ route('login') }}"    class="px-4 py-3 text-sm font-medium text-white/80 hover:text-white rounded-lg hover:bg-white/10 transition">Connexion</a>
                <a href="{{ route('register') }}" class="mt-1 px-4 py-3 text-sm font-semibold text-center text-white rounded-xl" style="background: linear-gradient(135deg,var(--gold),var(--gold-light));">S'inscrire gratuitement</a>
            @endguest
            @auth
                <a href="{{ route('dashboard') }}" class="px-4 py-3 text-sm font-semibold text-center text-white rounded-xl" style="background: linear-gradient(135deg,var(--blue-electric),#1248b0);">Mon espace</a>
            @endauth
        </nav>
    </div>
</header>


{{-- ══════════════════════════════════════════════════════════════
     2. HERO
     ══════════════════════════════════════════════════════════════ --}}
<section   class="relative min-h-screen flex flex-col justify-center overflow-hidden" style="background: var(--blue-night);" aria-label="Bannière principale" >

    {{-- Background image --}}
    <div class="absolute inset-0 z-0">
        <img src="https://res.cloudinary.com/dstbqtuxm/image/upload/v1782659620/ChatGPT_Image_Jun_28_2026_03_00_42_PM_qkpjbj.png"
             alt=""
             class="w-full h-full object-cover opacity-25"
             aria-hidden="true">
        {{-- Gradient overlay --}}
        <div class="absolute inset-0"
             style="background: linear-gradient(135deg, rgba(10,22,40,.0) 0%, rgba(13,33,69,.0) 60%, rgba(30,95,216,.-1) 100%);"
             aria-hidden="true"></div>
        {{-- Grid perspective --}}
        <div class="absolute inset-0 hero-grid-overlay opacity-40" aria-hidden="true"></div>
        {{-- Glow accent --}}
        <div class="absolute -bottom-40 -right-40 w-[600px] h-[600px] rounded-full blur-[120px] opacity-20"
             style="background: var(--blue-electric);" aria-hidden="true"></div>
        <div class="absolute top-1/3 -left-20 w-[400px] h-[400px] rounded-full blur-[100px] opacity-10"
             style="background: var(--gold);" aria-hidden="true"></div>
    </div>

    <div class="relative z-10 max-w-[1700px]  mx-auto px-10 lg:px-20 pt-32 pb-24">

       <div class="max-w-6xl">

            {{-- Eyebrow --}}
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-white/15 bg-white/5 backdrop-blur-sm mb-8">
                <span class="w-2 h-2 rounded-full animate-pulse" style="background: var(--gold);"></span>
                <span class="section-eyebrow" style="color: var(--gold-light);">Plateforme officielle — Sénégal</span>
            </div>

            {{-- Title --}}
            <h1 class="font-display text-5xl sm:text-6xl lg:text-7xl text-white leading-tight mb-6">
                Le rendez-vous des
                <em class="not-italic text-gold-gradient">événements pro</em><br>
                au Sénégal
            </h1>

            {{-- Subtitle --}}
            <p class="text-lg text-white/65 max-w-xl mx-auto leading-relaxed mb-10">
                Salons, conférences, forums, expositions — découvrez, réservez et promouvez
                les événements qui façonnent l'économie sénégalaise.
            </p>

            {{-- Search bar --}}
            <form action="{{ route('user.events.search') }}" method="GET" class="flex flex-col sm:flex-row gap-3 justify-center gap-10 ml-20 max-w-2xl mb-14" role="search" aria-label="Rechercher un événement">                  
                @csrf
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none" aria-hidden="true">
                        <svg class="w-5 h-5 text-white/40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607z"/>
                        </svg>
                    </div>
                    <input type="search"
                           name="q"
                           placeholder="Rechercher un salon, conférence, exposant…"
                           class="search-input w-full pl-12 pr-4 py-4 rounded-xl bg-white/10 backdrop-blur text-white placeholder-white/40 border border-white/15 text-sm transition"
                           aria-label="Recherche">
                </div>
                <button type="submit"
                        class="px-7 py-4 rounded-xl font-semibold text-sm text-white transition-all duration-200 hover:brightness-110 active:scale-95 whitespace-nowrap"
                        style="background: linear-gradient(135deg, var(--gold), var(--gold-light));">
                    Rechercher
                </button>
            </form>

            {{-- Stats rapides --}}
            <div class="flex flex-wrap justify-center gap-10 translate-x-8">
                <div>
                    <div class="font-display text-4xl text-white">+240</div>
                    <div class="text-xs text-white/50 mt-1">Événements référencés</div>
                </div>
                <div class="w-px bg-white/15 self-stretch hidden sm:block" aria-hidden="true"></div>
                <div>
                    <div class="font-display text-4xl text-white">+180</div>
                    <div class="text-xs text-white/50 mt-1">Exposants actifs</div>
                </div>
                <div class="w-px bg-white/15 self-stretch hidden sm:block" aria-hidden="true"></div>
                <div>
                    <div class="font-display text-4xl text-white">+15k</div>
                    <div class="text-xs text-white/50 mt-1">Visiteurs inscrits</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scroll hint --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 flex flex-col items-center gap-2 opacity-60" aria-hidden="true">
      <!--  <span class="text-xs text-white/50 tracking-widest uppercase">Découvrir</span> -->
        <div class="w-px h-10 bg-gradient-to-b from-white/30 to-transparent animate-pulse"></div>
    </div>
</section>
<br>
        {{-- TOP_A2M : bannière haute, sous la navbar --}}
        <div class="max-w-4xl mx-auto mb-10 h-[90px] sm:h-[120px] lg:h-[130px]">
            {!! $pub('top_a2m', 970, 250, 'Top bannière') !!}
        </div>

{{-- ══════════════════════════════════════════════════════════════
     3. LOGOS PARTENAIRES
     ══════════════════════════════════════════════════════════════ --}}
<section class="py-14 border-y" style="border-color: var(--gray-soft);" aria-label="Nos partenaires">
    <div class="max-w-7xl mx-auto px-6 lg:px-16">
        <p class="text-center text-xs font-semibold tracking-widest uppercase mb-10" style="color: var(--gray-mid);">
            Ils nous font confiance
        </p>
        <div class="flex flex-wrap items-center justify-center gap-10 lg:gap-16">
            @foreach(['CCIAD', 'ANSD', 'APIX', 'DER/FJ', 'ADEPME', 'CTIC Dakar', 'Banque de l\'UEMOA'] as $partner)
            <div class="partner-logo cursor-default select-none text-xl font-display font-medium"
                 style="color: var(--blue-night);" aria-label="{{ $partner }}">
                {{ $partner }}
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     4. STATISTIQUES ANIMÉES
     ══════════════════════════════════════════════════════════════ --}}
<section
    class="py-24"
    style="background: var(--pearl);"
    aria-label="Chiffres clés"
    x-data="{
        stats: [
            { value: 240, suffix: '+', label: 'Événements organisés', icon: 'calendar' },
            { value: 180, suffix: '+', label: 'Exposants référencés', icon: 'building' },
            { value: 15000, suffix: '+', label: 'Visiteurs enregistrés', icon: 'users' },
            { value: 14,   suffix: '',  label: 'Régions couvertes',    icon: 'map' },
        ],
        animated: false,
        counts: [0, 0, 0, 0],
        initCounters() {
            if (this.animated) return;
            this.animated = true;
            this.stats.forEach((stat, i) => {
              const duration = 3500;
              const steps = 120;
                const increment = stat.value / steps;
                let current = 0;
                const timer = setInterval(() => {
                    current = Math.min(current + increment, stat.value);
                    this.counts[i] = Math.floor(current);
                    if (current >= stat.value) clearInterval(timer);
                }, duration / steps);
            });
        }
    }"
    x-init="
    const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) {
            initCounters();
            observer.disconnect();
        }
    }, { threshold: 0.9 });
    observer.observe($el);
"
>
    <div class="max-w-7xl mx-auto px-6 lg:px-16">
        <div class="text-center mb-16 reveal" x-init="
    const io = new IntersectionObserver((e) => {
        if (e[0].isIntersecting) { $el.classList.add('visible'); io.disconnect(); }
    }, { threshold: 0.1 });
    io.observe($el);
">
            <p class="section-eyebrow mb-3">Chiffres clés</p>
            <h2 class="font-display text-4xl lg:text-5xl" style="color: var(--blue-night);">
                ExpoDakar en quelques chiffres
            </h2>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            @php
            $statIcons = [
                'calendar' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>',
                'building'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>',
                'users'     => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Zm-13.5 0a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>',
                'map'       => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z"/>',
            ];
            $statData = [
                ['value' => 240,  'suffix' => '+', 'label' => 'Événements organisés', 'icon' => 'calendar'],
                ['value' => 180,  'suffix' => '+', 'label' => 'Exposants référencés',  'icon' => 'building'],
                ['value' => 15000,'suffix' => '+', 'label' => 'Visiteurs enregistrés', 'icon' => 'users'],
                ['value' => 14,   'suffix' => '',  'label' => 'Régions couvertes',      'icon' => 'map'],
            ];
            @endphp

            @foreach($statData as $i => $stat)
            <div class="stat-card bg-white rounded-2xl p-8 reveal reveal-delay-{{ $i + 1 }}"
                 x-intersect.once="$el.classList.add('visible')"
                 style="box-shadow: 0 4px 24px rgba(10,22,40,.06);">
                <div class="flex items-center justify-between mb-5">
                    <span class="font-display text-5xl font-light" style="color: var(--blue-night);">
                        <span x-text="counts[{{ $i }}].toLocaleString('fr-FR')">{{ number_format($stat['value'], 0, ',', ' ') }}</span>{{ $stat['suffix'] }}
                    </span>
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
                         style="background: var(--pearl);">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                             style="color: var(--blue-electric);" aria-hidden="true">
                            {!! $statIcons[$stat['icon']] !!}
                        </svg>
                    </div>
                </div>
                <p class="text-sm font-medium" style="color: var(--gray-mid);">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     5. CATÉGORIES POPULAIRES
     ══════════════════════════════════════════════════════════════ --}}
<section id="categories" class="py-24 bg-white" aria-label="Catégories d'événements">
    <div class="max-w-7xl mx-auto px-6 lg:px-16">

        <div class="text-center mb-16 reveal" x-intersect.once="$el.classList.add('visible')">
            <p class="section-eyebrow mb-3">Explorer par thème</p>
            <h2 class="font-display text-4xl lg:text-5xl mb-4" style="color: var(--blue-night);">Catégories populaires</h2>
            <p class="text-base max-w-lg mx-auto" style="color: var(--gray-mid);">
                Trouvez les événements qui correspondent à votre secteur d'activité.
            </p>
        </div>

        {{-- Filtre dynamique --}}
        <div class="flex flex-wrap justify-center gap-3 mb-12 reveal" x-intersect.once="$el.classList.add('visible')"
             x-data="{ active: 'all' }">
            <button @click="active='all'"
                    :class="active==='all' ? 'active' : ''"
                    class="cat-pill px-5 py-2.5 text-sm font-medium rounded-full border transition"
                    style="border-color: var(--gray-soft); color: var(--blue-night);">
                Tous
            </button>
            @foreach($categories as $cat)
            <button @click="active='{{ $cat->id }}'"
                    :class="active==='{{ $cat->id }}' ? 'active' : ''"
                    class="cat-pill px-5 py-2.5 text-sm font-medium rounded-full border transition"
                    style="border-color: var(--gray-soft); color: var(--blue-night);">
                {{ $cat->nom }}
            </button>
            @endforeach
        </div>

        {{-- Grille catégories --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
            @php
                $catIcons = [
                    '<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>',
                    '<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>',
                    '<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/></svg>',
                    '<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>',
                    '<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 0 0 1.5-.189m-1.5.189a6.01 6.01 0 0 1-1.5-.189m3.75 7.478a12.06 12.06 0 0 1-4.5 0m3.75 2.383a14.406 14.406 0 0 1-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 1 0-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/></svg>',
                    '<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253"/></svg>',
                    '<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>',
                    '<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 1-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21a48.25 48.25 0 0 1-8.135-.687c-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"/></svg>',
                ];
            @endphp
            @foreach($categories as $idx => $cat)
            <a href="{{ route('user.events.index', ['categorie' => $cat->id]) }}"
               class="group flex flex-col items-center gap-4 p-8 rounded-2xl border text-center transition-all duration-300 hover:border-blue-200 reveal reveal-delay-{{ ($idx % 4) + 1 }}"
               style="border-color: var(--gray-soft);"
               x-intersect.once="$el.classList.add('visible')">
                {{-- APRÈS --}}
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-300 group-hover:scale-110 group-hover:rotate-3"
                    style="background: var(--blue-soft, #EFF6FF);">
                    <span class="w-6 h-6" style="color: var(--blue-electric);">
                        {!! $catIcons[$idx % count($catIcons)] !!}
                    </span>
                </div>
                <div>
                    <div class="font-semibold text-sm mb-1" style="color: var(--blue-night);">{{ $cat->nom }}</div>
                    @if(isset($cat->events_count))
                    <div class="text-xs" style="color: var(--gray-mid);">{{ $cat->events_count }} événement{{ $cat->events_count > 1 ? 's' : '' }}</div>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     5bis. SPLH — bannière spéciale pleine largeur
     ══════════════════════════════════════════════════════════════ --}}
<section class="py-8 bg-white" aria-label="Espace publicitaire">
    <div class="max-w-7xl mx-auto px-6 lg:px-16">
        <div class="h-[100px] sm:h-[110px] lg:h-[120px]">
            {!! $pub('splh', 1000, 120, 'Bannière SPLH') !!}
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     6. ÉVÉNEMENTS POPULAIRES
     ══════════════════════════════════════════════════════════════ --}}
<section id="evenements" class="py-24" style="background: var(--pearl);" aria-label="Événements à la une">
    <div class="max-w-7xl mx-auto px-6 lg:px-16">

        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 mb-16">
            <div class="reveal" x-intersect.once="$el.classList.add('visible')">
                <p class="section-eyebrow mb-3">À ne pas manquer</p>
                <h2 class="font-display text-4xl lg:text-5xl" style="color: var(--blue-night);">Événements populaires</h2>
            </div>
            <a href="{{ route('user.events.index') }}"
               class="reveal reveal-delay-2 inline-flex items-center gap-2 text-sm font-semibold transition-colors group"
               style="color: var(--blue-electric);"
               x-intersect.once="$el.classList.add('visible')">
                Voir tous les événements
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                </svg>
            </a>
        </div>

        {{-- Corps : grille événements + pavé A1R en sticky --}}
        <div class="lg:flex lg:items-start lg:gap-8">

        {{-- A1R : pavé latéral, visible à partir de lg, sticky pendant le scroll --}}
        <aside class="hidden lg:block lg:w-[300px] flex-shrink-0 order-2 sticky top-28 self-start" aria-label="Espace publicitaire">
            <div class="h-[600px]">
                {!! $pub('a1r', 300, 600, 'Pavé A1R') !!}
            </div>
        </aside>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-7 order-1 flex-1">
            @foreach($events as $idx => $event)
            <article class="event-card card-lift bg-white rounded-2xl overflow-hidden reveal reveal-delay-{{ ($idx % 3) + 1 }}"
                     style="box-shadow: 0 4px 24px rgba(10,22,40,.07);"
                     x-intersect.once="$el.classList.add('visible')"
                     aria-label="{{ $event->titre }}">

                {{-- Image --}}
                <div class="event-img-wrap relative h-52">
                    @if($event->image)
                        <img src="{{ $event->image }}"
                             alt="{{ $event->titre }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center"
                             style="background: linear-gradient(135deg, var(--blue-night), var(--blue-electric));">
                            <svg class="w-12 h-12 text-white/30" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                            </svg>
                        </div>
                    @endif

                    {{-- Badge catégorie --}}
                    @if($event->categorie)
                    <span class="absolute top-4 left-4 px-3 py-1 text-xs font-semibold rounded-full backdrop-blur-sm"
                          style="background: rgba(10,22,40,.7); color: var(--gold-light);">
                        {{ $event->categorie->nom }}
                    </span>
                    @endif

                    {{-- Badge date --}}
                    <div class="absolute top-4 right-4 flex flex-col items-center justify-center w-12 h-12 rounded-xl bg-white shadow-lg">
                        <span class="text-xs font-bold leading-none" style="color: var(--blue-electric);">
                            {{ \Carbon\Carbon::parse($event->date_debut)->format('d') }}
                        </span>
                        <span class="text-xs uppercase leading-none mt-0.5" style="color: var(--gray-mid);">
                            {{ \Carbon\Carbon::parse($event->date_debut)->translatedFormat('M') }}
                        </span>
                    </div>
                </div>

                {{-- Contenu --}}
                <div class="p-6">

                    {{-- Exposant --}}
                    @if($event->exposant)
                    <div class="flex items-center gap-2 mb-3">
                      
                        <span class="text-xs font-medium" style="color: var(--gray-mid);">{{ $event->exposant->nom }}</span>
                    </div>
                    @endif

                    <h3 class="font-semibold text-base leading-snug mb-3 line-clamp-2" style="color: var(--blue-night);">
                        {{ $event->titre }}
                    </h3>

                    {{-- Lieu + durée --}}
                    <div class="flex items-center gap-4 text-xs mb-5" style="color: var(--gray-mid);">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                            </svg>
                            {{ $event->lieu }}
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            {{ \Carbon\Carbon::parse($event->date_debut)->diffInDays($event->date_fin) + 1 }}j
                        </span>
                    </div>

                    {{-- CTA --}}
                    <a href="{{ route('user.events.show', $event->id) }}"
                       class="block w-full text-center py-3 rounded-xl text-sm font-semibold transition-all duration-200 hover:brightness-105 active:scale-95"
                       style="background: linear-gradient(135deg, var(--blue-electric), #1248b0); color: white;">
                        Voir les détails
                    </a>
                </div>
            </article>
            @endforeach
        </div>

        @if($events->isEmpty())
        <div class="text-center py-20 flex-1">
            <div class="text-5xl mb-4">📅</div>
            <p class="font-semibold text-lg mb-2" style="color: var(--blue-night);">Aucun événement disponible</p>
            <p class="text-sm" style="color: var(--gray-mid);">De nouveaux événements seront bientôt publiés.</p>
        </div>
        @endif

        </div> {{-- /flex grille + A1R --}}
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     7. EXPOSANTS PREMIUM
     ══════════════════════════════════════════════════════════════ --}}
<section id="exposants" class="py-24 bg-white" aria-label="Exposants premium">
    <div class="max-w-7xl mx-auto px-6 lg:px-16">

        <div class="text-center mb-16 reveal" x-intersect.once="$el.classList.add('visible')">
            <p class="section-eyebrow mb-3">Ils exposent sur ExpoDakar</p>
            <h2 class="font-display text-4xl lg:text-5xl mb-4" style="color: var(--blue-night);">Exposants premium</h2>
            <p class="text-base max-w-lg mx-auto" style="color: var(--gray-mid);">
                Des entreprises de premier plan qui font confiance à notre plateforme pour toucher leur audience.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($exposants as $idx => $exposant)
            <div class="card-lift group flex flex-col gap-5 p-7 rounded-2xl border reveal reveal-delay-{{ ($idx % 3) + 1 }}"
                 style="border-color: var(--gray-soft); box-shadow: 0 2px 16px rgba(10,22,40,.04);"
                 x-intersect.once="$el.classList.add('visible')">

                {{-- Header --}}
                <div class="flex items-center gap-4">
                    {{-- Logo ou initiale --}}
                    <div class="w-14 h-14 rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden"
                         style="background: var(--pearl);">
                        @if($exposant->logo)
                            <img src="{{ Storage::url($exposant->logo) }}"
                                 alt="Logo {{ $exposant->nom }}"
                                 class="w-full h-full object-contain p-2">
                        @else
                            <span class="font-display text-2xl font-bold" style="color: var(--blue-electric);">
                                {{ strtoupper(substr($exposant->nom, 0, 1)) }}
                            </span>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-semibold text-base" style="color: var(--blue-night);">{{ $exposant->nom }}</h3>
                        @if($exposant->secteur)
                        <span class="text-xs px-2 py-0.5 rounded-full mt-1 inline-block"
                              style="background: rgba(30,95,216,.08); color: var(--blue-electric);">
                            {{ $exposant->secteur }}
                        </span>
                        @endif
                    </div>
                </div>

                {{-- Description --}}
                @if($exposant->description)
                <p class="text-sm leading-relaxed line-clamp-3 flex-1" style="color: var(--gray-mid);">
                    {{ $exposant->description }}
                </p>
                @endif

                {{-- Footer : liens --}}
                <div class="flex items-center gap-3 pt-4 border-t" style="border-color: var(--gray-soft);">
                    @if($exposant->site_web)
                    <a href="{{ $exposant->site_web }}" target="_blank" rel="noopener noreferrer"
                       class="flex items-center gap-1.5 text-xs font-medium transition-colors"
                       style="color: var(--blue-electric);"
                       aria-label="Site web de {{ $exposant->nom }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253"/>
                        </svg>
                        Site web
                    </a>
                    @endif
                    @if($exposant->linkedin)
                    <a href="{{ $exposant->linkedin }}" target="_blank" rel="noopener noreferrer"
                       class="flex items-center justify-center w-7 h-7 rounded-lg transition hover:opacity-80"
                       style="background: #0077B5; color: white;"
                       aria-label="LinkedIn de {{ $exposant->nom }}">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                        </svg>
                    </a>
                    @endif
                    <a href="{{ route('user.exposants.show', $exposant->id) }}"
                       class="ml-auto text-xs font-semibold transition-colors" style="color: var(--gray-mid);">
                        Voir le profil →
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     7bis. BLOC_SPECIAL — emplacement premium
     ══════════════════════════════════════════════════════════════ --}}
<section class="py-16 bg-white" aria-label="Espace publicitaire premium">
    <div class="max-w-7xl mx-auto px-6 lg:px-16">
        <div class="rounded-2xl p-8 lg:p-10 grid lg:grid-cols-[1fr_300px] gap-8 items-center"
             style="background: var(--pearl);">
            <div>
                <p class="section-eyebrow mb-3">Espace partenaire</p>
                <h3 class="font-display text-3xl lg:text-4xl mb-3" style="color: var(--blue-night);">
                    Mettez votre marque en avant
                </h3>
                <p class="text-sm leading-relaxed max-w-md" style="color: var(--gray-mid);">
                    Cet emplacement premium (BLOC SPECIAL) est visible par tous les visiteurs de la
                    page d'accueil. Contactez-nous pour réserver votre créneau publicitaire.
                </p>
            </div>
            <div class="h-[600px] mx-auto w-full max-w-[300px]">
                {!! $pub('bloc_special', 300, 600, 'Bloc spécial') !!}
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     8. POURQUOI CHOISIR EXPODAKAR
     ══════════════════════════════════════════════════════════════ --}}
<section class="py-24 overflow-hidden" style="background: var(--blue-night);" aria-label="Nos atouts">
    <div class="max-w-7xl mx-auto px-6 lg:px-16">

        <div class="grid lg:grid-cols-2 gap-16 items-center">
            {{-- Texte --}}
            <div>
                <p class="section-eyebrow mb-4">Notre valeur ajoutée</p>
                <h2 class="font-display text-4xl lg:text-5xl text-white mb-6">
                    Pourquoi choisir<br><span class="text-gold-gradient">ExpoDakar</span> ?
                </h2>
                <p class="text-white/60 text-base leading-relaxed mb-10">
                    Une plateforme pensée pour l'écosystème économique sénégalais, connectant visiteurs,
                    entreprises et organisateurs en toute simplicité.
                </p>

                <div class="space-y-6">
                    @php
                        $atouts = [
                            [
                                'icon' => '<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>',
                                'title' => 'Découverte simplifiée',
                                'desc'  => 'Trouvez rapidement les événements pertinents grâce à une recherche avancée et des filtres intelligents.',
                            ],
                            [
                                'icon' => '<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z"/></svg>',
                                'title' => 'Réservation instantanée',
                                'desc'  => 'Réservez votre place en quelques clics. Recevez votre billet QR par email immédiatement.',
                            ],
                            [
                                'icon' => '<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 1 1 0-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 0 1-1.44-4.282m3.102.069a18.03 18.03 0 0 1-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 0 1 8.835 2.535M10.34 6.66a23.847 23.847 0 0 1 8.835-2.535m0 0A23.74 23.74 0 0 1 18.795 3m.38 1.125a23.91 23.91 0 0 1 1.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 0 0 1.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 0 1 0 3.46"/></svg>',
                                'title' => 'Visibilité maximale',
                                'desc'  => 'Exposants et organisateurs bénéficient d\'outils de promotion performants pour maximiser leur impact.',
                            ],
                            [
                                'icon' => '<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>',
                                'title' => 'Tableau de bord complet',
                                'desc'  => 'Suivez vos inscriptions, gérez vos événements et analysez vos performances en temps réel.',
                            ],
                        ];
                    @endphp

                    @foreach($atouts as $idx => $atout)
                    <div class="flex gap-5 reveal reveal-delay-{{ $idx + 1 }}" x-intersect.once="$el.classList.add('visible')">
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5"
                            style="background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.1);">
                            <span class="w-5 h-5 text-white" style="display:flex;">
                                {!! $atout['icon'] !!}
                            </span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white mb-1">{{ $atout['title'] }}</h3>
                            <p class="text-sm text-white/55 leading-relaxed">{{ $atout['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Visuel --}}
            <div class="relative reveal reveal-delay-2" x-intersect.once="$el.classList.add('visible')">
                <div class="relative rounded-3xl overflow-hidden"
                     style="background: linear-gradient(135deg, rgba(30,95,216,.2), rgba(196,168,76,.1)); border: 1px solid rgba(255,255,255,.08);">
                    <div class="p-8 grid grid-cols-2 gap-5">
                        @php
                        $cards = [
                            ['label' => 'Événements actifs',   'value' => '48',    'change' => '+12%'],
                            ['label' => 'Inscriptions ce mois','value' => '1 240',  'change' => '+28%'],
                            ['label' => 'Exposants vérifiés',  'value' => '183',   'change' => '+5%'],
                            ['label' => 'Satisfaction client', 'value' => '98%',   'change' => '↑'],
                        ];
                        @endphp
                        @foreach($cards as $card)
                        <div class="rounded-2xl p-5" style="background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.08);">
                            <div class="text-xs mb-3" style="color: rgba(255,255,255,.45);">{{ $card['label'] }}</div>
                            <div class="font-display text-3xl text-white mb-1">{{ $card['value'] }}</div>
                            <div class="text-xs font-medium" style="color: var(--gold-light);">{{ $card['change'] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                {{-- Deco glow --}}
                <div class="absolute -bottom-10 -left-10 w-48 h-48 rounded-full blur-3xl opacity-30"
                     style="background: var(--gold);" aria-hidden="true"></div>
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     9. GALERIE
     ══════════════════════════════════════════════════════════════ --}}
<section class="py-24 bg-white" aria-label="Galerie photos">
    <div class="max-w-7xl mx-auto px-6 lg:px-16">

        <div class="text-center mb-16 reveal" x-intersect.once="$el.classList.add('visible')">
            <p class="section-eyebrow mb-3">Nos moments forts</p>
            <h2 class="font-display text-4xl lg:text-5xl" style="color: var(--blue-night);">
                ExpoDakar en images
            </h2>
        </div>

        {{-- Grille galerie mosaïque --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="col-span-2 row-span-2 rounded-2xl overflow-hidden h-80 lg:h-auto reveal"
                 x-intersect.once="$el.classList.add('visible')">
                <img src="https://www.firstevent.co.uk/wp-content/uploads/2024/09/Cardano-D1-316-1-1.jpg" alt="Salon professionnel Dakar"
                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
            </div>
            @foreach(['https://www.conferenceexpo.com/wp-content/uploads/2019/02/conference-expo-by-nimlok-gallery-d.jpg','https://elleevents.com.au/wp-content/uploads/2020/06/conference-exhibition-CLIA_Sydney2018.jpg','https://elleevents.com.au/wp-content/uploads/2020/06/conference-exhibitionC360-opt.jpg','https://www.graynoise.com.au/wp-content/uploads/2017/11/TM20240516_0507web.jpg'] as $idx => $img)
            <div class="rounded-2xl overflow-hidden h-40 reveal reveal-delay-{{ $idx + 1 }}"
                 x-intersect.once="$el.classList.add('visible')">
                <img src="{{  $img }}" alt="Événement ExpoDakar"
                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     10. TÉMOIGNAGES
     ══════════════════════════════════════════════════════════════ --}}
<section class="py-24" style="background: var(--pearl);" aria-label="Témoignages clients">
    <div class="max-w-7xl mx-auto px-6 lg:px-16">

        <div class="text-center mb-16 reveal" x-intersect.once="$el.classList.add('visible')">
            <p class="section-eyebrow mb-3">Ce qu'ils en disent</p>
            <h2 class="font-display text-4xl lg:text-5xl" style="color: var(--blue-night);">Témoignages</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
            @php
            $temoignages = [
                ['name' => 'Aminata Diallo',    'role' => 'Directrice Marketing, TechHub Dakar',  'stars' => 5, 'text' => 'ExpoDakar a transformé la façon dont nous gérons nos participations aux salons. Interface intuitive, gain de temps remarquable et une visibilité inégalée.'],
                ['name' => 'Moussa Konaté',     'role' => 'PDG, Import-Export Sénégal',           'stars' => 5, 'text' => 'Grâce à ExpoDakar, nous avons généré 3 fois plus de leads lors du dernier FOIRE de Dakar. La plateforme est tout simplement indispensable.'],
                ['name' => 'Fatou Mbodj',       'role' => 'Organisatrice d\'événements',          'stars' => 5, 'text' => 'La gestion des inscriptions et des billets QR a rendu nos conférences tellement plus fluides. Nos participants adorent l\'expérience.'],
            ];
            @endphp

            @foreach($temoignages as $idx => $temoignage)
            <div class="card-lift bg-white rounded-2xl p-8 flex flex-col gap-5 reveal reveal-delay-{{ $idx + 1 }}"
                 style="box-shadow: 0 4px 24px rgba(10,22,40,.06);"
                 x-intersect.once="$el.classList.add('visible')">
                {{-- Étoiles --}}
                <div class="flex gap-1" aria-label="{{ $temoignage['stars'] }} étoiles sur 5">
                    @for($s = 0; $s < $temoignage['stars']; $s++)
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" style="color: var(--gold);" aria-hidden="true">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    @endfor
                </div>

                {{-- Texte --}}
                <p class="text-sm leading-relaxed flex-1 italic" style="color: var(--gray-mid);">
                    "{{ $temoignage['text'] }}"
                </p>

                {{-- Auteur --}}
                <div class="flex items-center gap-3 pt-4 border-t" style="border-color: var(--gray-soft);">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold text-white text-sm flex-shrink-0"
                         style="background: linear-gradient(135deg, var(--blue-electric), var(--blue-night));">
                        {{ strtoupper(substr($temoignage['name'], 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-sm font-semibold" style="color: var(--blue-night);">{{ $temoignage['name'] }}</div>
                        <div class="text-xs" style="color: var(--gray-mid);">{{ $temoignage['role'] }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     11. FAQ ACCORDÉON ALPINE.JS
     ══════════════════════════════════════════════════════════════ --}}
<section id="faq" class="py-24 bg-white" aria-label="Questions fréquentes">
    <div class="max-w-3xl mx-auto px-6 lg:px-8">

        <div class="text-center mb-16 reveal" x-intersect.once="$el.classList.add('visible')">
            <p class="section-eyebrow mb-3">Vous avez des questions ?</p>
            <h2 class="font-display text-4xl lg:text-5xl mb-4" style="color: var(--blue-night);">
                Questions fréquentes
            </h2>
            <p class="text-base" style="color: var(--gray-mid);">
                Tout ce que vous devez savoir sur ExpoDakar.
            </p>
        </div>

        @php
        $faqs = [
            ['q' => 'Comment réserver un événement sur ExpoDakar ?', 'a' => 'Il vous suffit de créer un compte gratuit, de parcourir les événements disponibles et de cliquer sur "Réserver". Votre billet QR est immédiatement envoyé par email.'],
            ['q' => 'Comment inscrire mon entreprise en tant qu\'exposant ?', 'a' => 'Rendez-vous dans la section "Devenir exposant" et complétez votre profil entreprise. Notre équipe valide les inscriptions sous 48h.'],
            ['q' => 'ExpoDakar est-il gratuit pour les visiteurs ?', 'a' => 'L\'accès à la plateforme et la consultation des événements sont entièrement gratuits. Certains événements peuvent avoir un tarif d\'entrée fixé par l\'organisateur.'],
            ['q' => 'Comment promouvoir mon événement sur la plateforme ?', 'a' => 'Les organisateurs disposent d\'un tableau de bord dédié avec des outils de promotion : mise en avant en page d\'accueil, emailings ciblés et partages sur les réseaux sociaux.'],
            ['q' => 'Puis-je annuler ou modifier ma réservation ?', 'a' => 'Oui, depuis votre espace personnel, vous pouvez gérer vos réservations jusqu\'à 24h avant l\'événement. Les modalités de remboursement dépendent de la politique de chaque organisateur.'],
            ['q' => 'ExpoDakar couvre-t-il toutes les régions du Sénégal ?', 'a' => 'Nous couvrons actuellement les 14 régions du Sénégal, avec une forte concentration sur Dakar, Thiès, Saint-Louis et Ziguinchor.'],
        ];
        @endphp

        <div class="space-y-4" x-data="{ open: null }" role="list">
            @foreach($faqs as $idx => $faq)
            <div class="rounded-2xl border overflow-hidden reveal reveal-delay-{{ ($idx % 3) + 1 }}"
                 style="border-color: var(--gray-soft);"
                 x-intersect.once="$el.classList.add('visible')"
                 role="listitem">
                <button @click="open === {{ $idx }} ? open = null : open = {{ $idx }}"
                        class="w-full flex items-center justify-between gap-4 px-6 py-5 text-left transition-colors hover:bg-gray-50"
                        :aria-expanded="open === {{ $idx }}"
                        aria-controls="faq-{{ $idx }}">
                    <span class="font-semibold text-sm" style="color: var(--blue-night);">{{ $faq['q'] }}</span>
                    <div class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center transition-colors"
                         :style="open === {{ $idx }} ? 'background: var(--blue-electric);' : 'background: var(--gray-soft);"'>
                        <svg class="w-3.5 h-3.5 transition-transform duration-300"
                             :class="open === {{ $idx }} ? 'rotate-180 text-white' : ''"
                             :style="open === {{ $idx }} ? 'color: white;' : 'color: var(--gray-mid);'"
                             fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </div>
                </button>

                <div x-show="open === {{ $idx }}"
                     x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     id="faq-{{ $idx }}"
                     role="region">
                    <div class="px-6 pb-5 text-sm leading-relaxed" style="color: var(--gray-mid);">
                        {{ $faq['a'] }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     12. NEWSLETTER
     ══════════════════════════════════════════════════════════════ --}}
<section class="py-24 overflow-hidden" style="background: var(--blue-night);" aria-label="Newsletter">
    <div class="max-w-3xl mx-auto px-6 lg:px-8 text-center">

        {{-- Deco --}}
        <div class="absolute left-1/2 -translate-x-1/2 w-[600px] h-[300px] rounded-full blur-[100px] opacity-15 pointer-events-none"
             style="background: var(--blue-electric);" aria-hidden="true"></div>

        <div class="relative reveal" x-intersect.once="$el.classList.add('visible')">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-white/15 bg-white/5 mb-8">
                <span class="text-lg">📩</span>
                <span class="section-eyebrow" style="color: var(--gold-light);">Restez informé</span>
            </div>

            <h2 class="font-display text-4xl lg:text-5xl text-white mb-4">
                Ne manquez aucun événement
            </h2>
            <p class="text-white/55 mb-10 max-w-md mx-auto">
                Recevez chaque semaine une sélection personnalisée des meilleurs événements professionnels au Sénégal.
            </p>

            <form action="{{ route('user.newsletter.subscribe') }}" method="POST"
                  class="flex flex-col sm:flex-row gap-3 max-w-lg mx-auto"
                  x-data="{ email: '', success: false }"
                  @submit.prevent="success = true">
                @csrf
                <input type="email"
                       name="email"
                       x-model="email"
                       placeholder="Votre adresse email professionnelle"
                       class="nl-input flex-1 px-5 py-4 rounded-xl bg-white/10 backdrop-blur text-white placeholder-white/35 border border-white/15 text-sm transition"
                       required
                       aria-label="Adresse email pour la newsletter">
                <button type="submit"
                        class="px-7 py-4 rounded-xl font-semibold text-sm transition-all hover:brightness-110 active:scale-95 whitespace-nowrap"
                        style="background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: var(--blue-night);">
                    S'abonner
                </button>
            </form>

            <p class="text-xs mt-5" style="color: rgba(255,255,255,.3);">
                Pas de spam. Désinscription en un clic. Données protégées.
            </p>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     12bis. B1L / B1R — bannières basses
     ══════════════════════════════════════════════════════════════ --}}
<section class="py-14 bg-white" aria-label="Espaces publicitaires">
    <div class="max-w-7xl mx-auto px-6 lg:px-16">
        <div class="grid sm:grid-cols-2 gap-6 max-w-3xl mx-auto">
            <div class="h-[250px]">
                {!! $pub('b1l', 300, 250, 'Bannière B1L') !!}
            </div>
            <div class="h-[250px]">
                {!! $pub('b1r', 300, 250, 'Bannière B1R') !!}
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     13. FOOTER PREMIUMoute
     ══════════════════════════════════════════════════════════════ --}}
<footer style="background: #070f1d;" aria-label="Pied de page">
    <div class="max-w-7xl mx-auto px-6 lg:px-16">

        {{-- Main footer --}}
        <div class="py-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 border-b" style="border-color: rgba(255,255,255,.06);">

            {{-- Brand --}}
            <div class="lg:col-span-1">
                <a href="{{ route('home') }}" class="flex items-center gap-3 mb-5" aria-label="ExpoDakar">
                        <img src="https://res.cloudinary.com/dstbqtuxm/image/upload/v1782085416/ChatGPT_Image_Jun_21__2026__07_24_51_PM-removebg-preview_zi77k0.png"  alt="Logo ExpoDakar" class="h-12 w-auto object-contain">
                    <span class="font-display text-2xl text-white">Expo<span class="text-gold-gradient">Dakar</span></span>
                </a>
                <p class="text-sm leading-relaxed mb-6" style="color: rgba(255,255,255,.4);">
                    La plateforme de référence pour les événements professionnels au Sénégal.
                </p>
                {{-- Réseaux sociaux --}}
                <div class="flex gap-3">
                    @foreach([
                        ['href' => '#', 'label' => 'Facebook',  'icon' => 'M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z'],
                        ['href' => '#', 'label' => 'Twitter/X', 'icon' => 'M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z'],
                        ['href' => '#', 'label' => 'LinkedIn',  'icon' => 'M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6zM2 9h4v12H2z M4 6a2 2 0 1 0 0-4 2 2 0 0 0 0 4z'],
                    ] as $social)
                    <a href="{{ $social['href'] }}"
                       class="w-9 h-9 rounded-xl flex items-center justify-center transition hover:opacity-80"
                       style="background: rgba(255,255,255,.07);"
                       aria-label="{{ $social['label'] }}" rel="noopener noreferrer">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $social['icon'] }}"/>
                        </svg>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Liens plateforme{{ route($link['route']) }} {{ $link['label'] }} {{ route($link['route']) }}--}}
            <div>
                <h3 class="text-xs font-semibold tracking-widest uppercase mb-5" style="color: rgba(255,255,255,.35);">Plateforme</h3>
                <ul class="space-y-3">
                    @foreach([
                        ['label' => 'Tous les événements', 'route' => 'user.events.index'],
                        ['label' => 'Exposants',           'route' => 'user.exposants.index'],
                        ['label' => 'Catégories',          'route' => 'user.categories.index'],
                        ['label' => 'Organiser un événement', 'route' => 'user.organisateurs.create'],
                    ] as $link)
                    <li>
                        <a href=""
                           class="text-sm transition-colors" style="color: rgba(255,255,255,.45);"
                           onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,.45)'">
                            
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Liens légaux --}}
            <div>
                <h3 class="text-xs font-semibold tracking-widest uppercase mb-5" style="color: rgba(255,255,255,.35);">Informations</h3>
                <ul class="space-y-3">
                    @foreach([
                        ['label' => 'À propos',          'route' => 'about'],
                        ['label' => 'Contact',           'route' => 'contact'],
                        ['label' => 'Conditions d\'utilisation', 'route' => 'terms'],
                        ['label' => 'Politique de confidentialité', 'route' => 'privacy'],
                    ] as $link)
                    <li>
                        <a href=""
                           class="text-sm transition-colors" style="color: rgba(255,255,255,.45);"
                           onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,.45)'">
                            
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h3 class="text-xs font-semibold tracking-widest uppercase mb-5" style="color: rgba(255,255,255,.35);">Contact</h3>
                <ul class="space-y-4">
                    <li class="flex gap-3">
                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="color: var(--gold);" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                        </svg>
                        <span class="text-sm" style="color: rgba(255,255,255,.45);">Dakar, Plateau — Sénégal</span>
                    </li>
                    <li class="flex gap-3">
                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="color: var(--gold);" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                        </svg>
                        <a href="mailto:contact@expodakar.sn"
                           class="text-sm transition-colors" style="color: rgba(255,255,255,.45);"
                           onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,.45)'">
                            contact@expodakar.sn
                        </a>
                    </li>
                    <li class="flex gap-3">
                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="color: var(--gold);" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 6z"/>
                        </svg>
                        <a href="tel:+221338001234"
                           class="text-sm transition-colors" style="color: rgba(255,255,255,.45);"
                           onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,.45)'">
                            +221 33 800 12 34
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="py-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs" style="color: rgba(255,255,255,.25);">
                © {{ date('Y') }} ExpoDakar. Tous droits réservés.
            </p>
            <p class="text-xs" style="color: rgba(255,255,255,.2);">
                Conçu  au Sénégal
            </p>
        </div>
    </div>
</footer>

{{-- ══════════════════════════════════════════════════════════════
     SCRIPTS : Intersection Observer pour les révélations
     ══════════════════════════════════════════════════════════════ --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Reveal on scroll via IntersectionObserver
    const revealEls = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });
        revealEls.forEach(el => io.observe(el));
    } else {
        // Fallback pour les navigateurs sans support
        revealEls.forEach(el => el.classList.add('visible'));
    }
});
</script>
<div x-data="{
        show: false,
        init() {
            if (!localStorage.getItem('welcomed')) {
                setTimeout(() => this.show = true, 1500);
            }
        },
        close() {
            this.show = false;
            localStorage.setItem('welcomed', '1');
        }
     }"
     x-show="show"
     x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     style="background:rgba(10,22,40,.6); backdrop-filter:blur(6px);">

    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden">

        {{-- Header image --}}
        <div class="relative h-40 flex items-center justify-center"
             style="background:linear-gradient(135deg,#0A1628,#1E5FD8);">
            <div class="absolute inset-0 opacity-20"
                 style="background-image:linear-gradient(rgba(196,168,76,.4) 1px,transparent 1px),linear-gradient(90deg,rgba(196,168,76,.4) 1px,transparent 1px); background-size:40px 40px;">
            </div>
            <div class="relative text-center">
                <p class="font-display text-3xl text-white font-bold">Expo<span style="color:#E8C96A;">DKR</span></p>
                <p class="text-xs text-white/60 mt-1">Plateforme événementielle du Sénégal</p>
            </div>
        </div>

        {{-- Corps --}}
        <div class="p-6 text-center">
            <h3 class="text-lg font-bold text-slate-800 mb-2">Bienvenue sur ExpoDKR ! 🎉</h3>
            <p class="text-sm text-slate-500 mb-6 leading-relaxed">
                Découvrez les meilleurs événements professionnels au Sénégal.
                Réservez votre place en quelques clics.
            </p>
            <div class="flex gap-3">
                <button @click="close()"
                        class="flex-1 py-2.5 rounded-xl text-sm font-semibold border border-slate-200 text-slate-500 hover:bg-slate-50 transition-colors">
                    Ignorer
                </button>
                <a href=""
                   @click="close()"
                   class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-white transition-all hover:brightness-110"
                   style="background:linear-gradient(135deg,#1E5FD8,#1248b0);">
                    Explorer les événements
                </a>
            </div>
        </div>
    </div>
</div>
<div x-data="{
        show: false,
        init() {
            if (!localStorage.getItem('nl_closed')) {
                setTimeout(() => this.show = true, 8000);
            }
        },
        close() {
            this.show = false;
            localStorage.setItem('nl_closed', '1');
        }
     }"
     x-show="show"
     x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     class="fixed bottom-6 right-6 z-50 w-80 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden">

    {{-- Header --}}
    <div class="px-5 py-4 flex items-start justify-between gap-3"
         style="background:linear-gradient(135deg,#0A1628,#0D2145);">
        <div>
            <p class="text-sm font-bold text-white">📩 Restez informé</p>
            <p class="text-xs text-white/55 mt-0.5">Recevez les événements chaque semaine</p>
        </div>
        <button @click="close()"
                class="text-white/40 hover:text-white transition-colors flex-shrink-0"
                aria-label="Fermer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Formulaire --}}
    <form action="" method="POST" class="p-4 flex flex-col gap-3">
        @csrf
        <input type="email"
               name="email"
               placeholder="votre@email.com"
               required
               class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        <button type="submit"
                @click="close()"
                class="w-full py-2.5 rounded-xl text-sm font-semibold text-white transition-all hover:brightness-110"
                style="background:linear-gradient(135deg,#C9A84C,#E8C96A); color:#0A1628;">
            S'abonner gratuitement
        </button>
        <button type="button" @click="close()"
                class="text-xs text-slate-400 hover:text-slate-600 text-center transition-colors">
            Non merci
        </button>
    </form>
</div>
@if(session('success'))
<div x-data="{ show: true }"
     x-show="show"
     x-cloak
     x-init="setTimeout(() => show = false, 4000)"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-2"
     class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 flex items-center gap-3 px-5 py-3.5 rounded-2xl shadow-xl text-sm font-semibold text-white"
     style="background:linear-gradient(135deg,#059669,#047857);">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
    </svg>
    {{ session('success') }}
    <button @click="show = false" class="text-white/60 hover:text-white ml-2 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
        </svg>
    </button>
</div>
@endif
{{-- ═══════════════════════════════════════════════
     POPUP "ÉVÉNEMENT SPONSORISÉ" (exposant spécial)
     ═══════════════════════════════════════════════ --}}
@php
    // Test : on va chercher l'événement 1 directement en BDD, peu importe $events
    $featuredEvent = \App\Models\Evenement::with('exposant')->find(10);
@endphp

@if($featuredEvent)
<div
    x-data="{
        show: false,
        closed: false,
        init() {
            if (localStorage.getItem('featured_closed_{{ $featuredEvent->id }}')) return;
            setTimeout(() => this.show = true, 2000);
        },
        close() {
            this.show = false;
            this.closed = true;
            localStorage.setItem('featured_closed_{{ $featuredEvent->id }}', '1');
        }
    }"
    x-show="show"
    x-cloak
    x-transition:enter="transition ease-out duration-400"
    x-transition:enter-start="opacity-0 translate-y-6 scale-95"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
    x-transition:leave-end="opacity-0 translate-y-6 scale-95"
    class="fixed bottom-6 right-6 z-50 w-80"
>
    <div class="relative rounded-2xl overflow-hidden shadow-2xl"
         style="background: white; border: 1px solid var(--gray-soft);">

        {{-- Halo pulsant répété toutes les 5s pour attirer l'attention --}}
        <div class="absolute -inset-1 rounded-2xl pointer-events-none"
             style="background: linear-gradient(135deg, var(--gold), var(--blue-electric));
                    opacity: .35;
                    filter: blur(10px);
                    animation: featured-pulse 5s ease-in-out infinite;"
             aria-hidden="true"></div>

        <div class="relative bg-white rounded-2xl overflow-hidden">

            {{-- Bandeau "Sponsorisé" --}}
            <div class="flex items-center justify-between px-4 py-2"
                 style="background: linear-gradient(135deg, var(--gold), var(--gold-light));">
                <span class="text-[11px] font-bold uppercase tracking-wider" style="color: var(--blue-night);">
                    ⭐ Événement sponsorisé
                </span>
                <button @click="close()" class="text-[--blue-night]/60 hover:text-[--blue-night] transition-colors" aria-label="Fermer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Image --}}
            <div class="relative h-32">
                @if($featuredEvent->image)
                    <img src="{{ Storage::url($featuredEvent->image) }}"
                         alt="{{ $featuredEvent->titre }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center"
                         style="background: linear-gradient(135deg, var(--blue-night), var(--blue-electric));">
                        <span class="text-white/40 text-3xl">📅</span>
                    </div>
                @endif

                {{-- Logo exposant en médaillon --}}
                @if($featuredEvent->exposant && $featuredEvent->exposant->logo)
                <div class="absolute -bottom-5 left-4 w-12 h-12 rounded-xl bg-white shadow-lg flex items-center justify-center overflow-hidden border-2 border-white">
                    <img src="{{ Storage::url($featuredEvent->exposant->logo) }}"
                         alt="Logo {{ $featuredEvent->exposant->nom }}"
                         class="w-full h-full object-contain p-1">
                </div>
                @endif
            </div>

            {{-- Contenu --}}
            <div class="p-4 pt-7">
                @if($featuredEvent->exposant)
                <p class="text-xs font-semibold mb-1" style="color: var(--blue-electric);">
                    Proposé par {{ $featuredEvent->exposant->nom }}
                </p>
                @endif
                <h3 class="font-semibold text-sm leading-snug mb-2" style="color: var(--blue-night);">
                    {{ $featuredEvent->titre }}
                </h3>
                <div class="flex items-center gap-3 text-xs mb-4" style="color: var(--gray-mid);">
                    <span>📍 {{ $featuredEvent->lieu }}</span>
                    <span>🗓 {{ \Carbon\Carbon::parse($featuredEvent->date_debut)->translatedFormat('d M') }}</span>
                </div>
                <a href="{{ route('user.events.show', $featuredEvent->id) }}"
                   class="block w-full text-center py-2.5 rounded-xl text-sm font-semibold text-white transition-all hover:brightness-110 active:scale-95"
                   style="background: linear-gradient(135deg, var(--blue-electric), #1248b0);">
                    Découvrir l'événement
                </a>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes featured-pulse {
    0%, 100% { opacity: .25; transform: scale(1); }
    50%      { opacity: .5;  transform: scale(1.03); }
}
</style>
@endif
</body>
</html>
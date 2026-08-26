<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExpoDakar - La plateforme des événements professionnels du Sénégal</title>
    <meta name="description" content="Découvrez, réservez et exposez sur les salons, conférences et forums professionnels du Sénégal.">

    {{-- ── Préconnexions : accélère la résolution DNS / TLS des domaines tiers critiques ── --}}
    <link rel="preconnect" href="https://cdn.tailwindcss.com">
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://res.cloudinary.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- ── Préchargement de l'image hero (LCP) ── --}}
    <link rel="preload" as="image" href="https://res.cloudinary.com/dstbqtuxm/image/upload/v1782659620/ChatGPT_Image_Jun_28_2026_03_00_42_PM_qkpjbj.png" fetchpriority="high">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Lenis (smooth scroll) + GSAP / ScrollTrigger — chargés en différé : ils ne servent qu'après DOMContentLoaded, donc ne doivent pas bloquer le premier rendu --}}
    <script defer src="https://cdn.jsdelivr.net/npm/lenis@1.1.13/dist/lenis.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>

    {{-- Une seule famille display (Instrument Serif) + une seule famille texte (Inter) : cohérence typographique sur toute la page, et un fichier de police en moins à télécharger --}}
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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

        *, *::before, *::after { box-sizing: border-box; }
        html, body { overflow-x: hidden; max-width: 100%; }
        html.lenis { height: auto; }
        .lenis.lenis-smooth { scroll-behavior: auto !important; }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--blue-night);
            background: #fff;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        section, header, footer, div, article, aside, nav { max-width: 100%; }

        .font-display { font-family: 'Instrument Serif', serif; }
        [x-cloak] { display: none !important; }

        .navbar-transparent { background: transparent; }
        .navbar-solid { background: var(--blue-night); box-shadow: 0 2px 24px rgba(10,22,40,.18); }

        .hero-grid-overlay {
            background-image:
                linear-gradient(rgba(196,168,76,.12) 1px, transparent 1px),
                linear-gradient(90deg, rgba(196,168,76,.12) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        .text-gold-gradient {
            background: linear-gradient(135deg, var(--gold), var(--gold-light), var(--gold));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }

        .reveal { opacity: 0; transform: translateY(28px); }
        .reveal.visible { opacity: 1; transform: translateY(0); transition: opacity .7s cubic-bezier(.22,.8,.24,1), transform .7s cubic-bezier(.22,.8,.24,1); }
        .reveal-delay-1 { transition-delay: .06s; } .reveal-delay-2 { transition-delay: .12s; }
        .reveal-delay-3 { transition-delay: .18s; } .reveal-delay-4 { transition-delay: .24s; }

        .card-lift { transition: transform .3s ease, box-shadow .3s ease; }
        .card-lift:hover { transform: translateY(-4px); box-shadow: 0 20px 48px rgba(10,22,40,.12); }

        .search-input:focus { outline: none; box-shadow: 0 0 0 3px rgba(196,168,76,.3); }
        .stat-card { border-top: 3px solid var(--gold); }
        .cat-pill { transition: background .25s, color .25s, border-color .25s; }
        .cat-pill:hover, .cat-pill.active { background: var(--blue-electric); color: #fff; border-color: var(--blue-electric); }

        .event-img-wrap { overflow: hidden; }
        .event-img-wrap img { transition: transform .45s ease; }
        .event-card:hover .event-img-wrap img { transform: scale(1.06); }

        .partner-logo { filter: grayscale(1) opacity(.5); transition: filter .3s; }
        .partner-logo:hover { filter: grayscale(0) opacity(1); }

        .section-eyebrow { font-size: .7rem; font-weight: 600; letter-spacing: .18em; text-transform: uppercase; color: var(--gold); }
        .nl-input:focus { outline: none; box-shadow: 0 0 0 3px rgba(196,168,76,.35); }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: var(--blue-electric); border-radius: 99px; }

        .pub-slot { transition: box-shadow .25s; overflow: hidden; }
        .pub-slot-label { position: absolute; top: 4px; left: 4px; z-index: 2; padding: 2px 7px; border-radius: 4px; font-size: 9px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; background: rgba(10,22,40,.72); color: #fff; pointer-events: none; }
        .pub-empty { background: repeating-linear-gradient(45deg, var(--gray-soft), var(--gray-soft) 10px, #fff 10px, #fff 20px); }

        /* ── Marquee partenaires ── */
        .marquee-track { display: flex; width: max-content; animation: marquee 34s linear infinite; }
        .marquee-track:hover { animation-play-state: paused; }
        @keyframes marquee { from { transform: translateX(0); } to { transform: translateX(-50%); } }
        .marquee-mask { mask-image: linear-gradient(90deg, transparent, #000 8%, #000 92%, transparent); -webkit-mask-image: linear-gradient(90deg, transparent, #000 8%, #000 92%, transparent); }

        /* ── Calendrier ── */
        .cal-rail { position: relative; }
        .cal-rail::before { content: ''; position: absolute; left: 1.4rem; top: 0; bottom: 0; width: 2px; background: var(--gray-soft); }
        .cal-dot { width: .7rem; height: .7rem; border-radius: 50%; background: var(--blue-electric); border: 3px solid #fff; box-shadow: 0 0 0 2px var(--blue-electric); }

        /* ── Fonctionnement (process) ── */
        .process-line { position: relative; }
        .process-line::before { content: ''; position: absolute; top: 1.75rem; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, var(--gray-soft), var(--gold), var(--gray-soft)); }
        @media (max-width: 1023px) { .process-line::before { display: none; } }

        /* ── Témoignages carousel ── */
        .testi-track { display: flex; transition: transform .6s cubic-bezier(.22,.8,.24,1); }
        .testi-slide { flex: 0 0 100%; }
        @media (min-width: 768px) { .testi-slide { flex: 0 0 33.3333%; } }

        /* ── Galerie masonry ── */
        .masonry { column-count: 2; column-gap: .9rem; }
        @media (min-width: 1024px) { .masonry { column-count: 4; } }
        .masonry-item { break-inside: avoid; margin-bottom: .9rem; border-radius: 1rem; overflow: hidden; position: relative; }
        .masonry-item img, .masonry-item video { width: 100%; display: block; transition: transform .5s ease; }
        .masonry-item:hover img, .masonry-item:hover video { transform: scale(1.06); }
        .masonry-play { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(10,22,40,.25); }

        /* ── CTA final ── */
        .cta-final { position: relative; overflow: hidden; }
        .cta-final::before {
            content: ''; position: absolute; inset: -20%;
            background: radial-gradient(circle at 30% 20%, rgba(30,95,216,.35), transparent 55%),
                        radial-gradient(circle at 75% 80%, rgba(201,168,76,.28), transparent 50%);
            animation: cta-drift 16s ease-in-out infinite;
        }
        @keyframes cta-drift { 0%,100% { transform: translate(0,0) scale(1); } 50% { transform: translate(-3%,3%) scale(1.06); } }
    </style>
</head>

<body>
{{--
|--------------------------------------------------------------------------
| ExpoDakar – Page d'accueil (refonte complète)
|--------------------------------------------------------------------------
--}}

@php
    $pubZones = $pubZones ?? [];

    $pub = function (string $zone, int $w, int $h, string $label) use ($pubZones) {
        $data = $pubZones[$zone] ?? null;
        $img  = $data['image'] ?? null;
        $lien = $data['lien']  ?? '#';
        ob_start(); ?>
        <div class="pub-slot relative rounded-xl overflow-hidden border border-dashed w-full h-full <?= $img ? '' : 'pub-empty' ?>"
             style="border-color: var(--gray-soft); background-color: var(--pearl);"
             data-zone="<?= e($zone) ?>">
            <span class="pub-slot-label">Pub · <?= $w ?>×<?= $h ?></span>
            <a href="<?= e($lien) ?>" target="_blank" rel="noopener sponsored" class="flex items-center justify-center w-full h-full">
                <?php if ($img): ?>
                    <img src="<?= e($img) ?>" alt="Publicité <?= e($label) ?>" class="w-full h-full object-cover" loading="lazy" decoding="async">
                <?php else: ?>
                    <div class="flex flex-col items-center justify-center gap-1 text-center px-3 py-4">
                        <span style="font-size:10px; font-weight:600; text-transform:uppercase; letter-spacing:.08em; color:var(--gray-mid);">Emplacement disponible</span>
                        <span style="font-size:.8rem; font-weight:700; color:var(--blue-night);"><?= e($label) ?></span>
                        <span style="font-size:9px; color:var(--gray-mid);"><?= $w ?> × <?= $h ?> px</span>
                    </div>
                <?php endif; ?>
            </a>
        </div>
        <?php return ob_get_clean();
    };
@endphp

<div class="hidden 2xl:block fixed top-0 left-0 h-screen w-[160px] z-30 overflow-hidden" aria-hidden="true">
    {!! $pub('ap_habillage_gauche', 160, 900, 'Habillage gauche') !!}
</div>
<div class="hidden 2xl:block fixed top-0 right-0 h-screen w-[160px] z-30 overflow-hidden" aria-hidden="true">
    {!! $pub('ap_habillage_droite', 160, 900, 'Habillage droite') !!}
</div>

<div class="2xl:mx-[160px]">

{{-- ═══════════════════════════════════════════════════════
     NAVBAR — ExpoDakar
     Transparent au départ → Blanc au scroll
════════════════════════════════════════════════════════ --}}

<header
    x-data="{
        open: false,
        scrolled: false,
        init() {
            this.scrolled = window.scrollY > 60;

            window.addEventListener('scroll', () => {
                this.scrolled = window.scrollY > 60;
            });
        }
    }"
    x-init="init()"
    :class="scrolled
        ? 'bg-white shadow-md'
        : 'bg-transparent'"
    class="fixed inset-x-0 top-0 z-50 transition-all duration-300">

    {{-- CONTENEUR --}}
    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">

        <div class="flex items-center justify-between h-16 sm:h-20">

            {{-- ═══════════════════════════════════════
                 LOGO
            ═══════════════════════════════════════ --}}
            <a
                href="{{ route('home') }}"
                class="flex items-center gap-2 sm:gap-3 shrink-0"
                aria-label="ExpoDakar">

                <img
                    src="https://res.cloudinary.com/dstbqtuxm/image/upload/v1782085416/ChatGPT_Image_Jun_21__2026__07_24_51_PM-removebg-preview_zi77k0.png"
                    alt="Logo ExpoDakar"
                    class="h-14 sm:h-16 w-auto object-contain"
                    width="80"
                    height="80"
                    fetchpriority="high">

                {{-- NOM EXPO DAKAR --}}
                <span
                    class="font-display text-xl sm:text-2xl font-semibold transition-colors duration-300"
                    :class="scrolled ? 'text-black' : 'text-white'">

                    Expo<span class="text-gold-gradient">Dakar</span>

                </span>

            </a>


            {{-- ═══════════════════════════════════════
                 MENU DESKTOP
            ═══════════════════════════════════════ --}}
            <nav
                class="hidden lg:flex items-center gap-8">

                <a
                    href="#evenements"
                    class="text-sm font-medium transition-colors duration-300"
                    :class="scrolled
                        ? 'text-black hover:text-blue-700'
                        : 'text-white/90 hover:text-white'">
                    Événements
                </a>

                <a
                    href="{{ route('user.categories.index') }}"
                    class="text-sm font-medium transition-colors duration-300"
                    :class="scrolled
                        ? 'text-black hover:text-blue-700'
                        : 'text-white/90 hover:text-white'">
                    Catégories
                </a>

                <a
                    href="#calendrier"
                    class="text-sm font-medium transition-colors duration-300"
                    :class="scrolled
                        ? 'text-black hover:text-blue-700'
                        : 'text-white/90 hover:text-white'">
                    Calendrier
                </a>

                <a
                    href="#exposants"
                    class="text-sm font-medium transition-colors duration-300"
                    :class="scrolled
                        ? 'text-black hover:text-blue-700'
                        : 'text-white/90 hover:text-white'">
                    Exposants
                </a>

                <a
                    href="#faq"
                    class="text-sm font-medium transition-colors duration-300"
                    :class="scrolled
                        ? 'text-black hover:text-blue-700'
                        : 'text-white/90 hover:text-white'">
                    FAQ
                </a>

            </nav>


            {{-- ═══════════════════════════════════════
                 ACTIONS DESKTOP
            ═══════════════════════════════════════ --}}
            <div class="hidden lg:flex items-center gap-3">

                @guest

                    {{-- CONNEXION --}}
                    <a
                        href="{{ route('login') }}"
                        class="text-sm font-medium px-4 py-2 rounded-lg
                               transition-all duration-300"
                        :class="scrolled
                            ? 'text-black hover:bg-gray-100'
                            : 'text-white/90 hover:text-white hover:bg-white'">
                        Connexion
                    </a>


                    {{-- INSCRIPTION --}}
                    <a
                        href="{{ route('register') }}"
                        class="text-sm font-semibold text-white
                               px-5 py-2.5 rounded-xl shadow-sm
                               hover:shadow-md hover:-translate-y-0.5
                               transition-all duration-200"
                        style="background:linear-gradient(135deg,var(--gold),var(--gold-light));">
                        S'inscrire
                    </a>

                @endguest


                @auth

                    {{-- MON ESPACE --}}
                    <a
                        href="{{ route('account') }}"
                        class="text-sm font-semibold text-white
                               px-5 py-2.5 rounded-xl shadow-sm
                               hover:shadow-md hover:-translate-y-0.5
                               transition-all duration-200"
                        style="background:linear-gradient(135deg,var(--blue-electric),#1248b0);">
                        Mon espace
                    </a>

                @endauth

            </div>


            {{-- ═══════════════════════════════════════
                 MOBILE
            ═══════════════════════════════════════ --}}
            <div class="flex items-center gap-2 lg:hidden">

                @auth

                    <a
                        href="{{ route('account') }}"
                        class="text-xs font-semibold text-white
                               px-3 py-1.5 rounded-lg shadow-sm"
                        style="background:linear-gradient(135deg,var(--blue-electric),#1248b0);">
                        Mon espace
                    </a>

                @endauth


                {{-- BOUTON MENU --}}
                <button
                    @click="open = !open"
                    type="button"
                    class="flex items-center justify-center
                           w-10 h-10 rounded-lg
                           border transition-all duration-300"
                    :class="scrolled
                        ? 'text-black bg-white border-gray-200 hover:bg-gray-100'
                        : 'text-white bg-white/10 border-white/20 hover:bg-white/20'"
                    aria-label="Menu"
                    :aria-expanded="open">

                    {{-- HAMBURGER --}}
                    <svg
                        x-show="!open"
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M4 6h16M4 12h16M4 18h16"/>

                    </svg>


                    {{-- FERMER --}}
                    <svg
                        x-show="open"
                        x-cloak
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 18L18 6M6 6l12 12"/>

                    </svg>

                </button>

            </div>

        </div>
    </div>


    {{-- ═══════════════════════════════════════
         MENU MOBILE
    ═══════════════════════════════════════ --}}
    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="lg:hidden bg-white border-t border-gray-200 shadow-lg">

        <nav class="flex flex-col gap-1 px-4 py-4">

            <a
                href="#evenements"
                @click="open = false"
                class="px-4 py-3 text-sm font-medium text-black
                       hover:text-blue-700 hover:bg-gray-50
                       rounded-lg transition">
                Événements
            </a>

            <a
                href="{{ route('user.categories.index') }}"
                @click="open = false"
                class="px-4 py-3 text-sm font-medium text-black
                       hover:text-blue-700 hover:bg-gray-50
                       rounded-lg transition">
                Catégories
            </a>

            <a
                href="#calendrier"
                @click="open = false"
                class="px-4 py-3 text-sm font-medium text-black
                       hover:text-blue-700 hover:bg-gray-50
                       rounded-lg transition">
                Calendrier
            </a>

            <a
                href="#exposants"
                @click="open = false"
                class="px-4 py-3 text-sm font-medium text-black
                       hover:text-blue-700 hover:bg-gray-50
                       rounded-lg transition">
                Exposants
            </a>

            <a
                href="#faq"
                @click="open = false"
                class="px-4 py-3 text-sm font-medium text-black
                       hover:text-blue-700 hover:bg-gray-50
                       rounded-lg transition">
                FAQ
            </a>

            <hr class="border-gray-200 my-2">

            @guest

                <a
                    href="{{ route('login') }}"
                    class="px-4 py-3 text-sm font-medium text-black
                           hover:text-blue-700 hover:bg-gray-50
                           rounded-lg transition">
                    Connexion
                </a>

                <a
                    href="{{ route('register') }}"
                    class="mt-1 px-4 py-3 text-sm font-semibold
                           text-center rounded-xl"
                    style="background:linear-gradient(135deg,var(--gold),var(--gold-light));
                           color:var(--blue-night);">
                    S'inscrire gratuitement
                </a>

            @endguest

            @auth

                <a
                    href="{{ route('account') }}"
                    class="px-4 py-3 text-sm font-semibold
                           text-center text-white rounded-xl"
                    style="background:linear-gradient(135deg,var(--blue-electric),#1248b0);">
                    Mon espace
                </a>

            @endauth

        </nav>
    </div>

</header>

{{-- ══════════════════════════════════════════════════════════════
     HERO img
     ══════════════════════════════════════════════════════════════ --}}
<section id="hero" class="relative h-screen min-h-[720px] flex flex-col overflow-hidden" style="background:var(--blue-night);" aria-label="Bannière principale">

    <div class="absolute inset-0 z-0">
        <img src="https://res.cloudinary.com/dstbqtuxm/image/upload/v1786363396/ChatGPT_Image_10_ao%C3%BBt_2026_02_20_43_vifa4p.png"
             alt="" class="w-full h-full object-cover opacity-40" aria-hidden="true" fetchpriority="high" decoding="async">
        <div class="absolute inset-0 opacity-[.25] hero-tech-grid" aria-hidden="true"></div>
        <div class="absolute inset-0 opacity-[.05] hero-noise" aria-hidden="true"></div>
        <div class="absolute top-[-10%] right-[8%] w-[32rem] h-[32rem] rounded-full blur-[120px] opacity-30 hero-glow-drift" style="background:var(--blue-electric);" aria-hidden="true"></div>
        <div class="absolute bottom-[-15%] left-[5%] w-[26rem] h-[26rem] rounded-full blur-[110px] opacity-20 hero-glow-drift" style="background:var(--gold); animation-delay:-6s;" aria-hidden="true"></div>
        <div class="absolute inset-0 hero-particles" aria-hidden="true">
            <span></span><span></span><span></span><span></span><span></span><span></span>
        </div>
    </div>

    <div class="relative z-10 flex-1 w-full max-w-[92rem] mx-auto px-4 sm:px-6 lg:px-16 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-center pt-28 sm:pt-32 lg:pt-24 pb-14">

        <div class="max-w-2xl" data-hero-reveal>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 sm:px-4 sm:py-2 rounded-full border border-white/15 bg-white/5 backdrop-blur-md mb-6 sm:mb-8" data-hero-item style="--d:0">
                <span class="w-2 h-2 rounded-full animate-pulse shrink-0" style="background:var(--gold);"></span>
                <span class="section-eyebrow text-xs tracking-widest uppercase" style="color:var(--gold-light);">Plateforme officielle — Sénégal</span>
            </div>

            <h1 class="font-display text-[2.75rem] leading-[1.05] sm:text-6xl lg:text-[5.2rem] text-white mb-5 sm:mb-6 tracking-tight" data-hero-item style="--d:1">
               <span class="block hero-gold-gradient"> Le rendez-vous 
                des événements pro
                au Sénégal</span>
            </h1>

            <p class="text-base sm:text-lg text-white/60 max-w-lg leading-relaxed mb-8 sm:mb-10" data-hero-item style="--d:2">
                Salons, conférences, forums, expositions — découvrez, réservez et promouvez les événements qui façonnent l'économie sénégalaise.
            </p>


            <div class="flex flex-wrap gap-3 sm:gap-4 mb-12 sm:mb-16" data-hero-item style="--d:4">
                <a href="{{ route('user.events.index') }}"
                   class="group inline-flex items-center gap-2 px-7 py-3.5 rounded-full font-semibold text-sm transition-all hover:brightness-110 active:scale-95"
                   style="background:linear-gradient(135deg,var(--gold),var(--gold-light));color:var(--blue-night); box-shadow:0 10px 28px rgba(201,168,76,.28);">
                    Explorer les événements
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3"/></svg>
                </a>
                <a href=""
                   class="inline-flex items-center gap-2 px-7 py-3.5 rounded-full font-semibold text-sm text-white border border-white/25 transition-all hover:border-white/60 hover:bg-white/5 active:scale-95">
                    Voir les exposants
                </a>
            </div>

            <div class="flex flex-wrap gap-8 sm:gap-12" data-hero-item style="--d:5">
                @foreach([['+240','Événements référencés'],['+180','Exposants actifs'],['+15k','Visiteurs inscrits']] as [$v,$l])
                <div>
                    <div class="font-display text-3xl sm:text-4xl text-white hero-counter" data-count="{{ $v }}">0</div>
                    <div class="text-xs text-white/45 mt-1.5 tracking-wide">{{ $l }}</div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="relative hidden lg:block h-full min-h-[34rem]" data-hero-reveal style="--d:2">
            <div class="hero-scene absolute inset-0">
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[26rem] h-[26rem] rounded-full blur-[90px] opacity-25" style="background:radial-gradient(circle, var(--blue-electric), transparent 70%);" aria-hidden="true"></div>

                <div class="hero-card hero-float" style="--x:4%; --y:6%; --w:15.5rem; --delay:0s;">
                    <div class="hero-card-media" style="background-image:url('https://res.cloudinary.com/dstbqtuxm/image/upload/v1786465750/events/yb3vyjn2fhuhvtdopjns.jpg'); background-position:20% 30%;"></div>
                    <div class="hero-card-body">
                        <span class="hero-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M4.5 21V7.5l7.5-4.5 7.5 4.5V21M9 21v-6h6v6"/></svg></span>
                        <div><p class="hero-card-title">Salon professionnel</p><p class="hero-card-sub">Stands & networking B2B</p></div>
                    </div>
                </div>

                <div class="hero-card hero-float" style="--x:52%; --y:2%; --w:14rem; --delay:1.2s;">
                    <div class="hero-card-body hero-card-body--solo">
                        <span class="hero-card-icon" style="background:rgba(201,168,76,.15); color:var(--gold-light);"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-1.68 9-3.75S16.97 12.75 12 12.75s-9 1.68-9 3.75 4.03 3.75 9 3.75Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 12.75V3.75m0 0L8.25 6M12 3.75 15.75 6"/></svg></span>
                        <div><p class="hero-card-title">Conférence</p><p class="hero-card-sub">Intervenants experts</p></div>
                    </div>
                </div>

                <div class="hero-card hero-float" style="--x:58%; --y:40%; --w:15rem; --delay:.5s;">
                    <div class="hero-card-body hero-card-body--solo">
                        <span class="hero-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 10.5h7.5m-7.5 3h4.5m-8.25 5.25L9 15.75H5.25A2.25 2.25 0 0 1 3 13.5v-6a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v6a2.25 2.25 0 0 1-2.25 2.25H15l-2.625 3-.375-3"/></svg></span>
                        <div><p class="hero-card-title">Forum</p><p class="hero-card-sub">Échanges & décideurs</p></div>
                    </div>
                </div>

                <div class="hero-card hero-float" style="--x:6%; --y:52%; --w:16rem; --delay:1.8s;">
                    <div class="hero-card-media" style="background-image:url('https://res.cloudinary.com/dstbqtuxm/image/upload/v1786363396/ChatGPT_Image_10_ao%C3%BBt_2026_02_20_43_vifa4p.png'); background-position:70% 60%;"></div>
                    <div class="hero-card-body">
                        <span class="hero-card-icon" style="background:rgba(201,168,76,.15); color:var(--gold-light);"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75 8.25 9l4.5 4.5 3-3 6 6M3.75 3.75h16.5A1.5 1.5 0 0 1 21.75 5.25v13.5a1.5 1.5 0 0 1-1.5 1.5H3.75a1.5 1.5 0 0 1-1.5-1.5V5.25a1.5 1.5 0 0 1 1.5-1.5Z"/></svg></span>
                        <div><p class="hero-card-title">Exposition</p><p class="hero-card-sub">Vitrine produits & services</p></div>
                    </div>
                </div>

                <div class="hero-card hero-card--pill hero-float" style="--x:38%; --y:72%; --w:11.5rem; --delay:2.4s;">
                    <div class="hero-card-body hero-card-body--solo">
                        <span class="hero-card-icon" style="background:rgba(255,255,255,.12);"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg></span>
                        <div><p class="hero-card-title">Networking</p><p class="hero-card-sub">Mise en relation ciblée</p></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="relative z-10 pb-6 flex justify-center" aria-hidden="true">
        <div class="flex flex-col items-center gap-2 opacity-50">
            <span class="text-[.65rem] tracking-[.2em] uppercase text-white/50">Défiler</span>
            <div class="w-px h-8 bg-gradient-to-b from-white/40 to-transparent animate-pulse"></div>
        </div>
    </div>
</section>

<style>
    /* Le hero utilise désormais la même famille display (Instrument Serif) que le reste du site : plus de rupture typographique */
    #hero{ font-family:'Inter', sans-serif; }
    .hero-gold-gradient{ background:linear-gradient(100deg, var(--gold-light), var(--gold) 45%, #F3DFA0 90%); -webkit-background-clip:text; background-clip:text; color:transparent; font-style:normal; }
    .hero-tech-grid{ background-image:linear-gradient(rgba(255,255,255,.14) 1px, transparent 1px),linear-gradient(90deg, rgba(255,255,255,.14) 1px, transparent 1px); background-size:56px 56px; mask-image:radial-gradient(ellipse 70% 60% at 30% 40%, #000 40%, transparent 90%); }
    .hero-noise{ background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E"); mix-blend-mode:overlay; }
    @keyframes hero-glow-drift{ 0%,100%{ transform:translate(0,0) scale(1); } 50%{ transform:translate(-4%,3%) scale(1.08); } }
    .hero-glow-drift{ animation:hero-glow-drift 14s ease-in-out infinite; }
    .hero-particles span{ position:absolute; width:3px; height:3px; border-radius:50%; background:var(--gold-light); opacity:.5; animation:hero-particle-drift linear infinite; box-shadow:0 0 8px 1px rgba(232,201,106,.6); }
    .hero-particles span:nth-child(1){ left:12%; top:80%; animation-duration:16s; animation-delay:0s; }
    .hero-particles span:nth-child(2){ left:28%; top:65%; animation-duration:21s; animation-delay:-4s; }
    .hero-particles span:nth-child(3){ left:47%; top:88%; animation-duration:18s; animation-delay:-9s; }
    .hero-particles span:nth-child(4){ left:65%; top:70%; animation-duration:24s; animation-delay:-2s; }
    .hero-particles span:nth-child(5){ left:81%; top:85%; animation-duration:19s; animation-delay:-12s; }
    .hero-particles span:nth-child(6){ left:92%; top:60%; animation-duration:22s; animation-delay:-7s; }
    @keyframes hero-particle-drift{ 0%{ transform:translateY(0) translateX(0); opacity:0; } 10%{ opacity:.6; } 90%{ opacity:.4; } 100%{ transform:translateY(-70vh) translateX(3vw); opacity:0; } }
    .hero-search-shell{ transition:border-color .3s, box-shadow .3s; }
    .hero-search-shell:focus-within{ border-color:rgba(201,168,76,.5); box-shadow:0 0 0 4px rgba(201,168,76,.12); }
    [data-hero-item]{ opacity:0; transform:translateY(22px); animation:hero-item-in .8s cubic-bezier(.22,.8,.24,1) forwards; animation-delay:calc(var(--d) * 90ms + 150ms); }
    @keyframes hero-item-in{ to{ opacity:1; transform:none; } }
    .hero-scene{ perspective:1400px; transform-style:preserve-3d; }
    .hero-card{ position:absolute; left:var(--x); top:var(--y); width:var(--w); border-radius:1.25rem; overflow:hidden; background:rgba(255,255,255,.07); backdrop-filter:blur(18px); -webkit-backdrop-filter:blur(18px); border:1px solid rgba(255,255,255,.14); box-shadow:0 20px 50px -12px rgba(0,0,0,.5), inset 0 1px 0 rgba(255,255,255,.12); transition:transform .4s cubic-bezier(.22,.8,.24,1), box-shadow .4s; will-change:transform; }
    .hero-card:hover{ transform:translateY(-6px) scale(1.03) !important; box-shadow:0 28px 60px -10px rgba(0,0,0,.55), inset 0 1px 0 rgba(255,255,255,.18); z-index:5; }
    .hero-card-media{ height:6.5rem; background-size:cover; filter:saturate(1.1); }
    .hero-card-body{ display:flex; align-items:center; gap:.75rem; padding:1rem 1.1rem; }
    .hero-card-body--solo{ padding:1.15rem 1.2rem; }
    .hero-card-icon{ width:2.35rem; height:2.35rem; flex-shrink:0; border-radius:.75rem; background:rgba(30,95,216,.22); color:#BFD3FF; display:flex; align-items:center; justify-content:center; }
    .hero-card-icon svg{ width:1.15rem; height:1.15rem; }
    .hero-card-title{ font-size:.86rem; font-weight:700; color:#fff; line-height:1.2; }
    .hero-card-sub{ font-size:.72rem; color:rgba(255,255,255,.5); margin-top:.15rem; }
    @keyframes hero-float{ 0%,100%{ transform:translate(0,0) rotate(0deg); } 50%{ transform:translate(0,-14px) rotate(.6deg); } }
    .hero-float{ animation:hero-float 7s ease-in-out infinite; animation-delay:var(--delay); }
    @media (prefers-reduced-motion: reduce){ .hero-float, .hero-glow-drift, .hero-particles span, [data-hero-item]{ animation:none !important; opacity:1 !important; transform:none !important; } }
    @media (max-width:1023px){ #hero{ height:auto; min-height:100svh; } }
</style>


{{-- ══ TOP A2M ══ --}}
<div class="w-full bg-white py-3 px-4">
    <div class="max-w-4xl mx-auto h-[80px] sm:h-[100px] lg:h-[120px]">
        {!! $pub('top_a2m', 970, 250, 'Top bannière') !!}
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     SECTION 1 — PARTENAIRES (marquee infini avec logos) 
══════════════════════════════════════════════════════════════ --}}
<section
    class="py-12 border-y bg-white"
    style="border-color:var(--gray-soft);"
    aria-label="Partenaires">

    {{-- TITRE --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">

        <p
            class="text-center text-xs font-semibold tracking-widest uppercase mb-8"
            style="color:var(--gray-mid);">
            Ils nous font confiance
        </p>

    </div>

    {{-- MARQUEE --}}
    <div class="marquee-mask overflow-hidden">

        <div class="marquee-track">

            {{-- Première série CCIAD--}}
            <div class="flex items-center gap-12 sm:gap-20 pr-12 sm:pr-20">

                @foreach([
                    ['nom' => 'Max it',      'logo' => 'https://res.cloudinary.com/dstbqtuxm/image/upload/v1786461747/maxit_jn3vx2.webp'],
                    ['nom' => 'cese',       'logo' => 'https://res.cloudinary.com/dstbqtuxm/image/upload/v1786461748/cese_mdir0z.webp'],
                    ['nom' => 'lywa',             'logo' => 'https://res.cloudinary.com/dstbqtuxm/image/upload/v1786461753/lywa_h0vg2q.webp'],
                    ['nom' => 'samsung',         'logo' => 'https://res.cloudinary.com/dstbqtuxm/image/upload/v1786461752/samsung_yabejo.webp'],
                    ['nom' => 'tooshare',        'logo' => 'https://res.cloudinary.com/dstbqtuxm/image/upload/v1786461753/tooshare_znn12k.webp'],
                    ['nom' => 'bourse',          'logo' => 'https://res.cloudinary.com/dstbqtuxm/image/upload/v1786461747/cgfbourse_m1zhvb.webp'],
                    ['nom' => 'francophonie',       'logo' => 'https://res.cloudinary.com/dstbqtuxm/image/upload/v1786461751/francophonie_kqcgcx.webp'],
                    ['nom' => 'fazah',      'logo' => 'https://res.cloudinary.com/dstbqtuxm/image/upload/v1786461750/fazah_fqy6lw.webp'],
                    ['nom' => 'destination senegal',      'logo' => 'https://res.cloudinary.com/dstbqtuxm/image/upload/v1786461750/aspt_t7dg0x.webp'],
                    ['nom' => 'Orange money',      'logo' => 'https://res.cloudinary.com/dstbqtuxm/image/upload/v1786461751/OM_cjpf6u.webp'],
                    ['nom' => 'Patisen',      'logo' => 'https://res.cloudinary.com/dstbqtuxm/image/upload/v1786461751/patisen_j1ftiv.webp'],
                    ['nom' => 'IAM',      'logo' => 'https://res.cloudinary.com/dstbqtuxm/image/upload/v1786461751/iam_uegvzz.webp'],
                    ['nom' => 'senelec',      'logo' => 'https://res.cloudinary.com/dstbqtuxm/image/upload/v1786461753/senelec_qmfw6m.webp'],
                    ['nom' => 'ministere',      'logo' => 'https://res.cloudinary.com/dstbqtuxm/image/upload/v1786461749/ministere_education_ptbq04.webp'],
                    ['nom' => 'Ctic',      'logo' => 'https://res.cloudinary.com/dstbqtuxm/image/upload/v1786461748/ctic_mo5sbu.webp'],
                    ['nom' => 'ujes',      'logo' => 'https://res.cloudinary.com/dstbqtuxm/image/upload/v1786461752/ujes_psnh7o.webp'],
                    ['nom' => 'ville',      'logo' => 'https://res.cloudinary.com/dstbqtuxm/image/upload/v1786461754/villededakar_nskciq.webp'],
                ] as $partenaire)

                    <div
                        class="flex items-center justify-center
                               w-32 sm:w-40 h-20 sm:h-24
                               shrink-0">

                        <img
                            src="{{ $partenaire['logo'] }}"
                            alt="Logo {{ $partenaire['nom'] }}"
                            class="max-w-full max-h-16 sm:max-h-20
                                   object-contain
                                   hover:-0 hover:opacity-100
                                   transition-all duration-300"
                            loading="lazy">

                    </div>

                @endforeach

            </div>



        </div>

    </div>

</section>

{{-- ══════════════════════════════════════════════════════════════
     SECTION 2 — POURQUOI EXPODAKAR
     ══════════════════════════════════════════════════════════════ --}}
<section class="py-16 sm:py-24 overflow-hidden" style="background:var(--blue-night);" aria-label="Nos atouts">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">
        <div class="grid lg:grid-cols-2 gap-10 sm:gap-16 items-center mb-14 sm:mb-20">
            <div>
                <p class="section-eyebrow mb-4">Notre valeur ajoutée</p>
                <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl text-white mb-4 sm:mb-6">
                    Pourquoi choisir<br><span class="text-gold-gradient">ExpoDakar</span> ?
                </h2>
                <p class="text-white/60 text-sm sm:text-base leading-relaxed mb-8 sm:mb-10">
                    Une plateforme pensée pour l'écosystème économique sénégalais.
                </p>
                <div class="space-y-5 sm:space-y-6">
                    @foreach([
                        ['Découverte simplifiée','Trouvez rapidement les événements pertinents grâce à une recherche avancée.','M9.348 14.652a7.5 7.5 0 1 0-1.06 1.06l4.157 4.158a.75.75 0 1 0 1.06-1.06l-4.157-4.158ZM3 9.75a5.25 5.25 0 1 1 10.5 0 5.25 5.25 0 0 1-10.5 0Z'],
                        ['Réservation instantanée','Réservez votre place en quelques clics. Recevez votre billet QR par email.','M2.25 8.25h19.5M6 4.5v3.75m12-3.75v3.75M6.75 12h.008v.008H6.75V12Zm3 0h.008v.008h-.008V12Zm3 0h.008v.008h-.008V12Zm3 0h.008v.008h-.008V12Zm-9 3h.008v.008H6.75V15Zm3 0h.008v.008h-.008V15Zm3 0h.008v.008h-.008V15Zm3 0h.008v.008h-.008V15ZM4.5 6.75h15a.75.75 0 0 1 .75.75v11.25a.75.75 0 0 1-.75.75h-15a.75.75 0 0 1-.75-.75V7.5a.75.75 0 0 1 .75-.75Z'],
                        ['Visibilité maximale','Exposants et organisateurs bénéficient d\'outils de promotion performants.','M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178ZM15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z'],
                        ['Tableau de bord complet','Suivez vos inscriptions et gérez vos événements en temps réel.','M3 13.5V21m0-7.5 4.5-4.5m-4.5 4.5 4.5 4.5M12 3v18m0-18 4.5 4.5M12 3l-4.5 4.5M18.75 8.25V21m0-12.75 2.25 2.25m-2.25-2.25L16.5 10.5'],
                    ] as $i => [$title,$desc,$icon])
                    <div class="flex gap-4 reveal reveal-delay-{{ $i+1 }}" x-intersect.once="$el.classList.add('visible')">
                        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl flex items-center justify-center shrink-0 mt-0.5" style="background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.1);">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white text-sm sm:text-base mb-1">{{ $title }}</h3>
                            <p class="text-xs sm:text-sm text-white/55 leading-relaxed">{{ $desc }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="relative reveal reveal-delay-2" x-intersect.once="$el.classList.add('visible')">
                <div class="relative rounded-2xl sm:rounded-3xl overflow-hidden" style="background:linear-gradient(135deg,rgba(30,95,216,.2),rgba(196,168,76,.1)); border:1px solid rgba(255,255,255,.08);">
                    <div class="p-5 sm:p-8 grid grid-cols-2 gap-3 sm:gap-5">
                        @foreach([['Événements actifs','48','+12%'],['Inscriptions mois','1 240','+28%'],['Exposants vérifiés','183','+5%'],['Satisfaction','98%','↑']] as $c)
                        <div class="rounded-xl sm:rounded-2xl p-4 sm:p-5" style="background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.08);">
                            <div class="text-xs mb-2" style="color:rgba(255,255,255,.45);">{{ $c[0] }}</div>
                            <div class="font-display text-2xl sm:text-3xl text-white mb-1">{{ $c[1] }}</div>
                            <div class="text-xs font-medium" style="color:var(--gold-light);">{{ $c[2] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- 4 cartes : Découvrir / Réserver / Exposer / Développer son réseau --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
            @foreach([
                ['Découvrir','Parcourez tous les événements professionnels du pays en un seul endroit.','M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z'],
                ['Réserver','Sécurisez votre place en quelques secondes, billet QR envoyé instantanément.','M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h4.5c.621 0 1.125-.504 1.125-1.125V3.375c0-.621-.504-1.125-1.125-1.125Z M17.625 5.25h-4.5c-.621 0-1.125.504-1.125 1.125v14.25c0 .621.504 1.125 1.125 1.125h4.5c.621 0 1.125-.504 1.125-1.125V6.375c0-.621-.504-1.125-1.125-1.125Z M8.25 6h.008v.008H8.25V6Zm0 3h.008v.008H8.25V9Zm0 3h.008v.008H8.25V12Zm0 3h.008v.008H8.25V15Z'],
                ['Exposer','Présentez votre entreprise à des milliers de visiteurs qualifiés.','M2.25 21h19.5M3 21V9.75m18 11.25V9.75M4.5 9.75l7.5-6 7.5 6M9 21v-6.75h6V21'],
                ['Développer son réseau','Connectez-vous aux bons interlocuteurs grâce au networking ciblé.','M13.5 21v-5.25a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21M8.25 21v-5.25a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75V21M2.25 21h19.5M3 21V6.75c0-.621.504-1.125 1.125-1.125h1.5c.621 0 1.125.504 1.125 1.125V21m9-14.25v-1.5c0-.621.504-1.125 1.125-1.125h1.5c.621 0 1.125.504 1.125 1.125v1.5m-3.75 0h3.75'],
            ] as $i => [$t,$d,$icon])
            <div class="rounded-2xl p-5 sm:p-6 reveal reveal-delay-{{ $i+1 }}" style="background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.09);" x-intersect.once="$el.classList.add('visible')">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4" style="background:linear-gradient(135deg,var(--gold),var(--gold-light));">
                    <svg class="w-5 h-5" style="color:var(--blue-night);" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                </div>
                <h3 class="font-semibold text-white text-sm sm:text-base mb-1.5">{{ $t }}</h3>
                <p class="text-xs sm:text-sm text-white/50 leading-relaxed">{{ $d }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     SECTION 3 — ÉVÉNEMENTS EN VEDETTE
     ══════════════════════════════════════════════════════════════ --}}
<section id="evenements" class="py-16 sm:py-24" style="background:var(--pearl);" aria-label="Événements">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10 sm:mb-16">
            <div>
                <p class="section-eyebrow mb-2">À ne pas manquer</p>
                <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl" style="color:var(--blue-night);">Événements en vedette</h2>
            </div>
            <a href="{{ route('user.events.index') }}" class="text-sm font-semibold group shrink-0" style="color:var(--blue-electric);">
                Voir tout <span class="group-hover:translate-x-1 inline-block transition-transform">→</span>
            </a>
        </div>

        <div class="lg:flex lg:items-start lg:gap-8">
            <aside class="hidden lg:block lg:w-[300px] shrink-0 order-2 sticky top-28 self-start" aria-label="Espace publicitaire">
                <div class="h-[600px]">{!! $pub('a1r', 300, 600, 'Pavé A1R') !!}</div>
            </aside>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-7 order-1 flex-1 w-full">
                @forelse($events as $idx => $event)
                @php $debut = \Carbon\Carbon::parse($event->date_debut); @endphp
                <article class="event-card card-lift bg-white rounded-2xl overflow-hidden reveal reveal-delay-{{ ($idx%3)+1 }}"
                         style="box-shadow:0 4px 24px rgba(10,22,40,.07);"
                         x-intersect.once="$el.classList.add('visible')"
                         aria-label="{{ $event->titre }}">
                    <div class="event-img-wrap relative h-48 sm:h-52">
                        @if($event->image)
                        <img src="{{ $event->image }}" alt="{{ $event->titre }}" class="w-full h-full object-cover" loading="lazy" decoding="async">
                        @else
                        <div class="w-full h-full flex items-center justify-center" style="background:linear-gradient(135deg,var(--blue-night),var(--blue-electric));">
                            <svg class="w-10 h-10 text-white/30" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159"/></svg>
                        </div>
                        @endif
                        <div class="absolute inset-0" style="background:linear-gradient(0deg, rgba(10,22,40,.55) 0%, transparent 45%);"></div>
                        @if($event->categorie)
                        <span class="absolute top-3 left-3 px-2.5 py-1 text-xs font-semibold rounded-full" style="background:rgba(10,22,40,.7);color:var(--gold-light);">{{ $event->categorie->nom }}</span>
                        @endif
                        <div class="absolute top-3 right-3 flex flex-col items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-white shadow-lg">
                            <span class="text-xs font-bold leading-none" style="color:var(--blue-electric);">{{ $debut->format('d') }}</span>
                            <span class="text-xs uppercase leading-none mt-0.5" style="color:var(--gray-mid);">{{ $debut->translatedFormat('M') }}</span>
                        </div>
                        @if(isset($event->visiteurs_count))
                        <div class="absolute bottom-3 left-3 flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium text-white" style="background:rgba(10,22,40,.55); backdrop-filter:blur(4px);">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Z"/></svg>
                            {{ $event->visiteurs_count }} visiteurs
                        </div>
                        @endif
                    </div>
                    <div class="p-4 sm:p-6">
                        @if($event->exposant)
                        <p class="text-xs mb-2" style="color:var(--gray-mid);">{{ $event->exposant->nom }}</p>
                        @endif
                        <h3 class="font-semibold text-sm sm:text-base leading-snug mb-2 sm:mb-3 line-clamp-2" style="color:var(--blue-night);">{{ $event->titre }}</h3>
                        <div class="flex items-center gap-3 text-xs mb-4 flex-wrap" style="color:var(--gray-mid);">
                            <span class="flex items-center gap-1 min-w-0">
                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                <span class="truncate">{{ $event->lieu }}</span>
                            </span>
                            <span class="shrink-0">{{ $debut->diffInDays($event->date_fin)+1 }}j</span>
                            @if(isset($event->prix))
                            <span class="shrink-0 font-semibold" style="color:var(--blue-electric);">{{ $event->prix > 0 ? number_format($event->prix,0,',',' ').' FCFA' : 'Gratuit' }}</span>
                            @endif
                        </div>
                        <a href="{{ route('user.events.show', $event->id) }}"
                           class="block w-full text-center py-2.5 sm:py-3 rounded-xl text-sm font-semibold transition hover:brightness-105"
                           style="background:linear-gradient(135deg,var(--blue-electric),#1248b0);color:white;">
                            Voir plus
                        </a>
                    </div>
                </article>
                @empty
                <div class="col-span-2 text-center py-16">
                    <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="var(--gray-mid)" stroke-width="1.4" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                    <p class="font-semibold text-lg mb-1" style="color:var(--blue-night);">Aucun événement disponible</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</section>


{{-- ══ SPLH ══ --}}
<div class="bg-white py-4 px-4">
    <div class="max-w-7xl mx-auto h-[80px] sm:h-[100px] lg:h-[120px]">{!! $pub('splh', 1000, 120, 'Bannière SPLH') !!}</div>
</div>


{{-- ══════════════════════════════════════════════════════════════
     SECTION 4 — CATÉGORIES
     ══════════════════════════════════════════════════════════════ --}}
<section id="categories" class="py-16 sm:py-24 bg-white" aria-label="Catégories">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">
        <div class="text-center mb-10 sm:mb-16">
            <p class="section-eyebrow mb-3">Explorer par thème</p>
            <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl mb-3" style="color:var(--blue-night);">Catégories populaires</h2>
            <p class="text-sm max-w-lg mx-auto" style="color:var(--gray-mid);">Trouvez les événements qui correspondent à votre secteur.</p>
        </div>

        <div class="flex flex-wrap justify-center gap-2 sm:gap-3 mb-10 sm:mb-12" x-data="{ active:'all' }">
            <button @click="active='all'" :class="active==='all'?'active':''" class="cat-pill px-4 py-2 text-xs sm:text-sm font-medium rounded-full border transition" style="border-color:var(--gray-soft);color:var(--blue-night);">Tous</button>
            @foreach($categories as $cat)
            <button @click="active='{{ $cat->id }}'" :class="active==='{{ $cat->id }}'?'active':''" class="cat-pill px-4 py-2 text-xs sm:text-sm font-medium rounded-full border transition" style="border-color:var(--gray-soft);color:var(--blue-night);">{{ $cat->nom }}</button>
            @endforeach
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-5">
            @php
            $catIcons = [
                'M2.25 21h19.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5v1.5H9v-1.5Zm4.5 0H15v1.5h-1.5v-1.5ZM9 11.25h1.5v1.5H9v-1.5Zm4.5 0H15v1.5h-1.5v-1.5ZM9 15.75h1.5v1.5H9v-1.5Zm4.5 0H15v1.5h-1.5v-1.5Z',
                'M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25',
                'M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z',
                'M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z',
                'M4.5 12.75l6 6 9-13.5',
                'M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.625c.621 0 1.125.504 1.125 1.125v.375m-18 0h18M4.5 4.5v.75c0 .414-.336.75-.75.75h-.75m19.5-1.5v.75c0 .414.336.75.75.75h.75m-1.5 0v.375c0 .621-.504 1.125-1.125 1.125H4.125A1.125 1.125 0 0 1 3 6.375V6m18 6v.375c0 .621-.504 1.125-1.125 1.125H4.125A1.125 1.125 0 0 1 3 18.375V18m18-6h-1.5m1.5 0v-.75a.75.75 0 0 0-.75-.75h-.75M4.5 12H3m1.5 0v-.75A.75.75 0 0 0 3.75 10.5H3m0 0v.75c0 .414.336.75.75.75h.75m0-1.5H4.5',
                'M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205 3 1.09m1.5.545-1.5-.546M6.75 7.364V3h-3v18m3-13.636 10.5-3.819',
                'M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155',
            ];
            @endphp
            @foreach($categories as $idx => $cat)
            <a href="{{ route('user.events.index', ['categorie'=>$cat->id]) }}"
               class="group flex flex-col items-center gap-3 p-4 sm:p-8 rounded-2xl border text-center transition-all hover:border-blue-200 card-lift reveal reveal-delay-{{ ($idx%4)+1 }}"
               style="border-color:var(--gray-soft);" x-intersect.once="$el.classList.add('visible')">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110" style="background:#EFF6FF;">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" style="color:var(--blue-electric);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $catIcons[$idx % count($catIcons)] }}"/>
                    </svg>
                </div>
                <div>
                    <div class="font-semibold text-xs sm:text-sm mb-0.5" style="color:var(--blue-night);">{{ $cat->nom }}</div>
                    @if(isset($cat->events_count))
                    <div class="text-xs" style="color:var(--gray-mid);">{{ $cat->events_count }} évén.</div>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     SECTION 5 — CALENDRIER
     ══════════════════════════════════════════════════════════════ --}}
@php
    // Regroupement simple par mois à partir de la collection $events déjà disponible.
    // (idéalement précalculé côté contrôleur pour de gros volumes)
    $calendrier = collect($events)->sortBy('date_debut')->groupBy(function($e){
        return \Carbon\Carbon::parse($e->date_debut)->translatedFormat('F Y');
    });
@endphp
<section id="calendrier" class="py-16 sm:py-24" style="background:var(--pearl);" aria-label="Calendrier des événements">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-16">
        <div class="text-center mb-10 sm:mb-16">
            <p class="section-eyebrow mb-3">Planifiez votre agenda</p>
            <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl mb-3" style="color:var(--blue-night);">Calendrier des événements</h2>
            <p class="text-sm max-w-lg mx-auto" style="color:var(--gray-mid);">Tous les événements à venir, mois par mois.</p>
        </div>

        <div x-data="{ month:'all' }">
            <div class="flex flex-wrap justify-center gap-2 sm:gap-3 mb-10 sm:mb-14">
                <button @click="month='all'" :class="month==='all' ? 'active' : ''" class="cat-pill px-4 py-2 text-xs sm:text-sm font-medium rounded-full border transition" style="border-color:var(--gray-soft);color:var(--blue-night);">Tous les mois</button>
                @foreach($calendrier->keys() as $m)
                <button @click="month='{{ $m }}'" :class="month==='{{ $m }}' ? 'active' : ''" class="cat-pill px-4 py-2 text-xs sm:text-sm font-medium rounded-full border transition capitalize" style="border-color:var(--gray-soft);color:var(--blue-night);">{{ $m }}</button>
                @endforeach
            </div>

            @forelse($calendrier as $mois => $evs)
            <div x-show="month==='all' || month==='{{ $mois }}'" x-cloak class="mb-12 sm:mb-16 last:mb-0">
                <h3 class="font-display text-xl sm:text-2xl capitalize mb-6 sm:mb-8" style="color:var(--blue-night);">{{ $mois }}</h3>
                <div class="cal-rail pl-10 sm:pl-12 space-y-6 sm:space-y-8">
                    @foreach($evs as $ev)
                    @php $d = \Carbon\Carbon::parse($ev->date_debut); @endphp
                    <div class="relative reveal reveal-delay-1" x-intersect.once="$el.classList.add('visible')">
                        <span class="cal-dot absolute -left-[2.15rem] sm:-left-[2.45rem] top-1.5"></span>
                        <a href="{{ route('user.events.show', $ev->id) }}" class="card-lift flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-6 bg-white rounded-2xl p-4 sm:p-5" style="box-shadow:0 4px 20px rgba(10,22,40,.06);">
                            <div class="flex flex-col items-center justify-center w-14 h-14 rounded-xl shrink-0" style="background:var(--pearl);">
                                <span class="text-base font-bold leading-none" style="color:var(--blue-electric);">{{ $d->format('d') }}</span>
                                <span class="text-[10px] uppercase leading-none mt-1" style="color:var(--gray-mid);">{{ $d->translatedFormat('M') }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-sm sm:text-base truncate" style="color:var(--blue-night);">{{ $ev->titre }}</p>
                                <p class="text-xs mt-1" style="color:var(--gray-mid);">{{ $ev->lieu }} @if($ev->categorie) · {{ $ev->categorie->nom }} @endif</p>
                            </div>
                            <span class="text-xs font-semibold shrink-0" style="color:var(--blue-electric);">Détails →</span>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <p class="text-center text-sm" style="color:var(--gray-mid);">Aucun événement programmé pour le moment.</p>
            @endforelse
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     SECTION 6 — EXPOSANTS PREMIUM
     ══════════════════════════════════════════════════════════════ --}}
<section id="exposants" class="py-16 sm:py-24 bg-white" aria-label="Exposants">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">
        <div class="text-center mb-10 sm:mb-16">
            <p class="section-eyebrow mb-3">Ils exposent sur ExpoDakar</p>
            <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl mb-3" style="color:var(--blue-night);">Exposants premium</h2>
            <p class="text-sm max-w-lg mx-auto" style="color:var(--gray-mid);">Des entreprises de premier plan qui font confiance à notre plateforme.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @foreach($exposants as $idx => $exposant)
            <div class="card-lift flex flex-col gap-4 p-5 sm:p-7 rounded-2xl border reveal reveal-delay-{{ ($idx%3)+1 }}" style="border-color:var(--gray-soft);" x-intersect.once="$el.classList.add('visible')">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 overflow-hidden" style="background:var(--pearl);">
                        @if($exposant->logo)
                        <img src="{{ $exposant->logo }}" alt="Logo {{ $exposant->nom_entreprise ?? $exposant->nom }}" class="w-full h-full object-contain p-1.5" loading="lazy" decoding="async">
                        @else
                        <span class="font-display text-2xl font-bold" style="color:var(--blue-electric);">{{ strtoupper(substr($exposant->nom_entreprise ?? $exposant->nom, 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-semibold text-sm sm:text-base truncate" style="color:var(--blue-night);">{{ $exposant->nom_entreprise ?? $exposant->nom }}</h3>
                        @if($exposant->secteur_activite ?? $exposant->secteur ?? null)
                        <span class="text-xs px-2 py-0.5 rounded-full mt-1 inline-block" style="background:rgba(30,95,216,.08);color:var(--blue-electric);">{{ $exposant->secteur_activite ?? $exposant->secteur }}</span>
                        @endif
                    </div>
                </div>
                @if(isset($exposant->stand))
                <p class="text-xs" style="color:var(--gray-mid);">Stand <span class="font-semibold" style="color:var(--blue-night);">{{ $exposant->stand }}</span></p>
                @endif
                @if($exposant->description ?? null)
                <p class="text-xs sm:text-sm leading-relaxed line-clamp-3 flex-1" style="color:var(--gray-mid);">{{ $exposant->description }}</p>
                @endif
                <div class="flex items-center gap-3 pt-3 border-t" style="border-color:var(--gray-soft);">
                    @if($exposant->site_web ?? null)
                    <a href="{{ $exposant->site_web }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-xs font-medium" style="color:var(--blue-electric);">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg>
                        Site web
                    </a>
                    @endif
                    <a href="{{ route('user.exposants.show', $exposant->id) }}" class="ml-auto text-xs font-semibold" style="color:var(--gray-mid);">Voir →</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══ BLOC SPECIAL ══ --}}
<section class="py-12 sm:py-16 bg-white" aria-label="Espace partenaire">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">
        <div class="rounded-2xl p-6 sm:p-10 grid lg:grid-cols-[1fr_300px] gap-6 sm:gap-8 items-center" style="background:var(--pearl);">
            <div>
                <p class="section-eyebrow mb-3">Espace partenaire</p>
                <h3 class="font-display text-2xl sm:text-4xl mb-3" style="color:var(--blue-night);">Mettez votre marque en avant</h3>
                <p class="text-sm leading-relaxed max-w-md" style="color:var(--gray-mid);">Cet emplacement premium est visible par tous les visiteurs d'ExpoDakar.</p>
            </div>
            <div class="h-[250px] sm:h-[400px] lg:h-[600px] w-full lg:max-w-[300px] lg:mx-auto">{!! $pub('bloc_special', 300, 600, 'Bloc spécial') !!}</div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     SECTION 7 — STATISTIQUES
     ══════════════════════════════════════════════════════════════ --}}
<section class="py-16 sm:py-24" style="background:var(--pearl);" aria-label="Chiffres clés"
    x-data="{
        animated:false,
        init() {
            const io = new IntersectionObserver(e => { if(e[0].isIntersecting){ this.startCounters(); io.disconnect(); } },{threshold:.3});
            io.observe(this.$el);
        },
        startCounters() {
            if(this.animated) return; this.animated=true;
            this.$el.querySelectorAll('[data-final]').forEach(el => {
                const val = parseInt(el.dataset.final, 10);
                let cur = 0; const steps = 80; const inc = val/steps;
                const t = setInterval(() => { cur = Math.min(cur+inc, val); el.textContent = Math.floor(cur).toLocaleString('fr-FR'); if(cur>=val) clearInterval(t); }, 40);
            });
        }
    }" x-init="init()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">
        <div class="text-center mb-10 sm:mb-16">
            <p class="section-eyebrow mb-3">Chiffres clés</p>
            <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl" style="color:var(--blue-night);">ExpoDakar en quelques chiffres</h2>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach([['240','+','Événements organisés','#1E5FD8'],['180','+','Exposants référencés','#7C3AED'],['15000','+','Visiteurs enregistrés','#059669'],['50','+','Partenaires institutionnels','#D97706']] as $i=>[$val,$suf,$lbl,$clr])
            <div class="stat-card bg-white rounded-2xl p-5 sm:p-8 reveal reveal-delay-{{ $i+1 }}" x-intersect.once="$el.classList.add('visible')" style="box-shadow:0 4px 24px rgba(10,22,40,.06);">
                <p class="font-display text-3xl sm:text-5xl font-light mb-3 sm:mb-5" style="color:{{ $clr }};"><span data-final="{{ $val }}">0</span>{{ $suf }}</p>
                <p class="text-xs sm:text-sm font-medium" style="color:var(--gray-mid);">{{ $lbl }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     SECTION 8 — FONCTIONNEMENT
     ══════════════════════════════════════════════════════════════ --}}
<section class="py-16 sm:py-24 bg-white" aria-label="Comment ça marche">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">
        <div class="text-center mb-14 sm:mb-20">
            <p class="section-eyebrow mb-3">Simple et rapide</p>
            <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl mb-3" style="color:var(--blue-night);">Comment ça fonctionne</h2>
            <p class="text-sm max-w-lg mx-auto" style="color:var(--gray-mid);">Cinq étapes pour tirer le meilleur parti d'ExpoDakar.</p>
        </div>
        <div class="process-line grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8 sm:gap-6">
            @foreach([
                ['Découvrir','Explorez les événements et exposants qui correspondent à votre secteur.','M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418'],
                ['Réserver','Choisissez votre événement et réservez votre place en quelques clics.','M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h4.5c.621 0 1.125-.504 1.125-1.125V3.375c0-.621-.504-1.125-1.125-1.125Z M17.625 5.25h-4.5c-.621 0-1.125.504-1.125 1.125v14.25c0 .621.504 1.125 1.125 1.125h4.5c.621 0 1.125-.504 1.125-1.125V6.375c0-.621-.504-1.125-1.125-1.125Z'],
                ['Participer','Rendez-vous sur place avec votre billet QR, tout est prêt.','M2.25 5.25a3 3 0 0 1 3-3h13.5a3 3 0 0 1 3 3V15a3 3 0 0 1-3 3h-3v.257c0 .597.237 1.17.659 1.591l.621.622a.75.75 0 0 1-.53 1.28h-9a.75.75 0 0 1-.53-1.28l.621-.622a2.25 2.25 0 0 0 .659-1.591V18h-3a3 3 0 0 1-3-3V5.25Zm1.5 0v7.5c0 .414.336.75.75.75h13.5a.75.75 0 0 0 .75-.75v-7.5a.75.75 0 0 0-.75-.75H4.5a.75.75 0 0 0-.75.75Z'],
                ['Networker','Échangez avec exposants, visiteurs et décideurs présents.','M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z'],
                ['Développer','Transformez vos échanges en opportunités concrètes pour votre activité.','M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941'],
            ] as $i => [$t,$d,$icon])
            <div class="relative flex flex-col items-center text-center reveal reveal-delay-{{ $i+1 }}" x-intersect.once="$el.classList.add('visible')">
                <div class="relative z-10 w-14 h-14 rounded-2xl flex items-center justify-center mb-5 shadow-lg" style="background:linear-gradient(135deg,var(--blue-electric),#1248b0);">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                    <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold" style="background:var(--gold-light); color:var(--blue-night);">{{ $i+1 }}</span>
                </div>
                <h3 class="font-semibold text-sm sm:text-base mb-1.5" style="color:var(--blue-night);">{{ $t }}</h3>
                <p class="text-xs sm:text-sm leading-relaxed" style="color:var(--gray-mid);">{{ $d }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     SECTION 9 — TÉMOIGNAGES (carousel)
     ══════════════════════════════════════════════════════════════ --}}
@php
$temoignages = [
    ['Aminata Diallo','Directrice Marketing, TechHub Dakar',5,'ExpoDakar a transformé la façon dont nous gérons nos participations aux salons. Interface intuitive et visibilité inégalée.'],
    ['Moussa Konaté','PDG, Import-Export Sénégal',5,'Grâce à ExpoDakar, nous avons généré nettement plus de contacts qualifiés lors du dernier salon.'],
    ['Fatou Mbodj','Organisatrice d\'événements',5,'La gestion des inscriptions et des billets QR a rendu nos conférences tellement plus fluides.'],
    ['Ibrahima Sarr','Responsable Développement, Sen Agro',4,'Une plateforme sérieuse, bien pensée pour les professionnels. Le tableau de bord exposant est un vrai plus.'],
];
@endphp
<section style="background:var(--pearl);" class="py-16 sm:py-24" aria-label="Témoignages"
    x-data="{ i:0, n:{{ count($temoignages) }}, per(){ return window.innerWidth>=768?3:1 }, max(){ return Math.max(0,this.n-this.per()) }, next(){ this.i=Math.min(this.i+1,this.max()) }, prev(){ this.i=Math.max(this.i-1,0) } }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10 sm:mb-16">
            <div>
                <p class="section-eyebrow mb-3">Ce qu'ils en disent</p>
                <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl" style="color:var(--blue-night);">Témoignages</h2>
            </div>
            <div class="flex gap-2 shrink-0">
                <button @click="prev()" class="w-10 h-10 rounded-full border flex items-center justify-center hover:bg-white transition" style="border-color:var(--gray-soft);">
                    <svg class="w-4 h-4" fill="none" stroke="var(--blue-night)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                </button>
                <button @click="next()" class="w-10 h-10 rounded-full border flex items-center justify-center hover:bg-white transition" style="border-color:var(--gray-soft);">
                    <svg class="w-4 h-4" fill="none" stroke="var(--blue-night)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                </button>
            </div>
        </div>

        <div class="overflow-hidden">
            <div class="testi-track" :style="'transform:translateX(calc(-' + i + ' * (100% / ' + per() + ')))'">
                @foreach($temoignages as $t)
                <div class="testi-slide px-2 sm:px-3">
                    <div class="rounded-2xl p-6 sm:p-8 flex flex-col gap-4 sm:gap-5 h-full" style="background:rgba(255,255,255,.75); backdrop-filter:blur(10px); border:1px solid rgba(255,255,255,.6); box-shadow:0 4px 24px rgba(10,22,40,.06);">
                        <div class="flex gap-1">@for($s=0;$s<$t[2];$s++)<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" style="color:var(--gold);"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor</div>
                        <p class="text-sm leading-relaxed flex-1 italic" style="color:var(--gray-mid);">"{{ $t[3] }}"</p>
                        <div class="flex items-center gap-3 pt-3 border-t" style="border-color:var(--gray-soft);">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-semibold text-white text-sm shrink-0" style="background:linear-gradient(135deg,var(--blue-electric),var(--blue-night));">{{ strtoupper(substr($t[0],0,1)) }}</div>
                            <div>
                                <div class="text-sm font-semibold" style="color:var(--blue-night);">{{ $t[0] }}</div>
                                <div class="text-xs" style="color:var(--gray-mid);">{{ $t[1] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     SECTION 10 — GALERIE (masonry)
     ══════════════════════════════════════════════════════════════ --}}
<section class="py-16 sm:py-24 bg-white" aria-label="Galerie">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">
        <div class="text-center mb-10 sm:mb-16">
            <p class="section-eyebrow mb-3">Nos moments forts</p>
            <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl" style="color:var(--blue-night);">ExpoDakar en images</h2>
        </div>
        <div class="masonry">
            <div class="masonry-item">
                <img src="https://www.firstevent.co.uk/wp-content/uploads/2024/09/Cardano-D1-316-1-1.jpg" alt="Salon professionnel Dakar" loading="lazy" decoding="async">
            </div>
            <div class="masonry-item">
                <img src="https://www.conferenceexpo.com/wp-content/uploads/2019/02/conference-expo-by-nimlok-gallery-d.jpg" alt="Conférence" loading="lazy" decoding="async">
            </div>
            <div class="masonry-item">
                <img src="https://elleevents.com.au/wp-content/uploads/2020/06/conference-exhibition-CLIA_Sydney2018.jpg" alt="Exposition" loading="lazy" decoding="async">
            </div>
            <div class="masonry-item relative">
                {{-- Emplacement vidéo : remplacer src par une vraie vidéo de recap (mp4/poster) --}}
                <img src="https://elleevents.com.au/wp-content/uploads/2020/06/conference-exhibitionC360-opt.jpg" alt="Vidéo récap ExpoDakar" loading="lazy" decoding="async">
                <div class="masonry-play">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background:rgba(255,255,255,.9);">
                        <svg class="w-5 h-5 ml-0.5" fill="var(--blue-night)" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </div>
                </div>
            </div>
            <div class="masonry-item">
                <img src="https://www.graynoise.com.au/wp-content/uploads/2017/11/TM20240516_0507web.jpg" alt="Networking" loading="lazy" decoding="async">
            </div>
            <div class="masonry-item">
                <img src="https://res.cloudinary.com/dstbqtuxm/image/upload/v1782659620/ChatGPT_Image_Jun_28_2026_03_00_42_PM_qkpjbj.png" alt="Événement ExpoDakar" loading="lazy" decoding="async">
            </div>
                        <div class="masonry-item">
                <img src="https://www.graynoise.com.au/wp-content/uploads/2017/11/TM20240516_0507web.jpg" alt="Networking" loading="lazy" decoding="async">
            </div>
            <div class="masonry-item">
                <img src="https://res.cloudinary.com/dstbqtuxm/image/upload/v1782659620/ChatGPT_Image_Jun_28_2026_03_00_42_PM_qkpjbj.png" alt="Événement ExpoDakar" loading="lazy" decoding="async">
            </div>
            
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     SECTION 11 — ACTUALITÉS 
     ══════════════════════════════════════════════════════════════ --}}


{{-- ══════════════════════════════════════════════════════════════
     SECTION 12 — FAQ
     ══════════════════════════════════════════════════════════════ --}}
<section id="faq" class="py-16 sm:py-24 bg-white" aria-label="FAQ">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10 sm:mb-16">
            <p class="section-eyebrow mb-3">Vous avez des questions ?</p>
            <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl mb-3" style="color:var(--blue-night);">Questions fréquentes</h2>
        </div>
        @php $faqs = [
            ['Comment réserver un événement sur ExpoDakar ?','Créez un compte gratuit, parcourez les événements et cliquez sur "Réserver". Votre billet QR est envoyé par email.'],
            ['Comment inscrire mon entreprise en tant qu\'exposant ?','Complétez votre profil entreprise dans la section "Devenir exposant". Notre équipe valide sous 48h.'],
            ['ExpoDakar est-il gratuit pour les visiteurs ?','L\'accès à la plateforme est entièrement gratuit. Certains événements peuvent avoir un tarif fixé par l\'organisateur.'],
            ['Comment promouvoir mon événement ?','Les organisateurs disposent d\'un tableau de bord avec outils de promotion, mise en avant et emailings ciblés.'],
            ['Puis-je annuler ma réservation ?','Oui, depuis votre espace personnel jusqu\'à 24h avant l\'événement.'],
            ['ExpoDakar couvre-t-il toutes les régions ?','Nous couvrons les 14 régions du Sénégal.'],
        ]; @endphp
        <div class="space-y-3 sm:space-y-4" x-data="{ open:null }">
            @foreach($faqs as $i => $faq)
            <div class="rounded-2xl border overflow-hidden" style="border-color:var(--gray-soft);">
                <button @click="open==={{ $i }}?open=null:open={{ $i }}" class="w-full flex items-center justify-between gap-3 px-4 sm:px-6 py-4 sm:py-5 text-left hover:bg-gray-50 transition">
                    <span class="font-semibold text-sm" style="color:var(--blue-night);">{{ $faq[0] }}</span>
                    <div class="shrink-0 w-6 h-6 rounded-full flex items-center justify-center transition-colors" :style="open==={{ $i }}?'background:var(--blue-electric)':'background:var(--gray-soft)'">
                        <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="open==={{ $i }}?'rotate-180':''" :style="open==={{ $i }}?'color:white':'color:var(--gray-mid)'" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                    </div>
                </button>
                <div x-show="open==={{ $i }}" x-cloak x-transition class="px-4 sm:px-6 pb-4 sm:pb-5 text-sm leading-relaxed" style="color:var(--gray-mid);">{{ $faq[1] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══ TEASER TARIFS ══ --}}
<section class="py-16 sm:py-24 bg-white" aria-label="Devenir exposant">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">
        <div class="rounded-2xl sm:rounded-3xl overflow-hidden" style="background:var(--blue-night);">
            <div class="grid lg:grid-cols-2 items-center">
                <div class="p-7 sm:p-10 lg:p-16">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-white/15 bg-white/5 mb-5 sm:mb-6">
                        <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background:var(--gold);"></span>
                        <span class="text-xs font-semibold tracking-widest uppercase" style="color:var(--gold-light);">Pour les entreprises</span>
                    </div>
                    <h2 class="font-display text-3xl sm:text-4xl text-white mb-3 sm:mb-4 leading-tight">
                        Devenez exposant sur<br><span class="text-gold-gradient">ExpoDakar</span>
                    </h2>
                    <p class="text-sm text-white/60 leading-relaxed mb-6 sm:mb-8 max-w-md">
                        Présentez votre entreprise et connectez-vous à des milliers de visiteurs professionnels. Sans engagement.
                    </p>
                    <div class="flex flex-wrap gap-3 mb-6 sm:mb-8">
                        @foreach(['Sans abonnement','4 formules disponibles','Activation immédiate'] as $point)
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0" style="background:rgba(255,255,255,.08);">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="var(--gold-light)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </div>
                            <span class="text-xs text-white/70">{{ $point }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('tarifs') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl font-semibold text-sm hover:brightness-110 transition" style="background:linear-gradient(135deg,var(--gold),var(--gold-light));color:var(--blue-night);">Voir les tarifs →</a>
                        <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl font-semibold text-sm text-white/80 border border-white/15 hover:text-white hover:bg-white/5 transition">Parler à un conseiller</a>
                    </div>
                </div>
                <div class="hidden lg:block p-10 lg:p-16" style="background:rgba(255,255,255,.03); border-left:1px solid rgba(255,255,255,.06);">
                    <div class="space-y-4">
                        @foreach([['Essentiel','75 000',''],['Professionnel · Recommandé','150 000','border: 1.5px solid var(--gold);'],['Premium','300 000','']] as $pack)
                        <div class="rounded-2xl p-5 flex items-center justify-between" style="background:rgba(255,255,255,{{ $pack[2]?'.08':'.05' }}); {{ $pack[2] }}">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide" style="color:{{ $pack[2]?'var(--gold-light)':'rgba(255,255,255,.4)' }};">{{ $pack[0] }}</p>
                                <p class="font-display text-2xl text-white mt-1">{{ $pack[1] }} <span class="text-xs font-sans font-normal" style="color:rgba(255,255,255,.4);">FCFA</span></p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     SECTION 13 — CTA FINAL
     ══════════════════════════════════════════════════════════════ --}}
<section class="cta-final py-20 sm:py-32" style="background:var(--blue-night);" aria-label="Appel à l'action final">
    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal" x-intersect.once="$el.classList.add('visible')">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-white/15 bg-white/5 backdrop-blur-md mb-6 sm:mb-8">
            <span class="w-2 h-2 rounded-full animate-pulse shrink-0" style="background:var(--gold);"></span>
            <span class="section-eyebrow text-xs" style="color:var(--gold-light);">Rejoignez la plateforme</span>
        </div>
        <h2 class="font-display text-4xl sm:text-5xl lg:text-6xl text-white mb-5 sm:mb-6 leading-[1.08]">
            Prêt à participer au<br><span class="text-gold-gradient">prochain grand événement</span> ?
        </h2>
        <p class="text-white/60 text-base sm:text-lg max-w-xl mx-auto mb-9 sm:mb-12">
            Que vous soyez visiteur, exposant ou organisateur, ExpoDakar vous connecte à l'écosystème professionnel du Sénégal.
        </p>
        <div class="flex flex-wrap justify-center gap-3 sm:gap-4">
            <a href="{{ route('user.events.index') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-full font-semibold text-sm sm:text-base transition-all hover:brightness-110 active:scale-95" style="background:linear-gradient(135deg,var(--gold),var(--gold-light));color:var(--blue-night); box-shadow:0 12px 32px rgba(201,168,76,.3);">
                Réserver ma place
            </a>
            <a href="{{ route('tarifs') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-full font-semibold text-sm sm:text-base text-white border border-white/25 transition-all hover:border-white/60 hover:bg-white/5 active:scale-95">
                Devenir exposant
            </a>
        </div>
    </div>
</section>


{{-- ══ NEWSLETTER ══ --}}
<section class="py-16 sm:py-24 overflow-hidden" style="background:var(--blue-night);" aria-label="Newsletter">
    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal" x-intersect.once="$el.classList.add('visible')">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-white/15 bg-white/5 mb-6 sm:mb-8">
            <svg class="w-4 h-4" fill="none" stroke="var(--gold-light)" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
            <span class="section-eyebrow text-xs" style="color:var(--gold-light);">Restez informé</span>
        </div>
        <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl text-white mb-3 sm:mb-4">Ne manquez aucun événement</h2>
        <p class="text-white/55 mb-8 sm:mb-10 max-w-md mx-auto text-sm sm:text-base">Recevez chaque semaine une sélection des meilleurs événements professionnels au Sénégal.</p>
        <form action="{{ route('user.newsletter.subscribe') }}" method="POST" class="flex flex-col sm:flex-row gap-3 max-w-lg mx-auto">
            @csrf
            <input type="email" name="email" placeholder="Votre adresse email professionnelle" required class="nl-input flex-1 px-4 sm:px-5 py-3.5 sm:py-4 rounded-xl bg-white/10 text-white placeholder-white/35 border border-white/15 text-sm transition">
            <button type="submit" class="px-6 sm:px-7 py-3.5 sm:py-4 rounded-xl font-semibold text-sm hover:brightness-110 whitespace-nowrap transition" style="background:linear-gradient(135deg,var(--gold),var(--gold-light));color:var(--blue-night);">S'abonner</button>
        </form>
        <p class="text-xs mt-4 sm:mt-5" style="color:rgba(255,255,255,.3);">Pas de spam. Désinscription en un clic.</p>
    </div>
</section>


{{-- ══ B1L / B1R ══ --}}
<section class="py-10 sm:py-14 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 max-w-3xl mx-auto">
            <div class="h-[160px] sm:h-[250px]">{!! $pub('b1l', 300, 250, 'Bannière B1L') !!}</div>
            <div class="h-[160px] sm:h-[250px]">{!! $pub('b1r', 300, 250, 'Bannière B1R') !!}</div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     FOOTER
     ══════════════════════════════════════════════════════════════ --}}
<footer style="background:#070f1d;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">
        <div class="py-10 sm:py-16 grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-8 sm:gap-12 border-b" style="border-color:rgba(255,255,255,.06);">
            <div class="col-span-2 sm:col-span-2 lg:col-span-1">
        <a href="{{ route('home') }}"
                class="flex items-center gap-2 sm:gap-3 shrink-0"
                aria-label="ExpoDakar">
                <img
                    src="https://res.cloudinary.com/dstbqtuxm/image/upload/v1786364683/ChatGPT_Image_10_ao%C3%BBt_2026__02_24_21-removebg-preview_spadbb.png"
                    alt="Logo ExpoDakar"
                    class="h-14 sm:h-16 w-auto object-contain"
                    width="80"
                    height="80"
                    fetchpriority="high">
                {{-- NOM EXPO DAKAR --}}
                <span
                    class="font-display text-xl sm:text-2xl font-semibold transition-colors duration-300"
                    : class="text-white">
                  <span class="font-display text-2xl text-white">Expo<span class="text-gold-gradient">Dakar</span></span>
                </span>
            </a>
                <p class="text-sm leading-relaxed mb-5" style="color:rgba(255,255,255,.4);">La plateforme de référence pour les événements professionnels au Sénégal.</p>
            <div class="flex items-center gap-3">

                @foreach([

                    {{-- FACEBOOK --}}
                    [
                        'name' => 'Facebook',
                        'url' => '#',
                        'icon' => 'M22 12.06C22 6.505 17.523 2 12 2S2 6.505 2 12.06c0 5.022 3.657 9.184 8.438 9.94v-7.03H7.898v-2.91h2.54V9.845c0-2.522 1.492-3.915 3.777-3.915 1.094 0 2.238.197 2.238.197v2.475h-1.26c-1.243 0-1.63.775-1.63 1.57v1.888h2.773l-.443 2.91h-2.33V22c4.78-.756 8.437-4.918 8.437-9.94Z'
                    ],

                    {{-- LINKEDIN --}}
                    [
                        'name' => 'LinkedIn',
                        'url' => '#',
                        'icon' => 'M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2ZM8.34 18.34H5.67V9.75h2.67v8.59ZM7 8.6a1.55 1.55 0 1 1 0-3.1 1.55 1.55 0 0 1 0 3.1Zm11.34 9.74h-2.67v-4.18c0-1-.02-2.28-1.39-2.28-1.39 0-1.6 1.08-1.6 2.2v4.26H10V9.75h2.56v1.17h.04c.36-.67 1.23-1.39 2.53-1.39 2.7 0 3.2 1.78 3.2 4.1v4.71Z'
                    ],

                    {{-- INSTAGRAM --}}
                    [
                        'name' => 'Instagram',
                        'url' => '#',
                        'icon' => 'M12 2c2.72 0 3.06.01 4.12.06 1.06.05 1.79.22 2.43.47.66.25 1.22.6 1.77 1.15.5.5.86 1.03 1.15 1.71.25.6.42 1.29.47 2.42.05 1.06.06 1.4.06 4.12s-.01 3.06-.06 4.12c-.05 1.06-.22 1.79-.47 2.43a4.7 4.7 0 0 1-1.15 1.77c-.5.5-1.03.86-1.71 1.15-.6.25-1.29.42-2.42.47-1.06.05-1.4.06-4.12.06s-3.06-.01-4.12-.06c-1.06-.05-1.79-.22-2.43-.47a4.7 4.7 0 0 1-1.77-1.15 4.7 4.7 0 0 1-1.15-1.71c-.25-.6-.42-1.29-.47-2.42C2.01 15.06 2 14.72 2 12s.01-3.06.06-4.12c.05-1.06.22-1.79.47-2.43.25-.66.6-1.22 1.15-1.77A4.7 4.7 0 0 1 5.39 2.53c.6-.25 1.29-.42 2.42-.47C8.87 2.01 9.21 2 11.93 2H12Zm0 1.8c-2.66 0-2.98.01-4.03.06-.97.05-1.5.2-1.85.34a3 3 0 0 0-1.12.73 3 3 0 0 0-.73 1.12c-.14.36-.29.88-.34 1.85-.05 1.05-.06 1.37-.06 4.03s.01 2.98.06 4.03c.05.97.2 1.5.34 1.85.16.42.37.78.73 1.12.34.36.7.57 1.12.73.36.14.88.29 1.85.34 1.05.05 1.37.06 4.03.06s2.98-.01 4.03-.06c.97-.05 1.5-.2 1.85-.34a3 3 0 0 0 1.12-.73 3 3 0 0 0 .73-1.12c.14-.36.29-.88.34-1.85.05-1.05.06-1.37.06-4.03s-.01-2.98-.06-4.03c-.05-.97-.2-1.5-.34-1.85a3 3 0 0 0-.73-1.12 3 3 0 0 0-1.12-.73c-.36-.14-.88-.29-1.85-.34-1.05-.05-1.37-.06-4.03-.06ZM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 1.8a3.2 3.2 0 1 0 0 6.4 3.2 3.2 0 0 0 0-6.4Zm5.2-2a1.17 1.17 0 1 1-2.34 0 1.17 1.17 0 0 1 2.34 0Z'
                    ],

                    {{-- TIKTOK --}}
                    [
                        'name' => 'TikTok',
                        'url' => '#',
                        'icon' => 'M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 1 1-2-2.75V9.43a6.32 6.32 0 1 0 5.45 6.27V8.26a8.16 8.16 0 0 0 4.79 1.52V6.69h-1.02Z'
                    ]

                ] as $s)

                    <a
                        href="{{ $s['url'] }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group w-10 h-10 rounded-xl
                            flex items-center justify-center
                            bg-white/10
                            border border-white/10
                            hover:bg-white
                            hover:-translate-y-1
                            transition-all duration-300"
                        aria-label="{{ $s['name'] }}">

                        <svg
                            class="w-5 h-5 text-white group-hover:text-[var(--blue-night)]
                                transition-colors duration-300"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                            aria-hidden="true">

                            <path d="{{ $s['icon'] }}"/>

                        </svg>

                    </a>

                @endforeach

            </div>
            </div>

            <div>
                <h3 class="text-xs font-semibold tracking-widest uppercase mb-4" style="color:rgba(255,255,255,.35);">Plateforme</h3>
                <ul class="space-y-2.5">
                    @foreach([['Événements',route('user.events.index')],['Catégories',route('user.categories.index')],['Tarifs',route('tarifs')]] as $l)
                    <li><a href="{{ $l[1] }}" class="text-sm transition-colors" style="color:rgba(255,255,255,.45);" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,.45)'">{{ $l[0] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h3 class="text-xs font-semibold tracking-widest uppercase mb-4" style="color:rgba(255,255,255,.35);">Infos</h3>
                <ul class="space-y-2.5">
                    @foreach([['À propos','#'],['Contact',route('contact')],['CGU','#'],['Confidentialité','#']] as $l)
                    <li><a href="{{ $l[1] }}" class="text-sm transition-colors" style="color:rgba(255,255,255,.45);" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,.45)'">{{ $l[0] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h3 class="text-xs font-semibold tracking-widest uppercase mb-4" style="color:rgba(255,255,255,.35);">Contact</h3>
                <ul class="space-y-3">
                    <li class="flex items-center gap-2 text-sm" style="color:rgba(255,255,255,.45);">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                        Dakar, Plateau
                    </li>
                    <li><a href="mailto:contact@expodakar.sn" class="text-sm" style="color:rgba(255,255,255,.45);">contact@first-media-group.com</a></li>
                    <li><a href="tel:+221338001234" class="text-sm" style="color:rgba(255,255,255,.45);"></a></li>
                </ul>
            </div>

      {{-- ══════════════════════════════════════════════════════════════
     CORRECTIF — Localisation footer : CICES / Foire de Dakar
     Deux blocs à remplacer dans le footer de index.blade.php
     ══════════════════════════════════════════════════════════════ --}}


        {{-- 2) Bloc "Nous trouver" — remplacer l'iframe --}}
        <div class="col-span-2 sm:col-span-2 lg:col-span-1">
            <h3 class="text-xs font-semibold tracking-widest uppercase mb-4" style="color:rgba(255,255,255,.35);">Nous trouver</h3>
            <div class="rounded-xl overflow-hidden border" style="border-color:rgba(255,255,255,.08); height:9rem;">
                <iframe
                    src="https://www.google.com/maps?q=CICES+Foire+de+Dakar+S%C3%A9n%C3%A9gal&output=embed"
                    class="w-full h-full  hover:opacity-100 hover:grayscale-0 transition"
                    style="border:0;" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                    title="Localisation ExpoDakar — CICES, Foire de Dakar"></iframe>
            </div>
        </div>
            </div>
        </div>

<div class="py-5 flex flex-col sm:flex-row items-center justify-center gap-4">
    <p class="text-xs" style="color:rgba(255,255,255,.25);">
        © {{ date('Y') }} ExpoDakar. Tous droits réservés.
    </p>

    <p class="text-xs" style="color:rgba(255,255,255,.2);">
        Conçu au Sénégal
    </p>
</div>
    </div>
</footer>

</div>
{{-- /2xl:mx-[160px] --}}


{{-- ══ POPUP BIENVENUE ══ --}}
<div x-data="{ show:false, init(){ if(!localStorage.getItem('welcomed')){ setTimeout(()=>this.show=true,1500); } }, close(){ this.show=false; localStorage.setItem('welcomed','1'); } }"
     x-show="show" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(10,22,40,.6); backdrop-filter:blur(6px);">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm sm:max-w-md overflow-hidden">
        <div class="relative h-32 sm:h-40 flex items-center justify-center" style="background:linear-gradient(135deg,#0A1628,#1E5FD8);">
            <div class="absolute inset-0 opacity-20" style="background-image:linear-gradient(rgba(196,168,76,.4) 1px,transparent 1px),linear-gradient(90deg,rgba(196,168,76,.4) 1px,transparent 1px); background-size:40px 40px;"></div>
            <div class="relative text-center">
                <p class="font-display text-2xl sm:text-3xl text-white font-bold">Expo<span style="color:#E8C96A;">DKR</span></p>
                <p class="text-xs text-white/60 mt-1">Plateforme événementielle du Sénégal</p>
            </div>
        </div>
        <div class="p-5 sm:p-6 text-center">
            <h3 class="flex items-center justify-center gap-2 text-base sm:text-lg font-bold mb-2" style="color:var(--blue-night);">
                Bienvenue sur ExpoDKR !
                <svg class="w-4 h-4" fill="var(--gold)" viewBox="0 0 20 20" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.286 3.957c.3.922-.755 1.688-1.539 1.118l-3.367-2.447a1 1 0 00-1.175 0l-3.367 2.447c-.784.57-1.838-.196-1.539-1.118l1.286-3.957a1 1 0 00-.363-1.118L2.063 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.285-3.958z"/></svg>
            </h3>
            <p class="text-sm mb-5 leading-relaxed" style="color:var(--gray-mid);">Découvrez les meilleurs événements professionnels au Sénégal.</p>
            <div class="flex gap-3">
                <button @click="close()" class="flex-1 py-2.5 rounded-xl text-sm font-semibold border border-gray-200 text-gray-500 hover:bg-gray-50 transition">Ignorer</button>
                <a href="{{ route('user.events.index') }}" @click="close()" class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-white hover:brightness-110 transition" style="background:linear-gradient(135deg,#1E5FD8,#1248b0);">Explorer</a>
            </div>
        </div>
    </div>
</div>


{{-- ══ POPUP NEWSLETTER ══ --}}
<div x-data="{ show:false, init(){ if(!localStorage.getItem('nl_closed')){ setTimeout(()=>this.show=true,8000); } }, close(){ this.show=false; localStorage.setItem('nl_closed','1'); } }"
     x-show="show" x-cloak x-transition class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-50 w-72 sm:w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
    <div class="px-4 sm:px-5 py-3 sm:py-4 flex items-start justify-between gap-3" style="background:linear-gradient(135deg,#0A1628,#0D2145);">
        <div class="flex items-start gap-2.5">
            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="var(--gold-light)" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
            <div>
                <p class="text-sm font-bold text-white">Restez informé</p>
                <p class="text-xs text-white/55 mt-0.5">Recevez les événements chaque semaine</p>
            </div>
        </div>
        <button @click="close()" class="text-white/40 hover:text-white transition shrink-0" aria-label="Fermer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <form action="{{ route('user.newsletter.subscribe') }}" method="POST" class="p-4 flex flex-col gap-2.5">
        @csrf
        <input type="email" name="email" placeholder="votre@email.com" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit" @click="close()" class="w-full py-2.5 rounded-xl text-sm font-semibold text-white hover:brightness-110 transition" style="background:linear-gradient(135deg,#C9A84C,#E8C96A);color:#0A1628;">S'abonner</button>
        <button type="button" @click="close()" class="text-xs text-gray-400 hover:text-gray-600 text-center transition">Non merci</button>
    </form>
</div>


{{-- ══ TOAST SUCCESS ══ --}}
@if(session('success'))
<div x-data="{ show:true }" x-show="show" x-cloak x-init="setTimeout(()=>show=false,4000)"
     x-transition class="fixed bottom-4 left-1/2 -translate-x-1/2 z-50 flex items-center gap-2 px-4 sm:px-5 py-3 sm:py-3.5 rounded-2xl shadow-xl text-sm font-semibold text-white max-w-xs sm:max-w-none text-center"
     style="background:linear-gradient(135deg,#059669,#047857);">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
    {{ session('success') }}
</div>
@endif


{{-- ══ POPUP ÉVÉNEMENT SPONSORISÉ ══ --}}
@php $featuredEvent = \App\Models\Evenement::with('exposant')->find(10); @endphp
@if($featuredEvent)
<div x-data="{
        show:false,
        init(){ if(!localStorage.getItem('featured_closed_{{ $featuredEvent->id }}')){setTimeout(()=>this.show=true,2000);} },
        close(){ this.show=false; localStorage.setItem('featured_closed_{{ $featuredEvent->id }}','1'); }
     }"
     x-show="show" x-cloak x-transition class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-50 w-72 sm:w-80">
    <div class="relative rounded-2xl overflow-hidden shadow-2xl" style="background:white; border:1px solid var(--gray-soft);">
        <div class="absolute -inset-0.5 rounded-2xl pointer-events-none" style="background:linear-gradient(135deg,var(--gold),var(--blue-electric)); opacity:.3; filter:blur(8px); animation:featured-pulse 5s ease-in-out infinite;" aria-hidden="true"></div>
        <div class="relative bg-white rounded-2xl overflow-hidden">
            <div class="flex items-center justify-between px-4 py-2" style="background:linear-gradient(135deg,var(--gold),var(--gold-light));">
                <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wide" style="color:var(--blue-night);">
                    <svg class="w-3.5 h-3.5" fill="var(--blue-night)" viewBox="0 0 20 20" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.286 3.957c.3.922-.755 1.688-1.539 1.118l-3.367-2.447a1 1 0 00-1.175 0l-3.367 2.447c-.784.57-1.838-.196-1.539-1.118l1.286-3.957a1 1 0 00-.363-1.118L2.063 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.285-3.958z"/></svg>
                    Événement sponsorisé
                </span>
                <button @click="close()" class="text-black/40 hover:text-black transition" aria-label="Fermer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="relative h-28 sm:h-32">
                @if($featuredEvent->image)
                <img src="{{ $featuredEvent->image }}" alt="{{ $featuredEvent->titre }}" class="w-full h-full object-cover" loading="lazy" decoding="async">
                @else
                <div class="w-full h-full flex items-center justify-center" style="background:linear-gradient(135deg,var(--blue-night),var(--blue-electric));">
                    <svg class="w-9 h-9 text-white/40" fill="none" stroke="currentColor" stroke-width="1.4" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                </div>
                @endif
            </div>
            <div class="p-4">
                @if($featuredEvent->exposant)
                <p class="text-xs font-semibold mb-1" style="color:var(--blue-electric);">Proposé par {{ $featuredEvent->exposant->nom }}</p>
                @endif
                <h3 class="font-semibold text-sm leading-snug mb-2" style="color:var(--blue-night);">{{ $featuredEvent->titre }}</h3>
                <div class="flex items-center gap-3 text-xs mb-3" style="color:var(--gray-mid);">
                    <span class="inline-flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                        {{ $featuredEvent->lieu }}
                    </span>
                    <span class="inline-flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                        {{ \Carbon\Carbon::parse($featuredEvent->date_debut)->translatedFormat('d M') }}
                    </span>
                </div>
                <a href="{{ route('user.events.show', $featuredEvent->id) }}" class="block w-full text-center py-2.5 rounded-xl text-sm font-semibold text-white hover:brightness-110 transition" style="background:linear-gradient(135deg,var(--blue-electric),#1248b0);">Découvrir l'événement</a>
            </div>
        </div>
    </div>
</div>
<style>@keyframes featured-pulse { 0%,100%{opacity:.2;transform:scale(1);} 50%{opacity:.4;transform:scale(1.02);} }</style>
@endif


{{-- ══════════════════════════════════════════════════════════════
     SCRIPTS — Lenis (smooth scroll) + GSAP ScrollTrigger (reveals)
     ══════════════════════════════════════════════════════════════ --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ---- Lenis smooth scroll ---- */
    let lenis;
    if (!reduceMotion && window.Lenis) {
        lenis = new Lenis({ duration: 1.1, easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)), smoothWheel: true });
        function raf(time){ lenis.raf(time); requestAnimationFrame(raf); }
        requestAnimationFrame(raf);
        if (window.gsap && window.ScrollTrigger) {
            lenis.on('scroll', ScrollTrigger.update);
            gsap.ticker.add((time) => { lenis.raf(time * 1000); });
            gsap.ticker.lagSmoothing(0);
        }
    }

    /* ---- Reveals : GSAP ScrollTrigger si dispo, sinon IntersectionObserver ---- */
    const els = document.querySelectorAll('.reveal');
    if (window.gsap && window.ScrollTrigger) {
        gsap.registerPlugin(ScrollTrigger);
        els.forEach((el) => {
            ScrollTrigger.create({
                trigger: el, start: 'top 88%', once: true,
                onEnter: () => el.classList.add('visible')
            });
        });
    } else if ('IntersectionObserver' in window) {
        const io = new IntersectionObserver(entries => {
            entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); } });
        }, { threshold: 0.1 });
        els.forEach(el => io.observe(el));
    } else {
        els.forEach(el => el.classList.add('visible'));
    }
});

/* ---- CountUp Hero ---- */
(function(){
    "use strict";
    var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    var counters = document.querySelectorAll("#hero .hero-counter[data-count]");
    if (counters.length){
        var cio = new IntersectionObserver(function(entries){
            entries.forEach(function(entry){
                if (!entry.isIntersecting) return;
                cio.unobserve(entry.target);
                var el = entry.target;
                var raw = el.getAttribute("data-count") || "0";
                var m = raw.match(/^([^\d]*)(\d+)([^\d]*)$/);
                if (!m){ el.textContent = raw; return; }
                var prefix = m[1], target = parseInt(m[2], 10), suffix = m[3];
                if (reduceMotion){ el.textContent = prefix + target + suffix; return; }
                var dur = 1400, start = null;
                function step(ts){
                    if (!start) start = ts;
                    var p = Math.min((ts - start) / dur, 1);
                    var eased = 1 - Math.pow(1 - p, 3);
                    el.textContent = prefix + Math.round(target * eased) + suffix;
                    if (p < 1) requestAnimationFrame(step);
                }
                requestAnimationFrame(step);
            });
        }, { threshold:.4 });
        counters.forEach(function(el){ cio.observe(el); });
    }

    if (!reduceMotion && window.matchMedia("(pointer:fine)").matches){
        var scene = document.querySelector("#hero .hero-scene");
        var heroEl = document.getElementById("hero");
        if (scene && heroEl){
            var tx = 0, ty = 0, cx = 0, cy = 0;
            heroEl.addEventListener("mousemove", function(e){
                var r = heroEl.getBoundingClientRect();
                tx = ((e.clientX - r.left) / r.width - .5) * 2;
                ty = ((e.clientY - r.top) / r.height - .5) * 2;
            }, { passive:true });
            (function raf(){
                cx += (tx - cx) * .05; cy += (ty - cy) * .05;
                scene.style.transform = "rotateY(" + (cx * 4) + "deg) rotateX(" + (cy * -4) + "deg) translate3d(" + (cx * -10) + "px," + (cy * -10) + "px,0)";
                requestAnimationFrame(raf);
            })();
        }
    }
})();
</script>

</body>
</html>
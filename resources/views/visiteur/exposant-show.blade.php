{{--
|--------------------------------------------------------------------------
| ExpoDakar – Page Détail Exposant PREMIUM (standalone)
| Route : exposants.show  |  Variable : $exposant
| Relations attendues : $exposant->evenements, $exposant->categorie,
|                        $exposant->galerie, $exposant->temoignages,
|                        $exposant->partenaires (facultatif, fallback fourni)
| Laravel 12 • Blade • Tailwind CSS CDN • Alpine.js 3 • GSAP • AOS • Lenis
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
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@studio-freight/lenis@1.0.42/dist/lenis.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

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
            --ink:           #374151;
        }

        html { scroll-behavior: auto; }
        body {
            font-family: 'Inter', sans-serif;
            background: #fff;
            color: var(--blue-night);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .font-display { font-family: 'Instrument Serif', serif; }
        .font-mono    { font-family: 'JetBrains Mono', monospace; }
        [x-cloak]     { display: none !important; }

        .text-gold-gradient {
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }

        .navbar-transparent { background: transparent; }
        .navbar-solid { background: rgba(10,22,40,.85); backdrop-filter: blur(16px); box-shadow: 0 2px 24px rgba(10,22,40,.18); }

        .reveal { opacity: 0; transform: translateY(24px); transition: opacity .7s cubic-bezier(.16,1,.3,1), transform .7s cubic-bezier(.16,1,.3,1); }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .reveal-d1 { transition-delay: .08s; } .reveal-d2 { transition-delay: .16s; } .reveal-d3 { transition-delay: .24s; }

        .card-lift { transition: transform .32s cubic-bezier(.16,1,.3,1), box-shadow .32s ease; }
        .card-lift:hover { transform: translateY(-6px); box-shadow: 0 24px 60px rgba(10,22,40,.14), 0 0 0 1px rgba(30,95,216,.08); }

        .event-img-wrap { overflow: hidden; }
        .event-img-wrap img { transition: transform .5s cubic-bezier(.16,1,.3,1); }
        .event-card:hover .event-img-wrap img { transform: scale(1.08); }
        .event-card:hover { box-shadow: 0 24px 60px rgba(30,95,216,.18); }

        .eyebrow { font-size: .7rem; font-weight: 600; letter-spacing: .2em; text-transform: uppercase; color: var(--gold); }
        .eyebrow-light { font-size: .7rem; font-weight: 600; letter-spacing: .2em; text-transform: uppercase; color: rgba(255,255,255,.5); }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--pearl); }
        ::-webkit-scrollbar-thumb { background: var(--blue-electric); border-radius: 99px; }
        *:focus-visible { outline: 2px solid var(--blue-electric); outline-offset: 3px; border-radius: 6px; }

        /* ── Hero atmosphere ─────────────────────────────────── */
        .hero-noise {
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.4'/%3E%3C/svg%3E");
            opacity: .05; mix-blend-mode: overlay;
        }
        .particle { position: absolute; border-radius: 50%; background: var(--gold-light); pointer-events: none; }

        /* ── Accreditation-badge signature (exhibitor pass motif) ── */
        .badge-pass {
            position: relative;
            background: linear-gradient(160deg, #FFFFFF 0%, #F7F8FC 100%);
            border-radius: 20px;
            box-shadow: 0 24px 60px rgba(10,22,40,.16), 0 0 0 1px rgba(10,22,40,.04);
        }
        .badge-pass::before {
            content: ''; position: absolute; left: -9px; top: 50%; transform: translateY(-50%);
            width: 18px; height: 18px; border-radius: 50%; background: #fff;
            box-shadow: inset -2px 0 4px rgba(10,22,40,.08);
        }
        .badge-pass::after {
            content: ''; position: absolute; right: -9px; top: 50%; transform: translateY(-50%);
            width: 18px; height: 18px; border-radius: 50%; background: #fff;
            box-shadow: inset 2px 0 4px rgba(10,22,40,.08);
        }
        .badge-perf {
            border-top: 2px dashed var(--gray-soft);
        }
        .stamp-ring {
            border: 1.5px dashed rgba(201,168,76,.55);
        }

        /* ── Magnetic buttons ─────────────────────────────────── */
        .magnetic { will-change: transform; }

        /* ── Masonry gallery ──────────────────────────────────── */
        .masonry { column-count: 1; column-gap: 1rem; }
        @media (min-width: 640px)  { .masonry { column-count: 2; } }
        @media (min-width: 1024px) { .masonry { column-count: 3; } }
        .masonry-item { break-inside: avoid; margin-bottom: 1rem; }
        .gallery-overlay { opacity: 0; transition: opacity .35s ease; background: linear-gradient(to top, rgba(10,22,40,.75), transparent 60%); }
        .gallery-tile:hover .gallery-overlay { opacity: 1; }
        .gallery-tile img { transition: transform .55s cubic-bezier(.16,1,.3,1); }
        .gallery-tile:hover img { transform: scale(1.07); }

        /* ── Stat cards ───────────────────────────────────────── */
        .stat-card { background: #fff; border: 1px solid var(--gray-soft); transition: all .3s ease; }
        .stat-card:hover { border-color: rgba(30,95,216,.25); box-shadow: 0 16px 40px rgba(10,22,40,.08); transform: translateY(-4px); }

        /* ── Accordion ────────────────────────────────────────── */
        .accordion-chevron { transition: transform .35s cubic-bezier(.16,1,.3,1); }

        /* ── Partner logos ────────────────────────────────────── */
        .partner-logo { filter: grayscale(1) opacity(.55); transition: filter .4s ease, transform .4s ease; }
        .partner-logo:hover { filter: grayscale(0) opacity(1); transform: scale(1.05); }

        /* ── Glow hover on buttons ───────────────────────────── */
        .glow-hover { position: relative; }
        .glow-hover::after {
            content: ''; position: absolute; inset: -2px; border-radius: inherit;
            background: linear-gradient(135deg, var(--blue-electric), var(--gold));
            opacity: 0; filter: blur(14px); transition: opacity .35s ease; z-index: -1;
        }
        .glow-hover:hover::after { opacity: .5; }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: .001ms !important; transition-duration: .001ms !important; scroll-behavior: auto !important; }
        }
    </style>
</head>
<body x-data="{ lightboxOpen: false, lightboxSrc: '', lightboxAlt: '' }">

{{-- ══════════════════════════════════════════════════════════════
     NAVBAR
     ══════════════════════════════════════════════════════════════ --}}
<header
    x-data="{ open: false, scrolled: false, init() { window.addEventListener('scroll', () => { this.scrolled = window.scrollY > 60; }, { passive: true }); } }"
    :class="scrolled ? 'navbar-solid' : 'navbar-transparent'"
    class="fixed inset-x-0 top-0 z-50 transition-all duration-300"
    role="banner"
>
    <div class="max-w-7xl mx-auto px-6 lg:px-16">
        <div class="flex items-center justify-between h-20">
            <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="ExpoDakar – Accueil">
                <img src="https://res.cloudinary.com/dstbqtuxm/image/upload/v1786364683/ChatGPT_Image_10_ao%C3%BBt_2026__02_24_21-removebg-preview_spadbb.png"
                     alt="Logo ExpoDakar" class="h-10 w-auto object-contain">
                <span class="font-display text-2xl text-white">Expo<span class="text-gold-gradient">Dakar</span></span>
            </a>
            <nav class="hidden lg:flex items-center gap-8" aria-label="Navigation principale">
                <a href="{{ route('events.index') }}"    class="text-sm font-medium text-white/80 hover:text-white transition-colors">Événements</a>
                <a href="{{ route('exposants.index') }}" class="text-sm font-medium text-white transition-colors border-b border-white/30 pb-0.5">Exposants</a>
                <a href="/#categories" class="text-sm font-medium text-white/80 hover:text-white transition-colors">Catégories</a>
                <a href="/#faq" class="text-sm font-medium text-white/80 hover:text-white transition-colors">FAQ</a>
            </nav>
            <div class="hidden lg:flex items-center gap-3">
                @guest
                    <a href="{{ route('login') }}" class="text-sm font-medium text-white/80 hover:text-white px-4 py-2 rounded-lg hover:bg-white/10 transition-colors">Connexion</a>
                    <a href="{{ route('register') }}" class="text-sm font-semibold text-white px-5 py-2.5 rounded-xl transition-all" style="background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: var(--blue-night);">S'inscrire</a>
                @endguest
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-white px-5 py-2.5 rounded-xl" style="background: linear-gradient(135deg, var(--blue-electric), #1248b0);">Mon espace</a>
                @endauth
            </div>
            <button @click="open = !open" class="lg:hidden flex items-center justify-center w-10 h-10 rounded-lg text-white hover:bg-white/10 transition" :aria-expanded="open" aria-label="Menu">
                <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="open" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>
    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="lg:hidden border-t border-white/10" style="background: var(--blue-night);">
        <nav class="flex flex-col gap-1 px-6 py-4">
            <a href="{{ route('events.index') }}"    @click="open=false" class="px-4 py-3 text-sm font-medium text-white/80 hover:text-white rounded-lg hover:bg-white/10 transition">Événements</a>
            <a href="{{ route('exposants.index') }}" @click="open=false" class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-white/10">Exposants</a>
            <a href="/#categories" @click="open=false" class="px-4 py-3 text-sm font-medium text-white/80 hover:text-white rounded-lg hover:bg-white/10 transition">Catégories</a>
            <a href="/#faq" @click="open=false" class="px-4 py-3 text-sm font-medium text-white/80 hover:text-white rounded-lg hover:bg-white/10 transition">FAQ</a>
            <hr class="border-white/10 my-2">
            @guest
                <a href="{{ route('login') }}" class="px-4 py-3 text-sm font-medium text-white/80 rounded-lg hover:bg-white/10 transition">Connexion</a>
                <a href="{{ route('register') }}" class="mt-1 px-4 py-3 text-sm font-semibold text-center rounded-xl" style="background:linear-gradient(135deg,var(--gold),var(--gold-light));color:var(--blue-night);">S'inscrire</a>
            @endguest
            @auth
                <a href="{{ route('dashboard') }}" class="px-4 py-3 text-sm font-semibold text-center text-white rounded-xl" style="background:linear-gradient(135deg,var(--blue-electric),#1248b0);">Mon espace</a>
            @endauth
        </nav>
    </div>
</header>


{{-- ══════════════════════════════════════════════════════════════
     HERO — occupe ~65vh
     ══════════════════════════════════════════════════════════════ --}}
<section id="hero" class="relative min-h-[65vh] flex flex-col justify-end overflow-hidden" style="background: var(--blue-night);" aria-label="Profil exposant">

    <div class="absolute inset-0 z-0 hero-noise" aria-hidden="true"></div>
    <div class="absolute inset-0 z-0" aria-hidden="true">
        <div class="absolute inset-0 opacity-[.12]" style="background-image: linear-gradient(rgba(196,168,76,.4) 1px,transparent 1px), linear-gradient(90deg,rgba(196,168,76,.4) 1px,transparent 1px); background-size: 64px 64px;"></div>
        <div id="glowBlue" class="absolute -bottom-32 -right-32 w-[560px] h-[560px] rounded-full opacity-20" style="background: var(--blue-electric); filter: blur(130px);"></div>
        <div id="glowGold" class="absolute top-10 -left-24 w-[380px] h-[380px] rounded-full opacity-[.14]" style="background: var(--gold); filter: blur(110px);"></div>
        <div class="particle" style="width:4px;height:4px;top:22%;left:68%;opacity:.5;"></div>
        <div class="particle" style="width:3px;height:3px;top:38%;left:82%;opacity:.35;"></div>
        <div class="particle" style="width:5px;height:5px;top:60%;left:74%;opacity:.4;"></div>
        <div class="particle" style="width:2px;height:2px;top:15%;left:40%;opacity:.3;"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-16 pt-40 pb-16 w-full">

        <nav id="heroBreadcrumb" class="flex items-center gap-2 mb-8 text-xs text-white/45" aria-label="Fil d'Ariane" style="opacity:0;">
            <a href="{{ route('home') }}" class="hover:text-white/80 transition-colors">Accueil</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            <a href="{{ route('exposants.index') }}" class="hover:text-white/80 transition-colors">Exposants</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            <span class="text-white/70">{{ Str::limit($exposant->nom, 30) }}</span>
        </nav>

        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-10">

            {{-- Bloc gauche : identité --}}
            <div class="flex flex-col sm:flex-row sm:items-end gap-7">
                <div id="heroLogo" class="flex-shrink-0" style="opacity:0;">
                    @if($exposant->logo)
                        <div class="w-28 h-28 lg:w-36 lg:h-36 rounded-3xl overflow-hidden border-4 border-white/15 shadow-2xl" style="background: white;">
                            <img src="{{ $exposant->logo }}" alt="Logo {{ $exposant->nom }}" class="w-full h-full object-contain p-3">
                        </div>
                    @else
                        <div class="w-28 h-28 lg:w-36 lg:h-36 rounded-3xl flex items-center justify-center border-4 border-white/15 shadow-2xl" style="background: linear-gradient(135deg, var(--blue-electric), var(--blue-deep));">
                            <span class="font-display text-5xl lg:text-6xl text-white">{{ strtoupper(substr($exposant->nom, 0, 1)) }}</span>
                        </div>
                    @endif
                </div>

                <div id="heroText" class="min-w-0" style="opacity:0;">
                    @if($exposant->secteur ?? $exposant->secteur_activite ?? null)
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-white/15 bg-white/5 backdrop-blur-sm mb-4">
                        <span class="w-1.5 h-1.5 rounded-full" style="background: var(--gold);"></span>
                        <span class="text-xs font-semibold tracking-widest uppercase" style="color: var(--gold-light);">{{ $exposant->secteur ?? $exposant->secteur_activite }}</span>
                    </div>
                    @endif

                    <h1 class="font-display text-5xl lg:text-7xl text-white leading-[0.95] mb-5">{{ $exposant->nom ?? $exposant->nom_entreprise }}</h1>

                    <div class="flex flex-wrap items-center gap-5">
                        @if($exposant->responsable)
                        <div class="flex items-center gap-2 text-white/65 text-sm">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                            {{ $exposant->responsable }}
                        </div>
                        @endif
                        @if(isset($exposant->evenements) && $exposant->evenements->count())
                        <div class="flex items-center gap-2 text-white/65 text-sm">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5"/></svg>
                            {{ $exposant->evenements->count() }} événement{{ $exposant->evenements->count() > 1 ? 's' : '' }}
                        </div>
                        @endif
                        @if($exposant->site_web)
                        <a href="{{ $exposant->site_web }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 text-sm font-medium transition-colors" style="color: var(--gold-light);" onmouseover="this.style.color='white'" onmouseout="this.style.color='var(--gold-light)'">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/></svg>
                            {{ parse_url($exposant->site_web, PHP_URL_HOST) ?? $exposant->site_web }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Bloc droit : actions premium --}}
            <div id="heroActions" x-data="{ favorited: false, copied: false }" class="flex flex-wrap gap-3 flex-shrink-0" style="opacity:0;">
                @if($exposant->site_web)
                <a href="{{ $exposant->site_web }}" target="_blank" rel="noopener noreferrer" class="magnetic glow-hover inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-semibold transition-all" style="background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: var(--blue-night);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3"/></svg>
                    Visiter le site
                </a>
                @endif
                @if($exposant->email)
                <a href="mailto:{{ $exposant->email }}" class="magnetic inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-semibold border border-white/20 text-white transition-all hover:bg-white/10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                    Contacter
                </a>
                @endif
                <button @click="navigator.share ? navigator.share({title: '{{ $exposant->nom }}', url: window.location.href}) : (navigator.clipboard.writeText(window.location.href), copied = true, setTimeout(() => copied = false, 2000))"
                        class="magnetic inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-semibold border border-white/20 text-white transition-all hover:bg-white/10" aria-label="Partager ce profil">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z"/></svg>
                    <span x-text="copied ? 'Lien copié' : 'Partager'"></span>
                </button>
                <button @click="favorited = !favorited" class="magnetic inline-flex items-center justify-center w-[46px] h-[46px] rounded-xl border border-white/20 text-white transition-all hover:bg-white/10" :aria-pressed="favorited" aria-label="Ajouter aux favoris">
                    <svg class="w-5 h-5 transition-all" :fill="favorited ? 'var(--gold-light)' : 'none'" :stroke="favorited ? 'var(--gold-light)' : 'currentColor'" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 21.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/></svg>
                </button>
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     LAYOUT PRINCIPAL
     ══════════════════════════════════════════════════════════════ --}}
<div class="max-w-7xl mx-auto px-6 lg:px-16 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        <div class="lg:col-span-2 flex flex-col gap-16">

            {{-- ══════════════ À propos ══════════════ --}}
            @if($exposant->description)
            <section data-aos="fade-up" aria-label="Description">
                <div class="flex items-start gap-4 mb-6">
                    <div class="flex-shrink-0 w-11 h-11 rounded-xl flex items-center justify-center" style="background: var(--pearl);">
                        <svg class="w-5 h-5" fill="none" stroke="var(--blue-electric)" stroke-width="1.6" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                    </div>
                    <div>
                        <p class="eyebrow mb-1">Présentation</p>
                        <h2 class="font-display text-3xl lg:text-4xl" style="color: var(--blue-night);">À propos</h2>
                    </div>
                </div>
                <div class="text-base leading-relaxed" style="color: var(--ink); max-width: 68ch;">
                    {!! nl2br(e($exposant->description)) !!}
                </div>
            </section>
            @endif

            {{-- ══════════════ Chiffres clés ══════════════ --}}
            @php
                $anneesExperience = $exposant->annees_experience ?? (isset($exposant->created_at) ? max(1, now()->diffInYears($exposant->created_at)) : 3);
                $nbEvenements     = optional($exposant->evenements ?? null)->count() ?? 0;
                $nbVisiteurs      = $exposant->nb_visiteurs ?? 12500;
                $nbPartenaires    = optional($exposant->partenaires ?? null)->count() ?? 8;
                $satisfaction     = $exposant->taux_satisfaction ?? 96;
            @endphp
            <section aria-label="Chiffres clés">
                <div class="flex items-center gap-3 mb-8" data-aos="fade-up">
                    <div class="w-1 h-8 rounded-full" style="background: linear-gradient(to bottom, var(--gold), var(--gold-light));" aria-hidden="true"></div>
                    <h2 class="font-display text-2xl lg:text-3xl" style="color: var(--blue-night);">En chiffres</h2>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
                    @foreach([
                        ['val' => $anneesExperience, 'suffix' => '+', 'label' => "Années d'expérience"],
                        ['val' => $nbEvenements,     'suffix' => '',  'label' => 'Événements organisés'],
                        ['val' => $nbVisiteurs,      'suffix' => '+', 'label' => 'Visiteurs accueillis'],
                        ['val' => $nbPartenaires,    'suffix' => '+', 'label' => 'Partenaires'],
                        ['val' => $satisfaction,     'suffix' => '%', 'label' => 'Taux de satisfaction'],
                    ] as $i => $stat)
                    <div class="stat-card rounded-2xl p-5 text-center" data-aos="fade-up" data-aos-delay="{{ $i * 80 }}">
                        <p class="font-display text-3xl lg:text-4xl mb-1 stat-counter" data-target="{{ $stat['val'] }}" style="color: var(--blue-night);">0<span style="color: var(--gold);">{{ $stat['suffix'] }}</span></p>
                        <p class="text-xs font-medium leading-snug" style="color: var(--gray-mid);">{{ $stat['label'] }}</p>
                    </div>
                    @endforeach
                </div>
            </section>

            {{-- ══════════════ Galerie Masonry ══════════════ --}}
            @if(isset($exposant->galerie) && $exposant->galerie->count())
            <section aria-label="Galerie photos">
                <div class="flex items-center gap-3 mb-8" data-aos="fade-up">
                    <div class="w-1 h-8 rounded-full" style="background: linear-gradient(to bottom, var(--blue-electric), var(--blue-night));" aria-hidden="true"></div>
                    <h2 class="font-display text-2xl lg:text-3xl" style="color: var(--blue-night);">Galerie</h2>
                </div>
                <div class="masonry">
                    @foreach($exposant->galerie as $i => $photo)
                    <div class="masonry-item">
                        <button type="button"
                                @click="lightboxOpen = true; lightboxSrc = '{{ $photo->url }}'; lightboxAlt = 'Photo {{ $exposant->nom }} {{ $i + 1 }}'"
                                class="gallery-tile relative block w-full rounded-2xl overflow-hidden group"
                                data-aos="fade-up" data-aos-delay="{{ ($i % 6) * 60 }}"
                                aria-label="Agrandir la photo {{ $i + 1 }}">
                            <img src="{{ $photo->url }}" alt="Photo {{ $exposant->nom }} {{ $i + 1 }}" class="w-full h-auto object-cover" loading="lazy">
                            <div class="gallery-overlay absolute inset-0 flex items-end p-4" aria-hidden="true">
                                <span class="inline-flex items-center gap-1.5 text-white text-xs font-semibold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607zM10.5 7.5v6m3-3h-6"/></svg>
                                    Agrandir
                                </span>
                            </div>
                        </button>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif

            {{-- ══════════════ Événements organisés ══════════════ --}}
            @if(isset($exposant->evenements) && $exposant->evenements->count())
            <section aria-label="Événements organisés">
                <div class="flex items-center justify-between mb-8" data-aos="fade-up">
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-8 rounded-full" style="background: linear-gradient(to bottom, var(--blue-electric), var(--blue-night));" aria-hidden="true"></div>
                        <h2 class="font-display text-2xl lg:text-3xl" style="color: var(--blue-night);">Événements organisés</h2>
                    </div>
                    <span class="text-xs font-semibold px-3 py-1 rounded-full" style="background: rgba(30,95,216,.08); color: var(--blue-electric);">{{ $exposant->evenements->count() }} au total</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach($exposant->evenements as $idx => $evenement)
                    <article class="event-card card-lift bg-white rounded-2xl overflow-hidden border" style="border-color: var(--gray-soft); box-shadow: 0 4px 20px rgba(10,22,40,.06);" aria-label="{{ $evenement->titre }}" data-aos="fade-up" data-aos-delay="{{ ($idx % 2) * 90 }}">
                        <div class="event-img-wrap relative h-44">
                            @if($evenement->image)
                                <img src="{{ $evenement->image }}" alt="{{ $evenement->titre }}" class="w-full h-full object-cover" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, var(--blue-night), var(--blue-electric));">
                                    <svg class="w-10 h-10 text-white/25" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Z"/></svg>
                                </div>
                            @endif
                            @if($evenement->categorie)
                            <span class="absolute top-3 left-3 px-2.5 py-1 text-xs font-semibold rounded-full backdrop-blur-sm" style="background: rgba(10,22,40,.72); color: var(--gold-light);">{{ $evenement->categorie->nom }}</span>
                            @endif
                            <div class="absolute top-3 right-3 flex flex-col items-center justify-center w-11 h-11 rounded-xl bg-white shadow-md">
                                <span class="text-xs font-bold leading-none" style="color: var(--blue-electric);">{{ \Carbon\Carbon::parse($evenement->date_debut)->format('d') }}</span>
                                <span class="text-xs uppercase leading-none mt-0.5" style="color: var(--gray-mid);">{{ \Carbon\Carbon::parse($evenement->date_debut)->translatedFormat('M') }}</span>
                            </div>
                            @php
                                $now = now(); $debut = \Carbon\Carbon::parse($evenement->date_debut); $fin = \Carbon\Carbon::parse($evenement->date_fin);
                                if ($now->lt($debut))                { $sl = 'À venir';  $sc = '#10B981'; $sb = '#ECFDF5'; }
                                elseif ($now->between($debut, $fin)) { $sl = 'En cours'; $sc = '#C2410C'; $sb = '#FFF7ED'; }
                                else                                  { $sl = 'Terminé';  $sc = '#9CA3AF'; $sb = '#F3F4F6'; }
                            @endphp
                            <span class="absolute bottom-3 left-3 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold" style="background: {{ $sb }}; color: {{ $sc }};">
                                <span class="w-1.5 h-1.5 rounded-full" style="background: {{ $sc }};"></span>{{ $sl }}
                            </span>
                        </div>
                        <div class="p-5">
                            <h3 class="font-semibold text-sm leading-snug mb-3 line-clamp-2" style="color: var(--blue-night);">{{ $evenement->titre }}</h3>
                            <div class="flex items-center gap-4 text-xs mb-4" style="color: var(--gray-mid);">
                                <span class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>{{ $evenement->lieu }}</span>
                                <span class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>{{ \Carbon\Carbon::parse($evenement->date_debut)->diffInDays($evenement->date_fin) + 1 }}j</span>
                            </div>
                            <a href="{{ route('events.show', $evenement->id) }}" class="block w-full text-center py-2.5 rounded-xl text-xs font-semibold transition-all hover:brightness-110 active:scale-95" style="background: linear-gradient(135deg, var(--blue-electric), #1248b0); color: white;">Voir l'événement</a>
                        </div>
                    </article>
                    @endforeach
                </div>
            </section>
            @endif

            {{-- ══════════════ Témoignages ══════════════ --}}
            @php
                $temoignages = $exposant->temoignages ?? collect([
                    (object)['nom' => 'Awa Diallo',   'role' => 'Responsable achats, SenCom',      'note' => 5, 'texte' => "Un accompagnement rigoureux du premier échange jusqu'au montage du stand. La qualité de présentation était irréprochable.", 'photo' => null],
                    (object)['nom' => 'Moussa Fall',  'role' => 'Directeur, Fall Industries',       'note' => 5, 'texte' => "Une équipe réactive et un réel sens du détail. Nos objectifs de visibilité ont été largement atteints.", 'photo' => null],
                    (object)['nom' => 'Fatou Sarr',   'role' => 'Chargée de partenariats, ODK Group','note' => 4, 'texte' => "Professionnalisme et clarté dans les échanges. Nous reconduirons la collaboration sans hésiter.", 'photo' => null],
                ]);
                $noteMoyenne = round(collect($temoignages)->avg('note'), 1);
            @endphp
            <section aria-label="Témoignages" x-data="{ active: 0, count: {{ count($temoignages) }} }">
                <div class="flex items-center justify-between mb-8" data-aos="fade-up">
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-8 rounded-full" style="background: linear-gradient(to bottom, var(--gold), var(--gold-light));" aria-hidden="true"></div>
                        <h2 class="font-display text-2xl lg:text-3xl" style="color: var(--blue-night);">Témoignages</h2>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-display text-2xl" style="color: var(--blue-night);">{{ $noteMoyenne }}</span>
                        <div class="flex gap-0.5" aria-hidden="true">
                            @for($s = 1; $s <= 5; $s++)
                                <svg class="w-4 h-4" fill="{{ $s <= round($noteMoyenne) ? 'var(--gold)' : '#E5E7EB' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.286 3.957c.3.922-.755 1.688-1.539 1.118l-3.367-2.447a1 1 0 00-1.175 0l-3.367 2.447c-.784.57-1.838-.196-1.539-1.118l1.286-3.957a1 1 0 00-.363-1.118L2.063 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.285-3.958z"/></svg>
                            @endfor
                        </div>
                    </div>
                </div>

                <div class="relative overflow-hidden" data-aos="fade-up">
                    <div class="flex transition-transform duration-500 ease-out" :style="`transform: translateX(-${active * 100}%)`">
                        @foreach($temoignages as $t)
                        <div class="w-full flex-shrink-0 px-1">
                            <div class="rounded-2xl p-8 border" style="border-color: var(--gray-soft); background: var(--pearl);">
                                <div class="flex gap-0.5 mb-4" aria-hidden="true">
                                    @for($s = 1; $s <= 5; $s++)
                                        <svg class="w-4 h-4" fill="{{ $s <= $t->note ? 'var(--gold)' : '#E5E7EB' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.286 3.957c.3.922-.755 1.688-1.539 1.118l-3.367-2.447a1 1 0 00-1.175 0l-3.367 2.447c-.784.57-1.838-.196-1.539-1.118l1.286-3.957a1 1 0 00-.363-1.118L2.063 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.285-3.958z"/></svg>
                                    @endfor
                                </div>
                                <p class="text-base leading-relaxed mb-6" style="color: var(--ink);">« {{ $t->texte }} »</p>
                                <div class="flex items-center gap-3">
                                    @if($t->photo)
                                        <img src="{{ $t->photo }}" alt="{{ $t->nom }}" class="w-11 h-11 rounded-full object-cover">
                                    @else
                                        <div class="w-11 h-11 rounded-full flex items-center justify-center text-white font-semibold text-sm" style="background: linear-gradient(135deg, var(--blue-electric), var(--blue-night));">{{ strtoupper(substr($t->nom, 0, 1)) }}</div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-semibold" style="color: var(--blue-night);">{{ $t->nom }}</p>
                                        <p class="text-xs" style="color: var(--gray-mid);">{{ $t->role }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="flex items-center justify-center gap-2 mt-6">
                        <template x-for="i in count" :key="i">
                            <button @click="active = i - 1" class="h-1.5 rounded-full transition-all" :class="active === i - 1 ? 'w-6' : 'w-1.5'" :style="active === i - 1 ? 'background: var(--gold)' : 'background: var(--gray-soft)'" :aria-label="`Témoignage ${i}`"></button>
                        </template>
                    </div>
                </div>
            </section>

        </div>
        {{-- /COLONNE PRINCIPALE --}}


        {{-- ────────────────────────────────────────────────────
             SIDEBAR (droite × 1)
             ──────────────────────────────────────────────────── --}}
        <aside class="flex flex-col gap-6" aria-label="Informations de contact">
            <div style="position: sticky; top: 6rem;">

                {{-- Card : Coordonnées --}}
                <div class="badge-pass overflow-hidden mb-5">
                    <div class="px-6 py-5" style="background: linear-gradient(135deg, var(--blue-night), var(--blue-deep));">
                        <p class="eyebrow-light mb-1 font-mono">Badge exposant</p>
                        <h3 class="font-display text-lg text-white leading-snug">{{ $exposant->nom ?? $exposant->nom_entreprise }}</h3>
                    </div>

                    <div class="p-6 flex flex-col gap-4">
                        @if($exposant->responsable)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background: var(--pearl);">
                                <svg class="w-4 h-4" fill="none" stroke="var(--blue-electric)" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                            </div>
                            <div><p class="text-xs font-medium mb-0.5" style="color: var(--gray-mid);">Responsable</p><p class="text-sm font-semibold" style="color: var(--blue-night);">{{ $exposant->responsable }}</p></div>
                        </div>
                        <hr style="border-color: var(--gray-soft);">
                        @endif

                        @if($exposant->telephone)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background: var(--pearl);">
                                <svg class="w-4 h-4" fill="none" stroke="var(--blue-electric)" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 6z"/></svg>
                            </div>
                            <div><p class="text-xs font-medium mb-0.5" style="color: var(--gray-mid);">Téléphone</p><a href="tel:{{ $exposant->telephone }}" class="text-sm font-semibold transition-colors" style="color: var(--blue-night);" onmouseover="this.style.color='var(--blue-electric)'" onmouseout="this.style.color='var(--blue-night)'">{{ $exposant->telephone }}</a></div>
                        </div>
                        <hr style="border-color: var(--gray-soft);">
                        @endif

                        @if($exposant->email)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background: var(--pearl);">
                                <svg class="w-4 h-4" fill="none" stroke="var(--blue-electric)" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                            </div>
                            <div class="min-w-0"><p class="text-xs font-medium mb-0.5" style="color: var(--gray-mid);">Email</p><a href="mailto:{{ $exposant->email }}" class="text-sm font-semibold transition-colors truncate block" style="color: var(--blue-night);" onmouseover="this.style.color='var(--blue-electric)'" onmouseout="this.style.color='var(--blue-night)'">{{ $exposant->email }}</a></div>
                        </div>
                        @endif

                        @if($exposant->site_web)
                        <hr style="border-color: var(--gray-soft);">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background: var(--pearl);">
                                <svg class="w-4 h-4" fill="none" stroke="var(--blue-electric)" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3"/></svg>
                            </div>
                            <div class="min-w-0"><p class="text-xs font-medium mb-0.5" style="color: var(--gray-mid);">Site web</p><a href="{{ $exposant->site_web }}" target="_blank" rel="noopener noreferrer" class="text-sm font-semibold transition-colors truncate block" style="color: var(--blue-electric);" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">{{ parse_url($exposant->site_web, PHP_URL_HOST) ?? $exposant->site_web }}</a></div>
                        </div>
                        @endif

                        @if($exposant->linkedin ?? null)
                        <hr style="border-color: var(--gray-soft);">
                        <a href="{{ $exposant->linkedin }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 transition-opacity hover:opacity-80">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background: #0077B5;">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                            </div>
                            <div><p class="text-xs font-medium" style="color: var(--gray-mid);">LinkedIn</p><p class="text-sm font-semibold" style="color: #0077B5;">Voir le profil</p></div>
                        </a>
                        @endif
                    </div>

                    {{-- QR code accréditation --}}
                    <div class="badge-perf px-6 py-5 flex items-center gap-4">
                        <div class="stamp-ring p-1.5 rounded-lg flex-shrink-0" style="background: white;">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=88x88&margin=0&color=0A1628&data={{ urlencode(request()->fullUrl()) }}"
                                 alt="QR code vers le profil de {{ $exposant->nom }}" width="72" height="72" loading="lazy">
                        </div>
                        <div>
                            <p class="text-xs font-semibold font-mono tracking-wide" style="color: var(--blue-night);">ACCÈS RAPIDE</p>
                            <p class="text-xs leading-relaxed" style="color: var(--gray-mid);">Scannez pour retrouver ce profil sur mobile</p>
                        </div>
                    </div>

                    {{-- CTA footer card --}}
                    <div class="px-6 pb-6 pt-1 flex flex-col gap-2.5">
                        @if($exposant->site_web)
                        <a href="{{ $exposant->site_web }}" target="_blank" rel="noopener noreferrer" class="magnetic glow-hover flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-semibold transition-all active:scale-95" style="background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: var(--blue-night);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                            Visiter le site web
                        </a>
                        @endif
                        @if($exposant->email)
                        <a href="mailto:{{ $exposant->email }}" class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-semibold border transition-all hover:bg-gray-50 active:scale-95" style="border-color: var(--gray-soft); color: var(--blue-night);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25"/></svg>
                            Envoyer un email
                        </a>
                        @endif
                        @if($exposant->telephone)
                        <a href="tel:{{ $exposant->telephone }}" class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-semibold border transition-all hover:bg-gray-50 active:scale-95" style="border-color: var(--gray-soft); color: var(--blue-night);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 6z"/></svg>
                            Appeler
                        </a>
                        @endif
                        @if($exposant->whatsapp ?? $exposant->telephone ?? null)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $exposant->whatsapp ?? $exposant->telephone) }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-semibold text-white transition-all active:scale-95" style="background: #25D366;">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.117.552 4.107 1.518 5.833L0 24l6.325-1.492A11.94 11.94 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.955 0-3.788-.531-5.361-1.457l-.385-.229-3.976.938 1.006-3.865-.251-.398A9.943 9.943 0 012 12C2 6.486 6.486 2 12 2s10 4.486 10 10-4.486 10-10 10z"/></svg>
                            WhatsApp
                        </a>
                        @endif
                        @if($exposant->brochure ?? null)
                        <a href="{{ Storage::url($exposant->brochure) }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-semibold border transition-all hover:bg-gray-50 active:scale-95" style="border-color: var(--gray-soft); color: var(--blue-night);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                            Télécharger la brochure
                        </a>
                        @endif
                    </div>
                </div>

                {{-- Card : Retour liste --}}
                <div class="rounded-2xl p-5 border" style="border-color: var(--gray-soft); background: var(--pearl);">
                    <p class="text-xs font-medium mb-3" style="color: var(--gray-mid);">Explorer d'autres exposants</p>
                    <a href="{{ route('exposants.index') }}" class="flex items-center gap-2 text-sm font-semibold transition-colors group" style="color: var(--blue-electric);" onmouseover="this.style.color='var(--blue-night)'" onmouseout="this.style.color='var(--blue-electric)'">
                        <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                        Voir tous les exposants
                    </a>
                </div>

            </div>
        </aside>

    </div>
</div>


{{-- ══════════════════════════════════════════════════════════════
     PARTENAIRES
     ══════════════════════════════════════════════════════════════ --}}
@php
    $partenaires = $exposant->partenaires ?? collect([
        (object)['nom' => 'SenCom',        'logo' => null],
        (object)['nom' => 'Fall Industries','logo' => null],
        (object)['nom' => 'ODK Group',     'logo' => null],
        (object)['nom' => 'Teranga Corp',  'logo' => null],
        (object)['nom' => 'Baobab Invest', 'logo' => null],
        (object)['nom' => 'Sahel Logistics','logo' => null],
    ]);
@endphp
<section class="py-16" style="background: var(--pearl);" aria-label="Partenaires">
    <div class="max-w-7xl mx-auto px-6 lg:px-16">
        <div class="text-center mb-10" data-aos="fade-up">
            <p class="eyebrow mb-2">Ils lui font confiance</p>
            <h2 class="font-display text-2xl lg:text-3xl" style="color: var(--blue-night);">Partenaires</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-6 items-center">
            @foreach($partenaires as $i => $p)
            <div class="flex items-center justify-center h-16" data-aos="fade-up" data-aos-delay="{{ $i * 60 }}">
                @if($p->logo ?? null)
                    <img src="{{ $p->logo }}" alt="{{ $p->nom }}" class="partner-logo max-h-10 w-auto object-contain">
                @else
                    <span class="partner-logo font-display text-lg" style="color: var(--blue-night);">{{ $p->nom }}</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     FAQ
     ══════════════════════════════════════════════════════════════ --}}
<section id="faq" class="py-20" aria-label="Questions fréquentes">
    <div class="max-w-3xl mx-auto px-6 lg:px-16">
        <div class="text-center mb-12" data-aos="fade-up">
            <p class="eyebrow mb-2">Questions fréquentes</p>
            <h2 class="font-display text-3xl lg:text-4xl" style="color: var(--blue-night);">Vous vous demandez peut-être…</h2>
        </div>

        <div x-data="{ openIndex: 0 }" class="flex flex-col gap-3" data-aos="fade-up">
            @php
                $faqs = [
                    ['q' => 'Comment prendre contact avec cet exposant ?', 'r' => "Utilisez les boutons de la carte « Coordonnées » dans la barre latérale : email, téléphone ou WhatsApp selon vos préférences. Vous pouvez aussi visiter directement son site web."],
                    ['q' => 'Puis-je consulter les événements passés de cet exposant ?', 'r' => "Oui. La section « Événements organisés » liste l'ensemble de ses participations, avec un statut clair (à venir, en cours, terminé) pour chacune."],
                    ['q' => 'Comment obtenir la brochure de présentation ?', 'r' => "Si l'exposant en a mis une à disposition, un bouton « Télécharger la brochure » apparaît dans la carte de coordonnées."],
                    ['q' => 'Les avis affichés sont-ils vérifiés ?', 'r' => "Les témoignages proviennent de partenaires et clients ayant collaboré avec l'exposant lors d'événements ExpoDakar."],
                ];
            @endphp
            @foreach($faqs as $i => $faq)
            <div class="rounded-2xl border overflow-hidden" style="border-color: var(--gray-soft);">
                <button @click="openIndex = openIndex === {{ $i }} ? -1 : {{ $i }}" class="w-full flex items-center justify-between gap-4 px-6 py-5 text-left" :aria-expanded="openIndex === {{ $i }}">
                    <span class="text-sm font-semibold" style="color: var(--blue-night);">{{ $faq['q'] }}</span>
                    <svg class="accordion-chevron w-5 h-5 flex-shrink-0" :style="openIndex === {{ $i }} ? 'transform: rotate(180deg)' : ''" fill="none" stroke="var(--blue-electric)" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <div x-show="openIndex === {{ $i }}" x-collapse x-cloak>
                    <p class="px-6 pb-5 text-sm leading-relaxed" style="color: var(--ink);">{{ $faq['r'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     CTA FINAL
     ══════════════════════════════════════════════════════════════ --}}
<section class="relative py-24 overflow-hidden" style="background: linear-gradient(135deg, var(--blue-night), var(--blue-deep));" aria-label="Appel à l'action">
    <div class="absolute inset-0 hero-noise" aria-hidden="true"></div>
    <div class="absolute -top-24 -left-24 w-[420px] h-[420px] rounded-full opacity-10" style="background: var(--gold); filter: blur(120px);" aria-hidden="true"></div>
    <div class="absolute -bottom-24 -right-24 w-[420px] h-[420px] rounded-full opacity-15" style="background: var(--blue-electric); filter: blur(120px);" aria-hidden="true"></div>

    <div class="relative z-10 max-w-3xl mx-auto px-6 text-center" data-aos="fade-up">
        <p class="eyebrow-light mb-4">Prêt à collaborer ?</p>
        <h2 class="font-display text-4xl lg:text-5xl text-white leading-tight mb-8">Vous souhaitez collaborer avec {{ $exposant->nom ?? 'cet exposant' }} ?</h2>
        <div class="flex flex-wrap items-center justify-center gap-4">
            @if($exposant->email)
            <a href="mailto:{{ $exposant->email }}" class="magnetic glow-hover inline-flex items-center gap-2 px-7 py-3.5 rounded-xl text-sm font-semibold transition-all" style="background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: var(--blue-night);">Contacter</a>
            @endif
            <a href="{{ route('exposants.index') }}" class="magnetic inline-flex items-center gap-2 px-7 py-3.5 rounded-xl text-sm font-semibold border border-white/20 text-white transition-all hover:bg-white/10">Découvrir les autres exposants</a>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     FOOTER PREMIUM
     ══════════════════════════════════════════════════════════════ --}}
<footer style="background: var(--blue-night);" role="contentinfo">
    <div class="max-w-7xl mx-auto px-6 lg:px-16 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 mb-12">
            <div class="lg:col-span-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3 mb-4" aria-label="ExpoDakar">
                    <img src="https://res.cloudinary.com/dstbqtuxm/image/upload/v1782085416/ChatGPT_Image_Jun_21__2026__07_24_51_PM-removebg-preview_zi77k0.png" alt="Logo ExpoDakar" class="h-9 w-auto object-contain">
                    <span class="font-display text-xl text-white">Expo<span class="text-gold-gradient">Dakar</span></span>
                </a>
                <p class="text-sm leading-relaxed text-white/50 max-w-xs">La plateforme de référence pour les foires, salons et forums professionnels au Sénégal.</p>
            </div>

            <div class="lg:col-span-2">
                <p class="eyebrow-light mb-4">Navigation</p>
                <nav class="flex flex-col gap-2.5" aria-label="Footer navigation">
                    <a href="{{ route('home') }}" class="text-sm text-white/60 hover:text-white transition-colors">Accueil</a>
                    <a href="{{ route('events.index') }}" class="text-sm text-white/60 hover:text-white transition-colors">Événements</a>
                    <a href="{{ route('exposants.index') }}" class="text-sm text-white/60 hover:text-white transition-colors">Exposants</a>
                    <a href="/#faq" class="text-sm text-white/60 hover:text-white transition-colors">FAQ</a>
                </nav>
            </div>

            <div class="lg:col-span-2">
                <p class="eyebrow-light mb-4">Réseaux</p>
                <div class="flex gap-3">
                    <a href="#" aria-label="LinkedIn" class="w-9 h-9 rounded-lg flex items-center justify-center border border-white/10 text-white/60 hover:text-white hover:border-white/30 transition-all"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg></a>
                    <a href="#" aria-label="Instagram" class="w-9 h-9 rounded-lg flex items-center justify-center border border-white/10 text-white/60 hover:text-white hover:border-white/30 transition-all"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073z"/></svg></a>
                    <a href="#" aria-label="X" class="w-9 h-9 rounded-lg flex items-center justify-center border border-white/10 text-white/60 hover:text-white hover:border-white/30 transition-all"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>
                </div>
            </div>

            <div class="lg:col-span-4">
                <p class="eyebrow-light mb-4">Restez informé</p>
                <form onsubmit="return false;" class="flex gap-2">
                    <label for="footer-newsletter" class="sr-only">Adresse email</label>
                    <input id="footer-newsletter" type="email" required placeholder="votre@email.com" class="flex-1 min-w-0 px-4 py-2.5 rounded-xl text-sm text-white bg-white/5 border border-white/10 placeholder-white/30 focus:border-white/30 focus:outline-none transition-colors">
                    <button type="submit" class="flex-shrink-0 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all hover:brightness-110" style="background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: var(--blue-night);">S'abonner</button>
                </form>
                <p class="text-xs text-white/35 mt-2.5">Un email par mois, zéro spam.</p>
            </div>
        </div>

        <hr class="border-white/10">
        <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-white/40">© {{ date('Y') }} ExpoDakar · Tous droits réservés</p>
            <p class="text-xs text-white/40">Fait avec soin à Dakar, Sénégal</p>
        </div>
    </div>
</footer>


{{-- ══════════════════════════════════════════════════════════════
     LIGHTBOX GALERIE
     ══════════════════════════════════════════════════════════════ --}}
<div x-show="lightboxOpen" x-cloak
     x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     @keydown.escape.window="lightboxOpen = false"
     class="fixed inset-0 z-[100] flex items-center justify-center p-6" style="background: rgba(10,22,40,.92);" role="dialog" aria-modal="true" aria-label="Photo en grand format">
    <button @click="lightboxOpen = false" class="absolute top-6 right-6 w-11 h-11 rounded-full flex items-center justify-center text-white border border-white/20 hover:bg-white/10 transition-colors" aria-label="Fermer">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <img :src="lightboxSrc" :alt="lightboxAlt" @click.outside="lightboxOpen = false" class="max-w-full max-h-[85vh] rounded-2xl object-contain shadow-2xl">
</div>


{{-- ══════════════════════════════════════════════════════════════
     SCRIPTS
     ══════════════════════════════════════════════════════════════ --}}
<script>
document.addEventListener('DOMContentLoaded', () => {

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // AOS scroll reveals
    AOS.init({ duration: 700, easing: 'ease-out-cubic', once: true, offset: 60, disable: prefersReducedMotion });

    // Legacy simple reveal fallback for elements still using .reveal class
    const revealEls = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window && revealEls.length) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); } });
        }, { threshold: 0.1 });
        revealEls.forEach(el => io.observe(el));
    }

    if (prefersReducedMotion) return;

    // Lenis smooth scroll
    if (window.Lenis) {
        const lenis = new Lenis({ duration: 1.1, smoothWheel: true });
        function raf(time) { lenis.raf(time); requestAnimationFrame(raf); }
        requestAnimationFrame(raf);
        if (window.gsap && window.gsap.ticker) {
            gsap.ticker.add((time) => lenis.raf(time * 1000));
            gsap.ticker.lagSmoothing(0);
        }
    }

    // GSAP hero load sequence
    if (window.gsap) {
        gsap.registerPlugin(ScrollTrigger);
        const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });
        tl.to('#heroBreadcrumb', { opacity: 1, y: 0, duration: .6, delay: .1 })
          .fromTo('#heroLogo',   { opacity: 0, scale: .85, y: 16 }, { opacity: 1, scale: 1, y: 0, duration: .8 }, '-=.3')
          .fromTo('#heroText',   { opacity: 0, y: 24 },              { opacity: 1, y: 0, duration: .8 }, '-=.55')
          .fromTo('#heroActions',{ opacity: 0, y: 16 },              { opacity: 1, y: 0, duration: .7 }, '-=.5');

        // Parallax glows
        gsap.to('#glowBlue', { y: -40, scrollTrigger: { trigger: '#hero', start: 'top top', end: 'bottom top', scrub: 1 } });
        gsap.to('#glowGold', { y: 30,  scrollTrigger: { trigger: '#hero', start: 'top top', end: 'bottom top', scrub: 1 } });

        // Stat counters
        document.querySelectorAll('.stat-counter').forEach((el) => {
            const target = parseFloat(el.dataset.target) || 0;
            const suffixSpan = el.querySelector('span');
            const obj = { val: 0 };
            ScrollTrigger.create({
                trigger: el, start: 'top 88%', once: true,
                onEnter: () => gsap.to(obj, {
                    val: target, duration: 1.4, ease: 'power2.out',
                    onUpdate: () => { el.firstChild.nodeValue = (target % 1 === 0 ? Math.round(obj.val) : obj.val.toFixed(1)) + ''; }
                })
            });
        });
    }

    // Magnetic button img
    document.querySelectorAll('.magnetic').forEach((btn) => {
        btn.addEventListener('mousemove', (e) => {
            const r = btn.getBoundingClientRect();
            const x = (e.clientX - r.left - r.width / 2) * 0.25;
            const y = (e.clientY - r.top - r.height / 2) * 0.25;
            btn.style.transform = `translate(${x}px, ${y}px)`;
        });
        btn.addEventListener('mouseleave', () => { btn.style.transform = 'translate(0, 0)'; });
    });
});
</script>

</body>
</html>
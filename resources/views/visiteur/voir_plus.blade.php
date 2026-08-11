{{--
|--------------------------------------------------------------------------
| ExpoDakar – Page Détail Événement PREMIUM (standalone, sans layout parent)
| Laravel 12 • Blade • Tailwind CSS v4 (Vite) • Alpine.js 3 • GSAP • Lenis
| Variable reçue : $event (relations ->categorie, ->exposant, ->galerie*)
| * galerie / temoignages / partenaires / timeline : fallback démo fourni
|   si les relations n'existent pas encore sur le modèle.
|--------------------------------------------------------------------------
--}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ Str::limit(strip_tags($event->description), 155) }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta property="og:title"       content="{{ $event->titre }} – ExpoDakar">
    <meta property="og:description" content="{{ Str::limit(strip_tags($event->description), 155) }}">
    <meta property="og:type"        content="event">
    @if($event->image)
    <meta property="og:image"       content="{{ Storage::url($event->image) }}">
    @endif

    <title>{{ $event->titre }} – ExpoDakar</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@studio-freight/lenis@1.0.42/dist/lenis.min.js"></script>

    <style>
        /* ── Design tokens ───────────────────────────────────── */
        :root {
            --blue:         #1E5FD8;   /* Bleu Électrique */
            --blue-dark:    #10284D;   /* Bleu Profond */
            --blue-night:   #0A1628;   /* Bleu Nuit */
            --blue-soft:    #EEF3FE;
            --gold:         #C9A84C;   /* Or Premium */
            --gold-light:   #E8C96A;   /* Or Clair */
            --pearl:        #F8F9FC;
            --gray-soft:    #EDEEF2;
            --gray-mid:     #8892A4;
            --gray-dark:    #374151;
            --success:      #10B981;
            --shadow-sm:    0 2px 12px rgba(10,22,40,.06);
            --shadow-md:    0 8px 32px rgba(10,22,40,.10);
            --shadow-lg:    0 24px 64px rgba(10,22,40,.16);
            --radius:       24px;
        }

        *, *::before, *::after { box-sizing: border-box; }
        html  { scroll-behavior: smooth; } /* fallback si JS désactivé ; neutralisé en JS une fois Lenis actif pour éviter tout conflit de fluidité */
        body  {
            font-family: 'Inter', sans-serif;
            color: var(--blue-night);
            background: #fff;
            overflow-x: hidden;
            margin: 0;
            -webkit-font-smoothing: antialiased;
            max-width: 100vw;
        }
        img, svg { max-width: 100%; }
        .font-display { font-family: 'Instrument Serif', serif; }
        .font-mono     { font-family: 'JetBrains Mono', monospace; }

        [x-cloak] { display: none !important; }

        .text-gold {
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }

        /* ── Navbar ──────────────────────────────────────────── */
        .navbar { position: fixed; inset: 0 0 auto 0; z-index: 50; transition: background .3s, box-shadow .3s, backdrop-filter .3s; }
        .navbar.scrolled { background: rgba(10,22,40,.86); backdrop-filter: blur(14px); box-shadow: 0 2px 24px rgba(10,22,40,.2); }
        .navbar-inner { max-width:80rem;margin:0 auto;padding:0 1.5rem;display:flex;align-items:center;justify-content:space-between;height:4.5rem; }
        .nav-cta-text { display:inline; }

        /* ── Hero atmosphere ─────────────────────────────────── */
        .hero-overlay { background: linear-gradient(to bottom, rgba(10,22,40,.30) 0%, rgba(10,22,40,.55) 45%, rgba(10,22,40,.94) 100%); }
        .hero-grid { background-image: linear-gradient(rgba(196,168,76,.35) 1px,transparent 1px), linear-gradient(90deg,rgba(196,168,76,.35) 1px,transparent 1px); background-size: 64px 64px; }
        .hero-noise {
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            background-size: 200px; opacity: .04; mix-blend-mode: overlay;
        }
        .particle { position: absolute; border-radius: 50%; pointer-events: none; }
        .hero-section { position:relative;min-height:94vh;display:flex;flex-direction:column;justify-content:flex-end;overflow:hidden; }
        .hero-inner { position:relative;z-index:1;max-width:80rem;margin:0 auto;padding:2rem 1.5rem 5rem;width:100%; }

        /* ── Glassmorphism ───────────────────────────────────── */
        .glass { background: rgba(255,255,255,.08); backdrop-filter: blur(18px); -webkit-backdrop-filter: blur(18px); border: 1px solid rgba(255,255,255,.16); }
        .glass-light { background: rgba(255,255,255,.75); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid rgba(255,255,255,.6); }

        /* ── Card system ─────────────────────────────────────── */
        .card-premium { border-radius: var(--radius); transition: transform .35s cubic-bezier(.16,1,.3,1), box-shadow .35s ease, border-color .35s ease; }
        .card-premium:hover { transform: translateY(-6px) rotate(-.35deg); box-shadow: var(--shadow-lg); }
        .card-lift { transition: transform .3s cubic-bezier(.16,1,.3,1), box-shadow .3s ease; }
        .card-lift:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }

        /* ── Reveal on scroll ────────────────────────────────── */
        .reveal { opacity: 0; transform: translateY(28px); filter: blur(4px); transition: opacity .75s cubic-bezier(.16,1,.3,1), transform .75s cubic-bezier(.16,1,.3,1), filter .75s ease; }
        .reveal.on { opacity: 1; transform: translateY(0); filter: blur(0); }
        .d1 { transition-delay: .08s; } .d2 { transition-delay: .16s; } .d3 { transition-delay: .24s; } .d4 { transition-delay: .32s; }

        .sidebar-sticky { position: sticky; top: 6rem; }

        /* ── Hero fade-in helper (replaces duplicated inline style attrs) ── */
        .fade-block { transition: opacity .7s ease, transform .7s ease; }
        .fade-block.d-title { transition-delay: .1s; }
        .fade-block.d-sub { transition: opacity .8s ease .15s; }
        .fade-block.d-meta { transition: opacity .8s ease .2s, transform .8s ease .2s; }
        .fade-block.d-stats { transition: opacity .8s ease .3s, transform .8s ease .3s; }
        .fade-block.d-mockup { transition: opacity 1s ease .3s, transform 1s ease .3s; }

        /* ── Buttons : magnetic + glow + ripple ──────────────── */
        .btn-premium {
            position: relative; overflow: hidden; display: inline-flex; align-items: center; justify-content: center; gap: .5rem;
            font-family: inherit; cursor: pointer; text-decoration: none; border: none;
            transition: filter .2s ease, box-shadow .3s ease;
            will-change: transform;
        }
        .btn-premium:active { filter: brightness(.96); }
        .btn-glow { position: relative; }
        .btn-glow::after {
            content: ''; position: absolute; inset: -3px; border-radius: inherit;
            background: linear-gradient(135deg, var(--blue), var(--gold)); opacity: 0; filter: blur(16px);
            transition: opacity .35s ease; z-index: -1;
        }
        .btn-glow:hover::after { opacity: .55; }
        .ripple { position: absolute; border-radius: 50%; background: rgba(255,255,255,.55); transform: scale(0); animation: rippleAnim .6s ease-out forwards; pointer-events: none; }
        @keyframes rippleAnim { to { transform: scale(3); opacity: 0; } }

        .share-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: .5rem;
            padding: .65rem 1.25rem; border-radius: 14px; font-size: .8rem; font-weight: 600; font-family: inherit;
            border: none; cursor: pointer; transition: filter .2s, transform .15s; text-decoration: none; color: white;
        }
        .share-btn:hover  { filter: brightness(1.1); }
        .share-btn:active { transform: scale(.96); }

        .sep { border: none; border-top: 1px solid var(--gray-soft); margin: 0; }

        .badge-status { display: inline-flex; align-items: center; gap: .4rem; padding: .3rem .9rem; border-radius: 99px; font-size: .72rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; }
        .badge-upcoming { background: #ECFDF5; color: #059669; }
        .badge-ongoing  { background: #FFF7ED; color: #C2410C; }
        .badge-past     { background: var(--gray-soft); color: var(--gray-mid); }

        *:focus-visible { outline: 2px solid var(--blue); outline-offset: 3px; border-radius: 6px; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--pearl); }
        ::-webkit-scrollbar-thumb { background: var(--blue); border-radius: 99px; }

        /* ── Hero mockup (illustration signature) ───────────── */
        .mockup-wrap { position: relative; width: 100%; max-width: 420px; aspect-ratio: 1/1; margin-inline: auto; }
        .mockup-ring { position: absolute; inset: 0; border-radius: 50%; border: 1px dashed rgba(232,201,106,.35); }
        .mockup-card {
            position: absolute; border-radius: 20px; background: rgba(255,255,255,.06); backdrop-filter: blur(14px);
            border: 1px solid rgba(255,255,255,.18); box-shadow: 0 20px 50px rgba(0,0,0,.35);
            padding: 1rem 1.15rem; color: white;
        }
        .mockup-glow { position: absolute; border-radius: 50%; filter: blur(90px); pointer-events: none; }

        /* ── Masonry gallery ──────────────────────────────────── */
        .masonry { column-count: 1; column-gap: 1rem; }
        @media (min-width: 640px)  { .masonry { column-count: 2; } }
        @media (min-width: 1024px) { .masonry { column-count: 2; } }
        .masonry-item { break-inside: avoid; margin-bottom: 1rem; }
        .gallery-overlay { opacity: 0; transition: opacity .35s ease; background: linear-gradient(to top, rgba(10,22,40,.78), transparent 60%); }
        .gallery-tile:hover .gallery-overlay, .gallery-tile:focus-visible .gallery-overlay { opacity: 1; }
        .gallery-tile img { transition: transform .55s cubic-bezier(.16,1,.3,1); }
        .gallery-tile:hover img { transform: scale(1.07); }

        /* ── Stat cards ───────────────────────────────────────── */
        .stat-card { background: #fff; border: 1px solid var(--gray-soft); transition: all .3s ease; }
        .stat-card:hover { border-color: rgba(30,95,216,.25); box-shadow: var(--shadow-md); transform: translateY(-4px); }

        /* ── Timeline ─────────────────────────────────────────── */
        .timeline-line { position: absolute; left: 19px; top: 0; bottom: 0; width: 2px; background: linear-gradient(to bottom, var(--gold), var(--gray-soft)); }
        .timeline-dot { width: 40px; height: 40px; border-radius: 50%; background: white; border: 2px solid var(--gold); display: flex; align-items: center; justify-content: center; flex-shrink: 0; z-index: 1; box-shadow: 0 0 0 6px white; }

        /* ── Accordion ────────────────────────────────────────── */
        .accordion-chevron { transition: transform .35s cubic-bezier(.16,1,.3,1); }

        /* ── Partner logos ────────────────────────────────────── */
        .partner-logo { filter: grayscale(1) opacity(.5); transition: filter .4s ease, transform .4s ease; }
        .partner-logo:hover { filter: grayscale(0) opacity(1); transform: scale(1.06); }

        /* ── Layout containers (extracted so mobile rules can target them) ── */
        .page-container { max-width:80rem;margin:0 auto;padding:5rem 1.5rem 6rem; }
        .two-col { display:grid;grid-template-columns:1fr 380px;gap:3rem;align-items:start; }
        .left-col { display:flex;flex-direction:column;gap:4rem; }
        .section-block { padding:2.5rem;border-radius:var(--radius);background:var(--pearl);position:relative;overflow:hidden; }
        .practical-grid { display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.25rem; }
        .practical-card { padding:1.5rem;border-radius:20px;background:var(--pearl);border:1px solid var(--gray-soft); }
        .stats-grid { display:grid;grid-template-columns:repeat(auto-fit,minmax(130px,1fr));gap:1rem; }
        .org-card { padding:2rem;border:1px solid var(--gray-soft);background:var(--pearl);box-shadow:var(--shadow-sm); }
        .share-card { padding:1.75rem;border-radius:20px;border:1px solid var(--gray-soft);background:var(--pearl); }
        .share-grid { display:grid;grid-template-columns:repeat(4,1fr);gap:.75rem; }
        .autres-grid { display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:1.5rem; }
        .sidebar-card { padding:1.5rem;border:1px solid var(--gray-soft);background:white;box-shadow:var(--shadow-sm); }
        .footer-inner { max-width:80rem;margin:0 auto;padding:4.5rem 1.5rem 3rem; }
        .footer-grid { display:grid;grid-template-columns:1.6fr 1fr 1fr 1.3fr;gap:3rem;margin-bottom:3.5rem; }
        .partners-grid { display:grid;grid-template-columns:repeat(4,1fr);gap:1.5rem;align-items:center; }
        .cta-section { position:relative;padding:6rem 1.5rem;overflow:hidden;background:linear-gradient(135deg,var(--blue-night),var(--blue-dark)); }

        @media (max-width: 1023px) {
            .two-col { grid-template-columns: 1fr !important; }
            .sidebar-sticky { position: static !important; }
            .hide-mobile { display: none !important; }
            .hero-cols { grid-template-columns: 1fr !important; }
            .mockup-wrap { max-width: 280px; margin-top: 2.5rem; }
            .footer-grid { grid-template-columns: 1fr 1fr !important; gap: 2.5rem !important; }
            .partners-grid { grid-template-columns: repeat(3,1fr) !important; }
        }
        @media (max-width: 767px) {
            .page-container { padding: 3rem 1.25rem 3.5rem !important; }
            .left-col { gap: 2.75rem !important; }
            .section-block, .org-card { padding: 1.5rem !important; }
            .share-card { padding: 1.25rem !important; }
            .cta-section { padding: 3.5rem 1.25rem !important; }
            .footer-inner { padding: 3rem 1.25rem 2rem !important; }
            .partners-grid { grid-template-columns: repeat(2,1fr) !important; }
            h2.font-display { font-size: 1.5rem !important; }
            .nav-cta-text { display: none; }
        }
        @media (max-width: 640px) {
            .hero-title { font-size: clamp(2rem, 8vw, 3rem) !important; }
            .hero-section { min-height: auto !important; padding-top: 5.5rem; }
            .hero-inner { padding: 1.5rem 1.25rem 3rem !important; }
            .share-grid { grid-template-columns: 1fr 1fr !important; }
            .footer-grid { grid-template-columns: 1fr !important; }
            .partners-grid { grid-template-columns: repeat(2,1fr) !important; }
            .practical-card { padding: 1.25rem !important; }
            .navbar-inner { padding: 0 1.25rem !important; height: 4rem !important; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: .001ms !important; transition-duration: .001ms !important; scroll-behavior: auto !important; }
        }

        @keyframes scrollPulse { 0%,100%{opacity:.5;transform:scaleY(1)} 50%{opacity:1;transform:scaleY(1.15)} }
        @keyframes floatY { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-14px); } }
    </style>
</head>
<body x-data="{ lightboxOpen: false, lightboxSrc: '', lightboxAlt: '' }">

{{-- ══════════════════════════════════════════════════════════════
     NAVBAR img
     ══════════════════════════════════════════════════════════════ --}}
<header class="navbar"
    x-data="{ scrolled: false, init() { window.addEventListener('scroll', () => { this.scrolled = window.scrollY > 80; }, { passive: true }); } }"
    :class="scrolled ? 'scrolled' : ''" role="banner">
    <div class="navbar-inner">
        <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:.65rem;text-decoration:none;" aria-label="ExpoDakar – Accueil">
            <img src=https://res.cloudinary.com/dstbqtuxm/image/upload/v1786364683/ChatGPT_Image_10_ao%C3%BBt_2026__02_24_21-removebg-preview_spadbb.png alt="Logo ExpoDakar" style="height:2.75rem;width:auto;object-fit:contain;">
            <span class="font-display" style="font-size:1.35rem;color:white;">Expo<span class="text-gold">Dakar</span></span>
        </a>
        <a href="/" class="btn-premium" style="gap:.5rem;font-size:.8rem;font-weight:600;color:white;padding:.5rem 1rem;border-radius:12px;background:rgba(255,255,255,.12);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.2);transition:background .2s;"
           onmouseover="this.style.background='rgba(255,255,255,.22)'" onmouseout="this.style.background='rgba(255,255,255,.12)'">
            <svg style="width:.9rem;height:.9rem;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
            <span class="nav-cta-text">Tous les événements</span>
        </a>
    </div>
</header>


{{-- ══════════════════════════════════════════════════════════════
     1. HERO IMMERSIF — image + illustration flottante à droite
     ══════════════════════════════════════════════════════════════ --}}
<section id="hero" class="hero-section" x-data="{ visible: false }" x-init="setTimeout(() => visible = true, 100)" aria-label="Bannière de l'événement">

    <div style="position:absolute;inset:0;z-index:0;">
        @if($event->image)
            <img src="{{ $event->image }}" alt="{{ $event->titre }}" style="width:100%;height:100%;object-fit:cover;" loading="eager">
        @else
            <div style="width:100%;height:100%;background:linear-gradient(135deg,#0A1628 0%,#10284D 55%,#1E5FD8 100%);"></div>
        @endif
        <div class="hero-overlay" style="position:absolute;inset:0;" aria-hidden="true"></div>
        <div class="hero-grid" style="position:absolute;inset:0;opacity:.1;" aria-hidden="true"></div>
        <div class="hero-noise" style="position:absolute;inset:0;" aria-hidden="true"></div>
        <div id="glowBlue" class="mockup-glow" style="width:520px;height:520px;bottom:-160px;right:-120px;background:var(--blue);opacity:.22;"></div>
        <div id="glowGold" class="mockup-glow" style="width:360px;height:360px;top:60px;left:-100px;background:var(--gold);opacity:.14;"></div>
    </div>

    <div class="hero-inner">

        <nav style="display:flex;gap:.5rem;align-items:center;margin-bottom:1.5rem;opacity:.5;font-size:.75rem;color:white;flex-wrap:wrap;" aria-label="Fil d'Ariane">
            <a href="{{ route('home') }}" style="color:inherit;text-decoration:none;">Accueil</a>
            <span>/</span>
            <a href="{{ route('events.index') }}" style="color:inherit;text-decoration:none;">Événements</a>
            <span>/</span>
            <span style="opacity:.9;">{{ Str::limit($event->titre, 30) }}</span>
        </nav>

        <div class="hero-cols" style="display:grid;grid-template-columns:1.15fr .85fr;gap:2.5rem;align-items:center;">

            {{-- Colonne texte --}}
            <div>
                <div class="fade-block" :style="visible ? 'opacity:1;transform:translateY(0)' : 'opacity:0;transform:translateY(16px)'" style="display:flex;flex-wrap:wrap;gap:.75rem;margin-bottom:1.75rem;">
                    @if($event->exposant)
                    <span style="display:inline-flex;align-items:center;gap:.5rem;padding:.3rem .8rem .3rem .3rem;border-radius:99px;background:rgba(255,255,255,.1);backdrop-filter:blur(6px);border:1px solid rgba(255,255,255,.18);">
                        @if($event->exposant->logo)
                            <img src="{{$event->exposant->logo }}" alt="" style="width:1.5rem;height:1.5rem;border-radius:50%;object-fit:cover;background:white;">
                        @else
                            <span style="width:1.5rem;height:1.5rem;border-radius:50%;background:linear-gradient(135deg,var(--blue),var(--blue-dark));display:flex;align-items:center;justify-content:center;font-size:.65rem;color:white;font-weight:700;">{{ strtoupper(substr($event->exposant->nom_entreprise ?? 'E',0,1)) }}</span>
                        @endif
                        <span style="font-size:.72rem;font-weight:600;color:rgba(255,255,255,.9);">{{ $event->exposant->nom_entreprise ?? 'ExpoDakar' }}</span>
                    </span>
                    @endif

                    @if($event->categorie)
                    <span style="display:inline-flex;align-items:center;gap:.4rem;padding:.35rem .9rem;border-radius:99px;font-size:.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;background:var(--blue);color:white;">
                        <svg style="width:.75rem;height:.75rem;" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M17.707 9.293a1 1 0 0 1 0 1.414l-7 7a1 1 0 0 1-1.414 0l-7-7A.997.997 0 0 1 2 10V5a3 3 0 0 1 3-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/></svg>
                        {{ $event->categorie->nom }}
                    </span>
                    @endif

                    @php
                        $now   = now();
                        $debut = \Carbon\Carbon::parse($event->date_debut);
                        $fin   = \Carbon\Carbon::parse($event->date_fin);
                        if ($now->lt($debut))                { $statusLabel = 'À venir';  $statusClass = 'badge-upcoming'; $statusDot = '#10B981'; }
                        elseif ($now->between($debut, $fin))  { $statusLabel = 'En cours'; $statusClass = 'badge-ongoing';  $statusDot = '#F97316'; }
                        else                                   { $statusLabel = 'Terminé';  $statusClass = 'badge-past';     $statusDot = '#9CA3AF'; }
                    @endphp
                    <span class="badge-status {{ $statusClass }}">
                        <span style="width:.45rem;height:.45rem;border-radius:50%;background:{{ $statusDot }};display:inline-block;" aria-hidden="true"></span>
                        {{ $statusLabel }}
                    </span>
                </div>

                <h1 class="font-display hero-title d-title fade-block"
                    style="font-size:clamp(2.5rem,5.2vw,4.25rem);color:white;line-height:1.06;margin:0 0 1.25rem;"
                    :style="visible ? 'opacity:1;transform:translateY(0)' : 'opacity:0;transform:translateY(24px)'">
                    {{ $event->titre }}
                </h1>

                @if($event->sous_titre ?? null)
                <p class="fade-block d-sub" style="font-size:1.05rem;color:rgba(255,255,255,.65);max-width:38rem;margin:0 0 1.75rem;line-height:1.6;"
                   :style="visible ? 'opacity:1' : 'opacity:0'">
                    {{ $event->sous_titre }}
                </p>
                @endif

                <div class="fade-block d-meta" :style="visible ? 'opacity:1;transform:translateY(0)' : 'opacity:0;transform:translateY(20px)'" style="display:flex;flex-wrap:wrap;gap:1.5rem;align-items:center;margin-bottom:2rem;">
                    <div style="display:flex;align-items:center;gap:.6rem;color:rgba(255,255,255,.85);font-size:.925rem;">
                        <svg style="width:1.1rem;height:1.1rem;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                        <span>
                            {{ \Carbon\Carbon::parse($event->date_debut)->translatedFormat('d M Y') }}
                            @if($event->date_fin && $event->date_fin !== $event->date_debut)
                                <span style="opacity:.6;margin:0 .3rem;">→</span>{{ \Carbon\Carbon::parse($event->date_fin)->translatedFormat('d M Y') }}
                            @endif
                        </span>
                    </div>
                    <span style="width:1px;height:1.25rem;background:rgba(255,255,255,.25);" aria-hidden="true"></span>
                    <div style="display:flex;align-items:center;gap:.6rem;color:rgba(255,255,255,.85);font-size:.925rem;">
                        <svg style="width:1.1rem;height:1.1rem;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                        <span>{{ $event->lieu }}</span>
                    </div>
                    @php $duree = \Carbon\Carbon::parse($event->date_debut)->diffInDays($event->date_fin) + 1; @endphp
                    <div style="display:flex;align-items:center;gap:.6rem;color:rgba(255,255,255,.7);font-size:.85rem;">
                        <svg style="width:1rem;height:1rem;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                        <span>{{ $duree }} jour{{ $duree > 1 ? 's' : '' }}</span>
                    </div>
                </div>

                {{-- Mini stat row (statistiques rapides) --}}
                <div class="fade-block d-stats" :style="visible ? 'opacity:1;transform:translateY(0)' : 'opacity:0;transform:translateY(16px)'" style="display:flex;flex-wrap:wrap;gap:2rem;">
                    <div>
                        <p class="font-display" style="font-size:2rem;color:white;line-height:1;margin:0;">248</p>
                        <p style="font-size:.72rem;color:rgba(255,255,255,.55);margin:.35rem 0 0;text-transform:uppercase;letter-spacing:.08em;">Inscrits</p>
                    </div>
                    <div>
                        <p class="font-display" style="font-size:2rem;color:white;line-height:1;margin:0;">500</p>
                        <p style="font-size:.72rem;color:rgba(255,255,255,.55);margin:.35rem 0 0;text-transform:uppercase;letter-spacing:.08em;">Places totales</p>
                    </div>
                    @if($event->exposant && $event->exposant->responsable)
                    <div>
                        <p class="font-display" style="font-size:1.15rem;color:white;line-height:1.3;margin:0;">{{ $event->exposant->responsable }}</p>
                        <p style="font-size:.72rem;color:rgba(255,255,255,.55);margin:.35rem 0 0;text-transform:uppercase;letter-spacing:.08em;">Responsable</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Colonne illustration mockup flottant (signature) --}}
            <div class="hide-mobile mockup-wrap fade-block d-mockup" id="mockupWrap"
                 :style="visible ? 'opacity:1;transform:scale(1)' : 'opacity:0;transform:scale(.9)'">
                <div class="mockup-wrap">
                    <div class="mockup-ring" style="animation: floatY 7s ease-in-out infinite;"></div>
                    <div class="mockup-ring" style="inset:14%; animation: floatY 9s ease-in-out infinite reverse;"></div>

                    <div id="mockCard1" class="mockup-card" style="top:6%;left:2%;width:58%;">
                        <p class="font-mono" style="font-size:.62rem;letter-spacing:.1em;color:var(--gold-light);margin:0 0 .4rem;text-transform:uppercase;">Badge accès</p>
                        <p style="font-size:.92rem;font-weight:700;margin:0 0 .15rem;">{{ Str::limit($event->titre, 22) }}</p>
                        <p style="font-size:.68rem;color:rgba(255,255,255,.6);margin:0;">{{ \Carbon\Carbon::parse($event->date_debut)->translatedFormat('d M Y') }}</p>
                    </div>

                    <div id="mockCard2" class="mockup-card" style="bottom:16%;right:0;width:52%;">
                        <div style="display:flex;align-items:center;gap:.5rem;">
                            <span style="width:2rem;height:2rem;border-radius:50%;background:linear-gradient(135deg,var(--gold),var(--gold-light));display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <svg style="width:1rem;height:1rem;color:var(--blue-night);" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                            </span>
                            <div>
                                <p style="font-size:.72rem;font-weight:600;margin:0;">{{ Str::limit($event->lieu, 18) }}</p>
                                <p style="font-size:.62rem;color:rgba(255,255,255,.55);margin:0;">Lieu confirmé</p>
                            </div>
                        </div>
                    </div>

                    <div id="mockCard3" class="mockup-card" style="top:38%;right:4%;width:44%;text-align:center;">
                        <p class="font-display" style="font-size:1.6rem;margin:0;line-height:1;">{{ $duree }}</p>
                        <p style="font-size:.62rem;color:rgba(255,255,255,.55);margin:.2rem 0 0;text-transform:uppercase;letter-spacing:.08em;">Jour{{ $duree>1?'s':'' }}</p>
                    </div>

                    <div class="mockup-glow" style="width:220px;height:220px;top:30%;left:30%;background:var(--blue);opacity:.35;"></div>
                </div>
            </div>
        </div>
    </div>

    <div style="position:absolute;bottom:2rem;left:50%;transform:translateX(-50%);display:flex;flex-direction:column;align-items:center;gap:.4rem;opacity:.5;" aria-hidden="true">
        <div style="width:1px;height:2rem;background:linear-gradient(to bottom,rgba(255,255,255,.5),transparent);animation:scrollPulse 1.8s ease-in-out infinite;"></div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     2. LAYOUT PRINCIPAL
     ══════════════════════════════════════════════════════════════ --}}
<div class="page-container">
    <div class="two-col">

        <div class="left-col">

            {{-- ══════════════ 3. À propos — storytelling ══════════════ --}}
            <section class="reveal" x-intersect.once="$el.classList.add('on')" aria-label="Description de l'événement">
                <div class="section-block">
                    <div style="position:absolute;top:-30px;right:-20px;width:140px;height:140px;border-radius:50%;background:radial-gradient(circle,rgba(201,168,76,.18),transparent 70%);" aria-hidden="true"></div>
                    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.75rem;position:relative;flex-wrap:wrap;">
                        <div style="width:3.25rem;height:3.25rem;border-radius:14px;background:linear-gradient(135deg,var(--blue),var(--blue-dark));display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:var(--shadow-sm);">
                            <svg style="width:1.5rem;height:1.5rem;color:white;" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                        </div>
                        <div>
                            <p class="font-mono" style="font-size:.68rem;letter-spacing:.16em;text-transform:uppercase;color:var(--gold);margin:0 0 .2rem;">Présentation</p>
                            <h2 class="font-display" style="font-size:2rem;color:var(--blue-night);margin:0;">À propos de cet événement</h2>
                        </div>
                    </div>
                    <div style="font-size:1.08rem;line-height:1.9;color:var(--gray-dark);max-width:64ch;position:relative;">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                </div>
            </section>


            {{-- ══════════════ Chiffres clés ══════════════ --}}
            @php
                $stats = [
                    [
                        'val' => $event->exposant->annees_experience ?? 25, 'suffix' => '+', 'label' => "Années d'expérience",
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.5a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0l-4.725 2.885a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557L2.037 10.386a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>',
                    ],
                    [
                        'val' => optional($event->exposant->evenements ?? null)->count() ?: 80, 'suffix' => '',  'label' => 'Salons organisés',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.006 0H9.497m5.006 0a41.7 41.7 0 00-5.006 0M9.497 14.25a41.6 41.6 0 015.006 0M5.625 8.25a11.25 11.25 0 0012.75 0M5.625 8.25c-1.036 0-1.875-.84-1.875-1.875V5.25c0-.621.504-1.125 1.125-1.125h14.25c.621 0 1.125.504 1.125 1.125v1.125c0 1.036-.84 1.875-1.875 1.875M5.625 8.25v2.25M18.375 8.25v2.25"/>',
                    ],
                    [
                        'val' => $event->nb_visiteurs_prevus ?? 150000, 'suffix' => '+', 'label' => 'Visiteurs attendus',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>',
                    ],
                    [
                        'val' => $event->nb_pays ?? 40, 'suffix' => '',  'label' => 'Pays représentés',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/>',
                    ],
                    [
                        'val' => optional($event->exposant->partenaires ?? null)->count() ?: 350, 'suffix' => '+', 'label' => 'Partenaires',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>',
                    ],
                ];
            @endphp
            <section aria-label="Chiffres clés">
                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:2rem;">
                    <div style="width:3px;height:2rem;border-radius:2px;background:linear-gradient(to bottom,var(--gold),var(--gold-light));flex-shrink:0;" aria-hidden="true"></div>
                    <h2 class="font-display" style="font-size:1.85rem;color:var(--blue-night);margin:0;">En chiffres</h2>
                </div>
                <div class="stats-grid">
                    @foreach($stats as $stat)
                    <div class="stat-card" style="border-radius:18px;padding:1.4rem 1rem;text-align:center;">
                        <div style="width:2.5rem;height:2.5rem;border-radius:12px;background:var(--blue-soft);display:flex;align-items:center;justify-content:center;margin:0 auto .75rem;">
                            <svg style="width:1.2rem;height:1.2rem;color:var(--blue);" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24" aria-hidden="true">{!! $stat['icon'] !!}</svg>
                        </div>
                        <p class="font-display stat-counter" data-target="{{ $stat['val'] }}" style="font-size:1.9rem;margin:0 0 .2rem;color:var(--blue-night);">0<span style="color:var(--gold);">{{ $stat['suffix'] }}</span></p>
                        <p style="font-size:.72rem;color:var(--gray-mid);line-height:1.3;margin:0;">{{ $stat['label'] }}</p>
                    </div>
                    @endforeach
                </div>
            </section>


            {{-- ══════════════ 4. Infos pratiques ══════════════ --}}
            <section class="reveal d1" x-intersect.once="$el.classList.add('on')" aria-label="Informations pratiques">
                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.75rem;">
                    <div style="width:3px;height:2rem;border-radius:2px;background:linear-gradient(to bottom,var(--blue),var(--blue-dark));flex-shrink:0;" aria-hidden="true"></div>
                    <h2 class="font-display" style="font-size:1.85rem;color:var(--blue-night);margin:0;">Informations pratiques</h2>
                </div>
                <div class="practical-grid">

                    <div class="card-lift practical-card">
                        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem;">
                            <div style="width:2.75rem;height:2.75rem;border-radius:12px;background:var(--blue-soft);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <svg style="width:1.25rem;height:1.25rem;color:var(--blue);" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75M8.25 12h.008v.008H8.25V12Zm3 0h.008v.008H11.25V12Zm3 0h.008v.008H14.25V12Z"/></svg>
                            </div>
                            <span style="font-size:.7rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--gray-mid);">Début</span>
                        </div>
                        <div style="font-weight:700;font-size:1.1rem;color:var(--blue-night);margin-bottom:.25rem;">{{ \Carbon\Carbon::parse($event->date_debut)->translatedFormat('l d M') }}</div>
                        <div style="font-size:.825rem;color:var(--gray-mid);">{{ \Carbon\Carbon::parse($event->date_debut)->translatedFormat('Y') }}</div>
                    </div>

                    <div class="card-lift practical-card">
                        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem;">
                            <div style="width:2.75rem;height:2.75rem;border-radius:12px;background:var(--blue-soft);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <svg style="width:1.25rem;height:1.25rem;color:var(--blue);" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            </div>
                            <span style="font-size:.7rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--gray-mid);">Fin</span>
                        </div>
                        <div style="font-weight:700;font-size:1.1rem;color:var(--blue-night);margin-bottom:.25rem;">{{ \Carbon\Carbon::parse($event->date_fin)->translatedFormat('l d M') }}</div>
                        <div style="font-size:.825rem;color:var(--gray-mid);">{{ \Carbon\Carbon::parse($event->date_fin)->translatedFormat('Y') }}</div>
                    </div>

                    <div class="card-lift practical-card">
                        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem;">
                            <div style="width:2.75rem;height:2.75rem;border-radius:12px;background:var(--blue-soft);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <svg style="width:1.25rem;height:1.25rem;color:var(--blue);" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                            </div>
                            <span style="font-size:.7rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--gray-mid);">Lieu</span>
                        </div>
                        <div style="font-weight:700;font-size:1.05rem;color:var(--blue-night);margin-bottom:.25rem;line-height:1.35;">{{ $event->lieu }}</div>
                        <a href="https://maps.google.com/?q={{ urlencode($event->lieu) }}" target="_blank" rel="noopener noreferrer"
                           style="font-size:.78rem;font-weight:600;color:var(--blue);text-decoration:none;display:inline-flex;align-items:center;gap:.3rem;margin-top:.5rem;"
                           onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                            Voir sur la carte
                            <svg style="width:.7rem;height:.7rem;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                        </a>
                    </div>

                    <div class="card-lift practical-card" style="background:linear-gradient(135deg,var(--blue),var(--blue-dark));border:none;">
                        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem;">
                            <div style="width:2.75rem;height:2.75rem;border-radius:12px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <svg style="width:1.25rem;height:1.25rem;color:white;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            </div>
                            <span style="font-size:.7rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:rgba(255,255,255,.65);">Durée</span>
                        </div>
                        <div class="font-display" style="font-size:2.5rem;color:white;line-height:1;margin-bottom:.25rem;">{{ $duree }}</div>
                        <div style="font-size:.875rem;color:rgba(255,255,255,.7);">jour{{ $duree > 1 ? 's' : '' }} d'événement</div>
                    </div>
                </div>
            </section>


            {{-- ══════════════ Galerie masonry ══════════════ --}}
            @php $galerie = $event->galerie ?? collect(); @endphp
            @if($galerie->count())
            <section aria-label="Galerie photos">
                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.75rem;">
                    <div style="width:3px;height:2rem;border-radius:2px;background:linear-gradient(to bottom,var(--blue),var(--blue-dark));flex-shrink:0;" aria-hidden="true"></div>
                    <h2 class="font-display" style="font-size:1.85rem;color:var(--blue-night);margin:0;">Galerie</h2>
                </div>
                <div class="masonry">
                    @foreach($galerie as $i => $photo)
                    <div class="masonry-item">
                        <button type="button" @click="lightboxOpen = true; lightboxSrc = '{{ $photo->url }}'; lightboxAlt = 'Photo {{ $i + 1 }}'"
                                class="gallery-tile" style="position:relative;display:block;width:100%;border-radius:18px;overflow:hidden;border:none;padding:0;cursor:pointer;" aria-label="Agrandir la photo {{ $i + 1 }}">
                            <img src="{{ $photo->url }}" alt="Photo {{ $i + 1 }}" style="width:100%;height:auto;display:block;object-fit:cover;" loading="lazy">
                            <div class="gallery-overlay" style="position:absolute;inset:0;display:flex;align-items:flex-end;padding:1rem;" aria-hidden="true">
                                <span style="display:inline-flex;align-items:center;gap:.4rem;color:white;font-size:.72rem;font-weight:600;">
                                    <svg style="width:.9rem;height:.9rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607zM10.5 7.5v6m3-3h-6"/></svg>
                                    Agrandir
                                </span>
                            </div>
                        </button>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif


            {{-- ══════════════ 5. Organisateur ══════════════ --}}
            <section class="reveal d2" x-intersect.once="$el.classList.add('on')" aria-label="Exposant et organisateur">
                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.75rem;">
                    <div style="width:3px;height:2rem;border-radius:2px;background:linear-gradient(to bottom,var(--blue),var(--blue-dark));flex-shrink:0;" aria-hidden="true"></div>
                    <h2 class="font-display" style="font-size:1.85rem;color:var(--blue-night);margin:0;">Organisateur</h2>
                </div>

                @if($event->exposant)
                <div class="card-premium org-card">
                    <div style="display:flex;flex-wrap:wrap;gap:1.5rem;align-items:flex-start;">
                        <div style="flex-shrink:0;">
                            @if($event->exposant->logo)
                                <img src="{{ $event->exposant->logo }}" alt="Logo {{ $event->exposant->nom_entreprise }}" style="width:5rem;height:5rem;border-radius:50%;object-fit:cover;border:3px solid white;box-shadow:var(--shadow-sm);">
                            @else
                                <div style="width:5rem;height:5rem;border-radius:50%;background:linear-gradient(135deg,var(--blue),var(--blue-dark));display:flex;align-items:center;justify-content:center;border:3px solid white;box-shadow:var(--shadow-sm);">
                                    <span class="font-display" style="font-size:1.75rem;color:white;font-weight:700;">{{ strtoupper(substr($event->exposant->nom_entreprise ?? 'E', 0, 1)) }}</span>
                                </div>
                            @endif
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="display:flex;flex-wrap:wrap;align-items:center;gap:.75rem;margin-bottom:.5rem;">
                                <h3 style="font-weight:700;font-size:1.2rem;color:var(--blue-night);margin:0;">{{ $event->exposant->nom_entreprise }}</h3>
                                @if($event->exposant->secteur_activite)
                                <span style="font-size:.72rem;font-weight:600;padding:.2rem .7rem;border-radius:99px;background:var(--blue-soft);color:var(--blue);">{{ $event->exposant->secteur_activite }}</span>
                                @endif
                            </div>
                            @if($event->exposant->responsable)
                            <p style="font-size:.9rem;color:var(--gray-mid);margin:0 0 1.25rem;display:flex;align-items:center;gap:.5rem;">
                                <svg style="width:.9rem;height:.9rem;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                                {{ $event->exposant->responsable }}
                            </p>
                            @endif
                            <div style="display:flex;flex-wrap:wrap;gap:.75rem;">
                                @if($event->exposant->telephone)
                                <a href="tel:{{ $event->exposant->telephone }}" style="display:inline-flex;align-items:center;gap:.5rem;font-size:.825rem;font-weight:500;color:var(--gray-dark);text-decoration:none;padding:.5rem .9rem;border-radius:10px;background:white;border:1px solid var(--gray-soft);transition:border-color .2s,color .2s;"
                                   onmouseover="this.style.borderColor='var(--blue)';this.style.color='var(--blue)'" onmouseout="this.style.borderColor='var(--gray-soft)';this.style.color='var(--gray-dark)'">
                                    <svg style="width:.9rem;height:.9rem;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 6z"/></svg>
                                    {{ $event->exposant->telephone }}
                                </a>
                                @endif
                                @if($event->exposant->email)
                                <a href="mailto:{{ $event->exposant->email }}" style="display:inline-flex;align-items:center;gap:.5rem;font-size:.825rem;font-weight:500;color:var(--gray-dark);text-decoration:none;padding:.5rem .9rem;border-radius:10px;background:white;border:1px solid var(--gray-soft);transition:border-color .2s,color .2s;"
                                   onmouseover="this.style.borderColor='var(--blue)';this.style.color='var(--blue)'" onmouseout="this.style.borderColor='var(--gray-soft)';this.style.color='var(--gray-dark)'">
                                    <svg style="width:.9rem;height:.9rem;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                                    {{--{{ $event->exposant->email }} --}}
                                </a>
                                @endif
                                @if($event->exposant->site_web)
                                <a href="{{ $event->exposant->site_web }}" target="_blank" rel="noopener noreferrer" class="btn-glow" style="display:inline-flex;align-items:center;gap:.5rem;font-size:.825rem;font-weight:600;color:white;text-decoration:none;padding:.5rem .9rem;border-radius:10px;background:var(--blue);transition:filter .2s;"
                                   onmouseover="this.style.filter='brightness(1.1)'" onmouseout="this.style.filter='none'">
                                    <svg style="width:.9rem;height:.9rem;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3"/></svg>
                                    Site web
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="card-premium org-card">
                    <div style="display:flex;flex-wrap:wrap;gap:1.5rem;align-items:center;">
                        <div style="width:5rem;height:5rem;border-radius:50%;background:linear-gradient(135deg,var(--blue),var(--blue-dark));display:flex;align-items:center;justify-content:center;border:3px solid white;box-shadow:var(--shadow-sm);flex-shrink:0;">
                            <svg style="width:2rem;height:2rem;color:white;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>
                        </div>
                        <div>
                            <h3 style="font-weight:700;font-size:1.2rem;color:var(--blue-night);margin:0 0 .35rem;">ExpoDakar</h3>
                            <p style="font-size:.875rem;color:var(--gray-mid);margin:0 0 .75rem;">Organisateur principal</p>
                            <a href="mailto:contact@expodakar.sn" style="font-size:.825rem;font-weight:600;color:var(--blue);text-decoration:none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">contact@expodakar.sn</a>
                        </div>
                    </div>
                </div>
                @endif
            </section>


            {{-- ══════════════ Timeline exposant ══════════════ --}}
            @php
                $timeline = [
                    ['annee' => $event->exposant->annee_creation ?? '2001', 'titre' => 'Création', 'texte' => "Fondation de l'entreprise et premiers pas sur le marché sénégalais."],
                    ['annee' => $event->exposant->annee_premiere_expo ?? '2005', 'titre' => 'Première exposition', 'texte' => "Participation au tout premier salon professionnel, point de départ d'une présence continue."],
                    ['annee' => $event->exposant->annee_expansion ?? '2014', 'titre' => 'Expansion', 'texte' => "Ouverture à de nouveaux marchés régionaux et renforcement du réseau de partenaires."],
                    ['annee' => 'Aujourd\'hui', 'titre' => "Aujourd'hui", 'texte' => "Un acteur reconnu, présent sur les plus grands salons professionnels d'Afrique de l'Ouest."],
                ];
            @endphp
            <section aria-label="Parcours de l'exposant" id="timelineSection">
                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:2rem;">
                    <div style="width:3px;height:2rem;border-radius:2px;background:linear-gradient(to bottom,var(--gold),var(--gold-light));flex-shrink:0;" aria-hidden="true"></div>
                    <h2 class="font-display" style="font-size:1.85rem;color:var(--blue-night);margin:0;">Le parcours</h2>
                </div>
                <div style="position:relative;padding-left:0;">
                    <div class="timeline-line" aria-hidden="true"></div>
                    <div style="display:flex;flex-direction:column;gap:2.25rem;">
                        @foreach($timeline as $step)
                        <div class="timeline-step" style="display:flex;gap:1.5rem;align-items:flex-start;opacity:0;transform:translateX(-16px);">
                            <div class="timeline-dot">
                                <span class="font-mono" style="font-size:.6rem;font-weight:700;color:var(--gold);">{{ $loop->iteration }}</span>
                            </div>
                            <div style="padding-top:.15rem;min-width:0;">
                                <p class="font-mono" style="font-size:.7rem;letter-spacing:.1em;color:var(--gold);margin:0 0 .25rem;text-transform:uppercase;">{{ $step['annee'] }}</p>
                                <h3 style="font-size:1.1rem;font-weight:700;color:var(--blue-night);margin:0 0 .4rem;">{{ $step['titre'] }}</h3>
                                <p style="font-size:.9rem;color:var(--gray-mid);line-height:1.6;margin:0;max-width:48ch;">{{ $step['texte'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>


            {{-- ══════════════ Autres événements de l'exposant ══════════════ --}}
            @php
                $autresEvenements = $event->exposant
                    ? optional($event->exposant->evenements ?? null)->where('id', '!=', $event->id)
                    : null;
            @endphp
            @if($autresEvenements && $autresEvenements->count())
            <section aria-label="Autres événements de l'exposant">
                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.75rem;">
                    <div style="width:3px;height:2rem;border-radius:2px;background:linear-gradient(to bottom,var(--blue),var(--blue-dark));flex-shrink:0;" aria-hidden="true"></div>
                    <h2 class="font-display" style="font-size:1.85rem;color:var(--blue-night);margin:0;">Autres événements de {{ $event->exposant->nom_entreprise }}</h2>
                </div>
                <div class="autres-grid">
                    @foreach($autresEvenements->take(4) as $autre)
                    <a href="{{ route('events.show', $autre->id) }}" class="card-premium" style="display:block;text-decoration:none;overflow:hidden;border:1px solid var(--gray-soft);background:white;box-shadow:var(--shadow-sm);">
                        <div style="height:140px;overflow:hidden;position:relative;">
                            @if($autre->image)
                                <img src="{{ Storage::url($autre->image) }}" alt="{{ $autre->titre }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <div style="width:100%;height:100%;background:linear-gradient(135deg,var(--blue-night),var(--blue));"></div>
                            @endif
                        </div>
                        <div style="padding:1.25rem;">
                            <p style="font-size:.7rem;color:var(--gray-mid);margin:0 0 .4rem;text-transform:uppercase;letter-spacing:.06em;">{{ \Carbon\Carbon::parse($autre->date_debut)->translatedFormat('d M Y') }}</p>
                            <h3 style="font-size:.95rem;font-weight:700;color:var(--blue-night);margin:0;line-height:1.35;">{{ Str::limit($autre->titre, 55) }}</h3>
                        </div>
                    </a>
                    @endforeach
                </div>
            </section>
            @endif


            {{-- ══════════════ 7 & 8. Partage social ══════════════ --}}
            <section class="reveal d3" x-intersect.once="$el.classList.add('on')" aria-label="Partager cet événement"
                x-data="{ open: false, copied: false, pageUrl: window.location.href, copyLink() { navigator.clipboard.writeText(this.pageUrl).then(() => { this.copied = true; setTimeout(() => this.copied = false, 2200); }); } }">
                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.75rem;">
                    <div style="width:3px;height:2rem;border-radius:2px;background:linear-gradient(to bottom,var(--blue),var(--blue-dark));flex-shrink:0;" aria-hidden="true"></div>
                    <h2 class="font-display" style="font-size:1.85rem;color:var(--blue-night);margin:0;">Partager l'événement</h2>
                </div>

                <div class="share-card">
                    <p style="font-size:.9rem;color:var(--gray-mid);margin:0 0 1.5rem;">Partagez cet événement avec vos contacts et votre réseau professionnel.</p>
                    <div class="share-grid">
                        <a :href="`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(pageUrl)}`" target="_blank" rel="noopener noreferrer" class="share-btn" style="background:#1877F2;flex-direction:column;gap:.4rem;padding:.9rem .5rem;" aria-label="Partager sur Facebook">
                            <svg style="width:1.2rem;height:1.2rem;" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            <span style="font-size:.7rem;">Facebook</span>
                        </a>
                        <a :href="`https://twitter.com/intent/tweet?url=${encodeURIComponent(pageUrl)}&text=${encodeURIComponent('{{ addslashes($event->titre) }} – ExpoDakar')}`" target="_blank" rel="noopener noreferrer" class="share-btn" style="background:#000;flex-direction:column;gap:.4rem;padding:.9rem .5rem;" aria-label="Partager sur X (Twitter)">
                            <svg style="width:1.2rem;height:1.2rem;" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            <span style="font-size:.7rem;">Twitter</span>
                        </a>
                        <a :href="`https://wa.me/?text=${encodeURIComponent('{{ addslashes($event->titre) }} – ' + pageUrl)}`" target="_blank" rel="noopener noreferrer" class="share-btn" style="background:#25D366;flex-direction:column;gap:.4rem;padding:.9rem .5rem;" aria-label="Partager sur WhatsApp">
                            <svg style="width:1.2rem;height:1.2rem;" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/></svg>
                            <span style="font-size:.7rem;">WhatsApp</span>
                        </a>
                        <a :href="`https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(pageUrl)}`" target="_blank" rel="noopener noreferrer" class="share-btn" style="background:#0A66C2;flex-direction:column;gap:.4rem;padding:.9rem .5rem;" aria-label="Partager sur LinkedIn">
                            <svg style="width:1.2rem;height:1.2rem;" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            <span style="font-size:.7rem;">LinkedIn</span>
                        </a>
                    </div>
                    <div style="margin-top:1rem;display:flex;gap:.75rem;align-items:center;flex-wrap:wrap;">
                        <div style="flex:1;min-width:0;padding:.7rem 1rem;border-radius:12px;background:white;border:1px solid var(--gray-soft);font-size:.8rem;color:var(--gray-mid);overflow:hidden;white-space:nowrap;text-overflow:ellipsis;"><span x-text="pageUrl"></span></div>
                        <button @click="copyLink()" class="btn-premium" style="flex-shrink:0;padding:.7rem 1.1rem;border-radius:12px;font-size:.8rem;font-weight:600;" :style="copied ? 'background:var(--success);color:white;' : 'background:var(--blue-soft);color:var(--blue);'" aria-label="Copier le lien">
                            <span x-show="!copied">Copier</span>
                            <span x-show="copied" x-cloak style="display:flex;align-items:center;gap:.35rem;"><svg style="width:.85rem;height:.85rem;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>Copié !</span>
                        </button>
                    </div>
                </div>
            </section>


            {{-- ══════════════ Témoignages ══════════════ --}}
            @php
                $temoignages = $event->exposant->temoignages ?? collect([
                    (object)['nom' => 'Awa Diallo',  'role' => 'Responsable achats, SenCom', 'note' => 5, 'texte' => "Un accompagnement rigoureux du premier échange jusqu'au montage du stand. La qualité de présentation était irréprochable."],
                    (object)['nom' => 'Moussa Fall',  'role' => 'Directeur, Fall Industries', 'note' => 5, 'texte' => "Une équipe réactive et un réel sens du détail. Nos objectifs de visibilité ont été largement atteints."],
                    (object)['nom' => 'Fatou Sarr',   'role' => 'Chargée de partenariats',   'note' => 4, 'texte' => "Professionnalisme et clarté dans les échanges. Nous reconduirons la collaboration sans hésiter."],
                ]);
                $noteMoyenne = round(collect($temoignages)->avg('note'), 1);
            @endphp
            <section aria-label="Témoignages" x-data="{ active: 0, count: {{ count($temoignages) }} }">
                <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.75rem;">
                    <div style="display:flex;align-items:center;gap:1rem;">
                        <div style="width:3px;height:2rem;border-radius:2px;background:linear-gradient(to bottom,var(--gold),var(--gold-light));flex-shrink:0;" aria-hidden="true"></div>
                        <h2 class="font-display" style="font-size:1.85rem;color:var(--blue-night);margin:0;">Témoignages</h2>
                    </div>
                    <div style="display:flex;align-items:center;gap:.5rem;">
                        <span class="font-display" style="font-size:1.5rem;color:var(--blue-night);">{{ $noteMoyenne }}</span>
                        <div style="display:flex;gap:.15rem;" aria-hidden="true">
                            @for($s = 1; $s <= 5; $s++)<svg style="width:1rem;height:1rem;" fill="{{ $s <= round($noteMoyenne) ? 'var(--gold)' : '#E5E7EB' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.286 3.957c.3.922-.755 1.688-1.539 1.118l-3.367-2.447a1 1 0 00-1.175 0l-3.367 2.447c-.784.57-1.838-.196-1.539-1.118l1.286-3.957a1 1 0 00-.363-1.118L2.063 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.285-3.958z"/></svg>@endfor
                        </div>
                    </div>
                </div>
                <div style="position:relative;overflow:hidden;">
                    <div style="display:flex;transition:transform .5s ease-out;" :style="`transform: translateX(-${active * 100}%)`">
                        @foreach($temoignages as $t)
                        <div style="width:100%;flex-shrink:0;padding:0 .25rem;">
                            <div style="border-radius:20px;padding:2rem;border:1px solid var(--gray-soft);background:var(--pearl);">
                                <div style="display:flex;gap:.15rem;margin-bottom:1rem;" aria-hidden="true">
                                    @for($s = 1; $s <= 5; $s++)<svg style="width:1rem;height:1rem;" fill="{{ $s <= $t->note ? 'var(--gold)' : '#E5E7EB' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.286 3.957c.3.922-.755 1.688-1.539 1.118l-3.367-2.447a1 1 0 00-1.175 0l-3.367 2.447c-.784.57-1.838-.196-1.539-1.118l1.286-3.957a1 1 0 00-.363-1.118L2.063 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.285-3.958z"/></svg>@endfor
                                </div>
                                <p style="font-size:1.02rem;line-height:1.75;color:var(--gray-dark);margin:0 0 1.5rem;">« {{ $t->texte }} »</p>
                                <div style="display:flex;align-items:center;gap:.75rem;">
                                    <div style="width:2.75rem;height:2.75rem;border-radius:50%;background:linear-gradient(135deg,var(--blue),var(--blue-dark));display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:.9rem;">{{ strtoupper(substr($t->nom, 0, 1)) }}</div>
                                    <div><p style="font-size:.9rem;font-weight:700;color:var(--blue-night);margin:0;">{{ $t->nom }}</p><p style="font-size:.78rem;color:var(--gray-mid);margin:0;">{{ $t->role }}</p></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div style="display:flex;align-items:center;justify-content:center;gap:.5rem;margin-top:1.5rem;">
                        <template x-for="i in count" :key="i">
                            <button @click="active = i - 1" style="height:.35rem;border-radius:99px;transition:all .3s;border:none;cursor:pointer;" :style="active === i - 1 ? 'width:1.5rem;background:var(--gold)' : 'width:.35rem;background:var(--gray-soft)'" :aria-label="`Témoignage ${i}`"></button>
                        </template>
                    </div>
                </div>
            </section>

        </div>
        {{-- /COLONNE GAUCHE --}}


        {{-- ────────────────────────────────────────────────────
             COLONNE DROITE – Sidebar sticky
             ──────────────────────────────────────────────────── --}}
        <aside aria-label="Réservation">
            <div class="sidebar-sticky" style="display:flex;flex-direction:column;gap:1.25rem;">

                {{-- Card réservation --}}
                <div class="card-premium" style="overflow:hidden;box-shadow:var(--shadow-md);border:1px solid var(--gray-soft);">
                    <div style="padding:1.5rem 1.75rem;background:linear-gradient(135deg,var(--blue),var(--blue-dark));">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem;flex-wrap:wrap;gap:.5rem;">
                            <span class="badge-status {{ $statusClass }}" style="font-size:.68rem;">
                                <span style="width:.4rem;height:.4rem;border-radius:50%;background:{{ $statusDot }};display:inline-block;" aria-hidden="true"></span>{{ $statusLabel }}
                            </span>
                            @if($event->categorie)<span style="font-size:.7rem;color:rgba(255,255,255,.65);">{{ $event->categorie->nom }}</span>@endif
                        </div>
                        <div class="font-display" style="font-size:1.35rem;color:white;line-height:1.3;">{{ Str::limit($event->titre, 55) }}</div>
                    </div>

                    <div style="padding:1.5rem 1.75rem;background:white;">
                        <div style="display:flex;flex-direction:column;gap:.85rem;margin-bottom:1.5rem;">
                            <div style="display:flex;align-items:center;gap:.75rem;">
                                <div style="width:2rem;height:2rem;border-radius:8px;background:var(--blue-soft);display:flex;align-items:center;justify-content:center;flex-shrink:0;"><svg style="width:.9rem;height:.9rem;color:var(--blue);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5"/></svg></div>
                                <div><div style="font-size:.7rem;color:var(--gray-mid);font-weight:500;">Date</div><div style="font-size:.875rem;font-weight:600;color:var(--blue-night);">{{ \Carbon\Carbon::parse($event->date_debut)->translatedFormat('d M Y') }}</div></div>
                            </div>
                            <hr class="sep">
                            <div style="display:flex;align-items:center;gap:.75rem;">
                                <div style="width:2rem;height:2rem;border-radius:8px;background:var(--blue-soft);display:flex;align-items:center;justify-content:center;flex-shrink:0;"><svg style="width:.9rem;height:.9rem;color:var(--blue);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg></div>
                                <div><div style="font-size:.7rem;color:var(--gray-mid);font-weight:500;">Lieu</div><div style="font-size:.875rem;font-weight:600;color:var(--blue-night);">{{ Str::limit($event->lieu, 32) }}</div></div>
                            </div>
                            <hr class="sep">
                            <div>
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.6rem;"><span style="font-size:.8rem;font-weight:500;color:var(--gray-mid);">Participants inscrits</span><span style="font-size:.8rem;font-weight:700;color:var(--blue);">248 / 500</span></div>
                                <div style="height:.4rem;border-radius:99px;background:var(--gray-soft);overflow:hidden;"><div style="width:0;height:100%;border-radius:99px;background:linear-gradient(to right,var(--blue),var(--blue-dark));transition:width 1s ease;" x-data="{}" x-init="setTimeout(() => $el.style.width = '49.6%', 300)"></div></div>
                                <p style="font-size:.72rem;color:var(--gray-mid);margin:.5rem 0 0;">252 places restantes</p>
                            </div>
                        </div>

                        @if($statusLabel !== 'Terminé')
                        <a href="{{ route('reservations.create', $event->id) }}" class="btn-premium btn-glow" onclick="createRipple(event)"
                           style="display:flex;width:100%;padding:1rem;border-radius:14px;text-align:center;justify-content:center;font-weight:700;font-size:1rem;color:white;text-decoration:none;background:linear-gradient(135deg,var(--blue),var(--blue-dark));box-shadow:0 8px 24px rgba(30,95,216,.35);"
                           onmouseover="this.style.filter='brightness(1.08)'" onmouseout="this.style.filter='none'">
                            Réserver ma place
                        </a>
                        @else
                        <div style="display:block;width:100%;padding:1rem;border-radius:14px;text-align:center;font-weight:700;font-size:1rem;color:var(--gray-mid);background:var(--gray-soft);cursor:not-allowed;">Événement terminé</div>
                        @endif
                        <p style="display:flex;align-items:center;justify-content:center;gap:.35rem;font-size:.75rem;color:var(--gray-mid);text-align:center;margin:.75rem 0 0;">
                            <svg style="width:.85rem;height:.85rem;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                            Inscription sécurisée · Billet QR par email
                        </p>
                    </div>
                </div>

                {{-- Card : accès rapide + QR --}}
                <div class="card-premium sidebar-card">
                    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.1rem;flex-wrap:wrap;">
                        <div style="padding:.4rem;border-radius:10px;border:1.5px dashed rgba(201,168,76,.55);background:var(--pearl);flex-shrink:0;">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=76x76&margin=0&color=0A1628&data={{ urlencode(request()->fullUrl()) }}" alt="QR code de la page événement" width="64" height="64" loading="lazy">
                        </div>
                        <div style="min-width:0;">
                            <p class="font-mono" style="font-size:.68rem;font-weight:700;letter-spacing:.08em;color:var(--blue-night);margin:0 0 .2rem;text-transform:uppercase;">Accès rapide</p>
                            <p style="font-size:.75rem;color:var(--gray-mid);margin:0;line-height:1.4;">Scannez pour ouvrir cette page sur mobile</p>
                        </div>
                    </div>
                    <a href="https://maps.google.com/?q={{ urlencode($event->lieu) }}" target="_blank" rel="noopener noreferrer" class="btn-premium" style="display:flex;width:100%;align-items:center;justify-content:center;gap:.5rem;padding:.75rem;border-radius:12px;font-size:.82rem;font-weight:600;color:var(--blue-night);background:var(--pearl);border:1px solid var(--gray-soft);text-decoration:none;margin-bottom:.6rem;">
                        <svg style="width:.95rem;height:.95rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                        Ouvrir dans Google Maps
                    </a>
                    @if($event->exposant->whatsapp ?? $event->exposant->telephone ?? null)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $event->exposant->whatsapp ?? $event->exposant->telephone) }}" target="_blank" rel="noopener noreferrer" class="btn-premium" style="display:flex;width:100%;align-items:center;justify-content:center;gap:.5rem;padding:.75rem;border-radius:12px;font-size:.82rem;font-weight:600;color:white;background:#25D366;text-decoration:none;margin-bottom:.6rem;">
                        <svg style="width:.95rem;height:.95rem;" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/></svg>
                        WhatsApp
                    </a>
                    @endif
                    @if($event->brochure ?? $event->exposant->brochure ?? null)
                    <a href="{{ Storage::url($event->brochure ?? $event->exposant->brochure) }}" target="_blank" rel="noopener noreferrer" class="btn-premium" style="display:flex;width:100%;align-items:center;justify-content:center;gap:.5rem;padding:.75rem;border-radius:12px;font-size:.82rem;font-weight:600;color:var(--blue-night);background:var(--pearl);border:1px solid var(--gray-soft);text-decoration:none;margin-bottom:.6rem;">
                        <svg style="width:.95rem;height:.95rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                        Brochure de présentation
                    </a>
                    @endif
                    @if($event->catalogue ?? null)
                    <a href="{{ Storage::url($event->catalogue) }}" target="_blank" rel="noopener noreferrer" class="btn-premium" style="display:flex;width:100%;align-items:center;justify-content:center;gap:.5rem;padding:.75rem;border-radius:12px;font-size:.82rem;font-weight:600;color:var(--blue-night);background:var(--pearl);border:1px solid var(--gray-soft);text-decoration:none;margin-bottom:.6rem;">
                        <svg style="width:.95rem;height:.95rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                        Catalogue produits
                    </a>
                    @endif
                    <a href="mailto:{{ $event->exposant->email ?? 'contact@expodakar.sn' }}?subject={{ urlencode('Demande de rendez-vous – '.$event->titre) }}" class="btn-premium btn-glow" style="display:flex;width:100%;align-items:center;justify-content:center;gap:.5rem;padding:.75rem;border-radius:12px;font-size:.82rem;font-weight:700;color:white;background:linear-gradient(135deg,var(--gold),var(--gold-light));text-decoration:none;">
                        <svg style="width:.95rem;height:.95rem;color:var(--blue-night);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                        <span style="color:var(--blue-night);">Prendre rendez-vous</span>
                    </a>
                </div>

                {{-- Card : partage rapide + réseaux --}}
                <div class="card-premium sidebar-card" style="padding:1.25rem 1.5rem;" x-data="{ pageUrl: window.location.href }">
                    <p style="font-size:.75rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gray-mid);margin:0 0 .9rem;">Suivre &amp; partager</p>
                    <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                        <a :href="`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(pageUrl)}`" target="_blank" rel="noopener noreferrer" style="flex:1;min-width:2.4rem;display:flex;align-items:center;justify-content:center;height:2.5rem;border-radius:10px;background:#1877F2;color:white;" aria-label="Facebook"><svg style="width:1rem;height:1rem;" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                        <a href="#" style="flex:1;min-width:2.4rem;display:flex;align-items:center;justify-content:center;height:2.5rem;border-radius:10px;background:linear-gradient(45deg,#F58529,#DD2A7B,#8134AF,#515BD4);color:white;" aria-label="Instagram"><svg style="width:1rem;height:1rem;" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.204-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12s.014 3.668.072 4.948c.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072s3.668-.014 4.948-.072c4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0z"/></svg></a>
                        <a :href="`https://wa.me/?text=${encodeURIComponent('{{ addslashes($event->titre) }} – ' + pageUrl)}`" target="_blank" rel="noopener noreferrer" style="flex:1;min-width:2.4rem;display:flex;align-items:center;justify-content:center;height:2.5rem;border-radius:10px;background:#25D366;color:white;" aria-label="WhatsApp"><svg style="width:1rem;height:1rem;" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/></svg></a>
                        <a :href="`https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(pageUrl)}`" target="_blank" rel="noopener noreferrer" style="flex:1;min-width:2.4rem;display:flex;align-items:center;justify-content:center;height:2.5rem;border-radius:10px;background:#0A66C2;color:white;" aria-label="LinkedIn"><svg style="width:1rem;height:1rem;" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg></a>
                    </div>
                </div>

                {{-- Card : aide --}}
                <div style="padding:1.25rem 1.5rem;border-radius:18px;border:1px solid var(--gray-soft);background:var(--pearl);">
                    <div style="display:flex;gap:.75rem;align-items:flex-start;">
                        <div style="width:2.25rem;height:2.25rem;border-radius:10px;background:var(--blue-soft);display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:.1rem;"><svg style="width:1.1rem;height:1.1rem;color:var(--blue);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z"/></svg></div>
                        <div>
                            <p style="font-size:.825rem;font-weight:600;color:var(--blue-night);margin:0 0 .3rem;">Besoin d'aide ?</p>
                            <p style="font-size:.78rem;color:var(--gray-mid);margin:0 0 .6rem;line-height:1.5;">Notre équipe est disponible pour répondre à vos questions.</p>
                            <a href="mailto:contact@expodakar.sn" style="font-size:.78rem;font-weight:600;color:var(--blue);text-decoration:none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">contact@expodakar.sn</a>
                        </div>
                    </div>
                </div>

            </div>
        </aside>

    </div>
</div>


{{-- ══════════════════════════════════════════════════════════════
     Partenaires
     ══════════════════════════════════════════════════════════════ --}}
@php
    $partenaires = $event->exposant->partenaires ?? collect([
        (object)['nom' => 'SenCom', 'logo' => null], (object)['nom' => 'Fall Industries', 'logo' => null],
        (object)['nom' => 'ODK Group', 'logo' => null], (object)['nom' => 'Teranga Corp', 'logo' => null],
        (object)['nom' => 'Baobab Invest', 'logo' => null], (object)['nom' => 'Sahel Logistics', 'logo' => null],
    ]);
@endphp
<section style="padding:4rem 1.5rem;background:var(--pearl);" aria-label="Partenaires">
    <div style="max-width:80rem;margin:0 auto;">
        <div style="text-align:center;margin-bottom:2.5rem;">
            <p class="font-mono" style="font-size:.7rem;letter-spacing:.16em;text-transform:uppercase;color:var(--gold);margin:0 0 .5rem;">Ils leur font confiance</p>
            <h2 class="font-display" style="font-size:1.85rem;color:var(--blue-night);margin:0;">Partenaires</h2>
        </div>
        <div class="partners-grid">
            @foreach($partenaires as $p)
            <div style="display:flex;align-items:center;justify-content:center;height:4rem;">
                @if($p->logo ?? null)
                    <img src="{{ $p->logo }}" alt="{{ $p->nom }}" class="partner-logo" style="max-height:2.5rem;width:auto;object-fit:contain;">
                @else
                    <span class="partner-logo font-display" style="font-size:1.15rem;color:var(--blue-night);">{{ $p->nom }}</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>




{{-- ══════════════════════════════════════════════════════════════
     CTA FINAL img
     ══════════════════════════════════════════════════════════════ --}}
<section class="cta-section" aria-label="Appel à l'action">
    <div class="hero-noise" style="position:absolute;inset:0;" aria-hidden="true"></div>
    <div class="mockup-glow" style="width:420px;height:420px;top:-120px;left:-120px;background:var(--gold);opacity:.12;" aria-hidden="true"></div>
    <div class="mockup-glow" style="width:420px;height:420px;bottom:-120px;right:-120px;background:var(--blue);opacity:.2;" aria-hidden="true"></div>

    <div style="position:relative;z-index:1;max-width:46rem;margin:0 auto;text-align:center;">
        <p class="font-mono" style="font-size:.7rem;letter-spacing:.16em;text-transform:uppercase;color:rgba(255,255,255,.55);margin:0 0 1rem;">Ne manquez pas cet événement</p>
        <h2 class="font-display" style="font-size:clamp(2.25rem,4.5vw,3.25rem);color:white;line-height:1.15;margin:0 0 2rem;">Réservez votre place et vivez {{ Str::limit($event->titre, 40) }} en direct</h2>
        <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:center;gap:1rem;">
            @if($statusLabel !== 'Terminé')
            <a href="{{ route('reservations.create', $event->id) }}" class="btn-premium btn-glow" style="padding:1rem 2rem;border-radius:14px;font-size:.95rem;font-weight:700;color:var(--blue-night);text-decoration:none;background:linear-gradient(135deg,var(--gold),var(--gold-light));">Réserver ma place</a>
            @endif
            <a href="{{ route('events.index') }}" class="btn-premium" style="padding:1rem 2rem;border-radius:14px;font-size:.95rem;font-weight:600;color:white;text-decoration:none;border:1px solid rgba(255,255,255,.25);background:rgba(255,255,255,.05);">Découvrir d'autres événements</a>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     FOOTER PREMIUM — 4 colonnes
     ══════════════════════════════════════════════════════════════ --}}
<footer style="background:var(--blue-night);" role="contentinfo">
    <div class="footer-inner">
        <div class="footer-grid">

            <div>
                <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:.65rem;text-decoration:none;margin-bottom:1rem;" aria-label="ExpoDakar">
                    <img src="https://res.cloudinary.com/dstbqtuxm/image/upload/v1782085416/ChatGPT_Image_Jun_21__2026__07_24_51_PM-removebg-preview_zi77k0.png" alt="Logo ExpoDakar" style="height:2.25rem;width:auto;object-fit:contain;">
                    <span class="font-display" style="font-size:1.2rem;color:white;">Expo<span class="text-gold">Dakar</span></span>
                </a>
                <p style="font-size:.85rem;line-height:1.6;color:rgba(255,255,255,.5);max-width:22rem;margin:0;">La plateforme de référence pour les foires, salons et forums professionnels au Sénégal.</p>
            </div>

            <div>
                <p class="font-mono" style="font-size:.68rem;letter-spacing:.14em;text-transform:uppercase;color:rgba(255,255,255,.4);margin:0 0 1rem;">Navigation</p>
                <nav style="display:flex;flex-direction:column;gap:.65rem;" aria-label="Footer navigation">
                    <a href="{{ route('home') }}" style="font-size:.85rem;color:rgba(255,255,255,.6);text-decoration:none;">Accueil</a>
                    <a href="{{ route('events.index') }}" style="font-size:.85rem;color:rgba(255,255,255,.6);text-decoration:none;">Événements</a>
                    <a href="{{ route('exposants.index') }}" style="font-size:.85rem;color:rgba(255,255,255,.6);text-decoration:none;">Exposants</a>
                </nav>
            </div>

            <div>
                <p class="font-mono" style="font-size:.68rem;letter-spacing:.14em;text-transform:uppercase;color:rgba(255,255,255,.4);margin:0 0 1rem;">Réseaux</p>
                <div style="display:flex;gap:.65rem;">
                    <a href="#" aria-label="LinkedIn" style="width:2.25rem;height:2.25rem;border-radius:10px;display:flex;align-items:center;justify-content:center;border:1px solid rgba(255,255,255,.12);color:rgba(255,255,255,.6);"><svg style="width:1rem;height:1rem;" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg></a>
                    <a href="#" aria-label="Instagram" style="width:2.25rem;height:2.25rem;border-radius:10px;display:flex;align-items:center;justify-content:center;border:1px solid rgba(255,255,255,.12);color:rgba(255,255,255,.6);"><svg style="width:1rem;height:1rem;" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069z"/></svg></a>
                    <a href="#" aria-label="X" style="width:2.25rem;height:2.25rem;border-radius:10px;display:flex;align-items:center;justify-content:center;border:1px solid rgba(255,255,255,.12);color:rgba(255,255,255,.6);"><svg style="width:1rem;height:1rem;" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>
                </div>
            </div>

            <div>
                <p class="font-mono" style="font-size:.68rem;letter-spacing:.14em;text-transform:uppercase;color:rgba(255,255,255,.4);margin:0 0 1rem;">Restez informé</p>
                <form onsubmit="return false;" style="display:flex;gap:.5rem;flex-wrap:wrap;">
                    <label for="footer-newsletter" style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0,0,0,0);">Adresse email</label>
                    <input id="footer-newsletter" type="email" required placeholder="votre@email.com" style="flex:1;min-width:9rem;padding:.65rem 1rem;border-radius:12px;font-size:.85rem;color:white;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.12);">
                    <button type="submit" class="btn-premium btn-glow" style="flex-shrink:0;padding:.65rem 1rem;border-radius:12px;font-size:.85rem;font-weight:600;color:var(--blue-night);background:linear-gradient(135deg,var(--gold),var(--gold-light));">S'abonner</button>
                </form>
                <p style="font-size:.72rem;color:rgba(255,255,255,.35);margin:.7rem 0 0;">Un email par mois, zéro spam.</p>
            </div>
        </div>

        <hr style="border:none;border-top:1px solid rgba(255,255,255,.1);">
        <div style="padding-top:1.5rem;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:.75rem;">
            <p style="font-size:.75rem;color:rgba(255,255,255,.4);margin:0;">© {{ date('Y') }} ExpoDakar · Tous droits réservés</p>
            <p style="font-size:.75rem;color:rgba(255,255,255,.4);margin:0;">Fait avec soin à Dakar, Sénégal</p>
        </div>
    </div>
</footer>


{{-- ══════════════════════════════════════════════════════════════
     LIGHTBOX
     ══════════════════════════════════════════════════════════════ --}}
<div x-show="lightboxOpen" x-cloak x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     @keydown.escape.window="lightboxOpen = false"
     style="position:fixed;inset:0;z-index:100;display:flex;align-items:center;justify-content:center;padding:1.5rem;background:rgba(10,22,40,.92);" role="dialog" aria-modal="true" aria-label="Photo en grand format">
    <button @click="lightboxOpen = false" style="position:absolute;top:1.5rem;right:1.5rem;width:2.75rem;height:2.75rem;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;border:1px solid rgba(255,255,255,.2);background:none;cursor:pointer;" aria-label="Fermer">
        <svg style="width:1.25rem;height:1.25rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <img :src="lightboxSrc" :alt="lightboxAlt" @click.outside="lightboxOpen = false" style="max-width:100%;max-height:85vh;border-radius:18px;object-fit:contain;box-shadow:0 24px 60px rgba(0,0,0,.4);">
</div>


{{-- ══════════════════════════════════════════════════════════════
     SCRIPTS
     ══════════════════════════════════════════════════════════════ --}}
<script>
function createRipple(e) {
    const btn = e.currentTarget;
    const rect = btn.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const span = document.createElement('span');
    span.className = 'ripple';
    span.style.width = span.style.height = size + 'px';
    span.style.left = (e.clientX - rect.left - size / 2) + 'px';
    span.style.top  = (e.clientY - rect.top - size / 2) + 'px';
    btn.appendChild(span);
    setTimeout(() => span.remove(), 650);
}

document.addEventListener('DOMContentLoaded', () => {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Reveal on scroll (fallback, en plus de x-intersect)
    const revealEls = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window && revealEls.length) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach(entry => { if (entry.isIntersecting) { entry.target.classList.add('on'); io.unobserve(entry.target); } });
        }, { threshold: 0.1 });
        revealEls.forEach(el => io.observe(el));
    } else {
        revealEls.forEach(el => el.classList.add('on'));
    }

    // Ripple sur tous les boutons premium au clic
    document.querySelectorAll('.btn-premium').forEach((btn) => {
        btn.addEventListener('click', createRipple);
    });

    if (prefersReducedMotion) return;

    // Lenis smooth scroll (remplace le scroll-behavior CSS natif pour éviter tout à-coup)
    if (window.Lenis) {
        document.documentElement.style.scrollBehavior = 'auto';
        const lenis = new Lenis({ duration: 1.1, easing: (t) => 1 - Math.pow(1 - t, 3), smoothWheel: true, wheelMultiplier: 1 });
        function raf(time) { lenis.raf(time); requestAnimationFrame(raf); }
        requestAnimationFrame(raf);
        if (window.gsap && window.gsap.ticker) {
            gsap.ticker.add((time) => lenis.raf(time * 1000));
            gsap.ticker.lagSmoothing(0);
        }
        // Ancres internes (#faq, etc.) passent aussi par Lenis
        document.querySelectorAll('a[href^="#"]').forEach((a) => {
            a.addEventListener('click', (e) => {
                const target = document.querySelector(a.getAttribute('href'));
                if (target) { e.preventDefault(); lenis.scrollTo(target, { offset: -80 }); }
            });
        });
    }

    if (window.gsap) {
        gsap.registerPlugin(ScrollTrigger);

        // Parallax glows + mockup cards (desktop only, avoids layout jank on mobile)
        if (window.innerWidth > 1023) {
            gsap.to('#glowBlue', { y: -50, scrollTrigger: { trigger: '#hero', start: 'top top', end: 'bottom top', scrub: 1 } });
            gsap.to('#glowGold', { y: 35,  scrollTrigger: { trigger: '#hero', start: 'top top', end: 'bottom top', scrub: 1 } });
            gsap.to('#mockCard1', { y: -18, scrollTrigger: { trigger: '#hero', start: 'top top', end: 'bottom top', scrub: 1.2 } });
            gsap.to('#mockCard2', { y: 22,  scrollTrigger: { trigger: '#hero', start: 'top top', end: 'bottom top', scrub: 1.2 } });
            gsap.to('#mockCard3', { y: -12, scrollTrigger: { trigger: '#hero', start: 'top top', end: 'bottom top', scrub: 1.2 } });
        }

        // Stat counters
        document.querySelectorAll('.stat-counter').forEach((el) => {
            const target = parseFloat(el.dataset.target) || 0;
            const obj = { val: 0 };
            ScrollTrigger.create({
                trigger: el, start: 'top 88%', once: true,
                onEnter: () => gsap.to(obj, {
                    val: target, duration: 1.5, ease: 'power2.out',
                    onUpdate: () => { el.firstChild.nodeValue = Math.round(obj.val).toLocaleString('fr-FR') + ''; }
                })
            });
        });

        // Timeline steps
        gsap.utils.toArray('.timeline-step').forEach((step, i) => {
            gsap.to(step, {
                opacity: 1, x: 0, duration: .7, ease: 'power3.out', delay: i * 0.05,
                scrollTrigger: { trigger: step, start: 'top 85%' }
            });
        });
    }

    // Magnetic buttons (desktop only — avoids sticky-hover / mis-tap issues on touch)
    if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
        document.querySelectorAll('.btn-premium').forEach((btn) => {
            btn.addEventListener('mousemove', (e) => {
                const r = btn.getBoundingClientRect();
                const x = (e.clientX - r.left - r.width / 2) * 0.2;
                const y = (e.clientY - r.top - r.height / 2) * 0.2;
                btn.style.transform = `translate(${x}px, ${y}px)`;
            });
            btn.addEventListener('mouseleave', () => { btn.style.transform = 'translate(0, 0)'; });
        });
    }

    // Parallax mockup on mousemove (desktop only) organisateur
    const mockupWrap = document.getElementById('mockupWrap');
    if (mockupWrap && window.innerWidth > 1023) {
        document.getElementById('hero').addEventListener('mousemove', (e) => {
            const x = (e.clientX / window.innerWidth - 0.5) * 16;
            const y = (e.clientY / window.innerHeight - 0.5) * 16;
            mockupWrap.style.transform = `translate(${x}px, ${y}px)`;
            mockupWrap.style.transition = 'transform .3s ease-out';
        });
    }
});
</script>

</body>
</html>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $article = $article ?? (object)[
            'titre' => "Comment bien préparer sa participation à un salon",
            'categorie' => 'Conseils exposants',
            'image' => 'https://www.firstevent.co.uk/wp-content/uploads/2024/09/Cardano-D1-316-1-1.jpg',
            'extrait' => "Les bonnes pratiques pour maximiser votre visibilité et vos contacts lors d'un événement professionnel.",
            'auteur' => (object)['nom' => 'Aïda Ndiaye', 'role' => 'Responsable contenu, ExpoDakar', 'avatar' => null],
            'date_publication' => now()->subDays(4),
            'temps_lecture' => 6,
            'contenu' => "<p>Participer à un salon professionnel représente un investissement important en temps et en ressources. Pourtant, beaucoup d'exposants abordent l'exercice sans véritable stratégie, et repartent avec le sentiment d'avoir manqué des opportunités.</p><p>Voici les étapes clés pour transformer votre présence sur un salon en véritable levier de croissance.</p><h2>Définir des objectifs précis avant l'événement</h2><p>Avant même de réserver un stand, posez-vous les bonnes questions&nbsp;: cherchez-vous à générer des leads, à lancer un nouveau produit, à renforcer votre notoriété locale ou à recruter de nouveaux partenaires&nbsp;? Chaque objectif implique une préparation différente, du choix de l'emplacement à la formation de vos équipes.</p><h2>Soigner le design de votre stand</h2><p>Un stand efficace se distingue en quelques secondes. Misez sur une signalétique claire, un message unique et visible de loin, et un espace d'accueil qui invite à la conversation plutôt qu'à la simple distribution de flyers.</p><blockquote>Les trois premières phrases échangées avec un visiteur déterminent souvent la suite de l'échange.</blockquote><h2>Préparer votre équipe sur le terrain</h2><p>Formez vos collaborateurs à un discours court et percutant. Les trois premières phrases échangées avec un visiteur déterminent souvent la suite de l'échange&nbsp;: elles doivent être claires, orientées bénéfices, et donner envie d'en savoir plus.</p><h2>Assurer un suivi rigoureux après le salon</h2><p>La majorité de la valeur d'un salon se joue après l'événement. Centralisez les contacts collectés dès le premier jour et programmez vos relances dans les 48 heures suivant la clôture, pendant que votre échange est encore frais dans l'esprit du prospect.</p><p>En appliquant ces quatre principes, votre participation à un salon cesse d'être une simple présence pour devenir un véritable outil de développement commercial.</p>",
        ];
        $publiePar = \Carbon\Carbon::parse($article->date_publication)->translatedFormat('d F Y');

        $autresArticles = $autresArticles ?? collect([
            (object)['titre' => 'Le networking B2B au Sénégal en pleine croissance', 'image' => 'https://www.conferenceexpo.com/wp-content/uploads/2019/02/conference-expo-by-nimlok-gallery-d.jpg', 'extrait' => "Un tour d'horizon des tendances qui structurent les rencontres professionnelles dans le pays.", 'categorie' => 'Tendances'],
            (object)['titre' => '5 conseils pour un stand qui attire les visiteurs', 'image' => 'https://elleevents.com.au/wp-content/uploads/2020/06/conference-exhibition-CLIA_Sydney2018.jpg', 'extrait' => "Design, positionnement, discours — ce qui fait vraiment la différence sur un salon.", 'categorie' => 'Conseils exposants'],
        ]);
    @endphp

    <title>{{ $article->titre }} – Blog ExpoDakar</title>
    <meta name="description" content="{{ Str::limit(strip_tags($article->extrait), 155) }}">
    <meta property="og:title" content="{{ $article->titre }} – ExpoDakar">
    <meta property="og:description" content="{{ Str::limit(strip_tags($article->extrait), 155) }}">
    <meta property="og:type" content="article">
    @if($article->image)
    <meta property="og:image" content="{{ $article->image }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --blue-electric: #1E5FD8;
            --blue-deep:     #10284D;
            --blue-night:    #0A1628;
            --blue-soft:     #EEF3FE;
            --gold:          #C9A84C;
            --gold-light:    #E8C96A;
            --pearl:         #F8F9FC;
            --gray-soft:     #EDEEF2;
            --gray-mid:      #8892A4;
            --gray-dark:     #374151;
        }
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; color: var(--blue-night); background:#fff; -webkit-font-smoothing:antialiased; }
        .font-display { font-family: 'Instrument Serif', serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        [x-cloak] { display: none !important; }

        .section-eyebrow {
            display:inline-flex; align-items:center; gap:.5rem; font-family:'JetBrains Mono',monospace;
            font-size:.7rem; letter-spacing:.16em; text-transform:uppercase; color:var(--gold);
        }
        .section-eyebrow::before { content:''; width:1.25rem; height:1px; background:var(--gold); display:inline-block; }

        /* ── Badge catégorie premium (hero) ──────────────────── */
        .category-badge {
            display:inline-flex; align-items:center; gap:.55rem; padding:.45rem .95rem .45rem .75rem;
            border:1px solid rgba(201,168,76,.4); border-radius:999px; background:rgba(201,168,76,.06);
            font-family:'JetBrains Mono',monospace; font-size:.68rem; letter-spacing:.15em; text-transform:uppercase; color:var(--gold);
        }
        .category-badge .dot { width:.4rem; height:.4rem; border-radius:999px; background:var(--gold); flex-shrink:0; }

        .card-lift { transition: transform .35s cubic-bezier(.16,1,.3,1), box-shadow .35s ease; }
        .card-lift:hover { transform: translateY(-4px); box-shadow: 0 24px 56px rgba(10,22,40,.12); }
        .img-zoom-wrap { overflow: hidden; }
        .img-zoom-wrap img { transition: transform .7s cubic-bezier(.16,1,.3,1); }
        .card-lift:hover .img-zoom-wrap img { transform: scale(1.06); }

        .underline-anim { position: relative; text-decoration: none; }
        .underline-anim::after {
            content: ''; position: absolute; left: 0; bottom: -2px; width: 100%; height: 1px;
            background: currentColor; transform: scaleX(0); transform-origin: right; transition: transform .3s cubic-bezier(.16,1,.3,1);
        }
        .underline-anim:hover::after { transform: scaleX(1); transform-origin: left; }

        .navbar { position: fixed; inset: 0 0 auto 0; z-index: 50; transition: background .3s, box-shadow .3s, backdrop-filter .3s, border-color .3s; border-bottom: 1px solid transparent; }
        .navbar.scrolled { background: rgba(10,22,40,.92); backdrop-filter: blur(14px); box-shadow: 0 2px 24px rgba(10,22,40,.12); border-bottom-color: rgba(255,255,255,.08); }
        .navbar.on-light { background: rgba(255,255,255,.94); backdrop-filter: blur(14px); border-bottom-color: var(--gray-soft); box-shadow: 0 2px 16px rgba(10,22,40,.04); }
        .navbar.on-light .nav-logo-text { color: var(--blue-night) !important; }
        .navbar.on-light .nav-link { color: var(--blue-night) !important; }

        *:focus-visible { outline: 2px solid var(--blue-electric); outline-offset: 3px; border-radius: 6px; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--pearl); }
        ::-webkit-scrollbar-thumb { background: var(--blue-electric); border-radius: 99px; }

        .reveal { opacity: 0; transform: translateY(24px); transition: opacity .7s cubic-bezier(.16,1,.3,1), transform .7s cubic-bezier(.16,1,.3,1); }
        .reveal.on { opacity: 1; transform: translateY(0); }

        /* ── Révélation d'entrée du hero (orchestrée, pas de lib externe) ─ */
        .hero-el { opacity: 0; transform: translateY(14px); animation: heroIn .7s cubic-bezier(.16,1,.3,1) forwards; }
        @keyframes heroIn { to { opacity: 1; transform: translateY(0); } }
        .hero-image-wrap { position: relative; overflow: hidden; box-shadow: 0 24px 64px rgba(10,22,40,.10); }
        .hero-image-wrap img { animation: heroImgIn 1.1s cubic-bezier(.16,1,.3,1) both; animation-delay:.15s; will-change: transform; }
        @keyframes heroImgIn { from { opacity: 0; transform: scale(1.06); } to { opacity: 1; transform: scale(1); } }
        .hero-image-badge {
            position:absolute; left:1.25rem; bottom:1.25rem; display:inline-flex; align-items:center; gap:.55rem;
            padding:.6rem 1.05rem; border-radius:999px; background:rgba(255,255,255,.92); backdrop-filter:blur(8px);
            box-shadow:0 10px 28px rgba(10,22,40,.16); font-family:'JetBrains Mono',monospace; font-size:.62rem;
            letter-spacing:.15em; text-transform:uppercase; color:var(--blue-night);
        }
        .hero-image-badge .dot { width:.4rem; height:.4rem; border-radius:999px; background:var(--gold); }

        /* ── Article prose ────────────────────────────────────── */
        .article-prose { font-size: 1.125rem; line-height: 1.9; color: var(--gray-dark); max-width: 70ch; counter-reset: section-counter; }
        .article-prose p { margin: 0 0 1.6rem; }
        .article-prose > p:first-of-type::first-letter {
            font-family: 'Instrument Serif', serif; font-size: 4.2rem; line-height: .72; float: left;
            padding: .4rem .6rem 0 0; color: var(--blue-night);
        }
        .article-prose h2 {
            counter-increment: section-counter;
            font-family:'Instrument Serif', serif; font-size:1.85rem; color:var(--blue-night);
            margin: 3.25rem 0 1.2rem; line-height:1.28; scroll-margin-top: 6.5rem;
        }
        .article-prose h2::before {
            content: counter(section-counter, decimal-leading-zero);
            display:block; width:2.75rem; font-family:'JetBrains Mono',monospace; font-size:.72rem;
            letter-spacing:.16em; color:var(--gold); padding-bottom:.7rem; margin-bottom:1.15rem;
            border-bottom:3px solid var(--gold-light);
        }
        .article-prose h3 { font-family:'Inter',sans-serif; font-weight:700; font-size:1.15rem; color:var(--blue-night); margin:2rem 0 .85rem; }
        .article-prose ul, .article-prose ol { margin: 0 0 1.6rem; padding-left: 1.4rem; }
        .article-prose li { margin-bottom: .5rem; }
        .article-prose a { color: var(--blue-electric); text-decoration: underline; text-underline-offset: 3px; }
        .article-prose blockquote {
            position: relative; margin: 2.5rem 0; padding: 2.15rem 2.15rem 2.15rem 3.15rem;
            background: var(--blue-night); color: #fff; border: 1px solid rgba(201,168,76,.3); border-left: 4px solid var(--gold);
            border-radius: 20px; font-family:'Instrument Serif', serif; font-style: italic; font-size: 1.4rem; line-height:1.55;
        }
        .article-prose blockquote::before {
            content: '\201C'; position:absolute; top:1rem; left:1.35rem; font-family:'Instrument Serif', serif; font-size:3.1rem; line-height:1; color:var(--gold); opacity:.9;
        }
        .article-prose img { width: 100%; border-radius: 20px; margin: 2.25rem 0 .75rem; }
        .article-prose figure { margin: 2.25rem 0; }
        .article-prose figure img { margin-bottom: .75rem; }
        .article-prose figcaption, .article-prose img + em {
            display:block; font-style: normal; font-size:.82rem; color: var(--gray-mid); text-align:center; margin-top:-.25rem;
        }

        .share-icon {
            width: 2.5rem; height: 2.5rem; border-radius: 999px; display:flex; align-items:center; justify-content:center;
            color: var(--blue-night); background: var(--pearl); border:1px solid var(--gray-soft); transition: background .2s, color .2s, transform .2s;
        }
        .share-icon:hover { background: var(--blue-electric); color:#fff; transform: translateY(-2px); }
        .share-icon-dark { background: rgba(255,255,255,.06); border-color: rgba(255,255,255,.12); color: rgba(255,255,255,.75); }
        .share-icon-dark:hover { background: var(--gold); color: var(--blue-night); }

        .progress-bar { position: fixed; top: 0; left: 0; height: 3px; background: linear-gradient(to right, var(--blue-electric), var(--gold)); z-index: 60; width: 0%; transition: width .1s linear; }

        /* ── Sommaire éditorial (signature) ──────────────────── */
        .toc-card { background: var(--pearl); border: 1px solid var(--gray-soft); border-radius: 18px; padding: 1.5rem 1.5rem 1.25rem; }
        .toc-rail { position: relative; padding-left: 1.1rem; margin-top: .9rem; }
        .toc-rail::before { content:''; position:absolute; left:0; top:0; bottom:0; width:1px; background:var(--gray-soft); }
        .toc-marker {
            position:absolute; left:-1.5px; top:0; width:3px; border-radius:2px; background:var(--gold);
            transition: transform .35s cubic-bezier(.16,1,.3,1), height .35s cubic-bezier(.16,1,.3,1);
        }
        .toc-link {
            display:block; font-size:.82rem; line-height:1.4; padding: .5rem 0; color:var(--gray-mid);
            text-decoration:none; transition:color .2s; cursor:pointer;
        }
        .toc-link.is-active, .toc-link:hover { color:var(--blue-night); font-weight:600; }

        /* ── Retour en haut + anneau de progression ──────────── */
        .back-to-top {
            position: fixed; right: 1.5rem; bottom: 1.5rem; z-index: 55; width: 3.1rem; height: 3.1rem; border-radius: 999px;
            background:#fff; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center;
            box-shadow: 0 10px 32px rgba(10,22,40,.18); color: var(--blue-night);
            transition: opacity .3s ease, transform .3s ease; opacity:0; transform: translateY(8px) scale(.9); pointer-events:none;
        }
        .back-to-top.is-visible { opacity:1; transform: translateY(0) scale(1); pointer-events:auto; }
        .back-to-top:hover { color: var(--blue-electric); }
        .back-to-top svg.progress-ring { position:absolute; inset:0; width:100%; height:100%; transform: rotate(-90deg); }
        .progress-ring__bg { fill:none; stroke: var(--gray-soft); stroke-width: 2; }
        .progress-ring__fg { fill:none; stroke: var(--gold); stroke-width: 2; stroke-linecap: round; stroke-dasharray: 119.4; stroke-dashoffset: 119.4; transition: stroke-dashoffset .1s linear; }
        .back-to-top .arrow-icon { position: relative; }

        /* ── Newsletter : grille géométrique subtile ─────────── */
        .geo-grid {
            position:absolute; inset:0; pointer-events:none;
            background-image: linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
            background-size: 42px 42px;
            -webkit-mask-image: radial-gradient(ellipse at center, black 0%, transparent 78%);
            mask-image: radial-gradient(ellipse at center, black 0%, transparent 78%);
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: .001ms !important; transition-duration: .001ms !important; scroll-behavior: auto !important; }
        }
    </style>
</head>
<body class="bg-white" x-data="{}">

<div class="progress-bar" id="readProgress"></div>

{{-- ══════════════════════════════════════════════════════════════
     NAVBAR
     ══════════════════════════════════════════════════════════════ --}}
<header class="navbar"
    x-data="{ scrolled: false, init() { window.addEventListener('scroll', () => { this.scrolled = window.scrollY > 60; }, { passive: true }); } }"
    :class="scrolled ? 'scrolled on-light' : ''" role="banner">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16 h-[4.5rem] flex items-center justify-between">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5" aria-label="ExpoDakar – Accueil">
            <img src="https://res.cloudinary.com/dstbqtuxm/image/upload/v1782085416/ChatGPT_Image_Jun_21__2026__07_24_51_PM-removebg-preview_zi77k0.png" alt="Logo ExpoDakar" class="h-9 w-auto object-contain">
            <span class="font-display text-xl nav-logo-text" style="color:var(--blue-night);">Expo<span style="color:var(--gold);">Dakar</span></span>
        </a>
        <a href="{{ route('blog.index') ?? '#' }}" class="nav-link inline-flex items-center gap-2 text-sm font-semibold underline-anim" style="color:var(--blue-night);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
            <span class="hidden sm:inline">Tous les articles</span>
            <span class="sm:hidden">Articles</span>
        </a>
    </div>
</header>


{{-- ══════════════════════════════════════════════════════════════
     HERO ARTICLE — couverture éditoriale
     ══════════════════════════════════════════════════════════════ --}}
<section class="relative pt-36 pb-14 sm:pt-44 sm:pb-20" style="background:var(--pearl);">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="hero-el flex items-center gap-2 text-xs mb-8" style="color:var(--gray-mid);animation-delay:0s;" aria-label="Fil d'Ariane">
            <a href="{{ route('home') }}" class="underline-anim" style="color:inherit;">Accueil</a>
            <span>/</span>
            <a href="{{ route('blog.index') ?? '#' }}" class="underline-anim" style="color:inherit;">Blog</a>
            <span>/</span>
            <span style="color:var(--blue-night);">{{ Str::limit($article->titre, 40) }}</span>
        </nav>

        <div class="hero-el mb-6" style="animation-delay:.08s;">
            <span class="category-badge"><span class="dot"></span>{{ $article->categorie }}</span>
        </div>

        <h1 class="hero-el font-display leading-[1.06]" style="font-size:clamp(2.4rem,6vw,4.25rem);color:var(--blue-night);animation-delay:.16s;">
            {{ $article->titre }}
        </h1>

        <p class="hero-el mt-6 text-base sm:text-lg" style="color:var(--gray-mid);max-width:44rem;line-height:1.75;animation-delay:.24s;">
            {{ $article->extrait }}
        </p>

        <div class="hero-el mt-9 flex flex-wrap items-center gap-4 sm:gap-6" style="animation-delay:.32s;">
            <div class="flex items-center gap-3">
                @if($article->auteur->avatar ?? null)
                    <img src="{{ $article->auteur->avatar }}" alt="{{ $article->auteur->nom }}" class="w-11 h-11 rounded-full object-cover border-2 border-white shadow-sm">
                @else
                    <div class="w-11 h-11 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0" style="background:linear-gradient(135deg,var(--blue-electric),var(--blue-deep));">
                        {{ strtoupper(substr($article->auteur->nom ?? 'E', 0, 1)) }}
                    </div>
                @endif
                <div>
                    <p class="text-sm font-semibold" style="color:var(--blue-night);">{{ $article->auteur->nom ?? 'ExpoDakar' }}</p>
                    <p class="text-xs" style="color:var(--gray-mid);">{{ $article->auteur->role ?? 'Rédaction' }}</p>
                </div>
            </div>
            <span class="hidden sm:block w-px h-8" style="background:var(--gray-soft);"></span>
            <div class="flex items-center gap-4 text-xs" style="color:var(--gray-mid);">
                <span class="inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5"/></svg>
                    {{ $publiePar }}
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    {{ $article->temps_lecture ?? 5 }} min de lecture
                </span>
            </div>
        </div>
    </div>
</section>

{{-- Image principale --}}
<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-4 sm:-mt-6">
    <div class="hero-image-wrap" style="border-radius:28px;">
        <img src="{{ $article->image }}" alt="{{ $article->titre }}" class="w-full h-[300px] sm:h-[420px] lg:h-[560px] object-cover" loading="eager">
        <span class="hero-image-badge"><span class="dot"></span>ExpoDakar · Insights</span>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     SOMMAIRE MOBILE (accordéon, masqué s'il n'y a pas de sections)
     ══════════════════════════════════════════════════════════════ --}}
<div class="lg:hidden max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-10" id="tocMobileWrap" x-data="{ open: false }" style="display:none;">
    <div class="rounded-2xl overflow-hidden border" style="background:var(--pearl);border-color:var(--gray-soft);">
        <button type="button" @click="open = !open" class="w-full flex items-center justify-between px-5 py-4" :aria-expanded="open.toString()">
            <span class="section-eyebrow">Sommaire</span>
            <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
        </button>
        <div x-show="open" x-collapse x-cloak class="px-5 pb-5" id="tocListMobile"></div>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════════════
     CORPS DE L'ARTICLE + SIDEBAR (sommaire + partage)
     ══════════════════════════════════════════════════════════════ --}}
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
    <div class="grid grid-cols-1 lg:grid-cols-[240px_1fr] gap-10 lg:gap-16">

        {{-- Rail latéral sticky (desktop) : sommaire éditorial + partage --}}
        <aside class="hidden lg:block sticky self-start" style="top:7rem;" aria-label="Navigation de l'article"
               x-data="{ copied: false, pageUrl: window.location.href, copyLink() { navigator.clipboard.writeText(this.pageUrl).then(() => { this.copied = true; setTimeout(() => this.copied = false, 2000); }); } }">

            <div class="toc-card" id="tocDesktopWrap" style="display:none;">
                <span class="section-eyebrow">Sommaire</span>
                <div class="toc-rail">
                    <span class="toc-marker" id="tocMarker"></span>
                    <div id="tocListDesktop"></div>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-8" id="shareRailDesktop">
                <span class="font-mono text-[.65rem] uppercase tracking-[.14em]" style="color:var(--gray-mid);">Partager</span>
                <a :href="`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(pageUrl)}`" target="_blank" rel="noopener noreferrer" class="share-icon" aria-label="Partager sur Facebook">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
                <a :href="`https://twitter.com/intent/tweet?url=${encodeURIComponent(pageUrl)}&text=${encodeURIComponent('{{ addslashes($article->titre) }}')}`" target="_blank" rel="noopener noreferrer" class="share-icon" aria-label="Partager sur X">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                <a :href="`https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(pageUrl)}`" target="_blank" rel="noopener noreferrer" class="share-icon" aria-label="Partager sur LinkedIn">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                </a>
                <button type="button" @click="copyLink()" class="share-icon" aria-label="Copier le lien">
                    <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z"/></svg>
                    <svg x-show="copied" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                </button>
            </div>
        </aside>

        {{-- Contenu --}}
        <article>
            <div class="article-prose">
                {!! $article->contenu !!}
            </div>

            {{-- Partage mobile --}}
            <div class="lg:hidden flex items-center gap-3 mt-10 pt-8" style="border-top:1px solid var(--gray-soft);" x-data="{ copied: false, pageUrl: window.location.href, copyLink() { navigator.clipboard.writeText(this.pageUrl).then(() => { this.copied = true; setTimeout(() => this.copied = false, 2000); }); } }">
                <span class="text-xs font-semibold uppercase tracking-wider mr-1" style="color:var(--gray-mid);">Partager</span>
                <a :href="`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(pageUrl)}`" target="_blank" rel="noopener noreferrer" class="share-icon" aria-label="Partager sur Facebook"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                <a :href="`https://wa.me/?text=${encodeURIComponent(pageUrl)}`" target="_blank" rel="noopener noreferrer" class="share-icon" aria-label="Partager sur WhatsApp"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/></svg></a>
                <button type="button" @click="copyLink()" class="share-icon" aria-label="Copier le lien">
                    <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z"/></svg>
                    <svg x-show="copied" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                </button>
            </div>

            {{-- Bio auteur --}}
            <div class="mt-14 p-6 sm:p-7 rounded-[20px]" style="background:var(--pearl);">
                <div class="w-8 h-[3px] rounded-full mb-5" style="background:linear-gradient(to right,var(--blue-electric),var(--gold));"></div>
                <div class="flex flex-wrap items-center gap-5">
                    @if($article->auteur->avatar ?? null)
                        <img src="{{ $article->auteur->avatar }}" alt="{{ $article->auteur->nom }}" class="w-16 h-16 sm:w-[4.5rem] sm:h-[4.5rem] rounded-full object-cover border-2 border-white shadow-sm">
                    @else
                        <div class="w-16 h-16 sm:w-[4.5rem] sm:h-[4.5rem] rounded-full flex items-center justify-center text-white font-bold text-xl flex-shrink-0" style="background:linear-gradient(135deg,var(--blue-electric),var(--blue-deep));">
                            {{ strtoupper(substr($article->auteur->nom ?? 'E', 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <p class="font-mono text-[.65rem] uppercase tracking-[.14em] mb-1" style="color:var(--gold);">Écrit par</p>
                        <p class="font-semibold" style="color:var(--blue-night);">{{ $article->auteur->nom ?? 'ExpoDakar' }}</p>
                        <p class="text-sm" style="color:var(--gray-mid);">{{ $article->auteur->role ?? 'Rédaction ExpoDakar' }}</p>
                    </div>
                </div>
            </div>

            {{-- CTA retour au blog --}}
            <div class="mt-10">
                <a href="{{ route('blog.index') ?? '#' }}" class="inline-flex items-center gap-2 text-sm font-semibold underline-anim" style="color:var(--blue-electric);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                    Retour à tous les articles
                </a>
            </div>
        </article>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════════════
     ARTICLES LIÉS
     ══════════════════════════════════════════════════════════════ --}}
@if($autresArticles->count())
<section style="background:var(--pearl);" class="py-20 sm:py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">
        <div class="text-center mb-12 sm:mb-16 reveal">
            <p class="section-eyebrow mb-3">À lire aussi</p>
            <h2 class="font-display text-3xl sm:text-4xl" style="color:var(--blue-night);">D'autres articles qui pourraient vous plaire</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @foreach($autresArticles as $a)
            <article class="card-lift reveal bg-white rounded-[20px] overflow-hidden" style="box-shadow:0 4px 24px rgba(10,22,40,.06);">
                <a href="{{ $a->url ?? '#' }}" class="block">
                    <div class="img-zoom-wrap aspect-[16/10]">
                        <img src="{{ $a->image }}" alt="{{ $a->titre }}" class="w-full h-full object-cover" loading="lazy" decoding="async">
                    </div>
                    <div class="p-6 sm:p-7">
                        <p class="text-[.68rem] font-semibold uppercase tracking-wider mb-2" style="color:var(--gold);">{{ $a->categorie }}</p>
                        <h3 class="font-semibold text-sm sm:text-base leading-snug mb-2" style="color:var(--blue-night);">{{ $a->titre }}</h3>
                        <p class="text-xs sm:text-sm leading-relaxed mb-4" style="color:var(--gray-mid);">{{ $a->extrait }}</p>
                        <span class="text-xs sm:text-sm font-semibold group inline-flex items-center gap-1" style="color:var(--blue-electric);">
                            Lire l'article <span class="group-hover:translate-x-1 inline-block transition-transform">→</span>
                        </span>
                    </div>
                </a>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif


{{-- ══════════════════════════════════════════════════════════════
     NEWSLETTER
     ══════════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden py-20 sm:py-24" style="background:linear-gradient(135deg,var(--blue-night),var(--blue-deep));">
    <div class="geo-grid" aria-hidden="true"></div>
    <div class="absolute -top-24 -left-24 w-72 h-72 rounded-full pointer-events-none" style="background:var(--gold);opacity:.10;filter:blur(90px);" aria-hidden="true"></div>
    <div class="absolute -bottom-24 -right-24 w-72 h-72 rounded-full pointer-events-none" style="background:var(--blue-electric);opacity:.16;filter:blur(90px);" aria-hidden="true"></div>
    <div class="relative max-w-2xl mx-auto px-4 sm:px-6 text-center reveal">
        <p class="font-mono text-[.7rem] uppercase tracking-[.16em] mb-4" style="color:var(--gold-light);">Newsletter ExpoDakar</p>
        <h2 class="font-display text-3xl sm:text-4xl text-white mb-5">Ne manquez aucun événement.</h2>
        <p class="text-sm sm:text-base mb-8" style="color:rgba(255,255,255,.65);">Recevez nos analyses, conseils et actualités directement dans votre boîte mail.</p>
        <form onsubmit="return false;" class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
            <label for="newsletter-article" class="sr-only">Adresse email</label>
            <input id="newsletter-article" type="email" required placeholder="votre@email.com" class="flex-1 min-w-0 px-4 py-3 rounded-xl text-sm text-white" style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.15);">
            <button type="submit" class="px-6 py-3 rounded-xl text-sm font-semibold flex-shrink-0" style="color:var(--blue-night);background:linear-gradient(135deg,var(--gold),var(--gold-light));">S'abonner</button>
        </form>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     FOOTER — institutionnel
     ══════════════════════════════════════════════════════════════ --}}
<footer style="background:var(--blue-night);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16 pt-16 pb-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8 pb-12" style="border-bottom:1px solid rgba(255,255,255,.08);">
            <div>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 mb-4">
                    <img src="https://res.cloudinary.com/dstbqtuxm/image/upload/v1782085416/ChatGPT_Image_Jun_21__2026__07_24_51_PM-removebg-preview_zi77k0.png" alt="Logo ExpoDakar" class="h-8 w-auto object-contain">
                    <span class="font-display text-lg text-white">Expo<span style="color:var(--gold);">Dakar</span></span>
                </a>
                <p class="text-sm leading-relaxed" style="color:rgba(255,255,255,.5);">La plateforme de référence pour les salons et événements professionnels au Sénégal.</p>
            </div>

            <div>
                <p class="font-mono text-[.65rem] uppercase tracking-[.16em] mb-4" style="color:var(--gold-light);">Navigation</p>
                <ul class="space-y-2.5 text-sm" style="color:rgba(255,255,255,.6);">
                    <li><a href="{{ route('home') }}" class="underline-anim hover:text-white" style="color:inherit;">Accueil</a></li>
                    <li><a href="{{ route('blog.index') ?? '#' }}" class="underline-anim hover:text-white" style="color:inherit;">Blog</a></li>
                    <li><a href="#" class="underline-anim hover:text-white" style="color:inherit;">Exposants</a></li>
                    <li><a href="#" class="underline-anim hover:text-white" style="color:inherit;">Événements</a></li>
                </ul>
            </div>

            <div>
                <p class="font-mono text-[.65rem] uppercase tracking-[.16em] mb-4" style="color:var(--gold-light);">Contact</p>
                <ul class="space-y-2.5 text-sm" style="color:rgba(255,255,255,.6);">
                    <li><a href="mailto:contact@expodakar.sn" class="underline-anim hover:text-white" style="color:inherit;">contact@expodakar.sn</a></li>
                    <li><a href="tel:+221000000000" class="underline-anim hover:text-white" style="color:inherit;">+221 00 000 00 00</a></li>
                    <li style="color:rgba(255,255,255,.6);">Dakar, Sénégal</li>
                </ul>
            </div>

            <div>
                <p class="font-mono text-[.65rem] uppercase tracking-[.16em] mb-4" style="color:var(--gold-light);">Réseaux</p>
                <div class="flex items-center gap-3">
                    <a href="#" target="_blank" rel="noopener noreferrer" class="share-icon share-icon-dark" aria-label="ExpoDakar sur Facebook">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" target="_blank" rel="noopener noreferrer" class="share-icon share-icon-dark" aria-label="ExpoDakar sur Instagram">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0 5.838a4 4 0 1 0 0 8 4 4 0 0 0 0-8zm5.239-1.44a1.2 1.2 0 1 0 0 2.4 1.2 1.2 0 0 0 0-2.4z"/></svg>
                    </a>
                    <a href="#" target="_blank" rel="noopener noreferrer" class="share-icon share-icon-dark" aria-label="ExpoDakar sur LinkedIn">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-8">
            <p class="text-xs" style="color:rgba(255,255,255,.4);">© {{ date('Y') }} ExpoDakar · Tous droits réservés</p>
            <div class="flex items-center gap-6 text-xs" style="color:rgba(255,255,255,.4);">
                <a href="#" class="underline-anim hover:text-white" style="color:inherit;">Mentions légales</a>
                <a href="#" class="underline-anim hover:text-white" style="color:inherit;">Confidentialité</a>
            </div>
        </div>
    </div>
</footer>

{{-- ══════════════════════════════════════════════════════════════
     RETOUR EN HAUT + ANNEAU DE PROGRESSION
     ══════════════════════════════════════════════════════════════ --}}
<button type="button" id="backToTop" class="back-to-top" aria-label="Retour en haut de l'article">
    <svg class="progress-ring" viewBox="0 0 44 44" aria-hidden="true">
        <circle class="progress-ring__bg" cx="22" cy="22" r="19"></circle>
        <circle class="progress-ring__fg" id="progressRingFg" cx="22" cy="22" r="19"></circle>
    </svg>
    <svg class="arrow-icon w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19.5V4.5m0 0-6 6m6-6 6 6"/></svg>
</button>


<script>
document.addEventListener('DOMContentLoaded', () => {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const article = document.querySelector('article');
    const bar = document.getElementById('readProgress');
    const ring = document.getElementById('progressRingFg');
    const backToTop = document.getElementById('backToTop');
    const RING_CIRCUMFERENCE = 119.4;

    // ── Barre de progression de lecture + anneau du bouton retour-haut ──
    function updateProgress() {
        if (!article) return;
        const rect = article.getBoundingClientRect();
        const total = article.offsetHeight - window.innerHeight;
        const scrolled = Math.min(Math.max(-rect.top, 0), total);
        const pct = total > 0 ? (scrolled / total) * 100 : 0;

        if (bar) bar.style.width = pct + '%';
        if (ring) ring.style.strokeDashoffset = RING_CIRCUMFERENCE - (RING_CIRCUMFERENCE * pct) / 100;

        if (backToTop) {
            const showAfter = article.offsetTop + 200;
            backToTop.classList.toggle('is-visible', window.scrollY > showAfter);
        }
    }
    window.addEventListener('scroll', updateProgress, { passive: true });
    updateProgress();

    if (backToTop) {
        backToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: prefersReducedMotion ? 'auto' : 'smooth' });
        });
    }

    // ── Sommaire éditorial auto-généré à partir des <h2> du contenu ──
    const proseHeadings = article ? Array.from(article.querySelectorAll('.article-prose h2')) : [];
    const tocDesktopWrap = document.getElementById('tocDesktopWrap');
    const tocMobileWrap = document.getElementById('tocMobileWrap');
    const tocDesktop = document.getElementById('tocListDesktop');
    const tocMobile = document.getElementById('tocListMobile');
    const marker = document.getElementById('tocMarker');

    if (proseHeadings.length > 0) {
        const slugify = (str) => (str || '')
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
            .toLowerCase().trim()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-');

        const tocItems = proseHeadings.map((h, i) => {
            if (!h.id) h.id = `${slugify(h.textContent)}-${i}` || `section-${i}`;
            return { id: h.id, text: h.textContent.trim() };
        });

        const buildTocLinks = (container) => {
            if (!container) return;
            container.innerHTML = '';
            tocItems.forEach((item) => {
                const a = document.createElement('a');
                a.href = `#${item.id}`;
                a.className = 'toc-link';
                a.dataset.tocTarget = item.id;
                a.textContent = item.text;
                a.addEventListener('click', (e) => {
                    e.preventDefault();
                    document.getElementById(item.id)?.scrollIntoView({ behavior: prefersReducedMotion ? 'auto' : 'smooth', block: 'start' });
                });
                container.appendChild(a);
            });
        };

        buildTocLinks(tocDesktop);
        buildTocLinks(tocMobile);
        if (tocDesktopWrap) tocDesktopWrap.style.display = '';
        if (tocMobileWrap) tocMobileWrap.style.display = '';

        const activate = (id) => {
            document.querySelectorAll('.toc-link').forEach((a) => a.classList.toggle('is-active', a.dataset.tocTarget === id));
            if (marker && tocDesktop) {
                const target = tocDesktop.querySelector(`[data-toc-target="${id}"]`);
                if (target) {
                    marker.style.transform = `translateY(${target.offsetTop}px)`;
                    marker.style.height = `${target.offsetHeight}px`;
                }
            }
        };

        if ('IntersectionObserver' in window) {
            const io = new IntersectionObserver((entries) => {
                const visible = entries
                    .filter((e) => e.isIntersecting)
                    .sort((a, b) => a.boundingClientRect.top - b.boundingClientRect.top);
                if (visible.length) activate(visible[0].target.id);
            }, { rootMargin: '-15% 0px -70% 0px', threshold: 0 });
            proseHeadings.forEach((h) => io.observe(h));
        }

        activate(tocItems[0].id);
    }

    // ── Révélations au scroll (déjà présentes ailleurs sur le site) ──
    if (!prefersReducedMotion && 'IntersectionObserver' in window) {
        document.querySelectorAll('.reveal').forEach((el) => {
            const io = new IntersectionObserver((entries) => {
                entries.forEach((e) => { if (e.isIntersecting) { el.classList.add('on'); io.unobserve(el); } });
            }, { threshold: .1 });
            io.observe(el);
        });
    } else {
        document.querySelectorAll('.reveal').forEach((el) => el.classList.add('on'));
    }

    // ── Léger parallax sur l'image hero (indépendant, GPU-friendly) ──
    const heroImg = document.querySelector('.hero-image-wrap img');
    if (heroImg && !prefersReducedMotion) {
        let ticking = false;
        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(() => {
                    const offset = Math.min(window.scrollY * 0.08, 40);
                    heroImg.style.transform = `translateY(${offset}px) scale(1.05)`;
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });
    }
});
</script>

</body>
</html>
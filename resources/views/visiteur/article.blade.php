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
            'contenu' => "<p>Participer à un salon professionnel représente un investissement important en temps et en ressources. Pourtant, beaucoup d'exposants abordent l'exercice sans véritable stratégie, et repartent avec le sentiment d'avoir manqué des opportunités.</p><p>Voici les étapes clés pour transformer votre présence sur un salon en véritable levier de croissance.</p><h2>1. Définir des objectifs précis avant l'événement</h2><p>Avant même de réserver un stand, posez-vous les bonnes questions&nbsp;: cherchez-vous à générer des leads, à lancer un nouveau produit, à renforcer votre notoriété locale ou à recruter de nouveaux partenaires&nbsp;? Chaque objectif implique une préparation différente, du choix de l'emplacement à la formation de vos équipes.</p><h2>2. Soigner le design de votre stand</h2><p>Un stand efficace se distingue en quelques secondes. Misez sur une signalétique claire, un message unique et visible de loin, et un espace d'accueil qui invite à la conversation plutôt qu'à la simple distribution de flyers.</p><h2>3. Préparer votre équipe sur le terrain</h2><p>Formez vos collaborateurs à un discours court et percutant. Les trois premières phrases échangées avec un visiteur déterminent souvent la suite de l'échange&nbsp;: elles doivent être claires, orientées bénéfices, et donner envie d'en savoir plus.</p><h2>4. Assurer un suivi rigoureux après le salon</h2><p>La majorité de la valeur d'un salon se joue après l'événement. Centralisez les contacts collectés dès le premier jour et programmez vos relances dans les 48 heures suivant la clôture, pendant que votre échange est encore frais dans l'esprit du prospect.</p><p>En appliquant ces quatre principes, votre participation à un salon cesse d'être une simple présence pour devenir un véritable outil de développement commercial.</p>",
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

        .card-lift { transition: transform .3s cubic-bezier(.16,1,.3,1), box-shadow .3s ease; }
        .card-lift:hover { transform: translateY(-4px); box-shadow: 0 20px 48px rgba(10,22,40,.12); }

        .underline-anim { position: relative; text-decoration: none; }
        .underline-anim::after {
            content: ''; position: absolute; left: 0; bottom: -2px; width: 100%; height: 1px;
            background: currentColor; transform: scaleX(0); transform-origin: right; transition: transform .3s cubic-bezier(.16,1,.3,1);
        }
        .underline-anim:hover::after { transform: scaleX(1); transform-origin: left; }

        .navbar { position: fixed; inset: 0 0 auto 0; z-index: 50; transition: background .3s, box-shadow .3s, backdrop-filter .3s, border-color .3s; border-bottom: 1px solid transparent; }
        .navbar.scrolled { background: rgba(10,22,40,.9); backdrop-filter: blur(14px); box-shadow: 0 2px 24px rgba(10,22,40,.15); border-bottom-color: rgba(255,255,255,.08); }
        .navbar.on-light { background: rgba(255,255,255,.92); backdrop-filter: blur(14px); border-bottom-color: var(--gray-soft); box-shadow: 0 2px 16px rgba(10,22,40,.05); }
        .navbar.on-light .nav-logo-text { color: var(--blue-night) !important; }
        .navbar.on-light .nav-link { color: var(--blue-night) !important; }

        *:focus-visible { outline: 2px solid var(--blue-electric); outline-offset: 3px; border-radius: 6px; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--pearl); }
        ::-webkit-scrollbar-thumb { background: var(--blue-electric); border-radius: 99px; }

        .reveal { opacity: 0; transform: translateY(24px); transition: opacity .7s cubic-bezier(.16,1,.3,1), transform .7s cubic-bezier(.16,1,.3,1); }
        .reveal.on { opacity: 1; transform: translateY(0); }

        /* ── Article prose ────────────────────────────────────── */
        .article-prose { font-size: 1.08rem; line-height: 1.95; color: var(--gray-dark); max-width: 68ch; }
        .article-prose p { margin: 0 0 1.5rem; }
        .article-prose h2 {
            font-family:'Instrument Serif', serif; font-size:1.75rem; color:var(--blue-night);
            margin: 2.75rem 0 1.1rem; line-height:1.3;
        }
        .article-prose h2::before { content:''; display:block; width:2.5rem; height:3px; border-radius:2px; background:linear-gradient(to right,var(--gold),var(--gold-light)); margin-bottom:1rem; }
        .article-prose ul, .article-prose ol { margin: 0 0 1.5rem; padding-left: 1.4rem; }
        .article-prose li { margin-bottom: .5rem; }
        .article-prose a { color: var(--blue-electric); text-decoration: underline; text-underline-offset: 3px; }
        .article-prose blockquote {
            margin: 2rem 0; padding: 1.5rem 1.75rem; border-left: 3px solid var(--gold); background: var(--pearl);
            border-radius: 0 14px 14px 0; font-family:'Instrument Serif', serif; font-style: italic; font-size: 1.25rem; color: var(--blue-night);
        }
        .article-prose img { width: 100%; border-radius: 18px; margin: 2rem 0; }

        .share-icon {
            width: 2.5rem; height: 2.5rem; border-radius: 10px; display:flex; align-items:center; justify-content:center;
            color: var(--blue-night); background: var(--pearl); border:1px solid var(--gray-soft); transition: background .2s, color .2s, transform .2s;
        }
        .share-icon:hover { background: var(--blue-electric); color:#fff; transform: translateY(-2px); }

        .progress-bar { position: fixed; top: 0; left: 0; height: 3px; background: linear-gradient(to right, var(--blue-electric), var(--gold)); z-index: 60; width: 0%; transition: width .1s linear; }

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
            Tous les articles
        </a>
    </div>
</header>


{{-- ══════════════════════════════════════════════════════════════
     HERO ARTICLE
     ══════════════════════════════════════════════════════════════ --}}
<section class="relative pt-32 pb-12 sm:pt-40 sm:pb-16" style="background:var(--pearl);">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs mb-6" style="color:var(--gray-mid);" aria-label="Fil d'Ariane">
            <a href="{{ route('home') }}" class="underline-anim" style="color:inherit;">Accueil</a>
            <span>/</span>
            <a href="{{ route('blog.index') ?? '#' }}" class="underline-anim" style="color:inherit;">Blog</a>
            <span>/</span>
            <span style="color:var(--blue-night);">{{ Str::limit($article->titre, 40) }}</span>
        </nav>

        <p class="section-eyebrow mb-4">{{ $article->categorie }}</p>

        <h1 class="font-display leading-[1.08]" style="font-size:clamp(2.25rem,5vw,3.5rem);color:var(--blue-night);">
            {{ $article->titre }}
        </h1>

        <p class="mt-5 text-base sm:text-lg" style="color:var(--gray-mid);max-width:44rem;line-height:1.7;">
            {{ $article->extrait }}
        </p>

        <div class="mt-8 flex flex-wrap items-center gap-4 sm:gap-6">
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
<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-2 sm:-mt-4">
    <div class="rounded-3xl overflow-hidden shadow-xl">
        <img src="{{ $article->image }}" alt="{{ $article->titre }}" class="w-full h-[260px] sm:h-[420px] object-cover" loading="eager">
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     CORPS DE L'ARTICLE + SIDEBAR PARTAGE
     ══════════════════════════════════════════════════════════════ --}}
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14 sm:py-20">
    <div class="grid grid-cols-1 lg:grid-cols-[64px_1fr] gap-8 lg:gap-14">

        {{-- Rail de partage sticky (desktop) --}}
        <aside class="hidden lg:flex flex-col items-center gap-3 sticky self-start" style="top:7rem;" aria-label="Partager l'article" x-data="{ copied: false, pageUrl: window.location.href, copyLink() { navigator.clipboard.writeText(this.pageUrl).then(() => { this.copied = true; setTimeout(() => this.copied = false, 2000); }); } }">
            <span class="font-mono text-[.65rem] uppercase tracking-[.14em] mb-1" style="color:var(--gray-mid);">Partager</span>
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
            <div class="mt-12 p-6 sm:p-7 rounded-2xl flex flex-wrap items-center gap-5" style="background:var(--pearl);">
                @if($article->auteur->avatar ?? null)
                    <img src="{{ $article->auteur->avatar }}" alt="{{ $article->auteur->nom }}" class="w-16 h-16 rounded-full object-cover border-2 border-white shadow-sm">
                @else
                    <div class="w-16 h-16 rounded-full flex items-center justify-center text-white font-bold text-xl flex-shrink-0" style="background:linear-gradient(135deg,var(--blue-electric),var(--blue-deep));">
                        {{ strtoupper(substr($article->auteur->nom ?? 'E', 0, 1)) }}
                    </div>
                @endif
                <div>
                    <p class="font-mono text-[.65rem] uppercase tracking-[.14em] mb-1" style="color:var(--gold);">Écrit par</p>
                    <p class="font-semibold" style="color:var(--blue-night);">{{ $article->auteur->nom ?? 'ExpoDakar' }}</p>
                    <p class="text-sm" style="color:var(--gray-mid);">{{ $article->auteur->role ?? 'Rédaction ExpoDakar' }}</p>
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
<section style="background:var(--pearl);" class="py-16 sm:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">
        <div class="text-center mb-10 sm:mb-16">
            <p class="section-eyebrow mb-3">À lire aussi</p>
            <h2 class="font-display text-3xl sm:text-4xl" style="color:var(--blue-night);">D'autres articles qui pourraient vous plaire</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-7">
            @foreach($autresArticles as $a)
            <article class="card-lift bg-white rounded-2xl overflow-hidden" style="box-shadow:0 4px 24px rgba(10,22,40,.06);">
                <a href="{{ $a->url ?? '#' }}" class="block">
                    <div class="h-44 sm:h-48 overflow-hidden">
                        <img src="{{ $a->image }}" alt="{{ $a->titre }}" class="w-full h-full object-cover" loading="lazy" decoding="async">
                    </div>
                    <div class="p-5 sm:p-6">
                        <p class="text-[.68rem] font-semibold uppercase tracking-wider mb-2" style="color:var(--gold);">{{ $a->categorie }}</p>
                        <h3 class="font-semibold text-sm sm:text-base leading-snug mb-2" style="color:var(--blue-night);">{{ $a->titre }}</h3>
                        <p class="text-xs sm:text-sm leading-relaxed mb-4" style="color:var(--gray-mid);">{{ $a->extrait }}</p>
                        <span class="text-xs sm:text-sm font-semibold group inline-flex items-center gap-1" style="color:var(--blue-electric);">
                            Lire plus <span class="group-hover:translate-x-1 inline-block transition-transform">→</span>
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
<section class="relative overflow-hidden py-16 sm:py-20" style="background:linear-gradient(135deg,var(--blue-night),var(--blue-deep));">
    <div class="absolute -top-24 -left-24 w-72 h-72 rounded-full pointer-events-none" style="background:var(--gold);opacity:.12;filter:blur(90px);" aria-hidden="true"></div>
    <div class="absolute -bottom-24 -right-24 w-72 h-72 rounded-full pointer-events-none" style="background:var(--blue-electric);opacity:.2;filter:blur(90px);" aria-hidden="true"></div>
    <div class="relative max-w-2xl mx-auto px-4 sm:px-6 text-center">
        <p class="font-mono text-[.7rem] uppercase tracking-[.16em] mb-3" style="color:rgba(255,255,255,.55);">Ne manquez aucun article</p>
        <h2 class="font-display text-3xl sm:text-4xl text-white mb-6">Recevez nos analyses chaque mois</h2>
        <form onsubmit="return false;" class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
            <label for="newsletter-article" class="sr-only">Adresse email</label>
            <input id="newsletter-article" type="email" required placeholder="votre@email.com" class="flex-1 min-w-0 px-4 py-3 rounded-xl text-sm text-white" style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.15);">
            <button type="submit" class="px-6 py-3 rounded-xl text-sm font-semibold flex-shrink-0" style="color:var(--blue-night);background:linear-gradient(135deg,var(--gold),var(--gold-light));">S'abonner</button>
        </form>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     FOOTER
     ══════════════════════════════════════════════════════════════ --}}
<footer style="background:var(--blue-night);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16 py-14 flex flex-wrap items-center justify-between gap-4">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5">
            <img src="https://res.cloudinary.com/dstbqtuxm/image/upload/v1782085416/ChatGPT_Image_Jun_21__2026__07_24_51_PM-removebg-preview_zi77k0.png" alt="Logo ExpoDakar" class="h-8 w-auto object-contain">
            <span class="font-display text-lg text-white">Expo<span style="color:var(--gold);">Dakar</span></span>
        </a>
        <p class="text-xs" style="color:rgba(255,255,255,.4);">© {{ date('Y') }} ExpoDakar · Tous droits réservés</p>
    </div>
</footer>


<script>
document.addEventListener('DOMContentLoaded', () => {
    // Barre de progression de lecture
    const bar = document.getElementById('readProgress');
    const article = document.querySelector('article');
    if (bar && article) {
        window.addEventListener('scroll', () => {
            const rect = article.getBoundingClientRect();
            const total = article.offsetHeight - window.innerHeight;
            const scrolled = Math.min(Math.max(-rect.top, 0), total);
            const pct = total > 0 ? (scrolled / total) * 100 : 0;
            bar.style.width = pct + '%';
        }, { passive: true });
    }

    // Reveal on scroll léger
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (!prefersReducedMotion && 'IntersectionObserver' in window) {
        document.querySelectorAll('.reveal').forEach((el) => {
            const io = new IntersectionObserver((entries) => {
                entries.forEach(e => { if (e.isIntersecting) { el.classList.add('on'); io.unobserve(el); } });
            }, { threshold: .1 });
            io.observe(el);
        });
    }
});
</script>

</body>
</html>
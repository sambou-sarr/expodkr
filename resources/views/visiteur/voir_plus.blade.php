{{--
|--------------------------------------------------------------------------
| ExpoDakar – Page Détail Événement (standalone, sans layout parent)
| Laravel 12 • Blade • Tailwind CSS v4 • Alpine.js 3
| Variable reçue : $event (avec relations ->categorie, ->exposant)
|--------------------------------------------------------------------------
--}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ Str::limit(strip_tags($event->description), 155) }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Open Graph --}}
    <meta property="og:title"       content="{{ $event->titre }} – ExpoDakar">
    <meta property="og:description" content="{{ Str::limit(strip_tags($event->description), 155) }}">
    <meta property="og:type"        content="event">
    @if($event->image)
    <meta property="og:image"       content="{{ Storage::url($event->image) }}">
    @endif

    <title>{{ $event->titre }} – ExpoDakar</title>

    {{-- Fonts Google --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Vite : Tailwind CSS v4 + Alpine.js --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ── Design tokens ───────────────────────────────────── */
        :root {
            --blue:         #2563EB;
            --blue-dark:    #1748c8;
            --blue-night:   #0A1628;
            --blue-soft:    #EFF6FF;
            --gold:         #C9A84C;
            --gold-light:   #E8C96A;
            --pearl:        #F8F9FC;
            --gray-soft:    #EDEEF2;
            --gray-mid:     #8892A4;
            --gray-dark:    #374151;
            --success:      #10B981;
            --shadow-sm:    0 2px 12px rgba(10,22,40,.06);
            --shadow-md:    0 8px 32px rgba(10,22,40,.10);
            --shadow-lg:    0 20px 60px rgba(10,22,40,.14);
        }

        *, *::before, *::after { box-sizing: border-box; }
        html  { scroll-behavior: smooth; }
        body  {
            font-family: 'Inter', sans-serif;
            color: var(--blue-night);
            background: #fff;
            overflow-x: hidden;
            margin: 0;
        }
        .font-display { font-family: 'Instrument Serif', serif; }

        /* ── Alpine cloak ────────────────────────────────────── */
        [x-cloak] { display: none !important; }

        /* ── Gold gradient text ──────────────────────────────── */
        .text-gold {
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ── Navbar ──────────────────────────────────────────── */
        .navbar {
            position: fixed;
            inset: 0 0 auto 0;
            z-index: 50;
            transition: background .3s, box-shadow .3s;
        }
        .navbar.scrolled {
            background: var(--blue-night);
            box-shadow: 0 2px 24px rgba(10,22,40,.2);
        }

        /* ── Hero ────────────────────────────────────────────── */
        .hero-overlay {
            background: linear-gradient(
                to bottom,
                rgba(10,22,40,.35) 0%,
                rgba(10,22,40,.55) 40%,
                rgba(10,22,40,.90) 100%
            );
        }

        /* ── Glassmorphism card ──────────────────────────────── */
        .glass {
            background: rgba(255,255,255,.72);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,.55);
        }

        /* ── Card hover lift ─────────────────────────────────── */
        .card-lift {
            transition: transform .28s ease, box-shadow .28s ease;
        }
        .card-lift:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        /* ── Scroll reveal ───────────────────────────────────── */
        .reveal { opacity: 0; transform: translateY(24px); transition: opacity .6s ease, transform .6s ease; }
        .reveal.on { opacity: 1; transform: translateY(0); }
        .d1 { transition-delay: .08s; }
        .d2 { transition-delay: .16s; }
        .d3 { transition-delay: .24s; }
        .d4 { transition-delay: .32s; }

        /* ── Sticky sidebar ──────────────────────────────────── */
        .sidebar-sticky {
            position: sticky;
            top: 6rem;
        }

        /* ── Share button ────────────────────────────────────── */
        .share-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            padding: .65rem 1.25rem;
            border-radius: .75rem;
            font-size: .8rem;
            font-weight: 600;
            font-family: inherit;
            border: none;
            cursor: pointer;
            transition: filter .2s, transform .15s;
            text-decoration: none;
            color: white;
        }
        .share-btn:hover  { filter: brightness(1.1); }
        .share-btn:active { transform: scale(.96); }

        /* ── Separator ───────────────────────────────────────── */
        .sep { border: none; border-top: 1px solid var(--gray-soft); margin: 0; }

        /* ── Badge status ────────────────────────────────────── */
        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .3rem .9rem;
            border-radius: 99px;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
        }
        .badge-upcoming { background: #ECFDF5; color: #059669; }
        .badge-ongoing  { background: #FFF7ED; color: #C2410C; }
        .badge-past     { background: var(--gray-soft); color: var(--gray-mid); }

        /* ── Focus ring ──────────────────────────────────────── */
        *:focus-visible {
            outline: 2px solid var(--blue);
            outline-offset: 3px;
            border-radius: 6px;
        }

        /* ── Scrollbar ───────────────────────────────────────── */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--pearl); }
        ::-webkit-scrollbar-thumb { background: var(--blue); border-radius: 99px; }

        /* ── Responsive helpers ──────────────────────────────── */
        @media (max-width: 1023px) {
            .two-col { grid-template-columns: 1fr !important; }
            .sidebar-sticky { position: static !important; }
            .hide-mobile { display: none !important; }
        }
        @media (max-width: 640px) {
            .hero-title { font-size: clamp(2rem, 8vw, 3rem) !important; }
            .share-grid { grid-template-columns: 1fr 1fr !important; }
        }
    </style>
</head>
<body>

{{-- ══════════════════════════════════════════════════════════════
     NAVBAR (transparente → solide au scroll)
     ══════════════════════════════════════════════════════════════ --}}
<header
    class="navbar"
    x-data="{
        scrolled: false,
        init() {
            window.addEventListener('scroll', () => { this.scrolled = window.scrollY > 80; }, { passive: true });
        }
    }"
    :class="scrolled ? 'scrolled' : ''"
    role="banner"
>
    <div style="max-width:80rem;margin:0 auto;padding:0 1.5rem;display:flex;align-items:center;justify-content:space-between;height:4.5rem;">

        {{-- Logo --}}
        <a href="{{ route('home') }}"
           style="display:inline-flex;align-items:center;gap:.65rem;text-decoration:none;"
           aria-label="ExpoDakar – Accueil">
            <span style="display:flex;align-items:center;justify-content:center;width:2.25rem;height:2.25rem;border-radius:.625rem;background:background: linear-gradient(135deg, #3B82F6, #1E5FD8);">
            <img src="https://res.cloudinary.com/dstbqtuxm/image/upload/v1782085416/ChatGPT_Image_Jun_21__2026__07_24_51_PM-removebg-preview_zi77k0.png"  alt="Logo ExpoDakar" class="h-12 w-auto object-contain">

            </span>
            <span class="font-display" style="font-size:1.35rem;color:white;">
                Expo<span class="text-gold">Dakar</span>
            </span>
        </a>

        {{-- Bouton retour liste --}}
        <a href="/"
           style="display:inline-flex;align-items:center;gap:.5rem;font-size:.8rem;font-weight:600;color:white;text-decoration:none;padding:.5rem 1rem;border-radius:.625rem;background:rgba(255,255,255,.12);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.2);transition:background .2s;"
           onmouseover="this.style.background='rgba(255,255,255,.22)'"
           onmouseout="this.style.background='rgba(255,255,255,.12)'">
            <svg style="width:.9rem;height:.9rem;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
            </svg>
            Tous les événements
        </a>
    </div>
</header>


{{-- ══════════════════════════════════════════════════════════════
     1. HERO IMMERSIF
     ══════════════════════════════════════════════════════════════ --}}
<section
    style="position:relative;min-height:92vh;display:flex;flex-direction:column;justify-content:flex-end;overflow:hidden;"
    x-data="{ visible: false }"
    x-init="setTimeout(() => visible = true, 100)"
    aria-label="Bannière de l'événement"
>
    {{-- Image de fond --}}
    <div style="position:absolute;inset:0;z-index:0;">
        @if(true)
            <img src="{{ Storage::url($event->image) }}"
                 alt="{{ $event->titre }}"
                 style="width:100%;height:100%;object-fit:cover;"
                 loading="eager">
        @else
            {{-- Fallback dégradé quand pas d'image --}}
            <div style="width:100%;height:100%;background:linear-gradient(135deg,#0A1628 0%,#1E3A70 50%,#2563EB 100%);"></div>
        @endif

        {{-- Overlay gradient storytelling --}}
        <div class="hero-overlay" style="position:absolute;inset:0;" aria-hidden="true"></div>

        {{-- Grain texture (décoration) --}}
        <div style="position:absolute;inset:0;opacity:.035;background-image:url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22n%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.9%22 numOctaves=%224%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23n)%22/%3E%3C/svg%3E');background-size:200px;" aria-hidden="true"></div>
    </div>

    {{-- Contenu Hero --}}
    <div style="position:relative;z-index:1;max-width:80rem;margin:0 auto;padding:2rem 1.5rem 5rem;width:100%;">

        {{-- Badges haut --}}
        <div style="display:flex;flex-wrap:wrap;gap:.75rem;margin-bottom:1.75rem;"
             :style="visible ? 'opacity:1;transform:translateY(0)' : 'opacity:0;transform:translateY(16px)'"
             style="transition:opacity .7s ease, transform .7s ease;">
            {{-- Badge catégorie --}}
            @if($event->categorie)
            <span style="display:inline-flex;align-items:center;gap:.4rem;padding:.35rem .9rem;border-radius:99px;font-size:.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;background:var(--blue);color:white;">
                <svg style="width:.75rem;height:.75rem;" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 0 1 0 1.414l-7 7a1 1 0 0 1-1.414 0l-7-7A.997.997 0 0 1 2 10V5a3 3 0 0 1 3-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                </svg>
                {{ $event->categorie->nom }}
            </span>
            @endif

            {{-- Badge statut dynamique --}}
            @php
                $now   = now();
                $debut = \Carbon\Carbon::parse($event->date_debut);
                $fin   = \Carbon\Carbon::parse($event->date_fin);
                if ($now->lt($debut)) {
                    $statusLabel = 'À venir';
                    $statusClass = 'badge-upcoming';
                    $statusDot   = '#10B981';
                } elseif ($now->between($debut, $fin)) {
                    $statusLabel = 'En cours';
                    $statusClass = 'badge-ongoing';
                    $statusDot   = '#F97316';
                } else {
                    $statusLabel = 'Terminé';
                    $statusClass = 'badge-past';
                    $statusDot   = '#9CA3AF';
                }
            @endphp
            <span class="badge-status {{ $statusClass }}">
                <span style="width:.45rem;height:.45rem;border-radius:50%;background:{{ $statusDot }};display:inline-block;" aria-hidden="true"></span>
                {{ $statusLabel }}
            </span>
        </div>

        {{-- Titre principal --}}
        <h1 class="font-display hero-title"
            style="font-size:clamp(2.5rem,6vw,4.5rem);color:white;line-height:1.08;margin:0 0 1.75rem;max-width:52rem;"
            :style="visible ? 'opacity:1;transform:translateY(0)' : 'opacity:0;transform:translateY(24px)'"
            style="transition:opacity .8s ease .1s, transform .8s ease .1s;">
            {{ $event->titre }}
        </h1>

        {{-- Métadonnées hero --}}
        <div style="display:flex;flex-wrap:wrap;gap:1.5rem;align-items:center;"
             :style="visible ? 'opacity:1;transform:translateY(0)' : 'opacity:0;transform:translateY(20px)'"
             style="transition:opacity .8s ease .2s, transform .8s ease .2s;">

            {{-- Dates --}}
            <div style="display:flex;align-items:center;gap:.6rem;color:rgba(255,255,255,.85);font-size:.925rem;">
                <svg style="width:1.1rem;height:1.1rem;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                </svg>
                <span>
                    {{ \Carbon\Carbon::parse($event->date_debut)->translatedFormat('d M Y') }}
                    @if($event->date_fin && $event->date_fin !== $event->date_debut)
                        <span style="opacity:.6;margin:0 .3rem;">→</span>
                        {{ \Carbon\Carbon::parse($event->date_fin)->translatedFormat('d M Y') }}
                    @endif
                </span>
            </div>

            {{-- Séparateur --}}
            <span style="width:1px;height:1.25rem;background:rgba(255,255,255,.25);" aria-hidden="true"></span>

            {{-- Lieu --}}
            <div style="display:flex;align-items:center;gap:.6rem;color:rgba(255,255,255,.85);font-size:.925rem;">
                <svg style="width:1.1rem;height:1.1rem;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                </svg>
                <span>{{ $event->lieu }}</span>
            </div>

            {{-- Durée --}}
            <div style="display:flex;align-items:center;gap:.6rem;color:rgba(255,255,255,.7);font-size:.85rem;">
                <svg style="width:1rem;height:1rem;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                @php $duree = \Carbon\Carbon::parse($event->date_debut)->diffInDays($event->date_fin) + 1; @endphp
                <span>{{ $duree }} jour{{ $duree > 1 ? 's' : '' }}</span>
            </div>
        </div>
    </div>

    {{-- Indicateur scroll --}}
    <div style="position:absolute;bottom:2rem;left:50%;transform:translateX(-50%);display:flex;flex-direction:column;align-items:center;gap:.4rem;opacity:.5;" aria-hidden="true">
        <div style="width:1px;height:2rem;background:linear-gradient(to bottom,rgba(255,255,255,.5),transparent);animation:scrollPulse 1.8s ease-in-out infinite;"></div>
    </div>

    <style>
        @keyframes scrollPulse { 0%,100%{opacity:.5;transform:scaleY(1)} 50%{opacity:1;transform:scaleY(1.15)} }
    </style>
</section>


{{-- ══════════════════════════════════════════════════════════════
     2. LAYOUT PRINCIPAL : Contenu + Sidebar (grille 2 colonnes)
     ══════════════════════════════════════════════════════════════ --}}
<div style="max-width:80rem;margin:0 auto;padding:4rem 1.5rem 6rem;">
    <div class="two-col" style="display:grid;grid-template-columns:1fr 380px;gap:3rem;align-items:start;">


        {{-- ────────────────────────────────────────────────────
             COLONNE GAUCHE – Contenu principal
             ──────────────────────────────────────────────────── --}}
        <div style="display:flex;flex-direction:column;gap:3rem;">


            {{-- ══════════════════════════════════════════════════
                 3. DESCRIPTION – Storytelling
                 ══════════════════════════════════════════════════ --}}
            <section class="reveal" x-intersect.once="$el.classList.add('on')" aria-label="Description de l'événement">

                {{-- En-tête section --}}
                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.75rem;">
                    <div style="width:3px;height:2rem;border-radius:2px;background:linear-gradient(to bottom,var(--blue),var(--blue-dark));flex-shrink:0;" aria-hidden="true"></div>
                    <h2 class="font-display" style="font-size:1.75rem;color:var(--blue-night);margin:0;">
                        À propos de cet événement
                    </h2>
                </div>

                {{-- Corps description --}}
                <div style="font-size:1.05rem;line-height:1.85;color:var(--gray-dark);max-width:64ch;">
                    {!! nl2br(e($event->description)) !!}
                </div>
            </section>


            {{-- ══════════════════════════════════════════════════
                 4. INFOS PRATIQUES – Cards date / lieu / durée
                 ══════════════════════════════════════════════════ --}}
            <section class="reveal d1" x-intersect.once="$el.classList.add('on')" aria-label="Informations pratiques">

                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.75rem;">
                    <div style="width:3px;height:2rem;border-radius:2px;background:linear-gradient(to bottom,var(--blue),var(--blue-dark));flex-shrink:0;" aria-hidden="true"></div>
                    <h2 class="font-display" style="font-size:1.75rem;color:var(--blue-night);margin:0;">
                        Informations pratiques
                    </h2>
                </div>

                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.25rem;">

                    {{-- Card Début --}}
                    <div class="card-lift"
                         style="padding:1.5rem;border-radius:1.25rem;background:var(--pearl);border:1px solid var(--gray-soft);">
                        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem;">
                            <div style="width:2.75rem;height:2.75rem;border-radius:.75rem;background:var(--blue-soft);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <svg style="width:1.25rem;height:1.25rem;color:var(--blue);" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75M8.25 12h.008v.008H8.25V12Zm3 0h.008v.008H11.25V12Zm3 0h.008v.008H14.25V12Z"/>
                                </svg>
                            </div>
                            <span style="font-size:.7rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--gray-mid);">Début</span>
                        </div>
                        <div style="font-weight:700;font-size:1.1rem;color:var(--blue-night);margin-bottom:.25rem;">
                            {{ \Carbon\Carbon::parse($event->date_debut)->translatedFormat('l d M') }}
                        </div>
                        <div style="font-size:.825rem;color:var(--gray-mid);">
                            {{ \Carbon\Carbon::parse($event->date_debut)->translatedFormat('Y') }}
                        </div>
                    </div>

                    {{-- Card Fin --}}
                    <div class="card-lift"
                         style="padding:1.5rem;border-radius:1.25rem;background:var(--pearl);border:1px solid var(--gray-soft);">
                        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem;">
                            <div style="width:2.75rem;height:2.75rem;border-radius:.75rem;background:var(--blue-soft);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <svg style="width:1.25rem;height:1.25rem;color:var(--blue);" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                            </div>
                            <span style="font-size:.7rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--gray-mid);">Fin</span>
                        </div>
                        <div style="font-weight:700;font-size:1.1rem;color:var(--blue-night);margin-bottom:.25rem;">
                            {{ \Carbon\Carbon::parse($event->date_fin)->translatedFormat('l d M') }}
                        </div>
                        <div style="font-size:.825rem;color:var(--gray-mid);">
                            {{ \Carbon\Carbon::parse($event->date_fin)->translatedFormat('Y') }}
                        </div>
                    </div>

                    {{-- Card Lieu --}}
                    <div class="card-lift"
                         style="padding:1.5rem;border-radius:1.25rem;background:var(--pearl);border:1px solid var(--gray-soft);">
                        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem;">
                            <div style="width:2.75rem;height:2.75rem;border-radius:.75rem;background:var(--blue-soft);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <svg style="width:1.25rem;height:1.25rem;color:var(--blue);" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                                </svg>
                            </div>
                            <span style="font-size:.7rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--gray-mid);">Lieu</span>
                        </div>
                        <div style="font-weight:700;font-size:1.05rem;color:var(--blue-night);margin-bottom:.25rem;line-height:1.35;">
                            {{ $event->lieu }}
                        </div>
                        <a href="https://maps.google.com/?q={{ urlencode($event->lieu) }}"
                           target="_blank" rel="noopener noreferrer"
                           style="font-size:.78rem;font-weight:600;color:var(--blue);text-decoration:none;display:inline-flex;align-items:center;gap:.3rem;margin-top:.5rem;"
                           onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                            Voir sur la carte
                            <svg style="width:.7rem;height:.7rem;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                            </svg>
                        </a>
                    </div>

                    {{-- Card Durée --}}
                    <div class="card-lift"
                         style="padding:1.5rem;border-radius:1.25rem;background:linear-gradient(135deg,var(--blue),var(--blue-dark));border:none;">
                        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem;">
                            <div style="width:2.75rem;height:2.75rem;border-radius:.75rem;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <svg style="width:1.25rem;height:1.25rem;color:white;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                            </div>
                            <span style="font-size:.7rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:rgba(255,255,255,.65);">Durée</span>
                        </div>
                        <div class="font-display" style="font-size:2.5rem;color:white;line-height:1;margin-bottom:.25rem;">
                            {{ $duree }}
                        </div>
                        <div style="font-size:.875rem;color:rgba(255,255,255,.7);">
                            jour{{ $duree > 1 ? 's' : '' }} d'événement
                        </div>
                    </div>
                </div>
            </section>


            {{-- ══════════════════════════════════════════════════
                 5. EXPOSANT / ORGANISATEUR
                 ══════════════════════════════════════════════════ --}}
            <section class="reveal d2" x-intersect.once="$el.classList.add('on')" aria-label="Exposant et organisateur">

                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.75rem;">
                    <div style="width:3px;height:2rem;border-radius:2px;background:linear-gradient(to bottom,var(--blue),var(--blue-dark));flex-shrink:0;" aria-hidden="true"></div>
                    <h2 class="font-display" style="font-size:1.75rem;color:var(--blue-night);margin:0;">
                        Organisateur
                    </h2>
                </div>

                @if($event->exposant)
                {{-- ── Exposant réel ── --}}
                <div style="padding:2rem;border-radius:1.5rem;border:1px solid var(--gray-soft);background:var(--pearl);box-shadow:var(--shadow-sm);">
                    <div style="display:flex;flex-wrap:wrap;gap:1.5rem;align-items:flex-start;">

                        {{-- Logo / Avatar exposant --}}
                        <div style="flex-shrink:0;">
                            @if($event->exposant->logo)
                                <img src="{{ Storage::url($event->exposant->logo) }}"
                                     alt="Logo {{ $event->exposant->nom_entreprise }}"
                                     style="width:5rem;height:5rem;border-radius:50%;object-fit:cover;border:3px solid white;box-shadow:var(--shadow-sm);">
                            @else
                                <div style="width:5rem;height:5rem;border-radius:50%;background:linear-gradient(135deg,var(--blue),var(--blue-dark));display:flex;align-items:center;justify-content:center;border:3px solid white;box-shadow:var(--shadow-sm);">
                                    <span class="font-display" style="font-size:1.75rem;color:white;font-weight:700;">
                                        {{ strtoupper(substr($event->exposant->nom_entreprise ?? 'E', 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Infos --}}
                        <div style="flex:1;min-width:0;">
                            <div style="display:flex;flex-wrap:wrap;align-items:center;gap:.75rem;margin-bottom:.5rem;">
                                <h3 style="font-weight:700;font-size:1.2rem;color:var(--blue-night);margin:0;">
                                  {{ $event->exposant->nom_entreprise }}
                                </h3>
                                @if($event->exposant->secteur_activite)
                                <span style="font-size:.72rem;font-weight:600;padding:.2rem .7rem;border-radius:99px;background:var(--blue-soft);color:var(--blue);">
                                    {{ $event->exposant->secteur_activite }}
                                </span>
                                @endif
                            </div>

                            @if($event->exposant->responsable)
                            <p style="font-size:.9rem;color:var(--gray-mid);margin:0 0 1.25rem;display:flex;align-items:center;gap:.5rem;">
                                <svg style="width:.9rem;height:.9rem;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                </svg>
                                {{ $event->exposant->responsable }}
                            </p>
                            @endif

                            {{-- Contacts --}}
                            <div style="display:flex;flex-wrap:wrap;gap:.75rem;">
                                @if($event->exposant->telephone)
                                <a href="tel:{{ $event->exposant->telephone }}"
                                   style="display:inline-flex;align-items:center;gap:.5rem;font-size:.825rem;font-weight:500;color:var(--gray-dark);text-decoration:none;padding:.5rem .9rem;border-radius:.625rem;background:white;border:1px solid var(--gray-soft);transition:border-color .2s,color .2s;"
                                   onmouseover="this.style.borderColor='var(--blue)';this.style.color='var(--blue)'"
                                   onmouseout="this.style.borderColor='var(--gray-soft)';this.style.color='var(--gray-dark)'"
                                   aria-label="Téléphone : {{ $event->exposant->telephone }}">
                                    <svg style="width:.9rem;height:.9rem;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 6z"/>
                                    </svg>
                                    {{ $event->exposant->telephone }}
                                </a>
                                @endif

                                @if($event->exposant->email)
                                <a href="mailto:{{ $event->exposant->email }}"
                                   style="display:inline-flex;align-items:center;gap:.5rem;font-size:.825rem;font-weight:500;color:var(--gray-dark);text-decoration:none;padding:.5rem .9rem;border-radius:.625rem;background:white;border:1px solid var(--gray-soft);transition:border-color .2s,color .2s;"
                                   onmouseover="this.style.borderColor='var(--blue)';this.style.color='var(--blue)'"
                                   onmouseout="this.style.borderColor='var(--gray-soft)';this.style.color='var(--gray-dark)'"
                                   aria-label="Email : {{ $event->exposant->email }}">
                                    <svg style="width:.9rem;height:.9rem;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                                    </svg>
                                    {{ $event->exposant->email }}
                                </a>
                                @endif

                                @if($event->exposant->site_web)
                                <a href="{{ $event->exposant->site_web }}"
                                   target="_blank" rel="noopener noreferrer"
                                   style="display:inline-flex;align-items:center;gap:.5rem;font-size:.825rem;font-weight:600;color:white;text-decoration:none;padding:.5rem .9rem;border-radius:.625rem;background:var(--blue);transition:filter .2s;"
                                   onmouseover="this.style.filter='brightness(1.1)'" onmouseout="this.style.filter='none'"
                                   aria-label="Site web de {{ $event->exposant->nom_entreprise }}">
                                    <svg style="width:.9rem;height:.9rem;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3"/>
                                    </svg>
                                    Site web
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @else
                {{-- ── Fallback : organisateur ExpoDakar ── --}}
                <div style="padding:2rem;border-radius:1.5rem;border:1px solid var(--gray-soft);background:var(--pearl);box-shadow:var(--shadow-sm);">
                    <div style="display:flex;flex-wrap:wrap;gap:1.5rem;align-items:center;">
                        <div style="width:5rem;height:5rem;border-radius:50%;background:linear-gradient(135deg,var(--blue),var(--blue-dark));display:flex;align-items:center;justify-content:center;border:3px solid white;box-shadow:var(--shadow-sm);flex-shrink:0;">
                            <svg style="width:2rem;height:2rem;color:white;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                            </svg>
                        </div>
                        <div>
                            <h3 style="font-weight:700;font-size:1.2rem;color:var(--blue-night);margin:0 0 .35rem;">ExpoDakar</h3>
                            <p style="font-size:.875rem;color:var(--gray-mid);margin:0 0 .75rem;">Organisateur principal</p>
                            <a href="mailto:contact@expodakar.sn"
                               style="font-size:.825rem;font-weight:600;color:var(--blue);text-decoration:none;"
                               onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                contact@expodakar.sn
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </section>


            {{-- ══════════════════════════════════════════════════
                 7 & 8. PARTAGE SOCIAL avec Alpine.js
                 ══════════════════════════════════════════════════ --}}
            <section class="reveal d3" x-intersect.once="$el.classList.add('on')" aria-label="Partager cet événement"
                x-data="{
                    open: false,
                    copied: false,
                    pageUrl: window.location.href,
                    copyLink() {
                        navigator.clipboard.writeText(this.pageUrl).then(() => {
                            this.copied = true;
                            setTimeout(() => this.copied = false, 2200);
                        });
                    }
                }">

                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.75rem;">
                    <div style="width:3px;height:2rem;border-radius:2px;background:linear-gradient(to bottom,var(--blue),var(--blue-dark));flex-shrink:0;" aria-hidden="true"></div>
                    <h2 class="font-display" style="font-size:1.75rem;color:var(--blue-night);margin:0;">
                        Partager l'événement
                    </h2>
                </div>

                <div style="padding:1.75rem;border-radius:1.5rem;border:1px solid var(--gray-soft);background:var(--pearl);">

                    <p style="font-size:.9rem;color:var(--gray-mid);margin:0 0 1.5rem;">
                        Partagez cet événement avec vos contacts et votre réseau professionnel.
                    </p>

                    <div class="share-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:.75rem;">

                        {{-- Facebook login--}}
                        <a :href="`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(pageUrl)}`"
                           target="_blank" rel="noopener noreferrer"
                           class="share-btn"
                           style="background:#1877F2;flex-direction:column;gap:.4rem;padding:.9rem .5rem;"
                           aria-label="Partager sur Facebook">
                            <svg style="width:1.2rem;height:1.2rem;" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            <span style="font-size:.7rem;">Facebook</span>
                        </a>

                        {{-- Twitter / X --}}
                        <a :href="`https://twitter.com/intent/tweet?url=${encodeURIComponent(pageUrl)}&text=${encodeURIComponent('{{ addslashes($event->titre) }} – ExpoDakar')}`"
                           target="_blank" rel="noopener noreferrer"
                           class="share-btn"
                           style="background:#000;flex-direction:column;gap:.4rem;padding:.9rem .5rem;"
                           aria-label="Partager sur X (Twitter)">
                            <svg style="width:1.2rem;height:1.2rem;" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                            <span style="font-size:.7rem;">Twitter</span>
                        </a>

                        {{-- WhatsApp --}}
                        <a :href="`https://wa.me/?text=${encodeURIComponent('{{ addslashes($event->titre) }} – ' + pageUrl)}`"
                           target="_blank" rel="noopener noreferrer"
                           class="share-btn"
                           style="background:#25D366;flex-direction:column;gap:.4rem;padding:.9rem .5rem;"
                           aria-label="Partager sur WhatsApp">
                            <svg style="width:1.2rem;height:1.2rem;" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
                            </svg>
                            <span style="font-size:.7rem;">WhatsApp</span>
                        </a>

                        {{-- LinkedIn --}}
                        <a :href="`https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(pageUrl)}`"
                           target="_blank" rel="noopener noreferrer"
                           class="share-btn"
                           style="background:#0A66C2;flex-direction:column;gap:.4rem;padding:.9rem .5rem;"
                           aria-label="Partager sur LinkedIn">
                            <svg style="width:1.2rem;height:1.2rem;" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                            <span style="font-size:.7rem;">LinkedIn</span>
                        </a>
                    </div>

                    {{-- Copier le lien --}}
                    <div style="margin-top:1rem;display:flex;gap:.75rem;align-items:center;">
                        <div style="flex:1;padding:.7rem 1rem;border-radius:.625rem;background:white;border:1px solid var(--gray-soft);font-size:.8rem;color:var(--gray-mid);overflow:hidden;white-space:nowrap;text-overflow:ellipsis;">
                            <span x-text="pageUrl"></span>
                        </div>
                        <button @click="copyLink()"
                                style="flex-shrink:0;padding:.7rem 1.1rem;border-radius:.625rem;border:none;font-size:.8rem;font-weight:600;font-family:inherit;cursor:pointer;transition:background .2s,color .2s;"
                                :style="copied ? 'background:var(--success);color:white;' : 'background:var(--blue-soft);color:var(--blue);'"
                                aria-label="Copier le lien">
                            <span x-show="!copied">Copier</span>
                            <span x-show="copied" x-cloak style="display:flex;align-items:center;gap:.35rem;">
                                <svg style="width:.85rem;height:.85rem;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                </svg>
                                Copié !
                            </span>
                        </button>
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


                {{-- ══════════════════════════════════════════════
                     6. SIDEBAR – Card réservation principale
                     ══════════════════════════════════════════════ --}}
                <div style="border-radius:1.5rem;overflow:hidden;box-shadow:var(--shadow-md);border:1px solid var(--gray-soft);">

                    {{-- Header card --}}
                    <div style="padding:1.5rem 1.75rem;background:linear-gradient(135deg,var(--blue),var(--blue-dark));">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem;">
                            <span class="badge-status {{ $statusClass }}" style="font-size:.68rem;">
                                <span style="width:.4rem;height:.4rem;border-radius:50%;background:{{ $statusDot }};display:inline-block;" aria-hidden="true"></span>
                                {{ $statusLabel }}
                            </span>
                            @if($event->categorie)
                            <span style="font-size:.7rem;color:rgba(255,255,255,.65);">{{ $event->categorie->nom }}</span>
                            @endif
                        </div>
                        <div class="font-display" style="font-size:1.35rem;color:white;line-height:1.3;">
                            {{ Str::limit($event->titre, 55) }}
                        </div>
                    </div>

                    {{-- Corps card --}}
                    <div style="padding:1.5rem 1.75rem;background:white;">

                        {{-- Infos mini --}}
                        <div style="display:flex;flex-direction:column;gap:.85rem;margin-bottom:1.5rem;">
                            <div style="display:flex;align-items:center;gap:.75rem;">
                                <div style="width:2rem;height:2rem;border-radius:.5rem;background:var(--blue-soft);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <svg style="width:.9rem;height:.9rem;color:var(--blue);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5"/>
                                    </svg>
                                </div>
                                <div>
                                    <div style="font-size:.7rem;color:var(--gray-mid);font-weight:500;">Date</div>
                                    <div style="font-size:.875rem;font-weight:600;color:var(--blue-night);">
                                        {{ \Carbon\Carbon::parse($event->date_debut)->translatedFormat('d M Y') }}
                                    </div>
                                </div>
                            </div>
                            <hr class="sep">
                            <div style="display:flex;align-items:center;gap:.75rem;">
                                <div style="width:2rem;height:2rem;border-radius:.5rem;background:var(--blue-soft);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <svg style="width:.9rem;height:.9rem;color:var(--blue);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div style="font-size:.7rem;color:var(--gray-mid);font-weight:500;">Lieu</div>
                                    <div style="font-size:.875rem;font-weight:600;color:var(--blue-night);">{{ Str::limit($event->lieu, 32) }}</div>
                                </div>
                            </div>
                            <hr class="sep">

                            {{-- Compteur participants (UI mock) --}}
                            <div>
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.6rem;">
                                    <span style="font-size:.8rem;font-weight:500;color:var(--gray-mid);">Participants inscrits</span>
                                    <span style="font-size:.8rem;font-weight:700;color:var(--blue);">248 / 500</span>
                                </div>
                                <div style="height:.4rem;border-radius:99px;background:var(--gray-soft);overflow:hidden;">
                                    <div style="width:49.6%;height:100%;border-radius:99px;background:linear-gradient(to right,var(--blue),var(--blue-dark));transition:width .8s ease;"
                                         x-data="{}" x-init="setTimeout(() => $el.style.width = '49.6%', 300)">
                                    </div>
                                </div>
                                <p style="font-size:.72rem;color:var(--gray-mid);margin:.5rem 0 0;">252 places restantes</p>
                            </div>
                        </div>

                        {{-- CTA Réserver --}}
                        @if($statusLabel !== 'Terminé')
                            <a href="{{ route('reservations.create', $event->id) }}"
                               style="display:block;width:100%;padding:1rem;border-radius:.875rem;text-align:center;font-weight:700;font-size:1rem;color:white;text-decoration:none;background:linear-gradient(135deg,var(--blue),var(--blue-dark));box-shadow:0 6px 20px rgba(37,99,235,.35);transition:filter .2s,transform .15s,box-shadow .2s;"
                               onmouseover="this.style.filter='brightness(1.08)';this.style.boxShadow='0 8px 28px rgba(37,99,235,.45)'"
                               onmouseout="this.style.filter='none';this.style.boxShadow='0 6px 20px rgba(37,99,235,.35)'"
                               onmousedown="this.style.transform='scale(.98)'" onmouseup="this.style.transform='none'">
                                Réserver ma place
                            </a>
                        
                        @else
                            <div style="display:block;width:100%;padding:1rem;border-radius:.875rem;text-align:center;font-weight:700;font-size:1rem;color:var(--gray-mid);background:var(--gray-soft);cursor:not-allowed;">
                                Événement terminé
                            </div>
                        @endif

                        {{-- Note sous le CTA --}}
                        <p style="font-size:.75rem;color:var(--gray-mid);text-align:center;margin:.75rem 0 0;">
                            🔒 Inscription sécurisée · Billet QR par email
                        </p>
                    </div>
                </div>


                {{-- ══════════════════════════════════════════════
                     Card : Partage rapide (mini version sidebar)
                     ══════════════════════════════════════════════ --}}
                <div style="padding:1.25rem 1.5rem;border-radius:1.25rem;border:1px solid var(--gray-soft);background:white;box-shadow:var(--shadow-sm);"
                     x-data="{ pageUrl: window.location.href }">
                    <p style="font-size:.75rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gray-mid);margin:0 0 .9rem;">
                        Partager
                    </p>
                    <div style="display:flex;gap:.6rem;">
                        <a :href="`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(pageUrl)}`"
                           target="_blank" rel="noopener noreferrer"
                           style="flex:1;display:flex;align-items:center;justify-content:center;height:2.5rem;border-radius:.625rem;background:#1877F2;color:white;transition:filter .2s;"
                           onmouseover="this.style.filter='brightness(1.1)'" onmouseout="this.style.filter='none'"
                           aria-label="Partager sur Facebook">
                            <svg style="width:1rem;height:1rem;" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a :href="`https://wa.me/?text=${encodeURIComponent('{{ addslashes($event->titre) }} – ' + pageUrl)}`"
                           target="_blank" rel="noopener noreferrer"
                           style="flex:1;display:flex;align-items:center;justify-content:center;height:2.5rem;border-radius:.625rem;background:#25D366;color:white;transition:filter .2s;"
                           onmouseover="this.style.filter='brightness(1.1)'" onmouseout="this.style.filter='none'"
                           aria-label="Partager sur WhatsApp">
                            <svg style="width:1rem;height:1rem;" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                        </a>
                        <a :href="`https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(pageUrl)}`"
                           target="_blank" rel="noopener noreferrer"
                           style="flex:1;display:flex;align-items:center;justify-content:center;height:2.5rem;border-radius:.625rem;background:#0A66C2;color:white;transition:filter .2s;"
                           onmouseover="this.style.filter='brightness(1.1)'" onmouseout="this.style.filter='none'"
                           aria-label="Partager sur LinkedIn">
                            <svg style="width:1rem;height:1rem;" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                        <a :href="`https://twitter.com/intent/tweet?url=${encodeURIComponent(pageUrl)}&text=${encodeURIComponent('{{ addslashes($event->titre) }} – ExpoDakar')}`"
                           target="_blank" rel="noopener noreferrer"
                           style="flex:1;display:flex;align-items:center;justify-content:center;height:2.5rem;border-radius:.625rem;background:#000;color:white;transition:filter .2s;"
                           onmouseover="this.style.filter='brightness(1.35)'" onmouseout="this.style.filter='none'"
                           aria-label="Partager sur X">
                            <svg style="width:1rem;height:1rem;" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                    </div>
                </div>


                {{-- ══════════════════════════════════════════════
                     Card : Aide / Assistance
                     ══════════════════════════════════════════════ --}}
                <div style="padding:1.25rem 1.5rem;border-radius:1.25rem;border:1px solid var(--gray-soft);background:var(--pearl);">
                    <div style="display:flex;gap:.75rem;align-items:flex-start;">
                        <div style="width:2.25rem;height:2.25rem;border-radius:.625rem;background:var(--blue-soft);display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:.1rem;">
                            <svg style="width:1.1rem;height:1.1rem;color:var(--blue);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z"/>
                            </svg>
                        </div>
                        <div>
                            <p style="font-size:.825rem;font-weight:600;color:var(--blue-night);margin:0 0 .3rem;">Besoin d'aide ?</p>
                            <p style="font-size:.78rem;color:var(--gray-mid);margin:0 0 .6rem;line-height:1.5;">Notre équipe est disponible pour répondre à vos questions.</p>
                            <a href="mailto:contact@expodakar.sn"
                               style="font-size:.78rem;font-weight:600;color:var(--blue);text-decoration:none;"
                               onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                contact@expodakar.sn
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </aside>
        {{-- /SIDEBAR --}}

    </div>
    {{-- /GRID PRINCIPAL --}}
</div>


{{-- ══════════════════════════════════════════════════════════════
     FOOTER MINIMAL
     ══════════════════════════════════════════════════════════════ --}}
<footer style="border-top:1px solid var(--gray-soft);padding:2rem 1.5rem;background:var(--pearl);" role="contentinfo">
    <div style="max-width:80rem;margin:0 auto;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:1rem;">
        <a href="{{ route('home') }}"
           style="display:inline-flex;align-items:center;gap:.5rem;text-decoration:none;"
           aria-label="ExpoDakar – Accueil">
            <span style="display:flex;align-items:center;justify-content:center;width:1.75rem;height:1.75rem;border-radius:.4rem;background:linear-gradient(135deg,var(--blue),var(--blue-dark));">
                <svg style="width:.85rem;height:.85rem;color:white;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18"/>
                </svg>
            </span>
            <span class="font-display" style="font-size:1.1rem;color:var(--blue-night);">Expo<span style="color:var(--blue);">Dakar</span></span>
        </a>
        <p style="font-size:.78rem;color:var(--gray-mid);margin:0;">
            © {{ date('Y') }} ExpoDakar · Tous droits réservés
        </p>
        <nav style="display:flex;gap:1.25rem;" aria-label="Liens footer">
            <a href="{{ route('home') }}"    style="font-size:.78rem;color:var(--gray-mid);text-decoration:none;" onmouseover="this.style.color='var(--blue-night)'" onmouseout="this.style.color='var(--gray-mid)'">Accueil</a>
            <a href="{{ route('events.index') }}" style="font-size:.78rem;color:var(--gray-mid);text-decoration:none;" onmouseover="this.style.color='var(--blue-night)'" onmouseout="this.style.color='var(--gray-mid)'">Événements</a>
            <a href="mailto:contact@expodakar.sn" style="font-size:.78rem;color:var(--gray-mid);text-decoration:none;" onmouseover="this.style.color='var(--blue-night)'" onmouseout="this.style.color='var(--gray-mid)'">Contact</a>
        </nav>
    </div>
</footer>


{{-- ══════════════════════════════════════════════════════════════
     SCRIPT : Intersection Observer (révélations au scroll)
     ══════════════════════════════════════════════════════════════ --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Révélations scroll
    const revealEls = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('on');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        revealEls.forEach(el => io.observe(el));
    } else {
        revealEls.forEach(el => el.classList.add('on'));
    }
});
</script>

</body>
</html>
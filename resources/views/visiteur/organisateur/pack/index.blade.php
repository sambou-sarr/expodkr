<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarifs · ExpoDKR</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --blue-night: #0A1628;
            --blue-electric: #1E5FD8;
            --gold: #C9A84C;
            --gold-light: #E8C96A;
            --pearl: #F7F8FC;
            --gray-soft: #EEF0F6;
            --gray-mid: #8892A4;
            --ink: #1C2733;
        }
        body { font-family:'Inter',sans-serif; background:var(--pearl); color:var(--blue-night); -webkit-font-smoothing:antialiased; overflow-x:hidden; }
        .font-display { font-family:'Instrument Serif',serif; }
        [x-cloak] { display:none !important; }

        .check { width:.95rem;height:.95rem;color:#0F7A54; flex-shrink:0; margin-top:.15rem; }
        .cross { width:.95rem;height:.95rem; flex-shrink:0; margin-top:.15rem; color:#C3C8D1; }
        .feat-li { display:flex; align-items:flex-start; gap:.6rem; font-size:.82rem; color:var(--ink); line-height:1.5; }
        .feat-li-off { display:flex; align-items:flex-start; gap:.6rem; font-size:.82rem; color:var(--gray-mid); }

        .card {
            background:white; border-radius:1.25rem; overflow:hidden; position:relative;
            display:flex; flex-direction:column; border:1px solid var(--gray-soft);
            box-shadow:0 1px 3px rgba(10,22,40,.04);
            transition: box-shadow .25s ease, border-color .25s ease, transform .25s ease;
        }
        .card:hover { box-shadow:0 12px 32px rgba(10,22,40,.08); border-color:#DCE1EC; transform:translateY(-3px); }

        .card-pop { border:1.5px solid var(--blue-electric) !important; box-shadow:0 8px 28px rgba(30,95,216,.12) !important; }
        .card-pop:hover { box-shadow:0 18px 44px rgba(30,95,216,.2) !important; transform:translateY(-5px); }

        .badge-pop {
            display:inline-flex; align-items:center; gap:.4rem;
            background:var(--blue-electric); color:white;
            font-size:.68rem; font-weight:600; letter-spacing:.03em;
            padding:.4rem 1rem; border-radius:0 0 .6rem .6rem;
        }

        .cta {
            display:block; text-align:center; padding:.8rem; border-radius:.65rem;
            font-size:.83rem; font-weight:600; text-decoration:none; margin-top:auto;
            background:white; color:var(--blue-night); border:1px solid var(--gray-soft);
            transition: background .2s ease, border-color .2s ease, color .2s ease;
        }
        .cta:hover { background:var(--blue-night); color:white; border-color:var(--blue-night); }
        .cta-pop { background:var(--blue-electric); color:white; border-color:var(--blue-electric); }
        .cta-pop:hover { background:#1248b0; border-color:#1248b0; }

        .section-label { font-size:.7rem; font-weight:600; letter-spacing:.05em; text-transform:uppercase; color:var(--gray-mid); margin-bottom:.75rem; }

        .ad-card { transition: box-shadow .25s ease, border-color .25s ease, transform .25s ease; }
        .ad-card:hover { box-shadow:0 8px 24px rgba(10,22,40,.06); border-color:#DCE1EC; transform:translateY(-3px); }

        .reveal { opacity:0; transform:translateY(14px); transition:opacity .5s ease, transform .5s ease; }
        .reveal.visible { opacity:1; transform:translateY(0); }
        .reveal-delay-1 { transition-delay:.05s; }
        .reveal-delay-2 { transition-delay:.1s; }
        .reveal-delay-3 { transition-delay:.15s; }
        .reveal-delay-4 { transition-delay:.2s; }

        .hero-grid-overlay {
            background-image:
                linear-gradient(rgba(196,168,76,.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(196,168,76,.08) 1px, transparent 1px);
            background-size: 48px 48px;
        }

        /* ── Glow doré du hero ── */
        .hero-glow {
            position:absolute; border-radius:50%; filter:blur(90px); pointer-events:none;
            animation: glow-drift 12s ease-in-out infinite;
        }
        @keyframes glow-drift { 0%,100% { transform:translate(0,0) scale(1); } 50% { transform:translate(-3%,3%) scale(1.08); } }

        /* ── Halo autour du logo ── */
        .logo-glow { position:relative; }
        .logo-glow::before {
            content:''; position:absolute; left:-.6rem; top:50%; transform:translateY(-50%);
            width:2.2rem; height:2.2rem; border-radius:50%;
            background:radial-gradient(circle, rgba(232,201,106,.5), transparent 70%);
            filter:blur(6px); pointer-events:none;
        }

        table.compare { width:100%; border-collapse:collapse; font-size:.8rem; }
        table.compare th, table.compare td { padding:.85rem 1rem; text-align:left; border-bottom:1px solid var(--gray-soft); }
        table.compare th { font-weight:600; color:var(--gray-mid); font-size:.72rem; text-transform:uppercase; letter-spacing:.04em; }
        table.compare td.center, table.compare th.center { text-align:center; }
        table.compare tr:last-child td { border-bottom:none; }
        table.compare tr:hover td { background:var(--pearl); }
        table.compare .t-check { width:1.05rem; height:1.05rem; color:#0F7A54; display:inline-block; vertical-align:middle; }
        table.compare .t-dash { color:#C3C8D1; font-weight:600; }

        /* ── Footer ── */
        .foot-link { font-size:.82rem; color:rgba(255,255,255,.5); text-decoration:none; transition:color .2s ease; }
        .foot-link:hover { color:white; }
        .foot-social {
            width:2.15rem; height:2.15rem; border-radius:.6rem; display:flex; align-items:center; justify-content:center;
            background:rgba(255,255,255,.06); color:rgba(255,255,255,.6); transition:background .2s ease, color .2s ease;
        }
        .foot-social:hover { background:var(--blue-electric); color:white; }
    </style>
</head>
<body>

    {{-- Header --}}
    <header style="background:var(--blue-night); border-bottom:1px solid rgba(255,255,255,.06);">
        <div style="max-width:64rem; margin:0 auto; padding:1.1rem 1.5rem; display:flex; align-items:center; justify-content:space-between;">
            <a href="{{ route('home') }}" style="display:flex; align-items:center; gap:.6rem; text-decoration:none;" class="logo-glow">
                <span class="font-display" style="font-size:1.15rem; color:white; position:relative;">Expo<span style="color:var(--gold-light);">DKR</span></span>
            </a>
            <a href="{{ route('home') }}" style="font-size:.8rem; color:rgba(255,255,255,.55); text-decoration:none; transition: color .2s ease;"
               onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,.55)'">
                ← Retour à l'accueil
            </a>
        </div>
    </header>

    {{-- Hero --}}
    <div style="background:var(--blue-night); padding:4rem 1.5rem 3.5rem; text-align:center; position:relative; overflow:hidden;">
        <div class="hero-grid-overlay" style="position:absolute; inset:0;" aria-hidden="true"></div>
        <div class="hero-glow" style="width:22rem; height:22rem; top:-8rem; right:-4rem; background:var(--gold); opacity:.14;" aria-hidden="true"></div>
        <div class="hero-glow" style="width:18rem; height:18rem; bottom:-9rem; left:-3rem; background:var(--blue-electric); opacity:.16; animation-delay:-6s;" aria-hidden="true"></div>

        <div style="position:relative; z-index:1; max-width:40rem; margin:0 auto;" x-data x-intersect.once="$el.classList.add('visible')" class="reveal">
            <p style="font-size:.72rem; font-weight:600; letter-spacing:.14em; text-transform:uppercase; color:var(--gold-light); margin-bottom:1.1rem;">Tarification</p>
            <h1 class="font-display" style="font-size:2.5rem; color:white; margin-bottom:1rem; line-height:1.2;">
                Des formules claires pour chaque niveau d'ambition
            </h1>
            <p style="font-size:.95rem; color:rgba(255,255,255,.55); line-height:1.6;">
                Aucun abonnement mensuel. Vous réglez une fois, au moment de créer votre événement.
            </p>
        </div>
    </div>

    {{-- Grille packs --}}
    <div style="max-width:76rem; margin:0 auto; padding:3.5rem 1.5rem 2rem;">
        <style>@media(min-width:1024px){.packs-grid{grid-template-columns:repeat(4,1fr)!important;}}</style>

        <div class="packs-grid" style="display:grid; grid-template-columns:1fr; gap:1.25rem;">

         
            {{-- PACK ESSENTIEL --}}
            <div class="card reveal reveal-delay-1" x-data x-intersect.once="$el.classList.add('visible')">
                <div style="padding:1.75rem; display:flex; flex-direction:column; flex:1;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:.4rem;">
                        <p class="section-label" style="margin:0;">Essentiel</p>
                        <span style="
                            background:#dc2626;
                            color:white;
                            padding:.3rem .65rem;
                            border-radius:999px;
                            font-size:.7rem;
                            font-weight:700;
                            letter-spacing:.03em;
                        ">
                            -25%
                        </span>
                    </div>

                    <p style="font-size:.82rem; color:var(--gray-mid); line-height:1.6; margin-bottom:1.5rem; min-height:2.6rem;">
                        Être présent sur la plateforme et présenter son activité.
                    </p>

                    <div style="margin-bottom:1.75rem; padding-bottom:1.5rem; border-bottom:1px solid var(--gray-soft);">

                        {{-- Ancien prix --}}
                        <div style="margin-bottom:.15rem;">
                            <span style="
                                font-size:.9rem;
                                color:var(--gray-mid);
                                text-decoration:line-through;
                            ">
                                100 000 FCFA
                            </span>
                        </div>

                        {{-- Nouveau prix --}}
                        <span class="font-display" style="font-size:1.9rem;">
                            75 000
                        </span>

                        <span style="font-size:.8rem; color:var(--gray-mid);">
                            FCFA / événement
                        </span>

                    </div>

                    <ul style="display:flex; flex-direction:column; gap:.7rem; margin-bottom:1.5rem;">
                        <li class="feat-li">
                            <svg class="check" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                            </svg>
                            Fiche entreprise
                        </li>

                        <li class="feat-li">
                            <svg class="check" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                            </svg>
                            Logo et description
                        </li>

                        <li class="feat-li">
                            <svg class="check" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                            </svg>
                            Formulaire de contact
                        </li>

                        <li class="feat-li-off">
                            <svg class="cross" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                            </svg>
                            Bannière publicitaire
                        </li>
                    </ul>

                    <a href="#" class="cta">Choisir ce pack</a>
                </div>
            </div>



            {{-- PACK PROFESSIONNEL --}}
            <div class="reveal reveal-delay-2" x-data x-intersect.once="$el.classList.add('visible')">
                <div class="card card-pop" style="height:100%;">
                    <div class="badge-pop" style="position:absolute; top:0; right:1.5rem;">Recommandé</div>
                    <div style="padding:1.75rem; padding-top:2.2rem; display:flex; flex-direction:column; flex:1;">
                         <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:.4rem;">
                            <p class="section-label" style="margin:0;">Professionnel</p>
                            <span style="
                                background:#dc2626;
                                color:white;
                                padding:.3rem .65rem;
                                border-radius:999px;
                                font-size:.7rem;
                                font-weight:700;
                                letter-spacing:.03em;
                            ">
                                -25%
                            </span>
                        </div>

                    <p style="font-size:.82rem; color:var(--gray-mid); line-height:1.6; margin-bottom:1.5rem; min-height:2.6rem;">
                        Développer sa visibilité et générer des contacts qualifiés.
                    </p>

                    <div style="margin-bottom:1.75rem; padding-bottom:1.5rem; border-bottom:1px solid var(--gray-soft);">

                        {{-- Ancien prix --}}
                        <div style="margin-bottom:.15rem;">
                            <span style="
                                font-size:.9rem;
                                color:var(--gray-mid);
                                text-decoration:line-through;
                            ">
                                200 000 FCFA
                            </span>
                        </div>

                        {{-- Nouveau prix --}}
                        <span class="font-display" style="font-size:1.9rem;">
                            150 000
                        </span>

                        <span style="font-size:.8rem; color:var(--gray-mid);">
                            FCFA / événement
                        </span>

                    </div>

                        <p style="font-size:.75rem; font-weight:600; color:var(--gray-mid); margin-bottom:.7rem;">Tout l'Essentiel, plus :</p>
                        <ul style="display:flex; flex-direction:column; gap:.7rem; margin-bottom:1.5rem;">
                            <li class="feat-li"><svg class="check" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>Galerie photos et vidéos</li>
                            <li class="feat-li"><svg class="check" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>Badge « Exposant vérifié »</li>
                            <li class="feat-li"><svg class="check" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>Publication d'actualités sur les réseaux</li>
                            <li class="feat-li"><svg class="check" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>Statistiques de consultation</li>
                            <li class="feat-li"><svg class="check" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>Mise en avant dans la recherche</li>
                        </ul>

                        <a href="#" class="cta cta-pop">Choisir ce pack</a>
                    </div>
                </div>
            </div>

            {{-- PACK PREMIUM --}}
            <div class="card reveal reveal-delay-3" x-data x-intersect.once="$el.classList.add('visible')">
                <div style="padding:1.75rem; display:flex; flex-direction:column; flex:1;">
                     <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:.4rem;">
                            <p class="section-label" style="margin:0;">Premium</p>
                            <span style="
                                background:#dc2626;
                                color:white;
                                padding:.3rem .65rem;
                                border-radius:999px;
                                font-size:.7rem;
                                font-weight:700;
                                letter-spacing:.03em;
                            ">
                                -25%
                            </span>
                        </div>

                    <p style="font-size:.82rem; color:var(--gray-mid); line-height:1.6; margin-bottom:1.5rem; min-height:2.6rem;">
                        Maximiser sa visibilité et attirer des prospects qualifiés.
                    </p>
                    <div style="margin-bottom:1.75rem; padding-bottom:1.5rem; border-bottom:1px solid var(--gray-soft);">

                                {{-- Ancien prix --}}
                                <div style="margin-bottom:.15rem;">
                                    <span style="
                                        font-size:.9rem;
                                        color:var(--gray-mid);
                                        text-decoration:line-through;
                                    ">
                                        500 000 FCFA
                                    </span>
                                </div>

                                {{-- Nouveau prix --}}
                                <span class="font-display" style="font-size:1.9rem;">
                                    275 000
                                </span>

                                <span style="font-size:.8rem; color:var(--gray-mid);">
                                    FCFA / événement
                                </span>
                                <br>

                        </div>

                    <p style="font-size:.75rem; font-weight:600; color:var(--gray-mid); margin-bottom:.7rem;">Tout le Professionnel, plus :</p>
                    <ul style="display:flex; flex-direction:column; gap:.7rem; margin-bottom:1.5rem;">
                        <li class="feat-li"><svg class="check" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>Page entreprise personnalisée</li>
                        <li class="feat-li"><svg class="check" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>Catalogue PDF téléchargeable</li>
                        <li class="feat-li"><svg class="check" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>Bannière sur le site</li>
                        <li class="feat-li"><svg class="check" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>Communication compléte de l'événement</li>
                        <li class="feat-li"><svg class="check" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>Statistiques avancées</li>
                    </ul>

                    <a href="#" class="cta">Choisir ce pack</a>
                </div>
            </div>

            {{-- PACK PARTENAIRE VIP --}}
            <div class="card reveal reveal-delay-4" x-data x-intersect.once="$el.classList.add('visible')">
                <div style="padding:1.75rem; display:flex; flex-direction:column; flex:1;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:.4rem;">
                            <p class="section-label" style="margin:0;">Partenaire VIP</p>
                            <span style="
                                background:#dc2626;
                                color:white;
                                padding:.3rem .65rem;
                                border-radius:999px;
                                font-size:.7rem;
                                font-weight:700;
                                letter-spacing:.03em;
                            ">
                                -25%
                            </span>
                        </div>

                    <p style="font-size:.82rem; color:var(--gray-mid); line-height:1.6; margin-bottom:1.5rem; min-height:2.6rem;">
                        Devenir un acteur majeur avec une visibilité premium.
                    </p>
                    <div style="margin-bottom:1.75rem; padding-bottom:1.5rem; border-bottom:1px solid var(--gray-soft);">

                                {{-- Ancien prix --}}
                                <div style="margin-bottom:.15rem;">
                                    <span style="
                                        font-size:.9rem;
                                        color:var(--gray-mid);
                                        text-decoration:line-through;
                                    ">
                                         2 000 000 FCFA
                                    </span>
                                </div>

                                {{-- Nouveau prix --}}
                                <span class="font-display" style="font-size:1.9rem;">
                                     1 500 000
                                </span>

                                <span style="font-size:.8rem; color:var(--gray-mid);">
                                    FCFA / événement
                                </span>

                        </div>

                    <p style="font-size:.75rem; font-weight:600; color:var(--gray-mid); margin-bottom:.7rem;">Tout le Premium, plus :</p>
                    <ul style="display:flex; flex-direction:column; gap:.7rem; margin-bottom:1.5rem;">
                        <li class="feat-li"><svg class="check" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>Statut « Partenaire officiel »</li>
                        <li class="feat-li"><svg class="check" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>Gestionnaire de compte dédié</li>
                        <li class="feat-li"><svg class="check" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>Bannière Premium en page d'accueil</li>
                        <li class="feat-li"><svg class="check" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>Article sponsorisé</li>
                        <li class="feat-li"><svg class="check" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>Rapport complet de performance</li>
                        <li class="feat-li"><svg class="check" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>Comminucation avant  pendant aprés  </li>
                    </ul>

                    <a href="#" class="cta">Choisir ce pack</a>
                </div>
            </div>

        </div>

        <p class="reveal" x-data x-intersect.once="$el.classList.add('visible')" style="text-align:center; font-size:.8rem; color:var(--gray-mid); margin-top:2.5rem;">
            Besoin d'un accompagnement sur-mesure ? <a href="#" style="color:var(--blue-electric); font-weight:600;">Contactez notre équipe</a>
        </p>
    </div>

    {{-- Tableau comparatif condensé --}}
    <div style="max-width:76rem; margin:0 auto; padding:1rem 1.5rem 4rem;">
        <div class="reveal" x-data x-intersect.once="$el.classList.add('visible')" style="background:white; border-radius:1.25rem; border:1px solid var(--gray-soft); overflow-x:auto;">
            <table class="compare">
                <thead>
                    <tr>
                        <th>Fonctionnalité</th>
                        <th class="center">Essentiel</th>
                        <th class="center">Professionnel</th>
                        <th class="center">Premium</th>
                        <th class="center">VIP</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $tCheck = '<svg class="t-check" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>';
                        $tDash  = '<span class="t-dash">—</span>';
                    @endphp
                    <tr>
                        <td>Fiche entreprise</td>
                        <td class="center">{!! $tCheck !!}</td><td class="center">{!! $tCheck !!}</td><td class="center">{!! $tCheck !!}</td><td class="center">{!! $tCheck !!}</td>
                    </tr>
                    <tr>
                        <td>Bannière publicitaire</td>
                        <td class="center">{!! $tDash !!}</td><td class="center">{!! $tDash !!}</td><td class="center">{!! $tCheck !!}</td><td class="center">{!! $tCheck !!}</td>
                    </tr>
                    <tr>
                        <td>Mise en avant page d'accueil</td>
                        <td class="center">{!! $tDash !!}</td><td class="center">{!! $tDash !!}</td><td class="center">{!! $tCheck !!}</td><td class="center">{!! $tCheck !!}</td>
                    </tr>
                    <tr>
                        <td>Gestionnaire de compte dédié</td>
                        <td class="center">{!! $tDash !!}</td><td class="center">{!! $tDash !!}</td><td class="center">{!! $tDash !!}</td><td class="center">{!! $tCheck !!}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Espaces publicitaires à la carte --}}
    <div style="background:var(--gray-soft); padding:4rem 1.5rem;">
        <div style="max-width:76rem; margin:0 auto;">
            <div class="reveal" x-data x-intersect.once="$el.classList.add('visible')" style="text-align:center; margin-bottom:2.5rem;">
                <p class="section-label" style="color:var(--blue-electric);">À la carte</p>
                <h2 class="font-display" style="font-size:1.85rem; margin-bottom:.6rem;">Espaces publicitaires indépendants</h2>
                <p style="font-size:.88rem; color:var(--gray-mid); max-width:34rem; margin:0 auto;">Boostez votre visibilité indépendamment de votre pack, à l'unité ou au mois.</p>
            </div>

            <style>@media(min-width:768px){.ads-grid{grid-template-columns:repeat(3,1fr)!important;}}</style>
            <div class="ads-grid" style="display:grid; grid-template-columns:1fr; gap:1.1rem;">

                <div class="ad-card reveal reveal-delay-1" x-data x-intersect.once="$el.classList.add('visible')" style="background:white; border-radius:1rem; padding:1.4rem; border:1px solid var(--gray-soft);">
                    <h4 style="font-size:.95rem; font-weight:600; margin-bottom:.2rem;">Bannière Header</h4>
                    <p style="font-size:.72rem; color:var(--gray-mid); margin-bottom:.7rem;">Haut du site</p>
                    <p style="font-size:1.15rem; font-weight:700; margin-bottom:.4rem;">150 000 <span style="font-size:.7rem; font-weight:400; color:var(--gray-mid);">FCFA/mois</span></p>
                    <p style="font-size:.78rem; color:var(--gray-mid);">Présente sur toutes les pages principales.</p>
                </div>

                <div class="ad-card reveal reveal-delay-2" x-data x-intersect.once="$el.classList.add('visible')" style="background:white; border-radius:1rem; padding:1.4rem; border:1px solid var(--gray-soft);">
                    <h4 style="font-size:.95rem; font-weight:600; margin-bottom:.2rem;">Bannière page d'accueil</h4>
                    <p style="font-size:.72rem; color:var(--gray-mid); margin-bottom:.7rem;">Emplacement premium</p>
                    <p style="font-size:1.15rem; font-weight:700; margin-bottom:.4rem;">100 000 <span style="font-size:.7rem; font-weight:400; color:var(--gray-mid);">FCFA/mois</span></p>
                    <p style="font-size:.78rem; color:var(--gray-mid);">Forte visibilité auprès des visiteurs.</p>
                </div>

                <div class="ad-card reveal reveal-delay-3" x-data x-intersect.once="$el.classList.add('visible')" style="background:white; border-radius:1rem; padding:1.4rem; border:1px solid var(--gray-soft);">
                    <h4 style="font-size:.95rem; font-weight:600; margin-bottom:.2rem;">Encadré catégorie</h4>
                    <p style="font-size:.72rem; color:var(--gray-mid); margin-bottom:.7rem;">Audience ciblée</p>
                    <p style="font-size:1.15rem; font-weight:700; margin-bottom:.4rem;">75 000 <span style="font-size:.7rem; font-weight:400; color:var(--gray-mid);">FCFA/mois</span></p>
                    <p style="font-size:.78rem; color:var(--gray-mid);">Visible auprès d'un segment ciblé.</p>
                </div>

                <div class="ad-card reveal reveal-delay-1" x-data x-intersect.once="$el.classList.add('visible')" style="background:white; border-radius:1rem; padding:1.4rem; border:1px solid var(--gray-soft);">
                    <h4 style="font-size:.95rem; font-weight:600; margin-bottom:.2rem;">Article sponsorisé</h4>
                    <p style="font-size:.72rem; color:var(--gray-mid); margin-bottom:.7rem;">Présentation dédiée</p>
                    <p style="font-size:1.15rem; font-weight:700;">100 000 <span style="font-size:.7rem; font-weight:400; color:var(--gray-mid);">FCFA</span></p>
                </div>

                <div class="ad-card reveal reveal-delay-2" x-data x-intersect.once="$el.classList.add('visible')" style="background:white; border-radius:1rem; padding:1.4rem; border:1px solid var(--gray-soft);">
                    <h4 style="font-size:.95rem; font-weight:600; margin-bottom:.2rem;">Publication sponsorisée</h4>
                    <p style="font-size:.72rem; color:var(--gray-mid); margin-bottom:.7rem;">Réseaux sociaux</p>
                    <p style="font-size:1.15rem; font-weight:700; margin-bottom:.4rem;">50 000 <span style="font-size:.7rem; font-weight:400; color:var(--gray-mid);">FCFA</span></p>
                    <p style="font-size:.78rem; color:var(--gray-mid);">Facebook, Instagram, LinkedIn.</p>
                </div>

                <div class="ad-card reveal reveal-delay-3" x-data x-intersect.once="$el.classList.add('visible')" style="background:var(--blue-night); border-radius:1rem; padding:1.4rem; border:1px solid var(--blue-night);">
                    <h4 style="font-size:.95rem; font-weight:600; margin-bottom:.2rem; color:white;">Pack Visibilité Entreprise</h4>
                    <p style="font-size:.72rem; color:rgba(255,255,255,.55); margin-bottom:.7rem;">Formule combinée</p>
                    <p style="font-size:1.15rem; font-weight:700; margin-bottom:.4rem; color:var(--gold-light);">250 000 <span style="font-size:.7rem; font-weight:400; color:rgba(255,255,255,.55);">FCFA/mois</span></p>
                    <p style="font-size:.78rem; color:rgba(255,255,255,.7);">Bannière + publication + mise en avant.</p>
                </div>

            </div>
        </div>
    </div>

    {{-- ══ FOOTER ══ --}}
    <footer style="background:var(--blue-night);">
        <div style="max-width:76rem; margin:0 auto; padding:3.5rem 1.5rem 2rem;">
            <div style="display:grid; grid-template-columns:1fr; gap:2.5rem; padding-bottom:2.5rem; border-bottom:1px solid rgba(255,255,255,.08);">
                <style>@media(min-width:768px){.foot-grid{grid-template-columns:1.4fr 1fr 1fr!important;}}</style>
                <div class="foot-grid" style="display:grid; grid-template-columns:1fr; gap:2.5rem;">

                    <div>
                        <span class="font-display" style="font-size:1.2rem; color:white;">Expo<span style="color:var(--gold-light);">DKR</span></span>
                        <p style="font-size:.82rem; color:rgba(255,255,255,.45); line-height:1.65; margin-top:.9rem; max-width:22rem;">
                            La plateforme événementielle de référence pour les salons, conférences et forums professionnels au Sénégal.
                        </p>
                    </div>

                    <div>
                        <h3 style="font-size:.72rem; font-weight:600; letter-spacing:.08em; text-transform:uppercase; color:rgba(255,255,255,.35); margin-bottom:1rem;">Liens utiles</h3>
                        <ul style="display:flex; flex-direction:column; gap:.65rem;">
                            <li><a href="{{ route('home') }}" class="foot-link">Accueil</a></li>
                            <li><a href="{{ route('user.events.index') }}" class="foot-link">Événements</a></li>
                            <li><a href="{{ route('tarifs') }}" class="foot-link">Tarifs</a></li>
                            <li><a href="{{ route('contact') }}" class="foot-link">Contact</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 style="font-size:.72rem; font-weight:600; letter-spacing:.08em; text-transform:uppercase; color:rgba(255,255,255,.35); margin-bottom:1rem;">Nous suivre</h3>
                        <div style="display:flex; gap:.6rem;">
                            <a href="#" class="foot-social" aria-label="Facebook">
                                <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                            </a>
                            <a href="#" class="foot-social" aria-label="LinkedIn">
                                <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6zM2 9h4v12H2z M4 6a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/></svg>
                            </a>
                            <a href="#" class="foot-social" aria-label="Instagram">
                                <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="3.5"/><circle cx="17.4" cy="6.6" r="1"/></svg>
                            </a>
                        </div>
                        <p style="font-size:.78rem; color:rgba(255,255,255,.4); margin-top:1.2rem;">Dakar, Plateau</p>
                        <a href="mailto:contact@expodakar.sn" class="foot-link" style="display:block; margin-top:.35rem;">contact@expodakar.sn</a>
                    </div>
                </div>
            </div>

            <div style="padding-top:1.5rem; display:flex; flex-direction:column; align-items:center; gap:.5rem; text-align:center;">
                <style>@media(min-width:640px){.foot-bottom{flex-direction:row!important;justify-content:space-between!important;text-align:left!important;}}</style>
                <div class="foot-bottom" style="display:flex; flex-direction:column; gap:.5rem; width:100%;">
                    <p style="font-size:.75rem; color:rgba(255,255,255,.3);">© {{ date('Y') }} ExpoDKR. Tous droits réservés.</p>
                    <p style="font-size:.75rem; color:rgba(255,255,255,.25);">Conçu au Sénégal</p>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
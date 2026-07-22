<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Organisateur · ExpoDKR</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

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
        * { box-sizing: border-box; margin:0; padding:0; }
        body { font-family:'Inter',sans-serif; background:var(--pearl); color:var(--blue-night); -webkit-font-smoothing:antialiased; min-height:100vh; }
        .font-display { font-family:'Instrument Serif',serif; }
        [x-cloak]     { display:none!important; }

        /* Sidebar */
        .sidebar { width:16rem; background:var(--blue-night); height:100vh; position:fixed; left:0; top:0; z-index:40; display:flex; flex-direction:column; transition:transform .3s ease; }
        .sidebar-link { display:flex; align-items:center; gap:.875rem; padding:.875rem 1.25rem; border-radius:1rem; font-size:.825rem; font-weight:500; color:rgba(255,255,255,.55); text-decoration:none; transition:all .2s; cursor:pointer; border:none; background:none; font-family:'Inter',sans-serif; width:100%; text-align:left; }
        .sidebar-link:hover { color:white; background:rgba(255,255,255,.08); }
        .sidebar-link.active { color:white; background:rgba(30,95,216,.5); }
        .sidebar-link .icon { width:1rem; height:1rem; flex-shrink:0; }

        /* Main layout */
        .main-wrap { margin-left:16rem; min-height:100vh; }
        @media(max-width:1024px) {
            .sidebar    { transform:translateX(-100%); }
            .sidebar.open { transform:translateX(0); }
            .main-wrap  { margin-left:0; }
            .page-pb    { padding-bottom:5rem; }
        }

        /* Cards */
        .card { background:white; border-radius:1.5rem; border:1px solid var(--gray-soft); box-shadow:0 2px 16px rgba(10,22,40,.05); }
        .stat-card { transition:transform .25s, box-shadow .25s; }
        .stat-card:hover { transform:translateY(-3px); box-shadow:0 12px 36px rgba(10,22,40,.1); }

        /* Badge statuts */
        .badge { display:inline-flex; align-items:center; gap:.375rem; font-size:.7rem; font-weight:700; padding:.3rem .75rem; border-radius:2rem; }

        /* Bottom nav mobile */
        .bottom-nav { display:none; position:fixed; bottom:0; left:0; right:0; z-index:50; background:white; border-top:1px solid var(--gray-soft); padding:.625rem 1.25rem; }
        @media(max-width:1024px) { .bottom-nav { display:flex; gap:.25rem; } }

        /* Scrollbar */
        ::-webkit-scrollbar { width:4px; }
        ::-webkit-scrollbar-thumb { background:var(--blue-electric); border-radius:99px; }

        /* Pulse */
        @keyframes pulse-dot { 0%,100%{opacity:1;} 50%{opacity:.35;} }
    </style>
</head>
<body>

{{--
|--------------------------------------------------------------------------
| ExpoDKR – Dashboard Organisateur
| Variables attendues :
|   Auth::user()->name, ->email, ->created_at
|   $stats = [totalEvents, totalReservations, totalRevenu, totalParticipants]
|   $events     → collection Eloquent avec ->reservations, ->categorie
|   $reservations → 10 dernières réservations avec ->evenement
|   $chartData  → ['labels'=>[], 'data'=>[]] pour le graphique revenus
|--------------------------------------------------------------------------
--}}

<div x-data="{
        sidebarOpen: false,
        activeTab: 'dashboard',
        notifOpen: false,
        toastMsg: '', toastShow: false,
        showToast(msg) { this.toastMsg=msg; this.toastShow=true; setTimeout(()=>this.toastShow=false,3000); }
     }"
     class="page-pb">


    {{-- ══════════════════════════════════════════════
         SIDEBAR
         ══════════════════════════════════════════════ --}}
    <aside class="sidebar" :class="sidebarOpen ? 'open' : ''" id="sidebar">

        {{-- Logo --}}
        <div style="padding:1.5rem 1.25rem 1rem; border-bottom:1px solid rgba(255,255,255,.06);">
            <a href="{{ route('home') }}" style="display:flex; align-items:center; gap:.75rem; text-decoration:none;">
                <div style="width:2.25rem; height:2.25rem; border-radius:.875rem; background:linear-gradient(135deg,var(--blue-electric),#1248b0); display:flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(30,95,216,.4);">
                    <svg style="width:1rem;height:1rem;color:white;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18"/></svg>
                </div>
                <span class="font-display" style="font-size:1.2rem; color:white;">
                    Expo<span style="background:linear-gradient(135deg,var(--gold),var(--gold-light));-webkit-background-clip:text;-webkit-text-fill-color:transparent;">DKR</span>
                </span>
            </a>
            <div style="display:flex; align-items:center; gap:.5rem; margin-top:.875rem; padding:.5rem .75rem; border-radius:.875rem; background:rgba(201,168,76,.1); border:1px solid rgba(201,168,76,.2);">
                <span style="width:.5rem; height:.5rem; border-radius:50%; background:var(--gold); animation:pulse-dot 2s infinite; flex-shrink:0;"></span>
                <span style="font-size:.68rem; font-weight:600; letter-spacing:.12em; text-transform:uppercase; color:var(--gold-light);">Espace Organisateur</span>
            </div>
        </div>

        {{-- Navigation --}}
        <nav style="flex:1; overflow-y:auto; padding:1rem .875rem; display:flex; flex-direction:column; gap:.25rem;">

            <p style="font-size:.62rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase; color:rgba(255,255,255,.25); padding:.5rem .5rem .375rem; margin-top:.25rem;">Principal</p>

            @foreach([
                ['dashboard',    'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z', 'Dashboard'],
                ['evenements',   'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5', 'Mes événements'],
                ['reservations', 'M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026', 'Réservations'],
                ['participants', 'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z', 'Participants'],
                ['revenus',      'M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z', 'Revenus'],
            ] as [$tab, $icon, $label])
            <button @click="activeTab='{{ $tab }}'; sidebarOpen=false"
                    class="sidebar-link" :class="activeTab==='{{ $tab }}' ? 'active' : ''">
                <svg class="icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/>
                </svg>
                {{ $label }}
                @if($tab === 'reservations')
                <span style="margin-left:auto; font-size:.65rem; font-weight:700; padding:.2rem .5rem; border-radius:2rem; background:rgba(30,95,216,.5); color:white;">
                    {{ ($stats['reservationsEnAttente'] ?? 0) }}
                </span>
                @endif
            </button>
            @endforeach

            <p style="font-size:.62rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase; color:rgba(255,255,255,.25); padding:.5rem .5rem .375rem; margin-top:.75rem;">Compte</p>

            @foreach([
                ['profil',  'M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z', 'Mon profil'],
                ['parametres','M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z', 'Paramètres'],
            ] as [$tab, $icon, $label])
            <button @click="activeTab='{{ $tab }}'; sidebarOpen=false"
                    class="sidebar-link" :class="activeTab==='{{ $tab }}' ? 'active' : ''">
                <svg class="icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/>
                </svg>
                {{ $label }}
            </button>
            @endforeach
        </nav>

        {{-- User footer --}}
        <div style="padding:1rem .875rem; border-top:1px solid rgba(255,255,255,.06);">
            <div style="display:flex; align-items:center; gap:.75rem; padding:.75rem; border-radius:1.25rem; background:rgba(255,255,255,.05);">
                <div style="width:2.25rem; height:2.25rem; border-radius:50%; background:linear-gradient(135deg,var(--blue-electric),var(--gold)); display:flex; align-items:center; justify-content:center; font-size:.875rem; font-weight:800; color:white; flex-shrink:0;">
                    {{ strtoupper(substr(Auth::user()->name ?? 'O', 0, 1)) }}
                </div>
                <div style="flex:1; min-width:0;">
                    <p style="font-size:.8rem; font-weight:600; color:white; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ Auth::user()->name ?? 'Organisateur' }}</p>
                    <p style="font-size:.68rem; color:rgba(255,255,255,.4); overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ Auth::user()->email ?? '' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" style="width:1.75rem; height:1.75rem; border-radius:.5rem; background:rgba(255,255,255,.08); border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:background .2s;" onmouseover="this.style.background='rgba(220,38,38,.3)'" onmouseout="this.style.background='rgba(255,255,255,.08)'" aria-label="Se déconnecter">
                        <svg style="width:.875rem;height:.875rem;color:rgba(255,255,255,.5);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Overlay mobile --}}
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen=false"
         style="position:fixed; inset:0; background:rgba(10,22,40,.5); backdrop-filter:blur(4px); z-index:39; display:none;"
         class="lg:hidden" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>


    {{-- ══════════════════════════════════════════════
         CONTENU PRINCIPAL
         ══════════════════════════════════════════════ --}}
    <div class="main-wrap">

        {{-- Topbar --}}
        <div style="background:white; border-bottom:1px solid var(--gray-soft); padding:0 1.5rem; height:4rem; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:30;">

            {{-- Burger --}}
            <button @click="sidebarOpen=!sidebarOpen"
                    class="lg:hidden"
                    style="width:2.25rem; height:2.25rem; border-radius:.75rem; border:1.5px solid var(--gray-soft); background:white; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:border-color .2s;"
                    aria-label="Menu">
                <svg style="width:1rem;height:1rem;color:var(--blue-night);" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
            </button>

            {{-- Titre page --}}
            <div style="display:flex; align-items:center; gap:.75rem;">
                <p style="font-size:.9rem; font-weight:700; color:var(--blue-night);"
                   x-text="{
                       dashboard:    'Vue d\'ensemble',
                       evenements:   'Mes événements',
                       reservations: 'Réservations',
                       participants: 'Participants',
                       revenus:      'Revenus',
                       profil:       'Mon profil',
                       parametres:   'Paramètres'
                   }[activeTab]">
                </p>
            </div>

            {{-- Droite topbar --}}
            <div style="display:flex; align-items:center; gap:.75rem;">

                {{-- Notifs --}}
                <div style="position:relative;" x-data="{ open:false }" @click.outside="open=false">
                    <button @click="open=!open"
                            style="position:relative; width:2.25rem; height:2.25rem; border-radius:.875rem; border:1.5px solid var(--gray-soft); background:white; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:border-color .2s;"
                            onmouseover="this.style.borderColor='var(--blue-electric)'" onmouseout="this.style.borderColor='var(--gray-soft)'"
                            aria-label="Notifications">
                        <svg style="width:1rem;height:1rem;color:var(--blue-night);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/></svg>
                        <span style="position:absolute; top:.2rem; right:.2rem; width:.6rem; height:.6rem; border-radius:50%; background:var(--blue-electric); border:2px solid white;"></span>
                    </button>

                    <div x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         style="position:absolute; right:0; top:calc(100% + .5rem); width:20rem; background:white; border-radius:1.25rem; border:1px solid var(--gray-soft); box-shadow:0 8px 32px rgba(10,22,40,.12); overflow:hidden;">
                        <div style="padding:1rem 1.25rem; border-bottom:1px solid var(--gray-soft); display:flex; align-items:center; justify-content:space-between;">
                            <p style="font-size:.825rem; font-weight:700; color:var(--blue-night);">Notifications</p>
                            <span style="font-size:.65rem; font-weight:700; padding:.2rem .625rem; border-radius:2rem; background:#EFF6FF; color:var(--blue-electric);">3 nouvelles</span>
                        </div>
                        @foreach([
                            ['🎫', 'Nouvelle réservation', 'Tech Forum 2026 · 2 places', '5 min',  '#EFF6FF'],
                            ['👤', 'Nouvel participant',   'Mariama Bah vient de s\'inscrire', '12 min', '#F5F3FF'],
                            ['💰', 'Paiement reçu',       '85 000 FCFA confirmé via Wave', '1h',    '#ECFDF5'],
                        ] as [$emoji, $title, $desc, $time, $bg])
                        <div style="display:flex; gap:.875rem; padding:.875rem 1.25rem; transition:background .2s; cursor:pointer;" onmouseover="this.style.background='var(--pearl)'" onmouseout="this.style.background='white'">
                            <div style="width:2.25rem; height:2.25rem; border-radius:.875rem; background:{{ $bg }}; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0;">{{ $emoji }}</div>
                            <div style="flex:1; min-width:0;">
                                <p style="font-size:.8rem; font-weight:600; color:var(--blue-night);">{{ $title }}</p>
                                <p style="font-size:.72rem; color:var(--gray-mid); margin-top:.1rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $desc }}</p>
                            </div>
                            <span style="font-size:.65rem; color:var(--gray-mid); flex-shrink:0;">{{ $time }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Créer événement image--}}
                <a href="{{ route('organisateur.events.create') }}"
                   style="display:flex; align-items:center; gap:.5rem; padding:.5rem 1.1rem; border-radius:.875rem; font-size:.78rem; font-weight:700; color:white; text-decoration:none; background:linear-gradient(135deg,var(--blue-electric),#1248b0); box-shadow:0 4px 12px rgba(30,95,216,.25); transition:filter .2s; white-space:nowrap;"
                   onmouseover="this.style.filter='brightness(1.1)'" onmouseout="this.style.filter='none'">
                    <svg style="width:.8rem;height:.8rem;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Créer un événement
                </a>
            </div>
        </div>


        {{-- ══════════════════════════════════════════
             ONGLET : DASHBOARD
             ══════════════════════════════════════════ --}}
        <div x-show="activeTab === 'dashboard'" style="padding:2rem 1.5rem;">

            {{-- Bonjour --}}
            <div style="margin-bottom:2rem;">
                <h1 class="font-display" style="font-size:1.8rem; color:var(--blue-night); margin-bottom:.3rem;">
                    Bonjour, {{ Auth::user()->name ?? 'Organisateur' }} 
                </h1>
                <p style="font-size:.875rem; color:var(--gray-mid);">
                    {{ now()->translatedFormat('l d F Y') }} · Voici un résumé de vos activités
                </p>
            </div>

            {{-- KPI Cards --}}
            <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:1rem; margin-bottom:2rem;">
                <style>@media(min-width:640px){.kpi-grid{grid-template-columns:repeat(4,1fr)!important;}}</style>
                <div class="kpi-grid" style="display:grid; grid-template-columns:repeat(2,1fr); gap:1rem; grid-column:1/-1;">

                    @php
                        $kpis = [
                            ['label'=>'Événements',    'value'=> $stats['totalEvents']        ?? 12,    'change'=>'+2',    'color'=>'#2563EB', 'bg'=>'#EFF6FF', 'icon'=>'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25'],
                            ['label'=>'Réservations',  'value'=> $stats['totalReservations']  ?? 248,   'change'=>'+34',   'color'=>'#7C3AED', 'bg'=>'#F5F3FF', 'icon'=>'M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026'],
                            ['label'=>'Participants',  'value'=> $stats['totalParticipants']  ?? 520,   'change'=>'+76',   'color'=>'#059669', 'bg'=>'#ECFDF5', 'icon'=>'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Z'],
                            ['label'=>'Revenus (FCFA)','value'=> number_format($stats['totalRevenu'] ?? 2850000, 0, ',', ' '), 'change'=>'+22%', 'color'=>'#D97706', 'bg'=>'#FFFBEB', 'icon'=>'M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75'],
                        ];
                    @endphp

                    @foreach($kpis as $i => $kpi)
                    <div class="card stat-card" style="padding:1.5rem;">
                        <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:1rem;">
                            <div style="width:2.75rem; height:2.75rem; border-radius:1rem; background:{{ $kpi['bg'] }}; display:flex; align-items:center; justify-content:center;">
                                <svg style="width:1.25rem;height:1.25rem;" fill="none" stroke="{{ $kpi['color'] }}" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $kpi['icon'] }}"/>
                                </svg>
                            </div>
                            <span style="font-size:.7rem; font-weight:700; padding:.25rem .625rem; border-radius:2rem; background:#ECFDF5; color:#059669;">{{ $kpi['change'] }} ce mois</span>
                        </div>
                        <p style="font-size:1.75rem; font-weight:800; color:var(--blue-night); line-height:1;">{{ $kpi['value'] }}</p>
                        <p style="font-size:.75rem; color:var(--gray-mid); margin-top:.35rem; font-weight:500;">{{ $kpi['label'] }}</p>
                    </div>
                    @endforeach

                </div>
            </div>


            {{-- Graphique + Événements récents --}}
            <div style="display:grid; gap:1.25rem; margin-bottom:1.25rem;">
                <style>@media(min-width:1024px){.main-grid{grid-template-columns:1fr 340px!important;}}</style>
                <div class="main-grid" style="display:grid; grid-template-columns:1fr; gap:1.25rem;">

                    {{-- Graphique revenus --}}
                    <div class="card" style="padding:1.5rem;">
                        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
                            <div>
                                <h2 style="font-size:.9rem; font-weight:700; color:var(--blue-night);">Revenus mensuels</h2>
                                <p style="font-size:.75rem; color:var(--gray-mid); margin-top:.2rem;">Évolution sur 6 mois</p>
                            </div>
                            <div style="display:flex; gap:.5rem;">
                                <button style="padding:.375rem .875rem; border-radius:.75rem; font-size:.72rem; font-weight:600; color:white; background:var(--blue-electric); border:none; cursor:pointer;">6 mois</button>
                                <button style="padding:.375rem .875rem; border-radius:.75rem; font-size:.72rem; font-weight:600; color:var(--gray-mid); background:white; border:1.5px solid var(--gray-soft); cursor:pointer; transition:border-color .2s;" onmouseover="this.style.borderColor='var(--blue-electric)'" onmouseout="this.style.borderColor='var(--gray-soft)'">1 an</button>
                            </div>
                        </div>
                        <div style="height:220px; position:relative;">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>

                    {{-- Répartition par événement --}}
                    <div class="card" style="padding:1.5rem;">
                        <div style="margin-bottom:1.5rem;">
                            <h2 style="font-size:.9rem; font-weight:700; color:var(--blue-night);">Réservations par événement</h2>
                            <p style="font-size:.75rem; color:var(--gray-mid); margin-top:.2rem;">Ce mois</p>
                        </div>
                        <div style="height:160px; max-width:160px; margin:0 auto 1.25rem; position:relative;">
                            <canvas id="donutChart"></canvas>
                        </div>
                        <div style="display:flex; flex-direction:column; gap:.75rem;">
                            @foreach($events->take(4) ?? [] as $i => $ev)
                            @php
                                $colors = ['#2563EB','#7C3AED','#059669','#D97706'];
                                $pct    = 100 / max($events->count(), 1);
                            @endphp
                            <div style="display:flex; align-items:center; justify-content:space-between; gap:.75rem;">
                                <div style="display:flex; align-items:center; gap:.5rem; min-width:0; flex:1;">
                                    <span style="width:.625rem; height:.625rem; border-radius:50%; flex-shrink:0; background:{{ $colors[$i % 4] }};"></span>
                                    <span style="font-size:.75rem; color:var(--gray-mid); overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ Str::limit($ev->titre, 20) }}</span>
                                </div>
                                <div style="display:flex; align-items:center; gap:.75rem; flex-shrink:0;">
                                    <div style="width:4rem; height:.375rem; border-radius:99px; background:var(--gray-soft); overflow:hidden;">
                                        <div style="height:100%; border-radius:99px; background:{{ $colors[$i % 4] }}; width:{{ round($pct * ($i + 1)) }}%;"></div>
                                    </div>
                                    <span style="font-size:.75rem; font-weight:700; color:var(--blue-night); width:2.5rem; text-align:right;">{{ $ev->reservations_count ?? rand(10,60) }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>


            {{-- Dernières réservations + Prochains événements --}}
            <div style="display:grid; gap:1.25rem;">
                <style>@media(min-width:1024px){.bottom-grid{grid-template-columns:1fr 1fr!important;}}</style>
                <div class="bottom-grid" style="display:grid; grid-template-columns:1fr; gap:1.25rem;">

                    {{-- Dernières réservations --}}
                    <div class="card" style="overflow:hidden;">
                        <div style="display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1px solid var(--gray-soft);">
                            <h2 style="font-size:.875rem; font-weight:700; color:var(--blue-night);">Dernières réservations</h2>
                            <button @click="activeTab='reservations'" style="font-size:.75rem; font-weight:600; color:var(--blue-electric); background:none; border:none; cursor:pointer; transition:opacity .2s;" onmouseover="this.style.opacity='.7'" onmouseout="this.style.opacity='1'">Voir tout →</button>
                        </div>
                        <div>
                            @forelse($reservations->take(5) ?? [] as $res)
                            @php
                                $ev   = $res->evenement ?? null;
                                $code = $res->code ?? 'EXP-' . str_pad($res->id ?? 0, 6, '0', STR_PAD_LEFT);
                                $st   = $res->statut ?? 'confirmee';
                                $statusStyles = [
                                    'confirmee' => ['Confirmé',   '#059669', '#ECFDF5'],
                                    'en_attente'=> ['En attente', '#D97706', '#FFFBEB'],
                                    'annule'    => ['Annulé',     '#DC2626', '#FEF2F2'],
                                ];
                                [$sl, $sc, $sb] = $statusStyles[$st] ?? ['Confirmé','#059669','#ECFDF5'];
                            @endphp
                            <div style="display:flex; align-items:center; gap:1rem; padding:.875rem 1.5rem; border-bottom:1px solid var(--gray-soft); transition:background .2s;" onmouseover="this.style.background='var(--pearl)'" onmouseout="this.style.background='white'">
                                <div style="width:2.25rem; height:2.25rem; border-radius:.875rem; background:linear-gradient(135deg,var(--blue-electric),#1248b0); display:flex; align-items:center; justify-content:center; font-size:.8rem; font-weight:700; color:white; flex-shrink:0;">
                                    {{ strtoupper(substr($res->nom ?? 'U', 0, 1)) }}
                                </div>
                                <div style="flex:1; min-width:0;">
                                    <p style="font-size:.8rem; font-weight:600; color:var(--blue-night); overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $res->nom ?? 'Visiteur' }}</p>
                                    <p style="font-size:.72rem; color:var(--gray-mid); overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $ev?->titre ?? '—' }} · {{ $res->nb_places ?? 1 }} place(s)</p>
                                </div>
                                <div style="text-align:right; flex-shrink:0;">
                                    <span style="display:inline-flex; align-items:center; gap:.3rem; font-size:.65rem; font-weight:700; padding:.25rem .625rem; border-radius:2rem; background:{{ $sb }}; color:{{ $sc }};">
                                        {{ $sl }}
                                    </span>
                                    <p style="font-size:.65rem; color:var(--gray-mid); margin-top:.2rem;">{{ \Carbon\Carbon::parse($res->created_at)->diffForHumans() }}</p>
                                </div>
                            </div>
                            @empty
                            <div style="padding:2.5rem; text-align:center; color:var(--gray-mid); font-size:.825rem;">
                                Aucune réservation pour l'instant.
                            </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Prochains événements --}}
                    <div class="card" style="overflow:hidden;">
                        <div style="display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1px solid var(--gray-soft);">
                            <h2 style="font-size:.875rem; font-weight:700; color:var(--blue-night);">Prochains événements</h2>
                            <button @click="activeTab='evenements'" style="font-size:.75rem; font-weight:600; color:var(--blue-electric); background:none; border:none; cursor:pointer; transition:opacity .2s;" onmouseover="this.style.opacity='.7'" onmouseout="this.style.opacity='1'">Voir tout →</button>
                        </div>
                        <div>
                            @forelse($events->where(fn($e) => \Carbon\Carbon::parse($e->date_debut)->isFuture())->take(5) ?? [] as $ev)
                            @php
                                $debut = \Carbon\Carbon::parse($ev->date_debut);
                                $jours = $debut->diffInDays(now(), false);
                                $jours = abs($jours);
                            @endphp
                            <div style="display:flex; align-items:center; gap:1rem; padding:.875rem 1.5rem; border-bottom:1px solid var(--gray-soft); transition:background .2s;" onmouseover="this.style.background='var(--pearl)'" onmouseout="this.style.background='white'">

                                {{-- Calendrier mini --}}
                                <div style="width:2.75rem; flex-shrink:0; text-align:center; background:var(--pearl); border-radius:.875rem; padding:.375rem .25rem; border:1.5px solid var(--gray-soft);">
                                    <p style="font-size:.6rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:var(--blue-electric);">{{ $debut->translatedFormat('M') }}</p>
                                    <p style="font-size:1.15rem; font-weight:800; color:var(--blue-night); line-height:1.1;">{{ $debut->format('d') }}</p>
                                </div>

                                <div style="flex:1; min-width:0;">
                                    <p style="font-size:.8rem; font-weight:600; color:var(--blue-night); overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $ev->titre }}</p>
                                    <p style="font-size:.72rem; color:var(--gray-mid); margin-top:.1rem;">📍 {{ $ev->lieu }}</p>
                                </div>

                                <div style="text-align:right; flex-shrink:0;">
                                    <p style="font-size:.72rem; font-weight:700; color:var(--blue-electric);">J – {{ $jours }}</p>
                                    <p style="font-size:.65rem; color:var(--gray-mid); margin-top:.2rem;">{{ $ev->reservations_count ?? 0 }} résas</p>
                                </div>
                            </div>
                            @empty
                            <div style="padding:2.5rem; text-align:center; color:var(--gray-mid); font-size:.825rem;">
                                Aucun événement à venir.
                                <a href="{{ route('organisateur.events.create') }}" style="color:var(--blue-electric); text-decoration:none; font-weight:600; display:block; margin-top:.5rem;">+ Créer un événement</a>
                            </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>

        </div>
        {{-- /dashboard --}}


        {{-- ══════════════════════════════════════════
             ONGLET : MES ÉVÉNEMENTS
             ══════════════════════════════════════════ --}}
        <div x-show="activeTab === 'evenements'" x-cloak style="padding:2rem 1.5rem;">

            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
                <h2 style="font-size:1.1rem; font-weight:700; color:var(--blue-night);">Mes événements ({{ $events->count() ?? 0 }})</h2>
                <a href="{{ route('organisateur.events.create') }}"
                   style="display:flex; align-items:center; gap:.5rem; padding:.625rem 1.25rem; border-radius:.875rem; font-size:.8rem; font-weight:700; color:white; text-decoration:none; background:linear-gradient(135deg,var(--blue-electric),#1248b0); box-shadow:0 4px 12px rgba(30,95,216,.25);">
                    + Créer un événement
                </a>
            </div>

            <div style="display:flex; flex-direction:column; gap:1rem;">
                @forelse($events ?? [] as $ev)
                @php
                    $now   = now();
                    $debut = \Carbon\Carbon::parse($ev->date_debut);
                    $fin   = \Carbon\Carbon::parse($ev->date_fin);
                    if ($now->lt($debut))               { $sl='À venir';  $sc='#059669'; $sb='#ECFDF5'; }
                    elseif ($now->between($debut,$fin)) { $sl='En cours'; $sc='#D97706'; $sb='#FFFBEB'; }
                    else                                { $sl='Terminé';  $sc='#9CA3AF'; $sb='#F1F5F9'; }
                @endphp
                <div class="card" style="display:flex; flex-wrap:wrap; align-items:center; gap:1.25rem; padding:1.25rem 1.5rem;">

                    <div style="width:3.5rem; height:3.5rem; border-radius:1rem; overflow:hidden; flex-shrink:0; background:linear-gradient(135deg,var(--blue-night),var(--blue-electric));">
                        @if($ev->image)
                        <img src="{{ Storage::url( $ev->image) }}" alt="{{ $ev->titre }}" style="width:100%;height:100%;object-fit:cover;">
                        @endif
                    </div>

                    <div style="flex:1; min-width:0;">
                        <div style="display:flex; align-items:center; gap:.625rem; margin-bottom:.3rem;">
                            <span style="font-size:.65rem; font-weight:700; padding:.2rem .625rem; border-radius:2rem; background:{{ $sb }}; color:{{ $sc }};">{{ $sl }}</span>
                            @if($ev->categorie)<span style="font-size:.68rem; color:var(--gray-mid);">{{ $ev->categorie->nom }}</span>@endif
                        </div>
                        <h3 style="font-size:.9rem; font-weight:700; color:var(--blue-night); overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $ev->titre }}</h3>
                        <p style="font-size:.75rem; color:var(--gray-mid); margin-top:.2rem;">📍 {{ $ev->lieu }} · 🗓 {{ $debut->translatedFormat('d M Y') }}</p>
                    </div>

                    <div style="display:flex; align-items:center; gap:.75rem; flex-shrink:0; flex-wrap:wrap;">
                        <div style="text-align:center; padding:.5rem .875rem; border-radius:.875rem; background:var(--pearl);">
                            <p style="font-size:1rem; font-weight:800; color:var(--blue-electric);">{{ $ev->reservations_count ?? 0 }}</p>
                            <p style="font-size:.65rem; color:var(--gray-mid);">Réservations</p>
                        </div>
                        <div style="display:flex; gap:.5rem;">
                            <a href="{{ route('events.show', $ev->id) }}"
                               style="width:2rem; height:2rem; border-radius:.625rem; border:1.5px solid var(--gray-soft); display:flex; align-items:center; justify-content:center; text-decoration:none; color:var(--gray-mid); transition:all .2s;"
                               onmouseover="this.style.borderColor='var(--blue-electric)'; this.style.color='var(--blue-electric)'" onmouseout="this.style.borderColor='var(--gray-soft)'; this.style.color='var(--gray-mid)'">
                                <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                            </a>
                            <a href="{{ route('events.edit', $ev->id) }}"
                               style="width:2rem; height:2rem; border-radius:.625rem; border:1.5px solid var(--gray-soft); display:flex; align-items:center; justify-content:center; text-decoration:none; color:var(--gray-mid); transition:all .2s;"
                               onmouseover="this.style.borderColor='#D97706'; this.style.color='#D97706'" onmouseout="this.style.borderColor='var(--gray-soft)'; this.style.color='var(--gray-mid)'">
                                <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="card" style="padding:4rem; text-align:center;">
                    <p style="font-size:.9rem; color:var(--gray-mid); margin-bottom:1rem;">Vous n'avez pas encore créé d'événement.</p>
                    <a href="{{ route('organisateur.events.create') }}" style="display:inline-flex; align-items:center; gap:.5rem; padding:.875rem 1.75rem; border-radius:1rem; font-size:.875rem; font-weight:700; color:white; text-decoration:none; background:linear-gradient(135deg,var(--blue-electric),#1248b0);">
                        + Créer mon premier événement
                    </a>
                </div>
                @endforelse
            </div>
        </div>


        {{-- ══════════════════════════════════════════
             ONGLET : RÉSERVATIONS statut
             ══════════════════════════════════════════ --}}
        <div x-show="activeTab === 'reservations'" x-cloak style="padding:2rem 1.5rem;">

            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
                <h2 style="font-size:1.1rem; font-weight:700; color:var(--blue-night);">Toutes les réservations</h2>
                <span style="font-size:.75rem; font-weight:600; padding:.375rem .875rem; border-radius:2rem; background:#EFF6FF; color:var(--blue-electric);">
                    {{ $reservations->count() ?? 0 }} au total
                </span>
            </div>

            <div class="card" style="overflow:hidden;">
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr style="background:var(--pearl); border-bottom:1px solid var(--gray-soft);">
                                @foreach(['Participant','Événement','Places','Mode paiement','Montant','Date'] as $col)
                                <th style="padding:.875rem 1.25rem; text-align:left; font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:var(--gray-mid); white-space:nowrap;">{{ $col }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reservations ?? [] as $res)
                            @php
                                $ev   = $res->evenement ?? null;
                                $st   = $res->statut ?? 'confirmee';
                                $statusStyles = [
                                    'confirmee'  => ['Confirmé',   '#059669', '#ECFDF5'],
                                    'en_attente' => ['En attente', '#D97706', '#FFFBEB'],
                                    'annule'     => ['Annulé',     '#DC2626', '#FEF2F2'],
                                ];
                                [$sl, $sc, $sb] = $statusStyles[$st] ?? ['Confirmé','#059669','#ECFDF5'];
                                $montant = (optional($ev?->categorie)->prix ?? 0) * ($res->nb_places ?? 1);
                                $paiements = ['sur_place'=>'Sur place','wave'=>'Wave','orange'=>'Orange Money'];
                            @endphp
                            <tr style="border-bottom:1px solid var(--gray-soft); transition:background .2s;" onmouseover="this.style.background='var(--pearl)'" onmouseout="this.style.background='white'">
                                <td style="padding:.875rem 1.25rem;">
                                    <div style="display:flex; align-items:center; gap:.75rem;">
                                        <div style="width:2rem; height:2rem; border-radius:50%; background:linear-gradient(135deg,var(--blue-electric),var(--gold)); display:flex; align-items:center; justify-content:center; font-size:.72rem; font-weight:700; color:white; flex-shrink:0;">
                                            {{ strtoupper(substr($res->nom ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p style="font-size:.8rem; font-weight:600; color:var(--blue-night);">{{ $res->nom ?? '—' }}</p>
                                            <p style="font-size:.68rem; color:var(--gray-mid);">{{ $res->email ?? '' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding:.875rem 1.25rem; font-size:.8rem; color:var(--blue-night); max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $ev?->titre ?? '—' }}</td>
                                <td style="padding:.875rem 1.25rem; font-size:.8rem; font-weight:600; color:var(--blue-night);">{{ $res->nb_places ?? 1 }}</td>
                                <td style="padding:.875rem 1.25rem; font-size:.78rem; color:var(--gray-mid);">{{ $paiements[$res->paiement ?? 'sur_place'] ?? 'Sur place' }}</td>
                                <td style="padding:.875rem 1.25rem; font-size:.825rem; font-weight:700; color:var(--blue-electric);">{{ $montant > 0 ? number_format($montant, 0, ',', ' ') . ' FCFA' : 'Gratuit' }}</td>
                                
                                <td style="padding:.875rem 1.25rem; font-size:.75rem; color:var(--gray-mid); white-space:nowrap;">{{ \Carbon\Carbon::parse($res->created_at)->translatedFormat('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="7" style="padding:3rem; text-align:center; color:var(--gray-mid); font-size:.825rem;">Aucune réservation pour l'instant.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        {{-- ══════════════════════════════════════════
             AUTRES ONGLETS (Participants, Revenus, Profil, Paramètres)
             ══════════════════════════════════════════ --}}
        @foreach(['participants','revenus','profil','parametres'] as $tab)
        <div x-show="activeTab === '{{ $tab }}'" x-cloak style="padding:2rem 1.5rem;">
            <div class="card" style="padding:4rem; text-align:center;">
                <div style="width:4rem; height:4rem; border-radius:1.5rem; background:var(--pearl); display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
                    <svg style="width:1.75rem;height:1.75rem;color:var(--gray-soft);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5h16.5m-16.5 0-1.5-9h19.5l-1.5 9M6.75 13.5v7.5m10.5-7.5v7.5m-7.5 0h4.5"/></svg>
                </div>
                <p style="font-size:.9rem; font-weight:700; color:var(--blue-night); margin-bottom:.4rem;">Section {{ ucfirst($tab) }}</p>
                <p style="font-size:.825rem; color:var(--gray-mid);">Cette section est en cours de développement.</p>
            </div>
        </div>
        @endforeach

    </div>
    {{-- /main-wrap --}}


    {{-- ══════════════════════════════════════════════
         BOTTOM NAV MOBILE
         ══════════════════════════════════════════════ --}}
    <nav class="bottom-nav">
        @foreach([
            ['dashboard',    'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z', ''],
            ['evenements',   'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25', ''],
            ['reservations', 'M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026', ''],
            ['revenus',      'M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75', ''],
            ['profil',       'M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z', ''],
        ] as [$tab, $icon, $_])
        <button @click="activeTab='{{ $tab }}'"
                style="flex:1; display:flex; flex-direction:column; align-items:center; gap:.2rem; padding:.375rem .25rem; border-radius:.75rem; border:none; cursor:pointer; font-family:'Inter',sans-serif; font-size:.58rem; font-weight:600; transition:all .2s;"
                :style="activeTab==='{{ $tab }}' ? 'color:var(--blue-electric); background:#EFF6FF;' : 'color:var(--gray-mid); background:transparent;'">
            <svg style="width:1.25rem;height:1.25rem;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
            {{ ucfirst($tab) }}
        </button>
        @endforeach
    </nav>


    {{-- Toast --}}
    <div x-show="toastShow" x-cloak
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
         style="position:fixed; bottom:1.5rem; left:50%; transform:translateX(-50%); z-index:999; background:var(--blue-night); color:white; padding:.75rem 1.5rem; border-radius:1rem; font-size:.825rem; font-weight:600; box-shadow:0 8px 32px rgba(10,22,40,.3); white-space:nowrap; display:flex; align-items:center; gap:.625rem;">
        ✅ <span x-text="toastMsg"></span>
    </div>

</div>


{{-- CHART.JS --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    Chart.defaults.font.family = 'Inter, sans-serif';
    Chart.defaults.font.size   = 11;
    Chart.defaults.color       = '#8892A4';

    // Revenus line chart
    const rCtx = document.getElementById('revenueChart');
    if (rCtx) {
        new Chart(rCtx, {
            type: 'line',
            data: {
                labels: ['Jan','Fév','Mar','Avr','Mai','Juin'],
                datasets: [{
                    label: 'Revenus',
                    borderColor:           '#1E5FD8',
                    backgroundColor:       'rgba(30,95,216,.07)',
                    fill:                  true,
                    tension:               0.45,
                    borderWidth:           2.5,
                    pointBackgroundColor:  '#1E5FD8',
                    pointBorderColor:      '#fff',
                    pointBorderWidth:      2,
                    pointRadius:           4,
                    pointHoverRadius:      6,
                }]
            },
            options: {
                responsive:          true,
                maintainAspectRatio: false,
                interaction:         { intersect:false, mode:'index' },
                plugins: {
                    legend: { display:false },
                    tooltip: {
                        backgroundColor: '#0A1628',
                        padding:         12,
                        cornerRadius:    10,
                        displayColors:   false,
                        callbacks: { label: ctx => ' ' + ctx.parsed.y.toLocaleString('fr-FR') + ' FCFA' }
                    }
                },
                scales: {
                    x: { grid:{ display:false }, border:{ display:false } },
                    y: {
                        grid:   { color:'rgba(0,0,0,.04)' },
                        border: { display:false },
                        ticks:  { callback: v => v >= 1000000 ? (v/1000000)+'M' : (v/1000)+'k' }
                    }
                }
            }
        });
    }

    // Donut chart
    const dCtx = document.getElementById('donutChart');
    if (dCtx) {
        new Chart(dCtx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data:            [42, 28, 18, 12],
                    backgroundColor: ['#1E5FD8','#7C3AED','#059669','#D97706'],
                    borderWidth:     0,
                    hoverOffset:     6,
                }]
            },
            options: {
                responsive:          true,
                maintainAspectRatio: false,
                cutout:              '72%',
                plugins: {
                    legend: { display:false },
                    tooltip: {
                        backgroundColor: '#0A1628',
                        padding:         10,
                        cornerRadius:    8,
                        callbacks: { label: ctx => ' ' + ctx.label + ' : ' + ctx.parsed + '%' }
                    }
                }
            }
        });
    }
});
</script>

</body>
</html>
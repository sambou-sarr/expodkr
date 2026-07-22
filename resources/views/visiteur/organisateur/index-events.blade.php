<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes événements · ExpoDKR Organisateur</title>

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

        * { box-sizing:border-box; margin:0; padding:0; }
        body { font-family:'Inter',sans-serif; background:var(--pearl); color:var(--blue-night); -webkit-font-smoothing:antialiased; min-height:100vh; }
        .font-display { font-family:'Instrument Serif',serif; }
        [x-cloak]     { display:none!important; }

        /* ── Sidebar ── */
        .sidebar { width:16rem; background:var(--blue-night); height:100vh; position:fixed; left:0; top:0; z-index:40; display:flex; flex-direction:column; transition:transform .3s ease; }
        .sidebar-link { display:flex; align-items:center; gap:.875rem; padding:.875rem 1.25rem; border-radius:1rem; font-size:.825rem; font-weight:500; color:rgba(255,255,255,.55); text-decoration:none; transition:all .2s; cursor:pointer; border:none; background:none; font-family:'Inter',sans-serif; width:100%; text-align:left; }
        .sidebar-link:hover  { color:white; background:rgba(255,255,255,.08); }
        .sidebar-link.active { color:white; background:rgba(30,95,216,.5); }

        /* ── Layout ── */
        .main-wrap { margin-left:16rem; min-height:100vh; }
        @media(max-width:1024px) {
            .sidebar   { transform:translateX(-100%); }
            .sidebar.open { transform:translateX(0); }
            .main-wrap { margin-left:0; }
            .page-pb   { padding-bottom:5rem; }
        }

        /* ── Cards ── */
        .card { background:white; border-radius:1.5rem; border:1px solid var(--gray-soft); box-shadow:0 2px 16px rgba(10,22,40,.05); }
        .ev-card { transition:transform .25s, box-shadow .25s; }
        .ev-card:hover { transform:translateY(-3px); box-shadow:0 12px 36px rgba(10,22,40,.1); }

        /* ── Input ── */
        .inp {
            width:100%; border:1.5px solid var(--gray-soft); border-radius:1rem;
            padding:.875rem 1rem; font-size:.875rem; font-family:'Inter',sans-serif;
            color:var(--blue-night); background:white; outline:none;
            transition:border-color .2s, box-shadow .2s;
        }
        .inp:focus { border-color:var(--blue-electric); box-shadow:0 0 0 3px rgba(30,95,216,.1); }
        .inp::placeholder { color:var(--gray-mid); }
        .inp-icon { padding-left:3rem; }
        .inp.err  { border-color:#DC2626; background:#FEF2F2; }

        /* ── Bottom nav ── */
        .bottom-nav { display:none; }
        @media(max-width:1024px) { .bottom-nav { display:flex; position:fixed; bottom:0; left:0; right:0; z-index:50; background:white; border-top:1px solid var(--gray-soft); padding:.625rem 1.25rem; gap:.25rem; } }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width:4px; }
        ::-webkit-scrollbar-thumb { background:var(--blue-electric); border-radius:99px; }

        /* ── Animations ── */
        @keyframes pulse-dot { 0%,100%{opacity:1;} 50%{opacity:.35;} }
        @keyframes fade-in   { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:none} }
        .fade-in { animation:fade-in .3s ease forwards; }
    </style>
</head>
<body>

{{--
|--------------------------------------------------------------------------
| ExpoDKR – Événements Organisateur (liste + modale créer/modifier)
| Variables : $events (collection), $categories, $stats
| Routes    : organisateur.events.*, Auth::user()
|--------------------------------------------------------------------------
--}}

<div x-data="{
        sidebarOpen:  false,
        view:         'liste',   /* liste | grille */
        search:       '',
        filterStatus: 'all',
        loading:      false,

        /* ── Modal créer/éditer ── */
        modal:        false,
        editMode:     false,
        confirmDelete: null,

        form: {
            id:           null,
            titre:        '',
            lieu:         '',
            date_debut:   '',
            date_fin:     '',
            id_categorie: '',
            description:  '',
            prix:         '',
            imagePreview: null,
        },

        openCreate() {
            this.editMode = false;
            this.form = { id:null, titre:'', lieu:'', date_debut:'', date_fin:'', id_categorie:'', description:'', prix:'', imagePreview:null };
            this.modal = true;
        },

        openEdit(ev) {
            this.editMode = true;
            this.form = {
                id:           ev.id,
                titre:        ev.titre,
                lieu:         ev.lieu,
                date_debut:   ev.date_debut,
                date_fin:     ev.date_fin,
                id_categorie: ev.id_categorie,
                description:  ev.description,
                prix:         ev.prix,
                imagePreview: ev.image,
            };
            this.modal = true;
        },

        previewImage(e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (r) => this.form.imagePreview = r.target.result;
            reader.readAsDataURL(file);
        },

        matchEvent(titre, lieu, statut) {
            const q  = this.search.toLowerCase();
            const sm = this.filterStatus === 'all' || statut === this.filterStatus;
            const tm = !q || titre.toLowerCase().includes(q) || lieu.toLowerCase().includes(q);
            return sm && tm;
        },

        toastMsg:  '',
        toastShow: false,
        showToast(msg) { this.toastMsg=msg; this.toastShow=true; setTimeout(()=>this.toastShow=false,3000); }
     }"
     class="page-pb">


    {{-- ══════════════════════════════════════════════
         SIDEBAR
         ══════════════════════════════════════════════ --}}
    <aside class="sidebar" :class="sidebarOpen ? 'open' : ''">
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
                <span style="width:.5rem; height:.5rem; border-radius:50%; background:var(--gold); animation:pulse-dot 2s infinite;"></span>
                <span style="font-size:.68rem; font-weight:600; letter-spacing:.12em; text-transform:uppercase; color:var(--gold-light);">Espace Organisateur</span>
            </div>
        </div>

        <nav style="flex:1; overflow-y:auto; padding:1rem .875rem; display:flex; flex-direction:column; gap:.2rem;">
            <p style="font-size:.62rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase; color:rgba(255,255,255,.25); padding:.5rem .5rem .375rem;">Principal</p>

            @foreach([
                ['dashboard',    route('organisateur.dashboard'), 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z', 'Dashboard', false],
                ['evenements',   route('organisateur.events.index'), 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5', 'Mes événements', true],
                ['reservations', route('organisateur.reservations.index'), 'M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026', 'Réservations', false],
                ['revenus',      '#', 'M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75', 'Revenus', false],
            ] as [$key, $href, $icon, $label, $active])
            <a href="{{ $href }}"
               class="sidebar-link {{ $active ? 'active' : '' }}">
                <svg style="width:1rem;height:1rem;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/>
                </svg>
                {{ $label }}
            </a>
            @endforeach

            <p style="font-size:.62rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase; color:rgba(255,255,255,.25); padding:.5rem .5rem .375rem; margin-top:.75rem;">Compte</p>
            <a href="{{ route('organisateur.profil') }}" class="sidebar-link">
                <svg style="width:1rem;height:1rem;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                Mon profil
            </a>
        </nav>

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
                    <button type="submit" style="width:1.75rem; height:1.75rem; border-radius:.5rem; background:rgba(255,255,255,.08); border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:background .2s;"
                            onmouseover="this.style.background='rgba(220,38,38,.3)'" onmouseout="this.style.background='rgba(255,255,255,.08)'">
                        <svg style="width:.875rem;height:.875rem;color:rgba(255,255,255,.5);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Overlay mobile --}}
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen=false"
         style="position:fixed; inset:0; background:rgba(10,22,40,.5); backdrop-filter:blur(4px); z-index:39;"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>


    {{-- ══════════════════════════════════════════════
         CONTENU PRINCIPAL events.create
         ══════════════════════════════════════════════ --}}
    <div class="main-wrap">

        {{-- Topbar --}}
        <div style="background:white; border-bottom:1px solid var(--gray-soft); padding:0 1.5rem; height:4rem; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:30; gap:1rem;">
            <div style="display:flex; align-items:center; gap:1rem;">
                <button @click="sidebarOpen=!sidebarOpen" class="lg:hidden"
                        style="width:2.25rem; height:2.25rem; border-radius:.75rem; border:1.5px solid var(--gray-soft); background:white; cursor:pointer; display:flex; align-items:center; justify-content:center;">
                    <svg style="width:1rem;height:1rem;color:var(--blue-night);" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                </button>
                <div>
                    <nav style="display:flex; align-items:center; gap:.5rem; font-size:.75rem; color:var(--gray-mid);">
                        <a href="{{ route('organisateur.dashboard') }}" style="color:var(--gray-mid); text-decoration:none; transition:color .2s;" onmouseover="this.style.color='var(--blue-electric)'" onmouseout="this.style.color='var(--gray-mid)'">Dashboard</a>
                        <svg style="width:.75rem;height:.75rem;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                        <span style="font-weight:700; color:var(--blue-night);">Mes événements</span>
                    </nav>
                </div>
            </div>

            <div style="display:flex; align-items:center; gap:.75rem; flex-shrink:0;">
                {{-- Toggle vue un--}}
                <div style="display:flex; border:1.5px solid var(--gray-soft); border-radius:.875rem; overflow:hidden;">
                    <button @click="view='liste'"
                            style="padding:.5rem .75rem; font-size:.75rem; font-weight:600; border:none; cursor:pointer; font-family:'Inter',sans-serif; transition:all .2s; display:flex; align-items:center; gap:.375rem;"
                            :style="view==='liste' ? 'background:var(--blue-electric); color:white;' : 'background:white; color:var(--gray-mid);'">
                        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                        Liste
                    </button>
                    <button @click="view='grille'"
                            style="padding:.5rem .75rem; font-size:.75rem; font-weight:600; border:none; cursor:pointer; font-family:'Inter',sans-serif; transition:all .2s; display:flex; align-items:center; gap:.375rem;"
                            :style="view==='grille' ? 'background:var(--blue-electric); color:white;' : 'background:white; color:var(--gray-mid);'">
                        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/></svg>
                        Grille
                    </button>
                </div>

 
            </div>
        </div>


        {{-- ══════════════════════════════════════════
             PAGE CONTENU
             ══════════════════════════════════════════ --}}
        <div style="padding:2rem 1.5rem;" class="fade-in">

            {{-- Flash messages --}}
            @if(session('success'))
            <div style="display:flex; align-items:center; gap:.75rem; padding:1rem 1.25rem; border-radius:1.25rem; background:#ECFDF5; border:1px solid #A7F3D0; margin-bottom:1.5rem;">
                <svg style="width:1rem;height:1rem;color:#059669;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                <p style="font-size:.825rem; font-weight:600; color:#059669;">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div style="display:flex; align-items:center; gap:.75rem; padding:1rem 1.25rem; border-radius:1.25rem; background:#FEF2F2; border:1px solid #FECACA; margin-bottom:1.5rem;">
                <svg style="width:1rem;height:1rem;color:#DC2626;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                <p style="font-size:.825rem; font-weight:600; color:#DC2626;">{{ session('error') }}</p>
            </div>
            @endif

            {{-- Header section --}}
            <div style="display:flex; flex-wrap:wrap; align-items:flex-start; justify-content:space-between; gap:1.5rem; margin-bottom:2rem;">
                <div>
                    <h1 class="font-display" style="font-size:1.75rem; color:var(--blue-night); margin-bottom:.3rem;">Mes événements</h1>
                    <p style="font-size:.875rem; color:var(--gray-mid);">
                        <span style="font-weight:700; color:var(--blue-night);">{{ $events->total() ?? $events->count() }}</span>
                        événement{{ ($events->total() ?? $events->count()) > 1 ? 's' : '' }} au total
                    </p>
                </div>

                {{-- Recherche + Filtres --}}
                <div style="display:flex; flex-wrap:wrap; gap:.75rem;">
                    {{-- Recherche --}}
                    <div style="position:relative;">
                        <div style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); pointer-events:none;">
                            <svg style="width:.9rem;height:.9rem;color:var(--gray-mid);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607z"/></svg>
                        </div>
                        <input type="text" x-model="search" placeholder="Rechercher un événement…"
                               style="width:16rem; padding:.625rem 1rem .625rem 2.75rem; border:1.5px solid var(--gray-soft); border-radius:1rem; font-size:.825rem; font-family:'Inter',sans-serif; color:var(--blue-night); background:white; outline:none; transition:border-color .2s;"
                               onfocus="this.style.borderColor='var(--blue-electric)'" onblur="this.style.borderColor='var(--gray-soft)'">
                    </div>

                    {{-- Filtre statut --}}
                    <div style="display:flex; gap:.5rem; flex-wrap:wrap;">
                        @foreach([['all','Tous','#64748B','#F1F5F9'],['a_venir','À venir','#059669','#ECFDF5'],['en_cours','En cours','#D97706','#FFFBEB'],['termine','Terminés','#9CA3AF','#F1F5F9']] as [$val,$lbl,$clr,$bg])
                        <button @click="filterStatus='{{ $val }}'"
                                style="padding:.5rem 1rem; border-radius:2rem; font-size:.75rem; font-weight:600; border:1.5px solid; cursor:pointer; font-family:'Inter',sans-serif; transition:all .2s; display:flex; align-items:center; gap:.375rem;"
                                :style="filterStatus==='{{ $val }}'
                                    ? 'background:{{ $bg }}; color:{{ $clr }}; border-color:{{ $clr }};'
                                    : 'background:white; color:var(--gray-mid); border-color:var(--gray-soft);'">
                            @if($val !== 'all')
                            <span style="width:.5rem; height:.5rem; border-radius:50%; background:{{ $clr }};"></span>
                            @endif
                            {{ $lbl }}
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>


            {{-- ── VUE LISTE ── --}}
            <div x-show="view === 'liste'">
                <div class="card" style="overflow:hidden;">
                    <div style="overflow-x:auto;">
                        <table style="width:100%; border-collapse:collapse;">
                            <thead>
                                <tr style="background:var(--pearl); border-bottom:1px solid var(--gray-soft);">
                                    @foreach(['Événement','Lieu','Date début','Date fin','Réservations','Revenu','Statut','Actions'] as $col)
                                    <th style="padding:.875rem 1.25rem; text-align:left; font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:var(--gray-mid); white-space:nowrap;">{{ $col }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($events as $ev)
                                @php
                                    $now   = now();
                                    $debut = \Carbon\Carbon::parse($ev->date_debut);
                                    $fin   = \Carbon\Carbon::parse($ev->date_fin);

                                    if ($now->lt($debut))               { $sl='a_venir';   $slLabel='À venir';  $sc='#059669'; $sb='#ECFDF5'; }
                                    elseif ($now->between($debut,$fin)) { $sl='en_cours';  $slLabel='En cours'; $sc='#D97706'; $sb='#FFFBEB'; }
                                    else                                { $sl='termine';   $slLabel='Terminé';  $sc='#9CA3AF'; $sb='#F1F5F9'; }

                                    $revenu = $ev->reservations->sum(fn($r) => (optional($ev->categorie)->prix ?? 0) * ($r->nb_places ?? 1));
                                @endphp
                                <tr style="border-bottom:1px solid var(--gray-soft); transition:background .2s;"
                                    x-show="matchEvent('{{ strtolower($ev->titre) }}', '{{ strtolower($ev->lieu) }}', '{{ $sl }}')"
                                    onmouseover="this.style.background='var(--pearl)'" onmouseout="this.style.background='white'">

                                    {{-- Événement --}}
                                    <td style="padding:.875rem 1.25rem;">
                                        <div style="display:flex; align-items:center; gap:.875rem;">
                                            <div style="width:2.75rem; height:2.75rem; border-radius:.875rem; overflow:hidden; flex-shrink:0; background:linear-gradient(135deg,var(--blue-night),var(--blue-electric));">
                                                @if($ev->image)
                                                <img src="{{ $ev->image }}" alt="{{ $ev->titre }}" style="width:100%;height:100%;object-fit:cover;">
                                                @endif
                                            </div>
                                            <div style="min-width:0;">
                                                <p style="font-size:.825rem; font-weight:700; color:var(--blue-night); overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:180px;">{{ $ev->titre }}</p>
                                                @if($ev->categorie)
                                                <span style="font-size:.65rem; font-weight:600; padding:.15rem .5rem; border-radius:2rem; background:#EFF6FF; color:var(--blue-electric); margin-top:.15rem; display:inline-block;">{{ $ev->categorie->nom }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Lieu --}}
                                    <td style="padding:.875rem 1.25rem; font-size:.8rem; color:var(--gray-mid); white-space:nowrap; max-width:140px; overflow:hidden; text-overflow:ellipsis;">
                                        📍 {{ $ev->lieu }}
                                    </td>

                                    {{-- Date début --}}
                                    <td style="padding:.875rem 1.25rem; font-size:.8rem; color:var(--blue-night); white-space:nowrap;">
                                        {{ $debut->translatedFormat('d M Y') }}
                                    </td>

                                    {{-- Date fin --}}
                                    <td style="padding:.875rem 1.25rem; font-size:.8rem; color:var(--blue-night); white-space:nowrap;">
                                        {{ $fin->translatedFormat('d M Y') }}
                                    </td>

                                    {{-- Réservations --}}
                                    <td style="padding:.875rem 1.25rem;">
                                        <div style="display:flex; align-items:center; gap:.625rem;">
                                            <div style="flex:1; height:.375rem; border-radius:99px; background:var(--gray-soft); overflow:hidden; min-width:4rem;">
                                                @php $pct = min(100, ($ev->reservations_count ?? 0) * 2); @endphp
                                                <div style="height:100%; border-radius:99px; width:{{ $pct }}%; background:var(--blue-electric);"></div>
                                            </div>
                                            <span style="font-size:.8rem; font-weight:700; color:var(--blue-night); white-space:nowrap;">{{ $ev->reservations_count ?? 0 }}</span>
                                        </div>
                                    </td>

                                    {{-- Revenu --}}
                                    <td style="padding:.875rem 1.25rem; font-size:.825rem; font-weight:700; color:var(--blue-electric); white-space:nowrap;">
                                        {{ $revenu > 0 ? number_format($revenu, 0, ',', ' ') . ' F' : 'Gratuit' }}
                                    </td>

                                    {{-- Statut --}}
                                    <td style="padding:.875rem 1.25rem;">
                                        <span style="display:inline-flex; align-items:center; gap:.375rem; font-size:.68rem; font-weight:700; padding:.3rem .75rem; border-radius:2rem; background:{{ $sb }}; color:{{ $sc }}; white-space:nowrap;">
                                            <span style="width:.45rem; height:.45rem; border-radius:50%; background:{{ $sc }};"></span>
                                            {{ $slLabel }}
                                        </span>
                                    </td>

                                    {{-- Actions --}}
                                    <td style="padding:.875rem 1.25rem;">
                                        <div style="display:flex; align-items:center; gap:.5rem;">
                                            {{-- Voir --}}
                                            <a href="{{ route('organisateur.events.show', $ev->id) }}"
                                               style="width:1.875rem; height:1.875rem; border-radius:.625rem; border:1.5px solid var(--gray-soft); display:flex; align-items:center; justify-content:center; text-decoration:none; color:var(--gray-mid); transition:all .2s;"
                                               onmouseover="this.style.borderColor='var(--blue-electric)'; this.style.color='var(--blue-electric)'; this.style.background='#EFF6FF';"
                                               onmouseout="this.style.borderColor='var(--gray-soft)'; this.style.color='var(--gray-mid)'; this.style.background='white';"
                                               title="Voir">
                                                <svg style="width:.8rem;height:.8rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                            </a>

                                            {{-- Modifier --}}
                                            <button @click="openEdit({
                                                        id:           {{ $ev->id }},
                                                        titre:        '{{ addslashes($ev->titre) }}',
                                                        lieu:         '{{ addslashes($ev->lieu) }}',
                                                        date_debut:   '{{ \Carbon\Carbon::parse($ev->date_debut)->format('Y-m-d') }}',
                                                        date_fin:     '{{ \Carbon\Carbon::parse($ev->date_fin)->format('Y-m-d') }}',
                                                        id_categorie: '{{ $ev->id_categorie ?? '' }}',
                                                        description:  '{{ addslashes($ev->description ?? '') }}',
                                                        prix:         '{{ optional($ev->categorie)->prix ?? '' }}',
                                                        image:        '{{ $ev->image ?? '' }}'
                                                    })"
                                                    style="width:1.875rem; height:1.875rem; border-radius:.625rem; border:1.5px solid var(--gray-soft); display:flex; align-items:center; justify-content:center; background:white; cursor:pointer; color:var(--gray-mid); transition:all .2s;"
                                                    onmouseover="this.style.borderColor='#D97706'; this.style.color='#D97706'; this.style.background='#FFFBEB';"
                                                    onmouseout="this.style.borderColor='var(--gray-soft)'; this.style.color='var(--gray-mid)'; this.style.background='white';"
                                                    title="Modifier">
                                                <svg style="width:.8rem;height:.8rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/></svg>
                                            </button>

                                            {{-- Supprimer --}}
                                            <button @click="confirmDelete = {{ $ev->id }}"
                                                    style="width:1.875rem; height:1.875rem; border-radius:.625rem; border:1.5px solid var(--gray-soft); display:flex; align-items:center; justify-content:center; background:white; cursor:pointer; color:var(--gray-mid); transition:all .2s;"
                                                    onmouseover="this.style.borderColor='#DC2626'; this.style.color='#DC2626'; this.style.background='#FEF2F2';"
                                                    onmouseout="this.style.borderColor='var(--gray-soft)'; this.style.color='var(--gray-mid)'; this.style.background='white';"
                                                    title="Supprimer">
                                                <svg style="width:.8rem;height:.8rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Confirmation suppression inline --}}
                                <tr x-show="confirmDelete === {{ $ev->id }}" x-cloak
                                    style="border-bottom:1px solid #FECACA; background:#FEF2F2;">
                                    <td colspan="8" style="padding:1rem 1.25rem;">
                                        <div style="display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
                                            <p style="font-size:.825rem; font-weight:600; color:#DC2626;">
                                                ⚠️ Supprimer <strong>{{ $ev->titre }}</strong> ? Cette action est irréversible.
                                            </p>
                                            <div style="display:flex; gap:.625rem;">
                                                <button @click="confirmDelete=null"
                                                        style="padding:.5rem 1rem; border-radius:.875rem; font-size:.8rem; font-weight:600; color:var(--blue-night); background:white; border:1.5px solid var(--gray-soft); cursor:pointer; font-family:'Inter',sans-serif;">
                                                    Annuler
                                                </button>
                                                <form action="{{ route('organisateur.events.destroy', $ev->id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                            style="padding:.5rem 1rem; border-radius:.875rem; font-size:.8rem; font-weight:700; color:white; background:#DC2626; border:none; cursor:pointer; font-family:'Inter',sans-serif;">
                                                        Supprimer définitivement
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                @empty
                                <tr>
                                    <td colspan="8" style="padding:4rem; text-align:center;">
                                        <div style="display:flex; flex-direction:column; align-items:center; gap:1.25rem;">
                                            <div style="width:4.5rem; height:4.5rem; border-radius:1.5rem; background:var(--pearl); display:flex; align-items:center; justify-content:center;">
                                                <svg style="width:2rem;height:2rem;color:var(--gray-soft);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25"/></svg>
                                            </div>
                                            <div>
                                                <p style="font-size:.9rem; font-weight:700; color:var(--blue-night); margin-bottom:.4rem;">Aucun événement créé</p>
                                                <p style="font-size:.8rem; color:var(--gray-mid);">Commencez par créer votre premier événement.</p>
                                            </div>
                                         
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if(method_exists($events, 'hasPages') && $events->hasPages())
                    <div style="display:flex; align-items:center; justify-content:space-between; padding:1rem 1.5rem; border-top:1px solid var(--gray-soft);">
                        <p style="font-size:.75rem; color:var(--gray-mid);">
                            {{ $events->firstItem() }} – {{ $events->lastItem() }} sur {{ $events->total() }} événements
                        </p>
                        <nav style="display:flex; gap:.5rem;">
                            @if(!$events->onFirstPage())
                            <a href="{{ $events->previousPageUrl() }}" style="width:2rem; height:2rem; border-radius:.625rem; border:1.5px solid var(--gray-soft); display:flex; align-items:center; justify-content:center; text-decoration:none; color:var(--gray-mid); transition:all .2s; background:white;" onmouseover="this.style.borderColor='var(--blue-electric)'; this.style.color='var(--blue-electric)';" onmouseout="this.style.borderColor='var(--gray-soft)'; this.style.color='var(--gray-mid)';">
                                <svg style="width:.75rem;height:.75rem;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                            </a>
                            @endif
                            @foreach($events->getUrlRange(max(1,$events->currentPage()-2), min($events->lastPage(),$events->currentPage()+2)) as $page => $url)
                            <a href="{{ $url }}" style="width:2rem; height:2rem; border-radius:.625rem; display:flex; align-items:center; justify-content:center; text-decoration:none; font-size:.75rem; font-weight:600; transition:all .2s;" :style="null"
                               style="{{ $page == $events->currentPage() ? 'background:var(--blue-electric); color:white; border:1.5px solid var(--blue-electric);' : 'background:white; color:var(--gray-mid); border:1.5px solid var(--gray-soft);' }}">
                                {{ $page }}
                            </a>
                            @endforeach
                            @if($events->hasMorePages())
                            <a href="{{ $events->nextPageUrl() }}" style="width:2rem; height:2rem; border-radius:.625rem; border:1.5px solid var(--gray-soft); display:flex; align-items:center; justify-content:center; text-decoration:none; color:var(--gray-mid); transition:all .2s; background:white;" onmouseover="this.style.borderColor='var(--blue-electric)'; this.style.color='var(--blue-electric)';" onmouseout="this.style.borderColor='var(--gray-soft)'; this.style.color='var(--gray-mid)';">
                                <svg style="width:.75rem;height:.75rem;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                            </a>
                            @endif
                        </nav>
                    </div>
                    @endif
                </div>
            </div>


            {{-- ── VUE GRILLE ── --}}
            <div x-show="view === 'grille'" x-cloak>
                <div style="display:grid; gap:1.25rem;">
                    <style>@media(min-width:640px){.ev-grid{grid-template-columns:repeat(2,1fr)!important;}} @media(min-width:1280px){.ev-grid{grid-template-columns:repeat(3,1fr)!important;}}</style>
                    <div class="ev-grid" style="display:grid; grid-template-columns:1fr; gap:1.25rem;">

                        @forelse($events as $ev)
                        @php
                            $now   = now();
                            $debut = \Carbon\Carbon::parse($ev->date_debut);
                            $fin   = \Carbon\Carbon::parse($ev->date_fin);

                            if ($now->lt($debut))               { $sl='a_venir';  $slLabel='À venir';  $sc='#059669'; $sb='#ECFDF5'; }
                            elseif ($now->between($debut,$fin)) { $sl='en_cours'; $slLabel='En cours'; $sc='#D97706'; $sb='#FFFBEB'; }
                            else                                { $sl='termine';  $slLabel='Terminé';  $sc='#9CA3AF'; $sb='#F1F5F9'; }

                            $revenu = $ev->reservations->sum(fn($r) => (optional($ev->categorie)->prix ?? 0) * ($r->nb_places ?? 1));
                        @endphp

                        <div class="card ev-card" style="overflow:hidden;"
                             x-show="matchEvent('{{ strtolower($ev->titre) }}', '{{ strtolower($ev->lieu) }}', '{{ $sl }}')">

                            {{-- Image --}}
                            <div style="height:12rem; position:relative; background:linear-gradient(135deg,var(--blue-night),var(--blue-electric));">
                                @if($ev->image)
                                <img src="{{ $ev->image }}" alt="{{ $ev->titre }}" style="width:100%;height:100%;object-fit:cover;">
                                @endif
                                <div style="position:absolute; top:.875rem; left:.875rem; display:flex; gap:.5rem;">
                                    <span style="font-size:.65rem; font-weight:700; padding:.25rem .625rem; border-radius:2rem; background:{{ $sb }}; color:{{ $sc }};">{{ $slLabel }}</span>
                                    @if($ev->categorie)
                                    <span style="font-size:.65rem; font-weight:700; padding:.25rem .625rem; border-radius:2rem; background:rgba(10,22,40,.7); color:var(--gold-light);">{{ $ev->categorie->nom }}</span>
                                    @endif
                                </div>
                                <div style="position:absolute; top:.875rem; right:.875rem; display:flex; gap:.375rem;">
                                    <button @click="openEdit({
                                                id:           {{ $ev->id }},
                                                titre:        '{{ addslashes($ev->titre) }}',
                                                lieu:         '{{ addslashes($ev->lieu) }}',
                                                date_debut:   '{{ \Carbon\Carbon::parse($ev->date_debut)->format('Y-m-d') }}',
                                                date_fin:     '{{ \Carbon\Carbon::parse($ev->date_fin)->format('Y-m-d') }}',
                                                id_categorie: '{{ $ev->id_categorie ?? '' }}',
                                                description:  '{{ addslashes($ev->description ?? '') }}',
                                                prix:         '{{ optional($ev->categorie)->prix ?? '' }}',
                                                image:        '{{ $ev->image ?? '' }}'
                                            })"
                                            style="width:2rem; height:2rem; border-radius:.625rem; background:rgba(255,255,255,.9); border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; color:var(--blue-night); transition:all .2s; backdrop-filter:blur(4px);"
                                            onmouseover="this.style.background='white'; this.style.color='var(--blue-electric)';" onmouseout="this.style.background='rgba(255,255,255,.9)'; this.style.color='var(--blue-night)';">
                                        <svg style="width:.8rem;height:.8rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/></svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Contenu --}}
                            <div style="padding:1.25rem;">
                                <h3 style="font-size:.9rem; font-weight:700; color:var(--blue-night); margin-bottom:.5rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $ev->titre }}</h3>

                                <div style="display:flex; flex-wrap:wrap; gap:.75rem; font-size:.72rem; color:var(--gray-mid); margin-bottom:1rem;">
                                    <span>📍 {{ Str::limit($ev->lieu, 20) }}</span>
                                    <span>🗓 {{ $debut->translatedFormat('d M Y') }}</span>
                                </div>

                                {{-- Stats mini --}}
                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:.75rem; margin-bottom:1rem;">
                                    <div style="padding:.75rem; border-radius:1rem; background:var(--pearl); text-align:center;">
                                        <p style="font-size:1.1rem; font-weight:800; color:var(--blue-electric);">{{ $ev->reservations_count ?? 0 }}</p>
                                        <p style="font-size:.65rem; color:var(--gray-mid);">Réservations</p>
                                    </div>
                                    <div style="padding:.75rem; border-radius:1rem; background:var(--pearl); text-align:center;">
                                        <p style="font-size:1rem; font-weight:800; color:#059669;">{{ $revenu > 0 ? number_format($revenu/1000, 0, ',', ' ') . 'k' : '0' }}</p>
                                        <p style="font-size:.65rem; color:var(--gray-mid);">FCFA</p>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div style="display:flex; gap:.625rem;">
                                    <a href="{{ route('organisateur.events.show', $ev->id) }}"
                                       style="flex:1; display:flex; align-items:center; justify-content:center; gap:.375rem; padding:.625rem; border-radius:.875rem; font-size:.78rem; font-weight:600; color:var(--blue-electric); background:#EFF6FF; border:1.5px solid var(--blue-electric); text-decoration:none; transition:background .2s;"
                                       onmouseover="this.style.background='#DBEAFE'" onmouseout="this.style.background='#EFF6FF'">
                                        Voir détails
                                    </a>
                                    <button @click="confirmDelete = {{ $ev->id }}"
                                            style="width:2.25rem; height:2.25rem; border-radius:.875rem; border:1.5px solid var(--gray-soft); background:white; cursor:pointer; display:flex; align-items:center; justify-content:center; color:var(--gray-mid); transition:all .2s;"
                                            onmouseover="this.style.borderColor='#DC2626'; this.style.color='#DC2626'; this.style.background='#FEF2F2';" onmouseout="this.style.borderColor='var(--gray-soft)'; this.style.color='var(--gray-mid)'; this.style.background='white';">
                                        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        @empty
                        <div style="grid-column:1/-1; padding:4rem; text-align:center; color:var(--gray-mid);">
                            <p style="font-size:.9rem; font-weight:700; color:var(--blue-night); margin-bottom:.5rem;">Aucun événement</p>
                            <a href="" style="margin-top:1rem; padding:.875rem 1.75rem; border-radius:1rem; font-size:.875rem; font-weight:700; color:white; background:linear-gradient(135deg,var(--blue-electric),#1248b0); border:none; cursor:pointer; font-family:'Inter',sans-serif;">
                                + Créer un événement
                            </a>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
        {{-- /padding --}}


        {{-- ══════════════════════════════════════════
             MODAL CRÉER / MODIFIER route
             ══════════════════════════════════════════ --}}
        <div x-show="modal"
             x-cloak
             @keydown.escape.window="modal=false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             style="position:fixed; inset:0; z-index:60; display:flex; align-items:flex-end; justify-content:flex-end; padding:1rem; background:rgba(10,22,40,.5); backdrop-filter:blur(4px);"
             @click.self="modal=false">

            <div x-show="modal"
                 x-transition:enter="transition ease-out duration-250"
                 x-transition:enter-start="opacity-0 translate-x-8"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 style="background:white; border-radius:1.75rem; width:100%; max-width:32rem; max-height:calc(100vh - 2rem); overflow-y:auto; box-shadow:0 24px 64px rgba(10,22,40,.25);">

                {{-- Header modal --}}
                <div style="display:flex; align-items:center; justify-content:space-between; padding:1.5rem 1.75rem; border-bottom:1px solid var(--gray-soft); position:sticky; top:0; background:white; z-index:1;">
                    <div style="display:flex; align-items:center; gap:.875rem;">
                        <div style="width:2.5rem; height:2.5rem; border-radius:.875rem; display:flex; align-items:center; justify-content:center;"
                             :style="editMode ? 'background:#FFFBEB;' : 'background:#EFF6FF;'">
                            <svg style="width:1.1rem;height:1.1rem;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"
                                 :style="editMode ? 'color:#D97706;' : 'color:var(--blue-electric);'">
                                <path stroke-linecap="round" stroke-linejoin="round" x-show="!editMode" d="M12 4.5v15m7.5-7.5h-15"/>
                                <path stroke-linecap="round" stroke-linejoin="round" x-show="editMode" x-cloak d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 style="font-size:.95rem; font-weight:800; color:var(--blue-night);"
                                x-text="editMode ? 'Modifier l\'événement' : 'Créer un événement'"></h2>
                            <p style="font-size:.72rem; color:var(--gray-mid); margin-top:.1rem;"
                               x-text="editMode ? 'Mettez à jour les informations' : 'Remplissez les informations'"></p>
                        </div>
                    </div>
                    <button @click="modal=false" style="width:2rem; height:2rem; border-radius:50%; border:1.5px solid var(--gray-soft); background:white; cursor:pointer; display:flex; align-items:center; justify-content:center; color:var(--gray-mid); transition:all .2s;"
                            onmouseover="this.style.borderColor='var(--blue-electric)'; this.style.color='var(--blue-electric)';" onmouseout="this.style.borderColor='var(--gray-soft)'; this.style.color='var(--gray-mid)';">
                        <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Formulaire --}}
                <form :action="editMode ? `{{ url('organisateur/events') }}/${form.id}` : '{{ route('organisateur.events.store') }}'"
                      method="POST"
                      enctype="multipart/form-data"
                      @submit="loading=true"
                      style="padding:1.75rem; display:flex; flex-direction:column; gap:1.25rem;">
                    @csrf
                    <input type="hidden" name="_method" :value="editMode ? 'PUT' : 'POST'">

                    {{-- Titre --}}
                    <div>
                        <label style="display:block; font-size:.75rem; font-weight:600; color:var(--blue-night); margin-bottom:.5rem;">Titre <span style="color:#DC2626;">*</span></label>
                        <input type="text" name="titre" x-model="form.titre" placeholder="Ex : Forum Tech Dakar 2026" required class="inp">
                    </div>

                    {{-- Lieu --}}
                    <div>
                        <label style="display:block; font-size:.75rem; font-weight:600; color:var(--blue-night); margin-bottom:.5rem;">Lieu <span style="color:#DC2626;">*</span></label>
                        <div style="position:relative;">
                            <span style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); pointer-events:none;">
                                <svg style="width:.9rem;height:.9rem;color:var(--gray-mid);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                            </span>
                            <input type="text" name="lieu" x-model="form.lieu" placeholder="Ex : Dakar, Plateau" required class="inp" style="padding-left:3rem;">
                        </div>
                    </div>

                    {{-- Dates --}}
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:.875rem;">
                        <div>
                            <label style="display:block; font-size:.75rem; font-weight:600; color:var(--blue-night); margin-bottom:.5rem;">Date début <span style="color:#DC2626;">*</span></label>
                            <input type="date" name="date_debut" x-model="form.date_debut" required class="inp">
                        </div>
                        <div>
                            <label style="display:block; font-size:.75rem; font-weight:600; color:var(--blue-night); margin-bottom:.5rem;">Date fin <span style="color:#DC2626;">*</span></label>
                            <input type="date" name="date_fin" x-model="form.date_fin" required class="inp">
                        </div>
                    </div>

                    {{-- Catégorie --}}
                    <div>
                        <label style="display:block; font-size:.75rem; font-weight:600; color:var(--blue-night); margin-bottom:.5rem;">Catégorie</label>
                        <div style="position:relative;">
                            <select name="id_categorie" x-model="form.id_categorie" class="inp" style="appearance:none; padding-right:2.5rem;">
                                <option value="">Sélectionner une catégorie</option>
                                @foreach($categories ?? [] as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nom }} – {{ number_format($cat->prix, 0, ',', ' ') }} FCFA</option>
                                @endforeach
                            </select>
                            <div style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); pointer-events:none;">
                                <svg style="width:.875rem;height:.875rem;color:var(--gray-mid);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label style="display:block; font-size:.75rem; font-weight:600; color:var(--blue-night); margin-bottom:.5rem;">Description</label>
                        <textarea name="description" x-model="form.description" rows="4" placeholder="Décrivez votre événement…"
                                  style="width:100%; border:1.5px solid var(--gray-soft); border-radius:1rem; padding:.875rem 1rem; font-size:.875rem; font-family:'Inter',sans-serif; color:var(--blue-night); background:white; outline:none; resize:none; transition:border-color .2s;"
                                  onfocus="this.style.borderColor='var(--blue-electric)'" onblur="this.style.borderColor='var(--gray-soft)'"></textarea>
                    </div>

                    {{-- Image --}}
                    <div>
                        <label style="display:block; font-size:.75rem; font-weight:600; color:var(--blue-night); margin-bottom:.5rem;">Image de couverture</label>

                        {{-- Aperçu image --}}
                        <template x-if="form.imagePreview">
                            <div style="position:relative; margin-bottom:.75rem;">
                                <img :src="form.imagePreview" style="width:100%; height:8rem; object-fit:cover; border-radius:1rem; border:1.5px solid var(--gray-soft);">
                                <button type="button" @click="form.imagePreview=null; document.getElementById('modal-image').value=''"
                                        style="position:absolute; top:.5rem; right:.5rem; width:1.75rem; height:1.75rem; border-radius:50%; background:rgba(10,22,40,.7); border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; color:white; transition:background .2s;"
                                        onmouseover="this.style.background='#DC2626'" onmouseout="this.style.background='rgba(10,22,40,.7)'">
                                    <svg style="width:.75rem;height:.75rem;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </template>

                        <label for="modal-image" style="display:block; border:2px dashed var(--gray-soft); border-radius:1rem; padding:1.5rem; text-align:center; cursor:pointer; transition:all .2s;"
                               onmouseover="this.style.borderColor='var(--blue-electric)'; this.style.background='#EFF6FF';" onmouseout="this.style.borderColor='var(--gray-soft)'; this.style.background='white';">
                            <svg style="width:1.5rem;height:1.5rem;color:var(--gray-mid);margin:0 auto .5rem;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/></svg>
                            <p style="font-size:.8rem; font-weight:600; color:var(--blue-night);">Choisir une image</p>
                            <p style="font-size:.7rem; color:var(--gray-mid); margin-top:.25rem;">JPG, PNG, WebP — max 5 Mo</p>
                        </label>
                        <input type="file" id="modal-image" name="image" accept="image/*" @change="previewImage($event)" class="sr-only">
                    </div>

                    {{-- Footer modal --}}
                    <div style="display:flex; gap:.75rem; padding-top:.5rem; border-top:1px solid var(--gray-soft);">
                        <button type="button" @click="modal=false"
                                style="flex:1; padding:.875rem; border-radius:1rem; font-size:.875rem; font-weight:600; color:var(--blue-night); background:white; border:1.5px solid var(--gray-soft); cursor:pointer; font-family:'Inter',sans-serif; transition:all .2s;"
                                onmouseover="this.style.background='var(--pearl)'" onmouseout="this.style.background='white'">
                            Annuler
                        </button>
                        <button type="submit"
                                :disabled="loading"
                                style="flex:2; display:flex; align-items:center; justify-content:center; gap:.625rem; padding:.875rem; border-radius:1rem; font-size:.875rem; font-weight:700; color:white; border:none; cursor:pointer; font-family:'Inter',sans-serif; transition:filter .2s;"
                                :style="editMode ? 'background:linear-gradient(135deg,#D97706,#B45309); box-shadow:0 4px 16px rgba(217,119,6,.25);' : 'background:linear-gradient(135deg,var(--blue-electric),#1248b0); box-shadow:0 4px 16px rgba(30,95,216,.25);'"
                                onmouseover="this.style.filter='brightness(1.1)'" onmouseout="this.style.filter='none'">
                            <svg x-show="loading" x-cloak class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle style="opacity:.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path style="opacity:.75;" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            <svg x-show="!loading" style="width:1rem;height:1rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            <span x-text="loading ? 'Enregistrement…' : (editMode ? 'Mettre à jour' : 'Créer l\'événement')"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>


        {{-- ══════════════════════════════════════════
             MODAL CONFIRM DELETE (grille)
             ══════════════════════════════════════════ --}}
        <div x-show="confirmDelete !== null" x-cloak
             @keydown.escape.window="confirmDelete=null"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             style="position:fixed; inset:0; z-index:70; display:flex; align-items:center; justify-content:center; padding:1rem; background:rgba(10,22,40,.6); backdrop-filter:blur(4px);"
             @click.self="confirmDelete=null">

            <div x-show="confirmDelete !== null"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 style="background:white; border-radius:1.75rem; width:100%; max-width:22rem; padding:2rem; box-shadow:0 24px 64px rgba(10,22,40,.25);">
                <div style="width:3.5rem; height:3.5rem; border-radius:1.25rem; background:#FEF2F2; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
                    <svg style="width:1.75rem;height:1.75rem;color:#DC2626;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                </div>
                <h3 style="font-size:1rem; font-weight:800; color:var(--blue-night); text-align:center; margin-bottom:.5rem;">Supprimer l'événement</h3>
                <p style="font-size:.825rem; color:var(--gray-mid); text-align:center; margin-bottom:1.75rem;">Cette action est irréversible. Toutes les réservations liées seront également supprimées.</p>
                <div style="display:flex; gap:.75rem;">
                    <button @click="confirmDelete=null"
                            style="flex:1; padding:.875rem; border-radius:1rem; font-size:.875rem; font-weight:600; color:var(--blue-night); background:white; border:1.5px solid var(--gray-soft); cursor:pointer; font-family:'Inter',sans-serif;">
                        Annuler
                    </button>
                    <template x-for="ev in [confirmDelete]" :key="ev">
                        <form :action="`{{ url('organisateur/events') }}/${ev}`" method="POST" style="flex:2;">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit"
                                    style="width:100%; padding:.875rem; border-radius:1rem; font-size:.875rem; font-weight:700; color:white; background:#DC2626; border:none; cursor:pointer; font-family:'Inter',sans-serif;">
                                Supprimer
                            </button>
                        </form>
                    </template>
                </div>
            </div>
        </div>


        {{-- Toast --}}
        <div x-show="toastShow" x-cloak
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
             style="position:fixed; bottom:1.5rem; left:50%; transform:translateX(-50%); z-index:999; background:var(--blue-night); color:white; padding:.75rem 1.5rem; border-radius:1rem; font-size:.825rem; font-weight:600; box-shadow:0 8px 32px rgba(10,22,40,.3); white-space:nowrap; display:flex; align-items:center; gap:.625rem;">
            ✅ <span x-text="toastMsg"></span>
        </div>


        {{-- Bottom nav mobile --}}
        <nav class="bottom-nav">
            @foreach([
                [route('organisateur.dashboard'), 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z', 'Home', false],
                [route('organisateur.events.index'), 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25', 'Événements', true],
                [route('organisateur.reservations.index'), 'M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026', 'Résas', false],
                [route('organisateur.profil'), 'M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z', 'Profil', false],
            ] as [$href, $icon, $label, $isActive])
            <a href="{{ $href }}"
               style="flex:1; display:flex; flex-direction:column; align-items:center; gap:.2rem; padding:.375rem .25rem; border-radius:.75rem; text-decoration:none; font-family:'Inter',sans-serif; font-size:.58rem; font-weight:600; transition:all .2s; {{ $isActive ? 'color:var(--blue-electric); background:#EFF6FF;' : 'color:var(--gray-mid); background:transparent;' }}">
                <svg style="width:1.25rem;height:1.25rem;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                {{ $label }}
            </a>
            @endforeach
        </nav>

    </div>

</div>

</body>
</html>
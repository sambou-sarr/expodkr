<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un événement · ExpoDKR Organisateur</title>

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
        body { font-family:'Inter',sans-serif; background:var(--pearl); color:var(--blue-night); -webkit-font-smoothing:antialiased; }
        .font-display { font-family:'Instrument Serif',serif; }
        [x-cloak]     { display:none!important; }

        /* Sidebar */
        .sidebar { width:16rem; background:var(--blue-night); height:100vh; position:fixed; left:0; top:0; z-index:40; display:flex; flex-direction:column; transition:transform .3s ease; }
        .sidebar-link { display:flex; align-items:center; gap:.875rem; padding:.875rem 1.25rem; border-radius:1rem; font-size:.825rem; font-weight:500; color:rgba(255,255,255,.55); text-decoration:none; transition:all .2s; cursor:pointer; border:none; background:none; font-family:'Inter',sans-serif; width:100%; text-align:left; }
        .sidebar-link:hover  { color:white; background:rgba(255,255,255,.08); }
        .sidebar-link.active { color:white; background:rgba(30,95,216,.5); }

        /* Layout */
        .main-wrap { margin-left:16rem; min-height:100vh; }
        @media(max-width:1024px) {
            .sidebar   { transform:translateX(-100%); }
            .sidebar.open { transform:translateX(0); }
            .main-wrap { margin-left:0; }
            .page-pb   { padding-bottom:5rem; }
        }

        /* Cards */
        .card { background:white; border-radius:1.5rem; border:1px solid var(--gray-soft); box-shadow:0 2px 16px rgba(10,22,40,.05); }

        /* Inputs */
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

        /* Steps */
        .step-circle {
            width:2.25rem; height:2.25rem; border-radius:50%;
            display:flex; align-items:center; justify-content:center;
            font-size:.8rem; font-weight:700; flex-shrink:0;
            transition:all .3s;
        }
        .step-active { background:var(--blue-electric); color:white; box-shadow:0 4px 12px rgba(30,95,216,.3); }
        .step-done   { background:#10B981; color:white; }
        .step-idle   { background:var(--gray-soft); color:var(--gray-mid); }
        .step-line   { flex:1; height:2px; background:var(--gray-soft); margin:0 .5rem; transition:background .3s; }
        .step-line.done { background:#10B981; }

        /* Recap icon chip */
        .recap-icon {
            width:2rem; height:2rem; border-radius:.625rem; flex-shrink:0;
            display:flex; align-items:center; justify-content:center;
            background:white; border:1px solid var(--gray-soft);
        }

        /* Bottom nav */
        .bottom-nav { display:none; }
        @media(max-width:1024px) { .bottom-nav { display:flex; position:fixed; bottom:0; left:0; right:0; z-index:50; background:white; border-top:1px solid var(--gray-soft); padding:.625rem 1.25rem; gap:.25rem; } }

        @keyframes pulse-dot { 0%,100%{opacity:1;} 50%{opacity:.35;} }
        @keyframes fade-in   { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:none} }
        .fade-in { animation:fade-in .3s ease forwards; }
        @keyframes spin { to{transform:rotate(360deg);} }

        ::-webkit-scrollbar { width:4px; }
        ::-webkit-scrollbar-thumb { background:var(--blue-electric); border-radius:99px; }
    </style>
</head>
<body>

{{--
|--------------------------------------------------------------------------
| ExpoDKR – Créer un événement (Organisateur)
| Variables : $categories, Auth::user()
| Route POST : route('organisateur.events.store')
|--------------------------------------------------------------------------
--}}

<div x-data="{
        sidebarOpen: false,
        step:        1,
        loading:     false,
        imagePreview: null,

        /* Données formulaire */
        form: {
            titre:        '',
            lieu:         '',
            date_debut:   '',
            date_fin:     '',
            id_categorie: '',
            description:  '',
            billetterie:  true,
            capacite:     '',
            prix_libre:   '',
        },

        errors: {},

        /* Navigation étapes */
        validateStep1() {
            this.errors = {};
            if (!this.form.titre.trim())     this.errors.titre     = 'Le titre est requis.';
            if (!this.form.lieu.trim())      this.errors.lieu      = 'Le lieu est requis.';
            if (!this.form.date_debut)       this.errors.date_debut= 'La date de début est requise.';
            if (!this.form.date_fin)         this.errors.date_fin  = 'La date de fin est requise.';
            if (this.form.date_debut && this.form.date_fin && this.form.date_debut > this.form.date_fin)
                this.errors.date_fin = 'La date de fin doit être après la date de début.';
            return Object.keys(this.errors).length === 0;
        },

        validateStep2() {
            this.errors = {};
            return true;
        },

        nextStep() {
            if (this.step === 1 && this.validateStep1()) this.step = 2;
            else if (this.step === 2 && this.validateStep2()) this.step = 3;
        },

        prevStep() {
            if (this.step > 1) this.step--;
        },

        previewImage(e) {
            const file = e.target.files[0];
            if (!file) return;
            if (file.size > 5 * 1024 * 1024) { alert('Max 5 Mo.'); e.target.value=''; return; }
            const r = new FileReader();
            r.onload = (ev) => this.imagePreview = ev.target.result;
            r.readAsDataURL(file);
        },

        duree() {
            if (!this.form.date_debut || !this.form.date_fin) return '—';
            const d = (new Date(this.form.date_fin) - new Date(this.form.date_debut)) / 86400000;
            return d >= 0 ? (d + 1) + ' jour' + (d > 0 ? 's' : '') : '—';
        },

        selectedCatNom: '',
        selectedCatPrix: '',
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
                [route('organisateur.dashboard'),       'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z', 'Dashboard',       false],
                [route('organisateur.events.index'),    'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5', 'Mes événements',  true],
                [route('organisateur.reservations.index'),'M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026','Réservations',    false],
                ['#',                                   'M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75', 'Revenus',          false],
            ] as [$href, $icon, $label, $active])
            <a href="{{ $href }}" class="sidebar-link {{ $active ? 'active' : '' }}">
                <svg style="width:1rem;height:1rem;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                {{ $label }}
            </a>
            @endforeach
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
                    <button type="submit" style="width:1.75rem; height:1.75rem; border-radius:.5rem; background:rgba(255,255,255,.08); border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:background .2s;" onmouseover="this.style.background='rgba(220,38,38,.3)'" onmouseout="this.style.background='rgba(255,255,255,.08)'">
                        <svg style="width:.875rem;height:.875rem;color:rgba(255,255,255,.5);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen=false"
         style="position:fixed; inset:0; background:rgba(10,22,40,.5); backdrop-filter:blur(4px); z-index:39;"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>


    {{-- ══════════════════════════════════════════════
         MAIN
         ══════════════════════════════════════════════ --}}
    <div class="main-wrap">

        {{-- Topbar --}}
        <div style="background:white; border-bottom:1px solid var(--gray-soft); padding:0 1.5rem; height:4rem; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:30; gap:1rem;">
            <div style="display:flex; align-items:center; gap:1rem;">
                <button @click="sidebarOpen=!sidebarOpen" class="lg:hidden"
                        style="width:2.25rem; height:2.25rem; border-radius:.75rem; border:1.5px solid var(--gray-soft); background:white; cursor:pointer; display:flex; align-items:center; justify-content:center;">
                    <svg style="width:1rem;height:1rem;color:var(--blue-night);" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                </button>
                <nav style="display:flex; align-items:center; gap:.5rem; font-size:.75rem; color:var(--gray-mid);">
                    <a href="{{ route('organisateur.dashboard') }}" style="color:var(--gray-mid); text-decoration:none; transition:color .2s;" onmouseover="this.style.color='var(--blue-electric)'" onmouseout="this.style.color='var(--gray-mid)'">Dashboard</a>
                    <svg style="width:.75rem;height:.75rem;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                    <a href="{{ route('organisateur.events.index') }}" style="color:var(--gray-mid); text-decoration:none; transition:color .2s;" onmouseover="this.style.color='var(--blue-electric)'" onmouseout="this.style.color='var(--gray-mid)'">Événements</a>
                    <svg style="width:.75rem;height:.75rem;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                    <span style="font-weight:700; color:var(--blue-night);">Créer</span>
                </nav>
            </div>

            <a href="{{ route('organisateur.events.index') }}"
               style="display:flex; align-items:center; gap:.5rem; font-size:.78rem; font-weight:600; color:var(--gray-mid); text-decoration:none; padding:.5rem 1rem; border-radius:.875rem; border:1.5px solid var(--gray-soft); background:white; transition:all .2s;"
               onmouseover="this.style.borderColor='var(--blue-electric)'; this.style.color='var(--blue-electric)'" onmouseout="this.style.borderColor='var(--gray-soft)'; this.style.color='var(--gray-mid)'">
                <svg style="width:.8rem;height:.8rem;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                Retour à la liste
            </a>
        </div>


        {{-- Contenu --}}
        <div style="padding:2rem 1.5rem;" class="fade-in">

            {{-- ── En-tête ── --}}
            <div style="margin-bottom:2.5rem;">
                <h1 class="font-display" style="font-size:1.8rem; color:var(--blue-night); margin-bottom:.3rem;">
                    Créer un événement
                </h1>
                <p style="font-size:.875rem; color:var(--gray-mid);">
                    Remplissez les informations en 3 étapes pour publier votre événement sur ExpoDKR.
                </p>
            </div>


            {{-- ── Stepper ── --}}
            <div style="display:flex; align-items:center; margin-bottom:2.5rem; max-width:36rem;">

                @foreach([['1','Informations'],['2','Médias & options'],['3','Récapitulatif']] as $i => [$num,$label])
                <div style="display:flex; align-items:center; flex:1;">
                    <div style="display:flex; flex-direction:column; align-items:center; gap:.4rem; flex:1;">
                        <div class="step-circle"
                             :class="{
                                'step-done':   step > {{ $num }},
                                'step-active': step == {{ $num }},
                                'step-idle':   step < {{ $num }}
                             }">
                            <span x-show="step <= {{ $num }}">{{ $num }}</span>
                            <svg x-show="step > {{ $num }}" x-cloak style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                        </div>
                        <span style="font-size:.7rem; font-weight:600;"
                              :style="step >= {{ $num }} ? 'color:var(--blue-night)' : 'color:var(--gray-mid)'">
                            {{ $label }}
                        </span>
                    </div>
                    @if($i < 2)
                    <div class="step-line" :class="step > {{ $num }} ? 'done' : ''" style="margin-bottom:1.25rem;"></div>
                    @endif
                </div>
                @endforeach
            </div>


            {{-- ════════════════════════
                 FORMULAIRE PRINCIPAL
                 ════════════════════════ --}}
            <form method="POST"
                  action="{{ route('organisateur.events.store') }}"
                  enctype="multipart/form-data"
                  @submit="loading=true"
                  id="event-form"
                  novalidate>
                @csrf

                {{-- Champs cachés Alpine → server --}}
                <input type="hidden" name="titre"        :value="form.titre">
                <input type="hidden" name="lieu"         :value="form.lieu">
                <input type="hidden" name="date_debut"   :value="form.date_debut">
                <input type="hidden" name="date_fin"     :value="form.date_fin">
                <input type="hidden" name="id_categorie" :value="form.id_categorie">
                <input type="hidden" name="description"  :value="form.description">
                <input type="hidden" name="capacite"     :value="form.capacite">

                <div style="display:grid; gap:1.5rem;">
                    <style>@media(min-width:1024px){.form-grid{grid-template-columns:1fr 320px!important;}}</style>
                    <div class="form-grid" style="display:grid; grid-template-columns:1fr; gap:1.5rem; align-items:start;">

                        {{-- ════ COLONNE PRINCIPALE ════ --}}
                        <div style="display:flex; flex-direction:column; gap:1.25rem;">


                            {{-- ── ÉTAPE 1 : Informations ── --}}
                            <div x-show="step === 1"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-x-4"
                                 x-transition:enter-end="opacity-100 translate-x-0">

                                {{-- Erreurs validation Laravel --}}
                                @if($errors->any())
                                <div style="padding:1rem; border-radius:1.25rem; background:#FEF2F2; border:1px solid #FECACA; display:flex; gap:.75rem;">
                                    <svg style="width:1rem;height:1rem;color:#DC2626;flex-shrink:0;margin-top:.1rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                                    <ul style="list-style:none;">
                                        @foreach($errors->all() as $e)
                                        <li style="font-size:.8rem; color:#DC2626; font-weight:500;">{{ $e }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                {{-- Card informations générales --}}
                                <div class="card" style="overflow:hidden;">
                                    <div style="display:flex; align-items:center; gap:.875rem; padding:1.25rem 1.5rem; border-bottom:1px solid var(--gray-soft);">
                                        <div style="width:2.25rem; height:2.25rem; border-radius:.875rem; background:#EFF6FF; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                            <svg style="width:1rem;height:1rem;color:var(--blue-electric);" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/></svg>
                                        </div>
                                        <div>
                                            <p style="font-size:.875rem; font-weight:700; color:var(--blue-night);">Informations générales</p>
                                            <p style="font-size:.72rem; color:var(--gray-mid);">Titre, lieu et dates de l'événement</p>
                                        </div>
                                    </div>

                                    <div style="padding:1.5rem; display:flex; flex-direction:column; gap:1.25rem;">

                                        {{-- Titre --}}
                                        <div>
                                            <label style="display:block; font-size:.75rem; font-weight:600; color:var(--blue-night); margin-bottom:.5rem;">
                                                Titre de l'événement <span style="color:#DC2626;">*</span>
                                            </label>
                                            <input type="text"
                                                   x-model="form.titre"
                                                   placeholder="Ex : Forum Tech Dakar 2026"
                                                   :class="errors.titre ? 'inp err' : 'inp'">
                                            <p x-show="errors.titre" x-cloak style="font-size:.72rem; color:#DC2626; margin-top:.375rem;" x-text="errors.titre"></p>
                                        </div>

                                        {{-- Lieu --}}
                                        <div>
                                            <label style="display:block; font-size:.75rem; font-weight:600; color:var(--blue-night); margin-bottom:.5rem;">
                                                Lieu <span style="color:#DC2626;">*</span>
                                            </label>
                                            <div style="position:relative;">
                                                <span style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); pointer-events:none;">
                                                    <svg style="width:.9rem;height:.9rem;color:var(--gray-mid);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                                </span>
                                                <input type="text"
                                                       x-model="form.lieu"
                                                       placeholder="Ex : Centre de Conférences de Diamniadio"
                                                       :class="errors.lieu ? 'inp err inp-icon' : 'inp inp-icon'">
                                            </div>
                                            <p x-show="errors.lieu" x-cloak style="font-size:.72rem; color:#DC2626; margin-top:.375rem;" x-text="errors.lieu"></p>
                                        </div>

                                        {{-- Dates --}}
                                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                                            <div>
                                                <label style="display:block; font-size:.75rem; font-weight:600; color:var(--blue-night); margin-bottom:.5rem;">
                                                    Date début <span style="color:#DC2626;">*</span>
                                                </label>
                                                <div style="position:relative;">
                                                    <span style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); pointer-events:none;">
                                                        <svg style="width:.9rem;height:.9rem;color:var(--gray-mid);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25"/></svg>
                                                    </span>
                                                    <input type="date"
                                                           x-model="form.date_debut"
                                                           :class="errors.date_debut ? 'inp err inp-icon' : 'inp inp-icon'">
                                                </div>
                                                <p x-show="errors.date_debut" x-cloak style="font-size:.72rem; color:#DC2626; margin-top:.375rem;" x-text="errors.date_debut"></p>
                                            </div>
                                            <div>
                                                <label style="display:block; font-size:.75rem; font-weight:600; color:var(--blue-night); margin-bottom:.5rem;">
                                                    Date fin <span style="color:#DC2626;">*</span>
                                                </label>
                                                <div style="position:relative;">
                                                    <span style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); pointer-events:none;">
                                                        <svg style="width:.9rem;height:.9rem;color:var(--gray-mid);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25"/></svg>
                                                    </span>
                                                    <input type="date"
                                                           x-model="form.date_fin"
                                                           :class="errors.date_fin ? 'inp err inp-icon' : 'inp inp-icon'">
                                                </div>
                                                <p x-show="errors.date_fin" x-cloak style="font-size:.72rem; color:#DC2626; margin-top:.375rem;" x-text="errors.date_fin"></p>
                                            </div>
                                        </div>

                                        {{-- Catégorie --}}
                                        <div>
                                            <label style="display:block; font-size:.75rem; font-weight:600; color:var(--blue-night); margin-bottom:.5rem;">Catégorie</label>
                                            <div style="position:relative;">
                                                <select x-model="form.id_categorie"
                                                        @change="
                                                            const opt = $event.target.options[$event.target.selectedIndex];
                                                            selectedCatNom  = opt.text.split('–')[0].trim();
                                                            selectedCatPrix = opt.dataset.prix ?? '';
                                                        "
                                                        class="inp" style="appearance:none; padding-right:2.5rem;">
                                                    <option value="">Sélectionner une catégorie</option>
                                                    @foreach($categories ?? [] as $cat)
                                                    <option value="{{ $cat->id }}" data-prix="{{ $cat->prix }}">
                                                        {{ $cat->nom }} – {{ number_format($cat->prix, 0, ',', ' ') }} FCFA
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <div style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); pointer-events:none;">
                                                    <svg style="width:.9rem;height:.9rem;color:var(--gray-mid);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Description --}}
                                        <div>
                                            <label style="display:block; font-size:.75rem; font-weight:600; color:var(--blue-night); margin-bottom:.5rem;">
                                                Description
                                                <span style="font-weight:400; color:var(--gray-mid); margin-left:.25rem;">(recommandé)</span>
                                            </label>
                                            <textarea x-model="form.description"
                                                      rows="5"
                                                      placeholder="Décrivez l'événement, son programme, ses objectifs…"
                                                      style="width:100%; border:1.5px solid var(--gray-soft); border-radius:1rem; padding:.875rem 1rem; font-size:.875rem; font-family:'Inter',sans-serif; color:var(--blue-night); background:white; resize:none; outline:none; transition:border-color .2s;"
                                                      onfocus="this.style.borderColor='var(--blue-electric)'" onblur="this.style.borderColor='var(--gray-soft)'"></textarea>
                                        </div>

                                    </div>
                                </div>

                                {{-- Bouton étape 1 --}}
                                <button type="button"
                                        @click="nextStep()"
                                        style="width:100%; display:flex; align-items:center; justify-content:center; gap:.625rem; padding:1rem; border-radius:1rem; font-size:.9rem; font-weight:700; color:white; border:none; cursor:pointer; background:linear-gradient(135deg,var(--blue-electric),#1248b0); box-shadow:0 4px 16px rgba(30,95,216,.3); font-family:'Inter',sans-serif; transition:filter .2s;"
                                        onmouseover="this.style.filter='brightness(1.1)'" onmouseout="this.style.filter='none'">
                                    Continuer → Médias & options
                                </button>
                            </div>


                            {{-- ── ÉTAPE 2 : Médias & Options ── --}}
                            <div x-show="step === 2"
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-x-4"
                                 x-transition:enter-end="opacity-100 translate-x-0">

                                {{-- Image de couverture --}}
                                <div class="card" style="overflow:hidden;">
                                    <div style="display:flex; align-items:center; gap:.875rem; padding:1.25rem 1.5rem; border-bottom:1px solid var(--gray-soft);">
                                        <div style="width:2.25rem; height:2.25rem; border-radius:.875rem; background:#F5F3FF; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                            <svg style="width:1rem;height:1rem;color:#7C3AED;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
                                        </div>
                                        <div>
                                            <p style="font-size:.875rem; font-weight:700; color:var(--blue-night);">Image de couverture</p>
                                            <p style="font-size:.72rem; color:var(--gray-mid);">Format recommandé : 1200×630px · JPG, PNG</p>
                                        </div>
                                    </div>
                                    <div style="padding:1.5rem;">

                                        {{-- Aperçu --}}
                                        <template x-if="imagePreview">
                                            <div style="position:relative; margin-bottom:1rem;">
                                                <img :src="imagePreview" style="width:100%; height:12rem; object-fit:cover; border-radius:1.25rem; border:1.5px solid var(--gray-soft);">
                                                <div style="position:absolute; inset:0; border-radius:1.25rem; background:linear-gradient(to top,rgba(10,22,40,.6),transparent); display:flex; align-items:flex-end; padding:1rem;">
                                                    <div style="display:flex; align-items:center; justify-content:space-between; width:100%;">
                                                        <p style="font-size:.75rem; color:rgba(255,255,255,.8);">Image sélectionnée</p>
                                                        <button type="button"
                                                                @click="imagePreview=null; document.getElementById('image-input').value=''"
                                                                style="display:flex; align-items:center; gap:.375rem; padding:.375rem .75rem; border-radius:.625rem; background:rgba(255,255,255,.2); color:white; border:none; cursor:pointer; font-size:.72rem; font-weight:600; backdrop-filter:blur(4px);">
                                                            <svg style="width:.75rem;height:.75rem;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                                                            Changer
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>

                                        {{-- Zone upload --}}
                                        <label for="image-input" x-show="!imagePreview"
                                               style="display:block; border:2px dashed var(--gray-soft); border-radius:1.25rem; padding:2.5rem 1.5rem; text-align:center; cursor:pointer; transition:all .2s;"
                                               onmouseover="this.style.borderColor='var(--blue-electric)'; this.style.background='#EFF6FF';" onmouseout="this.style.borderColor='var(--gray-soft)'; this.style.background='white';">
                                            <div style="width:3.5rem; height:3.5rem; border-radius:1.25rem; background:var(--pearl); display:flex; align-items:center; justify-content:center; margin:0 auto .875rem;">
                                                <svg style="width:1.5rem;height:1.5rem;color:var(--gray-mid);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/></svg>
                                            </div>
                                            <p style="font-size:.875rem; font-weight:600; color:var(--blue-night);">Glisser-déposer ou cliquer</p>
                                            <p style="font-size:.75rem; color:var(--gray-mid); margin-top:.35rem;">JPG, PNG, WebP — max 5 Mo</p>
                                        </label>
                                        <input type="file" id="image-input" name="image" accept="image/*" @change="previewImage($event)" class="sr-only">
                                    </div>
                                </div>

                                {{-- Options billetterie --}}
                                <div class="card" style="overflow:hidden;">
                                    <div style="display:flex; align-items:center; gap:.875rem; padding:1.25rem 1.5rem; border-bottom:1px solid var(--gray-soft);">
                                        <div style="width:2.25rem; height:2.25rem; border-radius:.875rem; background:#ECFDF5; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                            <svg style="width:1rem;height:1rem;color:#059669;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026"/></svg>
                                        </div>
                                        <div>
                                            <p style="font-size:.875rem; font-weight:700; color:var(--blue-night);">Billetterie</p>
                                            <p style="font-size:.72rem; color:var(--gray-mid);">Capacité et options de participation</p>
                                        </div>
                                    </div>
                                    <div style="padding:1.5rem; display:flex; flex-direction:column; gap:1.1rem;">

                                        {{-- Activer billetterie --}}
                                        <label style="display:flex; align-items:center; justify-content:space-between; cursor:pointer; padding:.875rem 1rem; border-radius:1rem; background:var(--pearl); border:1.5px solid var(--gray-soft);">
                                            <div>
                                                <p style="font-size:.875rem; font-weight:600; color:var(--blue-night);">Activer la billetterie en ligne</p>
                                                <p style="font-size:.72rem; color:var(--gray-mid); margin-top:.15rem;">Permettre les réservations via ExpoDKR</p>
                                            </div>
                                            <div x-data style="position:relative; flex-shrink:0; margin-left:1rem;">
                                                <input type="checkbox" name="billetterie" x-model="form.billetterie" class="sr-only">
                                                <div @click="form.billetterie = !form.billetterie"
                                                     style="width:3rem; height:1.5rem; border-radius:99px; cursor:pointer; transition:background .2s; display:flex; align-items:center; padding:.15rem;"
                                                     :style="form.billetterie ? 'background:var(--blue-electric);' : 'background:var(--gray-soft);'">
                                                    <div style="width:1.2rem; height:1.2rem; border-radius:50%; background:white; box-shadow:0 1px 4px rgba(0,0,0,.15); transition:transform .2s;"
                                                         :style="form.billetterie ? 'transform:translateX(1.5rem);' : 'transform:translateX(0);'"></div>
                                                </div>
                                            </div>
                                        </label>

                                        {{-- Capacité --}}
                                        <div x-show="form.billetterie">
                                            <label style="display:block; font-size:.75rem; font-weight:600; color:var(--blue-night); margin-bottom:.5rem;">
                                                Capacité maximale
                                                <span style="font-weight:400; color:var(--gray-mid);">(optionnel)</span>
                                            </label>
                                            <div style="position:relative;">
                                                <span style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); pointer-events:none;">
                                                    <svg style="width:.9rem;height:.9rem;color:var(--gray-mid);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Z"/></svg>
                                                </span>
                                                <input type="number" x-model="form.capacite" placeholder="Ex : 500" min="1" class="inp inp-icon">
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                {{-- Boutons étape 2 --}}
                                <div style="display:flex; gap:.75rem;">
                                    <button type="button" @click="prevStep()"
                                            style="flex:1; padding:1rem; border-radius:1rem; font-size:.875rem; font-weight:600; color:var(--blue-night); border:1.5px solid var(--gray-soft); background:white; cursor:pointer; font-family:'Inter',sans-serif; transition:background .2s;"
                                            onmouseover="this.style.background='var(--pearl)'" onmouseout="this.style.background='white'">
                                        ← Retour
                                    </button>
                                    <button type="button" @click="nextStep()"
                                            style="flex:2; display:flex; align-items:center; justify-content:center; gap:.625rem; padding:1rem; border-radius:1rem; font-size:.9rem; font-weight:700; color:white; border:none; cursor:pointer; background:linear-gradient(135deg,var(--blue-electric),#1248b0); font-family:'Inter',sans-serif; transition:filter .2s;"
                                            onmouseover="this.style.filter='brightness(1.1)'" onmouseout="this.style.filter='none'">
                                        Continuer → Récapitulatif
                                    </button>
                                </div>
                            </div>


                            {{-- ── ÉTAPE 3 : Récapitulatif ── --}}
                            <div x-show="step === 3"
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-x-4"
                                 x-transition:enter-end="opacity-100 translate-x-0">

                                <div class="card" style="overflow:hidden;">
                                    <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--gray-soft); background:linear-gradient(135deg,var(--blue-night),var(--blue-deep)); position:relative; overflow:hidden;">
                                        <div style="position:absolute; inset:0; opacity:.1; background-image:linear-gradient(rgba(201,168,76,.5) 1px,transparent 1px),linear-gradient(90deg,rgba(201,168,76,.5) 1px,transparent 1px); background-size:25px 25px;" aria-hidden="true"></div>
                                        <p style="font-size:.68rem; font-weight:700; letter-spacing:.15em; text-transform:uppercase; color:var(--gold-light); position:relative; margin-bottom:.3rem;">Récapitulatif</p>
                                        <p class="font-display" style="font-size:1.4rem; color:white; position:relative; line-height:1.25;" x-text="form.titre || 'Titre de l\'événement'"></p>
                                    </div>

                                    <div style="padding:1.5rem; display:flex; flex-direction:column; gap:1rem;">

                                        @foreach([
                                            ['M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z', 'Lieu',        'form.lieu || \'—\''],
                                            ['M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25', 'Date début',  'form.date_debut ? new Date(form.date_debut).toLocaleDateString(\'fr-FR\',{day:\'2-digit\',month:\'long\',year:\'numeric\'}) : \'—\''],
                                            ['M3 3v1.5M3 21v-6m0 0 2.77-.693a9 9 0 0 1 6.208.682l.108.054a9 9 0 0 0 6.086.71l3.114-.732a48.524 48.524 0 0 1-.005-10.499l-3.11.732a9 9 0 0 1-6.085-.711l-.108-.054a9 9 0 0 0-6.208-.682L3 4.5M3 15V4.5', 'Date fin',    'form.date_fin   ? new Date(form.date_fin).toLocaleDateString(\'fr-FR\',{day:\'2-digit\',month:\'long\',year:\'numeric\'})   : \'—\''],
                                            ['M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z', 'Durée',       'duree()'],
                                            ['M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3ZM6 6h.008v.008H6V6Z', 'Catégorie',   'selectedCatNom || \'Non définie\''],
                                            ['M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z', 'Prix',        'selectedCatPrix ? Number(selectedCatPrix).toLocaleString(\'fr-FR\') + \' FCFA\' : \'Gratuit\''],
                                            ['M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Z M4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z', 'Capacité',    'form.capacite ? form.capacite + \' participants max\' : \'Illimitée\''],
                                            ['M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z', 'Billetterie', 'form.billetterie ? \'Activée\' : \'Désactivée\''],
                                        ] as [$icon, $label, $expr])
                                        <div style="display:flex; align-items:center; gap:.875rem; padding:.75rem; border-radius:.875rem; background:var(--pearl);">
                                            <span class="recap-icon">
                                                <svg style="width:1rem;height:1rem;color:var(--blue-electric);" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                                            </span>
                                            <div style="display:flex; align-items:center; justify-content:space-between; flex:1; gap:.5rem;">
                                                <span style="font-size:.75rem; color:var(--gray-mid); font-weight:500;">{{ $label }}</span>
                                                <span style="font-size:.8rem; font-weight:700; color:var(--blue-night); text-align:right;" x-text="{{ $expr }}"></span>
                                            </div>
                                        </div>
                                        @endforeach

                                        {{-- Aperçu image --}}
                                        <template x-if="imagePreview">
                                            <div>
                                                <p style="font-size:.75rem; font-weight:600; color:var(--gray-mid); margin-bottom:.5rem;">Image de couverture</p>
                                                <img :src="imagePreview" style="width:100%; height:8rem; object-fit:cover; border-radius:1.25rem; border:1.5px solid var(--gray-soft);">
                                            </div>
                                        </template>

                                        {{-- Description --}}
                                        <div x-show="form.description">
                                            <p style="font-size:.75rem; font-weight:600; color:var(--gray-mid); margin-bottom:.5rem;">Description</p>
                                            <p style="font-size:.825rem; color:var(--blue-night); line-height:1.65; padding:.875rem; border-radius:.875rem; background:var(--pearl); display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden;" x-text="form.description"></p>
                                        </div>

                                    </div>
                                </div>

                                {{-- Alerte modification --}}
                                <div style="padding:.875rem 1rem; border-radius:1rem; background:#FFFBEB; border:1px solid #FDE68A; display:flex; gap:.75rem;">
                                    <svg style="width:.9rem;height:.9rem;color:#D97706;flex-shrink:0;margin-top:.15rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                                    <p style="font-size:.75rem; color:#92400E;">Vérifiez bien les informations avant de publier. Vous pourrez les modifier depuis votre espace organisateur.</p>
                                </div>

                                {{-- Boutons étape 3 --}}
                                <div style="display:flex; gap:.75rem;">
                                    <button type="button" @click="prevStep()"
                                            style="flex:1; padding:1rem; border-radius:1rem; font-size:.875rem; font-weight:600; color:var(--blue-night); border:1.5px solid var(--gray-soft); background:white; cursor:pointer; font-family:'Inter',sans-serif; transition:background .2s;"
                                            onmouseover="this.style.background='var(--pearl)'" onmouseout="this.style.background='white'">
                                        ← Modifier
                                    </button>
                                    <button type="submit"
                                            :disabled="loading"
                                            style="flex:2; display:flex; align-items:center; justify-content:center; gap:.625rem; padding:1rem; border-radius:1rem; font-size:.9rem; font-weight:700; color:var(--blue-night); border:none; cursor:pointer; font-family:'Inter',sans-serif; transition:filter .2s, opacity .2s;"
                                            :style="loading ? 'opacity:.6; cursor:not-allowed; background:linear-gradient(135deg,var(--gold),var(--gold-light));' : 'background:linear-gradient(135deg,var(--gold),var(--gold-light)); box-shadow:0 4px 16px rgba(201,168,76,.35);'"
                                            onmouseover="if(!loading) this.style.filter='brightness(1.08)'" onmouseout="this.style.filter='none'">
                                        <svg x-show="loading" x-cloak style="width:1rem;height:1rem;animation:spin 1s linear infinite;" fill="none" viewBox="0 0 24 24">
                                            <circle style="opacity:.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path style="opacity:.75;" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4z"/>
                                        </svg>
                                        <svg x-show="!loading" style="width:1rem;height:1rem;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                        <span x-text="loading ? 'Publication en cours…' : 'Publier l\'événement'"></span>
                                    </button>
                                </div>
                            </div>

                        </div>
                        {{-- /colonne principale --}}


                        {{-- ════ SIDEBAR DROITE ════ --}}
                        <div style="display:flex; flex-direction:column; gap:1.25rem; position:sticky; top:5.5rem;">

                            {{-- Aperçu carte --}}
                            <div class="card" style="overflow:hidden;">
                                <div style="padding:1rem 1.25rem; border-bottom:1px solid var(--gray-soft);">
                                    <p style="font-size:.78rem; font-weight:700; color:var(--blue-night);">Aperçu de l'événement</p>
                                    <p style="font-size:.68rem; color:var(--gray-mid); margin-top:.15rem;">Mise à jour en temps réel</p>
                                </div>

                                {{-- Image preview --}}
                                <div style="height:9rem; position:relative; background:linear-gradient(135deg,var(--blue-night),var(--blue-electric)); overflow:hidden;">
                                    <img x-show="imagePreview" :src="imagePreview" style="width:100%; height:100%; object-fit:cover;" alt="Aperçu">
                                    <div x-show="!imagePreview" style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center;">
                                        <svg style="width:2.5rem;height:2.5rem;color:rgba(255,255,255,.3);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75"/></svg>
                                    </div>
                                    <div style="position:absolute; inset:0; background:linear-gradient(to top,rgba(10,22,40,.8),transparent); display:flex; align-items:flex-end; padding:.875rem;">
                                        <p class="font-display" style="font-size:1rem; color:white; line-height:1.25; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;"
                                           x-text="form.titre || 'Titre de l\'événement'"></p>
                                    </div>
                                </div>

                                <div style="padding:1rem 1.25rem;">
                                    <div style="display:flex; flex-direction:column; gap:.625rem;">
                                        <div style="display:flex; align-items:center; gap:.625rem; font-size:.75rem; color:var(--gray-mid);">
                                            <svg style="width:.875rem;height:.875rem;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                            <span x-text="form.lieu || 'Lieu de l\'événement'"></span>
                                        </div>
                                        <div style="display:flex; align-items:center; gap:.625rem; font-size:.75rem; color:var(--gray-mid);">
                                            <svg style="width:.875rem;height:.875rem;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25"/></svg>
                                            <span x-text="form.date_debut ? new Date(form.date_debut).toLocaleDateString('fr-FR',{day:'2-digit',month:'long',year:'numeric'}) : 'Date de début'"></span>
                                        </div>
                                        <div style="display:flex; align-items:center; justify-content:space-between; padding:.75rem; border-radius:.875rem; background:var(--pearl); margin-top:.25rem;">
                                            <span style="font-size:.72rem; color:var(--gray-mid);">Prix participation</span>
                                            <span style="font-size:.875rem; font-weight:800; color:var(--blue-electric);"
                                                  x-text="selectedCatPrix ? Number(selectedCatPrix).toLocaleString('fr-FR') + ' FCFA' : 'Gratuit'"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            {{-- Conseils --}}
                            <div style="border-radius:1.5rem; padding:1.25rem; background:#F8FAFC; border:1px solid var(--gray-soft);">
                                <div style="display:flex; gap:.875rem;">
                                    <div style="width:2.25rem; height:2.25rem; border-radius:.875rem; background:#EFF6FF; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                        <svg style="width:1rem;height:1rem;color:var(--blue-electric);" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z"/></svg>
                                    </div>
                                    <div>
                                        <p style="font-size:.78rem; font-weight:700; color:var(--blue-night); margin-bottom:.875rem;">Conseils pour votre événement</p>
                                        <ul style="list-style:none; display:flex; flex-direction:column; gap:.75rem;">
                                            @foreach([
                                                'Un titre accrocheur augmente les réservations de 40%',
                                                'Ajoutez une image haute résolution (1200×630px)',
                                                'Une description complète rassure les participants',
                                                'Précisez l\'adresse exacte pour faciliter l\'accès',
                                            ] as $tip)
                                            <li style="display:flex; align-items:flex-start; gap:.625rem; font-size:.75rem; color:var(--gray-mid); line-height:1.55;">
                                                <span style="width:.375rem; height:.375rem; border-radius:50%; background:var(--blue-electric); flex-shrink:0; margin-top:.45rem;"></span>
                                                {{ $tip }}
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            {{-- Étape indicator mobile --}}
                            <div class="card" style="padding:1rem 1.25rem;">
                                <p style="font-size:.72rem; font-weight:600; color:var(--gray-mid); margin-bottom:.625rem;">Progression</p>
                                <div style="display:flex; gap:.375rem;">
                                    @foreach([1,2,3] as $s)
                                    <div style="flex:1; height:.375rem; border-radius:99px; transition:background .3s;"
                                         :style="step >= {{ $s }} ? 'background:var(--blue-electric);' : 'background:var(--gray-soft);'"></div>
                                    @endforeach
                                </div>
                                <p style="font-size:.72rem; color:var(--gray-mid); margin-top:.5rem; text-align:right;" x-text="`Étape ${step}/3`"></p>
                            </div>

                        </div>
                        {{-- /sidebar droite --}}

                    </div>
                </div>

            </form>

        </div>
        {{-- /contenu --}}


        {{-- Bottom nav --}}
        <nav class="bottom-nav">
            @foreach([
                [route('organisateur.dashboard'),      'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z', 'Home',       false],
                [route('organisateur.events.index'),   'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25', 'Événements', true],
                [route('organisateur.reservations.index'),'M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026','Résas',      false],
            ] as [$href, $icon, $label, $active])
            <a href="{{ $href }}"
               style="flex:1; display:flex; flex-direction:column; align-items:center; gap:.2rem; padding:.375rem .25rem; border-radius:.75rem; text-decoration:none; font-family:'Inter',sans-serif; font-size:.58rem; font-weight:600; transition:all .2s; {{ $active ? 'color:var(--blue-electric); background:#EFF6FF;' : 'color:var(--gray-mid);' }}">
                <svg style="width:1.25rem;height:1.25rem;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                {{ $label }}
            </a>
            @endforeach
        </nav>

    </div>

</div>

</body>
</html>
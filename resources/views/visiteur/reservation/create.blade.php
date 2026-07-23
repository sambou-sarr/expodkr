<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Réserver – {{ $event->titre }} · ExpoDKR</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --blue-night:    #0A1628;
            --blue-electric: #1E5FD8;
            --gold:          #C9A84C;
            --gold-light:    #E8C96A;
            --pearl:         #F7F8FC;
            --gray-soft:     #EEF0F6;
            --gray-mid:      #8892A4;
        }
        body { font-family: 'Inter', sans-serif; background: var(--pearl); color: var(--blue-night); -webkit-font-smoothing: antialiased; }
        .font-display { font-family: 'Instrument Serif', serif; }
        [x-cloak]     { display: none !important; }

        /* Steps */
        .step-active { background: var(--blue-electric); color: white; }
        .step-done   { background: #10B981; color: white; }
        .step-idle   { background: var(--gray-soft); color: var(--gray-mid); }

        /* Input focus image*/
        .inp {
            width: 100%;
            border: 1px solid var(--gray-soft);
            border-radius: .875rem;
            padding: .875rem 1rem;
            font-size: .875rem;
            font-family: inherit;
            color: var(--blue-night);
            background: white;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }
        .inp:focus { border-color: var(--blue-electric); box-shadow: 0 0 0 3px rgba(30,95,216,.12); }
        .inp::placeholder { color: var(--gray-mid); }
        .inp.err { border-color: #DC2626; background: #FEF2F2; }

        /* Mobile bottom bar */
        @media (max-width: 768px) {
            .mobile-bar { position: fixed; bottom: 0; left: 0; right: 0; z-index: 40; }
            .page-pad   { padding-bottom: 7rem; }
        }

        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: var(--blue-electric); border-radius: 99px; }
    </style>
</head>
<body>

{{--
|--------------------------------------------------------------------------
| ExpoDKR – Réservation · Étape 1 : Formulaire
| Variables : $event (avec ->categorie, ->exposant)
| Route POST : reservations.store
|--------------------------------------------------------------------------
--}}

<div x-data="{
        step: 1,
        form: {
            nom: '{{ Auth::user()->name ?? '' }}',
            email: '{{ Auth::user()->email ?? '' }}',
            telephone: '',
            nb_places: 1,
            paiement: 'sur_place',
            cgv: false
        },
        loading: false,
        errors: {},

        validate() {
            this.errors = {};
            if (!this.form.nom.trim())      this.errors.nom       = 'Le nom est requis.';
            if (!this.form.email.trim())    this.errors.email     = 'L\'email est requis.';
            if (!this.form.telephone.trim()) this.errors.telephone = 'Le téléphone est requis.';
            if (this.form.nb_places < 1)   this.errors.nb_places = 'Minimum 1 place.';
            if (!this.form.cgv)            this.errors.cgv       = 'Vous devez accepter les conditions.';
            return Object.keys(this.errors).length === 0;
        },

        nextStep() {
            if (this.step === 1) {
                this.errors = {};
                if (!this.form.nom.trim())   this.errors.nom   = 'Requis.';
                if (!this.form.email.trim()) this.errors.email = 'Requis.';
                if (Object.keys(this.errors).length === 0) this.step = 2;
                return;
            }
            if (this.step === 2) {
                this.errors = {};
                if (!this.form.telephone.trim()) this.errors.telephone = 'Requis.';
                if (Object.keys(this.errors).length === 0) this.step = 3;
            }
        },

        totalPrix() {
            const prix = {{ $event->categorie->prix ?? 0 }};
            return (prix * this.form.nb_places).toLocaleString('fr-FR');
        }
     }"
     class="min-h-screen page-pad">


    {{-- ══════════════════════════════════════════════
         NAVBAR MINIMALE
         ══════════════════════════════════════════════ --}}
    <header class="sticky top-0 z-50" style="background:var(--blue-night); box-shadow:0 2px 20px rgba(10,22,40,.2);">
        <div style="max-width:64rem; margin:0 auto; padding:0 1.5rem; display:flex; align-items:center; justify-content:space-between; height:4rem;">
            <a href="{{ route('home') }}" style="display:flex; align-items:center; gap:.6rem; text-decoration:none;">
                <span style="display:flex; align-items:center; justify-content:center; width:2rem; height:2rem; border-radius:.5rem; background:linear-gradient(135deg,var(--blue-electric),var(--blue-night));">
                    <svg style="width:1rem;height:1rem;color:white;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18"/>
                    </svg>
                </span>
                <span class="font-display" style="font-size:1.2rem; color:white;">Expo<span style="background:linear-gradient(135deg,var(--gold),var(--gold-light));-webkit-background-clip:text;-webkit-text-fill-color:transparent;">DKR</span></span>
            </a>
            <a href="{{ route('events.show', $event->id) }}"
               style="display:flex; align-items:center; gap:.5rem; font-size:.8rem; font-weight:500; color:rgba(255,255,255,.65); text-decoration:none; transition:color .2s;"
               onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,.65)'">
                <svg style="width:.9rem;height:.9rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                Retour à l'événement
            </a>
        </div>
    </header>


    {{-- ══════════════════════════════════════════════
         HERO ÉVÉNEMENT (compact) form
         ══════════════════════════════════════════════ --}}
    <div style="background:linear-gradient(135deg,var(--blue-night),var(--blue-electric)); padding:2rem 1.5rem;">
        <div style="max-width:64rem; margin:0 auto; display:flex; flex-wrap:wrap; align-items:center; gap:1.25rem;">

            {{-- Image miniature --}}
            <div style="width:4.5rem; height:4.5rem; border-radius:1rem; overflow:hidden; flex-shrink:0; border:2px solid rgba(255,255,255,.15);">
                @if($event->image)
                    <img src="{{ Storage::url($event->image) }}" alt="{{ $event->titre }}" style="width:100%;height:100%;object-fit:cover;">
                @else
                    <div style="width:100%;height:100%;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;">
                        <svg style="width:1.75rem;height:1.75rem;color:rgba(255,255,255,.5);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5"/>
                        </svg>
                    </div>
                @endif
            </div>

            <div style="flex:1; min-width:0;">
                @if($event->categorie)
                <span style="font-size:.7rem; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:var(--gold-light);">
                    {{ $event->categorie->nom }}
                </span>
                @endif
                <h1 class="font-display" style="font-size:1.35rem; color:white; line-height:1.25; margin:.2rem 0 .5rem; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                    {{ $event->titre }}
                </h1>
                <div style="display:flex; flex-wrap:wrap; gap:1rem; font-size:.78rem; color:rgba(255,255,255,.6);">
                    <span>📍 {{ $event->lieu }}</span>
                    <span>🗓 {{ \Carbon\Carbon::parse($event->date_debut)->translatedFormat('d M Y') }}</span>
                    @if($event->categorie && $event->categorie->prix)
                    <span style="color:var(--gold-light); font-weight:600;">{{ number_format($event->categorie->prix, 0, ',', ' ') }} FCFA / place</span>
                    @endif
                </div>
            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════
         ÉTAPES (stepper)
         ══════════════════════════════════════════════ --}}
    <div style="max-width:64rem; margin:0 auto; padding:1.5rem 1.5rem 0;">
        <div style="display:flex; align-items:center; gap:0; margin-bottom:2rem;">

            @foreach([['1','Vos infos'],['2','Contact'],['3','Paiement']] as $i => [$num, $label])
            <div style="display:flex; align-items:center; flex:1;">
                <div style="display:flex; flex-direction:column; align-items:center; gap:.35rem; flex:1;">
                    <div class="step-{{ ['idle','idle','idle'][$i] }}"
                         :class="{
                            'step-done':   step > {{ $num }},
                            'step-active': step == {{ $num }},
                            'step-idle':   step < {{ $num }}
                         }"
                         style="width:2.25rem; height:2.25rem; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.8rem; font-weight:700; transition:all .3s;">
                        <span x-show="step <= {{ $num }}">{{ $num }}</span>
                        <svg x-show="step > {{ $num }}" x-cloak style="width:1rem;height:1rem;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                        </svg>
                    </div>
                    <span style="font-size:.7rem; font-weight:500; color:var(--gray-mid);">{{ $label }}</span>
                </div>
                @if($i < 2)
                <div style="flex:1; height:2px; margin:0 .25rem; margin-bottom:1.25rem;"
                     :style="step > {{ $num }} ? 'background:var(--blue-electric)' : 'background:var(--gray-soft)'"
                     style="background:var(--gray-soft); transition:background .3s;">
                </div>
                @endif
            </div>
            @endforeach

        </div>
    </div>


    {{-- ══════════════════════════════════════════════
         FORMULAIRE
         ══════════════════════════════════════════════ --}}
    <div style="max-width:64rem; margin:0 auto; padding:0 1.5rem 2rem;">
        <div style="display:grid; grid-template-columns:1fr; gap:1.5rem;">

            <style>@media(min-width:768px){.res-grid{grid-template-columns:1fr 340px!important;}}</style>
            <div class="res-grid" style="display:grid; grid-template-columns:1fr; gap:1.5rem; align-items:start;">

                {{-- ────── FORMULAIRE PRINCIPAL ────── --}}
                <form method="POST" action="{{ route('reservations.store') }}" @submit.prevent="if(validate()) { loading=true; $el.submit(); }">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                    <input type="hidden" name="nb_places" :value="form.nb_places">
                    <input type="hidden" name="paiement"  :value="form.paiement">


                    {{-- ═══ ÉTAPE 1 : Identité ═══ --}}
                    <div x-show="step === 1"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-x-4"
                         x-transition:enter-end="opacity-100 translate-x-0">

                        <div style="background:white; border-radius:1.5rem; border:1px solid var(--gray-soft); box-shadow:0 2px 20px rgba(10,22,40,.06); padding:1.75rem; margin-bottom:1rem;">
                            <div style="display:flex; align-items:center; gap:.75rem; margin-bottom:1.5rem; padding-bottom:1rem; border-bottom:1px solid var(--gray-soft);">
                                <div style="width:2.25rem; height:2.25rem; border-radius:.75rem; background:#EFF6FF; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <svg style="width:1.1rem;height:1.1rem;color:var(--blue-electric);" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p style="font-size:.875rem; font-weight:700; color:var(--blue-night);">Vos informations</p>
                                    <p style="font-size:.75rem; color:var(--gray-mid);">Ces informations figureront sur votre billet</p>
                                </div>
                            </div>

                            <div style="display:flex; flex-direction:column; gap:1.1rem;">

                                {{-- Nom complet --}}
                                <div>
                                    <label style="display:block; font-size:.75rem; font-weight:600; color:var(--blue-night); margin-bottom:.5rem;">
                                        Nom complet <span style="color:#DC2626;">*</span>
                                    </label>
                                    <input type="text"
                                           name="nom"
                                           x-model="form.nom"
                                           placeholder="Ex : Aminata Diallo"
                                           :class="errors.nom ? 'inp err' : 'inp'">
                                    <p x-show="errors.nom" x-cloak style="font-size:.72rem; color:#DC2626; margin-top:.35rem;" x-text="errors.nom"></p>
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label style="display:block; font-size:.75rem; font-weight:600; color:var(--blue-night); margin-bottom:.5rem;">
                                        Adresse email <span style="color:#DC2626;">*</span>
                                    </label>
                                    <input type="email"
                                           name="email"
                                           x-model="form.email"
                                           placeholder="votre@email.com"
                                           :class="errors.email ? 'inp err' : 'inp'">
                                    <p style="font-size:.72rem; color:var(--gray-mid); margin-top:.35rem;">
                                        Le billet QR sera envoyé à cette adresse.
                                    </p>
                                    <p x-show="errors.email" x-cloak style="font-size:.72rem; color:#DC2626; margin-top:.25rem;" x-text="errors.email"></p>
                                </div>

                            </div>
                        </div>

                        <button type="button"
                                @click="nextStep()"
                                style="width:100%; padding:1rem; border-radius:1rem; font-size:.9rem; font-weight:700; color:white; border:none; cursor:pointer; background:linear-gradient(135deg,var(--blue-electric),#1248b0); box-shadow:0 4px 16px rgba(30,95,216,.3); transition:filter .2s;"
                                onmouseover="this.style.filter='brightness(1.1)'" onmouseout="this.style.filter='none'">
                            Continuer →
                        </button>
                    </div>


                    {{-- ═══ ÉTAPE 2 : Contact & Places ═══ --}}
                    <div x-show="step === 2"
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-x-4"
                         x-transition:enter-end="opacity-100 translate-x-0">

                        <div style="background:white; border-radius:1.5rem; border:1px solid var(--gray-soft); box-shadow:0 2px 20px rgba(10,22,40,.06); padding:1.75rem; margin-bottom:1rem;">
                            <div style="display:flex; align-items:center; gap:.75rem; margin-bottom:1.5rem; padding-bottom:1rem; border-bottom:1px solid var(--gray-soft);">
                                <div style="width:2.25rem; height:2.25rem; border-radius:.75rem; background:#ECFDF5; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <svg style="width:1.1rem;height:1.1rem;color:#059669;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 6z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p style="font-size:.875rem; font-weight:700; color:var(--blue-night);">Contact & Places</p>
                                    <p style="font-size:.75rem; color:var(--gray-mid);">Coordonnées et nombre de participants</p>
                                </div>
                            </div>

                            <div style="display:flex; flex-direction:column; gap:1.1rem;">

                                {{-- Téléphone --}}
                                <div>
                                    <label style="display:block; font-size:.75rem; font-weight:600; color:var(--blue-night); margin-bottom:.5rem;">
                                        Téléphone <span style="color:#DC2626;">*</span>
                                    </label>
                                    <input type="tel"
                                           name="telephone"
                                           x-model="form.telephone"
                                           placeholder="+221 77 000 00 00"
                                           :class="errors.telephone ? 'inp err' : 'inp'">
                                    <p x-show="errors.telephone" x-cloak style="font-size:.72rem; color:#DC2626; margin-top:.35rem;" x-text="errors.telephone"></p>
                                </div>

                                {{-- Nombre de places --}}
                                <div>
                                    <label style="display:block; font-size:.75rem; font-weight:600; color:var(--blue-night); margin-bottom:.5rem;">
                                        Nombre de places <span style="color:#DC2626;">*</span>
                                    </label>
                                    <div style="display:flex; align-items:center; gap:1rem;">
                                        <button type="button"
                                                @click="if(form.nb_places > 1) form.nb_places--"
                                                style="width:2.75rem; height:2.75rem; border-radius:.75rem; border:1px solid var(--gray-soft); background:white; font-size:1.25rem; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; color:var(--blue-night); transition:all .2s;"
                                                onmouseover="this.style.borderColor='var(--blue-electric)'; this.style.color='var(--blue-electric)'"
                                                onmouseout="this.style.borderColor='var(--gray-soft)'; this.style.color='var(--blue-night)'">−</button>
                                        <div style="flex:1; text-align:center; font-size:1.5rem; font-weight:700; color:var(--blue-night);" x-text="form.nb_places"></div>
                                        <button type="button"
                                                @click="if(form.nb_places < 10) form.nb_places++"
                                                style="width:2.75rem; height:2.75rem; border-radius:.75rem; border:1px solid var(--gray-soft); background:white; font-size:1.25rem; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; color:var(--blue-night); transition:all .2s;"
                                                onmouseover="this.style.borderColor='var(--blue-electric)'; this.style.color='var(--blue-electric)'"
                                                onmouseout="this.style.borderColor='var(--gray-soft)'; this.style.color='var(--blue-night)'">+</button>
                                    </div>
                                    <p style="font-size:.72rem; color:var(--gray-mid); margin-top:.5rem; text-align:center;">Maximum 10 places par réservation</p>
                                </div>

                            </div>
                        </div>

                        <div style="display:flex; gap:.75rem;">
                            <button type="button" @click="step = 1"
                                    style="flex:1; padding:1rem; border-radius:1rem; font-size:.875rem; font-weight:600; color:var(--blue-night); border:1px solid var(--gray-soft); background:white; cursor:pointer; transition:background .2s;"
                                    onmouseover="this.style.background='var(--pearl)'" onmouseout="this.style.background='white'">
                                ← Retour
                            </button>
                            <button type="button" @click="nextStep()"
                                    style="flex:2; padding:1rem; border-radius:1rem; font-size:.9rem; font-weight:700; color:white; border:none; cursor:pointer; background:linear-gradient(135deg,var(--blue-electric),#1248b0); transition:filter .2s;"
                                    onmouseover="this.style.filter='brightness(1.1)'" onmouseout="this.style.filter='none'">
                                Continuer →
                            </button>
                        </div>
                    </div>


                    {{-- ═══ ÉTAPE 3 : Paiement & CGV ═══ --}}
                    <div x-show="step === 3"
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-x-4"
                         x-transition:enter-end="opacity-100 translate-x-0">

                        <div style="background:white; border-radius:1.5rem; border:1px solid var(--gray-soft); box-shadow:0 2px 20px rgba(10,22,40,.06); padding:1.75rem; margin-bottom:1rem;">
                            <div style="display:flex; align-items:center; gap:.75rem; margin-bottom:1.5rem; padding-bottom:1rem; border-bottom:1px solid var(--gray-soft);">
                                <div style="width:2.25rem; height:2.25rem; border-radius:.75rem; background:#FFFBEB; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <svg style="width:1.1rem;height:1.1rem;color:#D97706;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p style="font-size:.875rem; font-weight:700; color:var(--blue-night);">Mode de paiement</p>
                                    <p style="font-size:.75rem; color:var(--gray-mid);">Choisissez votre méthode préférée</p>
                                </div>
                            </div>

                            {{-- Options paiement --}}
                            <div style="display:flex; flex-direction:column; gap:.75rem; margin-bottom:1.5rem;">

                                @foreach([
                                    ['sur_place',  '🏢', 'Paiement sur place',    'Payez à l\'entrée le jour de l\'événement'],
                                    ['wave',       '📱', 'Wave',                  'Paiement mobile rapide via Wave'],
                                    ['orange',     '🟠', 'Orange Money',          'Paiement via Orange Money'],
                                ] as [$val, $emoji, $label, $desc])
                                <label style="display:flex; align-items:center; gap:1rem; padding:1rem 1.25rem; border-radius:1rem; cursor:pointer; border:2px solid; transition:all .2s;"
                                       :style="form.paiement === '{{ $val }}' ? 'border-color:var(--blue-electric); background:#EFF6FF;' : 'border-color:var(--gray-soft); background:white;'">
                                    <input type="radio" name="paiement" value="{{ $val }}" x-model="form.paiement" class="sr-only">
                                    <span style="font-size:1.35rem; flex-shrink:0;">{{ $emoji }}</span>
                                    <div style="flex:1;">
                                        <p style="font-size:.875rem; font-weight:600; color:var(--blue-night);">{{ $label }}</p>
                                        <p style="font-size:.72rem; color:var(--gray-mid);">{{ $desc }}</p>
                                    </div>
                                    <div style="width:1.1rem; height:1.1rem; border-radius:50%; border:2px solid; flex-shrink:0; display:flex; align-items:center; justify-content:center; transition:all .2s;"
                                         :style="form.paiement === '{{ $val }}' ? 'border-color:var(--blue-electric);' : 'border-color:var(--gray-mid);'">
                                        <div style="width:.45rem; height:.45rem; border-radius:50%; transition:all .2s;"
                                             :style="form.paiement === '{{ $val }}' ? 'background:var(--blue-electric);' : 'background:transparent;'"></div>
                                    </div>
                                </label>
                                @endforeach

                            </div>

                            {{-- CGV --}}
                            <label style="display:flex; align-items:flex-start; gap:.75rem; cursor:pointer; padding:1rem; border-radius:1rem; background:var(--pearl); border:1px solid var(--gray-soft);">
                                <div style="position:relative; flex-shrink:0; margin-top:.1rem;">
                                    <input type="checkbox" x-model="form.cgv" class="sr-only">
                                    <div style="width:1.25rem; height:1.25rem; border-radius:.375rem; border:2px solid; display:flex; align-items:center; justify-content:center; transition:all .2s;"
                                         :style="form.cgv ? 'border-color:var(--blue-electric); background:var(--blue-electric);' : 'border-color:var(--gray-mid); background:white;'"
                                         @click="form.cgv = !form.cgv">
                                        <svg x-show="form.cgv" x-cloak style="width:.7rem;height:.7rem;color:white;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                        </svg>
                                    </div>
                                </div>
                                <p style="font-size:.78rem; color:var(--gray-mid); line-height:1.6;">
                                    J'accepte les
                                    <a href="#" style="color:var(--blue-electric); text-decoration:underline;">conditions générales d'utilisation</a>
                                    et la
                                    <a href="#" style="color:var(--blue-electric); text-decoration:underline;">politique de confidentialité</a>
                                    d'ExpoDKR.
                                </p>
                            </label>
                            <p x-show="errors.cgv" x-cloak style="font-size:.72rem; color:#DC2626; margin-top:.5rem;" x-text="errors.cgv"></p>
                        </div>

                        <div style="display:flex; gap:.75rem;">
                            <button type="button" @click="step = 2"
                                    style="flex:1; padding:1rem; border-radius:1rem; font-size:.875rem; font-weight:600; color:var(--blue-night); border:1px solid var(--gray-soft); background:white; cursor:pointer; transition:background .2s;"
                                    onmouseover="this.style.background='var(--pearl)'" onmouseout="this.style.background='white'">
                                ← Retour
                            </button>
                            <button type="submit"
                                    :disabled="loading"
                                    style="flex:2; padding:1rem; border-radius:1rem; font-size:.9rem; font-weight:700; color:var(--blue-night); border:none; cursor:pointer; background:linear-gradient(135deg,var(--gold),var(--gold-light)); box-shadow:0 4px 16px rgba(201,168,76,.35); transition:filter .2s, opacity .2s;"
                                    :style="loading ? 'opacity:.6; cursor:not-allowed;' : ''"
                                    onmouseover="this.style.filter='brightness(1.08)'" onmouseout="this.style.filter='none'">
                                <span x-show="!loading">✅ Confirmer ma réservation</span>
                                <span x-show="loading" x-cloak>⏳ Traitement…</span>
                            </button>
                        </div>
                    </div>

                </form>


                {{-- ────── RÉCAP SIDEBAR ────── --}}
                <div>
                    <div style="background:white; border-radius:1.5rem; border:1px solid var(--gray-soft); box-shadow:0 2px 20px rgba(10,22,40,.06); overflow:hidden; position:sticky; top:5.5rem;">

                        {{-- Header récap --}}
                        <div style="padding:1.25rem 1.5rem; background:linear-gradient(135deg,var(--blue-night),var(--blue-deep)); color:white;">
                            <p style="font-size:.7rem; font-weight:600; letter-spacing:.15em; text-transform:uppercase; color:rgba(255,255,255,.5); margin-bottom:.35rem;">Récapitulatif</p>
                            <p class="font-display" style="font-size:1.1rem; line-height:1.3; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                                {{ $event->titre }}
                            </p>
                        </div>

                        <div style="padding:1.25rem 1.5rem;">

                            {{-- Détails --}}
                            <div style="display:flex; flex-direction:column; gap:.875rem; margin-bottom:1.25rem;">

                                <div style="display:flex; align-items:center; gap:.75rem;">
                                    <div style="width:2rem; height:2rem; border-radius:.625rem; background:var(--pearl); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                        <svg style="width:.9rem;height:.9rem;color:var(--gray-mid);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p style="font-size:.7rem; color:var(--gray-mid);">Date</p>
                                        <p style="font-size:.8rem; font-weight:600; color:var(--blue-night);">{{ \Carbon\Carbon::parse($event->date_debut)->translatedFormat('d M Y') }}</p>
                                    </div>
                                </div>

                                <div style="display:flex; align-items:center; gap:.75rem;">
                                    <div style="width:2rem; height:2rem; border-radius:.625rem; background:var(--pearl); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                        <svg style="width:.9rem;height:.9rem;color:var(--gray-mid);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p style="font-size:.7rem; color:var(--gray-mid);">Lieu</p>
                                        <p style="font-size:.8rem; font-weight:600; color:var(--blue-night);">{{ $event->lieu }}</p>
                                    </div>
                                </div>

                            </div>

                            {{-- Prix --}}
                            <div style="padding:1rem; border-radius:1rem; background:var(--pearl); border:1px solid var(--gray-soft);">
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:.5rem;">
                                    <span style="font-size:.78rem; color:var(--gray-mid);">Prix unitaire</span>
                                    <span style="font-size:.8rem; font-weight:600; color:var(--blue-night);">
                                        {{ isset($event->categorie->prix) ? number_format($event->categorie->prix, 0, ',', ' ') . ' FCFA' : 'Gratuit' }}
                                    </span>
                                </div>
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:.75rem;">
                                    <span style="font-size:.78rem; color:var(--gray-mid);">Nombre de places</span>
                                    <span style="font-size:.8rem; font-weight:600; color:var(--blue-night);" x-text="form.nb_places + ' place(s)'">1 place(s)</span>
                                </div>
                                <div style="border-top:1px solid var(--gray-soft); padding-top:.75rem; display:flex; justify-content:space-between; align-items:center;">
                                    <span style="font-size:.8rem; font-weight:700; color:var(--blue-night);">Total</span>
                                    <span style="font-size:1.1rem; font-weight:800; color:var(--blue-electric);" x-text="totalPrix() + ' FCFA'">0 FCFA</span>
                                </div>
                            </div>

                            <div style="display:flex; align-items:center; gap:.5rem; margin-top:1rem; padding:.625rem; border-radius:.75rem; background:#ECFDF5;">
                                <svg style="width:.9rem;height:.9rem;color:#059669;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                                </svg>
                                <p style="font-size:.7rem; color:#059669; font-weight:500;">Paiement sécurisé · Billet QR par email</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════
         BOTTOM BAR MOBILE
         ══════════════════════════════════════════════ --}}
    <div class="mobile-bar md:hidden" style="background:white; border-top:1px solid var(--gray-soft); padding:1rem 1.5rem; display:flex; gap:.75rem; align-items:center;">
        <div style="flex:1;">
            <p style="font-size:.7rem; color:var(--gray-mid);">Total</p>
            <p style="font-size:1.1rem; font-weight:800; color:var(--blue-electric);" x-text="totalPrix() + ' FCFA'"></p>
        </div>
        <button type="button"
                @click="step < 3 ? nextStep() : null"
                x-show="step < 3"
                style="flex:1; padding:.875rem; border-radius:1rem; font-size:.875rem; font-weight:700; color:white; border:none; cursor:pointer; background:linear-gradient(135deg,var(--blue-electric),#1248b0);">
            Continuer →
        </button>
        <button type="submit"
                form="main-form"
                x-show="step === 3"
                x-cloak
                style="flex:1; padding:.875rem; border-radius:1rem; font-size:.875rem; font-weight:700; color:var(--blue-night); border:none; cursor:pointer; background:linear-gradient(135deg,var(--gold),var(--gold-light));">
            ✅ Confirmer
        </button>
    </div>

</div>

</body>
</html>
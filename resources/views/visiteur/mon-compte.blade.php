<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon compte · ExpoDKR</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>

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
        * { box-sizing: border-box; }
        body { font-family:'Inter',sans-serif; background:var(--pearl); color:var(--blue-night); -webkit-font-smoothing:antialiased; margin:0; }
        .font-display  { font-family:'Instrument Serif',serif; }
        .font-mono-jet { font-family:'JetBrains Mono',monospace; }
        [x-cloak]      { display:none!important; }

        /* Nav tabs */
        .tab-btn { padding:.625rem 1.25rem; border-radius:.875rem; font-size:.82rem; font-weight:600; border:none; cursor:pointer; transition:all .2s; font-family:'Inter',sans-serif; }
        .tab-active { background:var(--blue-electric); color:white; box-shadow:0 4px 12px rgba(30,95,216,.25); }
        .tab-idle   { background:white; color:var(--gray-mid); border:1.5px solid var(--gray-soft); }
        .tab-idle:hover { border-color:var(--blue-electric); color:var(--blue-electric); }

        /* Card */
        .card { background:white; border-radius:1.5rem; border:1px solid var(--gray-soft); box-shadow:0 2px 16px rgba(10,22,40,.05); }

        /* Ticket perf */
        .ticket-perf { position:relative; }
        .ticket-perf::before, .ticket-perf::after {
            content:''; position:absolute; width:1.25rem; height:1.25rem;
            border-radius:50%; background:var(--pearl); top:50%; transform:translateY(-50%); z-index:2;
        }
        .ticket-perf::before { left:-.625rem; }
        .ticket-perf::after  { right:-.625rem; }

        /* Status badges */
        .badge-green  { background:#ECFDF5; color:#059669; }
        .badge-amber  { background:#FFFBEB; color:#D97706; }
        .badge-red    { background:#FEF2F2; color:#DC2626; }
        .badge-gray   { background:#F1F5F9; color:#64748B; }
        .badge-blue   { background:#EFF6FF; color:#2563EB; }

        /* Bottom nav mobile */
        .bottom-nav { display:none; }
        @media(max-width:768px) {
            .bottom-nav { display:flex; position:fixed; bottom:0; left:0; right:0; z-index:50; background:white; border-top:1px solid var(--gray-soft); padding:.75rem 1.5rem; gap:.5rem; }
            .page-pb    { padding-bottom:6rem; }
        }

        /* Animations */
        @keyframes fade-in { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:none} }
        .fade-in { animation:fade-in .35s ease forwards; }

        ::-webkit-scrollbar { width:4px; }
        ::-webkit-scrollbar-thumb { background:var(--blue-electric); border-radius:99px; }
    </style>
</head>
<body>

{{--
|--------------------------------------------------------------------------
| ExpoDKR – Mon compte visiteur
| Variables attendues :
|   Auth::user() → name, email, created_at
|   $reservations → collection Eloquent avec ->evenement, ->evenement->image,
|                   ->evenement->categorie, ->evenement->lieu, etc.
|   $stats = ['total' => N, 'aVenir' => N, 'termines' => N, 'annules' => N]
|--------------------------------------------------------------------------
--}}

<div x-data="{
        activeTab:   'reservations',
        modalBillet: null,
        toastMsg:    '',
        toastShow:   false,

        showToast(msg) {
            this.toastMsg  = msg;
            this.toastShow = true;
            setTimeout(() => this.toastShow = false, 3000);
        },

        openBillet(res) {
            this.modalBillet = res;
            this.$nextTick(() => {
                const canvas = document.getElementById('modal-qr');
                if(canvas && typeof QRCode !== 'undefined') {
                    QRCode.toCanvas(canvas, res.qrUrl, {
                        width:160, margin:1,
                        color:{ dark:'#0A1628', light:'#FFFFFF' },
                        errorCorrectionLevel:'H'
                    });
                }
            });
        }
    }"
    class="page-pb">


    {{-- ══════════════════════════════════════════════
         NAVBAR
         ══════════════════════════════════════════════ --}}
    <header style="background:var(--blue-night); position:sticky; top:0; z-index:40; box-shadow:0 2px 20px rgba(10,22,40,.2);">
        <div style="max-width:72rem; margin:0 auto; padding:0 1.5rem; display:flex; align-items:center; justify-content:space-between; height:4rem;">

            <a href="{{ route('home') }}" style="display:flex; align-items:center; gap:.625rem; text-decoration:none;">
                <div style="width:2rem; height:2rem; border-radius:.625rem; background:linear-gradient(135deg,var(--blue-electric),#1248b0); display:flex; align-items:center; justify-content:center;">
                    <svg style="width:.9rem;height:.9rem;color:white;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18"/></svg>
                </div>
                <span class="font-display" style="font-size:1.15rem; color:white;">Expo<span style="background:linear-gradient(135deg,var(--gold),var(--gold-light));-webkit-background-clip:text;-webkit-text-fill-color:transparent;">DKR</span></span>
            </a>

            <nav style="display:flex; align-items:center; gap:1.5rem;">
                <a href="{{ route('user.events.index') }}" style="font-size:.82rem; font-weight:500; color:rgba(255,255,255,.65); text-decoration:none; transition:color .2s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,.65)'">Événements</a>

                {{-- Avatar dropdown --}}
                <div style="position:relative;" x-data="{ open:false }" @click.outside="open=false">
                    <button @click="open=!open"
                            style="display:flex; align-items:center; gap:.625rem; padding:.375rem .75rem .375rem .375rem; border-radius:2rem; background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.15); cursor:pointer; transition:background .2s;"
                            onmouseover="this.style.background='rgba(255,255,255,.15)'" onmouseout="this.style.background='rgba(255,255,255,.1)'">
                        <div style="width:2rem; height:2rem; border-radius:50%; background:linear-gradient(135deg,var(--blue-electric),var(--gold)); display:flex; align-items:center; justify-content:center; font-size:.8rem; font-weight:700; color:white; flex-shrink:0;">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <span style="font-size:.8rem; font-weight:600; color:white; max-width:8rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                            {{ Auth::user()->name ?? 'Utilisateur' }}
                        </span>
                        <svg style="width:.875rem;height:.875rem;color:rgba(255,255,255,.5); transition:transform .2s;" :style="open ? 'transform:rotate(180deg)' : ''" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                    </button>

                    <div x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         style="position:absolute; right:0; top:calc(100% + .5rem); width:13rem; background:white; border-radius:1.25rem; border:1px solid var(--gray-soft); box-shadow:0 8px 32px rgba(10,22,40,.12); overflow:hidden; padding:.5rem;">

                        <a href="#" @click="activeTab='profil'; open=false"
                           style="display:flex; align-items:center; gap:.625rem; padding:.75rem 1rem; border-radius:.875rem; font-size:.82rem; font-weight:500; color:var(--blue-night); text-decoration:none; transition:background .2s;"
                           onmouseover="this.style.background='var(--pearl)'" onmouseout="this.style.background='transparent'">
                            <svg style="width:.9rem;height:.9rem;color:var(--gray-mid);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                            Mon profil
                        </a>
                        <a href="#" @click="activeTab='reservations'; open=false"
                           style="display:flex; align-items:center; gap:.625rem; padding:.75rem 1rem; border-radius:.875rem; font-size:.82rem; font-weight:500; color:var(--blue-night); text-decoration:none; transition:background .2s;"
                           onmouseover="this.style.background='var(--pearl)'" onmouseout="this.style.background='transparent'">
                            <svg style="width:.9rem;height:.9rem;color:var(--gray-mid);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026"/></svg>
                            Mes billets
                        </a>
                        <hr style="border:none; border-top:1px solid var(--gray-soft); margin:.25rem 0;">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    style="display:flex; align-items:center; gap:.625rem; width:100%; padding:.75rem 1rem; border-radius:.875rem; font-size:.82rem; font-weight:500; color:#DC2626; background:none; border:none; cursor:pointer; text-align:left; font-family:'Inter',sans-serif; transition:background .2s;"
                                    onmouseover="this.style.background='#FEF2F2'" onmouseout="this.style.background='transparent'">
                                <svg style="width:.9rem;height:.9rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
                                Se déconnecter
                            </button>
                        </form>
                    </div>
                </div>
            </nav>
        </div>
    </header>


    {{-- ══════════════════════════════════════════════
         HERO PROFIL
         ══════════════════════════════════════════════ --}}
    <div style="background:linear-gradient(135deg,var(--blue-night),var(--blue-deep)); padding:2.5rem 1.5rem 0;">
        <div style="max-width:72rem; margin:0 auto;">

            <div style="display:flex; flex-wrap:wrap; align-items:flex-end; gap:1.5rem; padding-bottom:2rem;">

                {{-- Avatar --}}
                <div style="width:5rem; height:5rem; border-radius:1.5rem; background:linear-gradient(135deg,var(--blue-electric),var(--gold)); display:flex; align-items:center; justify-content:center; font-size:2rem; font-weight:800; color:white; flex-shrink:0; border:3px solid rgba(255,255,255,.15); box-shadow:0 8px 24px rgba(30,95,216,.3);">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>

                <div style="flex:1; min-width:0;">
                    <p style="font-size:.7rem; font-weight:600; letter-spacing:.15em; text-transform:uppercase; color:var(--gold-light); margin-bottom:.35rem;">Espace membre</p>
                    <h1 class="font-display" style="font-size:1.75rem; color:white; line-height:1.2; margin-bottom:.35rem;">
                        {{ Auth::user()->name ?? 'Visiteur' }}
                    </h1>
                    <p style="font-size:.82rem; color:rgba(255,255,255,.5);">
                        {{ Auth::user()->email ?? '' }}
                        <span style="margin:0 .5rem;">·</span>
                        Membre depuis {{ \Carbon\Carbon::parse(Auth::user()->created_at)->translatedFormat('M Y') }}
                    </p>
                </div>

                {{-- CTA explorer --}}
                <a href="{{ route('user.events.index') }}"
                   style="display:flex; align-items:center; gap:.625rem; padding:.75rem 1.5rem; border-radius:1rem; font-size:.82rem; font-weight:700; color:var(--blue-night); text-decoration:none; background:linear-gradient(135deg,var(--gold),var(--gold-light)); box-shadow:0 4px 16px rgba(201,168,76,.3); transition:filter .2s; white-space:nowrap;"
                   onmouseover="this.style.filter='brightness(1.08)'" onmouseout="this.style.filter='none'">
                    <svg style="width:.9rem;height:.9rem;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607z"/></svg>
                    Explorer les événements
                </a>
            </div>

            {{-- Tabs --}}
            <div style="display:flex; gap:.5rem; overflow-x:auto; padding-bottom:0; -webkit-overflow-scrolling:touch;">
                @foreach([
                    ['reservations', '🎫', 'Mes réservations'],
                    ['billets',      '📱', 'Mes billets'],
                    ['profil',       '👤', 'Mon profil'],
                    ['securite',     '🔒', 'Sécurité'],
                ] as [$tab, $icon, $label])
                <button @click="activeTab = '{{ $tab }}'"
                        style="display:flex; align-items:center; gap:.5rem; padding:.75rem 1.25rem; border-radius:1rem 1rem 0 0; font-size:.82rem; font-weight:600; border:none; cursor:pointer; white-space:nowrap; font-family:'Inter',sans-serif; transition:all .2s;"
                        :style="activeTab === '{{ $tab }}'
                            ? 'background:var(--pearl); color:var(--blue-electric);'
                            : 'background:rgba(255,255,255,.08); color:rgba(255,255,255,.6);'"
                        onmouseover="if(this.getAttribute('aria-selected') !== 'true') this.style.background='rgba(255,255,255,.14)'"
                        onmouseout="if(this.getAttribute('aria-selected') !== 'true') this.style.background='rgba(255,255,255,.08)'"
                        :aria-selected="activeTab === '{{ $tab }}'">
                    <span>{{ $icon }}</span>
                    {{ $label }}
                </button>
                @endforeach
            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════
         CONTENU PRINCIPAL
         ══════════════════════════════════════════════ --}}
    <div style="max-width:72rem; margin:0 auto; padding:2rem 1.5rem;">


        {{-- ══════ ONGLET : MES RÉSERVATIONS ══════ --}}
        <div x-show="activeTab === 'reservations'" class="fade-in">

            {{-- Stats --}}
            <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:1rem; margin-bottom:2rem;">
                <style>@media(min-width:640px){.stats-grid{grid-template-columns:repeat(4,1fr)!important;}}</style>
                <div class="stats-grid" style="display:grid; grid-template-columns:repeat(2,1fr); gap:1rem; grid-column:1/-1;">
                    @php
                        $stats = $stats ?? [
                            'total'    => $reservations->count()                                      ?? 0,
                            'aVenir'   => $reservations->filter(fn($r) => \Carbon\Carbon::parse($r->evenement?->date_debut ?? now()->addDay())->isFuture())->count() ?? 0,
                            'termines' => $reservations->filter(fn($r) => \Carbon\Carbon::parse($r->evenement?->date_fin   ?? now()->subDay())->isPast())->count()   ?? 0,
                            'annules'  => $reservations->where('statut', 'annule')->count()           ?? 0,
                        ];
                    @endphp
                    @foreach([
                        [$stats['total'],    'Total',        '#2563EB', '#EFF6FF', 'M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026'],
                        [$stats['aVenir'],   'À venir',      '#059669', '#ECFDF5', 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25'],
                        [$stats['termines'], 'Terminés',     '#D97706', '#FFFBEB', 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
                        [$stats['annules'],  'Annulés',      '#DC2626', '#FEF2F2', 'M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636'],
                    ] as [$val, $lbl, $clr, $bg, $icon])
                    <div class="card" style="padding:1.25rem;">
                        <div style="width:2.25rem; height:2.25rem; border-radius:.75rem; background:{{ $bg }}; display:flex; align-items:center; justify-content:center; margin-bottom:.875rem;">
                            <svg style="width:1rem;height:1rem;" fill="none" stroke="{{ $clr }}" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                        </div>
                        <p style="font-size:1.75rem; font-weight:800; color:var(--blue-night);">{{ $val }}</p>
                        <p style="font-size:.75rem; color:var(--gray-mid); margin-top:.15rem; font-weight:500;">{{ $lbl }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Liste réservations --}}
            @forelse($reservations ?? [] as $res)
            @php
                $ev      = $res->evenement ?? null;
                $debut   = \Carbon\Carbon::parse($ev?->date_debut ?? now());
                $fin     = \Carbon\Carbon::parse($ev?->date_fin   ?? now());
                $now     = now();
                $statut  = $res->statut ?? 'confirmee';

                if ($statut === 'annule')           { $sl='Annulé';    $sc='#DC2626'; $sb='#FEF2F2'; }
                elseif ($now->lt($debut))           { $sl='À venir';   $sc='#059669'; $sb='#ECFDF5'; }
                elseif ($now->between($debut,$fin)) { $sl='En cours';  $sc='#D97706'; $sb='#FFFBEB'; }
                else                                { $sl='Terminé';   $sc='#64748B'; $sb='#F1F5F9'; }

                $code  = $res->code ?? 'EXP-' . str_pad($res->id ?? 0, 6, '0', STR_PAD_LEFT);
                $total = (optional($ev?->categorie)->prix ?? 0) * ($res->nb_places ?? 1);
            @endphp

            <div class="card" style="margin-bottom:1rem; overflow:hidden; transition:box-shadow .2s;"
                 onmouseover="this.style.boxShadow='0 8px 32px rgba(10,22,40,.1)'"
                 onmouseout="this.style.boxShadow='0 2px 16px rgba(10,22,40,.05)'">

                {{-- Header card --}}
                <div style="display:flex; flex-wrap:wrap; align-items:center; gap:1rem; padding:1.25rem 1.5rem; border-bottom:1px solid var(--gray-soft);">

                    {{-- Image événement --}}
                    <div style="width:3.5rem; height:3.5rem; border-radius:1rem; overflow:hidden; flex-shrink:0; background:linear-gradient(135deg,var(--blue-night),var(--blue-electric));">
                        @if($ev?->image)
                            <img src="{{ $ev->image }}" alt="{{ $ev->titre }}" style="width:100%;height:100%;object-fit:cover;">
                        @endif
                    </div>

                    <div style="flex:1; min-width:0;">
                        <div style="display:flex; align-items:center; gap:.75rem; flex-wrap:wrap; margin-bottom:.2rem;">
                            <span style="font-size:.68rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; padding:.25rem .625rem; border-radius:2rem; background:{{ $sb }}; color:{{ $sc }};">
                                {{ $sl }}
                            </span>
                            @if($ev?->categorie)
                            <span style="font-size:.68rem; font-weight:600; color:var(--gray-mid);">{{ $ev->categorie->nom }}</span>
                            @endif
                        </div>
                        <h3 style="font-size:.9rem; font-weight:700; color:var(--blue-night); overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:22rem;">
                            {{ $ev?->titre ?? 'Événement' }}
                        </h3>
                        <div style="display:flex; flex-wrap:wrap; gap:.875rem; font-size:.75rem; color:var(--gray-mid); margin-top:.2rem;">
                            <span>📍 {{ $ev?->lieu ?? '—' }}</span>
                            <span>🗓 {{ $debut->translatedFormat('d M Y') }}</span>
                            <span>🎫 {{ $res->nb_places ?? 1 }} place{{ ($res->nb_places ?? 1) > 1 ? 's' : '' }}</span>
                        </div>
                    </div>

                    {{-- Prix --}}
                    <div style="text-align:right; flex-shrink:0;">
                        <p style="font-size:1.1rem; font-weight:800; color:var(--blue-electric);">
                            {{ $total > 0 ? number_format($total, 0, ',', ' ') . ' FCFA' : 'Gratuit' }}
                        </p>
                        <p class="font-mono-jet" style="font-size:.68rem; color:var(--gray-mid); margin-top:.15rem;">{{ $code }}</p>
                    </div>
                </div>

                {{-- Footer card --}}
                <div style="display:flex; flex-wrap:wrap; items-center:center; justify-content:space-between; gap:.75rem; padding:.875rem 1.5rem; background:var(--pearl);">
                    <p style="font-size:.72rem; color:var(--gray-mid);">
                        Réservé le {{ \Carbon\Carbon::parse($res->created_at)->translatedFormat('d M Y à H:i') }}
                        · Paiement : {{ ['sur_place'=>'Sur place','wave'=>'Wave','orange'=>'Orange Money'][$res->paiement ?? 'sur_place'] ?? 'Sur place' }}
                    </p>

                    <div style="display:flex; gap:.625rem;">
                        {{-- Voir billet --}}
                        <button @click="openBillet({
                                    code:   '{{ $code }}',
                                    titre:  '{{ addslashes($ev?->titre ?? '') }}',
                                    lieu:   '{{ addslashes($ev?->lieu ?? '') }}',
                                    date:   '{{ $debut->translatedFormat('d M Y') }}',
                                    heure:  '{{ $debut->format('H:i') }}',
                                    places: '{{ $res->nb_places ?? 1 }}',
                                    total:  '{{ $total > 0 ? number_format($total,0,',','') . ' FCFA' : 'Gratuit' }}',
                                    qrUrl:  '{{ url('/reservations/verify/' . $code) }}',
                                    statut: '{{ $sl }}'
                                })"
                                style="display:flex; align-items:center; gap:.5rem; padding:.5rem 1rem; border-radius:.875rem; font-size:.78rem; font-weight:600; color:var(--blue-electric); background:white; border:1.5px solid var(--blue-electric); cursor:pointer; font-family:'Inter',sans-serif; transition:all .2s;"
                                onmouseover="this.style.background='#EFF6FF'" onmouseout="this.style.background='white'">
                            <svg style="width:.8rem;height:.8rem;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75ZM6.75 16.5h.75v.75h-.75v-.75ZM16.5 6.75h.75v.75h-.75v-.75ZM13.5 13.5h.75v.75h-.75v-.75ZM13.5 19.5h.75v.75h-.75v-.75ZM19.5 13.5h.75v.75h-.75v-.75ZM19.5 19.5h.75v.75h-.75v-.75ZM16.5 16.5h.75v.75h-.75v-.75Z"/></svg>
                            Voir le billet
                        </button>

                        {{-- Voir événement --}}
                        @if($ev)
                        <a href="{{ route('user.events.show', $ev->id) }}"
                           style="display:flex; align-items:center; gap:.5rem; padding:.5rem 1rem; border-radius:.875rem; font-size:.78rem; font-weight:600; color:var(--gray-mid); background:white; border:1.5px solid var(--gray-soft); text-decoration:none; transition:all .2s;"
                           onmouseover="this.style.borderColor='var(--gray-mid)'; this.style.color='var(--blue-night)'" onmouseout="this.style.borderColor='var(--gray-soft)'; this.style.color='var(--gray-mid)'">
                            <svg style="width:.8rem;height:.8rem;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                            Événement
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            @empty
            <div class="card" style="padding:4rem 2rem; text-align:center;">
                <div style="width:5rem; height:5rem; border-radius:1.75rem; background:var(--pearl); display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
                    <svg style="width:2.25rem;height:2.25rem;color:var(--gray-soft);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026"/></svg>
                </div>
                <p style="font-size:1rem; font-weight:700; color:var(--blue-night); margin-bottom:.5rem;">Aucune réservation pour l'instant</p>
                <p style="font-size:.85rem; color:var(--gray-mid); margin-bottom:1.75rem;">Découvrez les événements disponibles et réservez votre place.</p>
                <a href="{{ route('user.events.index') }}"
                   style="display:inline-flex; align-items:center; gap:.625rem; padding:.875rem 1.75rem; border-radius:1rem; font-size:.875rem; font-weight:700; color:white; text-decoration:none; background:linear-gradient(135deg,var(--blue-electric),#1248b0); box-shadow:0 4px 16px rgba(30,95,216,.25);">
                    🗓 Explorer les événements
                </a>
            </div>
            @endforelse
        </div>


        {{-- ══════ ONGLET : MES BILLETS (QR mobiles) ══════ --}}
        <div x-show="activeTab === 'billets'" x-cloak class="fade-in">

            <div style="display:grid; gap:1.25rem;">
                <style>@media(min-width:640px){.billets-grid{grid-template-columns:repeat(2,1fr)!important;}}</style>
                <div class="billets-grid" style="display:grid; grid-template-columns:1fr; gap:1.25rem;">

                    @forelse($reservations ?? [] as $res)
                    @php
                        $ev    = $res->evenement ?? null;
                        $debut = \Carbon\Carbon::parse($ev?->date_debut ?? now());
                        $code  = $res->code ?? 'EXP-' . str_pad($res->id ?? 0, 6, '0', STR_PAD_LEFT);
                    @endphp

                    <div class="card" style="overflow:hidden; transition:transform .25s, box-shadow .25s; cursor:pointer;"
                         onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 16px 48px rgba(10,22,40,.12)'"
                         onmouseout="this.style.transform='none'; this.style.boxShadow='0 2px 16px rgba(10,22,40,.05)'"
                         @click="openBillet({
                             code:   '{{ $code }}',
                             titre:  '{{ addslashes($ev?->titre ?? '') }}',
                             lieu:   '{{ addslashes($ev?->lieu ?? '') }}',
                             date:   '{{ $debut->translatedFormat('d M Y') }}',
                             heure:  '{{ $debut->format('H:i') }}',
                             places: '{{ $res->nb_places ?? 1 }}',
                            total:  '{{ (optional($ev?->categorie)->prix ?? 0) * ($res->nb_places ?? 1) > 0 ? number_format((optional($ev?->categorie)->prix ?? 0) * ($res->nb_places ?? 1), 0, ',', ' ') . ' FCFA' : 'Gratuit' }}',
                             qrUrl:  '{{ url('/reservations/verify/' . $code) }}',
                             statut: 'Voir'
                         })">

                        {{-- Header billet mini --}}
                        <div style="background:linear-gradient(135deg,var(--blue-night),var(--blue-electric)); padding:1.25rem; position:relative; overflow:hidden;">
                            <div style="position:absolute; inset:0; opacity:.1; background-image:linear-gradient(rgba(201,168,76,.5) 1px,transparent 1px),linear-gradient(90deg,rgba(201,168,76,.5) 1px,transparent 1px); background-size:20px 20px;" aria-hidden="true"></div>
                            <div style="position:relative;">
                                @if($ev?->categorie)
                                <span style="font-size:.65rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--gold-light);">{{ $ev->categorie->nom }}</span>
                                @endif
                                <h3 style="font-size:.95rem; font-weight:700; color:white; margin:.25rem 0 .5rem; line-height:1.3; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                                    {{ $ev?->titre ?? 'Événement' }}
                                </h3>
                                <p style="font-size:.72rem; color:rgba(255,255,255,.55);">📍 {{ $ev?->lieu }} · 🗓 {{ $debut->translatedFormat('d M Y') }}</p>
                            </div>
                        </div>

                        {{-- QR mini --}}
                        <div style="display:flex; align-items:center; justify-content:center; padding:1.25rem; background:white; border-top:2px dashed var(--gray-soft);">
                            <div style="padding:.5rem; background:white; border:1.5px solid var(--gray-soft); border-radius:.75rem;">
                                <canvas id="qr-{{ $res->id ?? loop.index }}" width="100" height="100"></canvas>
                            </div>
                        </div>

                        <div style="padding:.875rem 1.25rem; text-align:center; background:var(--pearl); border-top:1px solid var(--gray-soft);">
                            <p class="font-mono-jet" style="font-size:.82rem; font-weight:700; color:var(--blue-night); letter-spacing:.1em;">{{ $code }}</p>
                            <p style="font-size:.68rem; color:var(--gray-mid); margin-top:.2rem;">Appuyez pour agrandir</p>
                        </div>
                    </div>

                    @empty
                    <div style="grid-column:1/-1; text-align:center; padding:3rem 1.5rem;">
                        <p style="font-size:.9rem; color:var(--gray-mid);">Aucun billet disponible.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>


        {{-- ══════ ONGLET : MON PROFIL ══════ --}}
        <div x-show="activeTab === 'profil'" x-cloak class="fade-in">

            <div style="max-width:38rem;">
                <form method="POST" action="{{ route('profile.update') }}" style="display:flex; flex-direction:column; gap:1.25rem;">
                    @csrf @method('PATCH')

                    <div class="card" style="padding:1.75rem;">
                        <h2 style="font-size:1rem; font-weight:700; color:var(--blue-night); margin-bottom:1.5rem; padding-bottom:1rem; border-bottom:1px solid var(--gray-soft);">
                            Informations personnelles
                        </h2>

                        <div style="display:flex; flex-direction:column; gap:1.1rem;">

                            @foreach([
                                ['name',  'text',  'Nom complet',        Auth::user()->name ?? '',  'M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z'],
                                ['email', 'email', 'Adresse email',       Auth::user()->email ?? '', 'M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75'],
                            ] as [$name, $type, $label, $value, $icon])
                            <div>
                                <label style="display:block; font-size:.75rem; font-weight:600; color:var(--blue-night); margin-bottom:.5rem;">{{ $label }}</label>
                                <div style="position:relative;">
                                    <span style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); pointer-events:none; display:flex; align-items:center;">
                                        <svg style="width:.9rem;height:.9rem;color:var(--gray-mid);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                                    </span>
                                    <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $value) }}"
                                           style="width:100%; padding:.875rem 1rem .875rem 3rem; border:1.5px solid var(--gray-soft); border-radius:1rem; font-size:.875rem; font-family:'Inter',sans-serif; color:var(--blue-night); background:white; outline:none; transition:border-color .2s, box-shadow .2s;"
                                           onfocus="this.style.borderColor='var(--blue-electric)'; this.style.boxShadow='0 0 0 3px rgba(30,95,216,.12)'"
                                           onblur="this.style.borderColor='var(--gray-soft)'; this.style.boxShadow='none'">
                                </div>
                                @error($name)<p style="font-size:.72rem; color:#DC2626; margin-top:.35rem;">{{ $message }}</p>@enderror
                            </div>
                            @endforeach

                        </div>
                    </div>

                    <button type="submit"
                            style="align-self:flex-start; display:flex; align-items:center; gap:.625rem; padding:.875rem 2rem; border-radius:1rem; font-size:.875rem; font-weight:700; color:white; border:none; cursor:pointer; background:linear-gradient(135deg,var(--blue-electric),#1248b0); box-shadow:0 4px 16px rgba(30,95,216,.25); font-family:'Inter',sans-serif; transition:filter .2s;"
                            onmouseover="this.style.filter='brightness(1.1)'" onmouseout="this.style.filter='none'">
                        <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                        Enregistrer les modifications
                    </button>
                </form>
            </div>
        </div>


        {{-- ══════ ONGLET : SÉCURITÉ ══════ --}}
        <div x-show="activeTab === 'securite'" x-cloak class="fade-in">
            <div style="max-width:38rem; display:flex; flex-direction:column; gap:1.25rem;">

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf @method('PUT')

                    <div class="card" style="padding:1.75rem;">
                        <h2 style="font-size:1rem; font-weight:700; color:var(--blue-night); margin-bottom:1.5rem; padding-bottom:1rem; border-bottom:1px solid var(--gray-soft);">
                            Changer le mot de passe
                        </h2>

                        <div style="display:flex; flex-direction:column; gap:1.1rem;"
                             x-data="{ showCurrent:false, showNew:false, showConfirm:false }">

                            @foreach([
                                ['current_password',      'showCurrent',  'Mot de passe actuel'],
                                ['password',              'showNew',      'Nouveau mot de passe'],
                                ['password_confirmation', 'showConfirm',  'Confirmer le nouveau mot de passe'],
                            ] as [$name, $show, $label])
                            <div>
                                <label style="display:block; font-size:.75rem; font-weight:600; color:var(--blue-night); margin-bottom:.5rem;">{{ $label }}</label>
                                <div style="position:relative;">
                                    <span style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); pointer-events:none; display:flex; align-items:center;">
                                        <svg style="width:.9rem;height:.9rem;color:var(--gray-mid);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                                    </span>
                                    <input :type="{{ $show }} ? 'text' : 'password'" name="{{ $name }}"
                                           style="width:100%; padding:.875rem 3rem .875rem 3rem; border:1.5px solid var(--gray-soft); border-radius:1rem; font-size:.875rem; font-family:'Inter',sans-serif; color:var(--blue-night); background:white; outline:none; transition:border-color .2s;"
                                           onfocus="this.style.borderColor='var(--blue-electric)'" onblur="this.style.borderColor='var(--gray-soft)'">
                                    <button type="button" @click="{{ $show }} = !{{ $show }}"
                                            style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:var(--gray-mid); display:flex; align-items:center; padding:.25rem;">
                                        <svg style="width:.9rem;height:.9rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                        </svg>
                                    </button>
                                </div>
                                @error($name)<p style="font-size:.72rem; color:#DC2626; margin-top:.35rem;">{{ $message }}</p>@enderror
                            </div>
                            @endforeach

                        </div>

                        <button type="submit"
                                style="margin-top:1.5rem; display:flex; align-items:center; gap:.625rem; padding:.875rem 2rem; border-radius:1rem; font-size:.875rem; font-weight:700; color:white; border:none; cursor:pointer; background:linear-gradient(135deg,var(--blue-electric),#1248b0); font-family:'Inter',sans-serif; transition:filter .2s;"
                                onmouseover="this.style.filter='brightness(1.1)'" onmouseout="this.style.filter='none'">
                            🔒 Mettre à jour le mot de passe
                        </button>
                    </div>
                </form>

                {{-- Danger zone --}}
                <div style="padding:1.5rem; border-radius:1.5rem; background:#FEF2F2; border:1.5px solid #FECACA;"
                     x-data="{ confirm:false }">
                    <h3 style="font-size:.875rem; font-weight:700; color:#DC2626; margin-bottom:.4rem;">Zone dangereuse</h3>
                    <p style="font-size:.8rem; color:#DC2626; opacity:.7; margin-bottom:1rem;">La suppression de votre compte est définitive et irréversible.</p>
                    <button @click="confirm=true" x-show="!confirm"
                            style="display:flex; align-items:center; gap:.5rem; padding:.75rem 1.5rem; border-radius:.875rem; font-size:.8rem; font-weight:700; color:white; background:#DC2626; border:none; cursor:pointer; font-family:'Inter',sans-serif;">
                        🗑 Supprimer mon compte
                    </button>
                    <div x-show="confirm" x-cloak style="display:flex; gap:.75rem; flex-wrap:wrap;">
                        <form method="POST" action="{{ route('profile.destroy') }}">
                            @csrf @method('DELETE')
                            <button type="submit" style="padding:.75rem 1.5rem; border-radius:.875rem; font-size:.8rem; font-weight:700; color:white; background:#DC2626; border:none; cursor:pointer; font-family:'Inter',sans-serif;">Confirmer la suppression</button>
                        </form>
                        <button @click="confirm=false" style="padding:.75rem 1.5rem; border-radius:.875rem; font-size:.8rem; font-weight:600; color:var(--blue-night); background:white; border:1.5px solid var(--gray-soft); cursor:pointer; font-family:'Inter',sans-serif;">Annuler</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    {{-- /contenu --}}


    {{-- ══════════════════════════════════════════════
         MODAL BILLET QR
         ══════════════════════════════════════════════ --}}
    <div x-show="modalBillet !== null"
         x-cloak
         @keydown.escape.window="modalBillet = null"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         style="position:fixed; inset:0; z-index:60; display:flex; align-items:center; justify-content:center; padding:1rem; background:rgba(10,22,40,.6); backdrop-filter:blur(6px);"
         @click.self="modalBillet = null">

        <div x-show="modalBillet !== null"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             style="background:white; border-radius:2rem; overflow:hidden; width:100%; max-width:22rem; box-shadow:0 24px 64px rgba(10,22,40,.25);">

            {{-- Header modal --}}
            <div style="background:linear-gradient(135deg,var(--blue-night),var(--blue-electric)); padding:1.5rem; position:relative;">
                <button @click="modalBillet = null"
                        style="position:absolute; top:1rem; right:1rem; width:2rem; height:2rem; border-radius:50%; background:rgba(255,255,255,.15); border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; color:white; transition:background .2s;"
                        onmouseover="this.style.background='rgba(255,255,255,.25)'" onmouseout="this.style.background='rgba(255,255,255,.15)'"
                        aria-label="Fermer">
                    <svg style="width:.875rem;height:.875rem;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
                <p style="font-size:.65rem; font-weight:700; letter-spacing:.15em; text-transform:uppercase; color:var(--gold-light); margin-bottom:.35rem;">Billet ExpoDKR</p>
                <h3 class="font-display" style="font-size:1.15rem; color:white; line-height:1.25;" x-text="modalBillet?.titre"></h3>
                <p style="font-size:.75rem; color:rgba(255,255,255,.55); margin-top:.35rem;">
                    <span x-text="modalBillet?.date"></span>
                    <span> · </span>
                    <span x-text="modalBillet?.lieu"></span>
                </p>
            </div>

            {{-- QR code --}}
            <div style="display:flex; flex-direction:column; align-items:center; padding:1.75rem; gap:1rem;">
                <div style="padding:.875rem; background:white; border:2px solid var(--gray-soft); border-radius:1.25rem; box-shadow:0 4px 20px rgba(10,22,40,.08);">
                    <canvas id="modal-qr"></canvas>
                </div>
                <p class="font-mono-jet" style="font-size:1.1rem; font-weight:700; color:var(--blue-night); letter-spacing:.15em;" x-text="modalBillet?.code"></p>
                <div style="display:flex; align-items:center; gap:.5rem; padding:.5rem 1rem; border-radius:2rem; background:#ECFDF5;">
                    <div style="width:.5rem; height:.5rem; border-radius:50%; background:#059669; animation:pulse 2s infinite;"></div>
                    <span style="font-size:.72rem; font-weight:600; color:#059669;">Billet valide · Non transférable</span>
                </div>
            </div>

            {{-- Détails --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0; border-top:2px dashed var(--gray-soft);">
                <div style="padding:1rem 1.5rem; border-right:1px solid var(--gray-soft);">
                    <p style="font-size:.65rem; font-weight:600; text-transform:uppercase; letter-spacing:.1em; color:var(--gray-mid); margin-bottom:.3rem;">Places</p>
                    <p style="font-size:.9rem; font-weight:700; color:var(--blue-night);" x-text="modalBillet?.places + ' place(s)'"></p>
                </div>
                <div style="padding:1rem 1.5rem;">
                    <p style="font-size:.65rem; font-weight:600; text-transform:uppercase; letter-spacing:.1em; color:var(--gray-mid); margin-bottom:.3rem;">Total</p>
                    <p style="font-size:.9rem; font-weight:700; color:var(--blue-electric);" x-text="modalBillet?.total"></p>
                </div>
            </div>

            {{-- Actions --}}
            <div style="display:flex; gap:.75rem; padding:1rem 1.5rem;">
                <button onclick="window.print()"
                        style="flex:1; padding:.875rem; border-radius:1rem; font-size:.82rem; font-weight:600; color:var(--blue-electric); background:#EFF6FF; border:1.5px solid var(--blue-electric); cursor:pointer; font-family:'Inter',sans-serif; transition:all .2s;"
                        onmouseover="this.style.background='#DBEAFE'" onmouseout="this.style.background='#EFF6FF'">
                    📥 Imprimer
                </button>
                <button @click="
                            if(navigator.share) {
                                navigator.share({ title:'Billet ExpoDKR', text: 'Code : ' + modalBillet.code });
                            } else {
                                navigator.clipboard.writeText(modalBillet.code);
                                showToast('Code copié dans le presse-papier !');
                            }
                        "
                        style="flex:1; padding:.875rem; border-radius:1rem; font-size:.82rem; font-weight:700; color:white; background:linear-gradient(135deg,var(--blue-electric),#1248b0); border:none; cursor:pointer; font-family:'Inter',sans-serif; transition:filter .2s;"
                        onmouseover="this.style.filter='brightness(1.1)'" onmouseout="this.style.filter='none'">
                    📤 Partager
                </button>
            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════
         TOAST
         ══════════════════════════════════════════════ --}}
    <div x-show="toastShow"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="position:fixed; bottom:1.5rem; left:50%; transform:translateX(-50%); z-index:999; background:var(--blue-night); color:white; padding:.75rem 1.5rem; border-radius:1rem; font-size:.825rem; font-weight:600; box-shadow:0 8px 32px rgba(10,22,40,.3); white-space:nowrap; display:flex; align-items:center; gap:.625rem;">
        <svg style="width:.875rem;height:.875rem;color:#10B981;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
        <span x-text="toastMsg"></span>
    </div>


    {{-- ══════════════════════════════════════════════
         BOTTOM NAV MOBILE
         ══════════════════════════════════════════════ --}}
    <nav class="bottom-nav">
        @foreach([
            ['reservations', 'M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026', 'Réservations'],
            ['billets',      'M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z', 'Billets'],
            ['profil',       'M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z', 'Profil'],
            ['securite',     'M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z', 'Sécurité'],
        ] as [$tab, $icon, $label])
        <button @click="activeTab = '{{ $tab }}'"
                style="flex:1; display:flex; flex-direction:column; align-items:center; gap:.25rem; padding:.375rem .5rem; border-radius:.875rem; border:none; cursor:pointer; font-family:'Inter',sans-serif; transition:all .2s;"
                :style="activeTab === '{{ $tab }}' ? 'background:#EFF6FF; color:var(--blue-electric);' : 'background:transparent; color:var(--gray-mid);'">
            <svg style="width:1.25rem;height:1.25rem;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
            <span style="font-size:.6rem; font-weight:600;">{{ $label }}</span>
        </button>
        @endforeach
    </nav>

</div>


{{-- ══════════════════════════════════════════════
     SCRIPTS QR MINI
     ══════════════════════════════════════════════ --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    // QR codes mini (onglet Billets)
    @foreach($reservations ?? [] as $res)
    @php
        $resCode = $res->code ?? 'EXP-' . str_pad($res->id ?? 0, 6, '0', STR_PAD_LEFT);
        $resUrl  = url('/reservations/verify/' . $resCode);
    @endphp
    (function() {
        const canvas = document.getElementById('qr-{{ $res->id ?? $loop->index }}');
        if (canvas && typeof QRCode !== 'undefined') {
            QRCode.toCanvas(canvas, '{{ $resUrl }}', {
                width: 100, margin: 1,
                color: { dark:'#0A1628', light:'#FFFFFF' },
                errorCorrectionLevel: 'H'
            });
        }
    })();
    @endforeach
});

// Pulse animation
const s = document.createElement('style');
s.textContent = `
@keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:.4;} }
@keyframes fade-in { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:none} }
`;
document.head.appendChild(s);
</script>

</body>
</html>
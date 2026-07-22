<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation · Billet #{{ $reservation->code ?? 'EXP-000' }} · ExpoDKR</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- QR Code library --}}
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
        body { font-family:'Inter',sans-serif; background:var(--pearl); color:var(--blue-night); -webkit-font-smoothing:antialiased; }
        .font-display  { font-family:'Instrument Serif',serif; }
        .font-mono-jet { font-family:'JetBrains Mono',monospace; }
        [x-cloak]      { display:none!important; }

        /* Confetti animation */
        @keyframes fall {
            0%   { transform:translateY(-20px) rotate(0deg); opacity:1; }
            100% { transform:translateY(100vh) rotate(720deg); opacity:0; }
        }
        .confetti { position:fixed; top:-20px; pointer-events:none; animation:fall linear forwards; z-index:100; border-radius:2px; }

        /* Ticket perforation */
        .ticket-perf {
            position:relative;
        }
        .ticket-perf::before,
        .ticket-perf::after {
            content:'';
            position:absolute;
            width:1.5rem;
            height:1.5rem;
            border-radius:50%;
            background:var(--pearl);
            top:50%;
            transform:translateY(-50%);
            z-index:2;
        }
        .ticket-perf::before { left:-0.75rem; }
        .ticket-perf::after  { right:-0.75rem; }

        /* Dashed separator */
        .dashed-sep {
            border: none;
            border-top: 2px dashed var(--gray-soft);
            margin: 0;
        }

        /* Print styles */
        @media print {
            .no-print    { display:none!important; }
            .print-only  { display:block!important; }
            body         { background:white; }
            .ticket-wrap { box-shadow:none!important; }
        }

        /* Mobile bottom bar */
        @media(max-width:768px) {
            .page-pad { padding-bottom:7rem; }
        }

        ::-webkit-scrollbar { width:4px; }
        ::-webkit-scrollbar-thumb { background:var(--blue-electric); border-radius:99px; }
    </style>
</head>
<body>

{{--
|--------------------------------------------------------------------------
| ExpoDKR – Confirmation de réservation · Billet QR
| Variables attendues :
|   $reservation->code, $reservation->nom, $reservation->email,
|   $reservation->telephone, $reservation->nb_places,
|   $reservation->paiement, $reservation->created_at
|   $reservation->event (Eloquent relation) ou $event
|--------------------------------------------------------------------------
--}}

@php
    $event      = $reservation->evenement ?? $event ?? null;
    $code       = $reservation->code       ?? 'EXP-' . str_pad($reservation->id ?? 0, 6, '0', STR_PAD_LEFT);
    $prixUnit   = optional($event?->categorie)->prix ?? 0;
    $total      = $prixUnit * ($reservation->nb_places ?? 1);
    $qrData     = url('/reservations/verify/' . $code);
    $paiements  = ['sur_place' => 'Sur place', 'wave' => 'Wave', 'orange' => 'Orange Money'];
    $paiLabel   = $paiements[$reservation->paiement ?? 'sur_place'] ?? 'Sur place';
@endphp


{{-- Confettis JS --}}
<div id="confetti-container" class="no-print"></div>


{{-- ══════════════════════════════════════════════
     NAVBAR MINIMALE
     ══════════════════════════════════════════════ --}}
<header class="no-print sticky top-0 z-50" style="background:var(--blue-night); box-shadow:0 2px 20px rgba(10,22,40,.2);">
    <div style="max-width:64rem; margin:0 auto; padding:0 1.5rem; display:flex; align-items:center; justify-content:space-between; height:4rem;">
        <a href="{{ route('home') }}" style="display:flex; align-items:center; gap:.6rem; text-decoration:none;">
            <span style="display:flex; align-items:center; justify-content:center; width:2rem; height:2rem; border-radius:.5rem; background:linear-gradient(135deg,var(--blue-electric),var(--blue-night));">
                <svg style="width:1rem;height:1rem;color:white;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18"/></svg>
            </span>
            <span class="font-display" style="font-size:1.2rem; color:white;">Expo<span style="background:linear-gradient(135deg,var(--gold),var(--gold-light));-webkit-background-clip:text;-webkit-text-fill-color:transparent;">DKR</span></span>
        </a>
        <div style="display:flex; align-items:center; gap:.5rem;">
            <span style="font-size:.75rem; font-weight:600; padding:.375rem .75rem; border-radius:2rem; background:#ECFDF5; color:#059669;">
                ✅ Réservation confirmée
            </span>
        </div>
    </div>
</header>


{{-- ══════════════════════════════════════════════
     HERO SUCCÈS
     ══════════════════════════════════════════════ --}}
<div class="no-print" style="background:linear-gradient(135deg,var(--blue-night),var(--blue-deep)); padding:3rem 1.5rem 2rem; text-align:center;">

    {{-- Icône succès animée --}}
    <div style="display:flex; justify-content:center; margin-bottom:1.5rem;">
        <div style="position:relative; width:5rem; height:5rem;">
            <div style="width:5rem; height:5rem; border-radius:50%; background:linear-gradient(135deg,#059669,#10B981); display:flex; align-items:center; justify-content:center; box-shadow:0 8px 32px rgba(5,150,105,.4);">
                <svg style="width:2.25rem; height:2.25rem; color:white;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                </svg>
            </div>
            <div style="position:absolute; inset:-4px; border-radius:50%; border:3px solid rgba(16,185,129,.3); animation:ping 2s cubic-bezier(0,0,.2,1) infinite;"></div>
        </div>
    </div>

    <h1 class="font-display" style="font-size:1.9rem; color:white; margin-bottom:.5rem; line-height:1.2;">
        Félicitations ! 🎉
    </h1>
    <p style="font-size:.95rem; color:rgba(255,255,255,.6); max-width:30rem; margin:0 auto 1.5rem;">
        Votre réservation est confirmée. Votre billet QR a été envoyé à
        <span style="color:var(--gold-light); font-weight:600;">{{ $reservation->email }}</span>
    </p>

    <div style="display:inline-flex; align-items:center; gap:.75rem; padding:.875rem 1.5rem; border-radius:1.5rem; background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.12);">
        <span style="font-size:.7rem; font-weight:600; letter-spacing:.15em; text-transform:uppercase; color:rgba(255,255,255,.45);">Code de réservation</span>
        <span class="font-mono-jet" style="font-size:1.1rem; font-weight:700; color:var(--gold-light);">{{ $code }}</span>
    </div>
</div>


{{-- ══════════════════════════════════════════════
     BILLET PRINCIPAL
     ══════════════════════════════════════════════ --}}
<div class="page-pad" style="max-width:42rem; margin:0 auto; padding:1.5rem;">

    {{-- Billet --}}
    <div class="ticket-wrap" style="background:white; border-radius:1.75rem; overflow:hidden; box-shadow:0 8px 40px rgba(10,22,40,.12); margin-bottom:1.5rem;">

        {{-- Header billet --}}
        <div style="background:linear-gradient(135deg,var(--blue-night),var(--blue-electric)); padding:1.75rem 2rem; position:relative; overflow:hidden;">
            <div style="position:absolute; inset:0; opacity:.12; background-image:linear-gradient(rgba(201,168,76,.5) 1px,transparent 1px),linear-gradient(90deg,rgba(201,168,76,.5) 1px,transparent 1px); background-size:30px 30px;" aria-hidden="true"></div>
            <div style="position:absolute; top:-2rem; right:-2rem; width:8rem; height:8rem; border-radius:50%; opacity:.1; background:var(--gold);" aria-hidden="true"></div>

            <div style="position:relative; display:flex; align-items:center; gap:1.25rem;">
                {{-- Logo événement --}}
                <div style="width:4rem; height:4rem; border-radius:1rem; overflow:hidden; flex-shrink:0; border:2px solid rgba(255,255,255,.2); background:rgba(255,255,255,.1);">
                    @if($event?->image)
                        <img src="{{ $event->image }}" alt="{{ $event?->titre }}" style="width:100%;height:100%;object-fit:cover;">
                    @else
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                            <svg style="width:1.5rem;height:1.5rem;color:rgba(255,255,255,.5);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5"/></svg>
                        </div>
                    @endif
                </div>
                <div style="flex:1; min-width:0;">
                    @if($event?->categorie)
                    <span style="font-size:.68rem; font-weight:600; letter-spacing:.12em; text-transform:uppercase; color:var(--gold-light);">{{ $event->categorie->nom }}</span>
                    @endif
                    <h2 class="font-display" style="font-size:1.3rem; color:white; line-height:1.25; margin:.2rem 0; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                        {{ $event?->titre ?? 'Événement' }}
                    </h2>
                    <div style="display:flex; flex-wrap:wrap; gap:.75rem; font-size:.75rem; color:rgba(255,255,255,.6); margin-top:.25rem;">
                        <span>📍 {{ $event?->lieu }}</span>
                        <span>🗓 {{ \Carbon\Carbon::parse($event?->date_debut)->translatedFormat('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Perforation --}}
        <div class="ticket-perf dashed-sep" style="margin:0;"></div>

        {{-- Corps billet --}}
        <div style="padding:1.75rem 2rem;">

            {{-- Infos titulaire --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.5rem;">

                <div>
                    <p style="font-size:.68rem; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:var(--gray-mid); margin-bottom:.35rem;">Titulaire</p>
                    <p style="font-size:.9rem; font-weight:700; color:var(--blue-night);">{{ $reservation->nom }}</p>
                    <p style="font-size:.75rem; color:var(--gray-mid); margin-top:.15rem;">{{ $reservation->email }}</p>
                </div>

                <div>
                    <p style="font-size:.68rem; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:var(--gray-mid); margin-bottom:.35rem;">Places</p>
                    <p style="font-size:.9rem; font-weight:700; color:var(--blue-night);">{{ $reservation->nb_places }} place{{ ($reservation->nb_places ?? 1) > 1 ? 's' : '' }}</p>
                    <p style="font-size:.75rem; color:var(--gray-mid); margin-top:.15rem;">{{ $paiLabel }}</p>
                </div>

                <div>
                    <p style="font-size:.68rem; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:var(--gray-mid); margin-bottom:.35rem;">Date événement</p>
                    <p style="font-size:.9rem; font-weight:700; color:var(--blue-night);">{{ \Carbon\Carbon::parse($event?->date_debut)->translatedFormat('d M Y') }}</p>
                    <p style="font-size:.75rem; color:var(--gray-mid); margin-top:.15rem;">{{ \Carbon\Carbon::parse($event?->date_debut)->translatedFormat('H\hi') }}</p>
                </div>

                <div>
                    <p style="font-size:.68rem; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:var(--gray-mid); margin-bottom:.35rem;">Total payé</p>
                    <p style="font-size:1.1rem; font-weight:800; color:var(--blue-electric);">
                        {{ $total > 0 ? number_format($total, 0, ',', ' ') . ' FCFA' : 'Gratuit' }}
                    </p>
                </div>

            </div>

            {{-- Perforation --}}
            <div class="ticket-perf dashed-sep" style="margin:0 -2rem 1.5rem;"></div>

            {{-- QR Code --}}
            <div style="display:flex; flex-direction:column; align-items:center; gap:1rem;">

                <div style="padding:.875rem; background:white; border-radius:1.25rem; border:2px solid var(--gray-soft); box-shadow:0 4px 20px rgba(10,22,40,.08);">
                    <canvas id="qr-canvas"></canvas>
                </div>

                <div style="text-align:center;">
                    <p class="font-mono-jet" style="font-size:1.2rem; font-weight:700; color:var(--blue-night); letter-spacing:.15em;">{{ $code }}</p>
                    <p style="font-size:.72rem; color:var(--gray-mid); margin-top:.25rem;">Présentez ce code à l'entrée de l'événement</p>
                </div>

                {{-- Badge validité --}}
                <div style="display:flex; align-items:center; gap:.5rem; padding:.5rem 1rem; border-radius:2rem; background:#ECFDF5; border:1px solid #A7F3D0;">
                    <div style="width:.5rem; height:.5rem; border-radius:50%; background:#059669; animation:pulse 2s infinite;"></div>
                    <span style="font-size:.72rem; font-weight:600; color:#059669;">Billet valide · Non transférable</span>
                </div>

            </div>

        </div>

        {{-- Footer billet --}}
        <div style="background:var(--pearl); padding:1rem 2rem; display:flex; align-items:center; justify-content:space-between; border-top:1px solid var(--gray-soft);">
            <div style="display:flex; align-items:center; gap:.5rem;">
                <span class="font-display" style="font-size:1rem; color:var(--blue-night);">Expo<span style="color:var(--blue-electric);">DKR</span></span>
            </div>
            <span style="font-size:.68rem; color:var(--gray-mid);">Émis le {{ \Carbon\Carbon::parse($reservation->created_at)->translatedFormat('d M Y à H:i') }}</span>
        </div>

    </div>
    {{-- /billet --}}


    {{-- ══════════════════════════════════════════════
         ÉTAPES SUIVANTES
         ══════════════════════════════════════════════ --}}
    <div class="no-print" style="background:white; border-radius:1.5rem; border:1px solid var(--gray-soft); padding:1.5rem; margin-bottom:1.5rem; box-shadow:0 2px 16px rgba(10,22,40,.05);">
        <p style="font-size:.875rem; font-weight:700; color:var(--blue-night); margin-bottom:1.25rem;">Que faire maintenant ?</p>

        <div style="display:flex; flex-direction:column; gap:1rem;">

            @foreach([
                ['#10B981', 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z', 'Billet envoyé par email', 'Vérifiez votre boîte mail · Code : ' . $code],
                ['#2563EB', 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25', 'Ajoutez à votre agenda', \Carbon\Carbon::parse($event?->date_debut)->translatedFormat('l d M Y') . ' · ' . ($event?->lieu ?? '')],
                ['#D97706', 'M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z', 'Préparez votre venue', ($event?->lieu ?? 'Voir l\'adresse sur la carte')],
                ['#7C3AED', 'M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026', 'Présentez votre QR code', 'À l\'entrée pour accéder à l\'événement'],
            ] as [$clr, $icon, $title, $desc])
            <div style="display:flex; align-items:flex-start; gap:.875rem;">
                <div style="width:2.25rem; height:2.25rem; border-radius:.75rem; display:flex; align-items:center; justify-content:center; flex-shrink:0;"
                     style="background:{{ $clr }}15;">
                    <svg style="width:1rem;height:1rem;" fill="none" stroke="{{ $clr }}" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/>
                    </svg>
                </div>
                <div style="flex:1; min-width:0;">
                    <p style="font-size:.825rem; font-weight:600; color:var(--blue-night);">{{ $title }}</p>
                    <p style="font-size:.75rem; color:var(--gray-mid); margin-top:.15rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $desc }}</p>
                </div>
            </div>
            @endforeach

        </div>
    </div>


    {{-- ══════════════════════════════════════════════
         ACTIONS
         ══════════════════════════════════════════════ --}}
    <div class="no-print" style="display:grid; grid-template-columns:1fr 1fr; gap:.875rem; margin-bottom:1.5rem;">

        {{-- Télécharger PDF --}}
        <button onclick="window.print()"
                style="display:flex; align-items:center; justify-content:center; gap:.625rem; padding:1rem; border-radius:1rem; font-size:.85rem; font-weight:600; color:var(--blue-night); border:1px solid var(--gray-soft); background:white; cursor:pointer; transition:all .2s; box-shadow:0 2px 8px rgba(10,22,40,.04);"
                onmouseover="this.style.borderColor='var(--blue-electric)'; this.style.color='var(--blue-electric)'"
                onmouseout="this.style.borderColor='var(--gray-soft)'; this.style.color='var(--blue-night)'">
            <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
            </svg>
            Télécharger
        </button>

        {{-- Partager --}}
        <button x-data
                @click="
                    if(navigator.share) {
                        navigator.share({
                            title: 'Mon billet ExpoDKR',
                            text: 'Je participe à {{ addslashes($event?->titre ?? '') }} !',
                            url: window.location.href
                        })
                    } else {
                        navigator.clipboard.writeText('{{ $code }}');
                        $dispatch('toast', 'Code copié !');
                    }
                "
                style="display:flex; align-items:center; justify-content:center; gap:.625rem; padding:1rem; border-radius:1rem; font-size:.85rem; font-weight:600; color:white; border:none; cursor:pointer; background:linear-gradient(135deg,var(--blue-electric),#1248b0); box-shadow:0 4px 16px rgba(30,95,216,.25); transition:filter .2s;"
                onmouseover="this.style.filter='brightness(1.1)'" onmouseout="this.style.filter='none'">
            <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z"/>
            </svg>
            Partager
        </button>

    </div>


    {{-- ══════════════════════════════════════════════
         AUTRES ÉVÉNEMENTS
         ══════════════════════════════════════════════ --}}
    @if(isset($autresEvents) && $autresEvents->count())
    <div class="no-print" style="background:white; border-radius:1.5rem; border:1px solid var(--gray-soft); overflow:hidden; margin-bottom:1.5rem; box-shadow:0 2px 16px rgba(10,22,40,.05);">
        <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--gray-soft);">
            <p style="font-size:.875rem; font-weight:700; color:var(--blue-night);">Vous pourriez aussi aimer</p>
        </div>
        <div style="display:flex; flex-direction:column;">
            @foreach($autresEvents->take(3) as $autre)
            <a href="{{ route('user.events.show', $autre->id) }}"
               style="display:flex; align-items:center; gap:1rem; padding:1rem 1.5rem; border-bottom:1px solid var(--gray-soft); text-decoration:none; transition:background .2s;"
               onmouseover="this.style.background='var(--pearl)'" onmouseout="this.style.background='white'">
                <div style="width:3rem; height:3rem; border-radius:.875rem; overflow:hidden; flex-shrink:0; background:linear-gradient(135deg,var(--blue-night),var(--blue-electric));">
                    @if($autre->image)
                        <img src="{{ $autre->image }}" alt="{{ $autre->titre }}" style="width:100%;height:100%;object-fit:cover;">
                    @endif
                </div>
                <div style="flex:1; min-width:0;">
                    <p style="font-size:.825rem; font-weight:600; color:var(--blue-night); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $autre->titre }}</p>
                    <p style="font-size:.72rem; color:var(--gray-mid); margin-top:.15rem;">📍 {{ $autre->lieu }} · 🗓 {{ \Carbon\Carbon::parse($autre->date_debut)->translatedFormat('d M Y') }}</p>
                </div>
                <svg style="width:.875rem; height:.875rem; color:var(--gray-mid); flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                </svg>
            </a>
            @endforeach
        </div>
    </div>
    @endif


    {{-- Liens de navigation --}}
    <div class="no-print" style="display:flex; flex-wrap:wrap; gap:.75rem; justify-content:center;">
        <a href="{{ route('home') }}"
           style="display:flex; align-items:center; gap:.5rem; font-size:.8rem; font-weight:500; color:var(--blue-electric); text-decoration:none; padding:.625rem 1.25rem; border-radius:.875rem; border:1px solid var(--blue-electric); background:white; transition:all .2s;"
           onmouseover="this.style.background='var(--blue-electric)'; this.style.color='white'" onmouseout="this.style.background='white'; this.style.color='var(--blue-electric)'">
            🏠 Accueil
        </a>
        <a href="{{ route('user.events.index') }}"
           style="display:flex; align-items:center; gap:.5rem; font-size:.8rem; font-weight:500; color:var(--gray-mid); text-decoration:none; padding:.625rem 1.25rem; border-radius:.875rem; border:1px solid var(--gray-soft); background:white; transition:background .2s;"
           onmouseover="this.style.background='var(--pearl)'" onmouseout="this.style.background='white'">
            📅 Voir d'autres événements
        </a>
        @auth
        <a href="{{ route('mon-compte.reservations') }}"
           style="display:flex; align-items:center; gap:.5rem; font-size:.8rem; font-weight:500; color:var(--gray-mid); text-decoration:none; padding:.625rem 1.25rem; border-radius:.875rem; border:1px solid var(--gray-soft); background:white; transition:background .2s;"
           onmouseover="this.style.background='var(--pearl)'" onmouseout="this.style.background='white'">
            🎫 Mes réservations
        </a>
        @endauth
    </div>

</div>
{{-- /conteneur principal --}}


{{-- ══════════════════════════════════════════════
     TOAST NOTIFICATION
     ══════════════════════════════════════════════ --}}
<div x-data="{ show: false, msg: '' }"
     @toast.window="msg = $event.detail; show = true; setTimeout(() => show = false, 3000)"
     x-show="show"
     x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-2"
     style="position:fixed; bottom:1.5rem; left:50%; transform:translateX(-50%); z-index:999; background:var(--blue-night); color:white; padding:.75rem 1.5rem; border-radius:1rem; font-size:.825rem; font-weight:600; box-shadow:0 8px 32px rgba(10,22,40,.3); white-space:nowrap;"
     x-text="msg">
</div>


{{-- ══════════════════════════════════════════════
     SCRIPTS : QR Code + Confettis
     ══════════════════════════════════════════════ --}}
<script>
// ── QR Code ───────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('qr-canvas');
    if (canvas && typeof QRCode !== 'undefined') {
        QRCode.toCanvas(canvas, '{{ $qrData }}', {
            width:       200,
            margin:      2,
            color: {
                dark:  '#0A1628',
                light: '#FFFFFF'
            },
            errorCorrectionLevel: 'H'
        }, (err) => {
            if (err) console.warn('QR Error:', err);
        });
    }

    // ── Confettis ──────────────────────────────────────────────────────
    const colors  = ['#1E5FD8','#C9A84C','#E8C96A','#10B981','#F43F5E','#8B5CF6','#0A1628'];
    const shapes  = [4, 6, 8, 12];

    function launchConfetti(count) {
        const container = document.getElementById('confetti-container');
        if (!container) return;

        for (let i = 0; i < count; i++) {
            const el     = document.createElement('div');
            const size   = Math.random() * 10 + 5;
            const left   = Math.random() * 100;
            const dur    = Math.random() * 2.5 + 2;
            const delay  = Math.random() * 1.5;
            const color  = colors[Math.floor(Math.random() * colors.length)];
            const rot    = Math.random() * 360;
            const width  = size;
            const height = size * (Math.random() * .5 + .5);

            el.className = 'confetti';
            el.style.cssText = `
                left:${left}%;
                width:${width}px;
                height:${height}px;
                background:${color};
                animation-duration:${dur}s;
                animation-delay:${delay}s;
                transform:rotate(${rot}deg);
                border-radius:${Math.random() > .5 ? '50%' : '2px'};
            `;
            container.appendChild(el);

            // Supprimer après animation
            setTimeout(() => el.remove(), (dur + delay) * 1000 + 200);
        }
    }

    // Lancer les confettis au chargement
    launchConfetti(80);
    // Deuxième vague
    setTimeout(() => launchConfetti(50), 800);
});

// ── Ping animation ────────────────────────────────────────────────────
const style = document.createElement('style');
style.textContent = `
@keyframes ping {
    75%, 100% { transform: scale(1.8); opacity: 0; }
}
@keyframes pulse {
    0%, 100% { opacity:1; }
    50% { opacity:.4; }
}
`;
document.head.appendChild(style);
</script>

</body>
</html>
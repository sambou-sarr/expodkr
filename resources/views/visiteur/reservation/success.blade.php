<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation confirmee . ExpoDKR</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
        * { box-sizing:border-box; margin:0; padding:0; }
        body {
            font-family:'Inter',sans-serif; background:var(--pearl); color:var(--blue-night);
            -webkit-font-smoothing:antialiased; min-height:100vh;
            display:flex; align-items:center; justify-content:center; padding:2rem 1rem;
        }
        .font-display { font-family:'Instrument Serif',serif; }
        .card { background:white; border-radius:1.75rem; border:1px solid var(--gray-soft); box-shadow:0 8px 32px rgba(10,22,40,.08); }
        .row {
            display:flex; align-items:center; justify-content:space-between; gap:1rem;
            padding:.875rem 0; border-bottom:1px solid var(--gray-soft);
        }
        .row:last-child { border-bottom:none; }
        @keyframes pop { 0%{transform:scale(.6); opacity:0;} 60%{transform:scale(1.08);} 100%{transform:scale(1); opacity:1;} }
        .success-icon { animation:pop .5s cubic-bezier(.34,1.56,.64,1) forwards; }
    </style>
</head>
<body>

<div style="width:100%; max-width:32rem;">

    <div class="card" style="overflow:hidden;">

        {{-- Bandeau succes --}}
        <div style="padding:2.5rem 2rem 2rem; text-align:center; background:linear-gradient(135deg,var(--blue-night),#0D2145); position:relative; overflow:hidden;">
            <div style="position:absolute; inset:0; opacity:.1; background-image:linear-gradient(rgba(201,168,76,.5) 1px,transparent 1px),linear-gradient(90deg,rgba(201,168,76,.5) 1px,transparent 1px); background-size:25px 25px;" aria-hidden="true"></div>

            <div class="success-icon" style="width:4rem; height:4rem; border-radius:50%; background:#ECFDF5; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem; position:relative;">
                <svg style="width:2rem;height:2rem;color:#059669;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
            </div>

            <p style="font-size:.68rem; font-weight:700; letter-spacing:.15em; text-transform:uppercase; color:var(--gold-light); position:relative; margin-bottom:.4rem;">
                Reservation confirmee
            </p>
            <h1 class="font-display" style="font-size:1.6rem; color:white; position:relative;">
                Merci, {{ explode(' ', $reservation->nom)[0] ?? $reservation->nom }} !
            </h1>
            <p style="font-size:.8rem; color:rgba(255,255,255,.6); margin-top:.4rem; position:relative;">
                Votre place pour cet evenement est reservee.
            </p>
        </div>

        {{-- Reference --}}
        <div style="padding:1.25rem 2rem; background:var(--pearl); border-bottom:1px solid var(--gray-soft); text-align:center;">
            <p style="font-size:.7rem; color:var(--gray-mid); margin-bottom:.3rem;">Numero de reference</p>
            <p style="font-size:1.15rem; font-weight:800; color:var(--blue-electric); letter-spacing:.05em;">{{ $reservation->reference }}</p>
        </div>

        {{-- Details --}}
        <div style="padding:1.5rem 2rem;">

            @if($reservation->evenement)
            <div style="margin-bottom:1.25rem;">
                <p style="font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:var(--gray-mid); margin-bottom:.5rem;">Evenement</p>
                <p style="font-size:1rem; font-weight:700; color:var(--blue-night); margin-bottom:.3rem;">{{ $reservation->evenement->titre }}</p>
                <div style="display:flex; align-items:center; gap:.5rem; font-size:.8rem; color:var(--gray-mid);">
                    <svg style="width:.85rem;height:.85rem;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                    {{ $reservation->evenement->lieu }}
                </div>
                <div style="display:flex; align-items:center; gap:.5rem; font-size:.8rem; color:var(--gray-mid); margin-top:.3rem;">
                    <svg style="width:.85rem;height:.85rem;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25"/></svg>
                    {{ \Carbon\Carbon::parse($reservation->evenement->date_debut)->translatedFormat('d M Y') }}
                </div>
            </div>
            @endif

            <div class="row">
                <span style="font-size:.8rem; color:var(--gray-mid);">Nom</span>
                <span style="font-size:.825rem; font-weight:600; color:var(--blue-night);">{{ $reservation->nom }}</span>
            </div>
            <div class="row">
                <span style="font-size:.8rem; color:var(--gray-mid);">Email</span>
                <span style="font-size:.825rem; font-weight:600; color:var(--blue-night);">{{ $reservation->email }}</span>
            </div>
            <div class="row">
                <span style="font-size:.8rem; color:var(--gray-mid);">Telephone</span>
                <span style="font-size:.825rem; font-weight:600; color:var(--blue-night);">{{ $reservation->telephone }}</span>
            </div>
            <div class="row">
                <span style="font-size:.8rem; color:var(--gray-mid);">Places reservees</span>
                <span style="font-size:.825rem; font-weight:600; color:var(--blue-night);">{{ $reservation->nb_places }}</span>
            </div>
            <div class="row">
                <span style="font-size:.8rem; color:var(--gray-mid);">Mode de paiement</span>
                <span style="font-size:.825rem; font-weight:600; color:var(--blue-night); text-transform:capitalize;">{{ str_replace('_', ' ', $reservation->mode_paiement) }}</span>
            </div>
            <div class="row">
                <span style="font-size:.8rem; color:var(--gray-mid);">Statut</span>
                @php
                    $statusStyles = [
                        'confirmee'  => ['Confirmee',  '#059669', '#ECFDF5'],
                        'en_attente' => ['En attente', '#D97706', '#FFFBEB'],
                        'annule'     => ['Annulee',    '#DC2626', '#FEF2F2'],
                    ];
                    [$sl, $sc, $sb] = $statusStyles[$reservation->statut] ?? ['Confirmee', '#059669', '#ECFDF5'];
                @endphp
                <span style="display:inline-flex; align-items:center; gap:.375rem; font-size:.7rem; font-weight:700; padding:.3rem .75rem; border-radius:2rem; background:{{ $sb }}; color:{{ $sc }};">
                    <span style="width:.4rem; height:.4rem; border-radius:50%; background:{{ $sc }};"></span>
                    {{ $sl }}
                </span>
            </div>
            <div class="row">
                <span style="font-size:.8rem; color:var(--gray-mid);">Montant total</span>
                <span style="font-size:.95rem; font-weight:800; color:var(--blue-electric);">{{ number_format($reservation->montant_total, 0, ',', ' ') }} FCFA</span>
            </div>

        </div>

        {{-- Actions --}}
        <div style="padding:1.5rem 2rem 2rem; display:flex; gap:.75rem;">
            <a href="{{ route('home') }}"
               style="flex:1; display:flex; align-items:center; justify-content:center; padding:.875rem; border-radius:1rem; font-size:.85rem; font-weight:600; color:var(--blue-night); background:white; border:1.5px solid var(--gray-soft); text-decoration:none; transition:background .2s;"
               onmouseover="this.style.background='var(--pearl)'" onmouseout="this.style.background='white'">
                Retour a l'accueil
            </a>
            @if($reservation->evenement)
            <a href="{{ route('user.events.show', $reservation->evenement->getKey()) }}"
               style="flex:1; display:flex; align-items:center; justify-content:center; padding:.875rem; border-radius:1rem; font-size:.85rem; font-weight:700; color:white; background:linear-gradient(135deg,var(--blue-electric),#1248b0); text-decoration:none; box-shadow:0 4px 16px rgba(30,95,216,.25); transition:filter .2s;"
               onmouseover="this.style.filter='brightness(1.1)'" onmouseout="this.style.filter='none'">
                Voir l'evenement
            </a>
            @endif
        </div>

    </div>

    <p style="text-align:center; font-size:.72rem; color:var(--gray-mid); margin-top:1.25rem;">
        Un email de confirmation a ete envoye a {{ $reservation->email }}
    </p>

</div>

</body>
</html>
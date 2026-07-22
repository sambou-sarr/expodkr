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

    <style>
        :root {
            --blue-night: #0A1628;
            --blue-electric: #1E5FD8;
            --gold: #C9A84C;
            --gold-light: #E8C96A;
            --pearl: #F7F8FC;
            --gray-soft: #EEF0F6;
            --gray-mid: #8892A4;
        }
        body { font-family:'Inter',sans-serif; background:var(--pearl); color:var(--blue-night); -webkit-font-smoothing:antialiased; }
        .font-display { font-family:'Instrument Serif',serif; }
    </style>
</head>
<body>

    {{-- Header --}}
    <header style="background:var(--blue-night);">
        <div style="max-width:64rem; margin:0 auto; padding:1.25rem 1.5rem; display:flex; align-items:center; justify-content:space-between;">
            <a href="{{ route('home') }}" style="display:flex; align-items:center; gap:.6rem; text-decoration:none;">
                <span class="font-display" style="font-size:1.2rem; color:white;">Expo<span style="color:var(--gold-light);">DKR</span></span>
            </a>
            <a href="{{ route('home') }}" style="font-size:.82rem; color:rgba(255,255,255,.65); text-decoration:none;">← Retour à l'accueil</a>
        </div>
    </header>

    {{-- Hero --}}
    <div style="background:linear-gradient(135deg,var(--blue-night),#1248b0); padding:4rem 1.5rem 3rem; text-align:center;">
        <p style="font-size:.72rem; font-weight:600; letter-spacing:.18em; text-transform:uppercase; color:var(--gold-light); margin-bottom:1rem;">Tarification</p>
        <h1 class="font-display" style="font-size:2.5rem; color:white; margin-bottom:1rem;">Choisissez le pack adapté à votre événement</h1>
        <p style="font-size:1rem; color:rgba(255,255,255,.6); max-width:36rem; margin:0 auto;">
            Payez uniquement au moment de créer votre événement — pas d'abonnement, pas d'engagement.
        </p>
    </div>

    {{-- Grille packs --}}
    <div style="max-width:76rem; margin:-2.5rem auto 0; padding:0 1.5rem 5rem; position:relative; z-index:10;">
        <div style="display:grid; grid-template-columns:1fr; gap:1.5rem;">
            <style>@media(min-width:1024px){.packs-grid{grid-template-columns:repeat(4,1fr)!important;}}</style>

            <div class="packs-grid" style="display:grid; grid-template-columns:1fr; gap:1.5rem;">
                @foreach($packs as $pack)
                @php
                    $estPro = $pack->slug === 'pro';
                @endphp
                <div style="background:white; border-radius:1.75rem; overflow:hidden; position:relative;
                            {{ $estPro ? 'border:2px solid var(--gold); box-shadow:0 16px 48px rgba(201,168,76,.25); transform:translateY(-1rem);' : 'border:1px solid var(--gray-soft); box-shadow:0 4px 24px rgba(10,22,40,.06);' }}">

                    @if($estPro)
                    <div style="background:linear-gradient(135deg,var(--gold),var(--gold-light)); text-align:center; padding:.5rem; font-size:.7rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--blue-night);">
                        ⭐ Le plus populaire
                    </div>
                    @endif

                    <div style="padding:2rem;">
                        <div style="width:3rem; height:3rem; border-radius:1rem; background:{{ $pack->couleur }}22; display:flex; align-items:center; justify-content:center; margin-bottom:1.25rem;">
                            <div style="width:1.25rem; height:1.25rem; border-radius:.4rem; background:{{ $pack->couleur }};"></div>
                        </div>

                        <h3 class="font-display" style="font-size:1.5rem; color:var(--blue-night); margin-bottom:.5rem;">{{ $pack->nom }}</h3>
                        <p style="font-size:.82rem; color:var(--gray-mid); line-height:1.6; margin-bottom:1.5rem; min-height:3rem;">{{ $pack->description }}</p>

                        <div style="margin-bottom:1.75rem;">
                            @if($pack->estGratuit())
                                <span class="font-display" style="font-size:2rem; color:var(--blue-night);">Gratuit</span>
                            @else
                                <span class="font-display" style="font-size:2rem; color:var(--blue-night);">{{ number_format($pack->prix, 0, ',', ' ') }}</span>
                                <span style="font-size:.85rem; color:var(--gray-mid);"> FCFA / événement</span>
                            @endif
                        </div>

                        <ul style="display:flex; flex-direction:column; gap:.75rem; margin-bottom:2rem;">
                            @php
                                $features = [
                                    $pack->max_evenements ? $pack->max_evenements . ' événement' . ($pack->max_evenements > 1 ? 's' : '') . ' actif' . ($pack->max_evenements > 1 ? 's' : '') : 'Événements illimités',
                                    $pack->stats_avancees ? 'Statistiques avancées' : 'Statistiques de base',
                                    $pack->mise_en_avant ? 'Mise en avant page d\'accueil' : null,
                                    $pack->support_dedie ? 'Accompagnement dédié' : null,
                                ];
                                $features = array_filter($features);
                            @endphp
                            @foreach($features as $feature)
                            <li style="display:flex; align-items:flex-start; gap:.625rem; font-size:.82rem; color:var(--blue-night);">
                                <svg style="width:1rem;height:1rem;color:#059669; flex-shrink:0; margin-top:.1rem;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                </svg>
                                {{ $feature }}
                            </li>
                            @endforeach

                            @foreach(['stats_avancees' => 'Statistiques avancées', 'mise_en_avant' => 'Mise en avant', 'support_dedie' => 'Accompagnement dédié'] as $key => $label)
                                @if(!$pack->$key)
                                <li style="display:flex; align-items:flex-start; gap:.625rem; font-size:.82rem; color:var(--gray-mid); opacity:.5;">
                                    <svg style="width:1rem;height:1rem; flex-shrink:0; margin-top:.1rem;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                    </svg>
                                    {{ $label }}
                                </li>
                                @endif
                            @endforeach
                        </ul>

                        <a href=""
                           style="display:block; text-align:center; padding:.875rem; border-radius:1rem; font-size:.85rem; font-weight:700; text-decoration:none; transition:filter .2s;
                                  {{ $estPro
                                        ? 'background:linear-gradient(135deg,var(--gold),var(--gold-light)); color:var(--blue-night);'
                                        : 'background:var(--pearl); color:var(--blue-night); border:1.5px solid var(--gray-soft);' }}"
                           onmouseover="this.style.filter='brightness(1.05)'" onmouseout="this.style.filter='none'">
                            {{ $pack->estGratuit() ? 'Commencer gratuitement' : 'Choisir ce pack' }}
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Note --}}
        <p style="text-align:center; font-size:.8rem; color:var(--gray-mid); margin-top:3rem;">
            Besoin d'un accompagnement sur-mesure ? <a href="" style="color:var(--blue-electric); font-weight:600; text-decoration:underline;">Contactez notre équipe</a>.
        </p>
    </div>

</body>
</html>
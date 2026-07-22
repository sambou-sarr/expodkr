<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Choisir un pack · ExpoDKR</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --blue-night: #0A1628; --blue-electric: #1E5FD8;
            --gold: #C9A84C; --gold-light: #E8C96A;
            --pearl: #F7F8FC; --gray-soft: #EEF0F6; --gray-mid: #8892A4;
        }
        body { font-family:'Inter',sans-serif; background:var(--pearl); color:var(--blue-night); }
        .font-display { font-family:'Instrument Serif',serif; }
        [x-cloak] { display:none!important; }
    </style>
</head>
<body>

<div x-data="{ packSelectionne: null, modePaiement: 'wave' }">

    {{-- Header --}}
    <header style="background:var(--blue-night); padding:1.25rem 1.5rem;">
        <div style="max-width:56rem; margin:0 auto; display:flex; align-items:center; justify-content:space-between;">
            <span class="font-display" style="font-size:1.2rem; color:white;">Expo<span style="color:var(--gold-light);">DKR</span></span>
            <a href="" style="font-size:.82rem; color:rgba(255,255,255,.65); text-decoration:none;">← Retour</a>
        </div>
    </header>

    <div style="max-width:56rem; margin:0 auto; padding:2.5rem 1.5rem;">

        <div style="margin-bottom:2rem;">
            <p style="font-size:.7rem; font-weight:600; letter-spacing:.15em; text-transform:uppercase; color:var(--gold); margin-bottom:.5rem;">Étape finale</p>
            <h1 class="font-display" style="font-size:2rem; color:var(--blue-night); margin-bottom:.5rem;">Choisissez un pack pour publier</h1>
            <p style="font-size:.9rem; color:var(--gray-mid);">
                Événement : <strong style="color:var(--blue-night);">{{ $evenement->titre }}</strong>
            </p>
        </div>

        @if($errors->any())
        <div style="background:#FEF2F2; border:1px solid #FECACA; color:#DC2626; padding:1rem 1.25rem; border-radius:1rem; font-size:.85rem; margin-bottom:1.5rem;">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('organisateur.packs.acheter', $evenement->id) }}">
            @csrf

            {{-- Grille des packs --}}
            <div style="display:grid; grid-template-columns:1fr; gap:1rem; margin-bottom:2rem;">
                <style>@media(min-width:768px){.pack-choice-grid{grid-template-columns:repeat(2,1fr)!important;}}</style>
                <div class="pack-choice-grid" style="display:grid; grid-template-columns:1fr; gap:1rem;">

                    @foreach($packs as $pack)
                    <label style="display:block; cursor:pointer; padding:1.5rem; border-radius:1.25rem; border:2px solid; transition:all .2s; background:white;"
                           :style="packSelectionne === {{ $pack->id }} ? 'border-color:var(--blue-electric); background:#EFF6FF;' : 'border-color:var(--gray-soft);'">

                        <input type="radio" name="pack_id" value="{{ $pack->id }}"
                               x-model="packSelectionne"
                               @change="packSelectionne = {{ $pack->id }}"
                               class="sr-only" required>

                        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:.75rem;">
                            <h3 style="font-size:1rem; font-weight:700; color:var(--blue-night);">{{ $pack->nom }}</h3>
                            <div style="width:1.25rem; height:1.25rem; border-radius:50%; border:2px solid; flex-shrink:0;"
                                 :style="packSelectionne === {{ $pack->id }} ? 'border-color:var(--blue-electric); background:var(--blue-electric);' : 'border-color:var(--gray-mid);'">
                            </div>
                        </div>

                        <p style="font-size:.78rem; color:var(--gray-mid); margin-bottom:1rem; line-height:1.5;">{{ $pack->description }}</p>

                        <p class="font-display" style="font-size:1.5rem; color:var(--blue-electric); margin-bottom:.75rem;">
                            {{ $pack->estGratuit() ? 'Gratuit' : number_format($pack->prix, 0, ',', ' ') . ' FCFA' }}
                        </p>

                        <div style="display:flex; flex-wrap:wrap; gap:.4rem;">
                            @if($pack->mise_en_avant)<span style="font-size:.68rem; padding:.25rem .625rem; border-radius:2rem; background:#FFFBEB; color:#D97706;">Mise en avant</span>@endif
                            @if($pack->stats_avancees)<span style="font-size:.68rem; padding:.25rem .625rem; border-radius:2rem; background:#EFF6FF; color:#2563EB;">Stats avancées</span>@endif
                            @if($pack->support_dedie)<span style="font-size:.68rem; padding:.25rem .625rem; border-radius:2rem; background:#ECFDF5; color:#059669;">Support dédié</span>@endif
                        </div>
                    </label>
                    @endforeach

                </div>
            </div>

            {{-- Mode de paiement (affiché seulement si pack payant) --}}
            <div x-show="packSelectionne && ![{{ $packs->where('prix', 0)->pluck('id')->implode(',') }}].includes(packSelectionne)"
                 x-cloak
                 style="background:white; border-radius:1.5rem; border:1px solid var(--gray-soft); padding:1.75rem; margin-bottom:1.5rem;">
                <h3 style="font-size:.9rem; font-weight:700; color:var(--blue-night); margin-bottom:1rem;">Mode de paiement</h3>

                <div style="display:flex; flex-direction:column; gap:.75rem;">
                    @foreach([['wave','📱','Wave'], ['orange','🟠','Orange Money'], ['virement','🏦','Virement bancaire']] as [$val, $emoji, $label])
                    <label style="display:flex; align-items:center; gap:1rem; padding:1rem 1.25rem; border-radius:1rem; cursor:pointer; border:2px solid;"
                           :style="modePaiement === '{{ $val }}' ? 'border-color:var(--blue-electric); background:#EFF6FF;' : 'border-color:var(--gray-soft);'">
                        <input type="radio" name="mode_paiement" value="{{ $val }}" x-model="modePaiement" class="sr-only">
                        <span style="font-size:1.25rem;">{{ $emoji }}</span>
                        <span style="font-size:.85rem; font-weight:600; color:var(--blue-night);">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <button type="submit"
                    :disabled="!packSelectionne"
                    :style="!packSelectionne ? 'opacity:.5; cursor:not-allowed;' : ''"
                    style="width:100%; padding:1rem; border-radius:1rem; font-size:.9rem; font-weight:700; color:white; border:none; cursor:pointer; background:linear-gradient(135deg,var(--blue-electric),#1248b0); box-shadow:0 4px 16px rgba(30,95,216,.3);">
                Confirmer et publier l'événement
            </button>
        </form>
    </div>
</div>

</body>
</html>
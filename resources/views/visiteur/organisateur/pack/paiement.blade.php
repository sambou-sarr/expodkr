<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement du pack · ExpoDKR</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --blue-night: #0A1628; --blue-electric: #1E5FD8;
            --gold: #C9A84C; --gold-light: #E8C96A;
            --pearl: #F7F8FC; --gray-soft: #EEF0F6; --gray-mid: #8892A4;
        }
        body { font-family:'Inter',sans-serif; background:var(--pearl); color:var(--blue-night); min-height:100vh; display:flex; align-items:center; justify-content:center; padding:1.5rem; }
        .font-display { font-family:'Instrument Serif',serif; }
    </style>
</head>
<body>

    <div style="background:white; border-radius:1.75rem; box-shadow:0 8px 40px rgba(10,22,40,.1); max-width:28rem; width:100%; overflow:hidden;">

        <div style="background:linear-gradient(135deg,var(--blue-night),var(--blue-electric)); padding:2rem; text-align:center;">
            <div style="width:3.5rem; height:3.5rem; border-radius:1rem; background:rgba(255,255,255,.12); display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
                <span style="font-size:1.5rem;">💳</span>
            </div>
            <h1 class="font-display" style="font-size:1.5rem; color:white; margin-bottom:.4rem;">Finaliser le paiement</h1>
            <p style="font-size:.8rem; color:rgba(255,255,255,.6);">Pack {{ $achat->pack->nom }} · {{ $achat->evenement->titre }}</p>
        </div>

        <div style="padding:2rem;">

            <div style="background:var(--pearl); border-radius:1.25rem; padding:1.25rem; margin-bottom:1.5rem;">
                <div style="display:flex; justify-content:space-between; margin-bottom:.5rem;">
                    <span style="font-size:.82rem; color:var(--gray-mid);">Référence</span>
                    <span style="font-size:.82rem; font-weight:700; color:var(--blue-night);">{{ $achat->reference }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; margin-bottom:.5rem;">
                    <span style="font-size:.82rem; color:var(--gray-mid);">Mode de paiement</span>
                    <span style="font-size:.82rem; font-weight:700; color:var(--blue-night);">{{ ucfirst($achat->mode_paiement) }}</span>
                </div>
                <div style="border-top:1px solid var(--gray-soft); margin-top:.75rem; padding-top:.75rem; display:flex; justify-content:space-between;">
                    <span style="font-size:.9rem; font-weight:700; color:var(--blue-night);">Montant total</span>
                    <span style="font-size:1.25rem; font-weight:800; color:var(--blue-electric);">{{ number_format($achat->montant, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>

            @if($achat->mode_paiement === 'wave')
            <div style="text-align:center; margin-bottom:1.5rem;">
                <p style="font-size:.85rem; color:var(--gray-mid); margin-bottom:1rem;">Scannez ce QR code avec l'application Wave pour payer.</p>
                <div style="width:10rem; height:10rem; background:var(--gray-soft); border-radius:1rem; margin:0 auto; display:flex; align-items:center; justify-content:center;">
                    <span style="font-size:.75rem; color:var(--gray-mid);">QR Wave ici</span>
                </div>
            </div>
            @elseif($achat->mode_paiement === 'orange')
            <div style="text-align:center; margin-bottom:1.5rem;">
                <p style="font-size:.85rem; color:var(--gray-mid);">Composez <strong style="color:var(--blue-night);">#144#</strong> et suivez les instructions pour envoyer {{ number_format($achat->montant,0,',',' ') }} FCFA au numéro <strong style="color:var(--blue-night);">77 000 00 00</strong>.</p>
            </div>
            @else
            <div style="text-align:center; margin-bottom:1.5rem;">
                <p style="font-size:.85rem; color:var(--gray-mid);">Effectuez un virement vers le compte ExpoDKR et envoyez le justificatif à <strong style="color:var(--blue-night);">paiements@expodakar.sn</strong>.</p>
            </div>
            @endif

            <p style="font-size:.75rem; color:var(--gray-mid); text-align:center; margin-bottom:1.5rem;">
                Une fois le paiement effectué, notre équipe confirmera votre pack sous 24h ouvrées.
            </p>

            <a href="{{ route('organisateur.evenements.show', $achat->evenement->id) }}"
               style="display:block; text-align:center; padding:.875rem; border-radius:1rem; font-size:.85rem; font-weight:700; color:white; text-decoration:none; background:linear-gradient(135deg,var(--blue-electric),#1248b0);">
                J'ai effectué le paiement
            </a>
        </div>
    </div>

</body>
</html>
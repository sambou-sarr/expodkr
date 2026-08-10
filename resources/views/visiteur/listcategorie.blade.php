<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catégories · ExpoDKR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
        body { font-family:'Inter',sans-serif; background:var(--pearl); color:var(--blue-night); }
        .font-display { font-family:'Instrument Serif',serif; }
    </style>
</head>
<body>

    <header style="background:var(--blue-night);">
        <div style="max-width:80rem; margin:0 auto; padding:1.25rem 1.5rem; display:flex; align-items:center; justify-content:space-between;">
            <a href="{{ route('home') }}" style="display:flex; align-items:center; gap:.6rem; text-decoration:none;">
                <span class="font-display" style="font-size:1.2rem; color:white;">Expo<span style="color:var(--gold-light);">DKR</span></span>
            </a>
            <a href="{{ route('home') }}" style="font-size:.82rem; color:rgba(255,255,255,.65); text-decoration:none;">← Retour à l'accueil</a>
        </div>
    </header>

    <div style="background:var(--blue-night); padding:3.5rem 1.5rem; text-align:center;">
        <p style="font-size:.72rem; font-weight:600; letter-spacing:.14em; text-transform:uppercase; color:var(--gold-light); margin-bottom:1rem;">Explorer</p>
        <h1 class="font-display" style="font-size:2.5rem; color:white; margin-bottom:.75rem;">Toutes les catégories</h1>
        <p style="font-size:.95rem; color:rgba(255,255,255,.55); max-width:32rem; margin:0 auto;">
            Parcourez les événements par thématique et trouvez ceux qui correspondent à votre secteur.
        </p>
    </div>

    <div style="max-width:80rem; margin:0 auto; padding:3rem 1.5rem 5rem;">
        <style>@media(min-width:768px){.cat-grid{grid-template-columns:repeat(3,1fr)!important;}}@media(min-width:1024px){.cat-grid{grid-template-columns:repeat(4,1fr)!important;}}</style>

        <div class="cat-grid" style="display:grid; grid-template-columns:1fr; gap:1.25rem;">
            @forelse($categories as $cat)
            <a href="{{ route('user.categories.show', $cat->id) }}"
               style="background:white; border-radius:1rem; padding:1.75rem; border:1px solid var(--gray-soft); text-decoration:none; color:inherit; transition:box-shadow .2s ease, border-color .2s ease; display:block;"
               onmouseover="this.style.boxShadow='0 12px 32px rgba(10,22,40,.08)'; this.style.borderColor='#DCE1EC';"
               onmouseout="this.style.boxShadow='none'; this.style.borderColor='var(--gray-soft)';">
                <div style="width:2.75rem; height:2.75rem; border-radius:.75rem; background:#1E5FD812; display:flex; align-items:center; justify-content:center; margin-bottom:1rem;">
                    <svg style="width:1.25rem; height:1.25rem; color:var(--blue-electric);" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                    </svg>
                </div>
                <h3 style="font-size:1rem; font-weight:600; margin-bottom:.35rem;">{{ $cat->nom }}</h3>
                <p style="font-size:.8rem; color:var(--gray-mid);">
                    {{ $cat->evenements_count }} événement{{ $cat->evenements_count > 1 ? 's' : '' }}
                </p>
            </a>
            @empty
            <div style="grid-column:1/-1; text-align:center; padding:3rem 0;">
                <p style="color:var(--gray-mid);">Aucune catégorie disponible pour le moment.</p>
            </div>
            @endforelse
        </div>
    </div>

</body>
</html>
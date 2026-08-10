<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $categorie->nom }} · ExpoDKR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --blue-night:#0A1628; --blue-electric:#1E5FD8; --gold:#C9A84C; --gold-light:#E8C96A; --pearl:#F7F8FC; --gray-soft:#EEF0F6; --gray-mid:#8892A4; }
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
            <a href="{{ route('user.categories.index') }}" style="font-size:.82rem; color:rgba(255,255,255,.65); text-decoration:none;">← Toutes les catégories</a>
        </div>
    </header>

    <div style="background:var(--blue-night); padding:3rem 1.5rem; text-align:center;">
        <h1 class="font-display" style="font-size:2.25rem; color:white;">{{ $categorie->nom }}</h1>
        <p style="font-size:.9rem; color:rgba(255,255,255,.55); margin-top:.5rem;">
            {{ $events->total() }} événement{{ $events->total() > 1 ? 's' : '' }} dans cette catégorie
        </p>
    </div>

    <div style="max-width:80rem; margin:0 auto; padding:3rem 1.5rem 5rem;">
        <style>@media(min-width:768px){.ev-grid{grid-template-columns:repeat(2,1fr)!important;}}@media(min-width:1024px){.ev-grid{grid-template-columns:repeat(3,1fr)!important;}}</style>

        <div class="ev-grid" style="display:grid; grid-template-columns:1fr; gap:1.5rem;">
            @forelse($events as $event)
            <a href="{{ route('user.events.show', $event->id) }}" style="text-decoration:none; color:inherit; background:white; border-radius:1rem; overflow:hidden; border:1px solid var(--gray-soft); display:block;">
                @if($event->image)
                <img src="{{ $event->image }}" alt="{{ $event->titre }}" style="width:100%; height:11rem; object-fit:cover;">
                @endif
                <div style="padding:1.25rem;">
                    <h3 style="font-weight:600; font-size:.95rem; margin-bottom:.4rem;">{{ $event->titre }}</h3>
                    <p style="font-size:.78rem; color:var(--gray-mid);">{{ $event->lieu }}</p>
                </div>
            </a>
            @empty
            <div style="grid-column:1/-1; text-align:center; padding:3rem 0;">
                <p style="color:var(--gray-mid);">Aucun événement dans cette catégorie pour le moment.</p>
            </div>
            @endforelse
        </div>

        <div style="margin-top:2rem;">
            {{ $events->links() }}
        </div>
    </div>

</body>
</html>
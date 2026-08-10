<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact · ExpoDKR</title>

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
        .inp {
            width:100%; padding:.875rem 1rem; border:1.5px solid var(--gray-soft); border-radius:1rem;
            font-size:.875rem; font-family:'Inter',sans-serif; color:var(--blue-night); background:white;
            outline:none; transition:border-color .2s, box-shadow .2s;
        }
        .inp:focus { border-color:var(--blue-electric); box-shadow:0 0 0 3px rgba(30,95,216,.12); }
        .btn-primary {
            width:100%; padding:.95rem 1.5rem; border-radius:1rem; border:none;
            font-size:.9rem; font-weight:700; font-family:'Inter',sans-serif; color:white; cursor:pointer;
            background:linear-gradient(135deg,var(--blue-electric),#1248b0);
            box-shadow:0 4px 20px rgba(30,95,216,.35); transition:filter .2s;
        }
        .btn-primary:hover { filter:brightness(1.1); }
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
        <p style="font-size:.72rem; font-weight:600; letter-spacing:.18em; text-transform:uppercase; color:var(--gold-light); margin-bottom:1rem;">Contact</p>
        <h1 class="font-display" style="font-size:2.5rem; color:white; margin-bottom:1rem;">Parlons de votre projet</h1>
        <p style="font-size:1rem; color:rgba(255,255,255,.6); max-width:34rem; margin:0 auto;">
            Une question sur un événement, un stand, un partenariat ? Notre équipe vous répond rapidement.
        </p>
    </div>

    {{-- Contenu --}}
    <div style="max-width:64rem; margin:-2.5rem auto 0; padding:0 1.5rem 5rem; position:relative; z-index:10;">
        <div style="display:grid; grid-template-columns:1fr; gap:1.5rem;">
            <style>@media(min-width:1024px){.contact-grid{grid-template-columns:.9fr 1.1fr!important;}}</style>

            <div class="contact-grid" style="display:grid; grid-template-columns:1fr; gap:1.5rem;">

                {{-- Infos --}}
                <div style="background:white; border-radius:1.75rem; padding:2.5rem; border:1px solid var(--gray-soft); box-shadow:0 4px 24px rgba(10,22,40,.06);">
                    <h3 class="font-display" style="font-size:1.4rem; margin-bottom:1.5rem;">Coordonnées</h3>

                    <div style="display:flex; flex-direction:column; gap:1.5rem;">
                        <div style="display:flex; gap:.9rem; align-items:flex-start;">
                            <svg style="width:1.1rem;height:1.1rem;color:var(--gold); flex-shrink:0; margin-top:.15rem;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                            <div>
                                <p style="font-size:.78rem; font-weight:600; color:var(--gray-mid); text-transform:uppercase; letter-spacing:.04em; margin-bottom:.2rem;">Adresse</p>
                                <p style="font-size:.9rem;">Dakar, Plateau</p>
                            </div>
                        </div>
                        <div style="display:flex; gap:.9rem; align-items:flex-start;">
                            <svg style="width:1.1rem;height:1.1rem;color:var(--gold); flex-shrink:0; margin-top:.15rem;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0-9.75 6.5L2.25 6.75"/></svg>
                            <div>
                                <p style="font-size:.78rem; font-weight:600; color:var(--gray-mid); text-transform:uppercase; letter-spacing:.04em; margin-bottom:.2rem;">Email</p>
                                <a href="mailto:contact@expodakar.sn" style="font-size:.9rem; color:var(--blue-electric); text-decoration:none;">contact@expodakar.sn</a>
                            </div>
                        </div>
                        <div style="display:flex; gap:.9rem; align-items:flex-start;">
                            <svg style="width:1.1rem;height:1.1rem;color:var(--gold); flex-shrink:0; margin-top:.15rem;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 6z"/></svg>
                            <div>
                                <p style="font-size:.78rem; font-weight:600; color:var(--gray-mid); text-transform:uppercase; letter-spacing:.04em; margin-bottom:.2rem;">Téléphone</p>
                                <a href="tel:+221338001234" style="font-size:.9rem; color:var(--blue-electric); text-decoration:none;">+221 33 800 12 34</a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Formulaire --}}
                <div style="background:white; border-radius:1.75rem; padding:2.5rem; border:1px solid var(--gray-soft); box-shadow:0 4px 24px rgba(10,22,40,.06);">
                    <h3 class="font-display" style="font-size:1.4rem; margin-bottom:1.5rem;">Envoyer un message</h3>

                    @if(session('status'))
                    <div style="padding:.875rem 1rem; border-radius:1rem; background:#ECFDF5; border:1px solid #A7F3D0; margin-bottom:1.5rem;">
                        <p style="font-size:.8rem; font-weight:500; color:#059669;">{{ session('status') }}</p>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('contact.send') }}">
                        @csrf
                        <div style="display:flex; flex-direction:column; gap:1rem;">
                            <input type="text" name="nom" placeholder="Votre nom" required class="inp" value="{{ old('nom') }}">
                            <input type="email" name="email" placeholder="Votre email" required class="inp" value="{{ old('email') }}">
                            <input type="text" name="sujet" placeholder="Sujet" class="inp" value="{{ old('sujet') }}">
                            <textarea name="message" rows="5" placeholder="Votre message" required class="inp">{{ old('message') }}</textarea>
                            <button type="submit" class="btn-primary">Envoyer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
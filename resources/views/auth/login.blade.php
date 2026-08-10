<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion · ExpoDKR</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--pearl);
            color: var(--blue-night);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        .font-display { font-family: 'Instrument Serif', serif; }
        [x-cloak]     { display: none !important; }

        /* ── Page ── */
        .auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
        }
        .auth-card { width: 100%; max-width: 26rem; }

        /* ── Input ── */
        .inp {
            width: 100%;
            padding: .875rem 1rem .875rem 3rem;
            border: 1.5px solid var(--gray-soft);
            border-radius: 1rem;
            font-size: .875rem;
            font-family: 'Inter', sans-serif;
            color: var(--blue-night);
            background: white;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }
        .inp:focus {
            border-color: var(--blue-electric);
            box-shadow: 0 0 0 3px rgba(30,95,216,.12);
        }
        .inp.err {
            border-color: #DC2626;
            background: #FFF8F8;
            box-shadow: 0 0 0 3px rgba(220,38,38,.08);
        }
        .inp::placeholder { color: var(--gray-mid); }

        /* ── CTA button ── */
        .btn-primary {
            width: 100%;
            padding: .95rem 1.5rem;
            border-radius: 1rem;
            border: none;
            font-size: .9rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            color: white;
            cursor: pointer;
            background: linear-gradient(135deg, var(--blue-electric), #1248b0);
            box-shadow: 0 4px 20px rgba(30,95,216,.35);
            transition: filter .2s, transform .15s;
            letter-spacing: .015em;
        }
        .btn-primary:hover    { filter: brightness(1.1); }
        .btn-primary:active   { transform: scale(.98); }
        .btn-primary:disabled { opacity: .6; cursor: not-allowed; }

        /* ── Floating icon / action dans les inputs ── */
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: var(--gray-mid);
            display: flex;
            align-items: center;
        }
        .input-action {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--gray-mid);
            display: flex;
            align-items: center;
            background: none;
            border: none;
            padding: .25rem;
            border-radius: .5rem;
            transition: color .2s;
        }
        .input-action:hover { color: var(--blue-electric); }

        /* ── Divider ── */
        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: var(--gray-mid);
            font-size: .75rem;
            font-weight: 500;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--gray-soft);
        }

        /* ── Liens ── */
        .link-forgot {
            font-size: .75rem;
            font-weight: 600;
            color: var(--blue-electric);
            text-decoration: none;
            transition: opacity .2s;
        }
        .link-forgot:hover { opacity: .75; }

        .link-register {
            font-weight: 700;
            color: var(--blue-electric);
            text-decoration: none;
            margin-left: .25rem;
            transition: opacity .2s;
        }
        .link-register:hover { opacity: .75; }

        .link-muted {
            font-size: .75rem;
            color: var(--gray-mid);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            transition: color .2s;
        }
        .link-muted:hover { color: var(--blue-electric); }

        /* ── Boutons OAuth ── */
        .oauth-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .625rem;
            padding: .875rem;
            border-radius: 1rem;
            border: 1.5px solid var(--gray-soft);
            background: white;
            cursor: pointer;
            font-size: .82rem;
            font-weight: 600;
            color: var(--blue-night);
            font-family: 'Inter', sans-serif;
            transition: border-color .2s, box-shadow .2s;
        }
        .oauth-btn:hover {
            border-color: var(--blue-electric);
            box-shadow: 0 0 0 3px rgba(30,95,216,.08);
        }

        /* ── Checkbox "se souvenir de moi" ── */
        .checkbox-box {
            width: 1.25rem;
            height: 1.25rem;
            border-radius: .375rem;
            border: 1.5px solid var(--gray-mid);
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all .2s;
        }
        .checkbox-box.checked {
            border-color: var(--blue-electric);
            background: var(--blue-electric);
        }

        /* ── Animations ── */
        @keyframes float-up {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .animate-in { animation: float-up .5s ease forwards; }
        .delay-1 { animation-delay: .1s; opacity: 0; }
        .delay-2 { animation-delay: .2s; opacity: 0; }
        .delay-3 { animation-delay: .3s; opacity: 0; }
        .delay-4 { animation-delay: .4s; opacity: 0; }
        .spin { animation: spin 1s linear infinite; }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: var(--blue-electric); border-radius: 99px; }
    </style>
</head>
<body>

{{--
|--------------------------------------------------------------------------
| ExpoDKR – Page de connexion
| Conserve : route('login') POST, route('password.request'), route('register')
|--------------------------------------------------------------------------
--}}

<div class="auth-page" x-data="{ showPass: false, loading: false }">
    <div class="auth-card">

        {{-- Logo --}}
        <div class="animate-in" style="text-align:center; margin-bottom:2.5rem;">
            <a href="{{ route('home') }}" style="display:inline-flex; align-items:center; gap:.625rem; text-decoration:none;">
                <div style="width:2.25rem; height:2.25rem; border-radius:.75rem; background:linear-gradient(135deg,var(--blue-electric),var(--blue-night)); display:flex; align-items:center; justify-content:center;">
                    <svg style="width:1.1rem;height:1.1rem;color:white;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18"/>
                    </svg>
                </div>
                <span class="font-display" style="font-size:1.35rem; color:var(--blue-night);">
                    Expo<span style="color:var(--blue-electric);">DKR</span>
                </span>
            </a>
        </div>

        {{-- En-tête formulaire --}}
        <div class="animate-in delay-1" style="text-align:center; margin-bottom:2rem;">
            <p style="font-size:.875rem; color:var(--gray-mid);">
                Connectez-vous à votre espace ExpoDKR
            </p>
        </div>

        {{-- Flash status --}}
        @if(session('status'))
        <div class="animate-in" style="display:flex; align-items:center; gap:.75rem; padding:.875rem 1rem; border-radius:1rem; background:#ECFDF5; border:1px solid #A7F3D0; margin-bottom:1.5rem;">
            <svg style="width:1rem;height:1rem;color:#059669;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
            <p style="font-size:.8rem; font-weight:500; color:#059669;">{{ session('status') }}</p>
        </div>
        @endif

        {{-- Erreurs globales --}}
        @if($errors->any())
        <div style="display:flex; align-items:flex-start; gap:.75rem; padding:.875rem 1rem; border-radius:1rem; background:#FEF2F2; border:1px solid #FECACA; margin-bottom:1.5rem;">
            <svg style="width:1rem;height:1rem;color:#DC2626;flex-shrink:0;margin-top:.1rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
            </svg>
            <div>
                @foreach($errors->all() as $error)
                <p style="font-size:.8rem; font-weight:500; color:#DC2626;">{{ $error }}</p>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Formulaire --}}
        <form method="POST" action="{{ route('login') }}" @submit="loading = true" class="animate-in delay-2">
            @csrf

            <div style="display:flex; flex-direction:column; gap:1.1rem;">

                {{-- Email --}}
                <div>
                    <label for="email" style="display:block; font-size:.75rem; font-weight:600; color:var(--blue-night); margin-bottom:.5rem;">
                        Adresse email
                    </label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                            </svg>
                        </span>
                        <input id="email"
                               type="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="votre@email.com"
                               required
                               autofocus
                               autocomplete="username"
                               class="{{ $errors->has('email') ? 'inp err' : 'inp' }}"
                               style="padding-left:3rem;">
                    </div>
                </div>

                {{-- Mot de passe --}}
                <div>
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:.5rem;">
                        <label for="password" style="font-size:.75rem; font-weight:600; color:var(--blue-night);">
                            Mot de passe
                        </label>
                        @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="link-forgot">
                            Mot de passe oublié ?
                        </a>
                        @endif
                    </div>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                            </svg>
                        </span>
                        <input id="password"
                               :type="showPass ? 'text' : 'password'"
                               name="password"
                               placeholder="••••••••"
                               required
                               autocomplete="current-password"
                               class="{{ $errors->has('password') ? 'inp err' : 'inp' }}"
                               style="padding-left:3rem; padding-right:3rem;">
                        <button type="button"
                                class="input-action"
                                @click="showPass = !showPass"
                                :aria-label="showPass ? 'Masquer le mot de passe' : 'Afficher le mot de passe'">
                            <svg x-show="!showPass" style="width:1rem;height:1rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                            <svg x-show="showPass" x-cloak style="width:1rem;height:1rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Se souvenir de moi --}}
                <label style="display:flex; align-items:center; gap:.75rem; cursor:pointer;" x-data="{ checked: false }">
                    <input id="remember_me" type="checkbox" name="remember" x-model="checked" class="sr-only">
                    <div @click="checked = !checked"
                         class="checkbox-box"
                         :class="{ 'checked': checked }">
                        <svg x-show="checked" x-cloak style="width:.7rem;height:.7rem;color:white;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                        </svg>
                    </div>
                    <span style="font-size:.82rem; color:var(--gray-mid); user-select:none;">Se souvenir de moi</span>
                </label>

                {{-- Bouton connexion --}}
                <button type="submit" class="btn-primary" :disabled="loading">
                    <span x-show="!loading" style="display:flex; align-items:center; justify-content:center; gap:.625rem;">
                      
                        Se connecter
                    </span>
                    <span x-show="loading" x-cloak style="display:flex; align-items:center; justify-content:center; gap:.625rem;">
                   
                        Connexion en cours…
                    </span>
                </button>

            </div>
        </form>

        {{-- Séparateur --}}
        <div class="divider animate-in delay-3" style="margin:1.75rem 0;">ou continuer avec</div>

        {{-- Auth sociale --}}
        <div class="animate-in delay-3" style="display:grid; grid-template-columns:1fr 1fr; gap:.75rem; margin-bottom:2rem;">

            <button type="button" class="oauth-btn">
                <svg style="width:1.1rem;height:1.1rem;" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Google
            </button>

            <button type="button" class="oauth-btn">
                <svg style="width:1.1rem;height:1.1rem;" fill="#1877F2" viewBox="0 0 24 24">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
                Facebook
            </button>

        </div>

        {{-- Lien inscription --}}
        <div class="animate-in delay-4" style="text-align:center; padding:1.25rem; border-radius:1.25rem; background:white; border:1.5px solid var(--gray-soft);">
            <p style="font-size:.82rem; color:var(--gray-mid);">
                Pas encore de compte ?
                <a href="{{ route('register') }}" class="link-register">
                    Créer un compte gratuit 
                </a>
            </p>
        </div>

        {{-- Lien retour accueil --}}
        <div style="text-align:center; margin-top:1.5rem;">
            <a href="{{ route('home') }}" class="link-muted">
                <svg style="width:.8rem;height:.8rem;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                Retour à l'accueil
            </a>
        </div>

    </div>
</div>

</body>
</html>
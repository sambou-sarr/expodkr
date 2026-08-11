<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte · ExpoDKR</title>

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

        * { box-sizing:border-box; margin:0; padding:0; }
        body {
            font-family:'Inter',sans-serif;
            background:var(--blue-night);
            color:var(--blue-night);
            min-height:100vh;
            -webkit-font-smoothing:antialiased;
        }
        .font-display { font-family:'Instrument Serif',serif; }
        [x-cloak]     { display:none!important; }

        /* Grille déco */
        .hero-grid {
            background-image:
                linear-gradient(rgba(201,168,76,.1) 1px,transparent 1px),
                linear-gradient(90deg,rgba(201,168,76,.1) 1px,transparent 1px);
            background-size:50px 50px;
        }

        /* Input */
        .inp {
            width:100%;
            padding:.875rem 1rem .875rem 3rem;
            border:1.5px solid var(--gray-soft);
            border-radius:1rem;
            font-size:.875rem;
            font-family:'Inter',sans-serif;
            color:var(--blue-night);
            background:white;
            outline:none;
            transition:border-color .2s,box-shadow .2s;
        }
        .inp:focus {
            border-color:var(--blue-electric);
            box-shadow:0 0 0 3px rgba(30,95,216,.12);
        }
        .inp.err {
            border-color:#DC2626;
            background:#FFF8F8;
            box-shadow:0 0 0 3px rgba(220,38,38,.08);
        }
        .inp::placeholder { color:var(--gray-mid); }
        .inp-sm { padding:.875rem 1rem; }

        /* CTA */
        .btn-primary {
            width:100%; padding:.95rem 1.5rem;
            border-radius:1rem; border:none;
            font-size:.9rem; font-weight:700;
            font-family:'Inter',sans-serif;
            color:white; cursor:pointer;
            background:linear-gradient(135deg,var(--blue-electric),#1248b0);
            box-shadow:0 4px 20px rgba(30,95,216,.35);
            transition:filter .2s,transform .15s;
            letter-spacing:.015em;
        }
        .btn-primary:hover  { filter:brightness(1.1); }
        .btn-primary:active { transform:scale(.98); }
        .btn-primary:disabled { opacity:.6; cursor:not-allowed; }

        /* Input icon wrapper */
        .inp-wrap  { position:relative; }
        .inp-icon  { position:absolute; left:1rem; top:50%; transform:translateY(-50%); pointer-events:none; color:var(--gray-mid); display:flex; align-items:center; }
        .inp-action{ position:absolute; right:1rem; top:50%; transform:translateY(-50%); cursor:pointer; color:var(--gray-mid); background:none; border:none; padding:.25rem; border-radius:.5rem; transition:color .2s; display:flex; align-items:center; }
        .inp-action:hover { color:var(--blue-electric); }

        /* Divider */
        .divider { display:flex; align-items:center; gap:1rem; color:var(--gray-mid); font-size:.75rem; font-weight:500; }
        .divider::before,.divider::after { content:''; flex:1; height:1px; background:var(--gray-soft); }

        /* Password strength */
        .strength-bar { height:.3rem; border-radius:99px; transition:all .3s; }

        /* Animations */
        @keyframes float-up { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:none} }
        .anim { animation:float-up .5s ease forwards; opacity:0; }
        .d1  { animation-delay:.1s; }
        .d2  { animation-delay:.2s; }
        .d3  { animation-delay:.25s; }
        .d4  { animation-delay:.3s; }

        @keyframes spin  { to{transform:rotate(360deg); } }
        @keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:.4;} }

        ::-webkit-scrollbar { width:4px; }
        ::-webkit-scrollbar-thumb { background:var(--blue-electric); border-radius:99px; }
    </style>
</head>
<body>

{{--
|--------------------------------------------------------------------------
| ExpoDKR – Page d'inscription (premium redesign)
| Conserve : route('register') POST, route('login')
| Champs : name, prenom, email, telephone, password, password_confirmation
|--------------------------------------------------------------------------
--}}

<div style="display:grid; min-height:100vh;"
     class="lg:grid-cols-2"
     x-data="{
         showPass:    false,
         showConfirm: false,
         loading:     false,
         password:    '',
         strength:    0,
         strengthLabel: '',
         strengthColor: '',

         checkStrength(val) {
             this.password = val;
             let s = 0;
             if (val.length >= 8)          s++;
             if (/[A-Z]/.test(val))        s++;
             if (/[0-9]/.test(val))        s++;
             if (/[^A-Za-z0-9]/.test(val)) s++;
             this.strength = s;
             const map = [
                 ['', ''],
                 ['Faible',  '#DC2626'],
                 ['Moyen',   '#D97706'],
                 ['Fort',    '#2563EB'],
                 ['Excellent','#059669'],
             ];
             this.strengthLabel = map[s][0];
             this.strengthColor = map[s][1];
         }
     }">


    <div style="background:var(--pearl); display:flex; flex-direction:column; justify-content:center; align-items:center; padding:2rem 1.5rem; min-height:100vh; overflow-y:auto;">

        <div style="width:100%; max-width:27rem;">

            {{-- Logo mobile --}}
            <div class="lg:hidden anim" style="text-align:center; margin-bottom:2rem;">
                <a href="{{ route('home') }}" style="display:inline-flex; align-items:center; gap:.625rem; text-decoration:none;">
                    <div style="width:2.25rem; height:2.25rem; border-radius:.75rem; background:linear-gradient(135deg,var(--blue-electric),var(--blue-night)); display:flex; align-items:center; justify-content:center;">
                        <svg style="width:1.1rem;height:1.1rem;color:white;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18"/></svg>
                    </div>
                    <span class="font-display" style="font-size:1.35rem; color:var(--blue-night);">Expo<span style="color:var(--blue-electric);">DKR</span></span>
                </a>
            </div>

            {{-- En-tête --}}
            <div class="anim d1" style="margin-bottom:2rem;">
                <h1 style="font-size:1.65rem; font-weight:800; color:var(--blue-night); letter-spacing:-.02em; margin-bottom:.4rem;">
                    Créer un compte
                </h1>
                <p style="font-size:.875rem; color:var(--gray-mid);">
                    Rejoignez ExpoDKR gratuitement en quelques secondes
                </p>
            </div>

            {{-- Erreurs globales --}}
            @if($errors->any())
            <div class="anim" style="display:flex; align-items:flex-start; gap:.75rem; padding:1rem; border-radius:1.25rem; background:#FEF2F2; border:1px solid #FECACA; margin-bottom:1.5rem;">
                <svg style="width:1rem;height:1rem;color:#DC2626;flex-shrink:0;margin-top:.1rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                <div>
                    @foreach($errors->all() as $error)
                    <p style="font-size:.8rem; font-weight:500; color:#DC2626; margin-bottom:.2rem;">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
            @endif


            {{-- ── FORMULAIRE ── --}}
            <form method="POST"
                  action="{{ route('register') }}"
                  @submit="loading=true"
                  class="anim d2">
                @csrf

                <div style="display:flex; flex-direction:column; gap:1rem;">

                    {{-- Nom + Prénom côte à côte --}}
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:.875rem;">

                        {{-- Nom --}}
                        <div>
                            <label for="name" style="display:block; font-size:.75rem; font-weight:600; color:var(--blue-night); margin-bottom:.5rem;">
                                Nom <span style="color:#DC2626;">*</span>
                            </label>
                            <div class="inp-wrap">
                                <span class="inp-icon">
                                    <svg style="width:.9rem;height:.9rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                                </span>
                                <input type="text"
                                       id="name"
                                       name="name"
                                       value="{{ old('name') }}"
                                       placeholder="Diallo"
                                       required autofocus autocomplete="family-name"
                                       class="{{ $errors->has('name') ? 'inp err' : 'inp' }}">
                            </div>
                            @error('name')
                            <p style="font-size:.7rem; color:#DC2626; margin-top:.35rem;">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Prénom --}}
                        <div>
                            <label for="prenom" style="display:block; font-size:.75rem; font-weight:600; color:var(--blue-night); margin-bottom:.5rem;">
                                Prénom <span style="color:#DC2626;">*</span>
                            </label>
                            <div class="inp-wrap">
                                <span class="inp-icon">
                                    <svg style="width:.9rem;height:.9rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                                </span>
                                <input type="text"
                                       id="prenom"
                                       name="prenom"
                                       value="{{ old('prenom') }}"
                                       placeholder="Aminata"
                                       required autocomplete="given-name"
                                       class="{{ $errors->has('prenom') ? 'inp err' : 'inp' }}">
                            </div>
                            @error('prenom')
                            <p style="font-size:.7rem; color:#DC2626; margin-top:.35rem;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" style="display:block; font-size:.75rem; font-weight:600; color:var(--blue-night); margin-bottom:.5rem;">
                            Adresse email <span style="color:#DC2626;">*</span>
                        </label>
                        <div class="inp-wrap">
                            <span class="inp-icon">
                                <svg style="width:.9rem;height:.9rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                            </span>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="votre@email.com"
                                   required autocomplete="email"
                                   class="{{ $errors->has('email') ? 'inp err' : 'inp' }}">
                        </div>
                        @error('email')
                        <p style="font-size:.7rem; color:#DC2626; margin-top:.35rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Téléphone --}}
                    <div>
                        <label for="telephone" style="display:block; font-size:.75rem; font-weight:600; color:var(--blue-night); margin-bottom:.5rem;">
                            Téléphone <span style="color:#DC2626;">*</span>
                        </label>
                        <div class="inp-wrap">
                            <span class="inp-icon">
                                <svg style="width:.9rem;height:.9rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 6z"/></svg>
                            </span>
                            <input type="tel"
                                   id="telephone"
                                   name="telephone"
                                   value="{{ old('telephone') }}"
                                   placeholder="+221 77 000 00 00"
                                   required autocomplete="tel"
                                   class="{{ $errors->has('telephone') ? 'inp err' : 'inp' }}">
                        </div>
                        @error('telephone')
                        <p style="font-size:.7rem; color:#DC2626; margin-top:.35rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Mot de passe --}}
                    <div>
                        <label for="password" style="display:block; font-size:.75rem; font-weight:600; color:var(--blue-night); margin-bottom:.5rem;">
                            Mot de passe <span style="color:#DC2626;">*</span>
                        </label>
                        <div class="inp-wrap">
                            <span class="inp-icon">
                                <svg style="width:.9rem;height:.9rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                            </span>
                            <input :type="showPass ? 'text' : 'password'"
                                   id="password"
                                   name="password"
                                   placeholder="••••••••"
                                   @input="checkStrength($event.target.value)"
                                   required autocomplete="new-password"
                                   style="padding-right:3rem;"
                                   class="{{ $errors->has('password') ? 'inp err' : 'inp' }}">
                            <button type="button" class="inp-action" @click="showPass=!showPass" :aria-label="showPass ? 'Masquer' : 'Afficher'">
                                <svg x-show="!showPass" style="width:.9rem;height:.9rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                <svg x-show="showPass" x-cloak style="width:.9rem;height:.9rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                            </button>
                        </div>

                        {{-- Barre de force --}}
                        <div x-show="password.length > 0" x-cloak style="margin-top:.625rem;">
                            <div style="display:flex; gap:.25rem; margin-bottom:.35rem;">
                                @for($i=1;$i<=4;$i++)
                                <div class="strength-bar" style="flex:1;"
                                     :style="strength >= {{ $i }}
                                         ? 'background:' + strengthColor
                                         : 'background:var(--gray-soft)'">
                                </div>
                                @endfor
                            </div>
                            <p style="font-size:.7rem; font-weight:600;" :style="'color:' + strengthColor" x-text="strengthLabel"></p>
                        </div>

                        @error('password')
                        <p style="font-size:.7rem; color:#DC2626; margin-top:.35rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirmer mot de passe --}}
                    <div>
                        <label for="password_confirmation" style="display:block; font-size:.75rem; font-weight:600; color:var(--blue-night); margin-bottom:.5rem;">
                            Confirmer le mot de passe <span style="color:#DC2626;">*</span>
                        </label>
                        <div class="inp-wrap">
                            <span class="inp-icon">
                                <svg style="width:.9rem;height:.9rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                            </span>
                            <input :type="showConfirm ? 'text' : 'password'"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   placeholder="••••••••"
                                   required autocomplete="new-password"
                                   style="padding-right:3rem;"
                                   class="{{ $errors->has('password_confirmation') ? 'inp err' : 'inp' }}">
                            <button type="button" class="inp-action" @click="showConfirm=!showConfirm" aria-label="Afficher/masquer">
                                <svg x-show="!showConfirm" style="width:.9rem;height:.9rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                <svg x-show="showConfirm" x-cloak style="width:.9rem;height:.9rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                            </button>
                        </div>
                        @error('password_confirmation')
                        <p style="font-size:.7rem; color:#DC2626; margin-top:.35rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- CGU --}}
                    <label style="display:flex; align-items:flex-start; gap:.75rem; cursor:pointer; padding:.875rem; border-radius:1rem; background:var(--pearl); border:1.5px solid var(--gray-soft); transition:border-color .2s;"
                           x-data="{ checked:false }"
                           onmouseover="this.style.borderColor='var(--blue-electric)'" onmouseout="this.style.borderColor='var(--gray-soft)'">
                        <div style="position:relative; flex-shrink:0; margin-top:.05rem;">
                            <input type="checkbox" name="cgv" required class="sr-only" x-model="checked">
                            <div @click="checked=!checked"
                                 style="width:1.25rem; height:1.25rem; border-radius:.375rem; border:1.5px solid; display:flex; align-items:center; justify-content:center; transition:all .2s; cursor:pointer;"
                                 :style="checked ? 'border-color:var(--blue-electric); background:var(--blue-electric);' : 'border-color:var(--gray-mid); background:white;'">
                                <svg x-show="checked" x-cloak style="width:.7rem;height:.7rem;color:white;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                            </div>
                        </div>
                        <p style="font-size:.78rem; color:var(--gray-mid); line-height:1.6; user-select:none;">
                            J'accepte les
                            <a href="#" style="color:var(--blue-electric); text-decoration:underline; font-weight:600;">conditions d'utilisation</a>
                            et la
                            <a href="#" style="color:var(--blue-electric); text-decoration:underline; font-weight:600;">politique de confidentialité</a>
                            d'ExpoDKR.
                        </p>
                    </label>

                    {{-- Bouton S'inscrire --}}
                    <button type="submit" class="btn-primary" :disabled="loading">
                        <span x-show="!loading" style="display:flex; align-items:center; justify-content:center; gap:.625rem;">
                            Créer mon compte gratuitement
                        </span>
                        <span x-show="loading" x-cloak style="display:flex; align-items:center; justify-content:center; gap:.625rem;">
                                <circle style="opacity:.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path style="opacity:.75;" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Création en cours…
                        </span>
                    </button>

                </div>
            </form>


            {{-- Séparateur --}}
            <div class="divider anim d3" style="margin:1.75rem 0;">ou s'inscrire avec</div>

            {{-- Boutons sociaux --}}
            <div class="anim d3" style="display:grid; grid-template-columns:1fr 1fr; gap:.75rem; margin-bottom:2rem;">
                <button type="button"
                        style="display:flex; align-items:center; justify-content:center; gap:.625rem; padding:.875rem; border-radius:1rem; border:1.5px solid var(--gray-soft); background:white; cursor:pointer; font-size:.82rem; font-weight:600; color:var(--blue-night); font-family:'Inter',sans-serif; transition:all .2s;"
                        onmouseover="this.style.borderColor='var(--blue-electric)'; this.style.boxShadow='0 0 0 3px rgba(30,95,216,.08)'" onmouseout="this.style.borderColor='var(--gray-soft)'; this.style.boxShadow='none'">
                    <svg style="width:1.1rem;height:1.1rem;" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Google
                </button>
                <button type="button"
                        style="display:flex; align-items:center; justify-content:center; gap:.625rem; padding:.875rem; border-radius:1rem; border:1.5px solid var(--gray-soft); background:white; cursor:pointer; font-size:.82rem; font-weight:600; color:var(--blue-night); font-family:'Inter',sans-serif; transition:all .2s;"
                        onmouseover="this.style.borderColor='var(--blue-electric)'; this.style.boxShadow='0 0 0 3px rgba(30,95,216,.08)'" onmouseout="this.style.borderColor='var(--gray-soft)'; this.style.boxShadow='none'">
                    <svg style="width:1.1rem;height:1.1rem;" fill="#1877F2" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    Facebook
                </button>
            </div>

            {{-- Déjà inscrit --}}
            <div class="anim d4" style="text-align:center; padding:1.25rem; border-radius:1.25rem; background:white; border:1.5px solid var(--gray-soft);">
                <p style="font-size:.82rem; color:var(--gray-mid);">
                    Déjà un compte ?
                    <a href="{{ route('login') }}"
                       style="font-weight:700; color:var(--blue-electric); text-decoration:none; margin-left:.3rem; transition:opacity .2s;"
                       onmouseover="this.style.opacity='.75'" onmouseout="this.style.opacity='1'">
                        Se connecter →
                    </a>
                </p>
            </div>

            {{-- Retour accueil --}}
            <div style="text-align:center; margin-top:1.5rem;">
                <a href="{{ route('home') }}"
                   style="font-size:.75rem; color:var(--gray-mid); text-decoration:none; display:inline-flex; align-items:center; gap:.4rem; transition:color .2s;"
                   onmouseover="this.style.color='var(--blue-electric)'" onmouseout="this.style.color='var(--gray-mid)'">
                    <svg style="width:.8rem;height:.8rem;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                    Retour à l'accueil
                </a>
            </div>

        </div>
    </div>
    {{-- /colonne droite --}}

</div>

<style>
@keyframes spin  { to{transform:rotate(360deg);} }
@keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:.4;} }
</style>

</body>
</html>
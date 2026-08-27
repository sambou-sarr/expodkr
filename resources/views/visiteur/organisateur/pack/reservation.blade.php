<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation · ExpoDKR</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --blue-night: #0A1628;
            --blue-electric: #1E5FD8;
            --gold: #C9A84C;
            --gold-light: #E8C96A;
            --pearl: #F7F8FC;
            --gray-soft: #EEF0F6;
            --gray-mid: #8892A4;
            --ink: #1C2733;
        }
        body { font-family:'Inter',sans-serif; background:var(--pearl); color:var(--blue-night); -webkit-font-smoothing:antialiased; overflow-x:hidden; }
        .font-display { font-family:'Instrument Serif',serif; }
        [x-cloak] { display:none !important; }

        .section-label { font-size:.7rem; font-weight:600; letter-spacing:.05em; text-transform:uppercase; color:var(--gray-mid); margin-bottom:.75rem; }

        .card { background:white; border-radius:1.25rem; border:1px solid var(--gray-soft); box-shadow:0 1px 3px rgba(10,22,40,.04); }

        .step-badge {
            width:1.7rem; height:1.7rem; border-radius:50%; display:flex; align-items:center; justify-content:center;
            font-size:.75rem; font-weight:700; flex-shrink:0;
            background:var(--blue-electric); color:white;
        }
        .step-badge-off { background:var(--gray-soft); color:var(--gray-mid); }

        .field-label { font-size:.78rem; font-weight:600; color:var(--ink); margin-bottom:.4rem; display:block; }
        .field-input, .field-select, .field-textarea {
            width:100%; font-size:.85rem; padding:.7rem .9rem; border-radius:.6rem;
            border:1px solid var(--gray-soft); background:var(--pearl); color:var(--ink);
            transition: border-color .2s ease, background .2s ease;
        }
        .field-input:focus, .field-select:focus, .field-textarea:focus {
            outline:none; border-color:var(--blue-electric); background:white;
        }
        .field-hint { font-size:.72rem; color:var(--gray-mid); margin-top:.35rem; }
        .field-error { font-size:.72rem; color:#DC2626; margin-top:.35rem; }

        .pack-option {
            display:flex; align-items:center; justify-content:space-between; gap:1rem;
            padding:.9rem 1rem; border-radius:.75rem; border:1.5px solid var(--gray-soft);
            cursor:pointer; transition: border-color .2s ease, background .2s ease;
        }
        .pack-option:hover { border-color:#DCE1EC; }
        .pack-option.is-active { border-color:var(--blue-electric); background:#F3F7FF; }

        .summary-row { display:flex; justify-content:space-between; align-items:center; font-size:.83rem; padding:.6rem 0; border-bottom:1px solid var(--gray-soft); }
        .summary-row:last-of-type { border-bottom:none; }

        .cta-solid {
            display:block; width:100%; text-align:center; padding:.9rem; border-radius:.7rem;
            font-size:.86rem; font-weight:600; text-decoration:none; border:none; cursor:pointer;
            background:var(--blue-electric); color:white; transition: background .2s ease, opacity .2s ease;
        }
        .cta-solid:hover { background:#1248b0; }
        .cta-solid:disabled { opacity:.5; cursor:not-allowed; }

        .pay-option {
            display:flex; align-items:center; gap:.7rem; padding:.75rem .9rem; border-radius:.65rem;
            border:1.5px solid var(--gray-soft); cursor:pointer; transition: border-color .2s ease, background .2s ease;
        }
        .pay-option.is-active { border-color:var(--blue-electric); background:#F3F7FF; }

        .reveal { opacity:0; transform:translateY(14px); transition:opacity .5s ease, transform .5s ease; }
        .reveal.visible { opacity:1; transform:translateY(0); }
    </style>
</head>
<body x-data="reservation()">

    {{-- Header simplifié --}}
    <header style="background:var(--blue-night);">
        <div style="max-width:76rem; margin:0 auto; padding:1.1rem 1.5rem; display:flex; align-items:center; justify-content:space-between;">
            <a href="{{ route('home') }}" style="display:flex; align-items:center; gap:.6rem; text-decoration:none;">
                <img src="https://res.cloudinary.com/dstbqtuxm/image/upload/v1782085416/ChatGPT_Image_Jun_21__2026__07_24_51_PM-removebg-preview_zi77k0.png" alt="Logo ExpoDakar" style="height:2.5rem; width:auto;">
                <span class="font-display" style="font-size:1.3rem; color:white;">ExpoDakar</span>
            </a>
            <a href="{{ route('tarifs') }}" style="font-size:.8rem; color:rgba(255,255,255,.6); text-decoration:none;">← Retour aux tarifs</a>
        </div>
    </header>

    {{-- Fil d'ariane / étapes --}}
    <div style="background:white; border-bottom:1px solid var(--gray-soft);">
        <div style="max-width:60rem; margin:0 auto; padding:1.1rem 1.5rem; display:flex; align-items:center; gap:.6rem;">
            <div style="display:flex; align-items:center; gap:.5rem;">
                <div class="step-badge" :class="step >= 1 ? '' : 'step-badge-off'">1</div>
                <span style="font-size:.8rem; font-weight:600;" :style="step===1 ? '' : 'color:var(--gray-mid)'">Votre pack</span>
            </div>
            <div style="flex:1; height:1px; background:var(--gray-soft);"></div>
            <div style="display:flex; align-items:center; gap:.5rem;">
                <div class="step-badge" :class="step >= 2 ? '' : 'step-badge-off'">2</div>
                <span style="font-size:.8rem; font-weight:600;" :style="step===2 ? '' : 'color:var(--gray-mid)'">Vos informations</span>
            </div>
            <div style="flex:1; height:1px; background:var(--gray-soft);"></div>
            <div style="display:flex; align-items:center; gap:.5rem;">
                <div class="step-badge" :class="step >= 3 ? '' : 'step-badge-off'">3</div>
                <span style="font-size:.8rem; font-weight:600;" :style="step===3 ? '' : 'color:var(--gray-mid)'">Paiement</span>
            </div>
        </div>
    </div>

    <div style="max-width:60rem; margin:0 auto; padding:3rem 1.5rem 5rem;">
        <div style="display:grid; grid-template-columns:1fr; gap:2rem;">
            <style>@media(min-width:900px){.reservation-grid{grid-template-columns:1.6fr 1fr!important;align-items:start;}}</style>

            <div class="reservation-grid" style="display:grid; grid-template-columns:1fr; gap:2rem;">

                {{-- Colonne formulaire --}}
                <div>

                    {{-- ÉTAPE 1 : choix du pack --}}
                    <div x-show="step === 1" x-cloak class="card" style="padding:1.75rem;">
                        <p class="section-label">Étape 1</p>
                        <h2 class="font-display" style="font-size:1.5rem; margin-bottom:1.25rem;">Confirmez votre pack</h2>

                        <div style="display:flex; flex-direction:column; gap:.75rem;">
                            <template x-for="pack in packs" :key="pack.id">
                                <div class="pack-option" :class="selectedPack.id === pack.id ? 'is-active' : ''" @click="selectedPack = pack">
                                    <div>
                                        <p style="font-size:.88rem; font-weight:600;" x-text="pack.name"></p>
                                        <p style="font-size:.75rem; color:var(--gray-mid);" x-text="pack.tagline"></p>
                                    </div>
                                    <p class="font-display" style="font-size:1.1rem; white-space:nowrap;" x-text="formatFcfa(pack.price) + ' FCFA'"></p>
                                </div>
                            </template>
                        </div>

                        <div style="margin-top:1.5rem;">
                            <label class="field-label">Événement concerné</label>
                            <select class="field-select" x-model="form.event_id">
                                <option value="">— Sélectionner un événement —</option>
                                <option value="1">Salon International de l'Industrie Dakar 2026</option>
                                <option value="2">Forum Économique Sénégal-Diaspora</option>
                                <option value="3">Créer un nouvel événement</option>
                            </select>
                            <p class="field-hint">Le pack s'applique à l'événement que vous choisissez ici.</p>
                        </div>

                        <button type="button" class="cta-solid" style="margin-top:1.75rem;" @click="step = 2">Continuer</button>
                    </div>

                    {{-- ÉTAPE 2 : informations exposant --}}
                    <div x-show="step === 2" x-cloak class="card" style="padding:1.75rem;">
                        <p class="section-label">Étape 2</p>
                        <h2 class="font-display" style="font-size:1.5rem; margin-bottom:1.25rem;">Vos informations</h2>

                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                            <div style="grid-column:1 / -1;">
                                <label class="field-label">Nom de l'entreprise / organisation</label>
                                <input type="text" class="field-input" x-model="form.company_name" placeholder="Ex. Atlantic Trading SARL">
                            </div>

                            <div>
                                <label class="field-label">Nom du contact</label>
                                <input type="text" class="field-input" x-model="form.contact_name" placeholder="Prénom et nom">
                            </div>

                            <div>
                                <label class="field-label">Téléphone</label>
                                <input type="tel" class="field-input" x-model="form.phone" placeholder="+221 77 000 00 00">
                            </div>

                            <div style="grid-column:1 / -1;">
                                <label class="field-label">Email</label>
                                <input type="email" class="field-input" x-model="form.email" placeholder="contact@entreprise.sn">
                            </div>

                            <div style="grid-column:1 / -1;">
                                <label class="field-label">Description de votre activité</label>
                                <textarea class="field-textarea" rows="3" x-model="form.description" placeholder="Quelques lignes sur votre entreprise, vos produits ou services…"></textarea>
                            </div>

                            <div style="grid-column:1 / -1;">
                                <label class="field-label">NINEA / RCCM (optionnel)</label>
                                <input type="text" class="field-input" x-model="form.tax_id" placeholder="Pour la facturation">
                            </div>
                        </div>

                        <div style="display:flex; gap:.9rem; margin-top:1.75rem;">
                            <button type="button" class="cta-solid" style="background:white; color:var(--blue-night); border:1px solid var(--gray-soft);" @click="step = 1">Retour</button>
                            <button type="button" class="cta-solid" :disabled="!canContinueStep2" @click="step = 3">Continuer</button>
                        </div>
                    </div>

                    {{-- ÉTAPE 3 : paiement --}}
                    <div x-show="step === 3" x-cloak class="card" style="padding:1.75rem;">
                        <p class="section-label">Étape 3</p>
                        <h2 class="font-display" style="font-size:1.5rem; margin-bottom:1.25rem;">Mode de paiement</h2>

                        <div style="display:flex; flex-direction:column; gap:.75rem;">
                            <label class="pay-option" :class="form.payment_method === 'orange_money' ? 'is-active' : ''">
                                <input type="radio" name="payment_method" value="orange_money" x-model="form.payment_method" style="accent-color:var(--blue-electric);">
                                <div>
                                    <p style="font-size:.85rem; font-weight:600;">Orange Money</p>
                                    <p style="font-size:.72rem; color:var(--gray-mid);">Paiement mobile instantané</p>
                                </div>
                            </label>

                            <label class="pay-option" :class="form.payment_method === 'wave' ? 'is-active' : ''">
                                <input type="radio" name="payment_method" value="wave" x-model="form.payment_method" style="accent-color:var(--blue-electric);">
                                <div>
                                    <p style="font-size:.85rem; font-weight:600;">Wave</p>
                                    <p style="font-size:.72rem; color:var(--gray-mid);">Paiement mobile instantané</p>
                                </div>
                            </label>

                            <label class="pay-option" :class="form.payment_method === 'virement' ? 'is-active' : ''">
                                <input type="radio" name="payment_method" value="virement" x-model="form.payment_method" style="accent-color:var(--blue-electric);">
                                <div>
                                    <p style="font-size:.85rem; font-weight:600;">Virement bancaire</p>
                                    <p style="font-size:.72rem; color:var(--gray-mid);">Facture envoyée par email, activation après réception</p>
                                </div>
                            </label>
                        </div>

                        <label style="display:flex; align-items:flex-start; gap:.6rem; margin-top:1.5rem;">
                            <input type="checkbox" x-model="form.accept_terms" style="margin-top:.2rem; accent-color:var(--blue-electric);">
                            <span style="font-size:.78rem; color:var(--gray-mid); line-height:1.5;">
                                J'accepte les <a href="#" style="color:var(--blue-electric); font-weight:600;">conditions générales</a> et confirme l'exactitude des informations fournies.
                            </span>
                        </label>

                        <div style="display:flex; gap:.9rem; margin-top:1.75rem;">
                            <button type="button" class="cta-solid" style="background:white; color:var(--blue-night); border:1px solid var(--gray-soft);" @click="step = 2">Retour</button>
                            <button type="button" class="cta-solid" :disabled="!canSubmit" @click="submitReservation()">
                                <span x-show="!submitting">Confirmer et payer</span>
                                <span x-show="submitting" x-cloak>Traitement…</span>
                            </button>
                        </div>
                    </div>

                    {{-- Confirmation --}}
                    <div x-show="step === 4" x-cloak class="card" style="padding:2.5rem 1.75rem; text-align:center;">
                        <div style="width:3.5rem; height:3.5rem; border-radius:50%; background:#E7F6EF; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
                            <svg style="width:1.8rem;height:1.8rem;color:#0F7A54;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                        </div>
                        <h2 class="font-display" style="font-size:1.6rem; margin-bottom:.6rem;">Réservation confirmée</h2>
                        <p style="font-size:.85rem; color:var(--gray-mid); max-width:26rem; margin:0 auto;">
                            Un email de confirmation a été envoyé à <span x-text="form.email" style="font-weight:600; color:var(--ink);"></span>. Votre pack sera actif dès validation du paiement.
                        </p>
                        <a href="{{ route('home') }}" class="cta-solid" style="display:inline-block; width:auto; margin-top:1.75rem; padding-left:1.5rem; padding-right:1.5rem;">Retour à l'accueil</a>
                    </div>

                </div>

                {{-- Colonne récapitulatif --}}
                <div class="card" style="padding:1.5rem; position:sticky; top:1.5rem;">
                    <p class="section-label">Récapitulatif</p>
                    <p class="font-display" style="font-size:1.2rem; margin-bottom:1rem;" x-text="selectedPack.name"></p>

                    <div class="summary-row">
                        <span style="color:var(--gray-mid);">Prix du pack</span>
                        <span x-text="formatFcfa(selectedPack.price) + ' FCFA'"></span>
                    </div>
                    <div class="summary-row" x-show="selectedPack.oldPrice">
                        <span style="color:var(--gray-mid);">Remise</span>
                        <span style="color:#0F7A54;" x-text="'-' + formatFcfa(selectedPack.oldPrice - selectedPack.price) + ' FCFA'"></span>
                    </div>
                    <div class="summary-row">
                        <span style="color:var(--gray-mid);">Événement</span>
                        <span x-text="eventLabel"></span>
                    </div>

                    <div style="margin-top:.75rem; padding-top:.9rem; border-top:1.5px solid var(--gray-soft); display:flex; justify-content:space-between; align-items:baseline;">
                        <span style="font-size:.85rem; font-weight:600;">Total à payer</span>
                        <span class="font-display" style="font-size:1.6rem;" x-text="formatFcfa(selectedPack.price) + ' FCFA'"></span>
                    </div>

                    <p style="font-size:.72rem; color:var(--gray-mid); margin-top:1rem; line-height:1.5;">
                        Paiement unique, sans engagement mensuel. Facture disponible dans votre espace après confirmation.
                    </p>
                </div>

            </div>
        </div>
    </div>

    <script>
        function reservation() {
            return {
                step: 1,
                submitting: false,
                packs: [
                    { id: 'essentiel',      name: 'Essentiel',       tagline: 'Être présent et visible',              price: 75000,   oldPrice: 100000 },
                    { id: 'professionnel',  name: 'Professionnel',   tagline: 'Générer des contacts qualifiés',       price: 150000,  oldPrice: 200000 },
                    { id: 'premium',        name: 'Premium',         tagline: 'Maximiser sa visibilité',              price: 275000,  oldPrice: 500000 },
                    { id: 'vip',            name: 'Partenaire VIP',  tagline: 'Devenir un acteur majeur',             price: 1500000, oldPrice: 2000000 },
                ],
                selectedPack: null,
                form: {
                    event_id: '',
                    company_name: '',
                    contact_name: '',
                    phone: '',
                    email: '',
                    description: '',
                    tax_id: '',
                    payment_method: '',
                    accept_terms: false,
                },
                init() {
                    const params = new URLSearchParams(window.location.search);
                    const requested = params.get('pack');
                    this.selectedPack = this.packs.find(p => p.id === requested) || this.packs[1];
                },
                get eventLabel() {
                    const labels = { '1': "Salon International de l'Industrie Dakar 2026", '2': 'Forum Économique Sénégal-Diaspora', '3': 'Nouvel événement' };
                    return labels[this.form.event_id] || '— à sélectionner —';
                },
                get canContinueStep2() {
                    return this.form.company_name && this.form.contact_name && this.form.phone && this.form.email;
                },
                get canSubmit() {
                    return this.form.payment_method && this.form.accept_terms;
                },
                formatFcfa(n) {
                    return new Intl.NumberFormat('fr-FR').format(n);
                },
                submitReservation() {
                    if (!this.canSubmit) return;
                    this.submitting = true;
                    // TODO: remplacer par un appel réel, ex. via fetch() vers une route Laravel
                    // (route('reservations.store')) avec this.selectedPack.id et this.form
                    setTimeout(() => {
                        this.submitting = false;
                        this.step = 4;
                    }, 900);
                }
            }
        }
    </script>

</body>
</html>
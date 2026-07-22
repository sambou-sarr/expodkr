@extends('admin.layout.header')

@section('title', 'Ajouter un exposant')
@section('subtitle', 'Créer un nouvel exposant ExpoDakar')

@section('content')

{{--
|--------------------------------------------------------------------------
| ExpoDKR Admin – Formulaire création exposant (premium redesign)
| Routes, variables Blade et logique 100% conservés
| route('exposants.store'), route('exposants.index')
|--------------------------------------------------------------------------
--}}

<div class="p-6 lg:p-8" x-data="exposantForm()">

    {{-- ══════════════════════════════════════════════════════════════
         HEADER PAGE
         ══════════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            {{-- Breadcrumb --}}
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('exposants.index') }}"
                   class="flex items-center gap-1.5 text-xs font-medium text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                    </svg>
                    Exposants
                </a>
                <span class="text-slate-200">/</span>
                <span class="text-xs font-semibold text-slate-600">Ajouter</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 leading-tight">Ajouter un exposant</h1>
            <p class="text-slate-400 text-sm mt-0.5">Remplissez les informations du nouvel exposant</p>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════════════
         ERREURS DE VALIDATION
         ══════════════════════════════════════════════════════════════ --}}
    @if($errors->any())
    <div class="mb-6 p-4 rounded-2xl border flex items-start gap-3"
         style="background:#FEF2F2; border-color:#FECACA;">
        <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
        </svg>
        <div>
            <p class="text-sm font-semibold text-red-700 mb-1">Veuillez corriger les erreurs suivantes :</p>
            <ul class="text-sm text-red-600 space-y-0.5">
                @foreach($errors->all() as $error)
                <li class="flex items-center gap-1.5">
                    <span class="w-1 h-1 rounded-full bg-red-400 flex-shrink-0"></span>
                    {{ $error }}
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif


    {{-- ══════════════════════════════════════════════════════════════
         FORMULAIRE
         ══════════════════════════════════════════════════════════════ --}}
    <form method="POST"
          action="{{ route('exposants.store') }}"
          enctype="multipart/form-data"
          @submit="loading = true"
          novalidate>
        @csrf

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">


            {{-- ────────────────────────────────────────────────────
                 COLONNE PRINCIPALE (2/3)
                 ──────────────────────────────────────────────────── --}}
            <div class="xl:col-span-2 flex flex-col gap-5">

                {{-- Card : Identité de l'entreprise --}}
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden"
                     style="box-shadow:0 2px 16px rgba(0,0,0,.05);">

                    <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background:#EFF6FF;">
                            <svg class="w-4 h-4" fill="none" stroke="#2563EB" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-800">Identité de l'entreprise</h3>
                            <p class="text-xs text-slate-400">Nom, secteur et responsable</p>
                        </div>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- Nom entreprise --}}
                        <div class="md:col-span-2">
                            <label for="nom_entreprise" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Nom de l'entreprise <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="nom_entreprise"
                                   name="nom_entreprise"
                                   value="{{ old('nom_entreprise') }}"
                                   placeholder="Ex : TechHub Dakar SARL"
                                   class="w-full border rounded-xl px-4 py-3 text-sm text-slate-700 placeholder-slate-400 transition-shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nom_entreprise') border-red-400 bg-red-50 @else border-slate-200 @enderror">
                            @error('nom_entreprise')
                            <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        {{-- Responsable --}}
                        <div>
                            <label for="responsable" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Responsable
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none" aria-hidden="true">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                    </svg>
                                </div>
                                <input type="text"
                                       id="responsable"
                                       name="responsable"
                                       value="{{ old('responsable') }}"
                                       placeholder="Ex : Aminata Diallo"
                                       class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-700 placeholder-slate-400 transition-shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('responsable') border-red-400 bg-red-50 @enderror">
                            </div>
                            @error('responsable')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Secteur activité --}}
                        <div>
                            <label for="secteur_activite" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Secteur d'activité
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none" aria-hidden="true">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                                    </svg>
                                </div>
                                <input type="text"
                                       id="secteur_activite"
                                       name="secteur_activite"
                                       value="{{ old('secteur_activite') }}"
                                       placeholder="Ex : Technologie, Agriculture…"
                                       class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-700 placeholder-slate-400 transition-shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('secteur_activite') border-red-400 bg-red-50 @enderror">
                            </div>
                            @error('secteur_activite')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Adresse --}}
                        <div class="md:col-span-2">
                            <label for="adresse" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Adresse
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none" aria-hidden="true">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                                    </svg>
                                </div>
                                <input type="text"
                                       id="adresse"
                                       name="adresse"
                                       value="{{ old('adresse') }}"
                                       placeholder="Ex : Plateau, Dakar, Sénégal"
                                       class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-700 placeholder-slate-400 transition-shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('adresse') border-red-400 bg-red-50 @enderror">
                            </div>
                            @error('adresse')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>


                {{-- Card : Contact --}}
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden"
                     style="box-shadow:0 2px 16px rgba(0,0,0,.05);">

                    <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background:#ECFDF5;">
                            <svg class="w-4 h-4" fill="none" stroke="#059669" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 6z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-800">Coordonnées de contact</h3>
                            <p class="text-xs text-slate-400">Téléphone, email et liens</p>
                        </div>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- Téléphone --}}
                        <div>
                            <label for="telephone" class="block text-xs font-semibold text-slate-600 mb-1.5">Téléphone</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none" aria-hidden="true">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 6z"/>
                                    </svg>
                                </div>
                                <input type="text"
                                       id="telephone"
                                       name="telephone"
                                       value="{{ old('telephone') }}"
                                       placeholder="+221 77 000 00 00"
                                       class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-700 placeholder-slate-400 transition-shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('telephone') border-red-400 bg-red-50 @enderror">
                            </div>
                            @error('telephone')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-xs font-semibold text-slate-600 mb-1.5">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none" aria-hidden="true">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                                    </svg>
                                </div>
                                <input type="email"
                                       id="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       placeholder="contact@entreprise.sn"
                                       class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-700 placeholder-slate-400 transition-shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-400 bg-red-50 @enderror">
                            </div>
                            @error('email')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Site web --}}
                        <div>
                            <label for="site_web" class="block text-xs font-semibold text-slate-600 mb-1.5">Site web</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none" aria-hidden="true">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3"/>
                                    </svg>
                                </div>
                                <input type="url"
                                       id="site_web"
                                       name="site_web"
                                       value="{{ old('site_web') }}"
                                       placeholder="https://www.entreprise.sn"
                                       class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-700 placeholder-slate-400 transition-shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('site_web') border-red-400 bg-red-50 @enderror">
                            </div>
                            @error('site_web')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- LinkedIn --}}
                        <div>
                            <label for="linkedin" class="block text-xs font-semibold text-slate-600 mb-1.5">LinkedIn</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none" aria-hidden="true">
                                    <svg class="w-4 h-4" fill="#0A66C2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                </div>
                                <input type="url"
                                       id="linkedin"
                                       name="linkedin"
                                       value="{{ old('linkedin') }}"
                                       placeholder="https://linkedin.com/company/…"
                                       class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-700 placeholder-slate-400 transition-shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('linkedin') border-red-400 bg-red-50 @enderror">
                            </div>
                            @error('linkedin')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>


                {{-- Card : Description --}}
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden"
                     style="box-shadow:0 2px 16px rgba(0,0,0,.05);">

                    <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background:#F5F3FF;">
                            <svg class="w-4 h-4" fill="none" stroke="#7C3AED" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-800">Description</h3>
                            <p class="text-xs text-slate-400">Présentation de l'entreprise</p>
                        </div>
                    </div>

                    <div class="p-6">
                        <label for="description" class="block text-xs font-semibold text-slate-600 mb-1.5">
                            Description
                            <span class="font-normal text-slate-400 ml-1">(recommandé)</span>
                        </label>
                        <textarea id="description"
                                  name="description"
                                  rows="5"
                                  placeholder="Présentez l'entreprise, ses activités, sa mission…"
                                  class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 placeholder-slate-400 resize-none transition-shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-400 bg-red-50 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>
            {{-- /colonne principale --}}


            {{-- ────────────────────────────────────────────────────
                 SIDEBAR (1/3)
                 ──────────────────────────────────────────────────── --}}
            <div class="flex flex-col gap-5">

                {{-- Card : Logo --}}
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden"
                     style="box-shadow:0 2px 16px rgba(0,0,0,.05);">

                    <div class="flex items-center gap-3 px-5 py-4 border-b border-slate-50">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background:#FFFBEB;">
                            <svg class="w-4 h-4" fill="none" stroke="#D97706" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-800">Logo</h3>
                            <p class="text-xs text-slate-400">PNG, JPG — max 5 Mo</p>
                        </div>
                    </div>

                    <div class="p-5">

                        {{-- Prévisualisation --}}
                        <template x-if="logoPreview">
                            <div class="mb-4 relative">
                                <div class="w-full h-36 rounded-2xl overflow-hidden border border-slate-100 flex items-center justify-center"
                                     style="background:#F8FAFC;">
                                    <img :src="logoPreview" alt="Aperçu logo" class="max-h-full max-w-full object-contain p-4">
                                </div>
                                <button type="button"
                                        @click="logoPreview = null; document.getElementById('logo').value = ''"
                                        class="absolute top-2 right-2 w-7 h-7 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-red-500 hover:border-red-200 flex items-center justify-center transition-all shadow-sm"
                                        aria-label="Supprimer l'aperçu">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </template>

                        {{-- Zone upload --}}
                        <label for="logo" class="block w-full cursor-pointer" x-show="!logoPreview">
                            <div class="border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 group">
                                <div class="flex flex-col items-center gap-2">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform"
                                         style="background:#F1F5F9;">
                                        <svg class="w-5 h-5 text-slate-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                                        </svg>
                                    </div>
                                    <p class="text-xs font-semibold text-slate-600 group-hover:text-blue-600 transition-colors">
                                        Cliquer pour choisir
                                    </p>
                                    <p class="text-xs text-slate-400">PNG, JPG, WebP</p>
                                </div>
                            </div>
                        </label>

                        <input type="file"
                               id="logo"
                               name="logo"
                               accept="image/*"
                               @change="previewLogo($event)"
                               class="sr-only"
                               aria-label="Logo de l'exposant">

                        @error('logo')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>


                {{-- Card : Actions --}}
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden"
                     style="box-shadow:0 2px 16px rgba(0,0,0,.05);">

                    <div class="px-5 py-4 border-b border-slate-50">
                        <h3 class="text-sm font-semibold text-slate-800">Enregistrement</h3>
                    </div>

                    <div class="p-5 flex flex-col gap-3">

                        {{-- Submit --}}
                        <button type="submit"
                                :disabled="loading"
                                class="w-full flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-semibold text-white transition-all hover:brightness-110 active:scale-98 disabled:opacity-60 disabled:cursor-not-allowed"
                                style="background:linear-gradient(135deg,#2563EB,#1d4ed8); box-shadow:0 4px 16px rgba(37,99,235,.3);">
                            <svg x-show="loading" x-cloak class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            <span x-text="loading ? 'Enregistrement…' : 'Enregistrer l\'exposant'"></span>
                        </button>

                        {{-- Reset --}}
                        <button type="reset"
                                @click="logoPreview = null"
                                class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-semibold text-slate-600 bg-slate-50 border border-slate-200 hover:bg-slate-100 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
                            </svg>
                            Réinitialiser
                        </button>

                        {{-- Annuler --}}
                        <a href="{{ route('exposants.index') }}"
                           class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                            </svg>
                            Retour à la liste
                        </a>
                    </div>
                </div>


                {{-- Card : Conseils --}}
                <div class="rounded-2xl p-4 border" style="background:#F8FAFC; border-color:#EEF0F6;">
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#EFF6FF;">
                            <svg class="w-4 h-4" fill="none" stroke="#2563EB" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-700 mb-1.5">Conseils</p>
                            <ul class="text-xs text-slate-500 space-y-1.5">
                                <li class="flex items-start gap-1.5">
                                    <span class="w-1 h-1 rounded-full bg-blue-400 mt-1.5 flex-shrink-0"></span>
                                    Logo recommandé : fond blanc ou transparent
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="w-1 h-1 rounded-full bg-blue-400 mt-1.5 flex-shrink-0"></span>
                                    Une description complète améliore la visibilité
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="w-1 h-1 rounded-full bg-blue-400 mt-1.5 flex-shrink-0"></span>
                                    Le site web doit commencer par https://
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
            {{-- /sidebar --}}

        </div>

    </form>

</div>


{{-- Alpine.js --}}
<script>
function exposantForm() {
    return {
        logoPreview: null,
        loading: false,

        previewLogo(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (file.size > 5 * 1024 * 1024) {
                alert('Le logo ne doit pas dépasser 5 Mo.');
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => { this.logoPreview = e.target.result; };
            reader.readAsDataURL(file);
        }
    }
}
</script>

@endsection
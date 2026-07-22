@extends('admin.layout.header')

@section('title', 'Modifier un exposant')
@section('subtitle', 'Mise à jour des informations de l\'exposant')

@section('content')

{{--
|--------------------------------------------------------------------------
| ExpoDKR Admin – Formulaire modification exposant (premium redesign)
| Routes, variables Blade ($exposant) et logique 100% conservés
| route('exposants.update', $exposant), route('exposants.index')
| Champs : nom_entreprise, responsable, telephone, email, secteur_activite,
|           site_web, statut, is_premium, adresse, description, logo
|--------------------------------------------------------------------------
--}}

<div class="p-6 lg:p-8" x-data="exposantEditForm('{{ $exposant->logo ?? '' }}')">

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
                <a href="{{ route('exposants.show', $exposant) }}"
                   class="text-xs font-medium text-slate-400 hover:text-slate-600 transition-colors truncate max-w-[140px]">
                    {{ $exposant->nom_entreprise }}
                </a>
                <span class="text-slate-200">/</span>
                <span class="text-xs font-semibold text-slate-600">Modifier</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 leading-tight">Modifier l'exposant</h1>
            <p class="text-slate-400 text-sm mt-0.5">
                Dernière mise à jour :
                <span class="font-medium text-slate-500">
                    {{ \Carbon\Carbon::parse($exposant->updated_at)->translatedFormat('d M Y à H:i') }}
                </span>
            </p>
        </div>

        {{-- Badge statut courant + lien voir --}}
        <div class="flex items-center gap-2 flex-wrap">
            @php
                $statusMap = [
                    'validé'     => ['Validé',      '#059669', '#ECFDF5'],
                    'en_attente' => ['En attente',  '#D97706', '#FFFBEB'],
                    'refusé'     => ['Refusé',      '#DC2626', '#FEF2F2'],
                ];
                [$statusLabel, $statusColor, $statusBg] = $statusMap[$exposant->statut] ?? ['Inconnu', '#9CA3AF', '#F1F5F9'];
            @endphp
            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-xl"
                  style="background:{{ $statusBg }}; color:{{ $statusColor }};">
                <span class="w-1.5 h-1.5 rounded-full" style="background:{{ $statusColor }};"></span>
                {{ $statusLabel }}
            </span>
            @if($exposant->is_premium)
            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-xl"
                  style="background:#FFFDF0; color:#C9A84C;">
                ⭐ Premium
            </span>
            @endif
            <a href="{{ route('exposants.show', $exposant) }}"
               class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-500 hover:text-blue-600 px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:border-blue-200 hover:bg-blue-50 transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                </svg>
                Voir la fiche
            </a>
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
          action="{{ route('exposants.update', $exposant) }}"
          enctype="multipart/form-data"
          @submit="loading = true"
          novalidate>
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">


            {{-- ────────────────────────────────────────────────────
                 COLONNE PRINCIPALE (2/3)
                 ──────────────────────────────────────────────────── --}}
            <div class="xl:col-span-2 flex flex-col gap-5">

                {{-- Card : Identité --}}
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
                                   value="{{ old('nom_entreprise', $exposant->nom_entreprise) }}"
                                   placeholder="Ex : TechHub Dakar SARL"
                                   class="w-full border rounded-xl px-4 py-3 text-sm text-slate-700 placeholder-slate-400 transition-shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nom_entreprise') border-red-400 bg-red-50 @else border-slate-200 @enderror">
                            @error('nom_entreprise')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Responsable --}}
                        <div>
                            <label for="responsable" class="block text-xs font-semibold text-slate-600 mb-1.5">Responsable</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none" aria-hidden="true">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                    </svg>
                                </div>
                                <input type="text"
                                       id="responsable"
                                       name="responsable"
                                       value="{{ old('responsable', $exposant->responsable) }}"
                                       placeholder="Ex : Aminata Diallo"
                                       class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('responsable') border-red-400 bg-red-50 @enderror">
                            </div>
                            @error('responsable')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Secteur --}}
                        <div>
                            <label for="secteur_activite" class="block text-xs font-semibold text-slate-600 mb-1.5">Secteur d'activité</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none" aria-hidden="true">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                                    </svg>
                                </div>
                                <input type="text"
                                       id="secteur_activite"
                                       name="secteur_activite"
                                       value="{{ old('secteur_activite', $exposant->secteur_activite) }}"
                                       placeholder="Ex : Technologie, Agriculture…"
                                       class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('secteur_activite') border-red-400 bg-red-50 @enderror">
                            </div>
                            @error('secteur_activite')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Adresse --}}
                        <div class="md:col-span-2">
                            <label for="adresse" class="block text-xs font-semibold text-slate-600 mb-1.5">Adresse</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none" aria-hidden="true">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                                    </svg>
                                </div>
                                <input type="text"
                                       id="adresse"
                                       name="adresse"
                                       value="{{ old('adresse', $exposant->adresse) }}"
                                       placeholder="Ex : Plateau, Dakar, Sénégal"
                                       class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('adresse') border-red-400 bg-red-50 @enderror">
                            </div>
                            @error('adresse')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
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
                            <h3 class="text-sm font-semibold text-slate-800">Coordonnées</h3>
                            <p class="text-xs text-slate-400">Téléphone, email et liens web</p>
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
                                       value="{{ old('telephone', $exposant->telephone) }}"
                                       placeholder="+221 77 000 00 00"
                                       class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('telephone') border-red-400 bg-red-50 @enderror">
                            </div>
                            @error('telephone')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
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
                                       value="{{ old('email', $exposant->email) }}"
                                       placeholder="contact@entreprise.sn"
                                       class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-400 bg-red-50 @enderror">
                            </div>
                            @error('email')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
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
                                       value="{{ old('site_web', $exposant->site_web) }}"
                                       placeholder="https://www.entreprise.sn"
                                       class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('site_web') border-red-400 bg-red-50 @enderror">
                            </div>
                            @error('site_web')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
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
                                       value="{{ old('linkedin', $exposant->linkedin ?? '') }}"
                                       placeholder="https://linkedin.com/company/…"
                                       class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('linkedin') border-red-400 bg-red-50 @enderror">
                            </div>
                            @error('linkedin')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
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
                                  class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 placeholder-slate-400 resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-400 bg-red-50 @enderror">{{ old('description', $exposant->description) }}</textarea>
                        @error('description')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
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
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-800">Logo</h3>
                            <p class="text-xs text-slate-400">PNG, JPG — max 5 Mo</p>
                        </div>
                    </div>

                    <div class="p-5">

                        {{-- Logo actuel --}}
                        @if($exposant->logo)
                        <div x-show="!logoPreview" class="mb-4">
                            <p class="text-xs font-semibold text-slate-500 mb-2">Logo actuel</p>
                            <div class="w-full h-32 rounded-2xl overflow-hidden border border-slate-100 flex items-center justify-center"
                                 style="background:#F8FAFC;">
                                <img src="{{ Storage::url($exposant->logo) }}"
                                     alt="Logo actuel de {{ $exposant->nom_entreprise }}"
                                     class="max-h-full max-w-full object-contain p-4">
                            </div>
                        </div>
                        @endif

                        {{-- Nouvelle prévisualisation --}}
                        <template x-if="logoPreview">
                            <div class="mb-4 relative">
                                <p class="text-xs font-semibold text-slate-500 mb-2">Nouveau logo</p>
                                <div class="w-full h-32 rounded-2xl overflow-hidden border border-blue-100 flex items-center justify-center"
                                     style="background:#EFF6FF;">
                                    <img :src="logoPreview" alt="Aperçu" class="max-h-full max-w-full object-contain p-4">
                                </div>
                                <button type="button"
                                        @click="logoPreview = null; document.getElementById('logo').value = ''"
                                        class="absolute top-7 right-2 w-7 h-7 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-red-500 hover:border-red-200 flex items-center justify-center shadow-sm transition-all"
                                        aria-label="Annuler le changement">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </template>

                        {{-- Zone upload --}}
                        <label for="logo" class="block w-full cursor-pointer">
                            <div class="border-2 border-dashed border-slate-200 rounded-2xl p-5 text-center hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 group">
                                <div class="flex flex-col items-center gap-2">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform"
                                         style="background:#F1F5F9;">
                                        <svg class="w-4.5 h-4.5 text-slate-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                                        </svg>
                                    </div>
                                    <p class="text-xs font-semibold text-slate-600 group-hover:text-blue-600 transition-colors">
                                        {{ $exposant->logo ? 'Remplacer le logo' : 'Choisir un logo' }}
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


                {{-- Card : Statut & Options admin --}}
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden"
                     style="box-shadow:0 2px 16px rgba(0,0,0,.05);">

                    <div class="flex items-center gap-3 px-5 py-4 border-b border-slate-50">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background:#FEF2F2;">
                            <svg class="w-4 h-4" fill="none" stroke="#DC2626" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-800">Options admin</h3>
                            <p class="text-xs text-slate-400">Statut et privilèges</p>
                        </div>
                    </div>

                    <div class="p-5 flex flex-col gap-4">

                        {{-- Statut --}}
                        <div>
                            <label for="statut" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Statut de validation
                            </label>
                            <div class="relative">
                                <select id="statut"
                                        name="statut"
                                        class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 bg-white appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent pr-9 @error('statut') border-red-400 bg-red-50 @enderror">
                                    <option value="en_attente" @selected(old('statut', $exposant->statut) == 'en_attente')>
                                        ⏳ En attente
                                    </option>
                                    <option value="validé" @selected(old('statut', $exposant->statut) == 'validé')>
                                        ✅ Validé
                                    </option>
                                    <option value="refusé" @selected(old('statut', $exposant->statut) == 'refusé')>
                                        ❌ Refusé
                                    </option>
                                </select>
                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none" aria-hidden="true">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                                    </svg>
                                </div>
                            </div>
                            @error('statut')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <hr class="border-slate-50">

                        {{-- Premium toggle --}}
                        <label class="flex items-center justify-between gap-4 cursor-pointer group">
                            <div>
                                <p class="text-sm font-semibold text-slate-700 group-hover:text-slate-900 transition-colors">
                                    Exposant Premium
                                </p>
                                <p class="text-xs text-slate-400 mt-0.5">
                                    Mise en avant sur la plateforme
                                </p>
                            </div>
                            <div class="relative flex-shrink-0"
                                 x-data="{ checked: {{ $exposant->is_premium ? 'true' : 'false' }} }">
                                <input type="checkbox"
                                       id="is_premium"
                                       name="is_premium"
                                       value="1"
                                       @checked(old('is_premium', $exposant->is_premium))
                                       x-model="checked"
                                       class="sr-only">
                                <div @click="checked = !checked; $el.previousElementSibling.checked = checked"
                                     class="w-12 h-6 rounded-full transition-colors duration-200 cursor-pointer"
                                     :style="checked ? 'background:#C9A84C;' : 'background:#E2E8F0;'">
                                    <div class="w-5 h-5 bg-white rounded-full shadow-sm transition-transform duration-200 translate-y-0.5"
                                         :style="checked ? 'transform: translateX(26px) translateY(2px)' : 'transform: translateX(2px) translateY(2px)'">
                                    </div>
                                </div>
                            </div>
                        </label>

                        @if($exposant->is_premium)
                        <div class="flex items-center gap-2 p-3 rounded-xl" style="background:#FFFDF0; border:1px solid #E8C96A;">
                            <span class="text-lg">⭐</span>
                            <p class="text-xs font-medium" style="color:#C9A84C;">Exposant premium actif</p>
                        </div>
                        @endif

                    </div>
                </div>


                {{-- Card : Méta infos --}}
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden"
                     style="box-shadow:0 2px 12px rgba(0,0,0,.04);">
                    <div class="px-5 py-4 border-b border-slate-50">
                        <h3 class="text-sm font-semibold text-slate-800">Informations</h3>
                    </div>
                    <div class="p-5 flex flex-col gap-3">
                        @foreach([
                            ['ID',         '#' . $exposant->id],
                            ['Créé le',    \Carbon\Carbon::parse($exposant->created_at)->translatedFormat('d M Y')],
                            ['Mis à jour', \Carbon\Carbon::parse($exposant->updated_at)->translatedFormat('d M Y')],
                        ] as [$k, $v])
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-400 font-medium">{{ $k }}</span>
                            <span class="font-semibold text-slate-700 font-mono bg-slate-50 px-2 py-0.5 rounded-lg">{{ $v }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>


                {{-- Card : Actions --}}
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden"
                     style="box-shadow:0 2px 16px rgba(0,0,0,.05);">

                    <div class="px-5 py-4 border-b border-slate-50">
                        <h3 class="text-sm font-semibold text-slate-800">Sauvegarder</h3>
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
                            <span x-text="loading ? 'Mise à jour…' : 'Mettre à jour'"></span>
                        </button>

                        {{-- Voir fiche --}}
                        <a href="{{ route('exposants.show', $exposant) }}"
                           class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-semibold text-slate-600 bg-slate-50 border border-slate-200 hover:bg-slate-100 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                            Voir la fiche
                        </a>

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

            </div>
            {{-- /sidebar --}}

        </div>

    </form>

</div>


{{-- Alpine.js --}}
<script>
function exposantEditForm(currentLogo) {
    return {
        logoPreview: null,
        loading: false,
        hasLogo: currentLogo !== '',

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
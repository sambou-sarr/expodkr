@extends('admin.layout.header')

@section('title', 'Modifier un événement')
@section('subtitle', 'Mise à jour des informations de l\'événement')

@section('content')

{{--
|--------------------------------------------------------------------------
| ExpoDKR Admin – Formulaire modification événement (premium redesign)
| Routes, variables Blade ($event, $categories, $exposants),
| Alpine.js et logique métier 100% conservés
|--------------------------------------------------------------------------
--}}

<div class="p-6 lg:p-8" x-data="eventFormEdit('{{ $event->image ?? '' }}')">

    {{-- ══════════════════════════════════════════════════════════════
         HEADER PAGE
         ══════════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            {{-- Breadcrumb --}}
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('events.index') }}"
                   class="flex items-center gap-1.5 text-xs font-medium text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                    </svg>
                    Événements
                </a>
                <span class="text-slate-200">/</span>
                <span class="text-xs text-slate-400 max-w-[160px] truncate">{{ $event->titre }}</span>
                <span class="text-slate-200">/</span>
                <span class="text-xs font-semibold text-slate-600">Modifier</span>
            </div>

            <h1 class="text-2xl font-bold text-slate-800 leading-tight">Modifier l'événement</h1>
            <p class="text-slate-400 text-sm mt-0.5">
                Dernière mise à jour :
                <span class="font-medium text-slate-500">
                    {{ $event->updated_at ? \Carbon\Carbon::parse($event->updated_at)->translatedFormat('d M Y à H:i') : '—' }}
                </span>
            </p>
        </div>

        {{-- Badge statut courant --}}
        @php
            $now   = now();
            $debut = \Carbon\Carbon::parse($event->date_debut);
            $fin   = \Carbon\Carbon::parse($event->date_fin);
            $statut = $event->statut ?? null;
            if (!$statut) {
                if ($now->lt($debut))               $statut = 'ouvert';
                elseif ($now->between($debut,$fin)) $statut = 'ouvert';
                else                                $statut = 'termine';
            }
            $statusMap = [
                'ouvert'   => ['Ouvert',    '#059669', '#ECFDF5'],
                'termine'  => ['Terminé',   '#DC2626', '#FEF2F2'],
                'brouillon'=> ['Brouillon', '#D97706', '#FFFBEB'],
            ];
            [$statusLabel, $statusColor, $statusBg] = $statusMap[$statut] ?? ['Inconnu', '#9CA3AF', '#F1F5F9'];
        @endphp

        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-xl"
                  style="background: {{ $statusBg }}; color: {{ $statusColor }};">
                <span class="w-1.5 h-1.5 rounded-full" style="background: {{ $statusColor }};"></span>
                {{ $statusLabel }}
            </span>

            {{-- Lien voir l'événement public --}}
            <a href="{{ route('events.show', $event->id) }}"
               target="_blank"
               rel="noopener"
               class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-500 hover:text-blue-600 px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:border-blue-200 hover:bg-blue-50 transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                </svg>
                Voir la page publique
            </a>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════════════
         ERREURS DE VALIDATION
         ══════════════════════════════════════════════════════════════ --}}
    @if($errors->any())
    <div class="mb-6 p-4 rounded-2xl border flex items-start gap-3"
         style="background:#FEF2F2; border-color:#FECACA;">
        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="#DC2626" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
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
{{-- ✅ corrigé --}}
<form action="{{ route('events.update', $event->id) }}"
      method="POST"
      enctype="multipart/form-data"
      @submit="loading = true"
      novalidate>
    @csrf
   

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- ────────────────────────────────────────────────────
                 COLONNE PRINCIPALE (2/3)
                 ──────────────────────────────────────────────────── --}}
            <div class="xl:col-span-2 flex flex-col gap-5">

                {{-- Card : Informations générales --}}
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden"
                     style="box-shadow: 0 2px 16px rgba(0,0,0,.05);">

                    <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background:#EFF6FF;">
                            <svg class="w-4 h-4" fill="none" stroke="#2563EB" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-800">Informations générales</h3>
                            <p class="text-xs text-slate-400">Titre, lieu et description</p>
                        </div>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- Titre --}}
                        <div class="md:col-span-2">
                            <label for="titre" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Titre de l'événement <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="titre"
                                   name="titre"
                                   value="{{ old('titre', $event->titre) }}"
                                   placeholder="Ex : Forum Tech Dakar 2026"
                                   class="w-full border rounded-xl px-4 py-3 text-sm text-slate-700 placeholder-slate-400 transition-shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('titre') border-red-400 bg-red-50 @else border-slate-200 @enderror">
                            @error('titre')
                            <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        {{-- Lieu --}}
                        <div class="md:col-span-2">
                            <label for="lieu" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Lieu <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none" aria-hidden="true">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                                    </svg>
                                </div>
                                <input type="text"
                                       id="lieu"
                                       name="lieu"
                                       value="{{ old('lieu', $event->lieu) }}"
                                       placeholder="Ex : Centre de Conférences de Diamniadio"
                                       class="w-full border rounded-xl pl-10 pr-4 py-3 text-sm text-slate-700 placeholder-slate-400 transition-shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('lieu') border-red-400 bg-red-50 @else border-slate-200 @enderror">
                            </div>
                            @error('lieu')
                            <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        {{-- Date début --}}
                        <div>
                            <label for="date_debut" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Date de début <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none" aria-hidden="true">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5"/>
                                    </svg>
                                </div>
                                <input type="date"
                                       id="date_debut"
                                       name="date_debut"
                                       value="{{ old('date_debut', \Carbon\Carbon::parse($event->date_debut)->format('Y-m-d')) }}"
                                       class="w-full border rounded-xl pl-10 pr-4 py-3 text-sm text-slate-700 transition-shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_debut') border-red-400 bg-red-50 @else border-slate-200 @enderror">
                            </div>
                            @error('date_debut')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Date fin --}}
                        <div>
                            <label for="date_fin" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Date de fin <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none" aria-hidden="true">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5"/>
                                    </svg>
                                </div>
                                <input type="date"
                                       id="date_fin"
                                       name="date_fin"
                                       value="{{ old('date_fin', \Carbon\Carbon::parse($event->date_fin)->format('Y-m-d')) }}"
                                       class="w-full border rounded-xl pl-10 pr-4 py-3 text-sm text-slate-700 transition-shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_fin') border-red-400 bg-red-50 @else border-slate-200 @enderror">
                            </div>
                            @error('date_fin')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="md:col-span-2">
                            <label for="description" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Description
                                <span class="font-normal text-slate-400 ml-1">(recommandé)</span>
                            </label>
                            <textarea id="description"
                                      name="description"
                                      rows="5"
                                      placeholder="Décrivez l'événement, son programme, ses objectifs…"
                                      class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 placeholder-slate-400 resize-none transition-shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-400 bg-red-50 @enderror">{{ old('description', $event->description) }}</textarea>
                            @error('description')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>


                {{-- Card : Image de couverture --}}
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden"
                     style="box-shadow: 0 2px 16px rgba(0,0,0,.05);">

                    <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background:#F5F3FF;">
                            <svg class="w-4 h-4" fill="none" stroke="#7C3AED" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-800">Image de couverture</h3>
                            <p class="text-xs text-slate-400">Format recommandé : 1200×630px · JPG ou PNG</p>
                        </div>
                    </div>

                    <div class="p-6">

                        {{-- Image actuelle (si pas de nouvelle preview) --}}
                        @if($event->image)
                        <div x-show="!imagePreview" class="mb-4">
                            <p class="text-xs font-semibold text-slate-500 mb-2">Image actuelle</p>
                            <div class="relative w-full">
                                <img src="{{Storage::url( $event->image )}}"
                                     alt="Image actuelle de {{ $event->titre }}"
                                     class="w-full h-52 object-cover rounded-2xl">
                                <div class="absolute inset-0 rounded-2xl bg-gradient-to-t from-black/50 to-transparent flex items-end p-4">
                                    <span class="text-xs font-medium text-white/80">Image enregistrée · Remplacez-la ci-dessous si nécessaire</span>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Zone d'upload --}}
                        <label for="image"
                               class="block w-full cursor-pointer"
                               x-show="!imagePreview">
                            <div class="border-2 border-dashed border-slate-200 rounded-2xl p-8 text-center hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 group">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform" style="background:#F1F5F9;">
                                        <svg class="w-5 h-5 text-slate-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-600 group-hover:text-blue-600 transition-colors">
                                            {{ $event->image ? 'Remplacer l\'image' : 'Choisir une image' }}
                                        </p>
                                        <p class="text-xs text-slate-400 mt-0.5">JPG, PNG, WebP — max 5 Mo</p>
                                    </div>
                                    <span class="px-4 py-1.5 rounded-xl text-xs font-semibold text-blue-600 border border-blue-200 bg-blue-50 group-hover:bg-blue-100 transition-colors">
                                        Parcourir
                                    </span>
                                </div>
                            </div>
                            <input type="file"
                                   id="image"
                                   name="image"
                                   accept="image/*"
                                   @change="previewImage($event)"
                                   class="sr-only"
                                   aria-label="Remplacer l'image de couverture">
                        </label>

                        {{-- Nouvelle prévisualisation --}}
                        <template x-if="imagePreview">
                            <div class="relative">
                                <p class="text-xs font-semibold text-slate-500 mb-2">Nouvelle image sélectionnée</p>
                                <img :src="imagePreview"
                                     alt="Prévisualisation"
                                     class="w-full h-52 object-cover rounded-2xl">
                                <div class="absolute inset-0 rounded-2xl bg-gradient-to-t from-black/60 to-transparent flex items-end p-4">
                                    <div class="flex items-center justify-between w-full">
                                        <p class="text-xs text-white/80 font-medium">Nouvelle image prête</p>
                                        <button type="button"
                                                @click="imagePreview = null; document.getElementById('image').value = ''"
                                                class="flex items-center gap-1.5 text-xs font-semibold text-white bg-white/20 hover:bg-white/30 px-3 py-1.5 rounded-lg backdrop-blur transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                            </svg>
                                            Annuler le changement
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>

                        @error('image')
                        <p class="mt-2 text-xs text-red-600 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                </div>

            </div>
            {{-- /colonne principale --}}


            {{-- ────────────────────────────────────────────────────
                 SIDEBAR (1/3)
                 ──────────────────────────────────────────────────── --}}
            <div class="flex flex-col gap-5">

                {{-- Card : Organisation --}}
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden"
                     style="box-shadow: 0 2px 16px rgba(0,0,0,.05);">

                    <div class="flex items-center gap-3 px-5 py-4 border-b border-slate-50">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background:#ECFDF5;">
                            <svg class="w-4 h-4" fill="none" stroke="#059669" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-800">Organisation</h3>
                            <p class="text-xs text-slate-400">Catégorie et exposant</p>
                        </div>
                    </div>

                    <div class="p-5 flex flex-col gap-4">

                        {{-- Catégorie --}}
                        <div>
                            <label for="id_categorie" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Catégorie
                            </label>
                            <div class="relative">
                                <select id="id_categorie"
                                        name="id_categorie"
                                        class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 bg-white appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow pr-9 @error('id_categorie') border-red-400 bg-red-50 @enderror">
                                    <option value="">Sélectionner une catégorie</option>
                                    @foreach($categories as $categorie)
                                    <option value="{{ $categorie->id }}"
                                            {{ (old('id_categorie', $event->id_categorie) == $categorie->id) ? 'selected' : '' }}>
                                        {{ $categorie->nom }}
                                        @if(isset($categorie->prix))
                                            – {{ number_format($categorie->prix, 0, ',', ' ') }} FCFA
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none" aria-hidden="true">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                                    </svg>
                                </div>
                            </div>
                            @error('id_categorie')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <hr class="border-slate-50">

                        {{-- Exposant --}}
                        <div>
                            <label for="exposant_id" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                Exposant
                            </label>
                            <div class="relative">
                                <select id="exposant_id"
                                        name="exposant_id"
                                        class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 bg-white appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow pr-9 @error('exposant_id') border-red-400 bg-red-50 @enderror">
                                    <option value="">Sélectionner un exposant</option>
                                    @foreach($exposants as $exposant)
                                    <option value="{{ $exposant->id }}"
                                            {{ (old('exposant_id', $event->exposant_id) == $exposant->id) ? 'selected' : '' }}>
                                        {{ $exposant->nom_entreprise }}
                                        @if($exposant->responsable)
                                            – {{ $exposant->responsable }}
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none" aria-hidden="true">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                                    </svg>
                                </div>
                            </div>
                            @error('exposant_id')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>


                {{-- Card : Méta infos --}}
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden"
                     style="box-shadow: 0 2px 16px rgba(0,0,0,.05);">
                    <div class="px-5 py-4 border-b border-slate-50">
                        <h3 class="text-sm font-semibold text-slate-800">Informations</h3>
                    </div>
                    <div class="p-5 flex flex-col gap-3">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-400 font-medium">ID événement</span>
                            <span class="font-mono font-semibold text-slate-600 bg-slate-50 px-2 py-0.5 rounded-lg">#{{ $event->id }}</span>
                        </div>
                        <hr class="border-slate-50">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-400 font-medium">Créé le</span>
                            <span class="font-medium text-slate-600">{{ \Carbon\Carbon::parse($event->created_at)->translatedFormat('d M Y') }}</span>
                        </div>
                        <hr class="border-slate-50">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-400 font-medium">Durée</span>
                            <span class="font-medium text-slate-600">
                                {{ \Carbon\Carbon::parse($event->date_debut)->diffInDays($event->date_fin) + 1 }} jour(s)
                            </span>
                        </div>
                    </div>
                </div>


                {{-- Card : Actions --}}
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden"
                     style="box-shadow: 0 2px 16px rgba(0,0,0,.05);">

                    <div class="px-5 py-4 border-b border-slate-50">
                        <h3 class="text-sm font-semibold text-slate-800">Actions</h3>
                    </div>

                    <div class="p-5 flex flex-col gap-3">

                        {{-- Bouton mettre à jour --}}
                        <button type="submit"
                                :disabled="loading"
                                class="w-full flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-semibold text-white transition-all hover:brightness-110 active:scale-98 disabled:opacity-60 disabled:cursor-not-allowed"
                                style="background: linear-gradient(135deg, #2563EB, #1d4ed8); box-shadow: 0 4px 16px rgba(37,99,235,.3);">
                            <svg x-show="loading" x-cloak
                                 class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            <span x-text="loading ? 'Mise à jour…' : 'Mettre à jour'"></span>
                        </button>

                        {{-- Voir publiquement --}}
                        <a href="{{ route('events.show', $event->id) }}"
                           target="_blank" rel="noopener"
                           class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-semibold text-slate-600 bg-slate-50 border border-slate-200 hover:bg-slate-100 hover:border-slate-300 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                            </svg>
                            Voir la page publique
                        </a>


                        {{-- Retour --}}
                        <a href="{{ route('events.index') }}"
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
        {{-- /grid --}}

    </form>

</div>


{{-- ══════════════════════════════════════════════════════════════
     ALPINE.JS — Logique conservée (identique à l'original)
     ══════════════════════════════════════════════════════════════ --}}
<script>
function eventFormEdit(currentImage) {
    return {
        imagePreview: null,
        loading: false,
        hasCurrentImage: currentImage !== '',

        previewImage(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (file.size > 5 * 1024 * 1024) {
                alert('L\'image ne doit pas dépasser 5 Mo.');
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                this.imagePreview = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
}
</script>

@endsection
@extends('admin.layout.header')

@section('title', 'Ajouter une catégorie')
@section('subtitle', 'Créer une nouvelle catégorie de stand')

@section('content')

{{--
|--------------------------------------------------------------------------
| ExpoDKR Admin – Formulaire création catégorie (premium redesign)
| Routes conservées : categories.store, categories.index
|--------------------------------------------------------------------------
--}}

<div class="p-6 lg:p-8" x-data="{ loading: false }">

    {{-- ══════════════════════════════════════════════════════════════
         HEADER PAGE
         ══════════════════════════════════════════════════════════════ --}}
    <div class="mb-8">
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('categories.index') }}"
               class="flex items-center gap-1.5 text-xs font-medium text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                Catégories
            </a>
            <span class="text-slate-200">/</span>
            <span class="text-xs font-semibold text-slate-600">Ajouter</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-800 leading-tight">Ajouter une catégorie</h1>
        <p class="text-slate-400 text-sm mt-0.5">Créer une nouvelle catégorie de stand</p>
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
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- ────────────────────────────────────────────────────
             COLONNE PRINCIPALE (2/3)
             ──────────────────────────────────────────────────── --}}
        <div class="xl:col-span-2">

            <form method="POST"
                  action="{{ route('categories.store') }}"
                  @submit="loading = true"
                  novalidate>
                @csrf

                <div class="flex flex-col gap-5">

                    {{-- Card : Informations --}}
                    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden"
                         style="box-shadow:0 2px 16px rgba(0,0,0,.05);">

                        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                                 style="background:#F5F3FF;">
                                <svg class="w-4 h-4" fill="none" stroke="#7C3AED" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-slate-800">Informations de la catégorie</h3>
                                <p class="text-xs text-slate-400">Nom, prix et description</p>
                            </div>
                        </div>

                        <div class="p-6 flex flex-col gap-5">

                            {{-- Nom --}}
                            <div>
                                <label for="nom" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                    Nom de la catégorie <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="nom"
                                       name="nom"
                                       value="{{ old('nom') }}"
                                       placeholder="Ex : Conférence, Workshop, Salon Pro…"
                                       required
                                       class="w-full border rounded-xl px-4 py-3 text-sm text-slate-700 placeholder-slate-400 transition-shadow focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('nom') border-red-400 bg-red-50 @else border-slate-200 @enderror">
                                @error('nom')
                                <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            {{-- Prix --}}
                            <div>
                                <label for="prix" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                    Prix de participation (FCFA) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none" aria-hidden="true">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25"/>
                                        </svg>
                                    </div>
                                    <input type="number"
                                           id="prix"
                                           name="prix"
                                           value="{{ old('prix') }}"
                                           placeholder="Ex : 50000"
                                           min="0"
                                           required
                                           class="w-full border rounded-xl pl-10 pr-16 py-3 text-sm text-slate-700 placeholder-slate-400 transition-shadow focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('prix') border-red-400 bg-red-50 @else border-slate-200 @enderror">
                                    <div class="absolute inset-y-0 right-3.5 flex items-center pointer-events-none" aria-hidden="true">
                                        <span class="text-xs font-semibold text-slate-400">FCFA</span>
                                    </div>
                                </div>
                                @error('prix')
                                <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div>
                                <label for="description" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                    Description
                                    <span class="font-normal text-slate-400 ml-1">(optionnel)</span>
                                </label>
                                <textarea id="description"
                                          name="description"
                                          rows="4"
                                          placeholder="Décrivez cette catégorie de stand, ce qu'elle inclut…"
                                          class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 placeholder-slate-400 resize-none transition-shadow focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('description') border-red-400 bg-red-50 @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>


                    {{-- Boutons (desktop) --}}
                    <div class="hidden xl:flex items-center gap-3">
                        <a href="{{ route('categories.index') }}"
                           class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                            </svg>
                            Annuler
                        </a>
                        <button type="submit"
                                :disabled="loading"
                                class="flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold text-white transition-all hover:brightness-110 active:scale-95 disabled:opacity-60 disabled:cursor-not-allowed"
                                style="background:linear-gradient(135deg,#7C3AED,#6D28D9); box-shadow:0 4px 16px rgba(124,58,237,.3);">
                            <svg x-show="loading" x-cloak class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            <span x-text="loading ? 'Enregistrement…' : 'Enregistrer la catégorie'"></span>
                        </button>
                    </div>

                </div>

            </form>
        </div>
        {{-- /colonne principale --}}


        {{-- ────────────────────────────────────────────────────
             SIDEBAR (1/3)
             ──────────────────────────────────────────────────── --}}
        <div class="flex flex-col gap-5">

            {{-- Card : Actions --}}
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden"
                 style="box-shadow:0 2px 16px rgba(0,0,0,.05);">

                <div class="px-5 py-4 border-b border-slate-50">
                    <h3 class="text-sm font-semibold text-slate-800">Enregistrement</h3>
                </div>

                <div class="p-5 flex flex-col gap-3">
                    <button type="submit"
                            form="cat-form"
                            :disabled="loading"
                            class="w-full flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-semibold text-white transition-all hover:brightness-110 active:scale-95 disabled:opacity-60"
                            style="background:linear-gradient(135deg,#7C3AED,#6D28D9); box-shadow:0 4px 16px rgba(124,58,237,.25);"
                            onclick="document.querySelector('form').requestSubmit()">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                        Enregistrer
                    </button>

                    <a href="{{ route('categories.index') }}"
                       class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                        </svg>
                        Retour à la liste
                    </a>
                </div>
            </div>


            {{-- Card : Aperçu live --}}
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden"
                 style="box-shadow:0 2px 12px rgba(0,0,0,.04);"
                 x-data="{
                     nom: '',
                     prix: '',
                     init() {
                         document.getElementById('nom').addEventListener('input', e => this.nom = e.target.value);
                         document.getElementById('prix').addEventListener('input', e => this.prix = e.target.value);
                     }
                 }">

                <div class="px-5 py-4 border-b border-slate-50">
                    <h3 class="text-sm font-semibold text-slate-800">Aperçu</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Rendu en temps réel</p>
                </div>

                <div class="p-5">
                    <div class="rounded-2xl border border-slate-100 p-4" style="background:#F8FAFC;">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                                 style="background:linear-gradient(135deg,#7C3AED,#6D28D9);">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-slate-800 truncate"
                                   x-text="nom || 'Nom de la catégorie'"></p>
                                <p class="text-xs text-slate-400">Catégorie de stand</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-400">Prix</span>
                            <span class="text-sm font-bold"
                                  style="color:#059669;"
                                  x-text="prix ? Number(prix).toLocaleString('fr-FR') + ' FCFA' : '—'">
                            </span>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Card : Conseils --}}
            <div class="rounded-2xl p-4 border" style="background:#FAFBFF; border-color:#EEF0F6;">
                <div class="flex gap-3">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#F5F3FF;">
                        <svg class="w-4 h-4" fill="none" stroke="#7C3AED" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-700 mb-1.5">Conseils</p>
                        <ul class="text-xs text-slate-500 space-y-1.5">
                            <li class="flex items-start gap-1.5">
                                <span class="w-1 h-1 rounded-full bg-purple-400 mt-1.5 flex-shrink-0"></span>
                                Choisissez un nom clair et distinct
                            </li>
                            <li class="flex items-start gap-1.5">
                                <span class="w-1 h-1 rounded-full bg-purple-400 mt-1.5 flex-shrink-0"></span>
                                Le prix doit être en FCFA sans décimales
                            </li>
                            <li class="flex items-start gap-1.5">
                                <span class="w-1 h-1 rounded-full bg-purple-400 mt-1.5 flex-shrink-0"></span>
                                Une description aide les exposants à choisir
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
        {{-- /sidebar --}}

    </div>
    {{-- /grid --}}

</div>

@endsection
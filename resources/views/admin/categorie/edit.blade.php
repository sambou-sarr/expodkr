@extends('admin.layout.header')

@section('title', 'Modifier une catégorie')
@section('subtitle', 'Mise à jour de la catégorie')

@section('content')

{{--
|--------------------------------------------------------------------------
| ExpoDKR Admin – Formulaire modification catégorie (premium redesign)
| Routes conservées : categories.update, categories.index
| Variable : $category (id, nom, prix, description)
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
            <span class="text-xs text-slate-400 truncate max-w-[120px]">{{ $category->nom }}</span>
            <span class="text-slate-200">/</span>
            <span class="text-xs font-semibold text-slate-600">Modifier</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-800 leading-tight">Modifier la catégorie</h1>
        <p class="text-slate-400 text-sm mt-0.5">
            Dernière mise à jour :
            <span class="font-medium text-slate-500">
                {{ \Carbon\Carbon::parse($category->updated_at)->translatedFormat('d M Y à H:i') }}
            </span>
        </p>
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
         GRID PRINCIPAL
         ══════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6"
         x-data="{
             nom:   '{{ addslashes(old('nom', $category->nom)) }}',
             prix:  '{{ old('prix', $category->prix) }}',
         }">

        {{-- ────────────────────────────────────────────────────
             COLONNE PRINCIPALE (2/3)
             ──────────────────────────────────────────────────── --}}
        <div class="xl:col-span-2">

            <form id="edit-form"
                  method="POST"
                  action="{{ route('categories.update', $category->id) }}"
                  @submit="loading = true"
                  novalidate>
                @csrf
                @method('PUT')

                <div class="flex flex-col gap-5">

                    {{-- Card : Informations --}}
                    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden"
                         style="box-shadow:0 2px 16px rgba(0,0,0,.05);">

                        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                                 style="background:#FFFBEB;">
                                <svg class="w-4 h-4" fill="none" stroke="#D97706" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-slate-800">Informations de la catégorie</h3>
                                <p class="text-xs text-slate-400">Modifiez le nom, le prix et la description</p>
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
                                       x-model="nom"
                                       value="{{ old('nom', $category->nom) }}"
                                       placeholder="Ex : Conférence, Workshop, Salon Pro…"
                                       required
                                       class="w-full border rounded-xl px-4 py-3 text-sm text-slate-700 placeholder-slate-400 transition-shadow focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('nom') border-red-400 bg-red-50 @else border-slate-200 @enderror">
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
                                           x-model="prix"
                                           value="{{ old('prix', $category->prix) }}"
                                           placeholder="Ex : 50000"
                                           min="0"
                                           required
                                           class="w-full border rounded-xl pl-10 pr-16 py-3 text-sm text-slate-700 placeholder-slate-400 transition-shadow focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('prix') border-red-400 bg-red-50 @else border-slate-200 @enderror">
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
                                          placeholder="Décrivez cette catégorie de stand…"
                                          class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 placeholder-slate-400 resize-none transition-shadow focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('description') border-red-400 bg-red-50 @enderror">{{ old('description', $category->description) }}</textarea>
                                @error('description')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>


                    {{-- Boutons desktop --}}
                    <div class="hidden xl:flex items-center gap-3">
                        <a href="{{ route('categories.index') }}"
                           class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                            </svg>
                            Retour
                        </a>
                        <button type="submit"
                                :disabled="loading"
                                class="flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold text-white transition-all hover:brightness-110 active:scale-95 disabled:opacity-60"
                                style="background:linear-gradient(135deg,#D97706,#B45309); box-shadow:0 4px 16px rgba(217,119,6,.3);">
                            <svg x-show="loading" x-cloak class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            <span x-text="loading ? 'Mise à jour…' : 'Enregistrer les modifications'"></span>
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
                    <h3 class="text-sm font-semibold text-slate-800">Sauvegarder</h3>
                </div>

                <div class="p-5 flex flex-col gap-3">

                    {{-- Submit --}}
                    <button type="submit"
                            form="edit-form"
                            :disabled="loading"
                            class="w-full flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-semibold text-white transition-all hover:brightness-110 active:scale-95 disabled:opacity-60"
                            style="background:linear-gradient(135deg,#D97706,#B45309); box-shadow:0 4px 16px rgba(217,119,6,.25);">
                        <svg x-show="loading" x-cloak class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                        <span x-text="loading ? 'Mise à jour…' : 'Enregistrer'"></span>
                    </button>

                    {{-- Retour --}}
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
                 style="box-shadow:0 2px 12px rgba(0,0,0,.04);">

                <div class="px-5 py-4 border-b border-slate-50">
                    <h3 class="text-sm font-semibold text-slate-800">Aperçu</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Mise à jour en temps réel</p>
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
                                   x-text="nom || '{{ addslashes($category->nom) }}'"></p>
                                <p class="text-xs text-slate-400">Catégorie #{{ $category->id }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                            <span class="text-xs text-slate-400">Prix</span>
                            <span class="text-sm font-bold"
                                  style="color:#059669;"
                                  x-text="prix ? Number(prix).toLocaleString('fr-FR') + ' FCFA' : '{{ number_format($category->prix, 0, ',', ' ') }} FCFA'">
                            </span>
                        </div>
                    </div>
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
                        ['ID',         '#' . $category->id],
                        ['Créé le',    \Carbon\Carbon::parse($category->created_at)->translatedFormat('d M Y')],
                        ['Mis à jour', \Carbon\Carbon::parse($category->updated_at)->translatedFormat('d M Y')],
                    ] as [$k, $v])
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-400 font-medium">{{ $k }}</span>
                        <span class="font-semibold text-slate-700 font-mono bg-slate-50 px-2 py-0.5 rounded-lg">{{ $v }}</span>
                    </div>
                    @endforeach
                </div>
            </div>


            {{-- Card : Danger zone --}}
            <div class="rounded-2xl p-4 border" style="background:#FEF2F2; border-color:#FECACA;"
                 x-data="{ confirmDelete: false }">
                <p class="text-xs font-semibold text-red-700 mb-1">Zone dangereuse</p>
                <p class="text-xs text-red-400 mb-3">Supprimer cette catégorie est irréversible.</p>

                <button type="button"
                        @click="confirmDelete = true"
                        x-show="!confirmDelete"
                        class="w-full flex items-center justify-center gap-2 py-2 rounded-xl text-xs font-semibold text-white transition-all hover:brightness-110"
                        style="background:#DC2626;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                    </svg>
                    Supprimer la catégorie
                </button>

                <div x-show="confirmDelete" x-cloak class="flex flex-col gap-2">
                    <p class="text-xs font-semibold text-red-700 text-center">Confirmer ?</p>
                    <div class="flex gap-2">
                        <button @click="confirmDelete = false"
                                type="button"
                                class="flex-1 py-1.5 rounded-lg text-xs font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">
                            Annuler
                        </button>
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="flex-1">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="w-full py-1.5 rounded-lg text-xs font-semibold text-white"
                                    style="background:#DC2626;">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        {{-- /sidebar --}}

    </div>
    {{-- /grid --}}

</div>

@endsection
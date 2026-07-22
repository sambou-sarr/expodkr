@extends('admin.layout.header')

@section('title', 'Gestion des catégories')
@section('subtitle', 'Gérez vos catégories facilement')

@section('content')

{{--
|--------------------------------------------------------------------------
| ExpoDKR Admin – Gestion des catégories (premium redesign)
| Variables : $categories
| Routes conservées : categories.create, categories.store,
|                     categories.destroy, categories.edit (si disponible)
|--------------------------------------------------------------------------
--}}

<div class="p-6 lg:p-8"
     x-data="{
         openModal: false,
         openEditModal: false,
         openDeleteId: null,
         editCat: { id: null, nom: '', prix: '', description: '' },

         openEdit(cat) {
             this.editCat = { ...cat };
             this.openEditModal = true;
         }
     }">


    {{-- ══════════════════════════════════════════════════════════════
         HEADER
         ══════════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <div class="w-1.5 h-1.5 rounded-full bg-purple-500 animate-pulse"></div>
                <span class="text-xs font-semibold tracking-widest uppercase text-slate-400">Configuration</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 leading-tight">Catégories</h1>
            <p class="text-slate-400 text-sm mt-0.5">
                <span class="font-semibold text-slate-600">{{ $categories->count() }}</span>
                catégorie{{ $categories->count() > 1 ? 's' : '' }} enregistrée{{ $categories->count() > 1 ? 's' : '' }}
            </p>
        </div>

        {{-- Bouton ajouter --}}
        <button @click="openModal = true"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition-all hover:brightness-110 active:scale-95 whitespace-nowrap"
                style="background:linear-gradient(135deg,#7C3AED,#6D28D9); box-shadow:0 4px 16px rgba(124,58,237,.3);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Ajouter une catégorie
        </button>
    </div>


    {{-- ══════════════════════════════════════════════════════════════
         STATS RAPIDES
         ══════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-8">

        <div class="bg-white rounded-2xl border border-slate-100 p-4" style="box-shadow:0 2px 12px rgba(0,0,0,.04);">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background:#F5F3FF;">
                <svg class="w-4 h-4" fill="none" stroke="#7C3AED" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-slate-800">{{ $categories->count() }}</p>
            <p class="text-xs text-slate-400 font-medium mt-0.5">Total catégories</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-4" style="box-shadow:0 2px 12px rgba(0,0,0,.04);">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background:#ECFDF5;">
                <svg class="w-4 h-4" fill="none" stroke="#059669" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25"/>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color:#059669;">
                {{ number_format($categories->avg('prix'), 0, ',', ' ') }}
            </p>
            <p class="text-xs text-slate-400 font-medium mt-0.5">Prix moyen (FCFA)</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-4" style="box-shadow:0 2px 12px rgba(0,0,0,.04);">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background:#EFF6FF;">
                <svg class="w-4 h-4" fill="none" stroke="#2563EB" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25"/>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color:#2563EB;">
                {{ $categories->sum('evenements_count') ?? '—' }}
            </p>
            <p class="text-xs text-slate-400 font-medium mt-0.5">Événements liés</p>
        </div>

    </div>


    {{-- ══════════════════════════════════════════════════════════════
         TABLEAU
         ══════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden"
         style="box-shadow:0 2px 16px rgba(0,0,0,.05);">

        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-50">
            <h2 class="text-sm font-semibold text-slate-800">Liste des catégories</h2>
            <p class="text-xs text-slate-400">Triées par <span class="font-semibold text-slate-600">ID</span></p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full" role="table">

                <thead>
                    <tr style="background:#F8FAFC; border-bottom:1px solid #F1F5F9;">
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide w-12">#</th>
                        <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide">Nom</th>
                        <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide hidden sm:table-cell">Prix</th>
                        <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide hidden lg:table-cell">Description</th>
                        <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide hidden md:table-cell">Événements</th>
                        <th class="text-right px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-50">

                    @forelse($categories as $cat)
                    <tr class="hover:bg-slate-50 transition-colors group">

                        {{-- ID --}}
                        <td class="px-6 py-4">
                            <span class="text-xs font-mono font-semibold text-slate-400 bg-slate-100 px-2 py-1 rounded-lg">
                                #{{ $cat->id }}
                            </span>
                        </td>

                        {{-- Nom --}}
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                                     style="background:linear-gradient(135deg,#7C3AED,#6D28D9);">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-semibold text-slate-800 group-hover:text-purple-700 transition-colors">
                                    {{ $cat->nom }}
                                </span>
                            </div>
                        </td>

                        {{-- Prix --}}
                        <td class="px-4 py-4 hidden sm:table-cell">
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1.5 rounded-lg"
                                  style="background:#ECFDF5; color:#059669;">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75"/>
                                </svg>
                                {{ number_format($cat->prix, 0, ',', ' ') }} FCFA
                            </span>
                        </td>

                        {{-- Description --}}
                        <td class="px-4 py-4 hidden lg:table-cell">
                            @if($cat->description)
                            <p class="text-sm text-slate-500 max-w-xs truncate">{{ $cat->description }}</p>
                            @else
                            <span class="text-slate-300 text-sm">—</span>
                            @endif
                        </td>

                        {{-- Événements liés --}}
                        <td class="px-4 py-4 hidden md:table-cell">
                            <div class="flex items-center gap-1.5">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#F8FAFC;">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-semibold text-slate-700">
                                    {{ $cat->evenements_count ?? 0 }}
                                </span>
                            </div>
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-1.5">

                                {{-- Modifier --}}
                                <button type="button"
                                        @click="openEdit({
                                            id: {{ $cat->id }},
                                            nom: '{{ addslashes($cat->nom) }}',
                                            prix: '{{ $cat->prix }}',
                                            description: '{{ addslashes($cat->description ?? '') }}'
                                        })"
                                        class="flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 text-slate-400 hover:text-amber-600 hover:border-amber-200 hover:bg-amber-50 transition-all"
                                        aria-label="Modifier {{ $cat->nom }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                                    </svg>
                                </button>

                                {{-- Supprimer avec confirmation --}}
                                <div class="relative" x-data="{ open: false }">
                                    <button type="button"
                                            @click="open = true"
                                            class="flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 text-slate-400 hover:text-red-600 hover:border-red-200 hover:bg-red-50 transition-all"
                                            aria-label="Supprimer {{ $cat->nom }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                        </svg>
                                    </button>

                                    {{-- Popover confirmation --}}
                                    <div x-show="open"
                                         x-cloak
                                         @click.outside="open = false"
                                         x-transition:enter="transition ease-out duration-150"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         class="absolute right-0 bottom-10 z-20 w-56 bg-white rounded-2xl border shadow-xl p-4"
                                         style="border-color:#FECACA;">
                                        <p class="text-xs font-semibold text-slate-800 mb-0.5">Supprimer la catégorie</p>
                                        <p class="text-xs text-slate-400 mb-3">
                                            Supprimer <span class="font-medium text-slate-600">« {{ $cat->nom }} »</span> ?
                                            Cette action est irréversible.
                                        </p>
                                        <div class="flex gap-2">
                                            <button @click="open = false"
                                                    type="button"
                                                    class="flex-1 py-1.5 rounded-lg text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                                                Annuler
                                            </button>
                                            <form method="POST"
                                                  action="{{ route('categories.destroy', $cat->id) }}"
                                                  class="flex-1">
                                                @csrf
                                                @method('DELETE')
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
                        </td>

                    </tr>

                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background:#F5F3FF;">
                                    <svg class="w-7 h-7" fill="none" stroke="#7C3AED" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-700 mb-1">Aucune catégorie créée</p>
                                    <p class="text-sm text-slate-400">Commencez par ajouter votre première catégorie.</p>
                                </div>
                                <button @click="openModal = true"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition-all hover:brightness-110"
                                        style="background:linear-gradient(135deg,#7C3AED,#6D28D9);">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                    </svg>
                                    Ajouter une catégorie
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
    {{-- /tableau --}}


    {{-- ══════════════════════════════════════════════════════════════
         MODAL : AJOUTER une catégorie
         ══════════════════════════════════════════════════════════════ --}}
    <div x-show="openModal"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         @keydown.escape.window="openModal = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background:rgba(0,0,0,.45); backdrop-filter:blur(4px);"
         role="dialog" aria-modal="true" aria-label="Ajouter une catégorie">

        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             @click.stop>

            {{-- Header modal --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-50">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#F5F3FF;">
                        <svg class="w-4 h-4" fill="none" stroke="#7C3AED" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-800">Ajouter une catégorie</h2>
                </div>
                <button @click="openModal = false"
                        class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors"
                        aria-label="Fermer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Corps modal --}}
            <form method="POST" action="{{ route('categories.store') }}" class="p-6 flex flex-col gap-4">
                @csrf

                {{-- Nom --}}
                <div>
                    <label for="cat_nom" class="block text-xs font-semibold text-slate-600 mb-1.5">
                        Nom <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="cat_nom"
                           name="nom"
                           placeholder="Ex : Conférence, Workshop, Salon…"
                           required
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-shadow">
                </div>

                {{-- Prix --}}
                <div>
                    <label for="cat_prix" class="block text-xs font-semibold text-slate-600 mb-1.5">
                        Prix (FCFA) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none" aria-hidden="true">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75"/>
                            </svg>
                        </div>
                        <input type="number"
                               id="cat_prix"
                               name="prix"
                               placeholder="Ex : 50000"
                               min="0"
                               required
                               class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-shadow">
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <label for="cat_desc" class="block text-xs font-semibold text-slate-600 mb-1.5">
                        Description
                        <span class="font-normal text-slate-400 ml-1">(optionnel)</span>
                    </label>
                    <textarea id="cat_desc"
                              name="description"
                              rows="3"
                              placeholder="Décrivez cette catégorie…"
                              class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 placeholder-slate-400 resize-none focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-shadow"></textarea>
                </div>

                {{-- Footer modal --}}
                <div class="flex gap-3 pt-2">
                    <button type="button"
                            @click="openModal = false"
                            class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50 transition-colors">
                        Annuler
                    </button>
                    <button type="submit"
                            class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-semibold text-white transition-all hover:brightness-110"
                            style="background:linear-gradient(135deg,#7C3AED,#6D28D9);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════════════
         MODAL : MODIFIER une catégorie
         ══════════════════════════════════════════════════════════════ --}}
    <div x-show="openEditModal"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         @keydown.escape.window="openEditModal = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background:rgba(0,0,0,.45); backdrop-filter:blur(4px);"
         role="dialog" aria-modal="true" aria-label="Modifier la catégorie">

        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             @click.stop>

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-50">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#FFFBEB;">
                        <svg class="w-4 h-4" fill="none" stroke="#D97706" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                        </svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-800">Modifier la catégorie</h2>
                </div>
                <button @click="openEditModal = false"
                        class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors"
                        aria-label="Fermer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Corps --}}
            <template x-if="editCat.id">
                <form :action="`/admin/categories/${editCat.id}`" method="POST" class="p-6 flex flex-col gap-4">
                    @csrf
                    @method('PUT')

                    {{-- Nom --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                            Nom <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nom"
                               x-model="editCat.nom"
                               required
                               class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow">
                    </div>

                    {{-- Prix --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                            Prix (FCFA) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none" aria-hidden="true">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75"/>
                                </svg>
                            </div>
                            <input type="number"
                                   name="prix"
                                   x-model="editCat.prix"
                                   min="0"
                                   required
                                   class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow">
                        </div>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                            Description <span class="font-normal text-slate-400 ml-1">(optionnel)</span>
                        </label>
                        <textarea name="description"
                                  x-model="editCat.description"
                                  rows="3"
                                  class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 resize-none focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow"></textarea>
                    </div>

                    {{-- Footer --}}
                    <div class="flex gap-3 pt-2">
                        <button type="button"
                                @click="openEditModal = false"
                                class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50 transition-colors">
                            Annuler
                        </button>
                        <button type="submit"
                                class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-semibold text-white transition-all hover:brightness-110"
                                style="background:linear-gradient(135deg,#D97706,#B45309);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </template>
        </div>
    </div>

</div>

@endsection
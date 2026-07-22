@extends('admin.layout.header')

@section('title', 'Gestion des Exposants')
@section('subtitle', 'Liste des exposants enregistrés')

@section('content')

{{--
|--------------------------------------------------------------------------
| ExpoDKR Admin – Liste des exposants (index complet)
| Variable : $exposants
| Routes conservées : exposants.create, exposants.show,
|                     exposants.edit, exposants.destroy
|--------------------------------------------------------------------------
--}}

<div class="p-6 lg:p-8" x-data="{ search: '' }">

    {{-- ══════════════════════════════════════════════════════════════
         HEADER
         ══════════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <div class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></div>
                <span class="text-xs font-semibold tracking-widest uppercase text-slate-400">Annuaire</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 leading-tight">Exposants</h1>
            <p class="text-slate-400 text-sm mt-0.5">
                <span class="font-semibold text-slate-600">{{ $exposants->count() }}</span>
                exposant{{ $exposants->count() > 1 ? 's' : '' }} enregistré{{ $exposants->count() > 1 ? 's' : '' }}
            </p>
        </div>

        <div class="flex items-center gap-3 flex-wrap">

            {{-- Recherche --}}
            <div class="relative">
                <div class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none" aria-hidden="true">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607z"/>
                    </svg>
                </div>
                <input type="text"
                       x-model="search"
                       placeholder="Rechercher un exposant…"
                       class="pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-white text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-56 transition-shadow"
                       aria-label="Rechercher">
            </div>

            {{-- Bouton ajouter --}}
            <a href="{{ route('exposants.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition-all hover:brightness-110 active:scale-95 whitespace-nowrap"
               style="background: linear-gradient(135deg, #2563EB, #1d4ed8); box-shadow: 0 4px 16px rgba(37,99,235,.3);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Ajouter un exposant
            </a>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════════════
         STATS RAPIDES
         ══════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @php
            $totalValides   = $exposants->where('statut', 'validé')->count();
            $totalEnAttente = $exposants->where('statut', '!=', 'validé')->count();
            $avecLogo       = $exposants->filter(fn($e) => $e->logo)->count();
        @endphp

        <div class="bg-white rounded-2xl border border-slate-100 p-4" style="box-shadow:0 2px 12px rgba(0,0,0,.04);">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background:#EFF6FF;">
                <svg class="w-4 h-4" fill="none" stroke="#2563EB" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-slate-800">{{ $exposants->count() }}</p>
            <p class="text-xs text-slate-400 font-medium mt-0.5">Total</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-4" style="box-shadow:0 2px 12px rgba(0,0,0,.04);">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background:#ECFDF5;">
                <svg class="w-4 h-4" fill="none" stroke="#059669" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color:#059669;">{{ $totalValides }}</p>
            <p class="text-xs text-slate-400 font-medium mt-0.5">Validés</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-4" style="box-shadow:0 2px 12px rgba(0,0,0,.04);">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background:#FFFBEB;">
                <svg class="w-4 h-4" fill="none" stroke="#D97706" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color:#D97706;">{{ $totalEnAttente }}</p>
            <p class="text-xs text-slate-400 font-medium mt-0.5">En attente</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-4" style="box-shadow:0 2px 12px rgba(0,0,0,.04);">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background:#F5F3FF;">
                <svg class="w-4 h-4" fill="none" stroke="#7C3AED" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color:#7C3AED;">{{ $avecLogo }}</p>
            <p class="text-xs text-slate-400 font-medium mt-0.5">Avec logo</p>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════════════
         TABLEAU
         ══════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden"
         style="box-shadow:0 2px 16px rgba(0,0,0,.05);">

        {{-- Header tableau --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-50">
            <h2 class="text-sm font-semibold text-slate-800">Liste des exposants</h2>
            <p class="text-xs text-slate-400">
                Trier par : <span class="font-semibold text-slate-600">Plus récent</span>
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full" role="table">

                <thead>
                    <tr style="background:#F8FAFC; border-bottom:1px solid #F1F5F9;">
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide">Exposant</th>
                        <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide hidden md:table-cell">Responsable</th>
                        <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide hidden lg:table-cell">Téléphone</th>
                        <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide hidden sm:table-cell">Email</th>
                        <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide">Statut</th>
                        <th class="text-right px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-50">

                    @forelse($exposants as $exposant)

                    <tr class="hover:bg-slate-50 transition-colors group"
                        x-show="search === '' || '{{ strtolower($exposant->nom_entreprise . ' ' . $exposant->responsable) }}'.includes(search.toLowerCase())">

                        {{-- Exposant : logo + nom + secteur --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">

                                {{-- Logo / Avatar --}}
                                <div class="w-11 h-11 rounded-xl overflow-hidden flex-shrink-0 flex items-center justify-center"
                                     >
                                    @if($exposant->logo)
                                        <img src="{{ Storage::url($exposant->logo) }}"
                                             alt="Logo {{ $exposant->nom_entreprise }}"
                                             class="w-full h-full object-contain p-1.5">
                                    @else
                                        <span class="text-white font-bold text-base">
                                            {{ strtoupper(substr($exposant->nom_entreprise, 0, 1)) }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Nom + secteur --}}
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-800 group-hover:text-blue-600 transition-colors truncate max-w-[160px]">
                                        {{ $exposant->nom_entreprise }}
                                    </p>
                                    @if($exposant->secteur_activite ?? $exposant->secteur ?? null)
                                    <span class="text-xs px-2 py-0.5 rounded-md mt-0.5 inline-block font-medium"
                                          style="background:#EFF6FF; color:#2563EB;">
                                        {{ $exposant->secteur_activite ?? $exposant->secteur }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Responsable --}}
                        <td class="px-4 py-4 hidden md:table-cell">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0"
                                     style="background:#F8FAFC;">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                    </svg>
                                </div>
                                <span class="text-sm text-slate-600 truncate max-w-[130px]">
                                    {{ $exposant->responsable ?? '—' }}
                                </span>
                            </div>
                        </td>

                        {{-- Téléphone --}}
                        <td class="px-4 py-4 hidden lg:table-cell">
                            @if($exposant->telephone)
                            <a href="tel:{{ $exposant->telephone }}"
                               class="flex items-center gap-1.5 text-sm text-slate-500 hover:text-blue-600 transition-colors">
                                <svg class="w-3.5 h-3.5 text-slate-300 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 6z"/>
                                </svg>
                                {{ $exposant->telephone }}
                            </a>
                            @else
                            <span class="text-slate-300 text-sm">—</span>
                            @endif
                        </td>

                        {{-- Email --}}
                        <td class="px-4 py-4 hidden sm:table-cell">
                            @if($exposant->email)
                            <a href="mailto:{{ $exposant->email }}"
                               class="text-sm text-slate-500 hover:text-blue-600 transition-colors truncate block max-w-[150px]">
                                {{ $exposant->email }}
                            </a>
                            @else
                            <span class="text-slate-300 text-sm">—</span>
                            @endif
                        </td>

                        {{-- Statut --}}
                        <td class="px-4 py-4">
                            @if($exposant->statut === 'valide')
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1.5 rounded-lg"
                                  style="background:#ECFDF5; color:#059669;">
                                <span class="w-1.5 h-1.5 rounded-full" style="background:#059669;"></span>
                                Validé
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1.5 rounded-lg"
                                  style="background:#FFFBEB; color:#D97706;">
                                <span class="w-1.5 h-1.5 rounded-full" style="background:#D97706;"></span>
                                {{ ucfirst($exposant->statut ?? 'En attente') }}
                            </span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-1.5">

                                {{-- Voir --}}
                                <a href="{{ route('exposants.show', $exposant) }}"
                                   class="flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all"
                                   aria-label="Voir {{ $exposant->nom_entreprise }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                    </svg>
                                </a>

                                {{-- Modifier --}}
                                <a href="{{ route('exposants.edit', $exposant) }}"
                                   class="flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 text-slate-400 hover:text-amber-600 hover:border-amber-200 hover:bg-amber-50 transition-all"
                                   aria-label="Modifier {{ $exposant->nom_entreprise }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/>
                                    </svg>
                                </a>

                                {{-- Supprimer avec confirmation Alpine --}}
                                <div class="relative" x-data="{ open: false }">
                                    <button type="button"
                                            @click="open = true"
                                            class="flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 text-slate-400 hover:text-red-600 hover:border-red-200 hover:bg-red-50 transition-all"
                                            aria-label="Supprimer {{ $exposant->nom_entreprise }}">
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
                                        <p class="text-xs font-semibold text-slate-800 mb-0.5">Confirmer la suppression</p>
                                        <p class="text-xs text-slate-400 mb-3">
                                            Supprimer <span class="font-medium text-slate-600">{{ Str::limit($exposant->nom_entreprise, 22) }}</span> ?
                                            Cette action est irréversible.
                                        </p>
                                        <div class="flex gap-2">
                                            <button @click="open = false"
                                                    type="button"
                                                    class="flex-1 py-1.5 rounded-lg text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                                                Annuler
                                            </button>
                                            <form method="POST"
                                                  action="{{ route('exposants.destroy', $exposant) }}"
                                                  class="flex-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="w-full py-1.5 rounded-lg text-xs font-semibold text-white transition-colors"
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

                    {{-- État vide --}}
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background:#F1F5F9;">
                                    <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-700 mb-1">Aucun exposant enregistré</p>
                                    <p class="text-sm text-slate-400">Commencez par ajouter votre premier exposant.</p>
                                </div>
                                <a href="{{ route('exposants.create') }}"
                                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition-all hover:brightness-110"
                                   style="background:linear-gradient(135deg,#2563EB,#1d4ed8);">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                    </svg>
                                    Ajouter un exposant
                                </a>
                            </div>
                        </td>
                    </tr>

                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if(method_exists($exposants, 'hasPages') && $exposants->hasPages())
        <div class="flex items-center justify-between px-6 py-4 border-t border-slate-50">
            <p class="text-xs text-slate-400">
                <span class="font-semibold text-slate-600">{{ $exposants->firstItem() }}</span>
                –
                <span class="font-semibold text-slate-600">{{ $exposants->lastItem() }}</span>
                sur
                <span class="font-semibold text-slate-600">{{ $exposants->total() }}</span>
                exposants
            </p>
            <nav class="flex items-center gap-1.5" aria-label="Pagination">
                @if($exposants->onFirstPage())
                <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-100 text-slate-300 cursor-not-allowed">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                    </svg>
                </span>
                @else
                <a href="{{ $exposants->previousPageUrl() }}"
                   class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:border-blue-300 hover:text-blue-600 hover:bg-blue-50 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                    </svg>
                </a>
                @endif

                @foreach($exposants->getUrlRange(max(1,$exposants->currentPage()-2), min($exposants->lastPage(),$exposants->currentPage()+2)) as $page => $url)
                    @if($page == $exposants->currentPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-semibold text-white"
                          style="background:linear-gradient(135deg,#2563EB,#1d4ed8);">{{ $page }}</span>
                    @else
                    <a href="{{ $url }}"
                       class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-xs font-medium text-slate-500 hover:border-blue-300 hover:text-blue-600 hover:bg-blue-50 transition-all">
                        {{ $page }}
                    </a>
                    @endif
                @endforeach

                @if($exposants->hasMorePages())
                <a href="{{ $exposants->nextPageUrl() }}"
                   class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:border-blue-300 hover:text-blue-600 hover:bg-blue-50 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                    </svg>
                </a>
                @else
                <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-100 text-slate-300 cursor-not-allowed">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                    </svg>
                </span>
                @endif
            </nav>
        </div>
        @endif

    </div>
    {{-- /table card --}}

</div>

@endsection
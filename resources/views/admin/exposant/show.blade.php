@extends('admin.layout.header')

@section('title', $exposant->nom_entreprise)
@section('subtitle', 'Fiche détaillée de l\'exposant')

@section('content')

{{--
|--------------------------------------------------------------------------
| ExpoDKR Admin – Voir un exposant (vue administrateur)
| Variable : $exposant (avec ->evenements, ->categorie)
| Routes conservées : exposants.edit, exposants.destroy, exposants.index
|--------------------------------------------------------------------------
--}}

<div x-data="{ confirmDelete: false, activeTab: 'infos' }">

    {{-- ══════════════════════════════════════════════════════════════
         BREADCRUMB + ACTIONS TOP
         ══════════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 px-6 lg:px-8 pt-6 pb-4">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-xs text-slate-400" aria-label="Fil d'Ariane">
            <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition-colors">Dashboard</a>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
            </svg>
            <a href="{{ route('exposants.index') }}" class="hover:text-slate-600 transition-colors">Exposants</a>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
            </svg>
            <span class="text-slate-600 font-medium truncate max-w-[200px]">{{ $exposant->nom_entreprise }}</span>
        </nav>

        {{-- Actions admin --}}
        <div class="flex items-center gap-2 flex-wrap">

            {{-- Badge statut --}}
            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-xl"
                  style="{{ $exposant->statut === 'validé' ? 'background:#ECFDF5; color:#059669;' : 'background:#FFFBEB; color:#D97706;' }}">
                <span class="w-1.5 h-1.5 rounded-full"
                      style="{{ $exposant->statut === 'validé' ? 'background:#059669;' : 'background:#D97706;' }}"></span>
                {{ ucfirst($exposant->statut ?? 'En attente') }}
            </span>

            {{-- Modifier --}}
            <a href="{{ route('exposants.edit', $exposant) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold border border-slate-200 bg-white text-slate-600 hover:border-amber-300 hover:text-amber-600 hover:bg-amber-50 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                </svg>
                Modifier
            </a>

            {{-- Supprimer --}}
            <button @click="confirmDelete = true"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold border border-slate-200 bg-white text-slate-600 hover:border-red-300 hover:text-red-600 hover:bg-red-50 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                </svg>
                Supprimer
            </button>
        </div>
    </div>


    {{-- Modal confirmation suppression --}}
    <div x-show="confirmDelete"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background:rgba(0,0,0,.45); backdrop-filter:blur(4px);">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:#FEF2F2;">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-800 text-center mb-1">Supprimer l'exposant</h3>
            <p class="text-sm text-slate-500 text-center mb-6">
                <span class="font-medium text-slate-700">« {{ Str::limit($exposant->nom_entreprise, 35) }} »</span>
                sera définitivement supprimé. Cette action est irréversible.
            </p>
            <div class="flex gap-3">
                <button @click="confirmDelete = false"
                        class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50 transition-colors">
                    Annuler
                </button>
                <form action="{{ route('exposants.destroy', $exposant) }}" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="w-full py-2.5 rounded-xl text-sm font-semibold text-white"
                            style="background:#DC2626;">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════════════
         HERO PROFIL
         ══════════════════════════════════════════════════════════════ --}}
    <div class="mx-6 lg:mx-8 mb-8">
        <div class="relative rounded-3xl overflow-hidden" style="background:linear-gradient(135deg,#0A1628 0%,#0D2145 60%,#1E3A70 100%); min-height:200px;">

            {{-- Décor grille --}}
            <div class="absolute inset-0 opacity-15"
                 style="background-image:linear-gradient(rgba(196,168,76,.4) 1px,transparent 1px),linear-gradient(90deg,rgba(196,168,76,.4) 1px,transparent 1px); background-size:50px 50px;"
                 aria-hidden="true"></div>
            <div class="absolute -top-10 -right-10 w-64 h-64 rounded-full opacity-10"
                 style="background:#2563EB; filter:blur(80px);" aria-hidden="true"></div>

            <div class="relative z-10 p-8 flex flex-col sm:flex-row items-start sm:items-end gap-6">

                {{-- Logo grand format image--}}
                <div class="w-24 h-24 rounded-2xl overflow-hidden flex items-center justify-center flex-shrink-0 border-4 border-white/15 shadow-2xl"
                     style="background:white;">
                    @if($exposant->logo)
                        <img src="{{ Storage::url($exposant->logo) }}"
                             alt="Logo {{ $exposant->nom_entreprise }}"
                             class="w-full h-full object-contain p-2">
                    @else
                        <span class="font-bold text-4xl" style="color:#2563EB;">
                            {{ strtoupper(substr($exposant->nom_entreprise, 0, 1)) }}
                        </span>
                    @endif
                </div>

                {{-- Infos hero --}}
                <div class="flex-1 min-w-0">
                    {{-- Secteur badge --}}
                    @if($exposant->secteur_activite ?? $exposant->secteur ?? null)
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full border border-white/15 bg-white/5 mb-3">
                        <span class="w-1.5 h-1.5 rounded-full" style="background:#C9A84C;"></span>
                        <span class="text-xs font-semibold tracking-widest uppercase" style="color:#E8C96A;">
                            {{ $exposant->secteur_activite ?? $exposant->secteur }}
                        </span>
                    </div>
                    @endif

                    <h1 class="text-3xl lg:text-4xl font-bold text-white leading-tight mb-2">
                        {{ $exposant->nom_entreprise }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-4">
                        @if($exposant->responsable)
                        <div class="flex items-center gap-2 text-white/65 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                            </svg>
                            {{ $exposant->responsable }}
                        </div>
                        @endif

                        @if(isset($exposant->evenements) && $exposant->evenements->count())
                        <div class="flex items-center gap-2 text-white/65 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25"/>
                            </svg>
                            {{ $exposant->evenements->count() }} événement{{ $exposant->evenements->count() > 1 ? 's' : '' }}
                        </div>
                        @endif

                        <div class="flex items-center gap-2 text-xs text-white/40">
                            <span>ID #{{ $exposant->id }}</span>
                            <span>·</span>
                            <span>Créé le {{ \Carbon\Carbon::parse($exposant->created_at)->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════════════
         STATS RAPIDES
         ══════════════════════════════════════════════════════════════ --}}
    <div class="px-6 lg:px-8 mb-6">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach([
                ['Événements',    isset($exposant->evenements) ? $exposant->evenements->count() : 0, '#2563EB', '#EFF6FF', '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25"/>'],
                ['Statut',        ucfirst($exposant->statut ?? 'En attente'), $exposant->statut === 'validé' ? '#059669' : '#D97706', $exposant->statut === 'validé' ? '#ECFDF5' : '#FFFBEB', '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>'],
                ['Ancienneté',    \Carbon\Carbon::parse($exposant->created_at)->diffInMonths(now()) . ' mois', '#7C3AED', '#F5F3FF', '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>'],
                ['Mise à jour',   \Carbon\Carbon::parse($exposant->updated_at)->translatedFormat('d M Y'), '#D97706', '#FFFBEB', '<path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182"/>'],
            ] as [$label, $value, $color, $bg, $icon])
            <div class="bg-white rounded-2xl border border-slate-100 p-4" style="box-shadow:0 2px 12px rgba(0,0,0,.04);">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background:{{ $bg }};">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="{{ $color }}" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                        {!! $icon !!}
                    </svg>
                </div>
                <p class="text-lg font-bold text-slate-800 leading-tight">{{ $value }}</p>
                <p class="text-xs text-slate-400 font-medium mt-0.5">{{ $label }}</p>
            </div>
            @endforeach
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════════════
         ONGLETS NAVIGATION
         ══════════════════════════════════════════════════════════════ --}}
    <div class="px-6 lg:px-8 mb-6">
        <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl w-fit">
            <button @click="activeTab = 'infos'"
                    class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200"
                    :class="activeTab === 'infos' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'">
                Informations
            </button>
            <button @click="activeTab = 'events'"
                    class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200"
                    :class="activeTab === 'events' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'">
                Événements
                @isset($exposant->evenements)
                <span class="ml-1.5 text-xs px-1.5 py-0.5 rounded-md"
                      :class="activeTab === 'events' ? 'bg-blue-100 text-blue-600' : 'bg-slate-200 text-slate-500'">
                    {{ $exposant->evenements->count() }}
                </span>
                @endisset
            </button>
            <button @click="activeTab = 'admin'"
                    class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200"
                    :class="activeTab === 'admin' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'">
                Admin
            </button>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════════════
         CONTENU PRINCIPAL
         ══════════════════════════════════════════════════════════════ --}}
    <div class="px-6 lg:px-8 pb-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ────────────────────────────────────────────────────
                 COLONNE PRINCIPALE
                 ──────────────────────────────────────────────────── --}}
            <div class="lg:col-span-2 flex flex-col gap-6">

                {{-- ── ONGLET : Informations ── --}}
                <div x-show="activeTab === 'infos'" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="flex flex-col gap-6">

                    {{-- Description --}}
                    @if($exposant->description)
                    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden" style="box-shadow:0 2px 16px rgba(0,0,0,.05);">
                        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#EFF6FF;">
                                <svg class="w-4 h-4" fill="none" stroke="#2563EB" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/>
                                </svg>
                            </div>
                            <h2 class="text-sm font-semibold text-slate-800">Description</h2>
                        </div>
                        <div class="p-6">
                            <p class="text-sm leading-relaxed text-slate-600 whitespace-pre-line">{{ $exposant->description }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Coordonnées complètes --}}
                    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden" style="box-shadow:0 2px 16px rgba(0,0,0,.05);">
                        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#ECFDF5;">
                                <svg class="w-4 h-4" fill="none" stroke="#059669" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 6z"/>
                                </svg>
                            </div>
                            <h2 class="text-sm font-semibold text-slate-800">Coordonnées</h2>
                        </div>
                        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                            @foreach([
                                ['Responsable',   $exposant->responsable ?? '—',   'M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z', null],
                                ['Téléphone',     $exposant->telephone ?? '—',      'M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 6z', $exposant->telephone ? 'tel:'.$exposant->telephone : null],
                                ['Email',         $exposant->email ?? '—',          'M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75', $exposant->email ? 'mailto:'.$exposant->email : null],
                                ['Site web',      $exposant->site_web ?? '—',       'M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3', $exposant->site_web],
                            ] as [$label, $value, $iconPath, $href])
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#F8FAFC;">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-400 mb-0.5">{{ $label }}</p>
                                    @if($href && $value !== '—')
                                    <a href="{{ $href }}"
                                       {{ str_starts_with($href, 'http') ? 'target=_blank rel=noopener noreferrer' : '' }}
                                       class="text-sm font-medium text-blue-600 hover:underline truncate block max-w-[200px]">
                                        {{ $value }}
                                    </a>
                                    @else
                                    <p class="text-sm font-semibold text-slate-700 truncate max-w-[200px]">{{ $value }}</p>
                                    @endif
                                </div>
                            </div>
                            @endforeach

                            {{-- LinkedIn --}}
                            @if($exposant->linkedin ?? null)
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#EFF6FF;">
                                    <svg class="w-3.5 h-3.5" fill="#0A66C2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-400 mb-0.5">LinkedIn</p>
                                    <a href="{{ $exposant->linkedin }}" target="_blank" rel="noopener noreferrer"
                                       class="text-sm font-medium text-blue-600 hover:underline">
                                        Voir le profil LinkedIn
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ── ONGLET : Événements ── --}}
                <div x-show="activeTab === 'events'" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0">

                    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden" style="box-shadow:0 2px 16px rgba(0,0,0,.05);">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-50">
                            <h2 class="text-sm font-semibold text-slate-800">Événements organisés</h2>
                            @isset($exposant->evenements)
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-lg" style="background:#EFF6FF; color:#2563EB;">
                                {{ $exposant->evenements->count() }} au total
                            </span>
                            @endisset
                        </div>

                        @isset($exposant->evenements)
                        @if($exposant->evenements->count())
                        <div class="divide-y divide-slate-50">
                            @foreach($exposant->evenements as $evenement)
                            @php
                                $now   = now();
                                $debut = \Carbon\Carbon::parse($evenement->date_debut);
                                $fin   = \Carbon\Carbon::parse($evenement->date_fin);
                                if ($now->lt($debut))               { $sl='À venir';  $sc='#059669'; $sb='#ECFDF5'; }
                                elseif ($now->between($debut,$fin)) { $sl='En cours'; $sc='#D97706'; $sb='#FFFBEB'; }
                                else                                { $sl='Terminé';  $sc='#9CA3AF'; $sb='#F1F5F9'; }
                            @endphp
                            <div class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50 transition-colors group">

                                {{-- Image --}}
                                <div class="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0"
                                     style="background:linear-gradient(135deg,#0A1628,#2563EB);">
                                    @if($evenement->image)
                                    <img src="{{ storage::url($evenement->image) }}" alt="{{ $evenement->titre }}" class="w-full h-full object-cover">
                                    @endif
                                </div>

                                {{-- Infos --}}
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-slate-800 group-hover:text-blue-600 transition-colors truncate">
                                        {{ $evenement->titre }}
                                    </p>
                                    <div class="flex items-center gap-3 mt-0.5">
                                        <span class="text-xs text-slate-400">{{ $debut->translatedFormat('d M Y') }}</span>
                                        <span class="text-slate-200">·</span>
                                        <span class="text-xs text-slate-400">{{ $evenement->lieu }}</span>
                                    </div>
                                </div>

                                {{-- Statut --}}
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-lg flex-shrink-0"
                                      style="background:{{ $sb }}; color:{{ $sc }};">
                                    {{ $sl }}
                                </span>

                                {{-- Action --}}
                                <a href="{{ route('events.show', $evenement->id) }}"
                                   class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all"
                                   aria-label="Voir {{ $evenement->titre }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                                    </svg>
                                </a>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="py-16 text-center">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-3" style="background:#F1F5F9;">
                                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25"/>
                                </svg>
                            </div>
                            <p class="text-sm text-slate-400">Aucun événement lié à cet exposant.</p>
                        </div>
                        @endif
                        @endisset
                    </div>
                </div>

                {{-- ── ONGLET : Admin ── --}}
                <div x-show="activeTab === 'admin'" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0">

                    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden" style="box-shadow:0 2px 16px rgba(0,0,0,.05);">
                        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#FEF2F2;">
                                <svg class="w-4 h-4" fill="none" stroke="#DC2626" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                                </svg>
                            </div>
                            <h2 class="text-sm font-semibold text-slate-800">Actions administrateur</h2>
                        </div>
                        <div class="p-6 flex flex-col gap-4">

                            {{-- Changer statut --}}
                            <div class="flex items-center justify-between p-4 rounded-xl border border-slate-100" style="background:#F8FAFC;">
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">Statut actuel</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Valider ou mettre en attente l'exposant</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($exposant->statut === 'validé')
                                    <form action="" method="POST">
                                        @csrf @method('PUT')
                                        <button type="submit"
                                                class="px-4 py-2 rounded-xl text-xs font-semibold border border-slate-200 text-slate-600 hover:bg-slate-100 transition-colors">
                                            Mettre en attente
                                        </button>
                                    </form>
                                    @else
                                    <form action="" method="POST">
                                        @csrf @method('PUT')
                                        <button type="submit"
                                                class="px-4 py-2 rounded-xl text-xs font-semibold text-white transition-all hover:brightness-110"
                                                style="background:#059669;">
                                            Valider l'exposant
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>

                            {{-- Danger zone --}}
                            <div class="p-4 rounded-xl border" style="background:#FEF2F2; border-color:#FECACA;">
                                <p class="text-xs font-semibold text-red-700 mb-1">Zone dangereuse</p>
                                <p class="text-xs text-red-500 mb-3">La suppression de cet exposant est irréversible.</p>
                                <button @click="confirmDelete = true"
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold text-white transition-all hover:brightness-110"
                                        style="background:#DC2626;">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                    </svg>
                                    Supprimer définitivement
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            {{-- /colonne principale --}}


            {{-- ────────────────────────────────────────────────────
                 SIDEBAR
                 ──────────────────────────────────────────────────── --}}
            <div class="flex flex-col gap-5">

                {{-- Card : Actions rapides --}}
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden" style="box-shadow:0 2px 16px rgba(0,0,0,.05);">
                    <div class="px-5 py-4 border-b border-slate-50">
                        <h3 class="text-sm font-semibold text-slate-800">Actions rapides</h3>
                    </div>
                    <div class="p-5 flex flex-col gap-2.5">
                        <a href="{{ route('exposants.edit', $exposant) }}"
                           class="w-full flex items-center gap-3 py-2.5 px-3 rounded-xl text-sm font-semibold border border-slate-200 text-slate-600 hover:border-amber-300 hover:text-amber-600 hover:bg-amber-50 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                            </svg>
                            Modifier la fiche
                        </a>
                        @if($exposant->email)
                        <a href="mailto:{{ $exposant->email }}"
                           class="w-full flex items-center gap-3 py-2.5 px-3 rounded-xl text-sm font-semibold border border-slate-200 text-slate-600 hover:border-blue-300 hover:text-blue-600 hover:bg-blue-50 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25"/>
                            </svg>
                            Contacter par email
                        </a>
                        @endif
                        @if($exposant->telephone)
                        <a href="tel:{{ $exposant->telephone }}"
                           class="w-full flex items-center gap-3 py-2.5 px-3 rounded-xl text-sm font-semibold border border-slate-200 text-slate-600 hover:border-green-300 hover:text-green-600 hover:bg-green-50 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 6z"/>
                            </svg>
                            Appeler
                        </a>
                        @endif
                        @if($exposant->site_web)
                        <a href="{{ $exposant->site_web }}" target="_blank" rel="noopener noreferrer"
                           class="w-full flex items-center gap-3 py-2.5 px-3 rounded-xl text-sm font-semibold text-white transition-all hover:brightness-110"
                           style="background:linear-gradient(135deg,#2563EB,#1d4ed8);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                            </svg>
                            Visiter le site
                        </a>
                        @endif

                        <hr class="border-slate-50">

                        <a href="{{ route('exposants.index') }}"
                           class="w-full flex items-center gap-3 py-2.5 px-3 rounded-xl text-sm font-medium text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                            </svg>
                            Retour à la liste
                        </a>
                    </div>
                </div>

                {{-- Card : Méta système logo--}}
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden" style="box-shadow:0 2px 12px rgba(0,0,0,.04);">
                    <div class="px-5 py-4 border-b border-slate-50">
                        <h3 class="text-sm font-semibold text-slate-800">Informations système</h3>
                    </div>
                    <div class="p-5 flex flex-col gap-3">
                        @foreach([
                            ['ID exposant',   '#' . $exposant->id],
                            ['Créé le',       \Carbon\Carbon::parse($exposant->created_at)->translatedFormat('d M Y à H:i')],
                            ['Mis à jour',    \Carbon\Carbon::parse($exposant->updated_at)->translatedFormat('d M Y à H:i')],
                            ['Statut',        ucfirst($exposant->statut ?? 'En attente')],
                        ] as [$k, $v])
                        <div class="flex items-start justify-between gap-3 text-xs">
                            <span class="text-slate-400 font-medium flex-shrink-0">{{ $k }}</span>
                            <span class="font-semibold text-slate-700 text-right font-mono bg-slate-50 px-2 py-0.5 rounded-lg max-w-[160px] truncate">
                                {{ $v }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
            {{-- /sidebar --}}

        </div>
    </div>

</div>

@endsection
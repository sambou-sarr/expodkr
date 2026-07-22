@extends('admin.layout.header')

@section('title', $event->titre)
@section('subtitle', 'Détail complet de l\'événement')

@section('content')

{{--
|--------------------------------------------------------------------------
| ExpoDKR Admin – Voir un événement (vue administrateur)
| Variables : $event (avec ->categorie, ->exposant)
| Routes, variables Blade et logique 100% conservés
|--------------------------------------------------------------------------
--}}

<div x-data="{ share: false, confirmDelete: false }">

    {{-- ══════════════════════════════════════════════════════════════
         BREADCRUMB + ACTIONS TOP
         ══════════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 px-6 lg:px-8 pt-6 pb-4">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-xs text-slate-400" aria-label="Fil d'Ariane">
            <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition-colors">Dashboard</a>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            <a href="{{ route('events.index') }}" class="hover:text-slate-600 transition-colors">Événements</a>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            <span class="text-slate-600 font-medium truncate max-w-[200px]">{{ $event->titre }}</span>
        </nav>

        {{-- Boutons d'action admin --}}
        <div class="flex items-center gap-2 flex-wrap">

            {{-- Badge statut --}}
            @php
                $now   = now();
                $debut = \Carbon\Carbon::parse($event->date_debut);
                $fin   = \Carbon\Carbon::parse($event->date_fin);
                $statut = $event->statut ?? null;
                if (!$statut) {
                    if ($now->lt($debut))               $statut = 'ouvert';
                    elseif ($now->between($debut,$fin)) $statut = 'live';
                    else                                $statut = 'termine';
                }
                $statusMap = [
                    'ouvert'   => ['À venir',  '#059669', '#ECFDF5'],
                    'live'     => ['En cours', '#D97706', '#FFFBEB'],
                    'termine'  => ['Terminé',  '#9CA3AF', '#F1F5F9'],
                    'brouillon'=> ['Brouillon','#D97706', '#FFFBEB'],
                ];
                [$statusLabel, $statusColor, $statusBg] = $statusMap[$statut] ?? ['Inconnu','#9CA3AF','#F1F5F9'];
                $duree = $debut->diffInDays($fin) + 1;
            @endphp

            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-xl"
                  style="background:{{ $statusBg }}; color:{{ $statusColor }};">
                <span class="w-1.5 h-1.5 rounded-full {{ $statut === 'live' ? 'animate-pulse' : '' }}"
                      style="background:{{ $statusColor }};"></span>
                {{ $statusLabel }}
            </span>

            {{-- Modifier --}}
            <a href="{{ route('events.edit', $event->id) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold border border-slate-200 bg-white text-slate-600 hover:border-amber-300 hover:text-amber-600 hover:bg-amber-50 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/>
                </svg>
                Modifier
            </a>

            {{-- Supprimer --}}
            <button type="button" @click="confirmDelete = true"
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
            <h3 class="text-base font-bold text-slate-800 text-center mb-1">Supprimer l'événement</h3>
            <p class="text-sm text-slate-500 text-center mb-6">
                <span class="font-medium text-slate-700">« {{ Str::limit($event->titre, 40) }} »</span> sera définitivement supprimé. Cette action est irréversible.
            </p>
            <div class="flex gap-3">
                <button @click="confirmDelete = false"
                        type="button"
                        class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50 transition-colors">
                    Annuler
                </button>
                <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="w-full py-2.5 rounded-xl text-sm font-semibold text-white transition-colors"
                            style="background:#DC2626;">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════════════
         HERO IMAGE
         ══════════════════════════════════════════════════════════════ --}}
    <div class="relative mx-6 lg:mx-8 rounded-3xl overflow-hidden mb-8" style="height:380px;">

        {{-- Image --}}
        <img src="{{ Storage::url($event->image) }}"
             alt="{{ $event->titre }}"
             class="w-full h-full object-cover">

        {{-- Overlay gradient --}}
        <div class="absolute inset-0"
             style="background:linear-gradient(to top, rgba(10,22,40,.92) 0%, rgba(10,22,40,.5) 50%, rgba(10,22,40,.15) 100%);"
             aria-hidden="true"></div>

        {{-- Contenu hero --}}
        <div class="absolute inset-0 flex flex-col justify-end p-8 lg:p-12">

            {{-- Badges --}}
            <div class="flex flex-wrap items-center gap-2 mb-4">
                @if($event->categorie)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full backdrop-blur-sm"
                      style="background:rgba(37,99,235,.75); color:white;">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 0 1 0 1.414l-7 7a1 1 0 0 1-1.414 0l-7-7A.997.997 0 0 1 2 10V5a3 3 0 0 1 3-3h5c.256 0 .512.098.707.293l7 7z" clip-rule="evenodd"/>
                    </svg>
                    {{ $event->categorie->nom }}
                </span>
                @endif

                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full"
                      style="background:{{ $statusBg }}; color:{{ $statusColor }};">
                    <span class="w-1.5 h-1.5 rounded-full" style="background:{{ $statusColor }};"></span>
                    {{ $statusLabel }}
                </span>

                <span class="text-xs text-white/50 px-3 py-1 rounded-full" style="background:rgba(255,255,255,.1);">
                    ID #{{ $event->id }}
                </span>
            </div>

            {{-- Titre --}}
            <h1 class="font-display text-4xl lg:text-5xl font-bold text-white leading-tight mb-4">
                {{ $event->titre }}
            </h1>

            {{-- Méta --}}
            <div class="flex flex-wrap items-center gap-5">
                <div class="flex items-center gap-2 text-white/75 text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                    </svg>
                    {{ $event->lieu }}
                </div>
                <div class="flex items-center gap-2 text-white/75 text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5"/>
                    </svg>
                    {{ $debut->translatedFormat('d M Y') }}
                    @if($debut->format('d M Y') !== $fin->translatedFormat('d M Y'))
                        <span class="text-white/40">→</span>
                        {{ $fin->translatedFormat('d M Y') }}
                    @endif
                </div>
                <div class="flex items-center gap-2 text-white/75 text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    {{ $duree }} jour{{ $duree > 1 ? 's' : '' }}
                </div>
                <div class="flex items-center gap-2 text-sm font-semibold" style="color:#E8C96A;">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25"/>
                    </svg>
                    {{ isset($event->prix) ? number_format($event->prix, 0, ',', ' ') . ' FCFA' : 'Gratuit' }}
                </div>
            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════════════
         CONTENU PRINCIPAL
         ══════════════════════════════════════════════════════════════ --}}
    <div class="px-6 lg:px-8 pb-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-7">


            {{-- ────────────────────────────────────────────────────
                 COLONNE GAUCHE (contenu)
                 ──────────────────────────────────────────────────── --}}
            <div class="lg:col-span-2 flex flex-col gap-6">

                {{-- Card : Stats rapides admin --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach([
                        ['Participants', $event->stands_count ?? 0,   '#2563EB', '#EFF6FF', '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Z"/>'],
                        ['Stands',       $event->stands_count ?? 0,   '#7C3AED', '#F5F3FF', '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>'],
                        ['Billets vendus', 0,                         '#059669', '#ECFDF5', '<path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026"/>'],
                        ['Durée (j)',    $duree,                      '#D97706', '#FFFBEB', '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>'],
                    ] as [$label, $value, $color, $bg, $icon])
                    <div class="bg-white rounded-2xl border border-slate-100 p-4" style="box-shadow:0 2px 12px rgba(0,0,0,.04);">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background:{{ $bg }};">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="{{ $color }}" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                {!! $icon !!}
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-slate-800">{{ $value }}</p>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">{{ $label }}</p>
                    </div>
                    @endforeach
                </div>


                {{-- Card : Description --}}
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden" style="box-shadow:0 2px 16px rgba(0,0,0,.05);">
                    <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#EFF6FF;">
                            <svg class="w-4 h-4" fill="none" stroke="#2563EB" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/>
                            </svg>
                        </div>
                        <h2 class="text-sm font-semibold text-slate-800">Description de l'événement</h2>
                    </div>
                    <div class="p-6">
                        @if($event->description)
                        <div class="text-sm leading-relaxed text-slate-600 whitespace-pre-line">{{ $event->description }}</div>
                        @else
                        <p class="text-sm text-slate-400 italic">Aucune description renseignée.</p>
                        @endif
                    </div>
                </div>


                {{-- Card : Dates & Lieu image--}}
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden" style="box-shadow:0 2px 16px rgba(0,0,0,.05);">
                    <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#F5F3FF;">
                            <svg class="w-4 h-4" fill="none" stroke="#7C3AED" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5"/>
                            </svg>
                        </div>
                        <h2 class="text-sm font-semibold text-slate-800">Dates & Lieu</h2>
                    </div>
                    <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-5">

                        <div class="flex flex-col gap-1.5">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Date de début</p>
                            <p class="text-sm font-semibold text-slate-800">{{ $debut->translatedFormat('l d M Y') }}</p>
                            <span class="text-xs px-2 py-0.5 rounded-md w-fit font-medium" style="background:#ECFDF5; color:#059669;">
                                J – {{ max(0, $now->diffInDays($debut, false)) }} jours
                            </span>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Date de fin</p>
                            <p class="text-sm font-semibold text-slate-800">{{ $fin->translatedFormat('l d M Y') }}</p>
                            <span class="text-xs px-2 py-0.5 rounded-md w-fit font-medium" style="background:#F5F3FF; color:#7C3AED;">
                                {{ $duree }} jour{{ $duree > 1 ? 's' : '' }} au total
                            </span>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Lieu</p>
                            <p class="text-sm font-semibold text-slate-800">{{ $event->lieu }}</p>
                            <a href="https://maps.google.com/?q={{ urlencode($event->lieu) }}"
                               target="_blank" rel="noopener noreferrer"
                               class="text-xs font-semibold flex items-center gap-1 transition-colors"
                               style="color:#2563EB;"
                               onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                                </svg>
                                Voir sur la carte
                            </a>
                        </div>
                    </div>
                </div>


                {{-- Card : Exposant / Organisateur --}}
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden" style="box-shadow:0 2px 16px rgba(0,0,0,.05);">
                    <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-50">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#ECFDF5;">
                            <svg class="w-4 h-4" fill="none" stroke="#059669" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                            </svg>
                        </div>
                        <h2 class="text-sm font-semibold text-slate-800">Organisateur</h2>
                    </div>
                    <div class="p-6">
                        @if($event->exposant)
                        <div class="flex flex-col sm:flex-row gap-5">
                            {{-- Logo --}}
                            <div class="w-16 h-16 rounded-2xl overflow-hidden flex-shrink-0 flex items-center justify-center"
                                 style="background:linear-gradient(135deg,#0A1628,#2563EB);">
                                @if($event->exposant->logo)
                                
                                    <img src="{{ Storage::url($event->exposant->logo) }}" alt="" class="w-full h-full object-contain p-2">
                                @else
                                    <span class="text-white font-bold text-2xl">{{ strtoupper(substr($event->exposant->nom_entreprise, 0, 1)) }}</span>
                                @endif
                            </div>
                            {{-- Infos --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <h3 class="font-bold text-slate-800">{{ $event->exposant->nom_entreprise }}</h3>
                                    @if($event->exposant->secteur_activite ?? $event->exposant->secteur ?? null)
                                    <span class="text-xs px-2 py-0.5 rounded-md font-medium" style="background:#EFF6FF; color:#2563EB;">
                                        {{ $event->exposant->secteur_activite ?? $event->exposant->secteur }}
                                    </span>
                                    @endif
                                </div>
                                @if($event->exposant->responsable)
                                <p class="text-sm text-slate-500 mb-3">{{ $event->exposant->responsable }}</p>
                                @endif
                                <div class="flex flex-wrap gap-3">
                                    @if($event->exposant->telephone)
                                    <a href="tel:{{ $event->exposant->telephone }}"
                                       class="flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:border-blue-300 hover:text-blue-600 transition-all">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 6z"/>
                                        </svg>
                                        {{ $event->exposant->telephone }}
                                    </a>
                                    @endif
                                    @if($event->exposant->email)
                                    <a href="mailto:{{ $event->exposant->email }}"
                                       class="flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:border-blue-300 hover:text-blue-600 transition-all">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25"/>
                                        </svg>
                                        {{ $event->exposant->email }}
                                    </a>
                                    @endif
                                    @if($event->exposant->site_web)
                                    <a href="{{ $event->exposant->site_web }}" target="_blank" rel="noopener noreferrer"
                                       class="flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg text-white transition-all hover:brightness-110"
                                       style="background:linear-gradient(135deg,#2563EB,#1d4ed8);">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3"/>
                                        </svg>
                                        Site web
                                    </a>
                                    @endif
                                    <a href="{{ route('exposants.show', $event->exposant->id) }}"
                                       class="text-xs font-semibold px-3 py-1.5 rounded-lg border border-slate-200 text-slate-500 hover:border-slate-300 hover:text-slate-700 transition-all">
                                        Voir le profil →
                                    </a>
                                </div>
                            </div>
                        </div>
                        @else
                        {{-- Fallback ExpoDakar --}}
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0"
                                 style="background:linear-gradient(135deg,#0A1628,#2563EB);">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 mb-0.5">ExpoDakar</h3>
                                <span class="text-xs px-2 py-0.5 rounded-md font-medium" style="background:#ECFDF5; color:#059669;">Organisateur vérifié</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

            </div>
            {{-- /colonne gauche --}}


            {{-- ────────────────────────────────────────────────────
                 SIDEBAR DROITE
                 ──────────────────────────────────────────────────── --}}
            <div class="flex flex-col gap-5">

                {{-- Card : Prix + Réservation --}}
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden" style="box-shadow:0 8px 32px rgba(0,0,0,.08); position:sticky; top:5rem;">

                    {{-- Header prix --}}
                    <div class="px-6 py-5 text-center" style="background:linear-gradient(135deg,#0A1628,#0D2145);">
                        @if(isset($event->prix) && $event->prix > 0)
                        <p class="text-xs font-medium mb-1" style="color:rgba(255,255,255,.5);">Prix de participation</p>
                        <p class="text-4xl font-bold text-white mb-0.5">
                            {{ number_format($event->prix, 0, ',', ' ') }}
                        </p>
                        <p class="text-sm font-medium" style="color:rgba(255,255,255,.5);">FCFA</p>
                        @else
                        <p class="text-xs font-medium mb-2" style="color:rgba(255,255,255,.5);">Accès</p>
                        <p class="text-3xl font-bold" style="color:#10B981;">Gratuit</p>
                        @endif
                    </div>

                    <div class="p-5 flex flex-col gap-3">

                        {{-- Bouton réserver (admin preview) --}}
                        <button type="button"
                                class="w-full flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-semibold text-white transition-all hover:brightness-110"
                                style="background:linear-gradient(135deg,#2563EB,#1d4ed8); box-shadow:0 4px 16px rgba(37,99,235,.3);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026"/>
                            </svg>
                            Aperçu réservation
                        </button>

                        {{-- Modifier --}}
                        <a href="{{ route('events.edit', $event->id) }}"
                           class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-semibold border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                            </svg>
                            Modifier l'événement
                        </a>

                        <hr class="border-slate-50">

                        {{-- Partager --}}
                        <div>
                            <button type="button"
                                    @click="share = !share"
                                    class="w-full flex items-center justify-between py-2 text-sm font-semibold text-slate-600 hover:text-slate-800 transition-colors">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z"/>
                                    </svg>
                                    Partager l'événement
                                </span>
                                <svg class="w-4 h-4 text-slate-400 transition-transform duration-200"
                                     :class="share ? 'rotate-180' : ''"
                                     fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                                </svg>
                            </button>

                            <div x-show="share"
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="grid grid-cols-2 gap-2 mt-3">
                                @php $shareUrl = urlencode(route('events.show', $event->id)); @endphp

                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}"
                                   target="_blank" rel="noopener noreferrer"
                                   class="flex items-center justify-center gap-2 py-2.5 rounded-xl text-xs font-semibold text-white transition-all hover:brightness-110"
                                   style="background:#1877F2;">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    Facebook
                                </a>

                                <a href="https://wa.me/?text={{ $shareUrl }}"
                                   target="_blank" rel="noopener noreferrer"
                                   class="flex items-center justify-center gap-2 py-2.5 rounded-xl text-xs font-semibold text-white transition-all hover:brightness-110"
                                   style="background:#25D366;">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                                    WhatsApp
                                </a>

                                <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ urlencode($event->titre) }}"
                                   target="_blank" rel="noopener noreferrer"
                                   class="flex items-center justify-center gap-2 py-2.5 rounded-xl text-xs font-semibold text-white transition-all hover:brightness-110"
                                   style="background:#000;">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                    Twitter
                                </a>

                                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}"
                                   target="_blank" rel="noopener noreferrer"
                                   class="flex items-center justify-center gap-2 py-2.5 rounded-xl text-xs font-semibold text-white transition-all hover:brightness-110"
                                   style="background:#0A66C2;">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                    LinkedIn
                                </a>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- Card : Méta infos admin --}}
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden" style="box-shadow:0 2px 12px rgba(0,0,0,.04);">
                    <div class="px-5 py-4 border-b border-slate-50">
                        <h3 class="text-sm font-semibold text-slate-800">Informations système</h3>
                    </div>
                    <div class="p-5 flex flex-col gap-3">
                        @foreach([
                            ['ID événement', '#' . $event->id],
                            ['Créé le',      \Carbon\Carbon::parse($event->created_at)->translatedFormat('d M Y à H:i')],
                            ['Mis à jour',   \Carbon\Carbon::parse($event->updated_at)->translatedFormat('d M Y à H:i')],
                            ['Catégorie',    $event->categorie->nom ?? '—'],
                        ] as [$k, $v])
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-400 font-medium">{{ $k }}</span>
                            <span class="font-semibold text-slate-700 font-mono bg-slate-50 px-2 py-0.5 rounded-lg">{{ $v }}</span>
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
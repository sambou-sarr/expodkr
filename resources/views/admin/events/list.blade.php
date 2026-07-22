@extends('admin.layout.header')

@section('title', 'Gestion des événements')
@section('subtitle', 'Gérez vos événements facilement')

@section('content')

{{--
|--------------------------------------------------------------------------
| ExpoDKR Admin – Gestion des événements (premium redesign)
| Toutes les routes, variables Blade et logique Alpine.js conservées
|--------------------------------------------------------------------------
--}}

<div class="p-6 lg:p-8" x-data="eventList()">

    {{-- ══════════════════════════════════════════════════════════════
         HEADER PAGE
         ══════════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <div class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></div>
                <span class="text-xs font-semibold tracking-widest uppercase text-slate-400">Catalogue</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 leading-tight">Tous les événements</h1>
            <p class="text-slate-400 text-sm mt-0.5">
                <span class="font-semibold text-slate-600">{{ $events->total() ?? $events->count() }}</span>
                événement{{ ($events->total() ?? $events->count()) > 1 ? 's' : '' }} au total
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
                       placeholder="Rechercher un événement…"
                       x-model="search"
                       class="pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-white text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-64 transition-shadow"
                       aria-label="Rechercher">
            </div>

            {{-- Bouton créer --}}
            <a href="{{ route('events.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition-all hover:brightness-110 active:scale-95 whitespace-nowrap"
               style="background: linear-gradient(135deg, #2563EB, #1d4ed8); box-shadow: 0 4px 16px rgba(37,99,235,.3);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Créer un événement
            </a>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════════════
         FILTRES
         ══════════════════════════════════════════════════════════════ --}}
    <div class="flex items-center gap-2 mb-6 flex-wrap">

        <button @click="filter='all'"
                class="px-4 py-2 rounded-xl text-sm font-semibold border transition-all duration-200"
                :class="filter === 'all'
                    ? 'bg-slate-800 text-white border-slate-800 shadow-sm'
                    : 'bg-white text-slate-500 border-slate-200 hover:border-slate-300 hover:text-slate-700'">
            Tous
            <span class="ml-1.5 text-xs px-1.5 py-0.5 rounded-md"
                  :class="filter === 'all' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500'">
                {{ $events->total() ?? $events->count() }}
            </span>
        </button>

        <button @click="filter='ouvert'"
                class="px-4 py-2 rounded-xl text-sm font-semibold border transition-all duration-200"
                :class="filter === 'ouvert'
                    ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm'
                    : 'bg-white text-slate-500 border-slate-200 hover:border-emerald-200 hover:text-emerald-600'">
            <span class="flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-80"></span>
                Ouverts
            </span>
        </button>

        <button @click="filter='brouillon'"
                class="px-4 py-2 rounded-xl text-sm font-semibold border transition-all duration-200"
                :class="filter === 'brouillon'
                    ? 'bg-amber-500 text-white border-amber-500 shadow-sm'
                    : 'bg-white text-slate-500 border-slate-200 hover:border-amber-200 hover:text-amber-600'">
            <span class="flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-80"></span>
                Brouillons
            </span>
        </button>

        <button @click="filter='termine'"
                class="px-4 py-2 rounded-xl text-sm font-semibold border transition-all duration-200"
                :class="filter === 'termine'
                    ? 'bg-red-500 text-white border-red-500 shadow-sm'
                    : 'bg-white text-slate-500 border-slate-200 hover:border-red-200 hover:text-red-500'">
            <span class="flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-80"></span>
                Terminés
            </span>
        </button>

        {{-- Reset si filtres actifs --}}
        <button x-show="search !== '' || filter !== 'all'"
                x-cloak
                @click="search=''; filter='all'"
                class="px-3 py-2 rounded-xl text-xs font-medium text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
            </svg>
            Réinitialiser
        </button>
    </div>


    {{-- ══════════════════════════════════════════════════════════════
         TABLEAU
         ══════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden"
         style="box-shadow: 0 2px 16px rgba(0,0,0,.05);">

        <div class="overflow-x-auto">
            <table class="w-full" role="table">

                <thead>
                    <tr style="background: #F8FAFC; border-bottom: 1px solid #F1F5F9;">
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide">Événement</th>
                        <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide hidden md:table-cell">Lieu</th>
                        <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide hidden lg:table-cell">Début</th>
                        <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide hidden lg:table-cell">Fin</th>
                        <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide">Statut</th>
                        <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide hidden sm:table-cell">Stands</th>
                        <th class="text-right px-5 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-50">

                    @forelse($events as $event)

                    {{-- Calcul statut dynamique via dates si statut absent --}}
                    @php
                        $statut = $event->statut ?? null;
                        if (!$statut) {
                            $now   = now();
                            $debut = \Carbon\Carbon::parse($event->date_debut);
                            $fin   = \Carbon\Carbon::parse($event->date_fin);
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

                    <tr class="hover:bg-slate-50 transition-colors group"
                        x-show="match('{{ strtolower($event->titre) }}', '{{ $statut }}')">

                        {{-- Événement (image + titre + catégorie) --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                {{-- Image --}}
                                <div class="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0"
                                     style="background: linear-gradient(135deg, #0A1628, #2563EB);">
                                    @if($event->image)
                                        <img src="{{ Storage::url($event->image) }}"
                                             alt="{{ $event->titre }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white/40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                {{-- Infos --}}
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-800 truncate max-w-[200px] group-hover:text-blue-600 transition-colors">
                                        {{ $event->titre }}
                                    </p>
                                    @if($event->categorie)
                                    <span class="text-xs px-2 py-0.5 rounded-md font-medium mt-0.5 inline-block"
                                          style="background: #EFF6FF; color: #2563EB;">
                                        {{ $event->categorie->nom }}
                                    </span>
                                    @elseif($event->exposant)
                                    <p class="text-xs text-slate-400 mt-0.5 truncate">{{ $event->exposant->nom }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Lieu --}}
                        <td class="px-4 py-4 hidden md:table-cell">
                            <div class="flex items-center gap-1.5 text-sm text-slate-500 max-w-[140px]">
                                <svg class="w-3.5 h-3.5 flex-shrink-0 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                                </svg>
                                <span class="truncate">{{ $event->lieu }}</span>
                            </div>
                        </td>

                        {{-- Date début --}}
                        <td class="px-4 py-4 hidden lg:table-cell">
                            <div class="text-sm text-slate-600 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($event->date_debut)->translatedFormat('d M Y') }}
                            </div>
                            <div class="text-xs text-slate-400 mt-0.5">
                                {{ \Carbon\Carbon::parse($event->date_debut)->translatedFormat('H:i') }}
                            </div>
                        </td>

                        {{-- Date fin --}}
                        <td class="px-4 py-4 hidden lg:table-cell">
                            <div class="text-sm text-slate-600 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($event->date_fin)->translatedFormat('d M Y') }}
                            </div>
                            <div class="text-xs text-slate-400 mt-0.5">
                                {{ \Carbon\Carbon::parse($event->date_fin)->translatedFormat('H:i') }}
                            </div>
                        </td>



                        {{-- Stands --}}
                        <td class="px-4 py-4 hidden sm:table-cell">
                            <div class="flex items-center gap-1.5">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: #F8FAFC;">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-semibold text-slate-700">{{ $event->stands_count ?? 0 }}</span>
                            </div>
                        </td>

                        {{-- Actions --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-1.5"
                                 x-data="{ menuOpen: false }">

                                {{-- Voir --}}
                                <a href="{{ route('events.show', $event->id) }}"
                                   class="flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all"
                                   aria-label="Voir {{ $event->titre }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                    </svg>
                                </a>

                                {{-- Modifier --}}
                                <a href="{{ route('events.edit', $event->id) }}"
                                   class="flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 text-slate-400 hover:text-amber-600 hover:border-amber-200 hover:bg-amber-50 transition-all"
                                   aria-label="Modifier {{ $event->titre }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/>
                                    </svg>
                                </a>

                                {{-- Supprimer --}}
                                <form action="{{ route('events.destroy', $event->id) }}" method="POST"
                                      onsubmit="return confirm('Supprimer définitivement « {{ addslashes($event->titre) }} » ? Cette action est irréversible.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 text-slate-400 hover:text-red-600 hover:border-red-200 hover:bg-red-50 transition-all"
                                            aria-label="Supprimer {{ $event->titre }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                        </svg>
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>

                    @empty

                    {{-- État vide --}}
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background: #F1F5F9;">
                                    <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-700 mb-1">Aucun événement trouvé</p>
                                    <p class="text-sm text-slate-400">Commencez par créer votre premier événement.</p>
                                </div>
                                <a href="{{ route('events.create') }}"
                                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition-all hover:brightness-110"
                                   style="background: linear-gradient(135deg, #2563EB, #1d4ed8);">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                    </svg>
                                    Créer un événement
                                </a>
                            </div>
                        </td>
                    </tr>

                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- ── Pied de tableau : pagination + compteur ── --}}
        @if(method_exists($events, 'links') && $events->hasPages())
        <div class="flex items-center justify-between px-5 py-4 border-t border-slate-50">
            <p class="text-xs text-slate-400">
                Affichage de
                <span class="font-semibold text-slate-600">{{ $events->firstItem() }}</span>
                à
                <span class="font-semibold text-slate-600">{{ $events->lastItem() }}</span>
                sur
                <span class="font-semibold text-slate-600">{{ $events->total() }}</span>
                résultats
            </p>

            <nav class="flex items-center gap-1.5" aria-label="Pagination">
                @if($events->onFirstPage())
                <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-100 text-slate-300 cursor-not-allowed">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                </span>
                @else
                <a href="{{ $events->previousPageUrl() }}"
                   class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:border-blue-300 hover:text-blue-600 hover:bg-blue-50 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                </a>
                @endif

                @foreach($events->getUrlRange(max(1,$events->currentPage()-2), min($events->lastPage(),$events->currentPage()+2)) as $page => $url)
                    @if($page == $events->currentPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-semibold text-white"
                          style="background: linear-gradient(135deg, #2563EB, #1d4ed8);">{{ $page }}</span>
                    @else
                    <a href="{{ $url }}"
                       class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-xs font-medium text-slate-500 hover:border-blue-300 hover:text-blue-600 hover:bg-blue-50 transition-all">
                        {{ $page }}
                    </a>
                    @endif
                @endforeach

                @if($events->hasMorePages())
                <a href="{{ $events->nextPageUrl() }}"
                   class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:border-blue-300 hover:text-blue-600 hover:bg-blue-50 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                </a>
                @else
                <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-100 text-slate-300 cursor-not-allowed">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                </span>
                @endif
            </nav>
        </div>
        @endif

    </div>
    {{-- /card --}}

</div>
{{-- /x-data --}}


{{-- ══════════════════════════════════════════════════════════════
     ALPINE.JS — Logique de filtrage (identique à l'original)
     ══════════════════════════════════════════════════════════════ --}}
<script>
function eventList() {
    return {
        search: '',
        filter: 'all',

        match(title, status) {
            const searchMatch = title.toLowerCase().includes(this.search.toLowerCase());
            const statusMatch = this.filter === 'all' || status === this.filter;
            return searchMatch && statusMatch;
        }
    }
}
</script>

@endsection
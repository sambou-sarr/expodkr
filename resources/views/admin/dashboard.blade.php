@extends('admin.layout.header')

@section('title', 'Dashboard')

@section('content')

{{--
|--------------------------------------------------------------------------
| ExpoDKR – Dashboard Admin Premium
| Variables attendues :
|   $totalEvents, $totalUsers, $totalTickets, $totalRevenue
|   $recentEvents (collection Eloquent avec ->categorie, ->exposant)
|   $recentUsers  (collection Eloquent)
|--------------------------------------------------------------------------
--}}

<div class="min-h-screen p-6 lg:p-8" style="background: #F1F5F9;">

    {{-- ══════════════════════════════════════════════════════════════
         HEADER
         ══════════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                <span class="text-xs font-semibold tracking-widest uppercase text-slate-400">Tableau de bord</span>
            </div>
            <h1 class="text-2xl lg:text-3xl font-bold text-slate-800 leading-tight">
                Bonjour, {{ Auth::user()->name ?? 'Admin' }} 👋
            </h1>
            <p class="text-slate-400 text-sm mt-0.5">
                {{ now()->translatedFormat('l d F Y') }} · Vue d'ensemble ExpoDKR
            </p>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            <a href="{{ route('events.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition-all hover:brightness-110 active:scale-95"
               style="background: linear-gradient(135deg, #2563EB, #1d4ed8); box-shadow: 0 4px 16px rgba(37,99,235,.3);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Créer un événement
            </a>
            <button onclick="window.location.reload()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
                </svg>
                Actualiser
            </button>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════════════
         STAT CARDS
         ══════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

        {{-- Événements --}}
        <a href="{{ route('events.index') }}"
           class="group relative overflow-hidden rounded-2xl p-6 bg-white border border-slate-100 hover:-translate-y-1 hover:shadow-xl transition-all duration-300"
           style="box-shadow: 0 2px 12px rgba(0,0,0,.05);">
            <div class="absolute top-0 right-0 w-24 h-24 rounded-full -translate-y-6 translate-x-6 opacity-10"
                 style="background: #2563EB;" aria-hidden="true"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="w-11 h-11 rounded-2xl flex items-center justify-center" style="background: #EFF6FF;">
                    <svg class="w-5 h-5" fill="none" stroke="#2563EB" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold px-2 py-1 rounded-lg" style="background:#ECFDF5; color:#059669;">+12%</span>
            </div>
            <p class="text-3xl font-bold text-slate-800 mb-1">{{ number_format($totalEvents ?? 24, 0, ',', ' ') }}</p>
            <p class="text-sm font-medium text-slate-400">Événements</p>
            <div class="mt-4 h-1 rounded-full bg-slate-100"><div class="h-full rounded-full w-3/4" style="background:#2563EB; opacity:.4;"></div></div>
        </a>

        {{-- Participants --}}
        <a href="{{ route('users.index') }}"
           class="group relative overflow-hidden rounded-2xl p-6 bg-white border border-slate-100 hover:-translate-y-1 hover:shadow-xl transition-all duration-300"
           style="box-shadow: 0 2px 12px rgba(0,0,0,.05);">
            <div class="absolute top-0 right-0 w-24 h-24 rounded-full -translate-y-6 translate-x-6 opacity-10"
                 style="background: #7C3AED;" aria-hidden="true"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="w-11 h-11 rounded-2xl flex items-center justify-center" style="background: #F5F3FF;">
                    <svg class="w-5 h-5" fill="none" stroke="#7C3AED" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold px-2 py-1 rounded-lg" style="background:#ECFDF5; color:#059669;">+8%</span>
            </div>
            <p class="text-3xl font-bold text-slate-800 mb-1">{{ number_format($totalUsers ?? 1240, 0, ',', ' ') }}</p>
            <p class="text-sm font-medium text-slate-400">Participants</p>
            <div class="mt-4 h-1 rounded-full bg-slate-100"><div class="h-full rounded-full w-2/3" style="background:#7C3AED; opacity:.4;"></div></div>
        </a>

        {{-- Tickets vendus --}}
        <a href="#"
           class="group relative overflow-hidden rounded-2xl p-6 bg-white border border-slate-100 hover:-translate-y-1 hover:shadow-xl transition-all duration-300"
           style="box-shadow: 0 2px 12px rgba(0,0,0,.05);">
            <div class="absolute top-0 right-0 w-24 h-24 rounded-full -translate-y-6 translate-x-6 opacity-10"
                 style="background: #059669;" aria-hidden="true"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="w-11 h-11 rounded-2xl flex items-center justify-center" style="background: #ECFDF5;">
                    <svg class="w-5 h-5" fill="none" stroke="#059669" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold px-2 py-1 rounded-lg" style="background:#ECFDF5; color:#059669;">+18%</span>
            </div>
            <p class="text-3xl font-bold text-slate-800 mb-1">{{ number_format($totalTickets ?? 860, 0, ',', ' ') }}</p>
            <p class="text-sm font-medium text-slate-400">Tickets vendus</p>
            <div class="mt-4 h-1 rounded-full bg-slate-100"><div class="h-full rounded-full w-4/5" style="background:#059669; opacity:.4;"></div></div>
        </a>

        {{-- Revenus --}}
        <a href="#"
           class="group relative overflow-hidden rounded-2xl p-6 border border-slate-100 hover:-translate-y-1 hover:shadow-xl transition-all duration-300"
           style="background: linear-gradient(135deg, #0A1628, #0D2145); box-shadow: 0 2px 12px rgba(0,0,0,.12);">
            <div class="absolute top-0 right-0 w-24 h-24 rounded-full -translate-y-6 translate-x-6 opacity-20"
                 style="background: #C9A84C;" aria-hidden="true"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="w-11 h-11 rounded-2xl flex items-center justify-center" style="background: rgba(201,168,76,.15);">
                    <svg class="w-5 h-5" fill="none" stroke="#E8C96A" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold px-2 py-1 rounded-lg" style="background:rgba(201,168,76,.15); color:#E8C96A;">+22%</span>
            </div>
            <p class="text-3xl font-bold text-white mb-1">{{ $totalRevenue ?? '12,5M' }} <span class="text-base font-medium" style="color:rgba(255,255,255,.5);">FCFA</span></p>
            <p class="text-sm font-medium" style="color:rgba(255,255,255,.5);">Revenus totaux</p>
            <div class="mt-4 h-1 rounded-full" style="background:rgba(255,255,255,.1);"><div class="h-full rounded-full w-5/6" style="background:#C9A84C; opacity:.7;"></div></div>
        </a>

    </div>


    {{-- ══════════════════════════════════════════════════════════════
         GRAPHIQUES
         ══════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">

        {{-- Graphique revenus (large) --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 p-6"
             style="box-shadow: 0 2px 12px rgba(0,0,0,.05);">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="font-semibold text-slate-800">Revenus mensuels</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Évolution sur 6 mois</p>
                </div>
                <div class="flex items-center gap-1.5 text-xs">
                    <span class="px-3 py-1.5 rounded-lg font-semibold text-white cursor-pointer" style="background:#2563EB;">6 mois</span>
                    <span class="px-3 py-1.5 rounded-lg text-slate-400 hover:bg-slate-50 cursor-pointer transition-colors">1 an</span>
                </div>
            </div>
            <div style="height: 220px; position: relative;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        {{-- Graphique donut --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-6"
             style="box-shadow: 0 2px 12px rgba(0,0,0,.05);">
            <div class="mb-6">
                <h2 class="font-semibold text-slate-800">Par catégorie</h2>
                <p class="text-xs text-slate-400 mt-0.5">Répartition des événements</p>
            </div>
            <div style="height: 160px; position: relative; max-width: 160px; margin: 0 auto;">
                <canvas id="categoryChart"></canvas>
            </div>
            <div class="mt-5 flex flex-col gap-2.5">
                @foreach([['Conférences','#2563EB','42%'],['Salons','#7C3AED','28%'],['Workshops','#059669','18%'],['Autres','#D97706','12%']] as [$lbl,$clr,$pct])
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:{{ $clr }};"></span>
                        <span class="text-xs text-slate-500">{{ $lbl }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-16 h-1.5 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full" style="width:{{ $pct }}; background:{{ $clr }};"></div>
                        </div>
                        <span class="text-xs font-semibold text-slate-700 w-8 text-right">{{ $pct }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>


    {{-- ══════════════════════════════════════════════════════════════
         GRILLE CONTENU PRINCIPAL
         ══════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- TABLE ÉVÉNEMENTS RÉCENTS --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 overflow-hidden"
             style="box-shadow: 0 2px 12px rgba(0,0,0,.05);">

            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-50">
                <div>
                    <h2 class="font-semibold text-slate-800">Événements récents</h2>
                    <p class="text-xs text-slate-400">Dernières publications</p>
                </div>
                <a href="{{ route('events.index') }}"
                   class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1 transition-colors">
                    Voir tout
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full" role="table">
                    <thead>
                        <tr style="background: #F8FAFC;">
                            <th class="text-left px-6 py-3 text-xs font-medium text-slate-400">Événement</th>
                            <th class="text-left px-4 py-3 text-xs font-medium text-slate-400 hidden sm:table-cell">Date</th>
                            <th class="text-left px-4 py-3 text-xs font-medium text-slate-400 hidden md:table-cell">Lieu</th>
                            <th class="text-left px-4 py-3 text-xs font-medium text-slate-400">Statut</th>
                            <th class="text-right px-6 py-3 text-xs font-medium text-slate-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">

                        @forelse($recentEvents ?? [] as $event)
                        @php
                            $now   = now();
                            $debut = \Carbon\Carbon::parse($event->date_debut);
                            $fin   = \Carbon\Carbon::parse($event->date_fin);
                            if ($now->lt($debut))                { $sl='À venir';  $sc='#059669'; $sb='#ECFDF5'; }
                            elseif ($now->between($debut,$fin))  { $sl='En cours'; $sc='#D97706'; $sb='#FFFBEB'; }
                            else                                 { $sl='Terminé';  $sc='#9CA3AF'; $sb='#F1F5F9'; }
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl overflow-hidden flex-shrink-0"
                                         style="background: linear-gradient(135deg, #0A1628, #2563EB);">
                                        @if($event->image)
                                        <img src="{{ $event->image }}" alt="" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-slate-800 truncate max-w-[180px]">{{ $event->titre }}</p>
                                        @if($event->categorie)
                                        <p class="text-xs text-slate-400">{{ $event->categorie->nom }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-500 hidden sm:table-cell whitespace-nowrap">
                                {{ $debut->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-500 hidden md:table-cell max-w-[120px] truncate">
                                {{ $event->lieu }}
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-lg"
                                      style="background: {{ $sb }}; color: {{ $sc }};">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background: {{ $sc }};"></span>
                                    {{ $sl }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('events.show', $event->id) }}"
                                       class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all"
                                       aria-label="Voir">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('events.edit', $event->id) }}"
                                       class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:text-amber-600 hover:border-amber-200 hover:bg-amber-50 transition-all"
                                       aria-label="Modifier">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('events.destroy', $event->id) }}" method="POST"
                                          onsubmit="return confirm('Supprimer cet événement ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:text-red-600 hover:border-red-200 hover:bg-red-50 transition-all"
                                                aria-label="Supprimer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        @empty
                        {{-- Fallback données statiques si pas de données Laravel --}}
                        @foreach([
                            ['Expo Dakar 2026','12 Juil 2026','Dakar','À venir','#059669','#ECFDF5'],
                            ['Tech Summit Diamniadio','18 Juil 2026','Diamniadio','En cours','#D97706','#FFFBEB'],
                            ['Startup Expo UCAD','25 Juil 2026','UCAD','À venir','#059669','#ECFDF5'],
                            ['Forum RH Sénégal','30 Juil 2026','Plateau','Terminé','#9CA3AF','#F1F5F9'],
                        ] as [$name, $date, $lieu, $status, $sc, $sb])
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl flex-shrink-0"
                                         style="background: linear-gradient(135deg, #0A1628, #2563EB);"></div>
                                    <p class="text-sm font-semibold text-slate-800">{{ $name }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-500 hidden sm:table-cell whitespace-nowrap">{{ $date }}</td>
                            <td class="px-4 py-4 text-sm text-slate-500 hidden md:table-cell">{{ $lieu }}</td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-lg"
                                      style="background:{{ $sb }}; color:{{ $sc }};">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background:{{ $sc }};"></span>
                                    {{ $status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-1.5">
                                    <a href="{{ route('events.index') }}"
                                       class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>


        {{-- PANNEAU LATÉRAL --}}
        <div class="flex flex-col gap-5">

            {{-- Derniers inscrits --}}
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden"
                 style="box-shadow: 0 2px 12px rgba(0,0,0,.05);">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-50">
                    <h2 class="font-semibold text-slate-800 text-sm">Derniers inscrits</h2>
                    <a href="{{ route('users.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition-colors">Voir tout →</a>
                </div>
                <div class="p-5 flex flex-col gap-3">
                    @forelse($recentUsers ?? [] as $user)
                    <div class="flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=2563EB&color=fff&size=80"
                             alt="{{ $user->name }}"
                             class="w-9 h-9 rounded-xl flex-shrink-0">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-slate-700 truncate">{{ $user->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ $user->email }}</p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-lg font-medium flex-shrink-0" style="background:#EFF6FF; color:#2563EB;">Membre</span>
                    </div>
                    @empty
                    {{-- Fallback statique --}}
                    @foreach([
                        ['Awa Diop','awa@expodkr.sn','#2563EB','Visiteur'],
                        ['Moussa Fall','moussa@expodkr.sn','#7C3AED','Organisateur'],
                        ['Fatou Ndiaye','fatou@expodkr.sn','#059669','Visiteur'],
                        ['Ibou Seck','ibou@expodkr.sn','#D97706','Exposant'],
                    ] as [$name, $email, $clr, $role])
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 text-white text-sm font-bold"
                             style="background: {{ $clr }};">
                            {{ strtoupper(substr($name, 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-slate-700 truncate">{{ $name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ $email }}</p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-lg font-medium flex-shrink-0" style="background:#EFF6FF; color:#2563EB;">{{ $role }}</span>
                    </div>
                    @endforeach
                    @endforelse
                </div>
            </div>


            {{-- Activité récente --}}
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden"
                 style="box-shadow: 0 2px 12px rgba(0,0,0,.05);">
                <div class="px-5 py-4 border-b border-slate-50">
                    <h2 class="font-semibold text-slate-800 text-sm">Activité récente</h2>
                </div>
                <div class="p-5 flex flex-col gap-0">
                    @foreach([
                        ['Nouveau événement créé',     'Forum Tech Dakar 2026', '#2563EB', '#EFF6FF', '2 min',  '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25"/>'],
                        ['120 tickets vendus',         'aujourd\'hui',          '#059669', '#ECFDF5', '15 min', '<path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026"/>'],
                        ['Nouvel utilisateur inscrit', 'Awa Diop',              '#7C3AED', '#F5F3FF', '1h',     '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0"/>'],
                        ['Paiement confirmé',          '850 000 FCFA',          '#D97706', '#FFFBEB', '2h',     '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75"/>'],
                    ] as [$title, $subtitle, $ic, $ib, $time, $ipath])
                    <div class="flex items-start gap-3 py-3 border-b border-slate-50 last:border-0">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5"
                             style="background: {{ $ib }};">
                            <svg class="w-4 h-4" fill="none" stroke="{{ $ic }}" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                {!! $ipath !!}
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-slate-700">{{ $title }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ $subtitle }}</p>
                        </div>
                        <span class="text-xs text-slate-300 flex-shrink-0 whitespace-nowrap">{{ $time }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
        {{-- /panneau latéral --}}

    </div>
    {{-- /grille --}}

</div>
{{-- /min-h-screen --}}


{{-- ══════════════════════════════════════════════════════════════
     CHART.JS
     ══════════════════════════════════════════════════════════════ --}}
<script>
document.addEventListener('DOMContentLoaded', () => {

    // Config globale Chart.js
    if (typeof Chart !== 'undefined') {
        Chart.defaults.font.family = 'Inter, sans-serif';
        Chart.defaults.font.size   = 11;
        Chart.defaults.color       = '#94A3B8';
    }

    // ── Revenus line chart ─────────────────────────────────────
    const revenueEl = document.getElementById('revenueChart');
    if (revenueEl) {
        new Chart(revenueEl, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
                datasets: [{
                    label: 'Revenus',
                    data: [400000, 650000, 900000, 750000, 1200000, 1500000],
                    borderColor: '#2563EB',
                    backgroundColor: 'rgba(37,99,235,.07)',
                    fill: true,
                    tension: 0.45,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#2563EB',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: 'index' },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        titleFont: { size: 11, weight: '600' },
                        bodyFont:  { size: 11 },
                        padding: 12,
                        cornerRadius: 10,
                        displayColors: false,
                        callbacks: {
                            label: ctx => ' ' + ctx.parsed.y.toLocaleString('fr-FR') + ' FCFA'
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        border: { display: false }
                    },
                    y: {
                        grid: { color: 'rgba(0,0,0,.04)', drawBorder: false },
                        border: { display: false },
                        ticks: {
                            callback: v => (v >= 1000000 ? (v/1000000)+'M' : (v/1000)+'k')
                        }
                    }
                }
            }
        });
    }

    // ── Donut catégories ──────────────────────────────────────
    const catEl = document.getElementById('categoryChart');
    if (catEl) {
        new Chart(catEl, {
            type: 'doughnut',
            data: {
                labels: ['Conférences', 'Salons', 'Workshops', 'Autres'],
                datasets: [{
                    data: [42, 28, 18, 12],
                    backgroundColor: ['#2563EB', '#7C3AED', '#059669', '#D97706'],
                    borderWidth: 0,
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '74%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: ctx => ' ' + ctx.label + ' : ' + ctx.parsed + '%'
                        }
                    }
                }
            }
        });
    }

});
</script>

@endsection
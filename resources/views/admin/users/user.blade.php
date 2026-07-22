@extends('admin.layout.header')

@section('title', 'Gestion des utilisateurs')
@section('subtitle', 'Gérez vos utilisateurs facilement')

@section('content')

{{--
|--------------------------------------------------------------------------
| ExpoDKR Admin – Gestion des utilisateurs (premium redesign)
| Variables : $users (collection ou paginator)
| Routes, variables Blade et logique 100% conservés
|--------------------------------------------------------------------------
--}}

<div class="p-6 lg:p-8" x-data="{ search: '' }">

    {{-- ══════════════════════════════════════════════════════════════
         HEADER PAGE
         ══════════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <div class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></div>
                <span class="text-xs font-semibold tracking-widest uppercase text-slate-400">Administration</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 leading-tight">Utilisateurs</h1>
            <p class="text-slate-400 text-sm mt-0.5">
                <span class="font-semibold text-slate-600">{{ $users->count() }}</span>
                utilisateur{{ $users->count() > 1 ? 's' : '' }} enregistré{{ $users->count() > 1 ? 's' : '' }}
            </p>
        </div>

        {{-- Recherche --}}
        <div class="relative">
            <div class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none" aria-hidden="true">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607z"/>
                </svg>
            </div>
            <input type="text"
                   x-model="search"
                   placeholder="Rechercher un utilisateur…"
                   class="pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-white text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-64 transition-shadow"
                   aria-label="Rechercher">
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════════════
         STATS RAPIDES
         ══════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @php
            $totalActifs   = $users->where('status', 'active')->count();
            $totalSuspends = $users->where('status', '!=', 'active')->count();
            $roles = $users->groupBy('role');
        @endphp

        <div class="bg-white rounded-2xl border border-slate-100 p-4 hover:-translate-y-0.5 transition-transform"
             style="box-shadow:0 2px 12px rgba(0,0,0,.04);">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background:#EFF6FF;">
                <svg class="w-4.5 h-4.5" fill="none" stroke="#2563EB" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-slate-800">{{ $users->count() }}</p>
            <p class="text-xs text-slate-400 font-medium mt-0.5">Total</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-4 hover:-translate-y-0.5 transition-transform"
             style="box-shadow:0 2px 12px rgba(0,0,0,.04);">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background:#ECFDF5;">
                <svg class="w-4.5 h-4.5" fill="none" stroke="#059669" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color:#059669;">{{ $totalActifs }}</p>
            <p class="text-xs text-slate-400 font-medium mt-0.5">Actifs</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-4 hover:-translate-y-0.5 transition-transform"
             style="box-shadow:0 2px 12px rgba(0,0,0,.04);">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background:#FEF2F2;">
                <svg class="w-4.5 h-4.5" fill="none" stroke="#DC2626" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color:#DC2626;">{{ $totalSuspends }}</p>
            <p class="text-xs text-slate-400 font-medium mt-0.5">Suspendus</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-4 hover:-translate-y-0.5 transition-transform"
             style="box-shadow:0 2px 12px rgba(0,0,0,.04);">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background:#FFFBEB;">
                <svg class="w-4.5 h-4.5" fill="none" stroke="#D97706" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color:#D97706;">{{ $roles->count() }}</p>
            <p class="text-xs text-slate-400 font-medium mt-0.5">Rôles distincts</p>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════════════
         TABLEAU UTILISATEURS
         ══════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden"
         style="box-shadow:0 2px 16px rgba(0,0,0,.05);">

        {{-- Header tableau --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-50">
            <h2 class="text-sm font-semibold text-slate-800">Tous les utilisateurs</h2>
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-400">
                    <span x-text="'{{ $users->count() }} membres'"></span>
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full" role="table">

                <thead>
                    <tr style="background:#F8FAFC; border-bottom:1px solid #F1F5F9;">
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide">Utilisateur</th>
                        <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide hidden md:table-cell">Email</th>
                        <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide hidden sm:table-cell">Rôle</th>
                        <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide hidden lg:table-cell">Événements</th>
                        <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide hidden lg:table-cell">Inscription</th>
                        <th class="text-right px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-50">

                    @forelse($users as $user)

                    <tr class="hover:bg-slate-50 transition-colors group"
                        x-show="search === '' || '{{ strtolower($user->name . ' ' . $user->email) }}'.includes(search.toLowerCase())">

                        {{-- Utilisateur : avatar + nom --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                {{-- Avatar --}}
                                <div class="relative flex-shrink-0">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=2563EB&color=fff&size=80"
                                         alt="{{ $user->name }}"
                                         class="w-10 h-10 rounded-xl">
                                    {{-- Indicateur statut --}}
                                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full border-2 border-white"
                                          style="background:{{ $user->status === 'active' ? '#10B981' : '#DC2626' }};"></span>
                                </div>
                                {{-- Nom --}}
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-800 group-hover:text-blue-600 transition-colors truncate max-w-[150px]">
                                        {{ $user->name }}
                                    </p>
                                    {{-- Email visible seulement mobile --}}
                                    <p class="text-xs text-slate-400 truncate max-w-[150px] md:hidden">
                                        {{ $user->email }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        {{-- Email --}}
                        <td class="px-4 py-4 hidden md:table-cell">
                            <a href="mailto:{{ $user->email }}"
                               class="text-sm text-slate-500 hover:text-blue-600 transition-colors truncate block max-w-[200px]">
                                {{ $user->email }}
                            </a>
                        </td>

                        {{-- Rôle --}}
                        <td class="px-4 py-4 hidden sm:table-cell">
                            @php
                                $roleColors = [
                                    'admin'        => ['#2563EB','#EFF6FF'],
                                    'organisateur' => ['#7C3AED','#F5F3FF'],
                                    'exposant'     => ['#059669','#ECFDF5'],
                                    'visiteur'     => ['#D97706','#FFFBEB'],
                                ];
                                $rc = $roleColors[strtolower($user->role)] ?? ['#9CA3AF','#F1F5F9'];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-lg capitalize"
                                  style="background:{{ $rc[1] }}; color:{{ $rc[0] }};">
                                {{ $user->role }}
                            </span>
                        </td>

                        {{-- Événements --}}
                        <td class="px-4 py-4 hidden lg:table-cell">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#F8FAFC;">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-semibold text-slate-700">{{ $user->events_count ?? 0 }}</span>
                            </div>
                        </td>

                        {{-- Date inscription --}}
                        <td class="px-4 py-4 hidden lg:table-cell">
                            <span class="text-sm text-slate-500">
                                {{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('d M Y') }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-1.5"
                                 x-data="{ menuOpen: false }">

                                {{-- Voir le profil {{ route('users.show', $user->id) }}
{{ route('users.show', $user->id) }}--}}
                                <a href=""
                                   class="flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all"
                                   aria-label="Voir {{ $user->name }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                    </svg>
                                </a>

                                {{-- Suspendre / Activer --}}
                                @if($user->status === 'active')
                                <div x-data="{ confirm: false }" class="relative">
                                    <button type="button"
                                            @click="confirm = !confirm"
                                            class="flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 text-slate-400 hover:text-amber-600 hover:border-amber-200 hover:bg-amber-50 transition-all"
                                            aria-label="Suspendre {{ $user->name }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                    </button>

                                    {{-- Popover confirmation suspendre --}}
                                    <div x-show="confirm"
                                         x-cloak
                                         @click.outside="confirm = false"
                                         x-transition:enter="transition ease-out duration-150"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         class="absolute right-0 bottom-10 z-20 w-52 bg-white rounded-2xl border shadow-xl p-4"
                                         style="border-color:#FED7AA;">
                                        <p class="text-xs font-semibold text-slate-800 mb-0.5">Suspendre l'accès</p>
                                        <p class="text-xs text-slate-400 mb-3">
                                            <span class="font-medium text-slate-600">{{ Str::limit($user->name, 18) }}</span> ne pourra plus se connecter.
                                        </p>
                                        <div class="flex gap-2">
                                            <button @click="confirm = false"
                                                    class="flex-1 py-1.5 rounded-lg text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                                                Annuler
                                            </button>
                                            <form action="" method="POST" class="flex-1">
                                                @csrf
                                                <button type="submit"
                                                        class="w-full py-1.5 rounded-lg text-xs font-semibold text-white transition-colors"
                                                        style="background:#D97706;">
                                                    Suspendre
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                @else

                                {{-- Activer --}}
                                <div x-data="{ confirm: false }" class="relative">
                                    <button type="button"
                                            @click="confirm = !confirm"
                                            class="flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 text-slate-400 hover:text-green-600 hover:border-green-200 hover:bg-green-50 transition-all"
                                            aria-label="Activer {{ $user->name }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                    </button>

                                    <div x-show="confirm"
                                         x-cloak
                                         @click.outside="confirm = false"
                                         x-transition:enter="transition ease-out duration-150"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         class="absolute right-0 bottom-10 z-20 w-52 bg-white rounded-2xl border shadow-xl p-4"
                                         style="border-color:#A7F3D0;">
                                        <p class="text-xs font-semibold text-slate-800 mb-0.5">Activer le compte</p>
                                        <p class="text-xs text-slate-400 mb-3">
                                            <span class="font-medium text-slate-600">{{ Str::limit($user->name, 18) }}</span> pourra de nouveau se connecter.
                                        </p>
                                        <div class="flex gap-2">
                                            <button @click="confirm = false"
                                                    class="flex-1 py-1.5 rounded-lg text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                                                Annuler
                                            </button>
                                            <form action="" method="POST" class="flex-1">
                                                @csrf
                                                <button type="submit"
                                                        class="w-full py-1.5 rounded-lg text-xs font-semibold text-white transition-colors"
                                                        style="background:#059669;">
                                                    Activer
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                @endif

                                {{-- Menu actions (3 points) --}}
                                <div class="relative" x-data="{ open: false }">
                                    <button type="button"
                                            @click="open = !open"
                                            @click.outside="open = false"
                                            class="flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all"
                                            aria-label="Plus d'actions">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
                                        </svg>
                                    </button>

                                    <div x-show="open"
                                         x-cloak
                                         x-transition:enter="transition ease-out duration-150"
                                         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                         class="absolute right-0 top-10 z-20 w-44 bg-white rounded-2xl border border-slate-100 shadow-xl overflow-hidden py-1">
                                        <a href=""
                                           class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                            </svg>
                                            Voir le profil
                                        </a>
                                        <a href="mailto:{{ $user->email }}"
                                           class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                                            </svg>
                                            Envoyer un email
                                        </a>
                                        <div class="border-t border-slate-50 my-1"></div>
                                        <button type="button"
                                                class="w-full flex items-center gap-2.5 px-4 py-2.5 text-xs font-medium text-red-500 hover:bg-red-50 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                            </svg>
                                            Supprimer
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </td>

                    </tr>

                    @empty

                    {{-- État vide --}}
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background:#F1F5F9;">
                                    <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-700 mb-1">Aucun utilisateur trouvé</p>
                                    <p class="text-sm text-slate-400">Les utilisateurs inscrits apparaîtront ici.</p>
                                </div>
                            </div>
                        </td>
                    </tr>

                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if(method_exists($users, 'hasPages') && $users->hasPages())
        <div class="flex items-center justify-between px-6 py-4 border-t border-slate-50">
            <p class="text-xs text-slate-400">
                <span class="font-semibold text-slate-600">{{ $users->firstItem() }}</span>
                –
                <span class="font-semibold text-slate-600">{{ $users->lastItem() }}</span>
                sur
                <span class="font-semibold text-slate-600">{{ $users->total() }}</span>
                utilisateurs
            </p>
            <nav class="flex items-center gap-1.5" aria-label="Pagination">
                @if($users->onFirstPage())
                <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-100 text-slate-300 cursor-not-allowed">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                </span>
                @else
                <a href="{{ $users->previousPageUrl() }}"
                   class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:border-blue-300 hover:text-blue-600 hover:bg-blue-50 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                </a>
                @endif

                @foreach($users->getUrlRange(max(1,$users->currentPage()-2), min($users->lastPage(),$users->currentPage()+2)) as $page => $url)
                    @if($page == $users->currentPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-semibold text-white"
                          style="background:linear-gradient(135deg,#2563EB,#1d4ed8);">{{ $page }}</span>
                    @else
                    <a href="{{ $url }}"
                       class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-xs font-medium text-slate-500 hover:border-blue-300 hover:text-blue-600 hover:bg-blue-50 transition-all">
                        {{ $page }}
                    </a>
                    @endif
                @endforeach

                @if($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}"
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
    {{-- /table card --}}

</div>

@endsection
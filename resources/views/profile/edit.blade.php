@extends('admin.layout.header')

@section('title', 'Profile')
@section('subtitle', 'Mes information du système')

@section('content')

<div class="max-w-6xl mx-auto py-10">

    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900">
            Mon profil
        </h1>
        <p class="text-slate-500">
            Gérez vos informations personnelles
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- CARTE PROFIL -->
        <div class="bg-white rounded-2xl shadow border p-8">

            <div class="flex flex-col items-center">

                <div
                    class="w-32 h-32 rounded-full bg-red-500 flex items-center justify-center text-5xl text-white font-bold">

                    {{ strtoupper(substr(Auth::user()->name,0,2)) }}

                </div>

                <h2 class="mt-4 text-3xl font-bold">
                    {{ Auth::user()->name }}
                </h2>

                <p class="text-gray-500">
                    {{ Auth::user()->email }}
                </p>

                <span
                    class="mt-2 px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-sm">
                    Administrateur
                </span>

                <button
                    class="mt-6 border rounded-xl px-6 py-3 hover:bg-slate-50">
                    Changer la photo
                </button>

            </div>

        </div>

        <!-- FORMULAIRE -->
        <div class="lg:col-span-2">

            <div class="bg-white rounded-2xl shadow border p-8">

                <h2 class="text-2xl font-bold mb-6">
                    Informations personnelles
                </h2>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-5">

                        <div>
                            <label class="font-semibold text-slate-600">
                                Nom complet
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', Auth::user()->name) }}"
                                class="w-full mt-2 border rounded-xl p-3">
                        </div>

                        <div>
                            <label class="font-semibold text-slate-600">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email', Auth::user()->email) }}"
                                class="w-full mt-2 border rounded-xl p-3">
                        </div>

                        <div>
                            <label class="font-semibold text-slate-600">
                                Organisation
                            </label>

                            <input
                                type="text"
                                name="organisation"
                                placeholder="Nom de votre entreprise"
                                class="w-full mt-2 border rounded-xl p-3">
                        </div>

                        <div>
                            <label class="font-semibold text-slate-600">
                                Bio
                            </label>

                            <textarea
                                name="bio"
                                rows="4"
                                placeholder="Parlez-nous un peu de vous..."
                                class="w-full mt-2 border rounded-xl p-3"></textarea>
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-xl font-semibold">

                            Enregistrer les modifications

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <!-- SECURITE -->

    <div class="mt-6 bg-white rounded-2xl shadow border p-8">

        <h2 class="text-2xl font-bold mb-5">
            Sécurité
        </h2>

        <a href="{{ route('password.request') }}"
           class="border px-6 py-3 rounded-xl hover:bg-slate-50">

            Changer le mot de passe

        </a>

        <p class="mt-5 text-slate-500">
            Dernière connexion : il y a 2 heures
        </p>

    </div>

</div>

@endsection
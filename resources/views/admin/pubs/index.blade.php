@extends('admin.layout.header')

@section('title', 'Gestion des pubs')
@section('subtitle', 'Gérez vos pubs facilement')

@section('content')

    <div class="max-w-6xl mx-auto px-6 py-10" x-data="{ openZone: null }">

        <h1 class="text-2xl font-bold text-slate-800 mb-8">Gestion des bannières publicitaires</h1>

        @if(session('success'))
        <div class="mb-6 px-4 py-3 rounded-lg bg-green-50 text-green-700 text-sm font-medium border border-green-200">
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($zones as $key => $zone)
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">

                {{-- Aperçu image --}}
                <div class="relative bg-slate-50 flex items-center justify-center h-40 border-b border-slate-100">
                    @if($zone['image'])
                        <img src="{{ $zone['image'] }}" alt="{{ $zone['label'] }}" class="max-h-full max-w-full object-contain">
                    @else
                        <span class="text-xs text-slate-400 font-medium">Aucune image</span>
                    @endif
                </div>

                {{-- Infos --}}
                <div class="p-4">
                    <h2 class="font-semibold text-sm text-slate-800 mb-1">{{ $zone['label'] }}</h2>
                    <p class="text-xs text-slate-400 mb-4">{{ $zone['w'] }} × {{ $zone['h'] }} px · zone: <code>{{ $key }}</code></p>

                    <button type="button"
                            @click="openZone = openZone === '{{ $key }}' ? null : '{{ $key }}'"
                            class="w-full py-2 rounded-lg text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 transition">
                        Modifier
                    </button>

                    {{-- Formulaire d'upload (affiché au clic) --}}
                    <div x-show="openZone === '{{ $key }}'" x-cloak class="mt-4">
                        <form action="{{ route('admin.pubs.update', $key) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-3">
                            @csrf
                            <input type="file" name="image" accept="image/*" required
                                   class="text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                            <button type="submit"
                                    class="py-2 rounded-lg text-sm font-semibold text-white bg-green-600 hover:bg-green-700 transition">
                                Enregistrer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endsection
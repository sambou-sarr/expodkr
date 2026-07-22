<?php

namespace App\Http\Controllers;

use App\Models\CategorieStand;
use App\Models\Evenement;
use App\Models\Exposant;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class EventsController extends Controller
{
    public function store(Request $request)
{
$request->validate([
    'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
]);
$image = null;

if ($request->hasFile('image') && $request->file('image')->isValid()) {

    $file = $request->file('image');

    $filename = time() . '_' . $file->getClientOriginalName();

    $image = $file->storeAs('events', $filename, 'public');
    }
        $event = new Evenement();
    $event->titre = $request->titre;
    $event->description = $request->description;
    $event->id_categorie = $request->id_categorie ?? null;
    $event->lieu = $request->lieu;
    $event->date_debut = $request->date_debut;
    $event->date_fin = $request->date_fin;
    $event->exposant_id = $request->exposant_id;
    $event->image = $image;
     $event->statut = 'en attente';
    $event->save();

    return redirect()->route('events.index')
        ->with('success', 'Événement créé avec succès');

    }
    public function create()
    {
        $categories = CategorieStand::all();
        $exposants = Exposant::all();

        return view('admin.events.create', compact('categories', 'exposants'));
    }
    public function show($id)
    {
        $event = Evenement::with(['categorie', 'exposant'])
            ->findOrFail($id);

        return view('admin.events.show', compact('event'));
    }
public function listevents()
{
    $events = Evenement::with(['categorie', 'exposant'])
                       ->latest()
                       ->paginate(10)
                       ->withQueryString();

    return view('admin.events.list', compact('events'));
}
    public function destroy($id)
    {
        $event = Evenement::findOrFail($id);
        $event->delete();

        return redirect()->back()->with('success', 'Événement supprimé avec succès');
    }
    public function update(Request $request, $id)
{
    $event = Evenement::findOrFail($id);
    $image = $event->image;

    if ($request->hasFile('image')) {
        $image = Cloudinary::upload(
            $request->file('image')->getRealPath(),
            ['folder' => 'expo-dkr/events']
        )->getSecurePath();
    }

    $event->update([
        'titre' => $request->titre,
        'lieu' => $request->lieu,
        'date_debut' => $request->date_debut,
        'date_fin' => $request->date_fin,
        'description' => $request->description,
        'id_categorie' => $request->id_categorie,
        'exposant_id' => $request->exposant_id,
        'image' => $image,
    ]);

    return redirect()->route('events.index')
        ->with('success', 'Événement mis à jour avec succès');
}
public function edit($id)
{
    $event = Evenement::findOrFail($id);

    $categories = CategorieStand::all();
    $exposants = Exposant::all();

    return view('admin.events.edit', compact('event', 'categories', 'exposants'));
}
}

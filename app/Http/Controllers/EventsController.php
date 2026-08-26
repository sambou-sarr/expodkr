<?php

namespace App\Http\Controllers;

use App\Models\CategorieStand;
use App\Models\Evenement;
use App\Models\Exposant;
use Illuminate\Http\Request;
use Cloudinary\Cloudinary;

class EventsController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
    ]);
    $url = null;

    if ($request->hasFile('image') && $request->file('image')->isValid()) {

        $file = $request->file('image');

        $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));

        $uploadedFile = $cloudinary->uploadApi()->upload($file->getRealPath(), [
            'folder' => 'events',
        ]);

        $url = $uploadedFile['secure_url'];       // URL à stocker en BDD
        $publicId = $uploadedFile['public_id'];   // utile pour suppression future
    }
            $event = new Evenement();
        $event->titre = $request->titre;
        $event->description = $request->description;
        $event->id_categorie = $request->id_categorie ?? null;
        $event->lieu = $request->lieu;
        $event->date_debut = $request->date_debut;
        $event->date_fin = $request->date_fin;
        $event->exposant_id = $request->exposant_id;
        $event->image = $url;
        $event->statut = 'brouillon';
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
    $imageUrl = $event->image;

    if ($request->hasFile('image') && $request->file('image')->isValid()) {

        $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));

        $uploadedFile = $cloudinary->uploadApi()->upload(
            $request->file('image')->getRealPath(),
            ['folder' => 'events']
        );

        $imageUrl = $uploadedFile['secure_url'];
    }

    $event->update([
        'titre' => $request->titre,
        'lieu' => $request->lieu,
        'date_debut' => $request->date_debut,
        'date_fin' => $request->date_fin,
        'description' => $request->description,
        'id_categorie' => $request->id_categorie,
        'exposant_id' => $request->exposant_id,
        'image' => $imageUrl,
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

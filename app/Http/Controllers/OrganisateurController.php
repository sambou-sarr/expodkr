<?php

namespace App\Http\Controllers;

use App\Models\CategorieStand;
use App\Models\Evenement;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrganisateurController extends Controller
{
    
/**
 * Liste des evenements de l'organisateur connecte.
 */
public function index(Request $request)
{
    $events = Evenement::query()
        ->where('exposant_id', Auth::id())
        ->withCount('reservations')
        ->with('categorie')
        ->latest()
        ->paginate(10);

    $categories = CategorieStand::orderBy('nom')->get();

    $reservations = Reservation::query()
        ->whereHas('evenement', function ($q) {
            $q->where('exposant_id', Auth::id());
        })
        ->with('evenement')
        ->latest()
        ->take(10)
        ->get();

    return view('visiteur.organisateur.dashboard', compact('events', 'categories', 'reservations'));
}
public function create()
{
    $categories = CategorieStand::orderBy('nom')->get();

    return view('visiteur.organisateur.create-events', compact('categories'));
}
/**
 * Afficher le detail d'un evenement (uniquement s'il appartient a l'organisateur connecte).
 */
public function show(int $id)
{
    $event = Evenement::with(['categorie', 'reservations'])
        ->where('user_id', Auth::id())
        ->findOrFail($id);

    return view('visiteur.organisateur.evenements.show', compact('event'));
}

/**
 * Enregistrer un nouvel evenement.
 */
public function store(Request $request)
{
    $data =   $request->validate([
        'titre'        => ['required', 'string', 'max:255'],
        'lieu'         => ['required', 'string', 'max:255'],
        'date_debut'   => ['required', 'date'],
        'date_fin'     => ['required', 'date', 'after_or_equal:date_debut'],
        'id_categorie' => ['nullable', 'exists:categorie_stands,id'],
        'description'  => ['nullable', 'string'],
        'capacite'     => ['nullable', 'integer', 'min:1'],
        'image'        => ['nullable', 'image', 'max:5120'],
    ]);


if ($request->hasFile('image') && $request->file('image')->isValid()) {
   
    $file = $request->file('image');
    $filename = time() . '_' . $file->getClientOriginalName();
    $data['image'] = $file->storeAs('events', $filename, 'public');
}
    $data['exposant_id'] = Auth::id();

      $event = new Evenement();
    $event->titre = $request->titre;
    $event->description = $request->description;
    $event->id_categorie = $data['id_categorie'];
    $event->lieu = $request->lieu;
    $event->date_debut = $request->date_debut;
    $event->date_fin = $request->date_fin;
    $event->exposant_id = Auth::id();
    $event->image = $data['image'];

    $event->save();
    return redirect()
        ->route('organisateur.events.index')
        ->with('success', "L'evenement a ete cree avec succes.");
}

/**
 * Mettre a jour un evenement existant (uniquement le proprietaire).
 */
public function update(Request $request, int $id)
{
    $event = Evenement::where('user_id', Auth::id())->findOrFail($id);

    $data =  $request->validate([
        'titre'        => ['required', 'string', 'max:255'],
        'lieu'         => ['required', 'string', 'max:255'],
        'date_debut'   => ['required', 'date'],
        'date_fin'     => ['required', 'date', 'after_or_equal:date_debut'],
        'id_categorie' => ['nullable', 'exists:categorie_stands,id'],
        'description'  => ['nullable', 'string'],
        'capacite'     => ['nullable', 'integer', 'min:1'],
        'image'        => ['nullable', 'image', 'max:5120'],
    ]);

    if ($request->hasFile('image')) {
        if ($event->image) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $event->image));
        }
        $path = Storage::disk('public')->put('evenements', $request->file('image'));
        $data['image'] = Storage::url($path);
    }

    $event->update($data);

    return redirect()
        ->route('organisateur.events.index')
        ->with('success', "L'evenement a ete mis a jour avec succes.");
}

/**
 * Supprimer un evenement (uniquement le proprietaire).
 */
public function destroy(int $id)
{
    $event = Evenement::where('user_id', Auth::id())->findOrFail($id);

    if ($event->image) {
        Storage::disk('public')->delete(str_replace('/storage/', '', $event->image));
    }

    $event->delete();

    return redirect()
        ->route('organisateur.events.index')
        ->with('success', "L'evenement a ete supprime.");
}


}
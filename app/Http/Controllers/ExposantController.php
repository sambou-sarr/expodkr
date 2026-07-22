<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\Exposant;
use App\Models\Reservation;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class ExposantController extends Controller
{
    public function index()
    {
        $exposants = Exposant::latest()->paginate(10);
        return view('admin.exposant.index', compact('exposants'));
    }

    public function create()
    {
        return view('admin.exposant.create');
    }

   public function store(Request $request)
{
    // 1. VALIDATION
    $data = $request->validate([
        'nom_entreprise' => 'required',
        'responsable' => 'required',
        'telephone' => 'required',
        'email' => 'required|email|unique:exposants',
        'adresse' => 'nullable',
        'description' => 'nullable',
        'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'statut' => 'nullable',
        'secteur_activite' => 'nullable',
        'site_web' => 'nullable',
        'facebook' => 'nullable',
        'instagram' => 'nullable',
        'linkedin' => 'nullable',
        'numero_registre' => 'nullable',
        'stand_numero' => 'nullable',
        'date_inscription' => 'nullable',
        'is_premium' => 'nullable',
    ]);

    // 2. UPLOAD IMAGE (STORAGE)
    $logoPath = null;

    if ($request->hasFile('logo') && $request->file('logo')->isValid()) {

        $file = $request->file('logo');

        $filename = time() . '_' . $file->getClientOriginalName();

        $logoPath = $file->storeAs('exposants', $filename, 'public');
    }

    // 3. SAVE WITH MODEL
    $exposant = new Exposant();

    $exposant->nom_entreprise = $data['nom_entreprise'];
    $exposant->responsable = $data['responsable'];
    $exposant->telephone = $data['telephone'];
    $exposant->email = $data['email'];
    $exposant->adresse = $data['adresse'] ?? null;
    $exposant->description = $data['description'] ?? null;

    $exposant->logo = $logoPath;

$exposant->statut = $data['statut'] ?? 'actif'; 
    $exposant->secteur_activite = $data['secteur_activite'] ?? null;
    $exposant->site_web = $data['site_web'] ?? null;
    $exposant->facebook = $data['facebook'] ?? null;
    $exposant->instagram = $data['instagram'] ?? null;
    $exposant->linkedin = $data['linkedin'] ?? null;
    $exposant->numero_registre = $data['numero_registre'] ?? null;
    $exposant->stand_numero = $data['stand_numero'] ?? null;
    $exposant->date_inscription = $data['date_inscription'] ?? null;
    $exposant->is_premium = $data['is_premium'] ?? 0;

    $exposant->save();

    return redirect()->route('exposants.index')
        ->with('success', 'Exposant ajouté avec succès');
}

    public function edit(Exposant $exposant)
    {
        return view('admin.exposant.edit', compact('exposant'));
    }

    public function update(Request $request, Exposant $exposant)
    {
        $exposant->update($request->all());

        return redirect()->route('exposants.index')
            ->with('success', 'Exposant modifié');
    }

    public function destroy(Exposant $exposant)
    {
        $exposant->delete();

        return redirect()->route('exposants.index')
            ->with('success', 'Exposant supprimé');
    }
    
     public function show(Exposant $exposant)
    {
        // Charger les relations nécessaires à la vue
        $exposant->load(['evenements.categorie']);

        return view('admin.exposant.show', compact('exposant'));
    }

    public function confirm(Reservation $reservation)
{
    $reservation->load('evenement.categorie', 'evenement.exposant');

    $autresEvents = Evenement::where('id', '!=', $reservation->evenement_id)
                             ->where('date_debut', '>', now())
                             ->latest()
                             ->take(3)
                             ->get();

    return view('reservations.confirm', compact('reservation', 'autresEvents'));
}
}
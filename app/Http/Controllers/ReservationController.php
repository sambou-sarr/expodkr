<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\Reservation;
use App\Mail\ReservationConfirmee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReservationController extends Controller
{
    /**
     * Affiche le formulaire de réservation (étapes 1-2-3) pour un événement donné.
     */
    public function create(Evenement $event)
    {
        $event->load(['categorie', 'exposant']);

        return view('visiteur.reservation.create', compact('event'));
    }

    /**
     * Traite la soumission du formulaire de réservation.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'event_id'   => ['required', 'exists:evenements,id'],
        'nom'        => ['required', 'string', 'max:255'],
        'email'      => ['required', 'email', 'max:255'],
        'telephone'  => ['required', 'string', 'max:30'],
        'nb_places'  => ['required', 'integer', 'min:1', 'max:10'],
        'paiement'   => ['required', 'in:sur_place,wave,orange'],
    ]);
    $event = Evenement::with(['categorie', 'exposant'])->findOrFail($validated['event_id']);
    $prixUnitaire = $event->categorie->prix ?? 0;
    $montantTotal = $prixUnitaire * $validated['nb_places'];
    $reference    = 'EXPO-' . strtoupper(\Illuminate\Support\Str::random(8));

    $reservation = Reservation::create([
        'evenement_id'     => $event->id,
        'nom'              => $validated['nom'],
        'email'            => $validated['email'],
        'telephone'        => $validated['telephone'],
        'nb_places'        => $validated['nb_places'],
        'mode_paiement'    => $validated['paiement'],
        'montant_total'    => $montantTotal,
        'reference'        => $reference,
        'date_reservation' => now(),
        'statut'           => $validated['paiement'] === 'sur_place' ? 'en_attente' : 'en_attente_paiement',
    ]);

    // Un Paiement lié si mode ≠ sur_place
    if ($validated['paiement'] !== 'sur_place') {
        $reservation->paiements()->create([
            'montant'  => $montantTotal,
            'methode'  => $validated['paiement'],
            'statut'   => 'en_attente',
        ]);
    }

    return redirect()
        ->route('reservations.success', $reservation->id)
        ->with('success', 'Réservation confirmée ! Un email vous a été envoyé.');
}
    /**
     * Page de confirmation après réservation réussie.
     */
 public function success(Reservation $reservation)
{
    $reservation->load(['evenement.categorie', 'evenement.exposant']);

    return view('visiteur.reservation.success', compact('reservation'));
}
}
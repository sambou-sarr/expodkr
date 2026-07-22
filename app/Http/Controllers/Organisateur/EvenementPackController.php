<?php

namespace App\Http\Controllers\Organisateur;

use App\Http\Controllers\Controller;
use App\Models\AchatPack;
use App\Models\Evenement;
use App\Models\Pack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EvenementPackController extends Controller
{
    /**
     * Affiche le choix de pack pour un événement fraîchement créé (en brouillon).
     */
    public function choisir(Evenement $evenement)
    {
        $this->authorizeOrganisateur($evenement);

        $packs = Pack::where('actif', true)->orderBy('ordre')->get();

        return view('organisateur.packs.choisir', compact('evenement', 'packs'));
    }

    /**
     * Traite le choix + paiement du pack pour un événement.
     */
    public function acheter(Request $request, Evenement $evenement)
    {
        $this->authorizeOrganisateur($evenement);

        $validated = $request->validate([
            'pack_id'       => ['required', 'exists:packs,id'],
            'mode_paiement' => ['required_if:pack_gratuit,false', 'nullable', 'in:wave,orange,virement'],
        ]);

        $pack = Pack::findOrFail($validated['pack_id']);
        $organisateur = Auth::user();

        // Vérifie la limite d'événements gratuits
        if ($pack->estGratuit()) {
            $nbEvenementsActifs = Evenement::where('organisateur_id', $organisateur->id)
                ->whereHas('pack', fn ($q) => $q->where('slug', 'gratuit'))
                ->count();

            if ($pack->max_evenements !== null && $nbEvenementsActifs >= $pack->max_evenements) {
                return back()->withErrors([
                    'pack_id' => "Vous avez atteint la limite du pack gratuit ({$pack->max_evenements} événement(s)). Choisissez un pack payant pour continuer.",
                ]);
            }
        }

        $reference = 'PACK-' . strtoupper(Str::random(8));

        $achat = AchatPack::create([
            'organisateur_id' => $organisateur->id,
            'evenement_id'    => $evenement->id,
            'pack_id'         => $pack->id,
            'montant'         => $pack->prix,
            'mode_paiement'   => $pack->estGratuit() ? 'gratuit' : $validated['mode_paiement'],
            'reference'       => $reference,
            'statut'          => $pack->estGratuit() ? 'confirme' : 'en_attente',
        ]);

        // Associe le pack à l'événement (immédiat si gratuit, sinon en attente de confirmation paiement)
        if ($pack->estGratuit()) {
            $evenement->update(['pack_id' => $pack->id]);

            return redirect()
                ->route('organisateur.evenements.show', $evenement->id)
                ->with('success', 'Votre événement est publié avec le pack Gratuit.');
        }

        // TODO: rediriger vers la page de paiement Wave/Orange (comme pour les réservations)
        return redirect()
            ->route('organisateur.packs.paiement', $achat->id)
            ->with('info', 'Finalisez le paiement pour activer votre pack.');
    }

    private function authorizeOrganisateur(Evenement $evenement): void
    {
        if ($evenement->organisateur_id !== Auth::id()) {
            abort(403, "Cet événement ne vous appartient pas.");
        }
    }
}
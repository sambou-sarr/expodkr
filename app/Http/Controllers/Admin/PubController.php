<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pub;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Cloudinary\Cloudinary;

class PubController extends Controller
{
    /**
     * Définition de toutes les zones publicitaires du site.
     * Ajoute/retire des lignes ici si tu changes les emplacements.
     */
    private function zonesDefinition(): array
    {
        return [
            'ap_habillage_gauche' => ['label' => 'Habillage gauche',  'w' => 160,  'h' => 900],
            'ap_habillage_droite' => ['label' => 'Habillage droite',  'w' => 160,  'h' => 900],
            'top_a2m'             => ['label' => 'Top bannière',      'w' => 970,  'h' => 250],
            'splh'                => ['label' => 'Bannière SPLH',     'w' => 1000, 'h' => 120],
            'a1r'                 => ['label' => 'Pavé A1R',          'w' => 300,  'h' => 600],
            'bloc_special'        => ['label' => 'Bloc spécial',      'w' => 300,  'h' => 600],
            'b1l'                 => ['label' => 'Bannière B1L',      'w' => 300,  'h' => 250],
            'b1r'                 => ['label' => 'Bannière B1R',      'w' => 300,  'h' => 250],
        ];
    }

    public function index()
    {
        $zonesDefinition = $this->zonesDefinition();

        // Récupère les images déjà enregistrées en base, indexées par zone
        $pubsEnregistrees = Pub::pluck('image', 'zone');

        $zones = [];
        foreach ($zonesDefinition as $key => $info) {
            $zones[$key] = [
                'label' => $info['label'],
                'w'     => $info['w'],
                'h'     => $info['h'],
                'image' => $pubsEnregistrees[$key] ?? null,
            ];
        }

        return view('admin.pubs.index', compact('zones'));
    }

    public function update(Request $request, string $zone)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $imageUrl = null;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));

            $uploadedFile = $cloudinary->uploadApi()->upload(
                $request->file('image')->getRealPath(),
                ['folder' => 'pubs']
            );

            $imageUrl = $uploadedFile['secure_url'];
        }

        Pub::updateOrCreate(
            ['zone' => $zone],
            ['image' => $imageUrl]
        );

        return redirect()->route('admin.pubs.index')
            ->with('success', 'Bannière mise à jour avec succès');
    }
}
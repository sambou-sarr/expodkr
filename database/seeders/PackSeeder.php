<?php

namespace Database\Seeders;

use App\Models\Pack;
use Illuminate\Database\Seeder;

class PackSeeder extends Seeder
{
    public function run(): void
    {
        $packs = [
            [
                'nom' => 'Gratuit',
                'slug' => 'gratuit',
                'description' => "Idéal pour tester la plateforme avec un premier événement.",
                'prix' => 0,
                'ordre' => 1,
                'max_evenements' => 1,
                'mise_en_avant' => false,
                'stats_avancees' => false,
                'support_dedie' => false,
                'couleur' => '#8892A4',
            ],
            [
                'nom' => 'Basique',
                'slug' => 'basique',
                'description' => "Publiez autant d'événements que vous voulez avec un suivi statistique.",
                'prix' => 15000,
                'ordre' => 2,
                'max_evenements' => null,
                'mise_en_avant' => false,
                'stats_avancees' => true,
                'support_dedie' => false,
                'couleur' => '#1E5FD8',
            ],
            [
                'nom' => 'Pro',
                'slug' => 'pro',
                'description' => "Gagnez en visibilité avec une mise en avant sur la page d'accueil.",
                'prix' => 35000,
                'ordre' => 3,
                'max_evenements' => null,
                'mise_en_avant' => true,
                'stats_avancees' => true,
                'support_dedie' => false,
                'couleur' => '#C9A84C',
            ],
            [
                'nom' => 'Premium',
                'slug' => 'premium',
                'description' => "Accompagnement dédié + visibilité maximale pour un lancement réussi.",
                'prix' => 75000,
                'ordre' => 4,
                'max_evenements' => null,
                'mise_en_avant' => true,
                'stats_avancees' => true,
                'support_dedie' => true,
                'couleur' => '#E8C96A',
            ],
        ];

        foreach ($packs as $pack) {
            Pack::updateOrCreate(['slug' => $pack['slug']], $pack);
        }
    }
}
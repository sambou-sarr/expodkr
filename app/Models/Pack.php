<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pack extends Model
{
    protected $fillable = [
        'nom', 'slug', 'description', 'prix', 'ordre',
        'max_evenements', 'mise_en_avant', 'stats_avancees',
        'support_dedie', 'couleur', 'actif',
    ];

    protected $casts = [
        'mise_en_avant'  => 'boolean',
        'stats_avancees' => 'boolean',
        'support_dedie'  => 'boolean',
        'actif'          => 'boolean',
    ];

    public function evenements()
    {
        return $this->hasMany(Evenement::class);
    }

    public function achats()
    {
        return $this->hasMany(AchatPack::class);
    }

    public function estGratuit(): bool
    {
        return $this->prix <= 0;
    }
}
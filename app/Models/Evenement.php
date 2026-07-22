<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    protected $fillable = [
        'titre',
        'id_categorie',
        'description',
        'date_debut',
        'date_fin',
        'lieu',
        'exposant_id', // 🔥 IMPORTANT AJOUT
    ];

    // 🔥 RESERVATIONS
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // 🔥 CATEGORIE
    public function categorie()
    {
        return $this->belongsTo(CategorieStand::class, 'id_categorie');
    }

    // 🔥 EXPOSANT (IMPORTANT)
    public function exposant()
    {
        return $this->belongsTo(Exposant::class, 'exposant_id');
    }
    public function pack()
{
    return $this->belongsTo(Pack::class);
}

public function organisateur()
{
    return $this->belongsTo(User::class, 'organisateur_id');
}

public function achatPack()
{
    return $this->hasOne(AchatPack::class);
}

public function estMisEnAvant(): bool
{
    return (bool) ($this->pack?->mise_en_avant ?? false);
}

public function aStatsAvancees(): bool
{
    return (bool) ($this->pack?->stats_avancees ?? false);
}
}
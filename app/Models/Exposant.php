<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exposant extends Model
{
  protected $fillable = [
    'nom_entreprise',
    'responsable',
    'telephone',
    'email',
    'adresse',
    'description',
    'logo',

    'statut',
    'secteur_activite',
    'site_web',

    'facebook',
    'instagram',
    'linkedin',

    'numero_registre',
    'stand_numero',

    'date_inscription',
    'is_premium',
    'exposant_id'
];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
    public function evenements()
{
    return $this->hasMany(Evenement::class);
}

}
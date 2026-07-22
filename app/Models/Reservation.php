<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'exposant_id',
        'stand_id',
        'evenement_id',
        'nom',
        'email',
        'telephone',
        'nb_places',
        'mode_paiement',
        'montant_total',
        'reference',
        'date_reservation',
        'statut',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exposant()
    {
        return $this->belongsTo(Exposant::class);
    }

    public function stand()
    {
        return $this->belongsTo(Stand::class);
    }

    public function evenement()
    {
        return $this->belongsTo(Evenement::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }
}
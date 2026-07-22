<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AchatPack extends Model
{
    protected $table = 'achats_packs';

    protected $fillable = [
        'organisateur_id', 'evenement_id', 'pack_id',
        'montant', 'mode_paiement', 'reference', 'statut',
    ];

    public function organisateur()
    {
        return $this->belongsTo(User::class, 'organisateur_id');
    }

    public function evenement()
    {
        return $this->belongsTo(Evenement::class);
    }

    public function pack()
    {
        return $this->belongsTo(Pack::class);
    }
}
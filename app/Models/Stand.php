<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stand extends Model
{
    protected $fillable = [
        'numero',
        'categorie_stand_id',
        'statut',
    ];

    public function categorie()
    {
        return $this->belongsTo(CategorieStand::class, 'categorie_stand_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}

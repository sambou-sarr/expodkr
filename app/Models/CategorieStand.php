<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategorieStand extends Model
{
    protected $fillable = [
        'nom',
        'prix',
        'description',
    ];

    public function stands()
    {
        return $this->hasMany(Stand::class);
    }
      public function evenements()
    {
        return $this->hasMany(Evenement::class, 'id_categorie');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    protected $table = 'articles';

    protected $fillable = [
        'titre',
        'slug',
        'image',
        'extrait',
        'contenu',
        'categorie',
        'auteur_id',
        'date_publication',
        'temps_lecture',
        'statut',
    ];

    protected $casts = [
        'date_publication' => 'datetime',
    ];

    /**
     * Auteur de l'article.
     */
    public function auteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auteur_id');
    }
}
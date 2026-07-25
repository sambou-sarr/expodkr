<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pub extends Model
{
    protected $table = 'pub';

    protected $fillable = ['zone', 'image'];

    public $timestamps = false;
}
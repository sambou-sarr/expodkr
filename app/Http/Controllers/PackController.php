<?php

namespace App\Http\Controllers;

use App\Models\Pack;

class PackController extends Controller
{
    public function index()
    {
        $packs = Pack::where('actif', true)->orderBy('ordre')->get();

        return view('packs.index', compact('packs'));
    }
}
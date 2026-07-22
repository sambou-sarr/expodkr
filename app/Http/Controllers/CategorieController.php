<?php

namespace App\Http\Controllers;

use App\Models\CategorieStand;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function index()
    {
        $categories = CategorieStand::all();
        return view('admin.categorie.index', compact('categories'));
    }

  public function store(Request $request)
{
    $request->validate([
        'nom' => 'required|string|max:255',
        'prix' => 'required|numeric|min:0',
        'description' => 'nullable|string',
    ]);

    CategorieStand::create([
        'nom' => $request->nom,
        'prix' => $request->prix,
        'description' => $request->description,
    ]);

    return redirect()->route('categories.index')
        ->with('success', 'Catégorie ajoutée avec succès');
}

    public function destroy($id)
    {
        CategorieStand::find($id)->delete();
        return back();
    }
    public function create(){
        return view('admin.categorie.create');
    }
}
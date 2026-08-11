<?php

namespace App\Http\Controllers;
   use App\Models\Evenement;
use App\Models\CategorieStand;
use App\Models\Exposant;
use App\Models\Article;
use App\Models\Visibilite;
use App\Models\Pub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisteurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
       $nombre = Visibilite::find(1);
    if($nombre == null ){
       $nmbre1 = new Visibilite();
       $nmbre1 -> nmbre = 1;
       $nmbre1->save(); 
    }else{

     $nombre->nmbre = $nombre->nmbre + 1;
     $nombre->update();

    }
    $events = Evenement::with('categorie')->limit(4)->get();
    $categories = CategorieStand::take(3)->get();
    $exposants = Exposant::take(3)->get();
    $pubZones = Pub::all()->mapWithKeys(function ($pub) {
        return [$pub->zone => ['image' => $pub->image, 'lien' => '#']];
    })->toArray();
    $articles = Article::where('statut', 'publie')
    ->whereNotNull('date_publication')
    ->where('date_publication', '<=', now())
    ->orderByDesc('date_publication')
    ->take(3)
    ->get();
    return view('index', compact('events', 'categories', 'exposants', 'pubZones','articles'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $event = Evenement::with(['categorie', 'exposant'])->findOrFail($id);

        return view('visiteur.voir_plus', compact('event'));
    }
    public function showex($id)
    {
        $exposant = Exposant::findOrFail($id);

        return view('visiteur.exposant-show', compact('exposant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
public function listevents(Request $request)
{
    $query = Evenement::with(['categorie', 'exposant']);

    if ($request->filled('q')) {
        $query->where(function($q) use ($request) {
            $q->where('titre', 'like', "%{$request->q}%")
              ->orWhere('lieu', 'like', "%{$request->q}%");
        });
    }
    if ($request->filled('categorie')) $query->where('id_categorie', $request->categorie);
    if ($request->filled('exposant'))  $query->where('id_exposant', $request->exposant);
    if ($request->filled('periode')) {
        match($request->periode) {
            'upcoming' => $query->where('date_debut', '>', now()),
            'ongoing'  => $query->where('date_debut', '<=', now())->where('date_fin', '>=', now()),
            'past'     => $query->where('date_fin', '<', now()),
        };
    }

    $events     = $query->latest()->paginate(9)->withQueryString();
    $categories = CategorieStand::all();
    $exposants  = Exposant::all();
    return view('visiteur.listevents', compact('events', 'categories', 'exposants'));
}

    public function compte()
    {
        $user = Auth::user();

        $reservations = $user->reservations()
            ->with(['evenement.categorie'])
            ->latest()
            ->get();

        $now = now();

        $stats = [
            'total'    => $reservations->count(),
            'aVenir'   => $reservations->filter(function ($r) use ($now) {
                return $r->evenement && $now->lt(\Carbon\Carbon::parse($r->evenement->date_debut));
            })->count(),
            'termines' => $reservations->filter(function ($r) use ($now) {
                return $r->evenement && $now->gt(\Carbon\Carbon::parse($r->evenement->date_fin));
            })->count(),
            'annules'  => $reservations->where('statut', 'annule')->count(),
        ];

        return view('visiteur.mon-compte', compact('reservations', 'stats'));
    }
  public function index1()
    {
        $categories = CategorieStand::withCount('evenements')->get();

        return view('visiteur.listcategorie', compact('categories'));
    }

    public function show1(CategorieStand $categorie)
    {
        $events = $categorie->evenements()
            ->with('exposant')
            ->latest()
            ->paginate(9);

        return view('visiteur.showcategorie', compact('categorie', 'events'));
    }
}
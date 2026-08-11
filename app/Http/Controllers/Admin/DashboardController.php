<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use App\Models\User;
use App\Models\Paiement;
use App\Models\Visibilite;

class DashboardController extends Controller
{
    public function index()
    {
        $vis= Visibilite::find(1);
        $eventsCount = Evenement::count();
        $usersCount = $vis->nmbre;
        $revenue = Paiement::where('statut', 'paye')->sum('montant');

        $latestEvents = Evenement::latest()->take(5)->get();
        $latestUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'eventsCount',
            'usersCount',
            'revenue',
            'latestEvents',
            'latestUsers'
        ));
    }
}
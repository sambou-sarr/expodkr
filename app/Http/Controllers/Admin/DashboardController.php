<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use App\Models\User;
use App\Models\Paiement;

class DashboardController extends Controller
{
    public function index()
    {
        $eventsCount = Evenement::count();
        $usersCount = User::count();
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
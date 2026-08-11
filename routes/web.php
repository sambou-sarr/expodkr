<?php

use Illuminate\Support\Facades\Route;

// Controllers - Public / Visiteur
use App\Http\Controllers\VisteurController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\PackController;

// Controllers - Espace utilisateur connecté
use App\Http\Controllers\ProfileController;

// Controllers - Organisateur
use App\Http\Controllers\OrganisateurController;
use App\Http\Controllers\Organisateur\EvenementPackController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\NewsletterController;

// Controllers - Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PubController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ExposantController;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
Route::get('/create-admin', function () {
   $superAdmin = User::create([
            'prenom' => 'sambou', // Champ ajouté à votre migration
            'name' => 'sarr', // Champ ajouté à votre migration
            'email' => 'sarrsambou03@gmail.com', // L'email de connexion
            'password' => Hash::make('12345678'), // Mot de passe facile pour le test
            'email_verified_at' => now(),
             'role' => 'admin',
            'telephone' => '772476160'
        ]);

    return "Admin créé avec succès : " .  $superAdmin->username;
});

Route::get('/run-migrations', function () {

    Artisan::call('migrate', [
        '--force' => true,
    ]);

    return "Toutes les migrations ont été exécutées avec succès !";
});


Route::get('/clear-cache', function () {
    Artisan::call('config:cache');
    Artisan::call('route:cache');
    Artisan::call('view:clear');
    return "Cache vidé !";
});

// ═══════════════════════════════════════════════
// PUBLIC - VISITEUR
// ═══════════════════════════════════════════════

Route::get('/', [VisteurController::class, 'index'])->name('home');
Route::get('/user/events', [VisteurController::class, 'listevents'])->name('user.events.index');
Route::get('/user/events/search', [VisteurController::class, 'search'])->name('user.events.search');
Route::get('/user/events/{id}', [VisteurController::class, 'show'])->name('user.events.show');
Route::get('/user/exposants/{id}', [VisteurController::class, 'showex'])->name('user.exposants.show');

Route::get('/blog', [BlogController::class, 'index'])
    ->name('blog.index');

Route::get('/blog/{article:slug}', [BlogController::class, 'show'])
    ->name('blog.show');

Route::get('/contact', function () {
    return view('visiteur.contact');
})->name('contact');

Route::post('/contact', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'nom' => 'required|string|max:255',
        'email' => 'required|email',
        'sujet' => 'nullable|string|max:255',
        'message' => 'required|string',
    ]);

    // TODO: envoyer l'email / enregistrer le message (Mail::send, ou Contact::create(...))

    return back()->with('status', 'Votre message a bien été envoyé, merci !');
})->name('contact.send');

Route::get('user/categories', [VisteurController::class, 'index1'])->name('user.categories.index');
Route::get('user/categories/{categorie}', [VisteurController::class, 'show1'])->name('user.categories.show');
// Tarification publique
Route::get('/tarifs', [PackController::class, 'index'])->name('packs.index');

// Réservations
Route::get('/evenements/reserver/{event}', [ReservationController::class, 'create'])->name('reservations.create');
Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
Route::get('/reservations/{reservation}/succes', [ReservationController::class, 'success'])->name('reservations.success');


Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->middleware('throttle:5,1') // 5 tentatives / minute / IP
    ->name('user.newsletter.subscribe');

Route::get('/newsletter/confirm/{subscriber}', [NewsletterController::class, 'confirm'])
    ->middleware('signed')
    ->name('user.newsletter.confirm');

Route::get('/newsletter/unsubscribe/{subscriber}', [NewsletterController::class, 'unsubscribe'])
    ->middleware('signed')
    ->name('user.newsletter.unsubscribe');
// ═══════════════════════════════════════════════
// ESPACE UTILISATEUR CONNECTÉ (auth)
// ═══════════════════════════════════════════════

Route::middleware('auth')->group(function () {

    // Mon compte
    Route::get('/mon-compte', [VisteurController::class, 'compte'])->name('account');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ═══════════════════════════════════════════════
// ESPACE ORGANISATEUR (auth)
// ═══════════════════════════════════════════════

Route::middleware(['auth','organisateur'])->prefix('organisateur')->name('organisateur.')->group(function () {
    Route::get('evenements/{evenement}/pack', [EvenementPackController::class, 'choisir'])->name('packs.choisir');
    Route::post('evenements/{evenement}/pack', [EvenementPackController::class, 'acheter'])->name('packs.acheter');
    Route::get('dashboard', [OrganisateurController::class, 'index'])->name('dashboard');
   // Dashboard
 
    // Evenements (CRUD complet)
    Route::get('events', [OrganisateurController::class, 'index'])->name('events.index');
    Route::get('events/create', [OrganisateurController::class, 'create'])->name('events.create');
    Route::post('events', [OrganisateurController::class, 'store'])->name('events.store');
    Route::get('events/{id}', [OrganisateurController::class, 'show'])->name('events.show');
    Route::put('events/{id}', [OrganisateurController::class, 'update'])->name('events.update');
    Route::delete('events/{id}', [OrganisateurController::class, 'destroy'])->name('events.destroy');
 
    // Reservations recues sur les evenements de l'organisateur
    Route::get('reservations', [OrganisateurController::class, 'reservations'])->name('reservations.index');
    // Profil organisateur
    Route::get('profil', [OrganisateurController::class, 'profil'])->name('profil');
 
    // Packs
    Route::get('evenements/{evenement}/pack', [EvenementPackController::class, 'choisir'])->name('packs.choisir');
    Route::post('evenements/{evenement}/pack', [EvenementPackController::class, 'acheter'])->name('packs.acheter');
});


// ═══════════════════════════════════════════════
// ADMIN - DASHBOARD (auth + verified)
// ═══════════════════════════════════════════════



// ═══════════════════════════════════════════════
// ADMIN - 
// ═══════════════════════════════════════════════

Route::middleware(['auth','admin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/event/list', [EventsController::class, 'listevents'])->name('events.index');
    Route::get('/admin/event/create', [EventsController::class, 'create'])->name('events.create');
    Route::post('/admin/event/create', [EventsController::class, 'store'])->name('events.store');
    Route::get('/admin/event/{id}', [EventsController::class, 'show'])->name('events.show');
    Route::get('/admin/events/update/{id}', [EventsController::class, 'update'])->name('events.update');
    Route::get('/admin/events/edit/{id}', [EventsController::class, 'edit'])->name('events.edit');
    Route::delete('/admin/user/supprimer/{id}', [EventsController::class, 'destroy'])->name('events.destroy');


    
    Route::get('admin/categories/list', [CategorieController::class, 'index'])->name('categories.index');
    Route::get('admin/categories/create', [CategorieController::class, 'create'])->name('categories.create');
    Route::post('admin/categories/create', [CategorieController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}/edit', [CategorieController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [CategorieController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategorieController::class, 'destroy'])->name('categories.destroy');

    Route::get('/admin/user/list', [UserController::class, 'index'])->name('users.index');
    Route::get('/admin/user/{id}', [UserController::class, 'show'])->name('users.show');


Route::get('/admin/pubs', [PubController::class, 'index'])->name('admin.pubs.index');
Route::post('/admin/pubs/{zone}', [PubController::class, 'update'])->name('admin.pubs.update');
    
});


// ═══════════════════════════════════════════════
// ADMIN - EXPOSANTS (auth)
// ═══════════════════════════════════════════════

Route::resource('exposants', ExposantController::class)->middleware(['auth']);

// ═══════════════════════════════════════════════
// ROUTES DE PREVIEW (test rapide, sans logique métier)
// ═══════════════════════════════════════════════

Route::get('/tarifs', function () {
    $packs = \App\Models\Pack::where('actif', true)->orderBy('ordre')->get();
    return view('visiteur.organisateur.pack.index', compact('packs'));
})->name('tarifs');

Route::get('/preview/packs/choisir', function () {
    $evenement = \App\Models\Evenement::first();
    $packs = \App\Models\Pack::where('actif', true)->orderBy('ordre')->get();
    return view('visiteur.organisateur.pack.choisir', compact('evenement', 'packs'));
})->name('preview.packs.choisir');

Route::get('/preview/packs/paiement', function () {
    $achat = \App\Models\AchatPack::with(['pack', 'evenement'])->first();
    return view('visiteur.organisateur.pack.paiement', compact('achat'));
})->name('preview.packs.paiement');


require __DIR__.'/auth.php';
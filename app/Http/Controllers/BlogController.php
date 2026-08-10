<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Liste des articles.
     */
    public function index(Request $request)
    {
        $articles = Article::query()
            ->where('statut', 'publie')
            ->orderByDesc('date_publication')
            ->paginate(9);

        return view('visiteur.article ', compact('articles'));
    }

    /**
     * Affichage d'un article.
     */
    public function show(Article $article)
    {
        // Sécurité : ne pas afficher les articles non publiés
        if ($article->statut !== 'publie') {
            abort(404);
        }

        // Articles liés
        $autresArticles = Article::query()
            ->where('statut', 'publie')
            ->where('id', '!=', $article->id)
            ->where('categorie', $article->categorie)
            ->orderByDesc('date_publication')
            ->limit(3)
            ->get();

        // S'il n'y a pas assez d'articles dans la même catégorie,
        // compléter avec les autres articles récents.
        if ($autresArticles->count() < 3) {
            $ids = $autresArticles
                ->pluck('id')
                ->push($article->id);

            $complement = Article::query()
                ->where('statut', 'publie')
                ->whereNotIn('id', $ids)
                ->orderByDesc('date_publication')
                ->limit(3 - $autresArticles->count())
                ->get();

            $autresArticles = $autresArticles->concat($complement);
        }

        return view('visiteur.article', compact(
            'article',
            'autresArticles'
        ));
    }
}
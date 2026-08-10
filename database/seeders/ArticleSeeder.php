<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [

            [
                'titre' => 'Comment bien préparer sa participation à un salon',
                'categorie' => 'Conseils exposants',
                'image' => 'https://www.firstevent.co.uk/wp-content/uploads/2024/09/Cardano-D1-316-1-1.jpg',
                'extrait' => 'Les bonnes pratiques pour maximiser votre visibilité et vos contacts lors d’un événement professionnel.',
                'contenu' => '
                    <p>Participer à un salon professionnel représente un investissement important en temps et en ressources.</p>

                    <p>Une bonne préparation permet de transformer votre présence sur un salon en véritable opportunité commerciale.</p>

                    <h2>1. Définir vos objectifs</h2>

                    <p>Avant de réserver votre stand, définissez clairement vos objectifs : générer des prospects, présenter un nouveau produit, développer votre réseau ou renforcer votre visibilité.</p>

                    <h2>2. Soigner votre stand</h2>

                    <p>Votre stand doit être visible, professionnel et facilement identifiable. Utilisez une signalétique claire et un message simple.</p>

                    <h2>3. Préparer votre équipe</h2>

                    <p>Votre équipe représente votre entreprise. Préparez un discours court, professionnel et orienté vers les besoins des visiteurs.</p>

                    <h2>4. Assurer le suivi</h2>

                    <p>Après l’événement, contactez rapidement les personnes rencontrées. Un bon suivi peut transformer une simple rencontre en véritable opportunité commerciale.</p>
                ',
                'date_publication' => Carbon::now()->subDays(4),
                'temps_lecture' => 6,
                'statut' => 'publie',
            ],

            [
                'titre' => 'Le networking B2B au Sénégal en pleine croissance',
                'categorie' => 'Tendances',
                'image' => 'https://www.conferenceexpo.com/wp-content/uploads/2019/02/conference-expo-by-nimlok-gallery-d.jpg',
                'extrait' => 'Un tour d’horizon des tendances qui structurent les rencontres professionnelles au Sénégal.',
                'contenu' => '
                    <p>Le networking professionnel occupe une place de plus en plus importante dans l’écosystème économique sénégalais.</p>

                    <h2>Des rencontres qui créent des opportunités</h2>

                    <p>Les salons, conférences et événements professionnels permettent aux entreprises de rencontrer de nouveaux partenaires et clients.</p>

                    <h2>Un écosystème dynamique</h2>

                    <p>Dakar accueille chaque année de nombreux événements réunissant entrepreneurs, investisseurs, institutions et professionnels.</p>

                    <p>Ces rencontres contribuent au développement des collaborations et à la création de nouvelles opportunités.</p>
                ',
                'date_publication' => Carbon::now()->subDays(8),
                'temps_lecture' => 5,
                'statut' => 'publie',
            ],

            [
                'titre' => '5 conseils pour un stand qui attire les visiteurs',
                'categorie' => 'Conseils exposants',
                'image' => 'https://elleevents.com.au/wp-content/uploads/2020/06/conference-exhibition-CLIA_Sydney2018.jpg',
                'extrait' => 'Design, positionnement et communication : découvrez ce qui fait réellement la différence sur un salon.',
                'contenu' => '
                    <p>Un stand professionnel doit attirer l’attention en quelques secondes.</p>

                    <h2>1. Utilisez un message clair</h2>

                    <p>Les visiteurs doivent immédiatement comprendre ce que votre entreprise propose.</p>

                    <h2>2. Travaillez votre identité visuelle</h2>

                    <p>Les couleurs, le logo et les supports visuels doivent être cohérents avec votre image de marque.</p>

                    <h2>3. Facilitez les échanges</h2>

                    <p>Prévoyez un espace permettant aux visiteurs de discuter facilement avec votre équipe.</p>

                    <h2>4. Proposez une expérience</h2>

                    <p>Une démonstration ou une animation peut permettre à votre entreprise de se démarquer.</p>

                    <h2>5. Collectez les contacts</h2>

                    <p>Ne laissez pas repartir un prospect intéressant sans moyen de le recontacter.</p>
                ',
                'date_publication' => Carbon::now()->subDays(12),
                'temps_lecture' => 4,
                'statut' => 'publie',
            ],

            [
                'titre' => 'Pourquoi participer aux événements professionnels à Dakar ?',
                'categorie' => 'Événements',
                'image' => 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?auto=format&fit=crop&w=1200&q=80',
                'extrait' => 'Les événements professionnels sont devenus un véritable levier pour développer son réseau et son activité.',
                'contenu' => '
                    <p>Dakar est aujourd’hui un centre majeur pour les rencontres professionnelles en Afrique de l’Ouest.</p>

                    <h2>Développer son réseau</h2>

                    <p>Les événements permettent de rencontrer directement des professionnels partageant les mêmes centres d’intérêt.</p>

                    <h2>Découvrir de nouvelles opportunités</h2>

                    <p>Participer régulièrement à des salons et conférences permet de rester informé des nouvelles tendances du marché.</p>

                    <h2>Développer son entreprise</h2>

                    <p>Une présence régulière dans les événements professionnels peut contribuer à renforcer la notoriété d’une entreprise.</p>
                ',
                'date_publication' => Carbon::now()->subDays(18),
                'temps_lecture' => 5,
                'statut' => 'publie',
            ],

            [
                'titre' => 'Les tendances événementielles à suivre au Sénégal',
                'categorie' => 'Tendances',
                'image' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=1200&q=80',
                'extrait' => 'Digitalisation, networking et expériences immersives transforment progressivement les événements professionnels.',
                'contenu' => '
                    <p>Le secteur événementiel évolue rapidement avec l’arrivée de nouvelles technologies et de nouvelles habitudes professionnelles.</p>

                    <h2>La digitalisation</h2>

                    <p>Les plateformes numériques facilitent désormais l’inscription, la communication et le suivi des participants.</p>

                    <h2>Des événements plus interactifs</h2>

                    <p>Les organisateurs cherchent à proposer des expériences plus engageantes afin de favoriser les échanges.</p>

                    <h2>Une meilleure visibilité</h2>

                    <p>Les réseaux sociaux permettent également aux événements d’atteindre une audience beaucoup plus large.</p>
                ',
                'date_publication' => Carbon::now()->subDays(23),
                'temps_lecture' => 5,
                'statut' => 'publie',
            ],

        ];

        foreach ($articles as $article) {

            Article::create([
                'titre' => $article['titre'],
                'slug' => Str::slug($article['titre']),
                'categorie' => $article['categorie'],
                'image' => $article['image'],
                'extrait' => $article['extrait'],
                'contenu' => $article['contenu'],
                'auteur_id' => null,
                'date_publication' => $article['date_publication'],
                'temps_lecture' => $article['temps_lecture'],
                'statut' => $article['statut'],
            ]);

        }
    }
}
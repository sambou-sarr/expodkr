<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class NewsletterController extends Controller
{
    /**
     * Inscription à la newsletter
     */
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
            ],
        ]);
        $subscriber = NewsletterSubscriber::where('email', $validated['email'])->first();

        // Déjà inscrit et confirmé
        if ($subscriber && $subscriber->confirmed_at) {
            return back()->with('newsletter_success',
                'Cette adresse email est déjà inscrite à notre newsletter.'
            );
        }

        // Créer ou récupérer l'abonné
        if (!$subscriber) {
            $subscriber = NewsletterSubscriber::create([
                'email' => $validated['email'],
                'confirmed_at' => null,
            ]);
        }

        // URL de confirmation signée
        $confirmationUrl = URL::temporarySignedRoute(
            'user.newsletter.confirm',
            now()->addHours(24),
            ['subscriber' => $subscriber->id]
        );

        /*
        |--------------------------------------------------------------------------
        | Envoi de l'email
        |--------------------------------------------------------------------------
        | Tu peux ici utiliser Mail::to(...)->send(...)
        | avec une Mailable NewsletterConfirmationMail.
        */

        return back()->with(
            'newsletter_success',
            'Merci ! Consultez votre boîte email pour confirmer votre inscription.'
        );
    }


    /**
     * Confirmation de l'inscription
     */
    public function confirm(Request $request, NewsletterSubscriber $subscriber)
    {
        if ($subscriber->confirmed_at) {
            return redirect('/')
                ->with('newsletter_success',
                    'Votre inscription à la newsletter est déjà confirmée.'
                );
        }

        $subscriber->update([
            'confirmed_at' => now(),
        ]);

        return redirect('/')
            ->with(
                'newsletter_success',
                'Votre inscription à la newsletter est maintenant confirmée !'
            );
    }


    /**
     * Désinscription
     */
    public function unsubscribe(
        Request $request,
        NewsletterSubscriber $subscriber
    ) {
        $subscriber->delete();

        return redirect('/')
            ->with(
                'newsletter_success',
                'Vous êtes maintenant désinscrit de la newsletter.'
            );
    }
}
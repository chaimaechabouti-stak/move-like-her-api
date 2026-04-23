<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Abonnement;
use App\Models\Inscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * POST /api/stripe/checkout
     * Crée une Stripe Checkout Session et retourne l'URL de paiement.
     */
    public function createCheckout(Request $request)
    {
        $data = $request->validate([
            'abonnement_id' => 'required|exists:abonnements,id',
            'salle_id'      => 'nullable|exists:salles,id',
            'frequence'     => 'required|in:mensuel,annuel',
        ]);

        $abonnement = Abonnement::findOrFail($data['abonnement_id']);
        $user       = $request->user();

        $montant = $data['frequence'] === 'annuel'
            ? ($abonnement->prix_annuel ?? $abonnement->prix_mensuel)
            : $abonnement->prix_mensuel;

        // Créer une inscription en attente
        $dateDebut = Carbon::today();
        $dateFin   = $data['frequence'] === 'annuel'
            ? $dateDebut->copy()->addYear()
            : $dateDebut->copy()->addMonth();

        // Annuler l'éventuel abonnement actif
        Inscription::where('user_id', $user->id)
            ->where('statut', 'active')
            ->update(['statut' => 'annulee']);

        $inscription = Inscription::create([
            'user_id'       => $user->id,
            'abonnement_id' => $abonnement->id,
            'salle_id'      => $data['salle_id'] ?? null,
            'frequence'     => $data['frequence'],
            'montant_paye'  => $montant,
            'statut'        => 'en_attente',
            'date_debut'    => $dateDebut,
            'date_fin'      => $dateFin,
        ]);

        $frontendUrl = config('app.frontend_url', 'http://localhost:5173');

        $session = Session::create([
            'payment_method_types' => ['card'],
            'customer_email'       => $user->email,
            'line_items'           => [[
                'price_data' => [
                    'currency'     => 'mad',
                    'unit_amount'  => $montant * 100, // en centimes
                    'product_data' => [
                        'name'        => $abonnement->nom . ' — ' . ucfirst($data['frequence']),
                        'description' => "Abonnement Move Like Her {$abonnement->nom}",
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode'              => 'payment',
            'success_url'       => $frontendUrl . '/paiement/succes?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'        => $frontendUrl . '/abonnements?cancelled=1',
            'metadata'          => [
                'inscription_id' => $inscription->id,
                'user_id'        => $user->id,
            ],
        ]);

        $inscription->update(['stripe_session_id' => $session->id]);

        return response()->json(['url' => $session->url]);
    }

    /**
     * GET /api/stripe/confirm?session_id=xxx
     * Appelé depuis la page succès — active l'inscription si le paiement est réel.
     */
    public function confirm(Request $request)
    {
        $sessionId = $request->query('session_id');
        if (!$sessionId) {
            return response()->json(['error' => 'session_id manquant.'], 422);
        }

        $session = Session::retrieve($sessionId);

        if ($session->payment_status !== 'paid') {
            return response()->json(['error' => 'Paiement non complété.'], 402);
        }

        $inscription = Inscription::where('stripe_session_id', $sessionId)->first();

        if (!$inscription) {
            return response()->json(['error' => 'Inscription introuvable.'], 404);
        }

        if ($inscription->statut !== 'active') {
            $inscription->update([
                'statut'                => 'active',
                'stripe_payment_intent' => $session->payment_intent,
            ]);
        }

        return response()->json($inscription->load('abonnement'));
    }

    /**
     * POST /api/stripe/webhook
     * Reçoit les événements Stripe et active l'abonnement si paiement réussi.
     */
    public function webhook(Request $request)
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret    = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (SignatureVerificationException $e) {
            return response()->json(['error' => 'Signature invalide.'], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session     = $event->data->object;
            $inscription = Inscription::where('stripe_session_id', $session->id)->first();

            if ($inscription) {
                $inscription->update([
                    'statut'                => 'active',
                    'stripe_payment_intent' => $session->payment_intent,
                ]);
            }
        }

        return response()->json(['status' => 'ok']);
    }
}

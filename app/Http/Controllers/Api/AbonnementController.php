<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Abonnement;
use App\Models\Inscription;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbonnementController extends Controller
{
    /** GET /api/abonnements */
    public function index()
    {
        return response()->json(
            Abonnement::where('actif', true)->orderBy('ordre')->get()
        );
    }

    /** POST /api/abonnements/souscrire — Auth requis */
    public function souscrire(Request $request)
    {
        $data = $request->validate([
            'abonnement_id' => 'required|exists:abonnements,id',
            'salle_id'      => 'nullable|exists:salles,id',
            'frequence'     => 'required|in:mensuel,annuel',
        ]);

        $abonnement = Abonnement::findOrFail($data['abonnement_id']);
        $user = $request->user();

        // Calculer le montant et la date de fin
        $dateDebut = Carbon::today();
        if ($data['frequence'] === 'annuel') {
            $montant  = $abonnement->prix_annuel ?? $abonnement->prix_mensuel;
            $dateFin  = $dateDebut->copy()->addYear();
        } else {
            $montant  = $abonnement->prix_mensuel;
            $dateFin  = $dateDebut->copy()->addMonth();
        }

        // Annuler l'abonnement actif existant
        Inscription::where('user_id', $user->id)
            ->where('statut', 'active')
            ->update(['statut' => 'annulee']);

        $inscription = Inscription::create([
            'user_id'        => $user->id,
            'abonnement_id'  => $abonnement->id,
            'salle_id'       => $data['salle_id'] ?? null,
            'frequence'      => $data['frequence'],
            'montant_paye'   => $montant,
            'statut'         => 'active',
            'date_debut'     => $dateDebut,
            'date_fin'       => $dateFin,
        ]);

        return response()->json(
            $inscription->load('abonnement', 'salle'),
            201
        );
    }

    /** GET /api/mon-abonnement — Auth requis */
    public function monAbonnement(Request $request)
    {
        $inscription = Inscription::with(['abonnement', 'salle.ville'])
            ->where('user_id', $request->user()->id)
            ->where('statut', 'active')
            ->latest()
            ->first();

        return response()->json($inscription);
    }
}

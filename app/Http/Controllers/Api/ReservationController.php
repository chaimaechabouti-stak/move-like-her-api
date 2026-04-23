<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Inscription;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function reserver(Request $request)
    {
        $request->validate(['cours_id' => 'required|exists:cours,id']);

        $user = $request->user();

        $hasActive = Inscription::where('user_id', $user->id)
            ->where('statut', 'active')
            ->where('date_fin', '>=', now())
            ->exists();

        if (!$hasActive) {
            return response()->json(['message' => 'Abonnement actif requis.'], 403);
        }

        $reservation = Reservation::firstOrCreate(
            ['user_id' => $user->id, 'cours_id' => $request->cours_id],
            ['statut' => 'confirmee']
        );

        if (!$reservation->wasRecentlyCreated && $reservation->statut === 'confirmee') {
            return response()->json(['message' => 'Déjà réservé.'], 409);
        }

        if (!$reservation->wasRecentlyCreated) {
            $reservation->update(['statut' => 'confirmee']);
        }

        return response()->json(['message' => 'Réservation confirmée.', 'reservation' => $reservation], 201);
    }

    public function annuler(Request $request, $coursId)
    {
        $reservation = Reservation::where('user_id', $request->user()->id)
            ->where('cours_id', $coursId)
            ->firstOrFail();

        $reservation->update(['statut' => 'annulee']);

        return response()->json(['message' => 'Réservation annulée.']);
    }

    public function mesReservations(Request $request)
    {
        $reservations = Reservation::with(['cours'])
            ->where('user_id', $request->user()->id)
            ->where('statut', 'confirmee')
            ->latest()
            ->get();

        return response()->json($reservations);
    }
}

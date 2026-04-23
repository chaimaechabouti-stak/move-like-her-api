<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    /** GET /api/coaches */
    public function index(Request $request)
    {
        $query = Coach::with(['user', 'salle.ville'])
            ->where('active', true);

        if ($request->filled('salle_id')) {
            $query->where('salle_id', $request->salle_id);
        }

        return response()->json($query->get());
    }

    /** GET /api/coaches/{id} */
    public function show(int $id)
    {
        $coach = Coach::with(['user', 'salle.ville', 'seances.cours'])
            ->where('active', true)
            ->findOrFail($id);

        return response()->json($coach);
    }

    /** GET /api/mes-seances — Coach connecté */
    public function mesSeances(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'coach') {
            return response()->json(['message' => 'Accès réservé aux coaches.'], 403);
        }

        $coach = $user->coach;

        if (!$coach) {
            return response()->json(['message' => 'Profil coach introuvable.'], 404);
        }

        $seances = $coach->seances()
            ->with(['cours', 'salle.ville'])
            ->where('active', true)
            ->orderByRaw("FIELD(jour,'Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi')")
            ->orderBy('heure_debut')
            ->get();

        return response()->json([
            'coach'   => $coach->load('user', 'salle.ville'),
            'seances' => $seances,
        ]);
    }
}

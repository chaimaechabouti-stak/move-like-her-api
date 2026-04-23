<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Seance;
use Illuminate\Http\Request;

class CoursController extends Controller
{
    /** GET /api/cours */
    public function index(Request $request)
    {
        $query = Cours::where('actif', true);

        if ($request->filled('niveau')) {
            $query->where('niveau', $request->niveau);
        }

        return response()->json($query->orderBy('nom')->get());
    }

    /** GET /api/cours/{slug} */
    public function show(string $slug)
    {
        $cours = Cours::where('slug', $slug)
            ->where('actif', true)
            ->firstOrFail();

        return response()->json($cours);
    }

    /** GET /api/planning — planning hebdomadaire d'une salle */
    public function planning(Request $request)
    {
        $request->validate([
            'salle_id' => 'nullable|exists:salles,id',
        ]);

        $query = Seance::with(['cours', 'coach.user'])
            ->where('active', true)
            ->orderByRaw("FIELD(jour, 'Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi')")
            ->orderBy('heure_debut');

        if ($request->filled('salle_id')) {
            $query->where('salle_id', $request->salle_id);
        }

        // Grouper par jour
        $seances = $query->get()->groupBy('jour');

        return response()->json($seances);
    }
}

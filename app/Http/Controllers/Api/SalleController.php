<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Salle;
use App\Models\Ville;
use Illuminate\Http\Request;

class SalleController extends Controller
{
    /** GET /api/salles */
    public function index(Request $request)
    {
        $query = Salle::with('ville')->where('active', true);

        if ($request->filled('ville')) {
            $query->whereHas('ville', fn($q) => $q->where('slug', $request->ville));
        }

        return response()->json($query->get());
    }

    /** GET /api/salles/{slug} */
    public function show(string $slug)
    {
        $salle = Salle::with(['ville', 'coaches.user'])
            ->where('slug', $slug)
            ->where('active', true)
            ->firstOrFail();

        return response()->json($salle);
    }

    /** GET /api/villes */
    public function villes()
    {
        return response()->json(Ville::orderBy('nom')->get());
    }
}

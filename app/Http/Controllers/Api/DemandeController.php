<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use Illuminate\Http\Request;

class DemandeController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'prenom'    => 'required|string|max:100',
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|max:150',
            'telephone' => 'nullable|string|max:20',
            'ville'     => 'nullable|string|max:100',
            'formule'   => 'nullable|string|max:100',
        ]);

        $demande = Demande::create($data);

        return response()->json([
            'message' => 'Demande envoyée avec succès.',
            'id'      => $demande->id,
        ], 201);
    }
}

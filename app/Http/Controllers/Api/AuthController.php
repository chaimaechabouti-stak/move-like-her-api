<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /** POST /api/register */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:100',
            'prenom'         => 'nullable|string|max:100',
            'email'          => 'required|email|unique:users',
            'telephone'      => 'nullable|string|max:20',
            'date_naissance' => 'nullable|date|before:today',
            'password'       => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'           => $data['name'],
            'prenom'         => $data['prenom'] ?? null,
            'email'          => $data['email'],
            'telephone'      => $data['telephone'] ?? null,
            'date_naissance' => $data['date_naissance'] ?? null,
            'role'           => 'membre',
            'password'       => Hash::make($data['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ], 201);
    }

    /** POST /api/login */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (!auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            throw ValidationException::withMessages([
                'email' => ['Identifiants incorrects.'],
            ]);
        }

        $user = User::where('email', $request->email)->first();

        // Révoquer les anciens tokens
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ]);
    }

    /** POST /api/logout */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Déconnectée avec succès.']);
    }

    /** GET /api/me */
    public function me(Request $request)
    {
        $user = $request->user()->load('inscriptionActive.abonnement');
        return response()->json($user);
    }
}

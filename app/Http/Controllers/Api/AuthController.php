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

    /** PUT /api/profil */
    public function updateProfil(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'prenom'    => 'sometimes|string|max:100',
            'name'      => 'sometimes|string|max:100',
            'email'     => 'sometimes|email|unique:users,email,' . $user->id,
            'telephone' => 'sometimes|nullable|string|max:20',
            'current_password'      => 'required_with:password',
            'password'              => 'sometimes|min:8|confirmed',
            'password_confirmation' => 'sometimes',
        ]);

        if (isset($data['current_password'])) {
            if (!\Hash::check($data['current_password'], $user->password)) {
                return response()->json(['message' => 'Mot de passe actuel incorrect.'], 422);
            }
            $user->password = \Hash::make($data['password']);
        }

        if (isset($data['prenom']))    $user->prenom    = $data['prenom'];
        if (isset($data['name']))      $user->name      = $data['name'];
        if (isset($data['email']))     $user->email     = $data['email'];
        if (isset($data['telephone'])) $user->telephone = $data['telephone'];

        $user->save();

        return response()->json($user);
    }
}

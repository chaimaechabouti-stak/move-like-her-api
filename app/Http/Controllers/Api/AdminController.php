<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Abonnement;
use App\Models\Coach;
use App\Models\Contact;
use App\Models\Cours;
use App\Models\Inscription;
use App\Models\Salle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /* ════════════════════════════════
       DASHBOARD STATS
    ════════════════════════════════ */

    public function stats()
    {
        // Inscriptions par mois (12 derniers mois)
        $inscriptionsParMois = Inscription::selectRaw('MONTH(created_at) as mois, YEAR(created_at) as annee, COUNT(*) as total, SUM(montant_paye) as revenus')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('annee', 'mois')
            ->orderBy('annee')->orderBy('mois')
            ->get();

        // Répartition par abonnement
        $repartitionAbos = Inscription::selectRaw('abonnement_id, COUNT(*) as total')
            ->where('statut', 'active')
            ->with('abonnement:id,nom')
            ->groupBy('abonnement_id')
            ->get()
            ->map(fn($i) => [
                'nom'   => $i->abonnement?->nom ?? 'Inconnu',
                'total' => $i->total,
            ]);

        // Nouveaux membres par mois
        $membresParMois = User::selectRaw('MONTH(created_at) as mois, YEAR(created_at) as annee, COUNT(*) as total')
            ->where('role', 'membre')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('annee', 'mois')
            ->orderBy('annee')->orderBy('mois')
            ->get();

        return response()->json([
            'users'               => User::where('role', 'membre')->count(),
            'coaches'             => Coach::where('active', true)->count(),
            'cours'               => Cours::where('actif', true)->count(),
            'inscriptions'        => Inscription::where('statut', 'active')->count(),
            'salles'              => Salle::where('active', true)->count(),
            'abonnements'         => Abonnement::where('actif', true)->count(),
            'revenus'             => Inscription::where('statut', 'active')->sum('montant_paye'),
            'inscriptions_mois'   => $inscriptionsParMois,
            'repartition_abos'    => $repartitionAbos,
            'membres_mois'        => $membresParMois,
        ]);
    }

    /* ════════════════════════════════
       UTILISATEURS
    ════════════════════════════════ */

    public function users(Request $request)
    {
        $query = User::with('inscriptionActive.abonnement')->orderByDesc('created_at');

        if ($request->filled('role'))   $query->where('role', $request->role);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q
                ->where('name',   'like', "%$s%")
                ->orWhere('prenom', 'like', "%$s%")
                ->orWhere('email',  'like', "%$s%")
            );
        }

        return response()->json($query->paginate(15));
    }

    public function showUser(int $id)
    {
        return response()->json(
            User::with(['inscriptionActive.abonnement', 'coach'])->findOrFail($id)
        );
    }

    public function updateUser(Request $request, int $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validate([
            'name'      => 'sometimes|string|max:100',
            'prenom'    => 'sometimes|string|max:100',
            'email'     => "sometimes|email|unique:users,email,$id",
            'telephone' => 'sometimes|nullable|string|max:20',
            'role'      => 'sometimes|in:membre,coach,admin',
        ]);
        $user->update($data);
        return response()->json($user->fresh());
    }

    public function deleteUser(int $id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'admin') {
            return response()->json(['message' => 'Impossible de supprimer un admin.'], 403);
        }
        $user->delete();
        return response()->json(['message' => 'Utilisateur supprimé.']);
    }

    /* ════════════════════════════════
       COACHES
    ════════════════════════════════ */

    public function coaches(Request $request)
    {
        $query = Coach::with(['user', 'salle.ville'])->orderByDesc('created_at');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('user', fn($q) => $q
                ->where('name',   'like', "%$s%")
                ->orWhere('prenom', 'like', "%$s%")
                ->orWhere('email',  'like', "%$s%")
            );
        }

        return response()->json($query->paginate(15));
    }

    public function createCoach(Request $request)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:100',
            'prenom'            => 'required|string|max:100',
            'email'             => 'required|email|unique:users',
            'password'          => 'required|string|min:6',
            'specialite'        => 'required|string|max:150',
            'salle_id'          => 'nullable|exists:salles,id',
            'bio'               => 'nullable|string',
            'experience_annees' => 'nullable|integer|min:0|max:50',
            'certifications'    => 'nullable|array',
            'cours_dispenses'   => 'nullable|array',
            'photo_url'         => 'nullable|string',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'prenom'   => $data['prenom'],
            'email'    => $data['email'],
            'role'     => 'coach',
            'password' => Hash::make($data['password']),
        ]);

        $coach = Coach::create([
            'user_id'           => $user->id,
            'salle_id'          => $data['salle_id'] ?? null,
            'specialite'        => $data['specialite'],
            'bio'               => $data['bio'] ?? null,
            'experience_annees' => $data['experience_annees'] ?? 0,
            'certifications'    => $data['certifications'] ?? [],
            'cours_dispenses'   => $data['cours_dispenses'] ?? [],
            'photo_url'         => $data['photo_url'] ?? null,
        ]);

        return response()->json($coach->load('user', 'salle.ville'), 201);
    }

    public function updateCoach(Request $request, int $id)
    {
        $coach = Coach::with('user')->findOrFail($id);
        $data = $request->validate([
            'name'              => 'sometimes|string|max:100',
            'prenom'            => 'sometimes|string|max:100',
            'specialite'        => 'sometimes|string|max:150',
            'salle_id'          => 'sometimes|nullable|exists:salles,id',
            'bio'               => 'sometimes|nullable|string',
            'experience_annees' => 'sometimes|integer|min:0|max:50',
            'certifications'    => 'sometimes|array',
            'cours_dispenses'   => 'sometimes|array',
            'photo_url'         => 'sometimes|nullable|string',
            'active'            => 'sometimes|boolean',
        ]);

        if (isset($data['name']))   $coach->user->update(['name'   => $data['name']]);
        if (isset($data['prenom'])) $coach->user->update(['prenom' => $data['prenom']]);

        $coach->update(collect($data)->except(['name', 'prenom'])->toArray());

        return response()->json($coach->fresh()->load('user', 'salle.ville'));
    }

    public function deleteCoach(int $id)
    {
        $coach = Coach::findOrFail($id);
        $coach->user->delete();
        return response()->json(['message' => 'Coach supprimé.']);
    }

    /* ════════════════════════════════
       COURS
    ════════════════════════════════ */

    public function listCours(Request $request)
    {
        $query = Cours::orderBy('nom');
        if ($request->filled('search'))
            $query->where('nom', 'like', '%' . $request->search . '%');
        return response()->json($query->get());
    }

    public function createCours(Request $request)
    {
        $data = $request->validate([
            'nom'         => 'required|string|max:100',
            'description' => 'nullable|string',
            'duree'       => 'required|string|max:20',
            'niveau'      => 'required|in:Tous niveaux,Débutant,Intermédiaire,Avancé',
            'calories'    => 'nullable|string|max:30',
            'image_url'   => 'nullable|string',
            'couleur'     => 'nullable|string|max:20',
        ]);
        $data['slug'] = Str::slug($data['nom']) . '-' . Str::random(4);
        return response()->json(Cours::create($data), 201);
    }

    public function updateCours(Request $request, int $id)
    {
        $cours = Cours::findOrFail($id);
        $data = $request->validate([
            'nom'         => 'sometimes|string|max:100',
            'description' => 'sometimes|nullable|string',
            'duree'       => 'sometimes|string|max:20',
            'niveau'      => 'sometimes|in:Tous niveaux,Débutant,Intermédiaire,Avancé',
            'calories'    => 'sometimes|nullable|string|max:30',
            'image_url'   => 'sometimes|nullable|string',
            'couleur'     => 'sometimes|nullable|string|max:20',
            'actif'       => 'sometimes|boolean',
        ]);
        $cours->update($data);
        return response()->json($cours->fresh());
    }

    public function deleteCours(int $id)
    {
        Cours::findOrFail($id)->update(['actif' => false]);
        return response()->json(['message' => 'Cours désactivé.']);
    }

    /* ════════════════════════════════
       SALLES
    ════════════════════════════════ */

    public function listSalles(Request $request)
    {
        $query = Salle::with('ville')->orderBy('nom');
        if ($request->filled('search'))
            $query->where('nom', 'like', '%' . $request->search . '%');
        return response()->json($query->paginate(15));
    }

    public function createSalle(Request $request)
    {
        $data = $request->validate([
            'nom'         => 'required|string|max:100',
            'ville_id'    => 'required|exists:villes,id',
            'adresse'     => 'nullable|string|max:255',
            'telephone'   => 'nullable|string|max:20',
            'email'       => 'nullable|email|max:100',
            'description' => 'nullable|string',
            'image_url'   => 'nullable|string',
            'horaires'    => 'nullable|string',
            'active'      => 'sometimes|boolean',
        ]);
        $data['slug'] = Str::slug($data['nom']) . '-' . Str::random(4);
        return response()->json(Salle::create($data)->load('ville'), 201);
    }

    public function updateSalle(Request $request, int $id)
    {
        $salle = Salle::findOrFail($id);
        $data = $request->validate([
            'nom'         => 'sometimes|string|max:100',
            'ville_id'    => 'sometimes|exists:villes,id',
            'adresse'     => 'sometimes|nullable|string|max:255',
            'telephone'   => 'sometimes|nullable|string|max:20',
            'email'       => 'sometimes|nullable|email|max:100',
            'description' => 'sometimes|nullable|string',
            'image_url'   => 'sometimes|nullable|string',
            'horaires'    => 'sometimes|nullable|string',
            'active'      => 'sometimes|boolean',
        ]);
        $salle->update($data);
        return response()->json($salle->fresh()->load('ville'));
    }

    public function deleteSalle(int $id)
    {
        Salle::findOrFail($id)->update(['active' => false]);
        return response()->json(['message' => 'Salle désactivée.']);
    }

    /* ════════════════════════════════
       ABONNEMENTS
    ════════════════════════════════ */

    public function listAbonnements(Request $request)
    {
        return response()->json(Abonnement::orderBy('ordre')->get());
    }

    public function createAbonnement(Request $request)
    {
        $data = $request->validate([
            'nom'             => 'required|string|max:100',
            'prix_mensuel'    => 'required|numeric|min:0',
            'prix_annuel'     => 'nullable|numeric|min:0',
            'fonctionnalites' => 'nullable|array',
            'populaire'       => 'sometimes|boolean',
            'couleur'         => 'nullable|string|max:20',
            'cta_texte'       => 'nullable|string|max:50',
            'ordre'           => 'sometimes|integer|min:0',
            'actif'           => 'sometimes|boolean',
        ]);
        $data['slug'] = Str::slug($data['nom']) . '-' . Str::random(4);
        return response()->json(Abonnement::create($data), 201);
    }

    public function updateAbonnement(Request $request, int $id)
    {
        $abo = Abonnement::findOrFail($id);
        $data = $request->validate([
            'nom'             => 'sometimes|string|max:100',
            'prix_mensuel'    => 'sometimes|numeric|min:0',
            'prix_annuel'     => 'sometimes|nullable|numeric|min:0',
            'fonctionnalites' => 'sometimes|array',
            'populaire'       => 'sometimes|boolean',
            'couleur'         => 'sometimes|nullable|string|max:20',
            'cta_texte'       => 'sometimes|nullable|string|max:50',
            'ordre'           => 'sometimes|integer|min:0',
            'actif'           => 'sometimes|boolean',
        ]);
        $abo->update($data);
        return response()->json($abo->fresh());
    }

    public function deleteAbonnement(int $id)
    {
        Abonnement::findOrFail($id)->update(['actif' => false]);
        return response()->json(['message' => 'Abonnement désactivé.']);
    }

    /* ════════════════════════════════
       INSCRIPTIONS
    ════════════════════════════════ */

    public function inscriptions(Request $request)
    {
        $query = Inscription::with(['user', 'abonnement', 'salle.ville'])
            ->orderByDesc('created_at');

        if ($request->filled('statut'))
            $query->where('statut', $request->statut);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('user', fn($q) => $q
                ->where('name',    'like', "%$s%")
                ->orWhere('prenom', 'like', "%$s%")
                ->orWhere('email',  'like', "%$s%")
            );
        }

        return response()->json($query->paginate(20));
    }

    public function contacts()
    {
        return response()->json(
            Contact::orderByDesc('created_at')->get()
        );
    }

    public function updateContact(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);
        $data = $request->validate([
            'statut' => 'required|in:nouveau,lu,traite',
        ]);
        $contact->update($data);
        return response()->json($contact);
    }

    public function deleteContact($id)
    {
        Contact::findOrFail($id)->delete();
        return response()->json(['message' => 'Supprimé']);
    }
}

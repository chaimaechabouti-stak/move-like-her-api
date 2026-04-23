<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    protected $fillable = [
        'user_id', 'abonnement_id', 'salle_id',
        'frequence', 'montant_paye', 'statut',
        'date_debut', 'date_fin', 'notes',
        'stripe_session_id', 'stripe_payment_intent',
    ];

    protected function casts(): array
    {
        return [
            'date_debut' => 'date',
            'date_fin'   => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function abonnement()
    {
        return $this->belongsTo(Abonnement::class);
    }

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }
}

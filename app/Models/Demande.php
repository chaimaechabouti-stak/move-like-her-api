<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    protected $fillable = [
        'prenom', 'name', 'email', 'telephone',
        'ville', 'formule', 'statut',
    ];
}

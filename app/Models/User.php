<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'prenom', 'email', 'telephone',
        'date_naissance', 'role', 'password',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'date_naissance'    => 'date',
        ];
    }

    public function coach()
    {
        return $this->hasOne(Coach::class);
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function inscriptionActive()
    {
        return $this->hasOne(Inscription::class)
            ->where('statut', 'active')
            ->latest();
    }

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isCoach(): bool { return $this->role === 'coach'; }
}

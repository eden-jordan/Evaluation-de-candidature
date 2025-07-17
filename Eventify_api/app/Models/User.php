<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'role',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'id_utilisateur');
    }
}

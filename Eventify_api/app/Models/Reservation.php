<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'numero',
        'id_utilisateur',
        'id_evenement',
    ];

    public function utilisateurs()
    {
        return $this->belongsTo(User::class, 'id_utilisateur');
    }

    public function evenements()
    {
        return $this->belongsTo(Evenement::class, 'id_evenement');
    }
}

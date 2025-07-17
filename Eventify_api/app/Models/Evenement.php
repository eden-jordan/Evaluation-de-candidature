<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    protected $fillable = [
        'titre',
        'description',
        'date',
        'heure',
        'lieu',
        'id_categorie',
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'id_categorie');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'id_evenement');
    }
}

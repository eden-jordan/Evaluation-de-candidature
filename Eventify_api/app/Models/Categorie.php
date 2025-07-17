<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $fillable = [
        'libelle',
    ];

    public function evenements()
    {
        return $this->hasMany(Evenement::class, 'id_categorie');
    }
}

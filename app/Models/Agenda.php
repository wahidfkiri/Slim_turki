<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'date_debut',
        'heure_debut',
        'date_fin',
        'heure_fin',
        'all_day',
        'dossier_id',
        'intervenant_id',
        'utilisateur_id',
        'categorie',
        'couleur'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'all_day' => 'boolean',
    ];

    public function dossier()
    {
        return $this->belongsTo(Dossier::class);
    }

    public function intervenant()
    {
        return $this->belongsTo(Intervenant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'date_debut',
        'date_fin',
        'priorite',
        'statut',
        'dossier_id',
        'intervenant_id',
        'utilisateur_id',
        'note'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
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
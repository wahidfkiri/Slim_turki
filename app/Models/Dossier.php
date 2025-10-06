<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dossier extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_dossier',
        'nom_dossier',
        'objet',
        'date_entree',
        'domaine_id',
        'sous_domaine_id',
        'conseil',
        'contentieux',
        'numero_role',
        'chambre',
        'numero_chambre',
        'numero_parquet',
        'numero_instruction',
        'numero_plainte',
        'archive',
        'note',
        'date_archive',
        'boite_archive'
    ];

    protected $casts = [
        'conseil' => 'boolean',
        'contentieux' => 'boolean',
        'archive' => 'boolean',
        'date_entree' => 'datetime',
        'date_archive' => 'date',
    ];

    public function domaine()
    {
        return $this->belongsTo(Domaine::class);
    }

    public function sousDomaine()
    {
        return $this->belongsTo(SousDomaine::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'dossier_user')
                    ->withPivot('ordre', 'role')
                    ->withTimestamps();
    }

    public function intervenants()
    {
        return $this->belongsToMany(Intervenant::class, 'dossier_intervenant')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function dossiersLies()
    {
        return $this->belongsToMany(Dossier::class, 'dossier_dossier', 
                    'dossier_id', 'dossier_lie_id')
                    ->withPivot('relation')
                    ->withTimestamps();
    }

    public function timeSheets()
    {
        return $this->hasMany(TimeSheet::class);
    }

    public function agendas()
    {
        return $this->hasMany(Agenda::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function factures()
    {
        return $this->hasMany(Facture::class);
    }

    public function fichiers()
    {
        return $this->hasMany(Fichier::class, 'module_id');
    }
}
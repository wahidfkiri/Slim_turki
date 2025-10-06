<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_timesheet',
        'utilisateur_id',
        'dossier_id',
        'description',
        'categorie',
        'type',
        'quantite',
        'prix',
        'total'
    ];

    protected $casts = [
        'date_timesheet' => 'datetime',
        'quantite' => 'decimal:2',
        'prix' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function dossier()
    {
        return $this->belongsTo(Dossier::class);
    }

    public function categorieRelation()
    {
        return $this->belongsTo(Categorie::class, 'categorie');
    }

    public function typeRelation()
    {
        return $this->belongsTo(Type::class, 'type');
    }
}
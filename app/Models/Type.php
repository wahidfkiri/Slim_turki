<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $fillable = ['nom'];

    public function timeSheets()
    {
        return $this->hasMany(TimeSheet::class, 'type');
    }
}
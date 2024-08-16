<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    public $timestamps = true;

    protected $table = 'cargos';

    protected $fillable = [
        'id',
        'nome',
    ];

    public function candidato()
    {
        return $this->hasMany(Candidato::class);
    }
}

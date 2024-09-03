<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidato extends Model
{
    protected $table = 'candidatos';

    protected $fillable = [
        'id',
        'nome',
        'cargo_id',
        'partido_id',
    ];

    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    public function votos()
    {
        return $this->hasMany(Voto::class);
    }

    use HasFactory;
}

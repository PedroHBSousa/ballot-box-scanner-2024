<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voto extends Model
{
    use HasFactory;

    protected $table = 'votos';

    protected $fillable = [
        'id',
        'cargo_id',
        'boletim_id',
        'candidato_id',
        'secao_id',
        'nominal',
        'nulo',
        'branco'
    ];

    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    public function boletim()
    {
        return $this->belongsTo(Boletim::class);
    }

    public function candidato()
    {
        return $this->belongsTo(Candidato::class);
    }

    public function secao()
    {
        return $this->belongsTo(Secao::class);
    }

}
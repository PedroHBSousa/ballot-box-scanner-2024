<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bairro extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'bairros';

    protected $fillable = [
        'id',
        'nome',
        'regiao'
    ];

    public function localidades()
    {
        return $this->hasMany(Localidade::class);
    }

}

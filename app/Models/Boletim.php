<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boletim extends Model
{
    use HasFactory;

    protected $table = 'boletins';

    protected $fillable = [
        'id',
        'secao_id',
        'aptos',
        'assinatura_digital'
    ];

    public function secao() 
    {
        return $this->hasOne(Secao::class);
    }

    public function votos()
    {
        return $this->hasMany(Voto::class);
    }

}
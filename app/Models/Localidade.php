<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Localidade extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'localidades';

    protected $fillable = [
        'id',
        'bairro_id',
        'nome'
    ];

    public function bairro()
    {
        return $this->belongsTo(Bairro::class);
    }

    public function secoes()
    {
        return $this->hasMany(Secao::class);
    }
}

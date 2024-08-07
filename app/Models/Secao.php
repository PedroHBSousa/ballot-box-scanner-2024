<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secao extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'secoes';

    protected $fillable = [
        'id',
        'localidade_id'
    ];

    public function localidade()
    {
        return $this->hasOne(Localidade::class);
    }

}

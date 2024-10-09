<?php

namespace App\Http\Controllers;

use App\Models\Secao;
use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    public function relatorio()
    {
        // Consultando os votos dos candidatos 10 e 11 agrupados por secao e localidade
        $resultados = Secao::with(['localidade', 'votos' => function($query) {
            $query->whereIn('candidato_id', [10, 11]);
        }, 'boletins']) // Mantendo o relacionamento com boletins
        ->get()
        ->groupBy('localidade_id');

        return view('relatorios', compact('resultados'));
    }
}

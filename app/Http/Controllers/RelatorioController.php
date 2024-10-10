<?php

namespace App\Http\Controllers;

use App\Models\Secao;
use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    public function relatorio()
    {
          // Consultando os votos dos candidatos 10 e 11 agrupados por localidade e seção
    $resultados = Secao::with(['localidade', 'votos' => function($query) {
        $query->whereIn('candidato_id', [10, 11]);
    }, 'boletins'])
    ->get()
    ->groupBy('localidade_id');

    // Estrutura para armazenar o total de votos por candidato em cada localidade
    $localidades = [];

    foreach ($resultados as $localidadeId => $secoes) {
        $totalVotosReinaldinho = 0;
        $totalVotosGleivison = 0;
        $totalVotos = 0;

        foreach ($secoes as $secao) {
            $boletim = $secao->boletins->first();
            $totalVotos += $boletim ? $boletim->comp : 0;

            $totalVotosReinaldinho += $secao->votos->where('candidato_id', 10)->count();
            $totalVotosGleivison += $secao->votos->where('candidato_id', 11)->count();
        }

        $localidades[$localidadeId] = [
            'nome' => $secoes->first()->localidade->nome,
            'regiao' => $secoes->first()->localidade->regiao,
            'totalVotos' => $totalVotos,
            'votosReinaldinho' => $totalVotosReinaldinho,
            'votosGleivison' => $totalVotosGleivison,
            'secoes' => $secoes
        ];
    }

    return view('relatorios', compact('localidades'));
    }
}

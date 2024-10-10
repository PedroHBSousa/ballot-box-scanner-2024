<?php

namespace App\Http\Controllers;

use App\Models\Candidato;
use App\Models\Secao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class RelatorioController extends Controller
{
    public function relatorio()
    {
        // Consultando os votos dos candidatos 10 e 11 agrupados por localidade e seção
        $resultados = Secao::with(['localidade', 'votos' => function ($query) {
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

    public function relatorioVereador()
    {
        return view('relatorios-vereador');
    }

    public function getVereador(Request $request)
    {
        $search = $request->query('search');

        if (!$search) {
            return response()->json(['error' => 'Por favor, insira o nome ou número do vereador.'], 400);
        }

        // Verifica se a busca é um número (ID do vereador)
        if (is_numeric($search)) {
            // Busca o vereador pelo ID
            $vereador = Candidato::where('id', $search)->first();

            if (!$vereador) {
                return response()->json(['error' => 'Candidato não encontrado.'], 404);
            }

            // Busca a quantidade de votos do vereador
            $quantidadeVotos = DB::table('votos')->where('candidato_id', $vereador->id)->count();

            // Busca as seções onde o vereador recebeu votos
            $secoes = Secao::whereHas('votos', function ($query) use ($vereador) {
                $query->where('candidato_id', $vereador->id);
            })
                ->with('localidade')
                ->withCount(['votos as votos_na_secao' => function ($query) use ($vereador) {
                    $query->where('candidato_id', $vereador->id);
                }])
                ->get();

            return response()->json([
                'vereador' => [
                    'nome' => $vereador->nome,
                    'id' => $vereador->id,
                    'partido' => $vereador->partido,
                    'quantidade_votos' => $quantidadeVotos
                ],
                'secoes' => $secoes
            ]);
        } else {

            // Busca pelo nome do vereador
            $vereadores = Candidato::where('nome', 'LIKE', '%' . $search . '%')->get();

            if ($vereadores->isEmpty()) {
                return response()->json(['error' => 'Candidato não encontrado.'], 404);
            }

            return response()->json(['vereadores' => $vereadores]);
        }
    }
}

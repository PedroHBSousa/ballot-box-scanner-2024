<?php

namespace App\Http\Controllers;

use App\Models\Bairro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DataController extends Controller
{
    public function getData($filter, Request $request)
    {
        try {
            // Exemplo de lógica para retornar dados com base no filtro
            $data = [];

            if ($filter === 'bairros') {
                // Obtém todos os bairros
                $data = Bairro::all();
                return response()->json([
                    'success' => true,
                    'bairros' => $data,
                ]);
            }


            switch ($filter) {
                case 'prefeitos':
                    $data = DB::table('votos')
                        ->select('candidatos.nome', DB::raw('count(*) as total'))
                        ->join('candidatos', 'votos.candidato_id', '=', 'candidatos.id')
                        ->where('candidatos.cargo_id', 11) // Cargo para prefeitos
                        ->groupBy('candidatos.nome')
                        ->get();
                    break;

                case 'vereadores':
                    $data = DB::table('votos')
                        ->select('candidatos.nome', DB::raw('count(*) as total'))
                        ->join('candidatos', 'votos.candidato_id', '=', 'candidatos.id')
                        ->where('candidatos.cargo_id', 13) // Cargo para vereadores.
                        ->groupBy('candidatos.nome')
                        ->orderBy('total', 'desc')
                        ->limit(10)
                        ->get();
                    break;

                default:
                    // Retorna erro se o filtro não for reconhecido
                    return response()->json(['error' => 'Filtro inválido'], 400);
            }

            return response()->json($data);
        } catch (\Exception $e) {
            // Registra o erro no log e retorna uma resposta de erro
            // \log::error('Erro ao buscar dados do gráfico: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar dados do gráfico'], 500);
        }
    }

    public function getDadosBairro($bairro_id)
    {
        try {
            // Verifica se o bairro_id foi fornecido
            if (!$bairro_id) {
                return response()->json(['error' => 'Bairro ID não fornecido'], 400);
            }

            $data = DB::table('votos')
                ->select('candidatos.nome', DB::raw('count(*) as total'))
                ->join('candidatos', 'votos.candidato_id', '=', 'candidatos.id')
                ->join('secoes', 'votos.secao_id', '=', 'secoes.id')
                ->join('localidades', 'secoes.localidade_id', '=', 'localidades.id')
                ->join('bairros', 'localidades.bairro_id', '=', 'bairros.id')
                ->where('bairros.id', $bairro_id)
                ->where('candidatos.cargo_id', 11) // Cargo para prefeitos
                ->groupBy('candidatos.nome')
                ->get();

            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar dados do bairro: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar dados do bairro'], 500);
        }
    }

    public function getBairros()
    {

        try {
            // Obtém todos os bairros e seleciona apenas o 'nome'
            $bairros = Bairro::select('id', 'nome')->get();
            return response()->json($bairros);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar bairros: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar bairros'], 500);
        }
    }
}

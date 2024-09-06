<?php

namespace App\Http\Controllers;

use App\Models\Bairro;
use App\Models\Localidade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DataController extends Controller
{
    public function getData($filter)
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
            if ($filter === 'localidades') {
                // Obtém todas as localidades
                $data = Localidade::all();
                return response()->json([
                    'success' => true,
                    'escolas' => $data,
                ]);
            }
            if ($filter === 'regioes') {
                // Obtem todas as regiões distintas na tabela Bairros
                $data = Bairro::select('regiao')->distinct()->pluck('regiao');
                return response()->json([
                    'success' => true,
                    'regioes' => $data,
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

                case 'partidos':
                    $data = DB::table('votos')
                        ->join('candidatos', 'votos.candidato_id', '=', 'candidatos.id')
                        ->select('candidatos.partido', DB::raw('COUNT(votos.id) as total'))
                        ->groupBy('candidatos.partido')
                        ->orderBy('total', 'desc')
                        ->limit(15)
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

    public function getDadosEscola($localidade_id)
    {
        try {
            // Verifica se o escola_id foi fornecido
            if (!is_numeric($localidade_id) || !$localidade_id) {
                return response()->json(['error' => 'Localidade ID inválido'], 400);
            }

            $data = DB::table('votos')
                ->select('candidatos.nome', DB::raw('count(*) as total'))
                ->join('candidatos', 'votos.candidato_id', '=', 'candidatos.id')
                ->join('secoes', 'votos.secao_id', '=', 'secoes.id')
                ->join('localidades', 'secoes.localidade_id', '=', 'localidades.id')
                ->where('localidades.id', $localidade_id)
                ->where('candidatos.cargo_id', 11) // Cargo para prefeitos
                ->groupBy('candidatos.nome')
                ->get();

            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar dados da escola: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar dados da escola'], 500);
        }
    }

    public function getLocalidades()
    {
        try {
            $localidades = Localidade::select('id', 'nome')->get();
            return response()->json($localidades);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar localidades: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar localidades'], 500);
        }
    }

    public function getDadosRegiao($regiao)
    {
        try {
            // Verifica se a região foi fornecida
            if (!$regiao) {
                return response()->json(['error' => 'Região não fornecida'], 400);
            }

            $data = DB::table('votos')
                ->select('candidatos.nome', DB::raw('count(*) as total'))
                ->join('candidatos', 'votos.candidato_id', '=', 'candidatos.id')
                ->join('secoes', 'votos.secao_id', '=', 'secoes.id')
                ->join('localidades', 'secoes.localidade_id', '=', 'localidades.id')
                ->join('bairros', 'localidades.bairro_id', '=', 'bairros.id')
                ->where('bairros.regiao', $regiao)
                ->where('candidatos.cargo_id', 11) // Cargo para prefeitos
                ->groupBy('candidatos.nome')
                ->get();

            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar dados da região: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar dados da região'], 500);
        }
    }

    public function getRegioes()
    {
        try {
            $regiao = DB::table('bairros')->distinct()->pluck('regiao');
            return response()->json($regiao);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar regiões: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar regiões'], 500);
        }
    }
}

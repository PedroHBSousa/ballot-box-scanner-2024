<?php

namespace App\Http\Controllers;

use App\Models\Bairro;
use App\Models\Candidato;
use App\Models\Localidade;
use App\Models\Voto;
use App\Models\Secao;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function dashboard()
    {
        return view('dashboard');
    }

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
                $data = Localidade::select('regiao')->distinct()->pluck('regiao');

                return response()->json([
                    'success' => true,
                    'regioes' => $data,
                ]);
            }


            switch ($filter) {
                case 'geral':
                    $prefeitosData = DB::table('votos')
                        ->select('candidatos.nome', DB::raw('count(*) as total'))
                        ->join('candidatos', 'votos.candidato_id', '=', 'candidatos.id')
                        ->where('candidatos.cargo_id', 11) // Cargo para prefeitos
                        ->groupBy('candidatos.nome')
                        ->get();

                    $vereadoresData = DB::table('votos')
                        ->select('candidatos.nome', DB::raw('count(*) as total'))
                        ->join('candidatos', 'votos.candidato_id', '=', 'candidatos.id')
                        ->where('candidatos.cargo_id', 13) // Cargo para vereadores
                        ->groupBy('candidatos.nome')
                        ->orderBy('total', 'desc')
                        ->limit(10)
                        ->get();

                    $data = [
                        'prefeitos' => $prefeitosData,
                        'vereadores' => $vereadoresData,
                    ];
                    break;

                case 'prefeitos':
                    $data = DB::table('votos')
                        ->select('candidatos.nome', DB::raw('count(*) as total'))
                        ->join('candidatos', 'votos.candidato_id', '=', 'candidatos.id')
                        ->where('candidatos.cargo_id', 11, 13) // Cargo para prefeitos
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

            // Busca os dados para prefeitos
            $prefeitosData = DB::table('votos')
                ->select('candidatos.nome', DB::raw('count(*) as total'))
                ->join('candidatos', 'votos.candidato_id', '=', 'candidatos.id')
                ->join('secoes', 'votos.secao_id', '=', 'secoes.id')
                ->join('localidades', 'secoes.localidade_id', '=', 'localidades.id')
                ->join('bairros', 'localidades.bairro_id', '=', 'bairros.id')
                ->where('bairros.id', $bairro_id)
                ->where('candidatos.cargo_id', 11) // Cargo para prefeitos
                ->groupBy('candidatos.nome')
                ->get();

            // Busca os dados para vereadores
            $vereadoresData = DB::table('votos')
                ->select('candidatos.nome', DB::raw('count(*) as total'))
                ->join('candidatos', 'votos.candidato_id', '=', 'candidatos.id')
                ->join('secoes', 'votos.secao_id', '=', 'secoes.id')
                ->join('localidades', 'secoes.localidade_id', '=', 'localidades.id')
                ->join('bairros', 'localidades.bairro_id', '=', 'bairros.id')
                ->where('bairros.id', $bairro_id)
                ->where('candidatos.cargo_id', 13) // Cargo para vereadores
                ->groupBy('candidatos.nome')
                ->orderBy('total', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'prefeitos' => $prefeitosData,
                'vereadores' => $vereadoresData,
            ]);
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
            // Verifica se o localidade_id foi fornecido e é válido
            if (!is_numeric($localidade_id) || !$localidade_id) {
                return response()->json(['error' => 'Localidade ID inválido'], 400);
            }

            // Busca os votos para prefeitos na localidade
            $dadosPrefeitos = DB::table('votos')
                ->select('candidatos.nome', DB::raw('count(*) as total'))
                ->join('candidatos', 'votos.candidato_id', '=', 'candidatos.id')
                ->join('secoes', 'votos.secao_id', '=', 'secoes.id')
                ->join('localidades', 'secoes.localidade_id', '=', 'localidades.id')
                ->where('localidades.id', $localidade_id)
                ->where('candidatos.cargo_id', 11) // Cargo para prefeitos
                ->groupBy('candidatos.nome')
                ->get();

            // Busca os votos para vereadores na localidade
            $dadosVereadores = DB::table('votos')
                ->select('candidatos.nome', DB::raw('count(*) as total'))
                ->join('candidatos', 'votos.candidato_id', '=', 'candidatos.id')
                ->join('secoes', 'votos.secao_id', '=', 'secoes.id')
                ->join('localidades', 'secoes.localidade_id', '=', 'localidades.id')
                ->where('localidades.id', $localidade_id)
                ->where('candidatos.cargo_id', 13) // Cargo para vereadores
                ->groupBy('candidatos.nome')
                ->orderBy('total', 'desc')
                ->limit(10)
                ->get();

            // Retorna ambos os conjuntos de dados
            return response()->json([
                'prefeitos' => $dadosPrefeitos,
                'vereadores' => $dadosVereadores
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar dados da localidade: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar dados da localidade'], 500);
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
            // Encontrar todas as localidades pela região
            $localidades = Localidade::where('regiao', $regiao)->pluck('id'); // Obter todos os IDs das localidades

            if ($localidades->isEmpty()) {
                return response()->json(['error' => 'Região não encontrada.'], 404);
            }

            // Encontrar os candidatos com cargo "11" (prefeitos)
            $dadosPrefeitos = Voto::select('candidatos.nome', DB::raw('count(*) as total'))
                ->join('candidatos', 'votos.candidato_id', '=', 'candidatos.id')
                ->join('secoes', 'votos.secao_id', '=', 'secoes.id')
                ->whereIn('secoes.localidade_id', $localidades)
                ->where('candidatos.cargo_id', 11) // Cargo para prefeitos
                ->groupBy('candidatos.nome')
                ->get();

            // Encontrar os candidatos com cargo "13" (vereadores)
            $dadosVereadores = Voto::select('candidatos.nome', DB::raw('count(*) as total'))
                ->join('candidatos', 'votos.candidato_id', '=', 'candidatos.id')
                ->join('secoes', 'votos.secao_id', '=', 'secoes.id')
                ->whereIn('secoes.localidade_id', $localidades)
                ->where('candidatos.cargo_id', 13) // Cargo para vereadores
                ->groupBy('candidatos.nome')
                ->orderBy('total', 'desc')
                ->limit(10)
                ->get();

            // Retorna ambos os conjuntos de dados
            return response()->json([
                'prefeitos' => $dadosPrefeitos,
                'vereadores' => $dadosVereadores
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar dados da região: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar dados da região'], 500);
        }
    }

    public function getRegioes()
    {
        try {
            $regiao = DB::table('localidades')->distinct()->pluck('regiao');
            return response()->json($regiao);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar regiões: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar regiões'], 500);
        }
    }

    public function getVereador(Request $request)
    {
        $id = $request->input('search');

        if (!$id) {
            session()->flash('error', 'Por favor, insira o número do vereador.');
            return redirect()->back(); // Redireciona de volta à página anterior
        }

        $vereador = Candidato::find($id);

        $quantidadeVotos = DB::table('votos')->where('candidato_id', $id)->count();

        $secoes = Secao::whereHas('votos', function ($query) use ($id) {
            $query->where('candidato_id', $id);
        })->get();

        session()->flash('vereador', [
            'nome' => $vereador->nome,
            'id' => $vereador->id,
            'partido' => $vereador->partido,
            'quantidade_votos' => $quantidadeVotos
        ]);
        session()->flash('secoes', $secoes);

        return view('dashboard');
    }
}

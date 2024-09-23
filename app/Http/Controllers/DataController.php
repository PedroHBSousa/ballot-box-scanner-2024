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
use Illuminate\Support\Carbon;



class DataController extends Controller
{
    public function dashboard()
    {

        $totalPrevisto = DB::table('votos')
            ->where('cargo_id', 11)
            ->count();
        $nominais = DB::table('votos')
            ->where('cargo_id', 11)
            ->whereNotNull('candidato_id')
            ->count();
        $nulos = DB::table('votos')
            ->where('cargo_id', 11)
            ->where('nulo', 'sim')
            ->count();
        $brancos = DB::table('votos')
            ->where('cargo_id', 11)
            ->where('branco', 'sim')
            ->count();
        // ------------------------------------------------------------------------------------------------------
        $totalPrevistoVereador = DB::table('votos')
            ->where('cargo_id', 13)
            ->count();
        $nominaisVereador = DB::table('votos')
            ->where('cargo_id', 13)
            ->whereNotNull('candidato_id')
            ->count();
        $nulosVereador = DB::table('votos')
            ->where('cargo_id', 13)
            ->where('nulo', 'sim')
            ->count();
        $brancosVereador = DB::table('votos')
            ->where('cargo_id', 13)
            ->where('branco', 'sim')
            ->count();

        // Total de seções previstas
        $totalSecoes = 206;

        // Quantidade de boletins apurados (linhas na tabela 'boletins')
        $secoesApuradas = DB::table('boletins')->count();

        // Percentual de seções apuradas
        $percentSecoesApuradas = $secoesApuradas > 0 ? ($secoesApuradas / $totalSecoes) * 100 : 0;


        // Calcular porcentagens
        $porcentagemNominais = $totalPrevisto > 0 ? ($nominais / $totalPrevisto) * 100 : 0;
        $porcentagemNulos =  $totalPrevisto > 0 ? ($nulos / $totalPrevisto) * 100 : 0;
        $porcentagemBrancos =  $totalPrevisto > 0 ? ($brancos / $totalPrevisto) * 100 : 0;

        $porcentagemNominaisVereador = $totalPrevistoVereador > 0 ? ($nominaisVereador / $totalPrevistoVereador) * 100 : 0;
        $porcentagemNulosVereador =  $totalPrevistoVereador > 0 ? ($nulosVereador / $totalPrevistoVereador) * 100 : 0;
        $porcentagemBrancosVereador =  $totalPrevistoVereador > 0 ? ($brancosVereador / $totalPrevistoVereador) * 100 : 0;

        // -------------------------------------------------------------------------------------------------------------
        // Total de votos apurados (nominais, nulos, brancos)
        $totalApurados = DB::table('boletins')
            ->sum('comp');

        $totalFaltantes = DB::table('boletins')
            ->sum('falt');

        $totalDeVotosPrevistos = 64437;
        $restanteApurar = $totalDeVotosPrevistos - ($totalApurados + $totalFaltantes);

        $percentApurados = ($totalApurados / $totalDeVotosPrevistos) * 100;
        $percentFaltantes = ($totalFaltantes / $totalDeVotosPrevistos) * 100;
        $percentRestante = ($restanteApurar / $totalDeVotosPrevistos) * 100;


        $ultimaAtualizacao = Voto::latest()->first()->updated_at ?? Carbon::now();
        return view(
            'dashboard',
            compact(
                'nominais',
                'brancos',
                'nulos',
                'porcentagemNominais',
                'porcentagemNulos',
                'porcentagemBrancos',
                'ultimaAtualizacao',
                'nominaisVereador',
                'brancosVereador',
                'nulosVereador',
                'porcentagemNominaisVereador',
                'porcentagemNulosVereador',
                'porcentagemBrancosVereador',
                'totalApurados',
                'totalFaltantes',
                'restanteApurar',
                'percentApurados',
                'percentFaltantes',
                'percentRestante',
                'secoesApuradas',
                'percentSecoesApuradas'
            )
        );
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
                        ->limit(12)
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
                        ->limit(12)
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
                ->limit(12)
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
                ->limit(12)
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
                ->limit(12)
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
            return response()->json($regiao->toArray());
        } catch (\Exception $e) {
            Log::error('Erro ao buscar regiões: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar regiões'], 500);
        }
    }

    public function getVereador(Request $request)
    {
        $search = $request->query('search');

        if (!$search) {
            return response()->json(['error' => 'Por favor, insira o nome, número do vereador ou partido.'], 400);
        }

        // Verifica se a busca é um número (ID do vereador)
        if (is_numeric($search)) {
            // Busca o vereador pelo ID
            $vereador = Candidato::where('id', $search)->first();

            if (!$vereador) {
                return response()->json(['error' => 'Vereador não encontrado.'], 404);
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
                return response()->json(['error' => 'Vereador não encontrado.'], 404);
            }

            return response()->json(['vereadores' => $vereadores]);
        }
    }
}

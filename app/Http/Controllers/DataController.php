<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{
    public function getData($filter)
    {
        try {
            // Exemplo de lógica para retornar dados com base no filtro
            $data = [];

            switch ($filter) {
                case 'prefeitos':
                    $data = DB::table('votos')
                        ->select('candidatos.nome', DB::raw('count(*) as total'))
                        ->join('candidatos', 'votos.candidato_id', '=', 'candidatos.id')
                        ->where('candidatos.cargo_id', 11) // Cargo para prefeitos.
                        ->groupBy('candidatos.nome')
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
}

<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function getChartData($filter)
    {
        try {
            // Consultar a tabela 'candidatos' filtrando pelo cargo correspondente ao filtro selecionado
            $data = DB::table('candidatos')
                ->join('cargos', 'candidatos.cargo_id', '=', 'cargos.id')
                ->select(DB::raw('count(candidatos.id) as total'), 'candidatos.nome')
                ->where('cargos.nome', $filter)
                ->groupBy('candidatos.nome')
                ->get();

            return response()->json($data);

        } catch (\Exception $e) {
            // Logar o erro para mais detalhes
            \Log::error('Erro ao buscar dados do gráfico: ' . $e->getMessage());
    
            // Retornar uma resposta de erro
            return response()->json(['error' => 'Erro ao buscar dados do gráfico'], 500);
        }
        
    }
    
}

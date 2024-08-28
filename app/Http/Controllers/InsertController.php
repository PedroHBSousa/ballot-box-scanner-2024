<?php

namespace App\Http\Controllers;

use App\Models\Candidato;
use App\Models\Secao;
use App\Models\Voto;
use Illuminate\Http\Request;

class InsertController extends Controller
{
    public function insert()
    {
        $search = request('search');

        if($search) {
            $secoes = Secao::with('localidade')->where('id', $search)->get();
        } else {
            $secoes = collect();
        }

        // Consulta para obter candidatos
        $candidatos = Candidato::where('cargo_id', 11)
        ->select('id', 'nome')
        ->get();

        return view('insert', ['secoes' => $secoes, 'candidatos' => $candidatos]);
    }

    public function insertdata(Request $request)
    {
        // Validação (opcional, mas recomendado)
        $validated = $request->validate([
            'secao_id' => 'required|exists:secoes,id',
            'votos' => 'required|array',
            'votos.*' => 'required|integer|min:0',
        ]);

        try {
            // Capturar os dados validados
            $secaoId = $validated['secao_id'];
            $votos = $validated['votos'];

            // Inserir o número de registros especificado na tabela `votos`
            $votosData = [];
            foreach ($votos as $candidatoId => $quantidade) {
                if ($quantidade > 0) { // Verificar se a quantidade é maior que zero
                    for ($i = 0; $i < $quantidade; $i++) {
                        $votosData[] = [
                            'secao_id' => $secaoId,
                            'candidato_id' => $candidatoId,
                            'nominal' => 'sim',
                            'branco' => 'nao',
                            'nulo' => 'nao',
                            'created_at' => now(),
                            'updated_at' => now()
                    ];
                }
            }
        }

            // Inserir todos os registros de uma vez
            Voto::insert($votosData);

            return redirect()->route('insert')->with('success', 'Voto registrado com sucesso!');
        } catch (\Exception $e) {
            // Em caso de erro, redirecionar com uma mensagem de erro genérica
            return redirect()->route('insert')->with('error', 'Ocorreu um erro. Tente novamente.'); 
        }
    }
}

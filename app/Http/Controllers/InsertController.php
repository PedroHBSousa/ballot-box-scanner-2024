<?php

namespace App\Http\Controllers;

use App\Models\Boletim;
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

        return view('insert', ['secoes' => $secoes]);
    }

    public function insertdata(Request $request)
    {
        // Validação (opcional, mas recomendado)
        $validated = $request->validate([
            'secao_id' => 'required|exists:secoes,id',
            'candidato_numero' => 'required|integer',
            'num_votos' => 'required|integer|min:1',
        ]);

        try {
            // Capturar os dados validados
            $secaoId = $validated['secao_id'];
            $candidatoNumero = $validated['candidato_numero'];
            $numeroVotos = $validated['num_votos'];

            // Inserir o número de registros especificado na tabela `votos`
            $votos = [];
            for ($i = 0; $i < $numeroVotos; $i++) {
                $votos[] = [
                    'secao_id' => $secaoId,
                    'candidato_id' => $candidatoNumero,
                    'nominal' => 'sim',
                    'branco' => 'nao',
                    'nulo' => 'nao',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            // Inserir todos os registros de uma vez
            Voto::insert($votos);

            return redirect()->route('insert')->with('success', 'Voto registrado com sucesso!');
        } catch (\Exception $e) {
            // Em caso de erro, redirecionar com uma mensagem de erro genérica
            return redirect()->route('insert')->with('error', 'Ocorreu um erro. Tente novamente.'); 
        }
    }
}

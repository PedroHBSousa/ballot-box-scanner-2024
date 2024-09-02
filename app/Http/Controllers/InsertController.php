<?php

namespace App\Http\Controllers;

use App\Models\Boletim;
use App\Models\Voto;
use App\Models\Secao;
use App\Models\Candidato;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class InsertController extends Controller
{
    public function insert(Request $request)
    {
        return view('insert');
    }

    public function getSecao(Request $request)
    {
        $secoes = collect();
        $action = $request->input('action');
        $search = $request->input('search');
        $secao = Secao::where('id', $search)->with('localidade')->get()->first();
        $candidatos = Candidato::where('cargo_id', 11)->get();
        return view('insert', compact('secao', 'candidatos'));
    }

    public function getVereador($vereadorId)
    {
        $candidato = Candidato::where('cargo_id', 13)->where('id', $vereadorId)->first();
        // $candidato = Candidato::where('cargo_id', 13)->find($id);
        if ($candidato) {
            return response()->json(['success' => true, 'candidato' => $candidato]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    private function getCargoIdForCandidato($candidatoId)
    {
        if (strlen($candidatoId) == 2) {
            return 11; // Prefeito
        } elseif (strlen($candidatoId) > 2) {
            return 13; // Vereador
        }
        return null; // Caso o candidatoId não corresponda a nenhum cargo
    }

    public function insertdata(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'secao_id' => 'required|exists:secoes,id',
            'apto' => 'required|integer|min:0',
            'comp' => 'required|integer|min:0',
            'falt' => 'required|integer|min:0',
            'votos' => 'required|array',
            'votos.*.candidato_id' => 'required|exists:candidatos,id',
            'votos.*.quantidade' => 'required|integer|min:0',
            'votos_branco_prefeito' => 'required|integer|min:0',
            'votos_nulo_prefeito' => 'required|integer|min:0',
            'votos_branco_vereador' => 'required|integer|min:0',
            'votos_nulo_vereador' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            $request->session()->flash('error', 'Dados digitados incorretamente.');
            return redirect()->route('insert');
        }

        try {
            $dadosBoletim = $request->only(['secao_id', 'apto', 'comp', 'falt']);
            $votos = $request->input('votos');
            $votosBrancoPrefeito = $request->input('votos_branco_prefeito');
            $votosNuloPrefeito = $request->input('votos_nulo_prefeito');
            $votosBrancoVereador = $request->input('votos_branco_vereador');
            $votosNuloVereador = $request->input('votos_nulo_vereador');
            $boletimExistente = Boletim::where('secao_id', $dadosBoletim['secao_id'])->first();

            if ($boletimExistente) {
                return redirect()->route('insert')->with('error', 'Boletim já exite para essa seção.');
            }

            $boletim = Boletim::create([
                'secao_id' => $dadosBoletim['secao_id'],
                'apto' => $dadosBoletim['apto'],
                'comp' => $dadosBoletim['comp'],
                'falt' => $dadosBoletim['falt'],
                'assinatura_digital' => 'manual-' . time(),
            ]);

            $votosData = [];

            // Inserir votos nominais com distinção de cargos
            foreach ($votos as $voto) {
                $candidatoId = $voto['candidato_id'];
                $quantidade = $voto['quantidade'];

                if ($quantidade > 0) {
                    $cargoId = $this->getCargoIdForCandidato($candidatoId); // Função para determinar o cargo correto

                    for ($i = 0; $i < $quantidade; $i++) {
                        $votosData[] = [
                            'boletim_id' => $boletim->id,
                            'secao_id' => $dadosBoletim['secao_id'],
                            'candidato_id' => $candidatoId,
                            'cargo_id' => $cargoId,
                            'nominal' => 'sim',
                            'branco' => 'nao',
                            'nulo' => 'nao',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }

            // Inserir votos em branco e nulos para prefeito
            for ($i = 0; $i < $votosBrancoPrefeito; $i++) {
                $votosData[] = [
                    'boletim_id' => $boletim->id,
                    'secao_id' => $dadosBoletim['secao_id'],
                    'candidato_id' => null,
                    'cargo_id' => 11,
                    'nominal' => 'nao',
                    'branco' => 'sim',
                    'nulo' => 'nao',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            for ($i = 0; $i < $votosNuloPrefeito; $i++) {
                $votosData[] = [
                    'boletim_id' => $boletim->id,
                    'secao_id' => $dadosBoletim['secao_id'],
                    'candidato_id' => null,
                    'cargo_id' => 11,
                    'nominal' => 'nao',
                    'branco' => 'nao',
                    'nulo' => 'sim',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Inserir votos em branco e nulos para vereador
            for ($i = 0; $i < $votosBrancoVereador; $i++) {
                $votosData[] = [
                    'boletim_id' => $boletim->id,
                    'secao_id' => $dadosBoletim['secao_id'],
                    'candidato_id' => null,
                    'cargo_id' => 13,
                    'nominal' => 'nao',
                    'branco' => 'sim',
                    'nulo' => 'nao',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            for ($i = 0; $i < $votosNuloVereador; $i++) {
                $votosData[] = [
                    'boletim_id' => $boletim->id,
                    'secao_id' => $dadosBoletim['secao_id'],
                    'candidato_id' => null,
                    'cargo_id' => 13,
                    'nominal' => 'nao',
                    'branco' => 'nao',
                    'nulo' => 'sim',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Voto::insert($votosData);

            $request->session()->forget('votosData');
            $request->session()->forget('dadosBoletim');
            $request->session()->forget('votosBrancoPrefeito');
            $request->session()->forget('votosNuloPrefeito');
            $request->session()->forget('votosBrancoVereador');
            $request->session()->forget('votosNuloVereador');
            $request->session()->flash('success', 'Boletim e votos registrados com sucesso.');

            return redirect()->route('insert');
        } catch (QueryException $e) {
            //Erros no banco de dados
            Log::error('Erro ao inserir boletim: ' . $e->getMessage());
            return redirect()->route('insert')->with('error', 'Erro ao inserir boletim: ' . $e->getMessage());
        } catch (\Exception $e) {
            // Outros tipos de erros
            Log::error('Erro geral: ' . $e->getMessage());
            return redirect()->route('insert')->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }
}

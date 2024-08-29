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
        // Inicializa $secoes como uma coleção vazia
        $secoes = collect();

        // Recupera o ID da seção a partir do parâmetro de busca, se houver
        $search = $request->input('search');

        if ($search) {
            // Se o parâmetro de busca for fornecido, busca a seção correspondente
            $secoes = Secao::where('id', $search)->with('localidade')->get();
        }

        // Recupera todos os candidatos para o formulário de votos
        $candidatos = Candidato::where('cargo_id', 11)->get();

        // Retorna a view com as variáveis $secoes e $candidatos
        return view('insert', compact('secoes', 'candidatos'));
    }

    public function insertdata(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'secao_id' => 'required|exists:secoes,id',
            'apto' => 'required|integer|min:0',
            'comp' => 'required|integer|min:0',
            'falt' => 'required|integer|min:0',
            'votos' => 'required|array',
            'votos.*.candidato_id' => 'required|exists:candidatos,id',
            'votos.*.quantidade' => 'required|integer|min:0', // Validação para a quantidade de votos
            'votos_branco' => 'required|integer|min:0',
            'votos_nulo' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->route('insert')->withErrors($validator)->withInput();
        }

        try {
            $dadosBoletim = $request->only(['secao_id', 'apto', 'comp', 'falt']);
            $votos = $request->input('votos');
            $votosBranco = $request->input('votos_branco');
            $votosNulo = $request->input('votos_nulo');

            $boletimExistente = Boletim::where('secao_id', $dadosBoletim['secao_id'])->first();

            if ($boletimExistente) {
                return redirect()->route('insert')->with('error', 'Boletim já exite para essa seção.');
            }

            // Criar novo boletim
            $boletim = Boletim::create([
                'secao_id' => $dadosBoletim['secao_id'],
                'apto' => $dadosBoletim['apto'],
                'comp' => $dadosBoletim['comp'],
                'falt' => $dadosBoletim['falt'],
                'assinatura_digital' => 'manual-' . time(), // Definindo uma assinatura para inserção manual
            ]);

            // Inserir votos nominais
            $votosData = [];
            foreach ($votos as $voto) {
                $candidatoId = $voto['candidato_id'];
                $quantidade = $voto['quantidade'];

                if ($quantidade > 0) {
                    for ($i = 0; $i < $quantidade; $i++) {
                        $votosData[] = [
                            'boletim_id' => $boletim->id,
                            'secao_id' => $dadosBoletim['secao_id'],
                            'candidato_id' => $candidatoId,
                            'cargo_id' => 11,
                            'nominal' => 'sim',
                            'branco' => 'nao',
                            'nulo' => 'nao',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }

            for ($i = 0; $i < $votosBranco; $i++) {
                $votosData[] = [
                    'boletim_id' => $boletim->id,
                    'secao_id' => $dadosBoletim['secao_id'],
                    'candidato_id' => null, // Voto em branco não tem candidato
                    'cargo_id' => 11,
                    'nominal' => 'nao',
                    'branco' => 'sim',
                    'nulo' => 'nao',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            for ($i = 0; $i < $votosNulo; $i++) {
                $votosData[] = [
                    'boletim_id' => $boletim->id,
                    'secao_id' => $dadosBoletim['secao_id'],
                    'candidato_id' => null, // Voto nulo não tem candidato
                    'cargo_id' => 11,
                    'nominal' => 'nao',
                    'branco' => 'nao',
                    'nulo' => 'sim',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Voto::insert($votosData);

            //Sucesso
            return redirect()->route('insert')->with('success', 'Boletim e votos inseridos com sucesso.');
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

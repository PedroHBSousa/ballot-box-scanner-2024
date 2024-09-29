<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Boletim;
use App\Models\Candidato;
use Illuminate\Support\Facades\Validator;
use App\Services\ScannerService;
use Illuminate\Database\QueryException;
use App\Models\Secao;
use App\Models\Voto;
use Illuminate\Support\Facades\Log;

class ScannerController extends Controller
{
    public function qrcodescanner()
    {
        return view('qrcodescanner',);
    }

    public function store(Request $request)
    {
        // Validação inicial do input
        $validator = Validator::make($request->all(), [
            'qrcode_value' => 'required',
        ]);

        if ($validator->fails()) {
            $request->session()->flash('error', 'QR Code não foi lido.');
            return redirect()->route('qrcodescanner');
        }

        // Processamento do valor do QRCode
        $qrCodeData = $request->input('qrcode_value');

        try {
            // Recuperar QR codes lidos da sessão
            $qrCodesLidos = $request->session()->get('qrCodesLidos', []);

            // Instanciar o serviço de Boletim Eleitoral
            $qrCodeService = new ScannerService($qrCodeData, $qrCodesLidos);
            $status = $qrCodeService->getStatus();

            // Atualizar os QR codes lidos na sessão
            $request->session()->put('qrCodesLidos', $qrCodeService->getQrCodesLidos());

            if ($status['qr_codes_restantes'] == 0) {
                // Todos os QR codes foram lidos, prossiga para a verificação/armazenamento
                $dadosBoletim = $qrCodeService->getDadosBoletim();
                $votos = $qrCodeService->getVotos();
                // Verificar se o boletim já existe
                $boletimExistente = Boletim::where('secao_id', $dadosBoletim['SECA'])->exists();

                if ($boletimExistente) {
                    $request->session()->flash('error', 'Boletim já cadastrado.');
                } else {
                    // Verificar se a seção existe
                    $secaoExistente = Secao::find($dadosBoletim['SECA']);

                    if (!$secaoExistente) {
                        $request->session()->flash('error', 'A seção lida não pertence ao município desejado.');
                    } else {

                        // Criar novo boletim
                        $boletim = Boletim::create([
                            'secao_id' => $dadosBoletim['SECA'],
                            'apto' => $dadosBoletim['APTO'],
                            'comp' => $dadosBoletim['COMP'],
                            'falt' => $dadosBoletim['FALT'],
                            'legc' => $dadosBoletim['LEGC'],
                            'assinatura_digital' => $dadosBoletim['ASSI'],
                        ]);
                        // dd($votos);
                        foreach ($votos as $voto) {
                            // Verificar se o voto é nulo ou branco
                            if ($voto['nulo'] === 'sim' || $voto['branco'] === 'sim') {
                                // Salvar votos nulos ou brancos, que não têm candidato
                                Voto::create(array_merge($voto, [
                                    'boletim_id' => $boletim->id,
                                    'secao_id' => $dadosBoletim['SECA'],
                                ]));
                            } else {
                                // Verificar se o candidato existe para votos nominais
                                $candidatoExistente = Candidato::find($voto['candidato_id']);

                                if ($candidatoExistente) {
                                    // Se o candidato existir, criar o voto nominal
                                    Voto::create(array_merge($voto, [
                                        'boletim_id' => $boletim->id,
                                        'secao_id' => $dadosBoletim['SECA'],
                                    ]));
                                } else {
                                    // Caso o candidato não exista, registrar no log ou continuar
                                    Log::warning("Candidato não encontrado: {$voto['candidato_id']}. Voto ignorado.");
                                }
                            }
                        }

                        $request->session()->flash('success', 'Boletim enviado com sucesso.');
                    }
                }

                // Limpar sessão após o processo
                $request->session()->forget('qrCodesLidos');
                $request->session()->forget('dadosBoletim');
                $request->session()->forget('conteudoCompleto');
                $request->session()->forget('votos');
            } else {
                $request->session()->flash('status', "{$status['qr_codes_lidos']}° QR Code salvo. Leia mais {$status['qr_codes_restantes']} para completar o boletim.");
            }
        } catch (QueryException $e) {
            // Captura erros relacionados ao banco de dados
            if ($e->getCode() == "23000") { // Código de violação de integridade
                $request->session()->flash('error', 'ERRO AO SALVAR NO BANCO' . $e->getMessage());
            } else {
                $request->session()->flash('error', 'OCORREU UM ERRO AO PROCESSAR SUA SOLICITAÇÃO.');
            }
        } catch (\Exception $e) {
            // Captura quaisquer outros erros
            Log::error('Erro geral: ' . $e->getMessage());
            $request->session()->flash('error', $e->getMessage());
        }
        return redirect()->route('qrcodescanner');
    }
    public function clearQRCodes(Request $request)
    {
        // Limpar os QR codes armazenados na sessão
        $request->session()->forget('qrCodesLidos');
        $request->session()->forget('dadosBoletim');
        $request->session()->forget('conteudoCompleto');
        $request->session()->forget('votos');

        // Definir uma mensagem de sucesso
        $request->session()->flash('status', 'QrCodes limpos com sucesso.');

        // Redirecionar de volta para a página de escaneamento ou outra página desejada
        return redirect()->route('qrcodescanner');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Boletim;
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

    public function dashboard()
    {

        return view('dashboard',);
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
                $boletimExistente = Boletim::where('assinatura_digital', $dadosBoletim['ASSI'])->first();

                if ($boletimExistente) {
                    $request->session()->flash('error', 'BOLETIM EXISTENTE');
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
                            'assinatura_digital' => $dadosBoletim['ASSI'],
                        ]);
                        // dd($votos);
                        foreach ($votos as $voto) {
                            Voto::create(array_merge($voto, [
                                'boletim_id' => $boletim->id,
                                'secao_id' => $dadosBoletim['SECA'],
                            ]));
                        }

                        $request->session()->flash('success', 'BOLETIM SALVO COM SUCESSO');
                    }
                }

                // Limpar sessão após o processo
                $request->session()->forget('qrCodesLidos');
                $request->session()->forget('dadosBoletim');
                $request->session()->forget('conteudoCompleto');
                $request->session()->forget('votos');
            } else {
                $request->session()->flash('status', "ESCANEADO {$status['qr_codes_lidos']} DE {$status['qr_codes_totais']} QR CODES. FALTAM {$status['qr_codes_restantes']}.");
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
        $request->session()->flash('status', 'QR CODES LIMPOS COM SUCESSO');

        // Redirecionar de volta para a página de escaneamento ou outra página desejada
        return redirect()->route('qrcodescanner');
    }
}

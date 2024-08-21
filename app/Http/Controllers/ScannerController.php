<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Boletim;
use Illuminate\Support\Facades\Validator;
use App\Services\BoletimEleitoralService;
use Illuminate\Database\QueryException;
use App\Models\Secao;

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
                $request->session()->flash('error', 'O QRCode não foi escaneado.');
                return redirect()->route('qrcodescanner');
            }

            // Processamento do valor do QRCode
            $qrCodeValue = $request->input('qrcode_value');
            $qrCodeArray = explode(" ", $qrCodeValue);
            $qrCodeData = array_map(function ($item) {
                return explode(":", $item);
            }, $qrCodeArray);

            try {
                // Recuperar QR codes lidos da sessão
                $qrCodesLidos = $request->session()->get('qrCodesLidos', []);

                // Instanciar o serviço de Boletim Eleitoral
                $qrCodeService = new BoletimEleitoralService($qrCodeData, $qrCodesLidos);
                $status = $qrCodeService->getStatus();

                // Atualizar os QR codes lidos na sessão
                $request->session()->put('qrCodesLidos', $qrCodeService->getQrCodesLidos());

                if ($status['qr_codes_restantes'] == 0) {
                    // Todos os QR codes foram lidos, prossiga para a verificação/armazenamento
                    $dadosBoletim = $qrCodeService->getDadosBoletim();

                    // Verificar se o boletim já existe
                    $boletimExistente = Boletim::where('assinatura_digital', $dadosBoletim['ASSI'])->first();

                    if ($boletimExistente) {
                        $request->session()->flash('error', 'Os QR codes lidos são referentes a um boletim existente no sistema.');
                    } else {
                        // Verificar se a seção existe
                        $secaoExistente = Secao::find($dadosBoletim['SECA']);

                        if (!$secaoExistente) {
                            $request->session()->flash('error', 'A seção escaneada não existe no sistema.');
                        } else {
                            // Criar novo boletim
                            Boletim::create([
                                'secao_id' => $dadosBoletim['SECA'],
                                'apto' => $dadosBoletim['APTO'],
                                'comp' => $dadosBoletim['COMP'],
                                'falt' => $dadosBoletim['FALT'],
                                'assinatura_digital' => $dadosBoletim['ASSI'],
                            ]);

                            $request->session()->flash('success', 'Boletim criado com sucesso.');
                        }
                    }

                    // Limpar sessão após o processo
                    $request->session()->forget('qrCodesLidos');
                } else {
                    $request->session()->flash('status', "Você leu {$status['qr_codes_lidos']} de {$status['qr_codes_totais']} QR Codes. Faltam {$status['qr_codes_restantes']}.");
                }
            } catch (QueryException $e) {
                // Captura erros relacionados ao banco de dados
                if ($e->getCode() == "23000") { // Código de violação de integridade
                    $request->session()->flash('error', 'Erro ao salvar o boletim: dados inválidos ou inconsistentes.');
                } else {
                    $request->session()->flash('error', 'Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.');
                }
            } catch (\Exception $e) {
                // Captura quaisquer outros erros
                $request->session()->flash('error', $e->getMessage());
            }
            return redirect()->route('qrcodescanner');
    }
    public function clearQRCodes(Request $request)
    {
        // Limpar os QR codes armazenados na sessão
        $request->session()->forget('qrCodesLidos');

        // Definir uma mensagem de sucesso
        $request->session()->flash('status', 'Os QR Codes foram limpos com sucesso.');

        // Redirecionar de volta para a página de escaneamento ou outra página desejada
        return redirect()->route('qrcodescanner');
    }
}

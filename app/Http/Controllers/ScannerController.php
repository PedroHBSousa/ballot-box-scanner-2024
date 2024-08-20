<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Boletim;
use Illuminate\Support\Facades\Validator;
use App\Services\BoletimEleitoralService;

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
        $validator = Validator::make($request->all(), [
            'qrcode_value' => 'required',
        ]);

        if ($validator->fails()) {
            $request->session()->flash('error', 'O QRCode não foi escaneado');
            return to_route('qrcodescanner');
        }

        $qrCodeValue = $request->input('qrcode_value');

        $qrCodeValue = explode(" ", $qrCodeValue);

        $qrCodeValue = array_map(function ($qrCodeValue) {
            return explode(":", $qrCodeValue);
        }, $qrCodeValue);

        try {

            // Verificar se MUNI e ZONA correspondem aos valores desejados
            // foreach ($qrCodeValue as $pair) {
            //     [$key, $value] = $pair;
            //     if ($key === 'MUNI' && $value !== '71153') {
            //         throw new \Exception('O QRCode não pertence ao município desejado.');
            //     }
            //     if ($key === 'ZONA' && $value !== '132') {
            //         throw new \Exception('O QRCode não pertence à zona desejada.');
            //     }
            // }

            // Recuperar QR codes lidos da sessão
            $qrCodesLidos = $request->session()->get('qrCodesLidos', []);

            $qrCodeService = new BoletimEleitoralService($qrCodeValue, $qrCodesLidos);
            $status = $qrCodeService->getStatus();

            // Atualizar os QR codes lidos na sessão
            $request->session()->put('qrCodesLidos', $qrCodeService->getQrCodesLidos());

            if ($status['qr_codes_restantes'] == 0) {
                // Todos os QR codes foram lidos, prossiga para a verificação/armazenamento
                $dadosBoletim = $qrCodeService->getDadosBoletim();
                $boletimExistente = Boletim::where('ASSI', $dadosBoletim['ASSI'])->first();

                if ($boletimExistente) {
                    $request->session()->flash('error', 'Os QR codes lidos são referentes a um boletim existente no sistema.');
                } else {
                    // Criar novo boletim
                    $dadosBoletim = [
                        'secao_id' =>  $dadosBoletim['SECA'], // Certifique-se de passar o valor correto
                        'apto' => $dadosBoletim['APTO'],
                        'comp' => $dadosBoletim['COMP'],
                        'falt' => $dadosBoletim['FALT'],
                        'assinatura_digital' => $dadosBoletim['ASSI'],
                    ];
                    Boletim::create($dadosBoletim);
                    $request->session()->flash('success', 'Boletim criado com sucesso.');
                }

                // Limpar sessão após o processo
                $request->session()->forget('qrCodesLidos');
            } else {
                $request->session()->flash('status', "Você leu {$status['qr_codes_lidos']} de {$status['qr_codes_totais']} QR Codes. Faltam {$status['qr_codes_restantes']}.");
            }
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());
        }

        return redirect()->route('qrcodescanner');
        // dd($request->all());
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

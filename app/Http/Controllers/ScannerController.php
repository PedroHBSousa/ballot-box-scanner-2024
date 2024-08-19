<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

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
            $request->session()->flash('error', 'Não foi possível ler o QR Code');
            return to_route('qrcodescanner');
        }

        // Obtém o valor do QR code
        $qrcodeValue = $request->input('qrcode_value');


        // Converte o valor do QR code em um array, dividindo pelos espaços
        $qrcodeData = explode(' ', trim($qrcodeValue));



        // Filtra as chaves que são necessárias
        $filteredData = [];
        $totalQRCodes = 0;
        $readQRCodes = 0;

        foreach ($qrcodeData as $item) {
            $keyValue = explode(':', $item);
            $key = $keyValue[0] ?? null;
            $value = $keyValue[1] ?? null;
            // if ($key[0]=== 'ZONA' && $value[1] != '71153') {
            //     $request->session()->flash('error', 'Zona não permitida');
            //     return to_route('qrcodescanner');
            // }

            if ($key === 'QRBU') {
                $totalQRCodes = (int) explode(':', $item)[2]; // Obtém o valor total de QR codes
                $readQRCodes = (int) $value; // Obtém o número de QR codes lidos
            }

            if (in_array($key, ['QRBU', 'VRQR', 'VRCH', 'ORIG', 'CARG'])) {
                $filteredData[$key] = $value;
            }
        }

        // Verifica a situação dos QR codes
        if ($readQRCodes < $totalQRCodes) {
            $message = 'Faltam QR Codes para serem lidos.';
        } elseif ($readQRCodes === $totalQRCodes) {
            $message = 'Todos os QR Codes foram lidos.';
        } else {
            $message = 'Número de QR Codes lidos excedeu o esperado.';
        }

        // Retorna a mensagem para o usuário
        return redirect()->back()->with('status', $message)->with('data', $filteredData);
        dd($request->all());
    }
}

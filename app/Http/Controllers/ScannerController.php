<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

        $qrCodeService= new BoletimEleitoralService($qrCodeValue);

        dd($request->all());
    }
}

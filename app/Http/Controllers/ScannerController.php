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
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'qrcode_value' => 'required',
        ]);
        if ($validator->fails()) {
            $request->session()->flash('error', 'Não foi possível ler o QR Code');
            return to_route('qrcodescanner');
        }
        // $request->validate(
        //     [
        //         'qrcode-value' => 'required',
        //     ],
        //     [
        //         'qrcode-value.required' => 'Please scan the QR code',
        //     ]
        // );
        dd($request->all());
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Boletim;
use Illuminate\Http\Request;

class InsertController extends Controller
{
    public function insert()
    {
        return view('insert');
    }

    public function insertdata(Request $request)
    {
        // Validação (opcional, mas recomendado)
        $validated = $request->validate([
            'secao_id' => 'required|numeric',
            'apto' => 'required|numeric',
            'assinatura_digital' => 'required|string',
            'comp' => 'required|numeric',
            'falt' => 'required|numeric'
        ]);

        // Criar um novo Boletim
        try {
            // Criar um novo Boletim
            Boletim::create($validated);
            // Redirecionar com sucesso
            return redirect()->route('insert')->with('success', 'Inserido com sucesso');
        } catch (\Exception $e) {
            // Redirecionar com erro
            return redirect()->route('insert')->with('error', 'Ocorreu um erro ao inserir os dados.');
        }
    }
}

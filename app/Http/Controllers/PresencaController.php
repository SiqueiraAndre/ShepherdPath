<?php

namespace App\Http\Controllers;

use App\Models\Etapa;
use App\Models\Missa;
use Illuminate\Http\Request;

class PresencaController extends Controller
{
    public function index()
    {
        // Carrega as missas e as etapas junto com seus catequistas relacionados
        $missas = Missa::all();
        $etapas = Etapa::with('catequistas')->get();

        return view('presenca', compact('missas', 'etapas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome_completo' => 'required|string|max:255',
            'missa_id' => 'required|exists:missas,id',
            'etapa_id' => 'required|exists:etapas,id',
            'catequista_id' => 'required|exists:catequistas,id',
        ]);

        // A lógica do Aluno/Presenca será expandida adiante
        return redirect()->back()->with('success', 'Presença registrada com sucesso!');
    }
}

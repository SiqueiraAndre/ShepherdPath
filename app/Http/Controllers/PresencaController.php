<?php

namespace App\Http\Controllers;

use App\Models\Etapa;
use App\Models\Missa;
use App\Models\Catequizando;
use App\Models\Presenca;
use App\Models\LinkAcesso;
use Illuminate\Http\Request;

class PresencaController extends Controller
{
    public function index(Request $request)
    {
        $link = null;
        if ($request->has('ref')) {
            $link = LinkAcesso::where('hash', $request->query('ref'))->first();
        }

        if (!$link || !$link->is_ativo || ($link->expira_em && $link->expira_em->isPast())) {
            return view('expirado');
        }

        // Incrementa o número de cliques/acessos sem afetar o updated_at para não sujar logs
        $link->timestamps = false;
        $link->increment('acessos');
        $link->timestamps = true;

        // Carrega as missas e as etapas junto com seus catequistas relacionados
        $missas = Missa::all();
        $etapas = Etapa::with(['catequistas', 'catequistas.catequizandos'])->get();

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

        $catequizando = Catequizando::firstOrCreate(
        ['nome_completo' => $request->nome_completo],
        [
            'etapa_id' => $request->etapa_id,
            'catequista_id' => $request->catequista_id,
        ]
        );

        if ($catequizando->etapa_id != $request->etapa_id || $catequizando->catequista_id != $request->catequista_id) {
            $catequizando->update([
                'etapa_id' => $request->etapa_id,
                'catequista_id' => $request->catequista_id,
            ]);
        }

        Presenca::create([
            'catequizando_id' => $catequizando->id,
            'missa_id' => $request->missa_id,
            'data_missa' => now()->toDateString(),
        ]);

        return redirect()->back()->with('success', 'Presença de <b>' . $catequizando->nome_completo . '</b> registrada com sucesso!');
    }
}
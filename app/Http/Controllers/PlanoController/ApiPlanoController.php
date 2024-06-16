<?php

namespace App\Http\Controllers;

use App\Models\Plano;
use App\Models\Especialidade;
use Illuminate\Http\Request;

class PlanoController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'nome_plano' => 'required|string|max:255',
            'descricao' => 'required|string',
            'fidelidade' => 'required|boolean',
            'periodo_fidelidade' => 'nullable|string|max:255',
            'valor' => 'required|numeric',
            'especialidades' => 'required|array',
            'especialidades.*.nome' => 'required|string|max:255',
            'especialidades.*.valor' => 'required|string|max:255',
            'especialidades.*.quantidade_consultas' => 'nullable|integer',
            'especialidades.*.sem_limite' => 'required|boolean',
        ]);

        $plano = Plano::create($data);

        foreach ($data['especialidades'] as $especialidade) {
            $especialidade['plano_id'] = $plano->id;
            Especialidade::create($especialidade);
        }

        return response()->json($plano->load('especialidades'), 201);
    }
}

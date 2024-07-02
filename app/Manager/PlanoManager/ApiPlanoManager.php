<?php

namespace App\Manager\PlanoManager;

use App\Models\Plano;
use App\Models\Especialidade;
use Illuminate\Http\Request;

class ApiPlanoManager
{
    public function criarPlano(Request $request)
    {
        $plano = Plano::create([
            'nome_plano' => $request['nome'],
            'descricao' => $request['description'],
            'fidelidade' => $request['fidelity'],
            'periodo_fidelidade' => $request['fidelityPeriod'] ?? null,
            'valor' => $request['valor'],
            'especialidades' => $request['specialties'],
            'id_woocomerce' => $request['id_woocomerce']
        ]);

        return $plano;
    }
}

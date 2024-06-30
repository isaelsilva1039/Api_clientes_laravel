<?php

namespace App\Http\Controllers\PlanoController;

use App\Http\Controllers\Controller;
use App\Manager\PlanoManager\ApiPlanoManager;
use App\Models\Plano;
use App\Models\Especialidade;
use Illuminate\Http\Request;

class ApiPlanoController extends Controller
{
    protected $planoManager;

    public function __construct(ApiPlanoManager $planoManager)
    {
        $this->planoManager = $planoManager;
    }

    public function store(Request $request)
    {
        $plano = $this->planoManager->criarPlano($request);

        return response()->json($plano, 201);
    }

    public function editarPlano(Request $request, $id)
    {
        $plano = Plano::findOrFail($id);
        // Obtendo as especialidades do request e formatando para o formato desejado
        $especialidades = $request->input('specialties', []);

        if (is_array($especialidades)) {
            $especialidadesFormatadas = array_map(function ($especialidade) {
                return [
                    'specialty' => $especialidade['id'] ?? $especialidade['specialty'],
                    'consultationCount' => $especialidade['consultationCount']
                ];
            }, $especialidades);
        } else {
            $especialidadesFormatadas = [];
        }

        $plano->update([
            'nome_plano' => $request['nome'],
            'descricao' => $request['description'],
            'fidelidade' => $request['fidelity'],
            'periodo_fidelidade' => $request['fidelityPeriod'] ?? null,
            'valor' => $request['valor'],
            'especialidades' => $especialidadesFormatadas,
        ]);


        return response()->json($plano, 200);
    }



    public function deletarPlano($id)
    {
        $plano = Plano::findOrFail($id);
        $plano->delete();

        return response()->json(null, 204);
    }

    public function listarPlanos(Request $request)
    {
        $perPage = $request->get('per_page', 10); // Número de itens por página, padrão é 10

        $planos = Plano::paginate($perPage);

        // Carregar especialidades
        $planos->getCollection()->transform(function ($plano) {
            $especialidades = $plano->especialidades;
            $especialidadeData = [];
            foreach ($especialidades as $especialidade) {
                $especialidadeInfo = Especialidade::find($especialidade['specialty']);
                if ($especialidadeInfo) {
                    $especialidadeData[] = [
                        'id' => $especialidadeInfo->id,
                        'nome' => $especialidadeInfo->nome,
                        'consultationCount' => $especialidade['consultationCount']
                    ];
                }
            }
            $plano->especialidades = $especialidadeData;
            return $plano;
        });

        return response()->json($planos, 200);
    }
}

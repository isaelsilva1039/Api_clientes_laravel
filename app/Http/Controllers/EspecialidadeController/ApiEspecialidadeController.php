<?php


namespace App\Http\Controllers\EspecialidadeController;

use App\Http\Controllers\Controller;
use App\Models\Especialidade;
use Illuminate\Http\Request;

class ApiEspecialidadeController extends Controller
{
    public function store(Request $request)
    {
 
           $especialidade = Especialidade::create($request->request->all());

        return response()->json($especialidade, 201);
    }



    public function index(Request $request)
    {
        $perPage = $request->query('perPage', 8); // Número de itens por página, padrão 10
        $especialidades = Especialidade::orderBy('id', 'desc')->paginate($perPage);


        return response()->json([
            'data' => $especialidades->items(),
            'meta' => [
                'total' => $especialidades->total(),
                'perPage' => $especialidades->perPage(),
                'currentPage' => $especialidades->currentPage(),
                'lastPage' => $especialidades->lastPage(),
                'hasMorePages' => $especialidades->hasMorePages(),
            ]
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nome' => 'sometimes|required|string|max:255',
            'valor' => 'sometimes|required|string|max:255',
            'quantidade_consultas' => 'sometimes|nullable|integer',
            'sem_limite' => 'sometimes|required|boolean',
        ]);

        $especialidade = Especialidade::findOrFail($id);
        $especialidade->update($data);

        return response()->json($especialidade, 200);
    }



    public function excluir($id)
    {
        $especialidade = Especialidade::find($id);
    
        if (!$especialidade) {
            return response()->json(['message' => 'Especialidade não encontrada'], 404);
        }
    
        $especialidade->delete();
    
        return response()->json(['message' => 'Especialidade excluída com sucesso'], 200);
    }
    
}

<?php

namespace App\Manager\ApiHorariosManager;

use App\Http\Controllers\Controller;
use App\Models\horarios\HorarioSemanal;
use App\Models\horarios\Mes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ApiHorariosManager extends Controller
{

    public function store(Request $request)
    {
        $userId = $request->user()->id;

        $data = [
            'horarios' => $request->input('horarios')
        ];

        $horario = HorarioSemanal::updateOrCreate(
            ['user_id' => $userId],
            $data
        );

        return response()->json(['message' => 'Horário salvo com sucesso!', 'data' => $horario], 201);
    }


    public function action(Request $request)
    {

        $userId = $request->user()->id;
        $horarios = HorarioSemanal::where('user_id', $userId)->get();

        if ($horarios->isEmpty()) {
            return response()->json(['message' => 'Nenhum horário encontrado para o usuário.'], 404);
        }

        return response()->json(['message' => 'Horários encontrados com sucesso.', 'data' => $horarios], 200);
    }



    public function obterHorariosUser($userId)
    {


        // Busca todos os horários associados ao usuário
        $horarios = HorarioSemanal::where('user_id', $userId)->get();

        // Verifica se encontrou horários e retorna a resposta adequada
        if ($horarios->isEmpty()) {
            return false;
        }

        return $horarios;

    }



    public function createAgenda(Request $request)
    {
         $validatedData = $request->validate([
            // 'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:50',
            'isActive' => 'required|boolean',
            'value' => 'required|integer',
        ]);


        // return Auth()->user()->id;
        // Tenta encontrar um registro existente pelo user_id e value
        $mes = Mes::where('user_id', Auth()->user()->id)
            ->where('value', $validatedData['value'])
            ->first();

        if ($mes) {
            // Atualiza o campo `isActive` se o registro já existir
            $mes->isActive = $validatedData['isActive'];
            $mes->save();
        } else {
            // Cria um novo registro caso não exista
            Mes::create([
                'user_id' => Auth()->user()->id,
                'mes' => $validatedData['name'],
                'isActive' => $validatedData['isActive'],
                'value' => $validatedData['value'],
            ]);
        }

        return response()->json(['message' => 'Operação realizada com sucesso!'], 200);
    }





    public function obterMesAgenda($id, Request $request)
    {

        // Recebe o valor opcional do parâmetro `ativo` da requisição
        $ativo = $request->input('ativo', false);
     
        // Inicia a query para buscar todos os meses associados ao usuário fornecido
        $query = Mes::where('user_id', $id);
    
        // Se o parâmetro `ativo` for fornecido e verdadeiro, filtra apenas os meses ativos
        if ($ativo) {
          
            $query->where('isActive', (bool) $ativo);
        }
    

        $query->orderBy('value' , 'asc');
        // Executa a consulta e obtém os resultados
        $agendasLiberadas = $query->get();
    
        // Retorna os meses encontrados
        return response()->json([
            'message' => 'Agendas encontradas com sucesso!',
            'data' => $agendasLiberadas,
        ], 200);
    }
    

    public function updateTempoConsulta(Request $request)
    {   
        /** @var User $usuario */
        $usuario =  Auth()->user();

        $tempoConsulta = ($request->input('hora') * 60) + $request->input('minuto');

        $usuario->tempo_consulta = $tempoConsulta;
        $usuario->save();

        return $usuario;
    }

}

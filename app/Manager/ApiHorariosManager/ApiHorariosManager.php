<?php

namespace App\Manager\ApiHorariosManager;

use App\Http\Controllers\Controller;
use App\Models\horarios\HorarioSemanal;
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

}

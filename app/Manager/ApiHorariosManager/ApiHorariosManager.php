<?php

namespace App\Manager\ApiHorariosManager;

use App\Http\Controllers\Controller;
use App\Models\horarios\HorarioSemanal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ApiHorariosManager extends Controller
{


    // Método para salvar os horários// Método para salvar os horários
    public function store(Request $request)
    {

         // Obtém o ID do usuário autenticado
        $userId = $request->user()->id;

        // Dados para atualização ou criação
        $data = [
            'horarios' => $request->input('horarios') // Certifique-se de que 'horarios' é enviado como um array ou objeto JSON adequado
        ];

        // Tenta atualizar o horário existente ou criar um novo
        $horario = HorarioSemanal::updateOrCreate(
            ['user_id' => $userId], // Condições para encontrar o registro existente
            $data                  // Dados para atualizar ou criar
        );

        // Cria e salva o novo horário
        // $horario = HorarioSemanal::create($data);

        return response()->json(['message' => 'Horário salvo com sucesso!', 'data' => $horario], 201);
    }


    public function action(Request $request)
    {

        // Obtém o ID do usuário autenticado
        $userId = $request->user()->id;

        // Busca todos os horários associados ao usuário
        $horarios = HorarioSemanal::where('user_id', $userId)->get();

        // Verifica se encontrou horários e retorna a resposta adequada
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

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
       // Verifica se o usuário está autenticado
       if (!Auth::check()) {
           return response()->json(['message' => 'Não autenticado'], 401);
       }

       // Obtém o ID do usuário autenticado
       $userId = Auth::id();

       // Valida os dados da requisição
       $data = $request->validate([
           'dia_da_semana' => 'required|string|max:10',
           'hora_inicio' => 'required|date_format:H:i',
           'hora_fim' => 'required|date_format:H:i'
       ]);

       // Inclui o ID do usuário no array de dados
       $data['user_id'] = $userId;

       // Cria e salva o novo horário
       $horario = HorarioSemanal::create($data);

       // Retorna uma resposta JSON
       return response()->json(['message' => 'Horário salvo com sucesso!', 'data' => $horario], 201);
   }

  }

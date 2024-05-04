<?php 


namespace App\Http\Controllers\AgendamentoController;

use App\Http\Controllers\Controller;
use App\Models\Agenda\Agendamento;
use Illuminate\Http\Request;


class ApiAgendamentoController extends Controller
{
    public function criarAgendamento(Request $request)
    {


        $start_time = date('Y-m-d H:i:s', strtotime($request->start_time));
        $end_time = date('Y-m-d H:i:s', strtotime($request->end_time));

        $horarioLivre = Agendamento::where('medico_id', $request->medico_id)
            ->where(function ($query) use ($start_time, $end_time) {
                $query->whereBetween('start_time', [$start_time, $end_time])
                      ->orWhereBetween('end_time', [$start_time, $end_time]);
            })->doesntExist();

        if (!$horarioLivre) {
            return response()->json(['error' => 'Horário já está ocupado.'], 409);
        }    

        $agendamento = new Agendamento;
        $agendamento->medico_id = $request->medico_id;
        $agendamento->cliente_id = $request->cliente_id;
        $agendamento->start_time = $start_time;
        $agendamento->end_time = $end_time;
        $agendamento->save();

        return response()->json(['message' => 'Agendamento criado com sucesso!'], 201);
    }
}

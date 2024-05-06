<?php


namespace App\Http\Controllers\AgendamentoController;

use App\Http\Controllers\Controller;
use App\Models\Agenda\Agendamento;
use App\Models\horarios\HorarioSemanal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAgendamentoController extends Controller
{
    public function criarAgendamento(Request $request)
    {
        $medicoId = $request->medico_id;
        $clienteId = auth()->user()->id;
        $start_time = Carbon::parse($request->start_time);
        $end_time = Carbon::parse($request->end_time)->subMinute(); // Subtrai 1 minuto do término
    

        // Verificar disponibilidade e sobreposição de horários
        $validacao = $this->validarHorario($medicoId, $start_time, $end_time);
        if ($validacao['erro']) {
            return response()->json(['error' => $validacao['mensagem']], $validacao['status']);
        }


        // Criar o agendamento
        $agendamento = new Agendamento;
        $agendamento->medico_id = $medicoId;
        $agendamento->cliente_id = $clienteId;
        $agendamento->start_time = $start_time;
        $agendamento->end_time = $end_time;
        $agendamento->save();

        return response()->json(['message' => 'Agendamento criado com sucesso!'], 201);
    }



    private function isHorarioPermitido($horariosDoDia, Carbon $start_time, Carbon $end_time)
    {
        if (!$horariosDoDia) {
            return false;
        }

        foreach ($horariosDoDia as $horario) {
            $inicioPermitido = Carbon::parse($start_time->format('Y-m-d') . ' ' . $horario['start']);
            $fimPermitido = Carbon::parse($start_time->format('Y-m-d') . ' ' . $horario['end']);

            if ($start_time->gte($inicioPermitido) && $end_time->lte($fimPermitido)) {
                return true;
            }
        }
        return false;
    }

    public function buscarAgendamentosCliente()
    {
        $user = auth()->user();

        // Verifica se o usuário está logado e se é um cliente (tipo 3)
        if ($user && $user->tipo == 3) {
            $agendamentos = $user->agendamentosComoCliente()->with(['medico', 'cliente'])->get();


            return response()->json([
                'message' => 'Agendamentos encontrados com sucesso!',
                'data' => $agendamentos
            ], 200);
        }



        // Verifica se o usuário está logado e se é um cliente (tipo 3)
        if ($user && $user->tipo == 2) {
            // Buscar todos os agendamentos onde o `medico_id` é igual ao ID do usuário
            $agendamentos = Agendamento::with(['medico', 'cliente'])
                ->where('medico_id', $user->id)
                ->get();

            return response()->json([
                'message' => 'Agendamentos encontrados com sucesso!',
                'data' => $agendamentos
            ], 200);
        }


        // Verifica se o usuário está logado e se é um cliente (tipo 3)
        if ($user && $user->tipo == 1) {
            // Buscar todos os agendamentos onde o `medico_id` é igual ao ID do usuário
            $agendamentos = Agendamento::with(['medico', 'cliente'])
                // ->where('medico_id', $user->id)
                ->get();

            return response()->json([
                'message' => 'Agendamentos encontrados com sucesso!',
                'data' => $agendamentos
            ], 200);
        }

        return response()->json(['error' => 'Usuário não autorizado ou não é um cliente.'], 403);
    }



    public function validarHorario($medicoId, $start_time, $end_time)
    {

        $horariosMedico = HorarioSemanal::where('user_id', $medicoId)->first();

        if (!$horariosMedico) {
            return ['erro' => true, 'mensagem' => 'Horários do médico não encontrados.', 'status' => 404];
        }

        $mapaDias = [
            'monday' => 'segunda',
            'tuesday' => 'terca',
            'wednesday' => 'quarta',
            'thursday' => 'quinta',
            'friday' => 'sexta',
            'saturday' => 'sabado',
            'sunday' => 'domingo'
        ];

        // Converter o dia da semana de inglês para português
        $diaEmIngles = strtolower($start_time->format('l'));
        $diaDaSemana = $mapaDias[$diaEmIngles] ?? null;

        if (!$diaDaSemana || !isset($horariosMedico->horarios[$diaDaSemana])) {
            return ['erro' => true, 'mensagem' => 'Não há horários disponíveis para este dia.', 'status' => 400];
        }

        $horariosDoDia = $horariosMedico->horarios[$diaDaSemana];

        if (!$this->isHorarioPermitido($horariosDoDia, $start_time, $end_time)) {
            return ['erro' => true, 'mensagem' => 'Horário fora do expediente do médico.', 'status' => 403];
        }

        $horarioLivre = Agendamento::where('medico_id', $medicoId)
            ->where(function ($query) use ($start_time, $end_time) {
                $query->whereBetween('start_time', [$start_time, $end_time])
                    ->orWhereBetween('end_time', [$start_time, $end_time]);
            })->doesntExist();

        if (!$horarioLivre) {
            return ['erro' => true, 'mensagem' => 'Horário já está ocupado.', 'status' => 409];
        }

        return ['erro' => false];
    }



    public function verificarDisponibilidaDeEeHorario(Request $request)
    {
        $medico_id = $request->input('medico_id');
        $start_time = Carbon::parse($request->input('start_time'));
        $end_time = Carbon::parse($request->input('end_time'));

        return $this->validarHorario($medico_id, $start_time, $end_time);
    }


    public function buscarHorariosDisponiveis(Request $request, $id)
    {

        $medicoId = $id;
        $dia = Carbon::parse($request->input('dia'))->format('Y-m-d');

        // Obter os horários do médico
        $horariosMedico = HorarioSemanal::where('user_id', $medicoId)->first();
        if (!$horariosMedico) {
            return response()->json(['error' => 'Horários do médico não encontrados.'], 500);
        }

        // Obter o nome do dia da semana em português
        $mapaDias = [
            'monday' => 'segunda',
            'tuesday' => 'terca',
            'wednesday' => 'quarta',
            'thursday' => 'quinta',
            'friday' => 'sexta',
            'saturday' => 'sabado',
            'sunday' => 'domingo'
        ];
        $diaDaSemanaIngles = strtolower(Carbon::parse($dia)->format('l'));
        $diaDaSemana = $mapaDias[$diaDaSemanaIngles] ?? null;

        if (!$diaDaSemana || !isset($horariosMedico->horarios[$diaDaSemana])) {
            return response()->json(['error' => 'Não há horários disponíveis para este dia.'], 400);
        }

        $horariosDoDia = $horariosMedico->horarios[$diaDaSemana];

        // Obter todos os agendamentos do médico para o dia
        $agendamentosOcupados = Agendamento::where('medico_id', $medicoId)
            ->whereDate('start_time', $dia)
            ->get(['start_time', 'end_time']);

        // Gerar todos os horários disponíveis baseando-se nos horários do expediente
        $horariosDisponiveis = [];
        foreach ($horariosDoDia as $periodo) {
            $startExpediente = Carbon::parse("$dia {$periodo['start']}");
            $endExpediente = Carbon::parse("$dia {$periodo['end']}");
            $intervalo = 45; // intervalo de 45 minutos entre cada horário

            while ($startExpediente->addMinutes($intervalo)->lte($endExpediente)) {
                $slotInicio = $startExpediente->clone()->subMinutes($intervalo);
                $slotFim = $startExpediente->clone();

                // Verificar se o horário está ocupado
                $ocupado = $agendamentosOcupados->contains(function ($agendamento) use ($slotInicio, $slotFim) {
                    return ($agendamento->start_time < $slotFim && $agendamento->end_time > $slotInicio);
                });

                // Se não estiver ocupado, adiciona na lista de horários disponíveis
                if (!$ocupado) {
                    $horariosDisponiveis[] = [
                        'start' => $slotInicio->format('H:i'),
                        'end' => $slotFim->format('H:i')
                    ];
                }
            }
        }

        return response()->json([
            'message' => 'Horários disponíveis encontrados com sucesso!',
            'data' => $horariosDisponiveis
        ], 200);
    }
}
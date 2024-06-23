<?php


namespace App\Manager\WhatsAppManager;

use App\Http\Controllers\AgendamentoController\ApiAgendamentoController;
use App\Models\Cliente;
use App\Models\Conversation\Conversation;
use App\Models\horarios\Mes;
use App\Models\Profissional;
use App\Models\TwilioSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;




class WhatsAppManager
{
    protected $token;
    protected $phoneId;
    protected $twilio;

    protected $apiAgendamento;

    public function __construct(ApiAgendamentoController $apiAgendamento)
    {
        $twilioSetting = TwilioSetting::find(1);

        $this->twilio = new Client($twilioSetting->sid, $twilioSetting->token);

        $this->apiAgendamento = $apiAgendamento;
    }

    public function sendMessage($to = '+559992292338', $message = 'Lá ele')
    {

        $from = "whatsapp:+14155238886"; // Número correto para o WhatsApp


        return $this->twilio->messages->create(
            "whatsapp:{$to}", // to
            [
                "from" => $from,
                "body" => $message
            ]
        );
    }

    protected function isValidCPF($cpf)
    {
        // Função para validar CPF (apenas um exemplo básico)
        // Você pode substituir esta função por uma validação mais robusta de CPF

        // Remover caracteres não numéricos
        $cpf = preg_replace('/\D/', '', $cpf);

        // Verificar se o CPF tem 11 dígitos
        if (strlen($cpf) != 11) {
            return false;
        }

        // Verificar se todos os dígitos são iguais (CPFs inválidos conhecidos)
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Calcular dígitos verificadores para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }



    public function handleWebhook(Request $request)
    {
        $from = '+559992292338'; // Número do remetente
        $body = $request->input('Body'); // Corpo da mensagem

        // Processar a mensagem recebida e obter a resposta
        $responseMessage = $this->processMessage($from, $body);

        $this->sendMessage($from, $responseMessage);
    }

    protected function processMessage($from, $body)
    {
        $conversation = Conversation::firstOrCreate(
            ['phone_number' => $from],
            ['asked_for_cpf' => false, 'status' => 'initial']
        );

        switch ($conversation->status) {
            case 'initial':
                return $this->handleInitialMessage($conversation);

            case 'waiting_for_cpf':
                return $this->handleCpfMessage($conversation, $body);

            case 'cpf_received':
                return $this->handleMenu($conversation, $body);

            case 'choosing_professional':
                return $this->handleChoosingProfessional($conversation, $body);

            case 'choosing_month':
                return $this->handleChoosingMonth($conversation, $body);

            case 'choosing_day':
                return $this->handleChoosingDay($conversation, $body);

            case 'choosing_time':
                return $this->handleChoosingTime($conversation, $body);

            default:
                return 'Erro desconhecido. Por favor, tente novamente.';
        }
    }

    protected function handleInitialMessage($conversation)
    {
        $conversation->status = 'waiting_for_cpf';
        $conversation->save();
        return 'Olá, seja bem-vindo ao agendamento Racca Saúde. Digite o seu CPF sem pontos e traços para continuarmos.';
    }

    protected function handleCpfMessage($conversation, $body)
    {
        if (!$this->isValidCPF($body)) {
            return 'Por favor, envie um CPF válido para continuar.';
        }

        $conversation->cpf = $body;
        $conversation->status = 'cpf_received';
        $conversation->save();

        $client = Cliente::where('cpfCnpj', $conversation->cpf)->first();

        if (!$client) {
            return "Esse CPF não foi encontrado na nossa base de dados.";
        }

        return "Olá, " . $client->name . "\n"
            . "O que deseja fazer? Digite o número da opção que deseja:\n"
            . "1. Agendar consulta\n"
            . "2. Ver suas consultas agendadas\n"
            . "3. Link da sala de chamada\n"
            . "4. Finalizar";
    }

    protected function handleMenu($conversation, $body)
    {
        switch ($body) {
            case '1':
                $conversation->status = 'choosing_professional';
                $conversation->save();
                return $this->handleAgendarConsulta();
            case '2':
                return 'Você escolheu ver suas consultas agendadas. Aqui estão seus agendamentos...';
            case '3':
                return 'Aqui está o link da sala de chamada: [link]';
            case '4':
                $conversation->delete();
                return 'Conversa finalizada. Se precisar de mais ajuda, envie uma nova mensagem.';
            default:
                return "Opção inválida. Digite o número da opção que deseja:\n"
                    . "1. Agendar consulta\n"
                    . "2. Ver suas consultas agendadas\n"
                    . "3. Link da sala de chamada\n"
                    . "4. Finalizar";
        }
    }

    protected function handleAgendarConsulta()
    {
        // Filtrar apenas os profissionais que têm um user_id
        $profissionais = Profissional::whereNotNull('user_id')->get();
        $response = "Você escolheu agendar uma consulta. Por favor, escolha um profissional:\n";

        foreach ($profissionais as $profissional) {
            $response .= "Matricula: " . $profissional->user_id . " - " . $profissional->nome . ' : ' . $profissional->especialidade . "\n \n";
        }

        $response .= "Digite o número correspondente ao profissional escolhido ou 4 para finalizar.";

        return $response;
    }

    protected function handleChoosingProfessional($conversation, $body)
    {
        if ($body == '4') {
            $conversation->delete();
            return 'Conversa finalizada. Se precisar de mais ajuda, envie uma nova mensagem.';
        }

        $profissional = Profissional::where('user_id', $body)->first();

        if (!$profissional) {
            return "Profissional inválido. Por favor, escolha um profissional válido:\n" . $this->handleAgendarConsulta();
        }

        // Salvar a escolha do profissional no campo meta
        $meta = $conversation->meta ?? [];
        $meta['professional'] = $profissional->toArray();
        $conversation->meta = $meta;
        $conversation->status = 'choosing_month';
        $conversation->save();

        return $this->listActiveMonths($conversation);
    }

    protected function handleChoosingMonth($conversation, $body = null)
    {
        if ($body == '4') {
            $conversation->delete();
            return 'Conversa finalizada. Se precisar de mais ajuda, envie uma nova mensagem.';
        }

        if ($body) {
            $meta = $conversation->meta ?? [];
            $selectedMonth = Mes::where('id', $body)->where('isActive', 1)->first();

            if (!$selectedMonth) {
                return "Mês inválido. Por favor, escolha um mês válido:\n" . $this->listActiveMonths($conversation);
            }

            $meta['month'] = $selectedMonth->toArray();
            $conversation->meta = $meta;
            $conversation->status = 'choosing_day';
            $conversation->save();

            return $this->listDaysInMonth($selectedMonth);
        }

        return $this->listActiveMonths($conversation);
    }

    protected function listActiveMonths($conversation)
    {
        $professionalId = $conversation->meta['professional']['user_id'];
        $months = Mes::where('user_id', $professionalId)->where('isActive', 1)->get();


        $response = "Escolha um mês para o agendamento: \n OBS: **Esse profissional só tem esses mês liberado para agendamento** \n
        ";

        foreach ($months as $month) {
            $response .= $month->id . ". " . $month->mes . "\n";
        }

        $response .= "Digite o número correspondente ao mês escolhido ou 4 para finalizar.";

        return $response;
    }


    protected function handleChoosingDay($conversation, $body)
    {
        if ($body == '4') {
            $conversation->delete();
            return 'Conversa finalizada. Se precisar de mais ajuda, envie uma nova mensagem.';
        }

        $meta = $conversation->meta ?? [];
        $selectedDay = (int)$body;
        $currentMonth = $meta['month']['mes'];

        if ($selectedDay < 1 || $selectedDay > date('t', strtotime("$currentMonth-01"))) {
            return "Dia inválido. Por favor, escolha um dia válido:\n" . $this->listDaysInMonth($meta['month']);
        }

        $meta['day'] = $selectedDay;
        $conversation->meta = $meta;
        $conversation->status = 'choosing_time';
        $conversation->save();

        return $this->listAvailableTimes($conversation);
    }


    protected function listDaysInMonth($month)
    {
        $currentMonth = $month['mes'];
        $currentYear = date('Y');
        $daysInMonth = date('t', strtotime("$currentYear-$currentMonth-01"));
        $currentDay = date('j');

        $response = "Escolha um dia para o agendamento:\n";

        for ($day = $currentDay; $day <= $daysInMonth; $day++) {
            $response .= $day . "\n";
        }

        $response .= "Digite o número correspondente ao dia escolhido ou 4 para finalizar.";

        return $response;
    }


    protected function listAvailableTimes($conversation)
    {
        $meta = $conversation->meta;
        $professionalId = $meta['professional']['user_id'];
        $date = date('Y-m-d', strtotime($meta['month']['mes'] . '-' . $meta['day']));
    
        // Log para inspecionar as variáveis
        Log::info('Profissional ID:', ['professionalId' => $professionalId]);
        Log::info('Data:', ['date' => $date]);
    
        $availableTimes = $this->apiAgendamento->buscarHorariosDisponiveisParaBoot($date, $professionalId);
    
        // Log para inspecionar os horários disponíveis
        Log::info('Horários Disponíveis:', ['availableTimes' => $availableTimes]);
    
        $response = "Escolha um horário para o agendamento:\n";
    
        foreach ($availableTimes as $time) {
            // Log para inspecionar cada horário
            Log::info('Horário:', ['time' => $time]);
    
            if (is_array($time)) {
                $timeString = $time[0] . ' a ' . $time[1]; // Assume que $time[0] é start e $time[1] é end
            } else {
                $timeString = $time;
            }
            $response .= $timeString . "\n";
        }
    
        $response .= "Digite o horário de início escolhido no formato HH:MM ou 4 para finalizar.";
    
        // Log para inspecionar a resposta final
        Log::info('Resposta:', ['response' => $response]);
    
        return $response;
    }
    


    protected function handleChoosingTime($conversation, $body)
    {
        if ($body == '4') {
            $conversation->delete();
            return 'Conversa finalizada. Se precisar de mais ajuda, envie uma nova mensagem.';
        }
    
        $meta = $conversation->meta ?? [];
        $selectedTime = $body;
    
        // Validação básica do horário no formato HH:MM
        if (!preg_match('/^\d{2}:\d{2}$/', $selectedTime)) {
            return "Horário inválido. Por favor, escolha um horário válido no formato HH:MM:\n" . $this->listAvailableTimes($conversation);
        }
    
        // Formatar a data e horário para criar o agendamento
        $date = date('Y-m-d', strtotime($meta['month']['mes'] . '-' . $meta['day']));
        $startTime = $date . ' ' . $selectedTime . ':00';
    
        // Encontra o horário correspondente na lista de horários disponíveis
        $availableTimes = $this->apiAgendamento->buscarHorariosDisponiveisParaBoot($date, $meta['professional']['user_id']);
    
        // Log para inspecionar os horários disponíveis
        Log::info('Horários Disponíveis:', ['availableTimes' => $availableTimes]);
    
        $endTime = null;
        foreach ($availableTimes as $time) {
            // Log para inspecionar cada horário
            Log::info('Horário:', ['time' => $time]);
    
            if (is_array($time) && isset($time[0]) && $time[0] === $selectedTime) {
                $endTime = $date . ' ' . $time[1] . ':00';
                break;
            }
        }
    
        if (!$endTime) {
            return "Horário inválido. Por favor, escolha um horário válido:\n" . $this->listAvailableTimes($conversation);
        }
    
        // Criar o agendamento
        $agendamento = $this->apiAgendamento->criarAgendamentoComBoot($meta['professional']['user_id'], $startTime, $endTime);
    
        if (is_string($agendamento)) {
            return $agendamento; // Mensagem de erro da validação de horário
        }
    
        // Atualizar a conversa com o agendamento criado
        $meta['appointment'] = $agendamento->toArray();
        $conversation->meta = $meta;
        $conversation->save();
    
        return "Agendamento confirmado para " . Carbon::parse($startTime)->format('d/m/Y') . " às " . Carbon::parse($startTime)->format('H:i') . ". Obrigado!";
    }
    
    
}

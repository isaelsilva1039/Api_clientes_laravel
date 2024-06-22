<?php


namespace App\Manager\WhatsAppManager;

use App\Models\Cliente;
use App\Models\Conversation\Conversation;
use App\Models\Profissional;
use App\Models\TwilioSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Twilio\Rest\Client;
use Twilio\TwiML\MessagingResponse;



class WhatsAppManager
{
    protected $token;
    protected $phoneId;
    protected $twilio;

    public function __construct()
    {
        $twilioSetting = TwilioSetting::find(1);

        $this->twilio = new Client($twilioSetting->sid, $twilioSetting->token);
    }

    public function processMessageAction($request)
    {
        $from = $request->input('From'); // Número do remetente
        $body = $request->input('Body'); // Corpo da mensagem

        // Processar a mensagem recebida e obter a resposta
        $responseMessage = $this->processMessage($from, $body);


        $this->sendMessage($to = '+559992292338', $responseMessage);
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
                return $this->handleOptionMessage($conversation, $body);

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

    protected function handleOptionMessage($conversation, $body)
    {
        switch ($body) {
            case '1':
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


    public function sendMessage($to = '+559992292338', $message = 'Lá ele')
    {

        $from = "14155238886";

        return $this->twilio->messages->create(
            "whatsapp:{$to}", // to
            [
                "from" => "whatsapp:{$from}",
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


    protected function handleAgendarConsulta()
    {
        $profissionais = Profissional::all();
        $nomesProfissionais = $profissionais->pluck('name');
        $response = "Você escolheu agendar uma consulta. Por favor, escolha um profissional:\n";
        
        foreach ($profissionais as $nome) {
            $response .= ('Matricula : ' . $profissionais->user_id) . ". " . $nome . "\n";
        }

        return $response;
    }
}

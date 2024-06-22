<?php


namespace App\Manager\WhatsAppManager;

use App\Models\Cliente;
use App\Models\Conversation\Conversation;
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

    public function processMessage($request)
    {   
        $from = $request->input('From'); // Número do remetente
        $body = $request->input('Body'); // Corpo da mensagem

        // Processar a mensagem recebida e obter a resposta
        $responseMessage = $this->tomarDecisao($from, $body);


        $this->sendMessage($to = '+559992292338', $responseMessage);
    }


 
    protected function tomarDecisao($from, $body)
    {
        // Verificar se é a primeira mensagem
        $conversation = Conversation::firstOrCreate(
            ['phone_number' => $from],
            ['asked_for_cpf' => false, 'status' => 'initial']
        );

        switch ($conversation->status) {
            case 'initial':
                $conversation->status = 'waiting_for_cpf';
                $conversation->save();
                return 'Olá, seja bem-vindo ao agendamento Racca Saúde. Digite o seu CPF sem pontos e traços para continuarmos.';
            
            case 'waiting_for_cpf':
                if (!$this->isValidCPF($body)) {
                    return 'Por favor, envie um CPF válido para continuar.';
                }
                $conversation->cpf = $body;

                $conversation->status = 'cpf_received';
                $conversation->save();

                $client = Cliente::where('cpfCnpj', $conversation->cpf)->first();

                if(!$client){
                    return "Esse CPF não foi encontrado na nossa base de dados";
                }

                return "Olá ". $client->name ."\n".
                   "O que deseja fazer? Digite o número da opção que deseja:\n"
                . "1. Agendar consulta\n"
                . "2. Ver suas consultas agendadas\n"
                . "3. Link da sala de chamada\n"
                . "4. Finalizar";
        
            
            case 'cpf_received':
                switch ($body) {
                    case '1':
                        // Lógica para agendar consulta
                        return 'Você escolheu agendar uma consulta. Por favor, forneça mais detalhes...';
                    case '2':
                        // Lógica para ver consultas agendadas
                        return 'Você escolheu ver suas consultas agendadas. Aqui estão seus agendamentos...';
                    case '3':
                        // Lógica para fornecer link da sala de chamada
                        return 'Aqui está o link da sala de chamada: [link]';
                    case '4':
                        $conversation->delete();
                        return 'Conversa finalizada. Se precisar de mais ajuda, envie uma nova mensagem.';
                    default:
                        return 'Opção inválida. Digite o número da opção que deseja: 1. Agendar consulta 2. Ver suas consultas agendadas 3. Link da sala de chamada';
                }
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


}
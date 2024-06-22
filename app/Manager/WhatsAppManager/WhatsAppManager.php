<?php


namespace App\Manager\WhatsAppManager;

use Illuminate\Support\Facades\Http;

class WhatsAppManager
{
    protected $token;
    protected $phoneId;

    public function __construct()
    {
        
    }

    public function processMessage($data)
    {
        // Lógica para processar a mensagem recebida
        $message = $data['messages'][0]['text']['body'];

        // Responder à mensagem
        $response = $this->sendMessage($data['messages'][0]['from'], "Você disse: $message");

        return $response;
    }

    public function sendMessage($to, $message)
    {
        $this->token = 'EAAFF9gS6WJUBOzdmYL9c1cchy3Ki1EmW4s7gEzs8RfI3cgzVhtnzHl2Gky6HSaNsEco4TDLJ7GLg5pDMVvLc6A8ljMZAC0RkGp1Qm52ukAa6kPmbPGxtsf8dZCCJZBKn1VzyLsD3JLZBzD0em4tNRHOZAZBbuqoBbzVuXkZCUIvCZBPaIBLjoq0vBN4ZCh7gWt1n912Q58JQmdYQgw842ZC30tBju1oc2VmhwAG4YZD';
        $this->phoneId = '375669655620250';

        $url = "https://graph.facebook.com/v13.0/{$this->phoneId}/messages";

        $response = Http::withToken($this->token)->post($url, [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'text',
            'text' => ['body' => $message],
        ]);

        return $response->json();
    }
}

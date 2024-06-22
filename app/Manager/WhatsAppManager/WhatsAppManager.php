<?php


namespace App\Manager\WhatsAppManager;

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
        // Envia uma resposta automática
        // $response = new MessagingResponse();
        // $response->message('WebHookrespondendo de boas');
        $this->sendMessage($to = '+559992292338', $message = 'O que queres ?');

        // return response($response, 200)->header('Content-Type', 'text/xml');
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



}
<?php

namespace App\Http\Controllers\ApiWhatsAppController;

use App\Http\Controllers\Controller;
use App\Manager\WhatsAppManager\WhatsAppManager;
use Illuminate\Http\Request;

class WhatsAppController extends Controller
{
    protected $whatsAppManager;

    public function __construct(WhatsAppManager $whatsAppManager)
    {
        $this->whatsAppManager = $whatsAppManager;
    }

    public function handleWebhook(Request $request)
    {
   
        // Processar a mensagem recebida
        $response = $this->whatsAppManager->handleWebhook($request);

        return response()->json($response);
    }


    public function sendMensagaem(Request $request)
    {

        $request->to;

        // Processar a mensagem recebida
        $response = $this->whatsAppManager->sendMessage(
            $request->to,
            $request->text
        );

        return response()->json($response);
    }



    
}

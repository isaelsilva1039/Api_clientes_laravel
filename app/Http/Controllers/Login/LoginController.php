<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use App\Manager\LoginManager\ApiLoginManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    
    private $apiLoginManager;

    public function __construct(
        // dentro do construct coloque as class que inicia como serviços
        ApiLoginManager $apiLoginManager
        )
    {
        $this->apiLoginManager =  $apiLoginManager;
    }


    public function login(Request $request)
    {
       $token = $this->apiLoginManager->loginUsuario($request);  
       return new JsonResponse($token);      
    }
}

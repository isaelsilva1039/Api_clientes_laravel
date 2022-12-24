<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use App\Manager\LoginManager\ApiRegisterManager;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    
    protected $apiregistrarUsuario ;

    public  function __construct(ApiRegisterManager $apiRegisterManager)
    {
        $this->apiregistrarUsuario = $apiRegisterManager;
    }


    public function register(Request $request, User $user)
    {
        $data = null;
        $statusCode = 200;

        try {
         
            $data =   $this->apiregistrarUsuario->registrarUsuario($request, $user);
          
        } catch (\Error $th) {
            // throw $th->getMessage('Erro Ao regitrar Usuario');
            $statusCode = 400;
            $data = 'Não registrado';
        }

        return new JsonResponse($data, $statusCode );
        
    }
    
}

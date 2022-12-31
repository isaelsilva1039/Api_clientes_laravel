<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use App\Manager\LoginManager\ApiRegisterManager;
use App\Models\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    
    protected $apiregistrarUsuario ;

    public  function __construct(ApiRegisterManager $apiRegisterManager)
    {
        $this->apiregistrarUsuario = $apiRegisterManager;
    }

    //registar novo usuario
    public function register(Request $request, User $user)
    {
        return new JsonResponse($this->apiregistrarUsuario->registrarUsuario($request, $user) );   
    }

    // editar usuario logado 
    public function editar(Request $request){
        return new JsonResponse ($this->apiregistrarUsuario->editarUsuario($request));
    }

    // usuario logado
    public function usuario(Request $request){
        return new JsonResponse($this->apiregistrarUsuario->pegarUsuarioPeloToken($request));
    }
    
}

<?php

namespace App\Http\Controllers\ApiControllerEscalas;

use App\Http\Controllers\Controller;
use App\Manager\ApiEscalaManager\ApiEscalaManager;
use App\Manager\LoginManager\ApiRegisterManager;
use App\Models\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiControllerEscala extends Controller
{
    
    protected $apiEscalaManager ;

    public  function __construct(ApiEscalaManager $apiEscalaManager)
    {
        $this->apiEscalaManager = $apiEscalaManager;
    }


    public function escalaAction(Request $request, User $user)
    {
        return new JsonResponse($this->apiEscalaManager->escalaMedica( $request ));   
    }

 
    public function obetemEscalarPorId($id)
    {
        return new JsonResponse($this->apiEscalaManager->escalaMedicaPorId($id ));   
    }


     public function editarMedico(Request $request, $id)
     {
         return new JsonResponse($this->apiEscalaManager->editarEscalaPorId( $request,$id ));   
     }


     public function excluirEscala(Request $request)
     {
         return new JsonResponse($this->apiEscalaManager->excluirEscalaPorId( $request));   
     }

     public function criarEscala(Request $request)
     {
         return new JsonResponse($this->apiEscalaManager->criarNovaEscala( $request));   
     }


    
}

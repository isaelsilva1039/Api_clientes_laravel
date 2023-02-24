<?php

namespace App\Http\Controllers\ApiMembros;

use App\Http\Controllers\Controller;
use App\Manager\ApiEscalaManager\ApiEscalaManager;
use App\Manager\ApiMembrosManager\ApiMembrosManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiControllerMembros extends Controller
{
    
    protected $apiMembrosManager ;

    public  function __construct(ApiMembrosManager $apiMembrosManager)
    {
        $this->apiMembrosManager = $apiMembrosManager;
    }


    public function indexMembros(Request $request){
        return new JsonResponse($this->apiMembrosManager->membros($request));
    }


    public function createMembroNovo(Request $request){
        return new JsonResponse($this->apiMembrosManager->novoMembros($request));
    }



    
}

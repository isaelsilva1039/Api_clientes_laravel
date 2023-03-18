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
    
    public function exibirAnexoAction($id){
        return new JsonResponse($this->apiMembrosManager->exibirAnexo($id));
    }


    public function createMembroNovo(Request $request){
        return new JsonResponse($this->apiMembrosManager->novoMembros($request));
    }

    public function buscarPorNomeMembro(Request $request)
    {
        return new JsonResponse($this->apiMembrosManager->buscarPorNome($request));
    }

    public function buscarPorId($id)
    {
        return new JsonResponse($this->apiMembrosManager->buscarPorId($id));
    }

    public function editarMembro($id, Request $request)
    {
        return new JsonResponse($this->apiMembrosManager->editar($id, $request));
    }

    public function deletarMembro($id)
    {
        return new JsonResponse($this->apiMembrosManager->delete($id));
    }

    public function salvarAnexoParaUmMembro(Request $request,$id)
    {
        return new JsonResponse($this->apiMembrosManager->salvarAnexo($request,$id));
    }


    
}

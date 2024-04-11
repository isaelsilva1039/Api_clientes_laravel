<?php

namespace App\Http\Controllers\ApiAsaassController;

use App\Http\Controllers\Controller;


;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Manager\ApiAsaasManager\ApiAsaasManager;

class ApiAsaasController extends Controller
{
    
    protected $apiAsaasManager ;

    public  function __construct(ApiAsaasManager $apiAsaasManager)
    {
        $this->apiAsaasManager = $apiAsaasManager;
    }

    public function indexCliente(Request $request){
        return new JsonResponse($this->apiAsaasManager->novoCliente($request));
    }
    
}

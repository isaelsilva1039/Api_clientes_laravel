<?php

namespace App\Http\Controllers\Tipo;

use App\Http\Controllers\Controller;
use App\Manager\ApiManagerIgrejas\ApiManagreIgreja;
use App\Manager\Tipo\ApiManagerTipo;

;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TipoController extends Controller
{
    
    protected $apiManagreTipo ;

    public  function __construct(ApiManagerTipo $apiManagreTipo)
    {
        $this->apiManagreTipo = $apiManagreTipo;
    }


    public function indexAllTipos(Request $request){
        return new JsonResponse($this->apiManagreTipo->IndexAll($request));
    }

    
}

<?php

namespace App\Http\Controllers\Profissional;

use App\Http\Controllers\Controller;
use App\Manager\ApiProfissionalManager\ApiProfissionalManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class ProfissionalController extends Controller
{

    /** @var ApiProfissionalManager @apiProfissionalManager */
    protected $apiProfissionalManager ;

    
    public  function __construct(ApiProfissionalManager $apiProfissionalManager)
    {
        $this->apiProfissionalManager = $apiProfissionalManager;
    }




    public function create(Request $request ){
        

        return new JsonResponse($this->apiProfissionalManager->store($request));
    }


    public function avatar($id){
        
        return new JsonResponse($this->apiProfissionalManager->exibirAvatar($id));
    }


    
}

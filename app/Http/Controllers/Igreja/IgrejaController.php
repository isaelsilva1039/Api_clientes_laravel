<?php

namespace App\Http\Controllers\Igreja;

use App\Http\Controllers\Controller;
use App\Manager\ApiManagerIgrejas\ApiManagreIgreja;

;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IgrejaController extends Controller
{
    
    protected $apiManagreIgreja ;

    public  function __construct(ApiManagreIgreja $apiManagreIgreja)
    {
        $this->apiManagreIgreja = $apiManagreIgreja;
    }


    public function indexAllIgrejas(Request $request){
        return new JsonResponse($this->apiManagreIgreja->IndexAll($request));
    }

    
}

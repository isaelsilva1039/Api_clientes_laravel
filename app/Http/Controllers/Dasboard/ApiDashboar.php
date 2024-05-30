<?php

namespace App\Http\Controllers\Dasboard;

use App\Http\Controllers\Controller;
use App\Manager\ApiEscalaManager\ApiEscalaManager;
use App\Manager\ApiManagerDashboard\ApiManagerDashboard;
use App\Manager\ApiMembrosManager\ApiMembrosManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiDashboar extends Controller
{
    
    protected $apiManagerDashboard ;

    
    public  function __construct(ApiManagerDashboard $apiManagerDashboard)
    {
        $this->apiManagerDashboard = $apiManagerDashboard;
    }


    public function index(Request $request){
        return new JsonResponse($this->apiManagerDashboard->index($request));
    }
    

    
}

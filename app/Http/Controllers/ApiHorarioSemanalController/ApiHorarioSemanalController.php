<?php

namespace App\Http\Controllers\ApiHorarioSemanalController;

use App\Http\Controllers\Controller;
use App\Manager\ApiHorariosManager\ApiHorariosManager;
use App\Models\horarios\HorarioSemanal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class ApiHorarioSemanalController extends Controller
{
    
    public $apiHorariosManager;

    public function __construct(ApiHorariosManager $apiHorariosManager) {
       $this->apiHorariosManager =  $apiHorariosManager;
    }


    public function create(Request $request) {
        return new JsonResponse($this->apiHorariosManager->store($request)); 
    }
}

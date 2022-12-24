<?php

namespace App\Http\Controllers\Ocorencia;

use App\Http\Controllers\Controller;
use App\Manager\ManagerOcorrencia\ManagerVeiculo;
use App\Models\Ocorencia\Veiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class VeiculoController extends Controller
{
    private $managerVeiculos; 
    
    public function __construct(ManagerVeiculo $managerVeiculo)
    {
        $this->managerVeiculos =  $managerVeiculo;
    }


    public function indexAction()
    {
        return Veiculo::all();
    }

    
    public function indexStore(Request $request)
    {
      $managerVeiculo = $this->managerVeiculos->novoVeiculo($request);
      return new JsonResponse($managerVeiculo);
    }

}

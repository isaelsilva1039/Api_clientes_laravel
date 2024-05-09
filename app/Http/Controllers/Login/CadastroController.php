<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use App\Manager\CadastrosClientes\ApiManagerCadastro;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class CadastroController extends Controller
{

    protected $apiCadastroCliente ;

    public  function __construct(ApiManagerCadastro $ApiManagerCadastro)
    {
        $this->apiCadastroCliente = $ApiManagerCadastro;
    }


    public function create(Request $request)
    {
        $data = null;
        $statusCode = 200;

        try {
         
            $data =   $this->apiCadastroCliente->novoCadastro($request);
          
        } catch (\Error $th) {
            // throw $th->getMessage('Erro Ao regitrar Usuario');
            $statusCode = 400;
            $data = 'Não registrado';
        }

        return new JsonResponse($data, $statusCode );
    }


    public function a(Request $request)
    {
       return 'aaaa' ;
    }
   
}

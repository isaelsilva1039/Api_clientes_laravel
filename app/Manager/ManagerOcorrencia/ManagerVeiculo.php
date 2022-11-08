<?php 
namespace App\Manager\ManagerOcorrencia;

use App\Http\Controllers\Controller;
use App\Models\Ocorencia\Veiculo;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\HttpFoundation\JsonResponse;

class ManagerVeiculo extends Controller{
    
    // serviço inserir  
    public function novoVeiculo($request){
        $status_code = 200;
       
        try {
            // valida dados da request
            $request->validate(['carro' => 'required','placa' => 'required','stado' => 'required','cidade' => 'required','endereco'  => 'required']);

            $user['user_id'] =  auth()->user()->id;
            $request = $request->all();
            $request = array_merge($user,$request);
            $veiculo = Veiculo::create($request); 

            $respon = ['Veiculo' => $veiculo ,"status_code" => $status_code =200];
        
        } catch (\Throwable $e) {
            $respon=["Error" => $e->getMessage() , "status_code" => $status_code = 400]; 
        }
        
        return $respon;
    }
}
?>
<?php 
namespace App\Manager\CadastrosClientes;

use App\Http\Controllers\Controller;
use App\Models\CadastrosCliente\Cadastro;

class ApiManagerCadastro extends Controller{

    // micros serviços para usar em qual quer controler   
    public function novoCadastro($request){
        $status_code = 200;
       
        try {
           // valida dados da request
            // $request->validate(['nome' => 'required','sobrenome' => 'required','cpf' => 'required','data_nacimento' => 'required']);
 
            $user['user_id'] =  auth()->user()->id;
            $request = $request->all();
            $request = array_merge($user,$request);
            
            $cadastro = Cadastro::create($request); 

            $respon = ['dados_cadastrado' => $cadastro ,"status_code" => $status_code =200];
        
        } catch (\Throwable $e) {
            $respon=["Error" => $e->getMessage() , "status_code" => $status_code = 400]; 
        }
        
        return $respon;
    }
}
?>
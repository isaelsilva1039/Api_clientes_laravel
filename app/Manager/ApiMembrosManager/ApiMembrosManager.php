<?php 
namespace App\Manager\ApiMembrosManager;

use App\Http\Controllers\Controller;
use App\Models\CadastroMembros\Membro;
use App\Models\CadastrosCliente\Cadastro;
use App\Models\EscalasMedicos\Escala;

class ApiMembrosManager extends Controller{

    // pegar todas as esclas   
    public function membros($request){
        $status_code = 200;
       
        try {
            $membro = Membro::all();
            $respon = ['Membros' => $membro ,"status_code" => $status_code ];
        
        } catch (\Throwable $e) {
            $respon=["Error" => $e->getMessage() , "status_code" => $status_code = 400]; 
        }
        
        return $respon;
    }



    // pegar todas as esclas   
    public function novoMembros($request){
        $status_code = 200;
       
        try {
            $membro = $request->all();

            $membro = Membro::create($membro);

            $respon = ['Membro' => $membro ,"status_code" => $status_code];
        
        } catch (\Throwable $e) {
            $respon=["Error" => $e->getMessage() , "status_code" => $status_code = 400]; 
        }
        
        return $respon;
    }

}
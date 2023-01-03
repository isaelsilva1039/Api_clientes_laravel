<?php 
namespace App\Manager\ApiEscalaManager;

use App\Http\Controllers\Controller;
use App\Models\CadastrosCliente\Cadastro;
use App\Models\EscalasMedicos\Escala;

class ApiEscalaManager extends Controller{

    // pegar todas as esclas   
    public function escalaMedica($request){
        $status_code = 200;
       
        try {
            $escalas = Escala::all();
            $respon = ['escala' => $escalas ,"status_code" => $status_code =200];
        
        } catch (\Throwable $e) {
            $respon=["Error" => $e->getMessage() , "status_code" => $status_code = 400]; 
        }
        
        return $respon;
    }


      // pegar apenas uma escala de um medico  
      public function escalaMedicaPorId($id){
        $status_code = 200;
       
        try {
            $escalas = Escala::findOrfail($id);
            $respon =  $escalas;
        
        } catch (\Throwable $e) {
            $respon=["Error" => $e->getMessage() , "status_code" => $status_code = 400]; 
        }
        
        return $respon;
    }


    
     public function editarEscalaPorId($request, $id){
        $status_code = 200;
       
        try {
            $medicoRequest = $request->all();
            $medico = $this->escalaMedicaPorId($id);
            
            $medicoAtualizado = $medico->update($medicoRequest);

            $respon = ['escala' => $medico ,"status_code" => $status_code =200];
        
        } catch (\Throwable $e) {
            $respon=["Error" => $e->getMessage() , "status_code" => $status_code = 400]; 
        }
        
        return $respon;
    }

       // pegar apenas uma escala de um medico  
       public function excluirEscalaPorId($request){
        $status_code = 200;
       
        try {
            $escalaRequeste = $request->id;

            $escalaAserescluida = Escala::destroy($escalaRequeste);

            $respon = ['escala' => $escalaRequeste ,"status_code" => $status_code =200];
        
        } catch (\Throwable $e) {
            $respon=["Error" => $e->getMessage() , "status_code" => $status_code = 400]; 
        }
        
        return $respon;
    }
}
?>
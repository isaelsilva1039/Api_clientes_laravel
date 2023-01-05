<?php 
namespace App\Manager\ApiGroupsManager;

use App\Http\Controllers\Controller;
use App\Models\CadastrosCliente\Cadastro;
use App\Models\EscalasMedicos\Escala;
use App\Models\Groups\Group;

class ApiGroupManager extends Controller{

    // pegar todas as esclas   
    public function obtemGroups($request){
       
        try {
            $status_code = 200;

            $groupos = Group::all();
            
            $respon = [
                'grupos' => $groupos ,
                "status_code" => $status_code 
            ];
        
        } catch (\Throwable $e) {
            $respon=["Error" => $e->getMessage() , "status_code" => $status_code = 400]; 
        }
        
        return $respon;
    }



      public function obtemGroupsPorId($id){
        $status_code = 200;
        try {
            
            $group = Group::findOrfail($id);
            $respon =  $group;
        
        } catch (\Throwable $e) {
            $respon=["Error" => $e->getMessage() , "status_code" => $status_code = 400]; 
        }
        
        return $respon;
    }


    
     public function editarGroupPorId($request, $id){
        
       
        try {
            $status_code = 200;
            $grupsResquest = $request->all();
            $groups = $this->obtemGroupsPorId($id);
            
            $groupsAtualizado = $groups->update($grupsResquest);

            $respon = ['groups' => $groups ,"status_code" => $status_code];
        
        } catch (\Throwable $e) {
            $respon=["Error" => $e->getMessage() , "status_code" => $status_code = 400]; 
        }
        
        return $respon;
    }

       // pegar apenas uma escala de um medico  
       public function excluirGroupPorId($id){
        
        try {
            $status_code = 200;
            $grupsResquest = $this->obtemGroupsPorId($id);

            $groupsAtualizado = Group::destroy($grupsResquest['id']);

            $respon = ['groups' => $grupsResquest ,"status_code" => $status_code];
        
        } catch (\Throwable $e) {
            $respon=["Error" => $e->getMessage() , "status_code" => $status_code = 400]; 
        }
        
        return $respon;
    }



      // pegar apenas uma escala de um medico  
      public function criarNovaGroup($request){
        $status_code = 200;
       
        try {
            $status_code = 200;
            $novoGroup = $request->all();

            $group = Group::create($novoGroup);

            $respon = ['groups' => $novoGroup ,"status_code" => $status_code];
        
        } catch (\Throwable $e) {
            $respon=["Error" => $e->getMessage() , "status_code" => $status_code = 400]; 
        }
        
        return $respon;
    }
}
?>
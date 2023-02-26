<?php 
namespace App\Manager\ApiMembrosManager;

use App\Http\Controllers\Controller;
use App\Models\CadastroMembros\Membro;
use App\Models\CadastrosCliente\Cadastro;
use App\Models\EscalasMedicos\Escala;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ApiMembrosManager extends Controller{

    // pegar todas as esclas   
    public function membros($request){

      
        $status_code = 200;
        try {
            $membro = Membro::with('igreja', 'tipo')->get();
            $respon = ['Membros' => $membro ,
            "status_code" => $status_code 
        ];
        
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
            $respon = [
                'Membro' => $membro ,
                "status_code" => $status_code
            ];
        
        } catch (\Throwable $e) {
            $respon=["Error" => $e->getMessage() , "status_code" => $status_code = 400]; 
        }
        
        return $respon;
    }


    public function buscarPorNome(Request $request){
        $status_code = 200;
       
        try {
            $nome = $request->input('nome');
            $membro = Membro::with('igreja', 'tipo')
                ->where('nome_membro', 'like', '%'.$nome.'%')
                ->get();

            $respon = [
                'Membro' => $membro ,
                "status_code" => $status_code
            ];
        
        } catch (\Throwable $e) {
            $respon=["Erro ao buscar por nome" => $e->getMessage() , "status_code" => $status_code = 400]; 
        }
        
        return $respon;
    }


    public function buscarPorId($id){
 
        $status_code = 200;
            try {

                $membro = Membro::with('igreja', 'tipo')->findOrFail($id); 
                $respon = $membro;
            
            } catch (\Throwable $e) {
                $respon=["Error" => $e->getMessage() , "status_code" => $status_code = 400]; 
            }
            
            return $respon;
        }


    public function editar($id, $request){
        $status_code = 400;
        try {
            $dadosAseremAtualizadoDoMembro = $request->all();
            $membro = $this->buscarPorId($id);
            
            $membroAtualidado = $membro->update($dadosAseremAtualizadoDoMembro);
            $status_code = 200;
            $respon = ['Membro' => $membroAtualidado ,"status_code" => $status_code];
        
        } catch (\Throwable $e) {
            $respon=["Error" => $e->getMessage() , "status_code" => $status_code]; 
        }

        return $respon;
    }


    public function delete($id){

          try{
            $membroAserExcluido = $this->buscarPorId($id);
            $membro = Membro::destroy($id);
            $respon = ['Excluido' => $membroAserExcluido ,"status_code" => $status_code =200];
          }catch(\Throwable $e){
            $respon=["Error" => $e->getMessage() , "status_code" => $status_code = 400]; 
          }

        return $respon;
    }

}
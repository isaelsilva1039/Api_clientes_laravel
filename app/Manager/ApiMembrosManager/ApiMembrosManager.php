<?php 
namespace App\Manager\ApiMembrosManager;

use App\Http\Controllers\Controller;
use App\Models\CadastroMembros\Anexo;
use App\Models\CadastroMembros\Membro;
use App\Models\CadastrosCliente\Cadastro;
use App\Models\EscalasMedicos\Escala;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
use Exception;

class ApiMembrosManager extends Controller{

    // pegar todas as esclas   
    public function membros($request){

        $status_code = 200;
        try {
            $membro = Membro::with('igreja', 'tipo','anexo')->get()->sortByDesc('id')->values();
                $respon = ['Membros' => $membro ,
                "status_code" => $status_code 
            ];
        
        } catch (\Throwable $e) {
            $respon=["Error" => $e->getMessage() , "status_code" => $status_code = 400]; 
        }
        
        return $respon;
    }



   // pegar todas as esclas   
public function novoMembros(Request $request){
        
    $status_code = 200;
       
    try {
        $membro = $request->all();
        // $anexo = $request->file('file');

        // $path = $anexo->store('anexos');
        // $url  = Storage::url($path);

        // $anexo = Anexo::create(
        //     ['path' => $path,
        //      'name' => $anexo->getClientOriginalName(),
        //      'url' => $url

        // ]);

        // $membro['fk_anexo'] = $anexo->id;

        $membro = Membro::create($membro);

        $respon = [
            'Membro' => $membro,
            'status_code' => $status_code
        ];
        
    } catch (\Throwable $e) {
        $respon=["Error" => $e->getMessage() , "status_code" => $status_code = 400]; 
    }
        
        return $respon;
    }



    public function salvarAnexo(Request $request,$id){
        
        try {
            $anexo = $request->file('file');

            $path = $anexo->store('anexos');
        
            $url  = Storage::url($path);

            $anexo = Anexo::create(
                ['path' => $path,
                'name' => $anexo->getClientOriginalName(),
                'url' => $url

            ]);

            $membroAserEditadoOfkAnexo = $this->buscarPorId($id);
            $membroAserEditadoOfkAnexo->fk_anexo = $anexo->id;
            $respon=
                [
                    'salvo' =>   $membroAserEditadoOfkAnexo->save(),
                    'fk_anexo' => $membroAserEditadoOfkAnexo->fk_anexo
                ];
        } catch (\Throwable $e) {
            $respon=["Error" => $e->getMessage() , "status_code" => $status_code = 400]; 
        }
            
            return $respon;
         
    }
    
    public function exibirAnexo($id)
    {

        try {

            $anexo = Anexo::findOrFail($id);   

            header('Content-Type: image/png');
    
            $url = storage_path('../app/' . $anexo->path);
            
            file_exists($url) ? null : throw new Exception('Arquivo não encontrado no caminho: ' . $url);

            header('Content-Disposition: attachment; filename=' . $anexo->name);

            return readfile($url);

        } catch (\Throwable $e) {
            return  $respon=["Error" => $e->getMessage() , "status_code" => $status_code = 400]; 
            
        }

        

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
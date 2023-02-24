<?php 
namespace App\Manager\Tipo;

use App\Http\Controllers\Controller;
use App\Models\Tipo\Tipo;

class ApiManagerTipo extends Controller{

    // pegar todas as esclas   
    public function IndexAll($request){
        $status_code = 200;
       
        try {
            $tipos = Tipo::all();
            $respon = ['Cargos' => $tipos ,"status_code" => $status_code ];
        
        } catch (\Throwable $e) {
            $respon=["Error" => $e->getMessage() , "status_code" => $status_code = 400]; 
        }
        
        return $respon;
    }
}
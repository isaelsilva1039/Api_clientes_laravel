<?php 
namespace App\Manager\ApiManagerIgrejas;

use App\Http\Controllers\Controller;
use App\Models\Igreja\Igreja;

class ApiManagreIgreja extends Controller{

    // pegar todas as esclas   
    public function IndexAll($request){
        $status_code = 200;
       
        try {
            $igrejas = Igreja::all();
            $respon = ['Igrejas' => $igrejas ,"status_code" => $status_code ];
        
        } catch (\Throwable $e) {
            $respon=["Error" => $e->getMessage() , "status_code" => $status_code = 400]; 
        }
        
        return $respon;
    }
}
<?php 
namespace App\Manager\LoginManager;

use App\Http\Controllers\Controller;
use App\Models\Ocorencia\Veiculo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiLoginManager extends Controller{

    // micros serviços para usar em qual quer controler   
    public function loginUsuario(Request $request){
        $status_code = 400;


        try {
            
            $credemciais = $request->only('email', 'password');
            
            // Caso o usuario não exista
            if(!auth()->attempt($credemciais)) {
                return Response ([
                    'result' => [
                        'status_code' => $status_code
                    ]
                ]);
            }
                    
            
            $token = $request->user()->createToken("auth_token");
            
            return Response ([
                    'token' => $token->plainTextToken,
                    'user' => auth()->user(),
                    'status_code' => $status_code = 200
                ]);
        
        } catch (\Throwable $th) {
            throw $th;
        }

    }

       
}
?>
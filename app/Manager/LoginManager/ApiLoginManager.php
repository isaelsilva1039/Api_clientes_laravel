<?php 
namespace App\Manager\LoginManager;

use App\Http\Controllers\Controller;
use App\Models\Ocorencia\Veiculo;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiLoginManager extends Controller{

    // micros serviços para usar em qual quer controler   
    public function loginUsuario(Request $request){
       
        $data=[];

        try {
            $status_code = 200;
            $credemciais = $request->only('email', 'password');

            // Caso o usuario não exista
            if(!auth()->attempt($credemciais)) {
                 $data = [
                    'mensagem' => 'E-mail e/ou senha incorreta',
                    'status' =>  'error',
                    'status_code' => $status_code = 200
                ];
            }
                    
            
            $token = $request->user()->createToken("auth_token");
            
             $data = [
                    'token' => $token->plainTextToken,
                    'user' => auth()->user(),
                    'avatar' => auth()->user()->fk_anexo ? route('profissional.avatar', ['id' => auth()->user()->fk_anexo]) : null,
                    'status_code' => $status_code = 200
                    
                ];
        
        } catch (\Throwable $th) {
            $data['mensagem'] = 'Email ou senha errado';
            $data['status_code'] =  500;
           
        }

        return $data;

    }

       
    public function getMe(Request $request)
    {
        $user = Auth::user();
        
        /** @var User $user */
        return [
            'user' => $user->makeHidden(['clientes', 'consultas']),
            'clientes' => $user->clientes()->get(),
            'consultas' => $user->consultas()->get()
        ];
    }
    
}
?>
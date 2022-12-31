<?php 
namespace App\Manager\LoginManager;

use App\Http\Controllers\Controller;
use App\Models\Ocorencia\Veiculo;
use App\Models\User;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiRegisterManager extends Controller{

    // micros serviços para usar em qual quer controler   
    public function registrarUsuario(Request $request, User $user){
        $userData = $request->only('name', 'email', 'password');
        $userData['password'] = bcrypt($userData['password']);

        if(!$user = $user->create($userData))
            abort(500, 'Erro ao criar Usuario');

        return  [
            'result' => [
                'user' => $user,
            ]
        ];
    }

    // pega usuario pelo id do usuario
    public function usuarioLogado($id){
        return User::findOrfail($id);
    }

    // pegaUsuarioPeloToken
    public function pegarUsuarioPeloToken(Request $request){
        return auth()->user();
    }


    // edita usuario
    public function editarUsuario(Request $request){
      
        $id = auth()->user()->id;
        
        $usuario = $this->usuarioLogado($id);
        
        $requestJson = $request->all();
        
        $updateUsuario = $usuario->update($requestJson);
        
        return (
            [
                "Clinente " => $usuario,
                "mensagem " => "Cliente Atualizado com sucesso",
                "code"      => 200,
            ]);
    }
}
?>
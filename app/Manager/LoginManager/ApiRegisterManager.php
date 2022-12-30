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
}
?>
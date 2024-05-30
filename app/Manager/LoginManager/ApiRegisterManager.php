<?php 
namespace App\Manager\LoginManager;

use App\Http\Controllers\Controller;
use App\Manager\ApiProfissionalManager\ApiProfissionalManager;
use App\Models\CadastroMembros\Anexo;
use App\Models\Ocorencia\Veiculo;
use App\Models\User;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiRegisterManager extends Controller{

    public $apiProfissionalManager;

    public function __construct(ApiProfissionalManager $apiProfissionalManager) {
        $this->apiProfissionalManager = $apiProfissionalManager;
    }
    // micros serviços para usar em qual quer controler   
    public function registrarUsuario(Request $request, User $user){
        
        try {
            $status_code = 200;
            $userData = $request->only('name', 'email', 'password' , 'tipo', 'cpf');


            /** @var Anexo $avatar */
            $avatar = $this->apiProfissionalManager->salvarAvatarProfissional($request);

            

            $userData['password'] = bcrypt($userData['password']);

            if(!$user = $user->create($userData + ['fk_anexo' => $avatar->id ?? null]))
                abort(500, 'Erro ao criar Usuario');

         
            $data = [
                'result' => [
                    'user' => $user,
                    'status_code' => $status_code
                ]
            ];

        } catch (\Throwable $th) {
            $data = [
                'mensagem' => 'erro ao registrar novo usuario',
                'status_code' => $status_code = 400
            ];
        }

        return $data;
        
    }

    // pega usuario pelo id do usuario
    public function usuarioLogado($id){
        return User::findOrfail($id);
    }

    // pegaUsuarioPeloToken
    public function pegarUsuarioPeloToken(Request $request){
        try {
            $status_code = 200;

            $data = [
                'user' => auth()->user(),
                'status_code' => $status_code
            ];
        } catch (\Throwable $th) {
            $data = [
                'mensagem' => 'erro ao recuperar dados do usuario',
                'status_code' => $status_code
            ];
        }

        return $data;
    }


    // edita usuario
    public function editarUsuario(Request $request){
      
       try {
        $status_code = 200;
        $id = auth()->user()->id;
        
        $usuario = $this->usuarioLogado($id);
        
        $requestJson = $request->all();

        if($requestJson == null){
            return $data = [
                'mensagem' => 'você precisa mandar pelo menos uma informação para essa api',
                'status_code' => 400
            ];
        }
        
        $updateUsuario = $usuario->update($requestJson);
        
        $data = 
            [
                "user " => $usuario,
                "mensagem " => "Cliente Atualizado com sucesso",
                "status_code"      => $status_code,
            ];
       } catch (\Throwable $th) {

        $data = [
            'mensagem' => 'erro ao editar usuario',
            'status_code' => $status_code
        ];
       }
       return $data;
    }



}
?>
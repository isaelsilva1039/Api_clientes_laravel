<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\JsonResponse as HttpJsonResponse;
use PhpParser\Node\Stmt\Return_;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Service\Attribute\Required;

class ClienteController extends Controller
{
  

    public function index()
    {
        return Cliente::all();
    }

  
    public function store(Request $request): Response
    {
        $code = 200;
        $mensagem = "Cadastros relaizado sucess";

        try {
              // validar se na request todos esses campo exite
                $request->validate([
                    'motorista' => 'required',
                    'carro'     => 'required',
                    'placa'     => 'required',
                    'stado'     => 'required',
                    'cidade'    => 'required',
                    'endereco'  => 'required'
                ]);
            
                $cliente = Cliente::create($request->all());
            
                return Response(
                    [
                        "Cliente" => $cliente,
                        "Mensagem" => $mensagem,
                        "Status Code" => $code
                    ]
                );
        
         } catch (\Throwable $th) {
            
                $code = 400;
                $mensagem = "Erro dados nao preenchidos";
            
                return Response(
                    [
                        "statusCode" => $code,
                        "mensagem"   =>$mensagem
                    ]
                 );
        
        }
     

    }



    public function show($id)
    {
        try {
            return Response ([Cliente::findOrfail($id)]);
        
        } catch (\Throwable $th) {
            return Response(["code" => 400, "mensagem" =>"Nao foi encontrado nem um resgistro"]);            
        }
    }

   
    

    public function update(Request $request, $id)
    {

        try {

            $requestJson = $request->all();

            $clienteLocalizado = $this->show($id);

            $clienteLocalizado->update($requestJson);
        
            return Response(
                [
                    "Clinente " => $clienteLocalizado,
                    "mensagem " => "Cliente Atualizado com sucesso",
                    "code"      => 200,
                ]
            );

        } catch (\Throwable $th) {

            return Response(
                [
                    "mensagem " => "Erro ao atualizar Cliente",
                    "code"      => 400,
                ]
            );
        }
        
        
    }

    


    public function destroy($id)
    {
        try {
            
            $this->show($id)->delete();
            return Response(["mensagem" => "Cliente deletado com sucesso", "code "=> 200]);

        } catch (\Throwable $th) {

            return Response(["mensagem " => "Erro ao deletar " ,"code" => 400]);
        }

    }
}

<?php
namespace App\Manager\CadastrosClientes;

use App\Http\Controllers\Controller;
use App\Models\CadastrosCliente\Cadastro;
use App\Models\Cliente;

class ApiManagerCadastro extends Controller
{
    

    /**
     * Insere um novo registro no banco de dados baseado no modelo e nos dados fornecidos.
     *
     * @param array $data Dados para criar um novo registro.
     * @param string $modelClass Classe do modelo para criação do registro.
     * @return array Resposta com os dados inseridos ou uma mensagem de erro.
     */
    public function inserirNoBanco(array $data)
    {
        $status_code = 200;

        try {
       
            $registro = Cliente::create($data);

            $resposta = ['dados_cadastrados' => $registro, "status_code" => 200];
        } catch (\Throwable $e) {
  
            $resposta = ["Error" => $e->getMessage(), "status_code" => 400];
        }

        return $resposta;
    }

}
?>
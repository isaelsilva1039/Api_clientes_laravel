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
     * @param string $id_cliente_assas id do cliente no assas -> sistema financeiro.
     * @return Cliente
     */
    public function inserirNoBanco(array $data, $id_cliente_assas)
    {
        $status_code = 200;

        try {
            
            // Adiciona o ID do cliente do Asaas aos dados antes de inserir no banco
            $data['id_cliente_assas'] = $id_cliente_assas;
            
            $registro = Cliente::create($data);


        } catch (\Throwable $e) {
  
            $registro = ["Error" => $e->getMessage(), "status_code" => 400];
        }

        return $registro;
    }

}
?>
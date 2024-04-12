<?php
namespace App\Manager\ApiAsaasManager;

use App\Http\Controllers\Controller;
use App\Manager\CadastrosClientes\ApiManagerCadastro;
use App\Models\CadastrosCliente\Cadastro;
use App\Models\Igreja\Igreja;
use CodePhix\Asaas\Asaas;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiAsaasManager extends Controller
{

    // TODO: Remover essa chave daqui e levar para o env
    const CHAVE_API_ASSAS = '$aact_YTU5YTE0M2M2N2I4MTliNzk0YTI5N2U5MzdjNWZmNDQ6OjAwMDAwMDAwMDAwMDA0MTEwODU6OiRhYWNoXzE4MzNiYzc5LWVlZDYtNGNjNS1iMzQ5LWExMjZiNGRkYzlkZA==';


    /**
     * @var ApiManagerCadastro $apiManagerCadastro 
     */
    protected $apiManagerCadastro;


    /**
     * @param ApiManagerCadastro $apiManagerCadastro 
     */
    public function __construct(ApiManagerCadastro $apiManagerCadastro)
    {
        $this->apiManagerCadastro = $apiManagerCadastro;
    }


    public function novoCliente(Request $request)
    {
        $billing = $request->input('billing', []);
        $status = $request->input('status');
        $lineItems = $request->input('line_items', []);

        // Logar os dados de entrada para fins de depuração (assegure-se de não logar dados sensíveis em produção)
        Log::info('Request billing data: ' . json_encode($billing));
        Log::info('Request line items: ' . json_encode($lineItems));

        
        $asaas = new Asaas(self::CHAVE_API_ASSAS);

        try {

            if ($status !== 'completed') {
                Log::info('Pedido não está completo. Status atual: ' . $status);
                return response()->json(['error' => 'Pedido não está completo, não é possível processar.'], 400);
            }
    

            $productName = $lineItems[0]['name'] ?? 'Produto não especificado';
            $observations = "Cliente importado do sistema X - Plano: {$productName}";

            $dados = [
                "name" => ($billing['first_name'] ?? 'Nome não informado') . ' ' . ($billing['last_name'] ?? ''),
                "email" => $billing['email'] ?? 'email@padrao.com',
                "phone" => $billing['phone'] ?? '',
                "mobilePhone" => $billing['cellphone'] ?? '',
                "cpfCnpj" => $billing['cpf'] ?? ($billing['cnpj'] ?? 'CPF/CNPJ não informado'),
                "postalCode" => $billing['postcode'] ?? '',
                "address" => $billing['address_1'] ?? '',
                "addressNumber" => $billing['number'] ?? '',
                "complement" => $billing['address_2'] ?? '',
                "province" => $billing['neighborhood'] ?? '',
                "externalReference" => $request->input('id', ''),
                "notificationDisabled" => !$request->input('is_paying_customer', true),
                "observations" => $observations,
            ];

            $data = $asaas->Cliente()->create($dados);
            if ($data) {
                $data = $this->apiManagerCadastro->inserirNoBanco($dados);
            }
        } catch (\Throwable $e) {
            Log::error('Erro ao criar cliente: ' . $e->getMessage());
            return response()->json(['error' => 'Falha na requisição', 'details' => $e->getMessage()], 500);
        }

        return $data;
    }



}
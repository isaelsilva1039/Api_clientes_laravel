<?php
namespace App\Manager\ApiAsaasManager;

use App\Http\Controllers\Controller;
use App\Manager\ApiAsaasManager\DependentDTO\DependentDTO;
use App\Manager\CadastrosClientes\ApiManagerCadastro;
use App\Models\CadastrosCliente\Cadastro;
use App\Models\Dependente\Dependente;
use App\Models\Igreja\Igreja;
use Carbon\Carbon;
use CodePhix\Asaas\Asaas;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class ApiAsaasManager extends Controller
{

    // TODO: Remover essa chave daqui e levar para o env
    const CHAVE_API_ASSAS = '$aact_YTU5YTE0M2M2N2I4MTliNzk0YTI5N2U5MzdjNWZmNDQ6OjAwMDAwMDAwMDAwMDA0MTEwODU6OiRhYWNoXzE4MzNiYzc5LWVlZDYtNGNjNS1iMzQ5LWExMjZiNGRkYzlkZA==';
    //  const CHAVE_API_ASSAS = '$aact_YTU5YTE0M2M2N2I4MTliNzk0YTI5N2U5MzdjNWZmNDQ6OjAwMDAwMDAwMDAwMDA0MTIwNTY6OiRhYWNoXzZiOWFkNmE2LWViOGItNGUwMC1hZDAwLTY2ODU3YmRkYmIxMg==';

    

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



    /**
     * Esse método é chamado no controller do webhook.
     */
    public function novoCliente(Request $request)
    {
        $cliente = [];
        $billing = $request->input('billing', []);
        $status = $request->input('status');
        $lineItems = $request->input('line_items', []);

        $total = $request->input('total');

        // Logar os dados de entrada para fins de depuração (assegure-se de não logar dados sensíveis em produção)
        Log::info('Request billing data: ' . json_encode($billing));
        Log::info('Request line items: ' . json_encode($lineItems));

        $client = new Client([
            'base_uri' => 'https://www.asaas.com/api/v3/',
            'headers' => [
                'Content-Type' => 'application/json',
                'access_token' => self::CHAVE_API_ASSAS,
            ]
        ]);

        try {
            if ($status !== 'completed') {
                Log::info('Pedido não está completo. Status atual: ' . $status);
                return response()->json(['error' => 'Pedido não está completo, não é possível processar.'], 400);
            }

            $productName = $lineItems[0]['name'] ?? 'Produto não especificado';

            $birthdate = trim($billing['birthdate'] ?? '');
            if ($birthdate) {
                $birthdate = Carbon::createFromFormat('m-d-Y\TH:i:s', $birthdate)->format('Y-m-d');
            }

            $observations = "Cliente importado do sistema X - Plano: {$productName} Data de nascimento: {$birthdate}";

            $dados = [
                "name" => ($billing['first_name'] ?? 'Nome não informado') . ' ' . ($billing['last_name'] ?? ''),
                "email" => $billing['email'] ?? 'email@padrao.com',
                "phone" => $billing['phone'] ?? '',
                "mobilePhone" => $billing['cellphone'] ?? '',
                "cpfCnpj" => $billing['cpf'] ?? ($billing['cnpj'] ?? 'CPF/CNPJ não informado'),
                "plano" => $productName,
                "postalCode" => $billing['postcode'] ?? '',
                "address" => $billing['address_1'] ?? '',
                "addressNumber" => $billing['number'] ?? '',
                "complement" => $billing['address_2'] ?? '',
                "province" => $billing['neighborhood'] ?? '',
                "externalReference" => $request->input('id', ''),
                "notificationDisabled" => !$request->input('is_paying_customer', true),
                "date_of_birth" => $birthdate,
                "total" => $total,
                "observations" => $observations,
            ];

            $response = $client->post('customers', [
                'json' => $dados
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            if (!isset($data['id'])) {
                throw new Exception('Falha ao obter ID do cliente da resposta da API.');
            }

            $id_cliente_assas = $data['id'];
            $cliente = $this->apiManagerCadastro->inserirNoBanco($dados, $id_cliente_assas, $productName);

            $this->createAssinatura($total, $id_cliente_assas);

            $dependentes = DependentDTO::fromRequest($lineItems);
            if ($dependentes) {
                foreach ($dependentes as $depData) {
                    $dataNascimento = Carbon::createFromFormat('d/m/Y', $depData['data_de_nascimento'])->format('Y-m-d');

                    $dependente = new Dependente([
                        'nome' => $depData['nome'],
                        'email' => $depData['email'],
                        'cpf' => $depData['cpf'],
                        'data_de_nascimento' => $dataNascimento,
                        'endereco' => $depData['endereco'],
                        'bairro' => $depData['bairro'],
                        'cidade' => $depData['cidade'],
                        'estado' => $depData['estado'],
                        'celular' => $depData['celular'],
                        'numero' => $depData['numero']
                    ]);

                    $cliente->dependentes()->save($dependente);
                }
            }

        } catch (GuzzleException $e) {
            Log::error('Erro ao criar cliente: ' . $e->getMessage());
            return response()->json(['error' => 'Falha na requisição', 'details' => $e->getMessage()], 500);
        }

        return $cliente;
    }


    public function createAssinatura(
        $total,
        $clienteId,
        $description = null,
        $tipo = 'BOLETO',
        $tipoCobransa = 'MONTHLY'

    ){

        $asaas = new Asaas(self::CHAVE_API_ASSAS);

             // Calcular a data de vencimento como hoje + 30 dias
        $dueDate = Carbon::now()->addDays(30)->format('Y-m-d');

        $dadosAssinatura = [
            'customer' => $clienteId,
            'billingType' => $tipo, // Exemplo: BOLETO, CREDIT_CARD, etc.
            'value' => $total, // Valor da assinatura
            'nextDueDate' => $dueDate, // Data do primeiro pagamento
            'cycle' => $tipoCobransa, // Frequência da assinatura, exemplo: MONTHLY, YEARLY, etc.
            'description' => $description
        ];
        
        $data  =  $asaas->Assinatura()->create($dadosAssinatura);

        return $data;

    }


}
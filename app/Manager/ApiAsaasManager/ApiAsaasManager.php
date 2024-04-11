<?php
namespace App\Manager\ApiAsaasManager;

use App\Http\Controllers\Controller;
use App\Models\Igreja\Igreja;
use CodePhix\Asaas\Asaas;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class ApiAsaasManager extends Controller
{


    public function novoCliente(Request $request)
    {
        $billing = $request->input('billing', []);
        $status = $request->input('status');
        $lineItems = $request->input('line_items', []);
    
        $apiKey = '$aact_YTU5YTE0M2M2N2I4MTliNzk0YTI5N2U5MzdjNWZmNDQ6OjAwMDAwMDAwMDAwMDA0MTEwODU6OiRhYWNoXzE4MzNiYzc5LWVlZDYtNGNjNS1iMzQ5LWExMjZiNGRkYzlkZA==';
        $asaas = new Asaas($apiKey);
    
        try {
            if ($status == 'completed' && !empty($lineItems)) {
                
                // Pegando o nome do primeiro produto - ajuste conforme necessário
                $productName = $lineItems[0]['name'] ?? 'Produto não especificado';
    
                $observations = "Cliente importado do sistema X - Plano: {$productName}";
    
                $data = $asaas->Cliente()->create([
                    "name" => ($billing['first_name'] ?? 'Usuário não informou o nome') . ' ' . ($billing['last_name'] ?? ''),
                    "email" => $billing['email'] ?? 'email@padrao.com',
                    "phone" => $billing['phone'] ?? '',
                    "mobilePhone" => $billing['cellphone'] ?? '',
                    "cpfCnpj" => $billing['cpf'] ?? ($billing['cnpj'] ?? '07761854386'),
                    "postalCode" => $billing['postcode'] ?? '',
                    "address" => $billing['address_1'] ?? '',
                    "addressNumber" => $billing['number'] ?? '',
                    "complement" => $billing['address_2'] ?? '',
                    "province" => $billing['neighborhood'] ?? '',
                    "externalReference" => $request->input('id', ''),
                    "notificationDisabled" => !$request->input('is_paying_customer', true),
                    "observations" => $observations,
                ]);
            }else {
                $data =  'Pagamento ainda está pendente';
            }
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            $data = response()->json(['error' => 'Falha na requisição', 'details' => $e->getMessage()], 500);
        }
    
        return $data;
    }
    

}
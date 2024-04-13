<?php
namespace App\Manager\ApiAsaasManager\DependentDTO;

use Spatie\DataTransferObject\DataTransferObject;

class DependentDTO extends DataTransferObject
{
    public ?string $nomeCompleto;
    public ?string $cpf;
    public ?string $dataNascimento;
    public ?string $endereco;
    public ?string $numero;
    public ?string $bairro;
    public ?string $cep;
    public ?string $cidade;
    public ?string $estado;
    public ?string $celular;
    public ?string $email;

    public static function fromRequest(array $data)
    {
        $metaData = $data[0]['meta_data']; // Considerando que o primeiro item é o relevante

        $displayValues = [];

        foreach ($metaData as $item) {
            if (isset($item['display_value'])) {
                // Verifica se display_value é um array e extrai apenas a parte 'value', se necessário
                $value = $item['display_value'];
                if (is_array($value) && isset($value['value'])) {
                    $displayValues[] = $value['value'];
                } else {
                    $displayValues[] = $value;
                }
            }
        }

        return self::organizeDependentData($displayValues);
        
      
    }


    public static function organizeDependentData($data) {
        $dependents = [];
        $currentDependent = null;
    
        foreach ($data as $value) {
            // Verificar se o valor é uma string e contém o padrão "Dependente"
            if (is_string($value) && preg_match('/Dependente (\d+)/', $value, $matches)) {
                $dependentNumber = $matches[1];
                $currentDependent = "Dependente $dependentNumber";
                if (!isset($dependents[$currentDependent])) {
                    $dependents[$currentDependent] = [];
                }
            }
            // Adicionar valor ao dependente atual se já foi identificado
            if ($currentDependent !== null) {
                $dependents[$currentDependent][] = $value;
            }
        }
        return self::extractEssentialData($dependents);
    }


    public static function extractEssentialData($data) {
        $essentialData = [];
    
        foreach ($data as $dependentKey => $dependentValues) {
            // Verificar se $dependentValues é realmente um array
            if (!is_array($dependentValues)) {
                continue;  // Se não for um array, pula para a próxima iteração
            }
    
            $extractedData = [
                'nome' => '',
                'email' => '',
                'cpf' => '',
                'data_de_nascimento' => '',
                'endereco' => '',
                'bairro' => '',
                'cidade' => '',
                'estado' => '',
                'celular' => '',
                'numero' => '' // Se precisar diferenciar entre celular e telefone fixo, caso contrário pode remover
            ];
    
            $length = count($dependentValues);
            for ($i = 0; $i < $length; $i += 2) {
                // Verifica se existe um índice válido para campo e valor
                if (isset($dependentValues[$i], $dependentValues[$i + 1])) {
                    $field = $dependentValues[$i];
                    $value = $dependentValues[$i + 1];
                    if (strpos($field, 'Nome Completo') !== false) {
                        $extractedData['nome'] = $value;
                    } elseif (strpos($field, 'Email') !== false) {
                        $extractedData['email'] = $value;
                    } elseif (strpos($field, 'CPF') !== false) {
                        $extractedData['cpf'] = $value;
                    } elseif (strpos($field, 'Data de Nascimento') !== false) {
                        $extractedData['data_de_nascimento'] = $value;
                    } elseif (strpos($field, 'Endereço') !== false) {
                        $extractedData['endereco'] = $value;
                    } elseif (strpos($field, 'Bairro') !== false) {
                        $extractedData['bairro'] = $value;
                    } elseif (strpos($field, 'Cidade') !== false) {
                        $extractedData['cidade'] = $value;
                    } elseif (strpos($field, 'Estado') !== false) {
                        $extractedData['estado'] = $value;
                    } elseif (strpos($field, 'Celular') !== false || strpos($field, 'Telefone Celular') !== false) {
                        $extractedData['celular'] = $value;
                    } elseif (strpos($field, 'Número') !== false && strpos($field, 'Celular') === false) {
                        $extractedData['numero'] = $value;
                    }
                }
            }
    
            // Armazenar os dados extraídos
            $essentialData[$dependentKey] = $extractedData;
        }
    
        return $essentialData;
    }
    
    
}
?>

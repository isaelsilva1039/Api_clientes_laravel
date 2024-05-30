<?php

namespace App\Manager\ApiManagerDashboard;

use App\Http\Controllers\Controller;
use App\Models\Agenda\Agendamento;
use App\Models\CadastroMembros\Membro;
use App\Models\Cliente;
use App\Models\Consultas\Consulta;
use App\Models\Profissional;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\DB;


class ApiManagerDashboard extends Controller
{

       
    public function index(Request $request)
    {
        $status_code = 200;
        $data = [];
    
        try {
            // Quantidade de clientes
            $data['quantidade_clientes'] = Cliente::count();
    
            // Quantidade de consultas pendentes e remarcadas na tabela 'agendamentos'
            $data['quantidade_consultas_pendentes_remarcadas'] = Agendamento::whereIn('status', ['pendente', 'remarcado'])->count();
    
            $data['quantidade_consultas_realizada'] = Agendamento::whereIn('status', ['realizado'])->count();

            // Quantidade de profissionais na tabela de profissionais
            $data['quantidade_profissionais'] = Profissional::count();
    
            // Quantidade de clientes com a quantidade de consultas expiradas
            // Supõe-se que exista uma coluna 'consultas_expiradas' ou algum critério para definir isso


            $data['clientes_com_consultas_expiradas'] = Consulta::whereColumn('quantidade_realizada', '=', 'quantidade_consultas')->count();

            
            
            // $data['clientes_com_consultas_expiradas'] = Cliente::whereHas('consultas', function ($query) {
            //     // Comparação direta entre colunas
            //     $query->where('quantidade_realizada', '=', 'quantidade_consultas');

            // })->count();
            
        } catch (\Throwable $e) {
            // Em caso de erro, ajustar o status_code e retornar a mensagem de erro
            $status_code = 500;
            $data = [
                'error' => 'Erro ao processar a requisição: ' . $e->getMessage()
            ];
        }
    
        return [$data, $status_code];
    }
    

}

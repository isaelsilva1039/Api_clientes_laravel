<?php

namespace App\Manager\ApiMembrosManager;

use App\Http\Controllers\Controller;
use App\Models\CadastroMembros\Anexo;
use App\Models\CadastroMembros\Membro;
use App\Models\CadastrosCliente\Cadastro;
use App\Models\EscalasMedicos\Escala;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
use Exception;
use Illuminate\Support\Facades\Log;

class ApiMembrosManager extends Controller
{

       
    public function membros($request)
    {

        $status_code = 200;
        try {

            // Defina um número padrão de itens por página.
            $perPage = $request->input('per_page', 5);

            $membro = Membro::with('igreja', 'tipo', 'anexo')->orderByDesc('id')->paginate($perPage);
            
            $respon = [
                'Membros'       => $membro,
                "status_code"   => $status_code
            ];

        } catch (\Throwable $e) {
            
            $respon = ["Error" => $e->getMessage(), "status_code" => $status_code = 400];
        
        }

        return $respon;
    }



    // pegar todas as esclas   
    public function novoMembros(Request $request)
    {

        $status_code = 200;

        try {
            $membro = $request->all();
            // $anexo = $request->file('file');

            // $path = $anexo->store('anexos');
            // $url  = Storage::url($path);

            // $anexo = Anexo::create(
            //     ['path' => $path,
            //      'name' => $anexo->getClientOriginalName(),
            //      'url' => $url

            // ]);

            // $membro['fk_anexo'] = $anexo->id;

            $membro = Membro::create($membro);

            $respon = [
                'Membro' => $membro,
                'status_code' => $status_code
            ];
        } catch (\Throwable $e) {
            $respon = ["Error" => $e->getMessage(), "status_code" => $status_code = 400];
        }

        return $respon;
    }



    public function salvarAnexo(Request $request, $id)
    {

        try {
            $anexo = $request->file('file');

            $path = $anexo->store('anexos');

            $url  = Storage::url($path);

            $anexo = Anexo::create(
                [
                    'path' => $path,
                    'name' => $anexo->getClientOriginalName(),
                    'url' => $url

                ]
            );

            $membroAserEditadoOfkAnexo = $this->buscarPorId($id);
            $membroAserEditadoOfkAnexo->fk_anexo = $anexo->id;
            $respon =
                [
                    'salvo' =>   $membroAserEditadoOfkAnexo->save(),
                    'fk_anexo' => $membroAserEditadoOfkAnexo->fk_anexo
                ];
        } catch (\Throwable $e) {
            $respon = ["Error" => $e->getMessage(), "status_code" => $status_code = 400];
        }

        return $respon;
    }

    public function exibirAnexo($id)
    {

        try {

            $anexo = Anexo::findOrFail($id);

            header('Content-Type: image/png');

            // $url = ('../storage/app/'.$anexo->path);

            $url = ('../storage/app/' . $anexo->path);

            file_exists($url) ? null : throw new Exception('Arquivo não encontrado no caminho: ' . $url);

            header('Content-Disposition: attachment; filename=' . $anexo->name);

            return readfile($url);
        } catch (\Throwable $e) {
            return  $respon = ["Error" => $e->getMessage(), "status_code" => $status_code = 400];
        }
    }


    public function buscarPorNome(Request $request)
    {
        $status_code = 200;

        try {
            $nome = $request->input('nome');
            $membro = Membro::with('igreja', 'tipo')
                ->where('nome_membro', 'like', '%' . $nome . '%')
                ->get();

            $respon = [
                'Membro' => $membro,
                "status_code" => $status_code
            ];
        } catch (\Throwable $e) {
            $respon = ["Erro ao buscar por nome" => $e->getMessage(), "status_code" => $status_code = 400];
        }

        return $respon;
    }


    public function buscarPorId($id)
    {

        $status_code = 200;
        try {

            $membro = Membro::with('igreja', 'tipo')->findOrFail($id);
            $respon = $membro;
        } catch (\Throwable $e) {
            $respon = ["Error" => $e->getMessage(), "status_code" => $status_code = 400];
        }

        return $respon;
    }


    public function editar($id, $request)
    {
        $status_code = 400;
        try {
            $dadosAseremAtualizadoDoMembro = $request->all();
            $membro = $this->buscarPorId($id);

            $membroAtualidado = $membro->update($dadosAseremAtualizadoDoMembro);
            $status_code = 200;
            $respon = ['Membro' => $membroAtualidado, "status_code" => $status_code];
        } catch (\Throwable $e) {
            $respon = ["Error" => $e->getMessage(), "status_code" => $status_code];
        }

        return $respon;
    }


    public function delete($id)
    {

        try {
            $membroAserExcluido = $this->buscarPorId($id);
            $membro = Membro::destroy($id);
            $respon = ['Excluido' => $membroAserExcluido, "status_code" => $status_code = 200];
        } catch (\Throwable $e) {
            $respon = ["Error" => $e->getMessage(), "status_code" => $status_code = 400];
        }

        return $respon;
    }


    public function obtemQuantidadeMembros()
    {
        try {

            $membro = Membro::with('igreja')
                ->count();

            $respon = [
                'Membros' => $membro,
                "status_code" =>  200
            ];
        } catch (\Throwable $th) {
            $respon = [
                'Erro' => $th->getCode(),
            ];
        }
        return $respon;
    }


    public function executarCron(Request $request)
    {
        // Defina o caminho completo para o script run_commands.sh.
        $scriptPath = ('../run_commands.sh');

        // Verifique se o script existe.
        if (file_exists($scriptPath)) {
            // Execute o script usando shell_exec() do PHP.
            $output = exec("/bin/bash $scriptPath");

            // Verifique se houve algum erro na execução do script.
            if ($output === null) {
                return response()->json(['error' => 'Erro ao executar o script run_commands.sh'], 500);
            } else {
                return response()->json(['message' => 'Script run_commands.sh executado com sucesso', 'output' => $output], 200);
            }
        } else {
            // O script não foi encontrado.
            return response()->json(['error' => 'Arquivo run_commands.sh não foi encontrado'], 404);
        }
    }
}

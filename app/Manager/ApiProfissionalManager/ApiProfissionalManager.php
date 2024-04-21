<?php
namespace App\Manager\ApiProfissionalManager;

use App\Http\Controllers\Controller;
use App\Models\CadastroMembros\Anexo;
use App\Models\Profissional;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApiProfissionalManager extends Controller
{


    public function store(Request $request)
    {

        $data = [];
        $status = 200;

        try {

            $request->cpf;

            /** @var Profissional $profisional */
            $profisional = $this->buscarPorCpf($request);


            if ($profisional) {
                $status = 500;
                return $data = [
                    'mensagem' => 'Esse profissional já existe',
                    'status' => $status
                ];
            }


            /** @var Anexo $avatar */
            $avatar = $this->salvarAvatarProfissional($request);

            /** @var Profissional $novoProfissional */
            $novoProfissional = Profissional::create($request->all() + ['fk_anexo' => $avatar->id ?? null]);


            $data = [
                'profissional' => $novoProfissional,
                'avatar' => route('profissional.avatar', ['id' => 1])

            ];

        } catch (\Exception $th) {
            throw $th;
        }

        return $data;
    }


    /**
     * Busca um profissional pelo CPF.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function buscarPorCpf(Request $request)
    {
        $cpf = $request->cpf; // Pega o CPF enviado via request

        $profissional = Profissional::where('cpf', $cpf)->first(); // Busca o primeiro profissional com o CPF fornecido


        return $profissional;
    }


    /**
     * Salvar avatar .
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function salvarAvatarProfissional(Request $request)
    {
        $anexo = $request->file('file');

        $path = $anexo->store('anexos');

        $url = Storage::url($path);

        $anexo = Anexo::create(
            [
                'path' => $path,
                'name' => $anexo->getClientOriginalName(),
                'url' => $url

            ]
        );


        return $anexo;

    }



    public function exibirAvatar($id)
    {
        try {

            $anexo = Anexo::findOrFail($id);

            header('Content-Type: image/png');


            $url = ('../storage/app/' . $anexo->path);

            file_exists($url) ? null : throw new Exception('Arquivo não encontrado no caminho: ' . $url);

            header('Content-Disposition: attachment; filename=' . $anexo->name);

            return readfile($url);

        } catch (\Throwable $e) {
            return $respon = ["Error" => $e->getMessage(), "status_code" => $status_code = 400];
        }
    }






}

?>
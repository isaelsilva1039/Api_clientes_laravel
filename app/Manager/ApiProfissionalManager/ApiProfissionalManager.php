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

            if (!$avatar) {
                return [
                    'mensagem' => 'é preciso adicionar uma foto',
                    'status' => 500
                ];
            }

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

        if (!$anexo) {
            return false;
        }

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



    public function buscarTodos(Request $request)
{
    $perPage = $request->input('per_page', 15);
    $page = $request->input('page', 1);
    $orderBy = $request->input('order_by', 'id');
    $sort = $request->input('sort', 'desc');
    $especialidade = $request->input('especialidade');

    $query = Profissional::with(['anexo']);

    if ($especialidade) {
        $query->where('especialidade', $especialidade);
    }

    $query->orderBy($orderBy, $sort);

    $profissionais = $query->paginate($perPage, ['*'], 'page', $page);

    // Adiciona a URL do anexo a cada profissional
    $profissionais->getCollection()->transform(function ($profissional) {
        if ($profissional->anexo) {
            $profissional->avatarUrl = route('profissional.avatar', ['id' => $profissional->anexo->id]);
        } else {
            $profissional->avatarUrl = null; // Ou um caminho padrão para um avatar padrão
        }
        return $profissional;
    });

    // Estrutura a resposta para incluir detalhes da paginação
    return response()->json([
        'data' => $profissionais->items(), // Os profissionais na página atual
        'total' => $profissionais->total(), // Total de profissionais
        'perPage' => $profissionais->perPage(), // Itens por página
        'currentPage' => $profissionais->currentPage(), // Página atual
        'lastPage' => $profissionais->lastPage(), // Última página
    ]);
}


    


}

?>
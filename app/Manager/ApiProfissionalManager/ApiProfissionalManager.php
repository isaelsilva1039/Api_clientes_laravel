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

    return response()->json([
        'data' => $profissionais->items(), 
        'total' => $profissionais->total(),
        'perPage' => $profissionais->perPage(),
        'currentPage' => $profissionais->currentPage(),
        'lastPage' => $profissionais->lastPage(),
    ]);
}



public function update(Request $request, $id)
{
    $status = 200;

    try {
        $profissional = Profissional::find($id);
        if (!$profissional) {
            return response()->json([
                'mensagem' => 'Profissional não encontrado',
                'status' => 404
            ], 404);
        }

        // Verifica se o CPF é enviado e é diferente do existente
        if ($request->filled('cpf') && $profissional->cpf !== $request->cpf) {
            $existente = Profissional::where('cpf', $request->cpf)->first();
            if ($existente) {
                return response()->json([
                    'mensagem' => 'Outro profissional já possui esse CPF',
                    'status' => 400
                ], 400);
            }
            $profissional->cpf = $request->cpf;
        }

        // Atualiza o avatar, se enviado
        if ($request->hasFile('file')) {
            $avatar = $this->salvarAvatarProfissional($request);
            if (!$avatar) {
                return response()->json([
                    'mensagem' => 'Erro ao salvar o avatar',
                    'status' => 500
                ], 500);
            }
            $profissional->fk_anexo = $avatar->id;
        }

        // Atualiza campos individuais se presentes
        $profissional->nome = $request->filled('nome') ? $request->nome : $profissional->nome;
        $profissional->email = $request->filled('email') ? $request->email : $profissional->email;
        $profissional->data_nascimento = $request->filled('data_nascimento') ? $request->data_nascimento : $profissional->data_nascimento;
        $profissional->especialidade = $request->filled('especialidade') ? $request->especialidade : $profissional->especialidade;

        $profissional->save();

        $data = [
            'profissional' => $profissional,
            'avatar' => $profissional->fk_anexo ? route('profissional.avatar', ['id' => $profissional->fk_anexo]) : null,
            'mensagem' => 'Profissional atualizado com sucesso'
        ];

    } catch (\Exception $e) {
        return response()->json([
            'mensagem' => 'Erro ao atualizar o profissional',
            'status' => 500,
            'error' => $e->getMessage()
        ], 500);
    }

    return response()->json($data, $status);
}



    


}

?>
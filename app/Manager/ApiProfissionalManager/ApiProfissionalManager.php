<?php

namespace App\Manager\ApiProfissionalManager;

use App\Http\Controllers\Controller;
use App\Models\CadastroMembros\Anexo;
use App\Models\Profissional;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ApiProfissionalManager extends Controller
{

    const USUARIO_ADMINISTRADOR = 1;

    const USUARIO_PROFISSIONAL_SAUDE = 2;

    const USUARIO_CLIENTE = 3;

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


            if ($novoProfissional) {
                // Criação do usuário associado ao profissional
                /** @var User $usuario */
                $usuario = User::create([
                    'name' => $novoProfissional->nome,
                    'email' => $novoProfissional->email,
                    'password' => bcrypt($novoProfissional->cpf),
                    'tipo' => self::USUARIO_PROFISSIONAL_SAUDE,
                    'fk_anexo' => $avatar->id
                ]);

                // Associação do usuário ao profissional se necessário
                $novoProfissional->user_id = $usuario->id;
                $novoProfissional->save();
                DB::commit();
            }


            $data = [
                'profissional' => $novoProfissional,
                'avatar' => route('profissional.avatar', ['id' => $avatar->id]),
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
        $cpf = $request->cpf;
        $incluiDeletados = $request->boolean('inclui_deletados', false);

        $query = Profissional::where('cpf', $cpf);

        if ($incluiDeletados) {
            $query->withTrashed();
        }

        $profissional = $query->first();

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
        $termo = $request->input('termo');  // Recebe o termo de busca

        $query = Profissional::with(['anexo']);

        if ($especialidade) {
            $query->where('especialidade', $especialidade);
        }

        if ($request->user()->tipo == self::USUARIO_PROFISSIONAL_SAUDE) {
            $query->where('user_id', $request->user()->id);
        }

        if ($termo !== null) {
            $query->where(function ($q) use ($termo) {
                $q->where('nome', 'like', "%{$termo}%")
                    ->orWhere('cpf', 'like', "%{$termo}%")
                    ->orWhere('email', 'like', "%{$termo}%");
            });
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
                    'status' => 400
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




            // Verifica se o CPF é enviado e é diferente do existente
            if ($request->filled('email') && $profissional->email !== $request->email) {
                $existente = Profissional::where('email', $request->email)->first();
                if ($existente) {
                    return response()->json([
                        'mensagem' => 'Outro profissional já possui esse email',
                        'status' => 400
                    ], 400);
                }
                $profissional->email = $request->email;
            }




            // Atualiza o avatar, se enviado
            if ($request->hasFile('file')) {
                $avatar = $this->salvarAvatarProfissional($request);
                if (!$avatar) {
                    return response()->json([
                        'mensagem' => 'Erro ao salvar o avatar',
                        'status' => 400
                    ], 400);
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

    /**
     * Remove the specified resource from storage using soft delete.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function softDelete($id)
    {
        $profissional = Profissional::find($id);

        if ($profissional) {
            $profissional->delete();
            return response()->json(['message' => 'Profissional deleted successfully.'], 200);
        } else {
            return response()->json(['message' => 'Profissional not found.'], 400);
        }
    }
}

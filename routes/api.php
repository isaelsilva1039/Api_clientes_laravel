<?php

use App\Http\Controllers\AgendamentoController\ApiAgendamentoController;

use App\Http\Controllers\ApiHorarioSemanalController\ApiHorarioSemanalController;
use App\Http\Controllers\ApiMembros\ApiControllerMembros;
use App\Http\Controllers\ApiWhatsAppController\WhatsAppController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\Dasboard\ApiDashboar;
use App\Http\Controllers\EspecialidadeController\ApiEspecialidadeController;
use App\Http\Controllers\Groups\ApiGroupsController;
use App\Http\Controllers\Login\CadastroController;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\Login\RegisterController;
use App\Http\Controllers\Profissional\ProfissionalController;
use App\Http\Controllers\Tipo\TipoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// -------------------------- USER -----------------------------------------------------------
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// -------------------------- CLIENTES ISAEL-----------------------------------------------------------
Route::get('/clientes',         [ClienteController::class, 'index'])->middleware('auth:sanctum');  // route principal
Route::post('/clientes',        [ClienteController::class, 'store'])->middleware('auth:sanctum');  // route de cadastros para o banco de dados 
Route::get('/clientes/{id}',    [ClienteController::class, 'show'])->middleware('auth:sanctum');   // trazer informação do banco de dados
Route::put('/clientes/{id}',    [ClienteController::class, 'update'])->middleware('auth:sanctum'); //atualiza
Route::delete('/clientes/{id}', [ClienteController::class, 'destroy'])->middleware('auth:sanctum');


// -------------------------- LOGIN , CADASTRO USUARIO NOVO  -----------------------------------------------------------
Route::prefix('auth')->group(function(){
    Route::post('/login',[LoginController::class, 'login']);
    Route::post('/logout',[LoginController::class, 'logout']);
    Route::post('/register',[RegisterController::class, 'register']);
    Route::post('/editar',[RegisterController::class, 'editar'])->middleware('auth:sanctum');//edita usuario Logado
    Route::get('/usuario',[RegisterController::class, 'usuario'])->middleware('auth:sanctum');//dados usuario logado

    Route::get('/me',[LoginController::class, 'me'])->middleware('auth:sanctum');
    // me
        
});

// -------------------------- CADASTRO DO CLIENTE AMARADO COM O USUARIO -----------------------------------------------------------
Route::prefix('cliente')->group(function(){
    Route::post('/cadastrar',[CadastroController::class, 'create'])->middleware('auth:sanctum');
    Route::post('/a',[CadastroController::class, 'a']);
});





Route::prefix('cliente')->group(function(){
    Route::get('/groups',[ApiGroupsController::class, 'groupsIndex'])->middleware('auth:sanctum');
    Route::get('/groups/{id}',[ApiGroupsController::class, 'obtemGroupPorId'])->middleware('auth:sanctum');
    Route::put('/groups/{id}',[ApiGroupsController::class, 'editarGroupPorId'])->middleware('auth:sanctum');
    Route::post('/groups',[ApiGroupsController::class, 'novoGroup'])->middleware('auth:sanctum');
    Route::delete('/groups/{id}',[ApiGroupsController::class, 'deleteGroup'])->middleware('auth:sanctum');
});



Route::prefix('membros')->group(function(){
    Route::get('/all',[ApiControllerMembros::class, 'indexMembros'])->middleware('auth:sanctum');
    Route::post('/create',[ApiControllerMembros::class, 'createMembroNovo'])->middleware('auth:sanctum');
    Route::get('/find',[ApiControllerMembros::class, 'buscarPorNomeMembro'])->middleware('auth:sanctum');
    Route::get('/find/{id}',[ApiControllerMembros::class, 'buscarPorId']);
    Route::put('/find/{id}',[ApiControllerMembros::class, 'editarMembro'])->middleware('auth:sanctum');
    Route::delete('/find/{id}',[ApiControllerMembros::class, 'deletarMembro'])->middleware('auth:sanctum');
    Route::get('/perfil/{id}',[ApiControllerMembros::class, 'exibirAnexoAction']);
    Route::post('/create/perfil/{id}',[ApiControllerMembros::class, 'salvarAnexoParaUmMembro']);
    Route::get('/all/count',[ApiControllerMembros::class, 'obtemQuantidadeMembros'])->middleware('auth:sanctum');
    Route::get('/execute',[ApiControllerMembros::class, 'execute']);

});

Route::prefix('igrejas')->group(function(){
    Route::get('/tipos',[TipoController::class, 'indexAllTipos'])->middleware('auth:sanctum');
});



// ROTAS RACCA
Route::prefix('racca')->group(function(){
    Route::post('/novo-cliente/webwook',[\App\Http\Controllers\ApiAsaassController\ApiAsaasController ::class, 'indexCliente']);
    Route::get('/clientes',[\App\Http\Controllers\ApiAsaassController\ApiAsaasController ::class, 'indexClienteAll'])->middleware('auth:sanctum');    

    Route::post('/vincular/cliente/{id}',[\App\Http\Controllers\ApiAsaassController\ApiAsaasController ::class, 'criarUserParaCliente']);
    Route::post('/consultas/liberar/user/{id}',[\App\Http\Controllers\ApiAsaassController\ApiAsaasController ::class, 'liberarConsultas']);



});


// ROTAS RACCA
Route::prefix('racca/profissional')->group(function(){
    Route::post('/novo',[ ProfissionalController::class, 'create']);
    Route::get('/avatar/{id}',[ ProfissionalController::class, 'avatar'])->name('profissional.avatar');

    Route::get('/all',[ ProfissionalController::class, 'buscarTodos'])->middleware('auth:sanctum');
    Route::post('/atualizar/{id}',[ ProfissionalController::class, 'update']);
    Route::delete('/delete/{id}',[ ProfissionalController::class, 'softDelete']);
    Route::get('/todos',[ ProfissionalController::class, 'buscarProfissional'])->middleware('auth:sanctum');

    Route::get('/horario/verificar',[ ApiAgendamentoController::class, 'verificarDisponibilidaDeEeHorario'])->middleware('auth:sanctum');
    
    
    // Route::post('/create/horario',[ ApiHorarioSemanalController::class, 'create'])->middleware('auth:sanctum');
});


Route::prefix('racca/agenda')->group(function(){
    Route::post('/novo',[ ApiAgendamentoController::class, 'criarAgendamento'])->middleware('auth:sanctum');

    Route::put('/atualizar/{id}',[ ApiAgendamentoController::class, 'atualizarAgendamento'])->middleware('auth:sanctum');


    Route::get('/agenda-cliente',[ ApiAgendamentoController::class, 'buscarAgendamentosCliente'])->middleware('auth:sanctum');

    Route::get('/agenda-cliente/{id}',[ ApiAgendamentoController::class, 'buscarEventoMedicoId'])->middleware('auth:sanctum');

    Route::post('/create/horario',[ ApiHorarioSemanalController::class, 'create'])->middleware('auth:sanctum')->middleware('auth:sanctum');


    Route::get('/agend-disponivel/{id}',[ ApiAgendamentoController::class, 'buscarHorariosDisponiveis'])->middleware('auth:sanctum');
    
    Route::post('/create/liberar',[ ApiHorarioSemanalController::class, 'createAgenda'])->middleware('auth:sanctum');
    
    Route::get('/calendario/mes/{id}',[ ApiHorarioSemanalController::class, 'obterMesAgenda'])->middleware('auth:sanctum');

    
});

Route::post('/racca/horarios/create/horario', [ApiHorarioSemanalController::class, 'create'])->middleware('auth:sanctum');
Route::get('/racca/horarios/action/horario', [ApiHorarioSemanalController::class, 'action'])->middleware('auth:sanctum');
Route::post('/racca/horarios/create/tempo/consultas', [ApiHorarioSemanalController::class, 'updateTempoConsulta'])->middleware('auth:sanctum');



Route::prefix('racca')->group(function(){
    Route::get('/index',[ ApiDashboar::class, 'index'])->middleware('auth:sanctum');
});


Route::prefix('especialidades')->group(function(){
    Route::post('/create',[ ApiEspecialidadeController::class, 'store'])->middleware('auth:sanctum');

    Route::get('/index',[ ApiEspecialidadeController::class, 'index'])->middleware('auth:sanctum');


    Route::put('/update/{id}',[ ApiEspecialidadeController::class, 'update'])->middleware('auth:sanctum');
    
    Route::delete('/excluir/{id}',[ ApiEspecialidadeController::class, 'excluir'])->middleware('auth:sanctum');
});



Route::prefix('webhook')->group(function(){
    Route::post('/whatsapp', [WhatsAppController::class, 'handleWebhook']);
    Route::post('/sendMensagaem', [WhatsAppController::class, 'sendMensagaem']);
    Route::get('/whatsapp', [WhatsAppController::class, 'verify']);


    
});
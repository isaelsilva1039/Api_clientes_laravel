<?php

use App\Http\Controllers\ApiControllerEscalas\ApiControllerEscala;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\Login\CadastroController;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\Login\RegisterController;
use App\Http\Controllers\Ocorencia\VeiculoController;
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


// -------------------------- CAMERAS ISMAEL  -----------------------------------------------------------
Route::get('/veiculos',         [VeiculoController::class, 'indexAction'])->middleware('auth:sanctum');  // pegar todos os veiculos cadastrado
Route::post('/veiculos',         [VeiculoController::class, 'indexStore'])->middleware('auth:sanctum');  // cria um novo resgistro



// -------------------------- LOGIN , CADASTRO USUARIO NOVO  -----------------------------------------------------------
Route::prefix('auth')->group(function(){
    Route::post('/login',[LoginController::class, 'login']);
    Route::post('/logout',[LoginController::class, 'logout']);
    Route::post('/register',[RegisterController::class, 'register']);
    Route::put('/editar',[RegisterController::class, 'editar'])->middleware('auth:sanctum');//edita usuario Logado
    Route::get('/usuario',[RegisterController::class, 'usuario'])->middleware('auth:sanctum');//dados usuario logado
    
});

// -------------------------- CADASTRO DO CLIENTE AMARADO COM O USUARIO -----------------------------------------------------------
Route::prefix('cliente')->group(function(){
    Route::post('/cadastrar',[CadastroController::class, 'create'])->middleware('auth:sanctum');
});



// -------------------------- CADASTRO DO CLIENTE AMARADO COM O USUARIO -----------------------------------------------------------
Route::prefix('cliente')->group(function(){
    Route::get('/escala',[ApiControllerEscala::class, 'escalaAction'])->middleware('auth:sanctum');
    Route::get('/escala/{id}',[ApiControllerEscala::class, 'obetemEscalarPorId'])->middleware('auth:sanctum');
    Route::put('/escala/{id}',[ApiControllerEscala::class, 'editarMedico'])->middleware('auth:sanctum');
    Route::delete('/escala',[ApiControllerEscala::class, 'excluirEscala'])->middleware('auth:sanctum');
    Route::post('/escala',[ApiControllerEscala::class, 'criarEscala'])->middleware('auth:sanctum');;

});




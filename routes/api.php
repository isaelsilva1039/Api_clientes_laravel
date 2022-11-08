<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\Login\RegisterController;
use App\Http\Controllers\Ocorencia\VeiculoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// rotas testes para clientes
Route::get('/clientes',         [ClienteController::class, 'index'])->middleware('auth:sanctum');  // route principal
Route::post('/clientes',        [ClienteController::class, 'store'])->middleware('auth:sanctum');  // route de cadastros para o banco de dados 
Route::get('/clientes/{id}',    [ClienteController::class, 'show'])->middleware('auth:sanctum');   // trazer informação do banco de dados
Route::put('/clientes/{id}',    [ClienteController::class, 'update'])->middleware('auth:sanctum'); //atualiza
Route::delete('/clientes/{id}', [ClienteController::class, 'destroy'])->middleware('auth:sanctum');


// rotas sistema Camereas
Route::get('/veiculos',         [VeiculoController::class, 'indexAction'])->middleware('auth:sanctum');  // pegar todos os veiculos cadastrado
Route::post('/veiculos',         [VeiculoController::class, 'indexStore'])->middleware('auth:sanctum');  // cria um novo resgistro




Route::prefix('auth')->group(function(){
    Route::post('/login',[LoginController::class, 'login']);
    Route::post('/logout',[LoginController::class, 'logout']);
    Route::post('/register',[RegisterController::class, 'register']);

});

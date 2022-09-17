<?php

use App\Http\Controllers\ClienteController;
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

Route::get('/clientes',         [ClienteController::class, 'index']); // route principal
Route::post('/clientes',        [ClienteController::class, 'store']); // route de cadastros para o banco de dados 
Route::get('/clientes/{id}',    [ClienteController::class, 'show']); // trazer informação do banco de dados
Route::put('/clientes/{id}',    [ClienteController::class, 'update']); //atualiza
Route::delete('/clientes/{id}', [ClienteController::class, 'destroy']);



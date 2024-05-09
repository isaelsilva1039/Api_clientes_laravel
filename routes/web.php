<?php

use App\Http\Controllers\ApiControllerEscalas\ApiControllerEscala;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::prefix('cliente')->group(function(){
    Route::get('/escala',[ApiControllerEscala::class, 'escalaAction']);
    Route::get('/escala/{id}',[ApiControllerEscala::class, 'obetemEscalarPorId'])->middleware('auth:sanctum');
    Route::put('/escala/{id}',[ApiControllerEscala::class, 'editarMedico'])->middleware('auth:sanctum');
    Route::delete('/escala',[ApiControllerEscala::class, 'excluirEscala'])->middleware('auth:sanctum');
    Route::post('/escala',[ApiControllerEscala::class, 'criarEscala'])->middleware('auth:sanctum');

});
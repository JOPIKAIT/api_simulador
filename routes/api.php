<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuiaPagamento\GuiaPagamentoController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('consultar-rupe', [GuiaPagamentoController::class, 'index']);
Route::get('consultar-rupe/{id}', [GuiaPagamentoController::class, 'show']);
Route::put('pagar-guia/{id}', [GuiaPagamentoController::class, 'pagarGuia']);
Route::put('cancelar-guia/{id}', [GuiaPagamentoController::class, 'cancelarGuia']);
Route::post('emitir-guia', [GuiaPagamentoController::class, 'store'])->name('pagar-guia.store');
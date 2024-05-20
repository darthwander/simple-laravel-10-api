<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ContaController;
use App\Http\Controllers\Api\V1\TransacoesController;

Route::prefix('v1')->group( function () 
{
    //Conta
    Route::get('/conta/listar-contas', [ContaController::class, 'index']);
    Route::get('/conta/exibir-conta', [ContaController::class, 'show']);
    Route::post('/conta/registrar-conta', [ContaController::class, 'store']);

    //Transação
    Route::post('/transacao', [TransacoesController::class, 'update']);
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ContaController;

Route::prefix('v1')->group( function () 
{
    Route::get('/contas/listar-contas', [ContaController::class, 'index']);
    Route::get('/contas/exibir-conta', [ContaController::class, 'show']);
    Route::post('/contas/registrar-conta', [ContaController::class, 'store']);
});

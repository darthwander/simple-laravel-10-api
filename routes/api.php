<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ContaController;

Route::prefix('v1')->group( function () 
{
    Route::get('/contas', [ContaController::class, 'index']);
    Route::get('/conta/{id}', [ContaController::class, 'show']);
});
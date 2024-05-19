<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransacaoUpdateRequest;
use App\Models\Conta;
use Illuminate\Http\Request;

class TransacaoController extends Controller
{
    public function update(Request $request)
    {
        TransacaoUpdateRequest::rules($request);
        
        if (Conta::efetuaTransacao($request)) {
            $saldo_atualizado = Conta::consultarSaldo($request);
            return response()->json(
                [
                    'numero_conta' => $request->numero_conta,
                    'saldo' => (float) $saldo_atualizado->saldo
                ]);    
        }
        
        return response()->json('Não foi possivel efetuar a transação.', 404);
    }
}

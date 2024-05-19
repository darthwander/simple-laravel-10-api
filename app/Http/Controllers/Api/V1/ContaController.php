<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContasStoreRequest;
use Illuminate\Http\Request;
use App\Models\Conta;

class ContaController extends Controller
{

    public function index()
    {
        return Conta::select('numero_conta', 'saldo')->get();
    }

    public function store(Request $request)
    {
        ContasStoreRequest::rules($request);
        $conta = Conta::create( $request->all());

        return response()->json([
            "numero_conta" => $conta->numero_conta,
            "saldo" => $conta->saldo
        ], 201);
    }

    public function show(Request $request)
    {
        $numero_conta = $request->input('numero_conta');
        $conta = Conta::select('numero_conta', 'saldo')
            ->where('numero_conta', $numero_conta)
            ->first();

        if (!$conta) {
            return response()->json(
                ['message' => 'Conta não encontrada']
                , 404);
        }

        return response()->json([$conta]);
    }
}

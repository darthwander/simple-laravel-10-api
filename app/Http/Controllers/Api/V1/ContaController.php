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
            "saldo" => (float) $conta->saldo
        ], 201);
    }

    public function show(Request $request)
    {
        $response = Conta::exibirDadosConta($request);

        if (array_key_exists('message', $response)) {
            return response()->json($response, 404);
        }

        return response()->json($response);
    }
}

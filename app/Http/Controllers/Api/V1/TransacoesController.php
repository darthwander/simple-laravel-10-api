<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransacaoUpdateRequest;
use App\Models\Conta;
use Illuminate\Http\Request;


/**
 * @OA\Tag(
 *     name="Transacao",
 *     description="API para gerenciamento de transações"
 * )
 */
class TransacoesController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/transacoes",
     *     summary="Create a new transaction",
     *     tags={"Transacao"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="numero_conta",
     *                 type="string",
     *                 description="Account number"
     *             ),
     *             @OA\Property(
     *                 property="forma_pagamento",
     *                 type="string",
     *                 enum={"PIX", "BOLETO", "CARTAO"},
     *                 description="Payment method"
     *             ),
     *             @OA\Property(
     *                 property="valor",
     *                 type="number",
     *                 description="Transaction value"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Transaction created successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Account not found"
     *     )
     * )
     */
    public function update(Request $request)
    {
        TransacaoUpdateRequest::rules($request);

        Conta::efetuaTransacao($request);
        $saldo_atualizado = Conta::consultarSaldo($request);
        return response()->json(
            [
                'numero_conta' => $request->numero_conta,
                'saldo' => (float) $saldo_atualizado->saldo
            ], 201);
    }
}

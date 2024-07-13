<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContasStoreRequest;
use Illuminate\Http\Request;
use App\Models\Conta;

/**
 * @OA\Tag(
 *     name="Conta",
 *     description="API para gerenciamento de contas"
 * )
 */
class ContaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/conta/listar-contas",
     *     summary="Get all accounts",
     *     tags={"Conta"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="numero_conta",
     *                 type="string",
     *                 description="Account number"
     *             ),
     *             @OA\Property(
     *                 property="saldo",
     *                 type="number",
     *                 description="Todas as contas cadastradas no sistema."
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        return Conta::select('numero_conta', 'saldo')->get();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/conta/registrar-conta",
     *     summary="Create a new account",
     *     tags={"Conta"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="numero_conta",
     *                 type="string",
     *                 description="Account number"
     *             ),
     *             @OA\Property(
     *                 property="saldo",
     *                 type="number",
     *                 description="Initial balance of the account"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Account created successfully"
     *     ),
     *     @OA\Response(
     *         response=406,
     *         description="Invalid data"
     *     )
     * )
     */
    public function store(Request $request)
    {
        ContasStoreRequest::rules($request);
        $conta = Conta::create($request->all());

        return response()->json([
            "numero_conta" => $conta->numero_conta,
            "saldo" => (float) $conta->saldo
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/conta/exibir-conta",
     *     summary="Get account information",
     *     tags={"Conta"},
     *     @OA\Parameter(
     *         name="numero_conta",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Account information retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Account not found"
     *     )
     * )
     */
    public function show(Request $request)
    {
        $response = Conta::exibirDadosConta($request);

        if (array_key_exists('message', $response)) {
            return response()->json($response, 404);
        }

        return response()->json($response);
    }
}


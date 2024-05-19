<?php

namespace App\Http\Requests;

use App\Models\Conta;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\ValidationRule;

class TransacaoUpdateRequest extends FormRequest
{
      
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public static function rules(Request $request) : void
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'numero_conta' => 'required|integer|exists:contas,numero_conta',
            'forma_pagamento' => 'required|in:P,C,D',
            'valor' => 'required|numeric|min:0.01'
        ]);
    
        if ($validator->fails()) {
            $errors = $validator->errors();
            throw new HttpResponseException(response()->json([
                'message' => 'Validation error',
                'errors' => $errors,
            ], 422));
        }
        
        self::verificaSaldoSuficiente($request);
    }
    
    public static function verificaSaldoSuficiente(Request $request): void
    {
        $saldo = Conta::consultaSaldo($request);

        if (!$saldo) {
            throw new HttpResponseException(response()->json([
                'message' => 'Conta não encontrada',
            ], 404));
        }

        $saldo = $saldo->saldo;

        $valor_transacao = Conta::calculaTransacao($request);

        if ($saldo < $valor_transacao) {
            throw new HttpResponseException(response()->json([
                'message' => 'Saldo insuficiente',
            ], 404));
        }
    }
}

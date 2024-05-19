<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Conta extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'numero_conta',
        'saldo'
    ];

    protected static array $taxas =
    [
        "P" => 0,
        "D" => 0.03,
        "C" => 0.05
    ];

    public static function exibirDadosConta(Request $request)
    {
        $numero_conta = $request->input('numero_conta');

        $conta = Conta::select('numero_conta', 'saldo')
            ->where('numero_conta', $numero_conta)
            ->first();

        if (!$conta) {
            return ['message' => 'Conta não encontrada'];
        }

        return [
            "numero_conta" => $conta->numero_conta,
            "saldo" => $conta->saldo
        ];
    }

    public static function consultarSaldo(Request $request)
    {
        return Conta::select('saldo')
            ->where('numero_conta', $request->numero_conta)
            ->first();
    }

    public static function calcularTransacao(Request $request): float
    {
        $valor = $request->valor;
        $taxa = self::$taxas[$request->forma_pagamento] ?? 0;
        return round($valor + ($valor * $taxa), 2);
    }

    public static function efetuaTransacao(Request $request)
    {
        $valor_transacao = self::calcularTransacao($request);
        $saldo_inicial = self::consultarSaldo($request);
        $saldo_atualizado = $saldo_inicial->saldo - $valor_transacao;

        return DB::table('contas')
            ->where('numero_conta', $request->numero_conta)
            ->update(['saldo' => $saldo_atualizado]);
    }

}

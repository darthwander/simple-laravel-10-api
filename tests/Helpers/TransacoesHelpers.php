<?php
 
function calcularSaldoEsperado($valor, $taxa) : float
{
    $total_transacao = calcularTransacao($valor, $taxa);
    
    return round(100 - $total_transacao, 2);
    
}

function calcularTransacao($valor, $taxa) : float
{
    return round($valor + ($valor * $taxa), 2);
}

function defaultNegativeAssertionsTransactions($response, $expectedStatusCode, $message) : void
{    
    $response->assertStatus($expectedStatusCode);
    $response = $response->getData(true);
    expect($response)->toHaveKey('message');
    expect($response['message'])->toEqual($message);
}

function defaultPositiveAssertionsTransactions($response, $expectedStatusCode, $taxa = 0, $numero_conta = 999999) : void
{   
    $response->assertStatus($expectedStatusCode);
    $response->assertJsonStructure([
        'saldo',
        'numero_conta'
    ]);

    $saldo_esperado = calcularSaldoEsperado(valor: 94.10, taxa: $taxa);
    $res = $response->getData(true);

    expect($res['numero_conta'])->toEqual($numero_conta);
    expect($res['saldo'])->toEqual($saldo_esperado);
}

function payloadTransactions($forma_pagamento, $valor = 94.10, $numero_conta = 999999) : array
{
    return [ 
        "forma_pagamento" => $forma_pagamento,
        "numero_conta" => $numero_conta,
        "valor" => $valor
    ];
}
<?php
 
function defaultNegativeAssertionsAccounts(
    $response, $expectedStatusCode, $toHaveKey, $message = 'Validation error') : void
{    
    $response->assertStatus($expectedStatusCode);
    $response = $response->getData(true);
    expect($response)->toHaveKey('message');
    expect($response['message'])->toEqual($message);
}

function defaultPositiveAssertionsAccounts(
    $response, $expectedStatusCode, $saldo = 100.00, $numero_conta = 999999) : void
{   
    $response->assertStatus($expectedStatusCode);

    $response->assertJsonStructure([
        'saldo',
        'numero_conta'
    ]);

    expect($response['numero_conta'])
        ->toBeInt()
        ->toEqual($numero_conta);

    expect($response['saldo'])
        ->toBeNumeric()
        ->toEqual($saldo)
        ->toBeGreaterThanOrEqual(0);
}

function payloadAccounts($saldo=100.00, $numero_conta = 999999) : array
{
    return [
        "numero_conta" => $numero_conta,
        "saldo" => $saldo
    ];
}
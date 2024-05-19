<?php

use Illuminate\Support\Facades\DB;

it('Deve criar uma nova conta', function($saldo)
{
    $numero_conta =  mt_rand(10000,30000);
    $data = [
        "numero_conta" => $numero_conta,
        "saldo" => $saldo
    ];

    $response = $this->postJson('/api/v1/conta/registrar-conta', $data);
    $response->assertStatus(201);

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

    DB::table('contas')->where('numero_conta', $numero_conta)->delete();
})->with(
    [
        [0], [10], [100], [1000]
    ]);

it('Não deve criar uma nova conta pois já existe', function()
{
    $numero_conta =  mt_rand(10000,30000);
    $saldo = 2000.00;

    $data = [
        "numero_conta" => $numero_conta,
        "saldo" => $saldo
    ];

    $this->postJson('/api/v1/conta/registrar-conta', $data);
    $response = $this->postJson('/api/v1/conta/registrar-conta', $data);

    $response->assertStatus(406);
    expect($response->getData(true))->toHaveKey('errors.numero_conta');

    DB::table('contas')->where('numero_conta', $numero_conta)->delete();
});

it('Não deve criar a conta pois o valor é negativo', function()
{
    $numero_conta =  mt_rand(10000,30000);
    $saldo = -100.00;

    $data = [
        "numero_conta" => $numero_conta,
        "saldo" => $saldo
    ];

    $response = $this->postJson('/api/v1/conta/registrar-conta', $data);

    $response->assertStatus(406);
    expect($response->getData(true))->toHaveKey('errors.saldo');
});

it('Não deve criar a conta poir o numero da conta é inválido ou inexistente', function($numero_conta)
{
    $saldo = 100.00;

    $data = [
        "numero_conta" => $numero_conta,
        "saldo" => $saldo
    ];

    $response = $this->postJson('/api/v1/conta/registrar-conta', $data);

    $response->assertStatus(406);
    expect($response->getData(true))->toHaveKey('errors.numero_conta');

})->with([
        [null], [0], [-1], [-10], [-100]
    ]);
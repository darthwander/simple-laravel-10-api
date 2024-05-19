<?php

use Illuminate\Support\Facades\DB;

it('Deve criar uma nova conta', function()
{
    $numero_conta =  mt_rand(1000,3000);
    $saldo = 2000.00;

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
        ->toEqual($saldo);

    DB::table('contas')->where('numero_conta', $numero_conta);
});

it('Não deve criar uma nova conta pois já existe', function()
{
    $numero_conta =  mt_rand(1000,3000);
    $saldo = 2000.00;

    $data = [
        "numero_conta" => $numero_conta,
        "saldo" => $saldo
    ];

    $this->postJson('/api/v1/conta/registrar-conta', $data);
    $response = $this->postJson('/api/v1/conta/registrar-conta', $data);

    $response->assertStatus(406);
    expect($response->getData(true))->toHaveKey('errors.numero_conta');

    DB::table('contas')->where('numero_conta', $numero_conta);
});

it('Não deve criar a conta pois o valor é negativo', function()
{
    $numero_conta =  mt_rand(1000,3000);
    $saldo = -100.00;

    $data = [
        "numero_conta" => $numero_conta,
        "saldo" => $saldo
    ];

    $response = $this->postJson('/api/v1/conta/registrar-conta', $data);

    $response->assertStatus(406);
    expect($response->getData(true))->toHaveKey('errors.saldo');

    DB::table('contas')->where('numero_conta', $numero_conta);
});
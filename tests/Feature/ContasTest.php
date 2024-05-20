<?php

use Illuminate\Support\Facades\DB;

beforeEach( function ()
{
    $this->numeroContaTest = 999999;
});

describe('Casos Positivos', function () {

    afterEach( function ()
    {
        DB::table('contas')->where('numero_conta', $this->numeroContaTest)->delete();
    });

    it('Deve criar uma nova conta', function ($saldo)
    {
        $data = [
            "numero_conta" => $this->numeroContaTest,
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
            ->toEqual($this->numeroContaTest);

        expect($response['saldo'])
            ->toBeNumeric()
            ->toEqual($saldo)
            ->toBeGreaterThanOrEqual(0);
    })->with('saldos');

    it('Deve exibir informações da conta', function ()
    {
        $data = [
            "numero_conta" => $this->numeroContaTest,
            "saldo" => $saldo = 100.00
        ];

        $this->postJson('/api/v1/conta/registrar-conta', $data);
        $response = $this->getJson("api/v1/conta/exibir-conta?numero_conta=$this->numeroContaTest");

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'saldo',
            'numero_conta'
        ]);

        expect($response['numero_conta'])
            ->toBeInt()
            ->toEqual($this->numeroContaTest);

        expect($response['saldo'])
            ->toBeNumeric()
            ->toEqual($saldo)
            ->toBeGreaterThanOrEqual(0);
    });
});

describe('Casos Negativos', function () {

    it('Não deve criar uma nova conta pois já existe', function ()
    {
        $data = [
            "numero_conta" => $this->numeroContaTest,
            "saldo" => 100.00
        ];

        $this->postJson('/api/v1/conta/registrar-conta', $data);
        $response = $this->postJson('/api/v1/conta/registrar-conta', $data);

        $response->assertStatus(406);
        expect($response->getData(true))->toHaveKey('errors.numero_conta');

        DB::table('contas')->where('numero_conta', $this->numeroContaTest)->delete();
    });

    it('Não deve criar a conta pois o valor é negativo', function ()
    {
        $data = [
            "numero_conta" => $this->numeroContaTest,
            "saldo" => -100.00
        ];

        $response = $this->postJson('/api/v1/conta/registrar-conta', $data);

        $response->assertStatus(406);
        expect($response->getData(true))->toHaveKey('errors.saldo');
    });

    it('Não deve criar a conta pois o numero da conta é inválido', function ($numero_conta)
    {
        $data = [
            "numero_conta" => $numero_conta,
            "saldo" => 100.00
        ];

        $response = $this->postJson('/api/v1/conta/registrar-conta', $data);

        $response->assertStatus(406);
        expect($response->getData(true))->toHaveKey('errors.numero_conta');

    })->with('numerosContasInvalidas');

    it('Não deve exibir as informações da conta, pois o número da conta não existe', function ($numero_conta)
    {
        $response = $this->getJson("api/v1/conta/exibir-conta?numero_conta=$numero_conta");

        $response->assertStatus(404);
        $data = $response->getData(true);
        expect($data)->toHaveKey('message');
        expect($data['message'])->toEqual('Conta não encontrada');

        DB::table('contas')->where('numero_conta', $numero_conta)->delete();
    })->with(
        [
            [300000],[0],[-1]
        ]);
});
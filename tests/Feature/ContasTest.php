<?php

use Illuminate\Support\Facades\DB;

afterEach( function ()
    {
        DB::table('contas')->where('numero_conta', 999999)->delete();
    });

describe('Casos Positivos', function () {

    it('Deve criar uma nova conta', function ($saldo)
    {
        $payload = payloadAccounts(saldo: $saldo);

        $response = $this->postJson('/api/v1/conta/registrar-conta', $payload);
        
        defaultPositiveAssertionsAccounts(
            response : $response, 
            expectedStatusCode: 201,
            saldo : $saldo
        );
    })->with('saldos');

    it('Deve exibir informações da conta', function ()
    {
        $payload = payloadAccounts();

        $this->postJson('/api/v1/conta/registrar-conta', $payload);
        $response = $this->getJson("api/v1/conta/exibir-conta?numero_conta=999999");

        defaultPositiveAssertionsAccounts(
            response : $response, 
            expectedStatusCode: 200,
            saldo : 100.00
        );
    });
});

describe('Casos Negativos', function () {

    it('Não deve criar uma nova conta pois já existe', function ()
    {
        $payload = payloadAccounts();

        $this->postJson('/api/v1/conta/registrar-conta', $payload);
        $response = $this->postJson('/api/v1/conta/registrar-conta', $payload);

        defaultNegativeAssertionsAccounts(
            response: $response,
            expectedStatusCode: 406,
            toHaveKey: 'errors.numero_conta' 
        );
    });

    it('Não deve criar a conta pois o valor é negativo', function ()
    {
        $payload = payloadAccounts(saldo: -100.00);

        $response = $this->postJson('/api/v1/conta/registrar-conta', $payload);

        defaultNegativeAssertionsAccounts(
            response: $response,
            expectedStatusCode: 406,
            toHaveKey: 'errors.saldo' 
        );
    });

    it('Não deve criar a conta pois o numero da conta é inválido', function ($numero_conta)
    {
        $payload = payloadAccounts(numero_conta: $numero_conta);

        $response = $this->postJson('/api/v1/conta/registrar-conta', $payload);

        defaultNegativeAssertionsAccounts(
            response: $response,
            expectedStatusCode: 406,
            toHaveKey: 'errors.numero_conta' 
        );

    })->with('numerosContasInvalidas');

    it('Não deve exibir as informações da conta, pois o número da conta não existe', function ($numero_conta)
    {
        $response = $this->getJson("api/v1/conta/exibir-conta?numero_conta=$numero_conta");

        defaultNegativeAssertionsAccounts(
            response: $response,
            expectedStatusCode: 404,
            toHaveKey: 'errors.numero_conta',
            message: 'Conta não encontrada' 
        );
    })->with('numerosContasInexistentes');
});
<?php

use Illuminate\Support\Facades\DB;

beforeEach( function ()
{
    $this->numeroContaTest = 999999;
    $data = [
        "numero_conta" => $this->numeroContaTest,
        "saldo" => 100.00
    ];
    
    $this->postJson('/api/v1/conta/registrar-conta', $data);
});

afterEach( function ()
{
    DB::table('contas')->where('numero_conta', $this->numeroContaTest)->delete();
});

describe('Caso Positivo', function () {
    
    it('Deve executar uma transação', function ($forma_pagamento, $taxa)
    {
        $data = buildData(forma_pagamento: $forma_pagamento);

        $response = $this->postJson('/api/v1/transacao', $data);
    
        defaultPositiveAssertions(
            response: $response,
            expectedStatusCode: 201, 
            taxa: $taxa
        );      
       
    })->with('formasPagamentoComTaxa');
});

describe('Casos Negativos', function () {
    
    it('Não deve executar a transação, pois não tem saldo suficiente', function ($forma_pagamento)
    {
        $data = buildData(forma_pagamento: $forma_pagamento, valor: 100.1);

        $response = $this->postJson('/api/v1/transacao', $data);
            
        defaultNegativeAssertions(
            response: $response,
            expectedStatusCode: 404,
            message: "Saldo insuficiente"
        );

    })->with('formasPagamento');
    
    it('Não deve executar a transação, pois o valor é inválido', function ($forma_pagamento, $valor)
    {
        $data = buildData(forma_pagamento: $forma_pagamento, valor: $valor);

        $response = $this->postJson('/api/v1/transacao', $data);
            
        defaultNegativeAssertions(
            response: $response,
            expectedStatusCode: 422,
            message: "Validation error"
        );        
    })
    ->with('formasPagamento')
    ->with('valoresInvalidos');
    
    it('Não deve executar a transação, pois a forma de pagamento é inválida', function ($forma_pagamento)
    {
        $data = buildData(forma_pagamento: $forma_pagamento);

        $response = $this->postJson('/api/v1/transacao', $data);
            
        defaultNegativeAssertions(
            response: $response,
            expectedStatusCode: 422,
            message: "Validation error"
        );
    })->with('formasPagamentoInvalidos');

    it('Não deve executar a transação, pois a conta não existe ou é inválida.', function ($forma_pagamento, $taxa, $numero_conta)
    {
        $data = buildData(forma_pagamento: $forma_pagamento, numero_conta: $numero_conta);

        $response = $this->postJson('/api/v1/transacao', $data);
           
        defaultNegativeAssertions(
            response: $response,
            expectedStatusCode: 422,
            message: "Validation error"
        );
    })
    ->with('formasPagamentoComTaxa')
    ->with('contasInvalidas');
});    
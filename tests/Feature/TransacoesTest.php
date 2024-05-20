<?php

use Illuminate\Support\Facades\DB;

beforeEach( function ()
{
    $payload = [
        "numero_conta" => 999999,
        "saldo" => 100.00
    ];
    
    $this->postJson('/api/v1/conta/registrar-conta', $payload);
});

afterEach( function ()
{
    DB::table('contas')->where('numero_conta', 999999)->delete();
});

describe('Caso Positivo', function () {
    
    it('Deve executar uma transação', function ($forma_pagamento, $taxa)
    {
        $payload = payloadTransactions(forma_pagamento: $forma_pagamento);

        $response = $this->postJson('/api/v1/transacao', $payload);
    
        defaultPositiveAssertionsTransactions(
            response: $response,
            expectedStatusCode: 201, 
            taxa: $taxa
        );      
       
    })->with('formasPagamentoComTaxa');
});

describe('Casos Negativos', function () {
    
    it('Não deve executar a transação, pois não tem saldo suficiente', function ($forma_pagamento)
    {
        $payload = payloadTransactions(forma_pagamento: $forma_pagamento, valor: 100.1);

        $response = $this->postJson('/api/v1/transacao', $payload);
            
        defaultNegativeAssertionsTransactions(
            response: $response,
            expectedStatusCode: 404,
            message: "Saldo insuficiente"
        );

    })->with('formasPagamento');
    
    it('Não deve executar a transação, pois o valor é inválido', function ($forma_pagamento, $valor)
    {
        $payload = payloadTransactions(forma_pagamento: $forma_pagamento, valor: $valor);

        $response = $this->postJson('/api/v1/transacao', $payload);
            
        defaultNegativeAssertionsTransactions(
            response: $response,
            expectedStatusCode: 422,
            message: "Validation error"
        );        
    })
    ->with('formasPagamento')
    ->with('valoresInvalidos');
    
    it('Não deve executar a transação, pois a forma de pagamento é inválida', function ($forma_pagamento)
    {
        $payload = payloadTransactions(forma_pagamento: $forma_pagamento);

        $response = $this->postJson('/api/v1/transacao', $payload);
            
        defaultNegativeAssertionsTransactions(
            response: $response,
            expectedStatusCode: 422,
            message: "Validation error"
        );
    })->with('formasPagamentoInvalidos');

    it('Não deve executar a transação, pois a conta não existe ou é inválida.', function ($forma_pagamento, $taxa, $numero_conta)
    {
        $payload = payloadTransactions(forma_pagamento: $forma_pagamento, numero_conta: $numero_conta);

        $response = $this->postJson('/api/v1/transacao', $payload);
           
        defaultNegativeAssertionsTransactions(
            response: $response,
            expectedStatusCode: 422,
            message: "Validation error"
        );
    })
    ->with('formasPagamentoComTaxa')
    ->with('contasInvalidas');
});    
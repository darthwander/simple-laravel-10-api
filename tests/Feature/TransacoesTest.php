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
        $data = [ 
            "forma_pagamento" => $forma_pagamento,
            "numero_conta" =>  $numero_conta = $this->numeroContaTest,
            "valor" => $valor = 94.10
        ];
        $response = $this->postJson('/api/v1/transacao', $data);
    
        $saldo_esperado = calcularSaldoEsperado($valor, $taxa);
       
        $response->assertStatus(201);
        $response->assertJsonStructure([
                'saldo',
                'numero_conta'
            ]);
        
        $res = $response->getData(true);
        expect($res['numero_conta'])->toEqual($numero_conta);
        expect($res['saldo'])->toEqual($saldo_esperado);
    })->with('formasPagamentoComTaxa');
});

describe('Casos Negativos', function () {
    
    it('Não deve executar a transação, pois não tem saldo suficiente', function ($forma_pagamento)
    {
        $data = [ 
            "forma_pagamento" => $forma_pagamento,
            "numero_conta" => $this->numeroContaTest,
            "valor" => $valor = 100.1
        ];
        $response = $this->postJson('/api/v1/transacao', $data);
            
        $res = $response->getData(true);
        expect($res)->toHaveKey('message');
        expect($res['message'])->toEqual("Saldo insuficiente");
        $response->assertStatus(404);
    })->with('formasPagamento');;
    
    it('Não deve executar a transação, pois o valor é inválido', function ($forma_pagamento, $valor)
    {
        $data = [ 
            "forma_pagamento" => $forma_pagamento,
            "numero_conta" => $this->numeroContaTest,
            "valor" => $valor
        ];
        $response = $this->postJson('/api/v1/transacao', $data);
            
        $res = $response->getData(true);
        expect($res)->toHaveKey('message');
        expect($res['message'])->toEqual("Validation error");
        $response->assertStatus(422);
        
    })
    ->with('formasPagamento')
    ->with('valoresInvalidos');
    
    it('Não deve executar a transação, pois a forma de pagamento é inválida', function ($forma_pagamento)
    {
        $data = [ 
            "forma_pagamento" => $forma_pagamento,
            "numero_conta" => $this->numeroContaTest,
            "valor" => 94.10
        ];
        $response = $this->postJson('/api/v1/transacao', $data);
            
        $res = $response->getData(true);
        expect($res)->toHaveKey('message');
        expect($res['message'])->toEqual("Validation error");
        $response->assertStatus(422);
    })->with('formasPagamentoInvalidos');

    it('Não deve executar a transação, pois a conta não existe ou é inválida.', function ($forma_pagamento, $taxa, $numero_conta)
    {
        $data = [ 
            "forma_pagamento" => $forma_pagamento,
            "numero_conta" => $numero_conta,
            "valor" => 94.10
        ];
        $response = $this->postJson('/api/v1/transacao', $data);
            
        $res = $response->getData(true);
        expect($res)->toHaveKey('message');
        expect($res['message'])->toEqual("Validation error");
        $response->assertStatus(422);
    })
    ->with('formasPagamentoComTaxa')
    ->with('contasInvalidas');
});    
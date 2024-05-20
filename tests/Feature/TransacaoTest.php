<?php

use Illuminate\Support\Facades\DB;

beforeEach( function ()
{
    $data = [
        "numero_conta" => 999999,
        "saldo" => 100.00
    ];
    
    $this->postJson('/api/v1/conta/registrar-conta', $data);
});

afterEach( function ()
{
    DB::table('contas')->where('numero_conta', 999999)->delete();
});

describe('Caso Positivo', function () {
    
    it('Deve executar uma transação', function ($forma_pagamento, $taxa)
    {
        $data = [ 
            "forma_pagamento" => $forma_pagamento,
            "numero_conta" =>  $numero_conta = 999999,
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
    })->with([
            ['P', 0], ['D', 0.03], ['C', 0.05] 
        ]);
});

describe('Casos Negativos', function () {
    
    it('Não deve executar a transação, pois não tem saldo suficiente', function ($forma_pagamento)
    {
        $data = [ 
            "forma_pagamento" => $forma_pagamento,
            "numero_conta" => 999999,
            "valor" => $valor = 100.1
        ];
        $response = $this->postJson('/api/v1/transacao', $data);
            
        $res = $response->getData(true);
        expect($res)->toHaveKey('message');
        expect($res['message'])->toEqual("Saldo insuficiente");
        $response->assertStatus(404);
    })->with([
        ['P'], ['D'], ['C'] 
    ]);;
    
    it('Não deve executar a transação, pois o valor é inválido', function ($forma_pagamento, $valor)
    {
        $data = [ 
            "forma_pagamento" => $forma_pagamento,
            "numero_conta" => 999999,
            "valor" => $valor
        ];
        $response = $this->postJson('/api/v1/transacao', $data);
            
        $res = $response->getData(true);
        expect($res)->toHaveKey('message');
        expect($res['message'])->toEqual("Validation error");
        $response->assertStatus(422);
        
    })->with([
        ['P', 0], ['D', 0], ['C', 0],
        ['P', -1], ['D', -1], ['C', -1],
        ['P', null], ['D', null], ['C', null],
        ['P', ''], ['D', ''], ['C', ''],
        ]);
    
    it('Não deve executar a transação, pois a forma de pagamento é inválida', function ($forma_pagamento)
    {
        $data = [ 
            "forma_pagamento" => $forma_pagamento,
            "numero_conta" => 999999,
            "valor" => 94.10
        ];
        $response = $this->postJson('/api/v1/transacao', $data);
            
        $res = $response->getData(true);
        expect($res)->toHaveKey('message');
        expect($res['message'])->toEqual("Validation error");
        $response->assertStatus(422);
    })->with([
        ['A'], [0.01], [1], [null], ['']
    ]);
});    

function calcularSaldoEsperado($valor, $taxa) : float
{
    $total_transacao = calcularTransacao($valor, $taxa);
    
    return round(100 - $total_transacao, 2);
    
}

function calcularTransacao($valor, $taxa) : float
{
    return round($valor + ($valor * $taxa), 2);
}
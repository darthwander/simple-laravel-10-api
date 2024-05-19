<?php

it('Deve criar uma nova conta', function()
{
    $data = [
        "numero_conta" => 12238,
        "saldo" => 2000.00
    ];

    $response = $this->postJson('/api/v1/conta/registrar-conta', $data);
    $response->assertStatus(201);
    $response->assertJsonStructure([
        'saldo',
        'numero_conta'
    ]);
    $this->assertIsInt($response['numero_conta']);
    $this->assertMatchesRegularExpression('/^\d+\.\d{2}$/', (string) $response['saldo']);
})->only();
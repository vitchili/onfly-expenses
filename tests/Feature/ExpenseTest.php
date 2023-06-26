<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    public function testStoreValidExpense()
    {
        $data = [
            'description' => 'Expense teste descricao',
            'date' => '2023-06-24',
            'value' => 10.99
        ];

        $token = '1|z8Bh3HyqriwtoqyexPwXralUdd5pthYsbEO89axZ';

        $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])
            ->postJson('/expense', $data);

        $response->assertStatus(201)
            ->assertJson([
                'request_status' => 'Operação realizada com sucesso.'
            ]);

        // Verificar se o registro foi salvo no banco de dados
        $this->assertDatabaseHas('expenses', [
            'description' => 'Expense description',
            'date' => '2023-06-24',
            'value' => 10.99
        ]);
    }

    public function testStoreInvalidExpense()
    {
        $data = [
            'description' => '',
            'date' => '2023-06-26', // Data futura não é permitida
            'value' => -5 // Valor negativo não é permitido
        ];

        $response = $this->postJson('/expense', $data);

        $response->assertStatus(500)
            ->assertJson([
                'request_status' => 'Erro ao adicionar despesa.'
            ]);

        // Verificar se o registro não foi salvo no banco de dados
        $this->assertDatabaseMissing('expenses', [
            'description' => '',
            'date' => '2023-06-26',
            'value' => -5
        ]);
    }
}
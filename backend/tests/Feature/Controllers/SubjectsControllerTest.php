<?php
namespace Tests\Feature\Controllers;

use Tests\TestCase;

class SubjectsControllerTest extends TestCase
{
    public function test_can_create_subject()
    {
        $payload = ['Descricao' => 'Assunto F.'];
        $response = $this->postJson('/api/subjects', $payload);
        
        $response->assertStatus(201)
                 ->assertJsonFragment(['Descricao' => 'Assunto F.']);
                 
        $this->assertDatabaseHas('assuntos', $payload);
    }
}
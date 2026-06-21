<?php
namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;

class SubjectsControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_list_subjects()
    {
        $response = $this->getJson('/api/subjects');
        $response->assertStatus(200)
                 ->assertJsonStructure(['data', 'current_page', 'last_page', 'total']);
    }

    public function test_can_create_subject()
    {
        $payload = ['Descricao' => 'Assunto ' . Str::random(8)];
        $response = $this->postJson('/api/subjects', $payload);
        
        $response->assertStatus(201)
                 ->assertJsonFragment(['Descricao' => $payload['Descricao']]);
                 
        $this->assertDatabaseHas('assuntos', $payload);
    }

    public function test_cannot_create_duplicate_subject()
    {
        $payload = ['Descricao' => 'Duplicado ' . Str::random(8)];
        $this->postJson('/api/subjects', $payload);
        
        $response = $this->postJson('/api/subjects', $payload);
        $response->assertStatus(409)
                 ->assertJsonStructure(['message']);
    }

    public function test_can_get_subject_by_id()
    {
        $subject = $this->postJson('/api/subjects', ['Descricao' => 'Subj ID ' . Str::random(8)])->json();
        
        $response = $this->getJson("/api/subjects/{$subject['CodAs']}");
        $response->assertStatus(200)
                 ->assertJsonFragment(['Descricao' => $subject['Descricao']]);
    }

    public function test_returns_404_when_getting_non_existent_subject()
    {
        $response = $this->getJson('/api/subjects/999999');
        $response->assertStatus(404);
    }

    public function test_can_update_subject()
    {
        $subject = $this->postJson('/api/subjects', ['Descricao' => 'Antigo ' . Str::random(8)])->json();
        $id = $subject['CodAs'];
        $newDesc = 'Novo ' . Str::random(8);
        
        $response = $this->putJson("/api/subjects/{$id}", ['Descricao' => $newDesc]);
        $response->assertStatus(200)
                 ->assertJsonFragment(['Descricao' => $newDesc]);
                 
        $this->assertDatabaseHas('assuntos', ['CodAs' => $id, 'Descricao' => $newDesc]);
    }

    public function test_returns_404_when_updating_non_existent_subject()
    {
        $response = $this->putJson('/api/subjects/999999', ['Descricao' => 'Novo Nome']);
        $response->assertStatus(404);
    }

    public function test_can_delete_subject()
    {
        $subject = $this->postJson('/api/subjects', ['Descricao' => 'Deletar ' . Str::random(8)])->json();
        $id = $subject['CodAs'];
        
        $response = $this->deleteJson("/api/subjects/{$id}");
        $response->assertStatus(200);
                 
        $this->assertDatabaseMissing('assuntos', ['CodAs' => $id]);
    }

    public function test_returns_404_when_deleting_non_existent_subject()
    {
        $response = $this->deleteJson('/api/subjects/999999');
        $response->assertStatus(404);
    }
}
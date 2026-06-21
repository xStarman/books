<?php
namespace Tests\Feature\Controllers;

use Tests\TestCase;

class AuthorsControllerTest extends TestCase
{
    public function test_can_list_authors()
    {
        $response = $this->getJson('/api/authors');
        $response->assertStatus(200)
                 ->assertJsonStructure(['data', 'current_page', 'last_page', 'total']);
    }

    public function test_can_create_author()
    {
        $payload = ['Nome' => 'Autor Teste Feature'];
        $response = $this->postJson('/api/authors', $payload);
        
        $response->assertStatus(201)
                 ->assertJsonFragment(['Nome' => 'Autor Teste Feature']);
                 
        $this->assertDatabaseHas('autores', $payload);
    }

    public function test_cannot_create_duplicate_author()
    {
        $payload = ['Nome' => 'Autor Duplicado Feature'];
        $this->postJson('/api/authors', $payload);
        
        $response = $this->postJson('/api/authors', $payload);
        $response->assertStatus(409)
                 ->assertJsonStructure(['message']);
    }

    public function test_can_update_author()
    {
        $author = $this->postJson('/api/authors', ['Nome' => 'Autor Antigo'])->json();
        $id = $author['CodAu'];
        
        $response = $this->putJson("/api/authors/{$id}", ['Nome' => 'Autor Novo']);
        $response->assertStatus(200)
                 ->assertJsonFragment(['Nome' => 'Autor Novo']);
                 
        $this->assertDatabaseHas('autores', ['CodAu' => $id, 'Nome' => 'Autor Novo']);
    }

    public function test_can_delete_author()
    {
        $author = $this->postJson('/api/authors', ['Nome' => 'Autor para Deletar'])->json();
        $id = $author['CodAu'];
        
        $response = $this->deleteJson("/api/authors/{$id}");
        $response->assertStatus(200);
                 
        $this->assertDatabaseMissing('autores', ['CodAu' => $id]);
    }
}
<?php
namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;

class AuthorsControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_list_authors()
    {
        $response = $this->getJson('/api/authors');
        $response->assertStatus(200)
                 ->assertJsonStructure(['data', 'current_page', 'last_page', 'total']);
    }

    public function test_can_create_author()
    {
        $payload = ['Nome' => 'Autor Teste ' . Str::random(8)];
        $response = $this->postJson('/api/authors', $payload);
        
        $response->assertStatus(201)
                 ->assertJsonFragment(['Nome' => $payload['Nome']]);
                 
        $this->assertDatabaseHas('autores', $payload);
    }

    public function test_cannot_create_duplicate_author()
    {
        $payload = ['Nome' => 'Autor Duplicado ' . Str::random(8)];
        $this->postJson('/api/authors', $payload);
        
        $response = $this->postJson('/api/authors', $payload);
        $response->assertStatus(409)
                 ->assertJsonStructure(['message']);
    }

    public function test_can_get_author_by_id()
    {
        $author = $this->postJson('/api/authors', ['Nome' => 'Autor By ID ' . Str::random(8)])->json();
        
        $response = $this->getJson("/api/authors/{$author['CodAu']}");
        $response->assertStatus(200)
                 ->assertJsonFragment(['Nome' => $author['Nome']]);
    }

    public function test_returns_404_when_getting_non_existent_author()
    {
        $response = $this->getJson('/api/authors/999999');
        $response->assertStatus(404);
    }

    public function test_can_update_author()
    {
        $author = $this->postJson('/api/authors', ['Nome' => 'Autor Antigo ' . Str::random(8)])->json();
        $id = $author['CodAu'];
        $newName = 'Autor Novo ' . Str::random(8);
        
        $response = $this->putJson("/api/authors/{$id}", ['Nome' => $newName]);
        $response->assertStatus(200)
                 ->assertJsonFragment(['Nome' => $newName]);
                 
        $this->assertDatabaseHas('autores', ['CodAu' => $id, 'Nome' => $newName]);
    }

    public function test_returns_404_when_updating_non_existent_author()
    {
        $response = $this->putJson('/api/authors/999999', ['Nome' => 'Novo Nome']);
        $response->assertStatus(404);
    }

    public function test_can_delete_author()
    {
        $author = $this->postJson('/api/authors', ['Nome' => 'Autor para Deletar ' . Str::random(8)])->json();
        $id = $author['CodAu'];
        
        $response = $this->deleteJson("/api/authors/{$id}");
        $response->assertStatus(200);
                 
        $this->assertDatabaseMissing('autores', ['CodAu' => $id]);
    }

    public function test_returns_404_when_deleting_non_existent_author()
    {
        $response = $this->deleteJson('/api/authors/999999');
        $response->assertStatus(404);
    }
}
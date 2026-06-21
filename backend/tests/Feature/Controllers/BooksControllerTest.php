<?php
namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Livro;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;

class BooksControllerTest extends TestCase
{
    use DatabaseTransactions;

    private function createDependencies()
    {
        $unique = Str::random(8);
        $authorResponse = $this->postJson('/api/authors', ['Nome' => 'Autor ' . $unique]);
        if ($authorResponse->status() !== 201) {
            dd('Author failed', $authorResponse->json());
        }
        $author = $authorResponse->json();

        $subjectResponse = $this->postJson('/api/subjects', ['Descricao' => 'Subj ' . $unique]);
        if ($subjectResponse->status() !== 201) {
            dd('Subject failed', $subjectResponse->json());
        }
        $subject = $subjectResponse->json();

        return [$author['CodAu'] ?? null, $subject['CodAs'] ?? null];
    }

    private function createBook()
    {
        [$authorId, $subjectId] = $this->createDependencies();
        $unique = Str::random(8);

        $payload = [
            'Titulo' => 'Livro ' . $unique,
            'Editora' => 'Editora Feature',
            'Edicao' => 1,
            'AnoPublicacao' => 2024,
            'Preco' => 50.00,
            'autores' => [$authorId],
            'assuntos' => [$subjectId]
        ];

        return $this->postJson('/api/books', $payload)->json();
    }

    public function test_can_create_book()
    {
        [$authorId, $subjectId] = $this->createDependencies();

        $payload = [
            'Titulo' => 'Livro Feature ' . Str::random(8),
            'Editora' => 'Editora Feature',
            'Edicao' => 1,
            'AnoPublicacao' => 2024,
            'Preco' => 50.00,
            'autores' => [$authorId],
            'assuntos' => [$subjectId]
        ];

        $response = $this->postJson('/api/books', $payload);
        
        $response->assertStatus(201)
                 ->assertJsonFragment(['Titulo' => $payload['Titulo']]);
                 
        $this->assertDatabaseHas('livros', ['Titulo' => $payload['Titulo'], 'Preco' => 50.00]);
    }

    public function test_can_list_books()
    {
        $book = $this->createBook();

        $response = $this->getJson('/api/books?filters[Titulo]=' . $book['Titulo']);

        $response->assertStatus(200)
                 ->assertJsonFragment(['Titulo' => $book['Titulo']]);
    }

    public function test_can_get_book_by_id()
    {
        $book = $this->createBook();

        $response = $this->getJson("/api/books/{$book['CodL']}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['Titulo' => $book['Titulo']]);
    }

    public function test_returns_404_when_getting_non_existent_book()
    {
        $response = $this->getJson('/api/books/999999');

        $response->assertStatus(404);
    }

    public function test_can_update_book()
    {
        $book = $this->createBook();
        [$authorId, $subjectId] = $this->createDependencies();

        $payload = [
            'Titulo' => 'Livro Atualizado ' . Str::random(8),
            'Editora' => 'Editora Atualizada',
            'Edicao' => 2,
            'AnoPublicacao' => 2025,
            'Preco' => 60.00,
            'autores' => [$authorId],
            'assuntos' => [$subjectId]
        ];

        $response = $this->putJson("/api/books/{$book['CodL']}", $payload);

        $response->assertStatus(200)
                 ->assertJsonFragment(['Titulo' => $payload['Titulo']]);

        $this->assertDatabaseHas('livros', ['CodL' => $book['CodL'], 'Titulo' => $payload['Titulo']]);
    }

    public function test_can_delete_book()
    {
        $book = $this->createBook();

        $response = $this->deleteJson("/api/books/{$book['CodL']}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('livros', ['CodL' => $book['CodL']]);
    }
}

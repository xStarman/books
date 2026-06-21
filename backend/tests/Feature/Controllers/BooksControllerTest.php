<?php
namespace Tests\Feature\Controllers;

use Tests\TestCase;

class BooksControllerTest extends TestCase
{
    private function createDependencies()
    {
        $author = $this->postJson('/api/authors', ['Nome' => 'Autor Livro Feature'])->json();
        $subject = $this->postJson('/api/subjects', ['Descricao' => 'Assunto L.'])->json();
        return [$author['CodAu'], $subject['CodAs']];
    }

    public function test_can_create_book()
    {
        [$authorId, $subjectId] = $this->createDependencies();

        $payload = [
            'Titulo' => 'Livro Feature',
            'Editora' => 'Editora Feature',
            'Edicao' => 1,
            'AnoPublicacao' => 2024,
            'Preco' => 50.00,
            'autores' => [$authorId],
            'assuntos' => [$subjectId]
        ];

        $response = $this->postJson('/api/books', $payload);
        
        $response->assertStatus(201)
                 ->assertJsonFragment(['Titulo' => 'Livro Feature']);
                 
        $this->assertDatabaseHas('livros', ['Titulo' => 'Livro Feature', 'Preco' => 50.00]);
        $this->assertDatabaseHas('livro_autor', ['Autor_CodAu' => $authorId]);
        $this->assertDatabaseHas('livro_assunto', ['Assunto_CodAs' => $subjectId]);
    }
}
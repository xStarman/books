<?php

namespace Tests\Unit\Services\Isbn;

use PHPUnit\Framework\TestCase;
use App\Services\Isbn\GetBookByIsbnService;
use App\Repositories\Isbn\IsbnRepository;

class GetBookByIsbnServiceTest extends TestCase
{
    public function test_returns_null_when_no_items_found()
    {
        $repo = $this->createMock(IsbnRepository::class);
        $repo->method('getByIsbn')->willReturn(['items' => []]);

        $service = new GetBookByIsbnService($repo);
        $result = $service->execute('0000000000');

        $this->assertNull($result);
    }

    public function test_returns_null_when_items_key_is_missing()
    {
        $repo = $this->createMock(IsbnRepository::class);
        $repo->method('getByIsbn')->willReturn([]);

        $service = new GetBookByIsbnService($repo);
        $result = $service->execute('0000000000');

        $this->assertNull($result);
    }

    public function test_maps_google_books_response_correctly()
    {
        $repo = $this->createMock(IsbnRepository::class);
        $repo->method('getByIsbn')->willReturn([
            'items' => [
                [
                    'volumeInfo' => [
                        'title' => 'Livro Teste',
                        'publisher' => 'Editora Teste',
                        'publishedDate' => '2020-05-10',
                        'authors' => ['Autor A', 'Autor B'],
                        'categories' => ['Ficção'],
                        'description' => 'Descrição do livro',
                        'imageLinks' => ['thumbnail' => 'http://img.com/thumb.jpg'],
                    ]
                ]
            ]
        ]);

        $service = new GetBookByIsbnService($repo);
        $result = $service->execute('9788575228074');

        $this->assertEquals('Livro Teste', $result['Titulo']);
        $this->assertEquals('Editora Teste', $result['Editora']);
        $this->assertEquals('2020', $result['AnoPublicacao']);
        $this->assertEquals(['Autor A', 'Autor B'], $result['autores']);
        $this->assertEquals(['Ficção'], $result['assuntos']);
        $this->assertEquals('Descrição do livro', $result['descricao']);
        $this->assertEquals('http://img.com/thumb.jpg', $result['thumbnail']);
    }

    public function test_handles_missing_optional_fields()
    {
        $repo = $this->createMock(IsbnRepository::class);
        $repo->method('getByIsbn')->willReturn([
            'items' => [
                [
                    'volumeInfo' => [
                        'title' => 'Livro Mínimo',
                    ]
                ]
            ]
        ]);

        $service = new GetBookByIsbnService($repo);
        $result = $service->execute('1234567890');

        $this->assertEquals('Livro Mínimo', $result['Titulo']);
        $this->assertNull($result['Editora']);
        $this->assertNull($result['AnoPublicacao']);
        $this->assertEquals([], $result['autores']);
        $this->assertEquals([], $result['assuntos']);
        $this->assertNull($result['descricao']);
        $this->assertNull($result['thumbnail']);
    }
}

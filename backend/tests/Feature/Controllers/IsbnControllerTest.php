<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;

class IsbnControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_returns_book_data_for_valid_isbn()
    {
        Http::fake([
            '*' => Http::response([
                'totalItems' => 1,
                'items' => [
                    [
                        'volumeInfo' => [
                            'title' => 'Introdução ao Pentest',
                            'publisher' => 'Novatec Editora',
                            'publishedDate' => '2019-10-22',
                            'authors' => ['Daniel Moreno'],
                            'categories' => ['Computers'],
                            'description' => 'Um livro sobre pentest',
                            'imageLinks' => ['thumbnail' => 'http://img.com/thumb.jpg'],
                        ]
                    ]
                ]
            ], 200)
        ]);

        $response = $this->getJson('/api/isbn/9788575228074');

        $response->assertStatus(200)
                 ->assertJsonFragment(['Titulo' => 'Introdução ao Pentest'])
                 ->assertJsonFragment(['Editora' => 'Novatec Editora'])
                 ->assertJsonFragment(['AnoPublicacao' => '2019']);
    }

    public function test_returns_404_when_isbn_not_found()
    {
        Http::fake([
            '*' => Http::response([
                'totalItems' => 0,
                'items' => []
            ], 200)
        ]);

        $response = $this->getJson('/api/isbn/0000000000');

        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Livro não encontrado']);
    }

    public function test_returns_500_when_google_api_fails()
    {
        Http::fake([
            '*' => Http::response(null, 500)
        ]);

        $response = $this->getJson('/api/isbn/9788575228074');

        $response->assertStatus(500);
    }
}

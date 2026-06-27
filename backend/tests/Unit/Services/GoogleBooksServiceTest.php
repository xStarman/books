<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\GoogleBooksService;
use Illuminate\Support\Facades\Http;

class GoogleBooksServiceTest extends TestCase
{
    public function test_fetches_by_isbn_successfully()
    {
        Http::fake([
            '*' => Http::response([
                'totalItems' => 1,
                'items' => [['volumeInfo' => ['title' => 'Livro Fake']]]
            ], 200)
        ]);

        $service = new GoogleBooksService();
        $result = $service->fetchByIsbn('9788575228074');

        $this->assertArrayHasKey('items', $result);
        $this->assertEquals('Livro Fake', $result['items'][0]['volumeInfo']['title']);
    }

    public function test_throws_exception_on_api_failure()
    {
        Http::fake([
            '*' => Http::response(null, 500)
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Falha ao comunicar com a API do Google Books.');

        $service = new GoogleBooksService();
        $service->fetchByIsbn('0000000000');
    }
}

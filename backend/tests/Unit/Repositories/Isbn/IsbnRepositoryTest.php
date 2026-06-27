<?php

namespace Tests\Unit\Repositories\Isbn;

use PHPUnit\Framework\TestCase;
use App\Repositories\Isbn\IsbnRepository;
use App\Services\GoogleBooksService;

class IsbnRepositoryTest extends TestCase
{
    public function test_delegates_to_google_books_service()
    {
        $expectedData = ['items' => [['volumeInfo' => ['title' => 'Test']]]];

        $googleService = $this->createMock(GoogleBooksService::class);
        $googleService->expects($this->once())
            ->method('fetchByIsbn')
            ->with('9788575228074')
            ->willReturn($expectedData);

        $repo = new IsbnRepository($googleService);
        $result = $repo->getByIsbn('9788575228074');

        $this->assertEquals($expectedData, $result);
    }
}

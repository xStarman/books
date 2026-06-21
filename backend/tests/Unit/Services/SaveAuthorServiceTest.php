<?php
namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\Authors\SaveAuthorService;
use App\Exceptions\AuthorAlreadyExistsException;

class SaveAuthorServiceTest extends TestCase
{
    public function test_throws_exception_if_author_exists()
    {
        $service = app(SaveAuthorService::class);
        
        $service->execute(['Nome' => 'Autor Duplicado']);
        
        $this->expectException(AuthorAlreadyExistsException::class);
        $service->execute(['Nome' => 'Autor Duplicado']);
    }

    public function test_creates_author()
    {
        $service = app(SaveAuthorService::class);
        $author = $service->execute(['Nome' => 'Novo Autor Único']);
        $this->assertEquals('Novo Autor Único', $author->Nome);
    }
}
<?php
namespace Tests\Unit\DTOs;

use App\DTOs\Authors\AuthorFiltersDTO;
use PHPUnit\Framework\TestCase;

class AuthorFiltersDTOTest extends TestCase
{
    public function test_can_create_from_array()
    {
        $dto = AuthorFiltersDTO::fromArray(['Nome' => 'Teste']);
        $this->assertEquals('Teste', $dto->Nome);
        
        $emptyDto = AuthorFiltersDTO::fromArray([]);
        $this->assertNull($emptyDto->Nome);
    }
}
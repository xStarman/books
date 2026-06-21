<?php
namespace Tests\Unit\DTOs;

use App\DTOs\Subjects\SubjectFiltersDTO;
use PHPUnit\Framework\TestCase;

class SubjectFiltersDTOTest extends TestCase
{
    public function test_can_create_from_array()
    {
        $dto = SubjectFiltersDTO::fromArray(['Descricao' => 'Teste']);
        $this->assertEquals('Teste', $dto->Descricao);
        
        $emptyDto = SubjectFiltersDTO::fromArray([]);
        $this->assertNull($emptyDto->Descricao);
    }
}
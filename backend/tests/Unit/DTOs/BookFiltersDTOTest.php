<?php
namespace Tests\Unit\DTOs;

use App\DTOs\Books\BookFiltersDTO;
use PHPUnit\Framework\TestCase;

class BookFiltersDTOTest extends TestCase
{
    public function test_can_create_from_array()
    {
        $dto = BookFiltersDTO::fromArray([
            'Titulo' => 'Livro',
            'Editora' => 'Editora',
            'Edicao' => 1,
            'AnoPublicacao' => 2020,
            'Autor' => 1,
            'Assunto' => 2
        ]);
        $this->assertEquals('Livro', $dto->Titulo);
        $this->assertEquals('Editora', $dto->Editora);
        $this->assertEquals(1, $dto->Edicao);
        $this->assertEquals(2020, $dto->AnoPublicacao);
        $this->assertEquals(1, $dto->Autor);
        $this->assertEquals(2, $dto->Assunto);
    }
}
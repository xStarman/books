<?php
namespace Tests\Unit\DTOs;

use App\DTOs\OrderDTO;
use PHPUnit\Framework\TestCase;

class OrderDTOTest extends TestCase
{
    public function test_creates_array_of_dtos()
    {
        $dtos = OrderDTO::fromArray(['Nome' => 'desc', 'CodAu' => 'asc']);
        $this->assertCount(2, $dtos);
        $this->assertEquals('Nome', $dtos[0]->field);
        $this->assertEquals('desc', $dtos[0]->direction);
        $this->assertEquals('CodAu', $dtos[1]->field);
        $this->assertEquals('asc', $dtos[1]->direction);
    }
}
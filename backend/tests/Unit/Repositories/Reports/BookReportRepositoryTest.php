<?php

namespace Tests\Unit\Repositories\Reports;

use Tests\TestCase;
use App\Repositories\Reports\BookReportRepository;
use App\DTOs\Reports\BookReportFilterDTO;

class BookReportRepositoryTest extends TestCase
{
    public function test_can_build_query_with_filters()
    {
        $repository = new BookReportRepository();
        
        $filters = BookReportFilterDTO::fromArray([
            'Titulo' => 'Teste',
            'autores' => [1],
            'assuntos' => [1]
        ]);
        
        $query = $repository->getQuery($filters);
        $sql = $query->toSql();
        
        $this->assertStringContainsString('ilike', $sql);
        $this->assertStringContainsString('exists', $sql);
    }
}

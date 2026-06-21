<?php

namespace Tests\Unit\Repositories\Reports;

use Tests\TestCase;
use App\Repositories\Reports\AuditReportRepository;
use App\DTOs\Reports\AuditReportFilterDTO;

class AuditReportRepositoryTest extends TestCase
{
    public function test_can_build_query_with_filters()
    {
        $repository = new AuditReportRepository();
        
        $filters = AuditReportFilterDTO::fromArray([
            'Titulo' => 'Teste',
            'acao' => 'UPDATE',
            'dataInicial' => '2024-01-01',
            'dataFinal' => '2024-12-31'
        ]);
        
        $query = $repository->getQuery($filters);
        $sql = $query->toSql();
        
        $this->assertStringContainsString('ilike', $sql);
        $this->assertStringContainsString('"acao" = ?', $sql);
        $this->assertStringContainsString('"data_alteracao" >= ?', $sql);
        $this->assertStringContainsString('"data_alteracao" <= ?', $sql);
    }
}

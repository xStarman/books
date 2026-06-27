<?php

namespace Tests\Unit\Services\Reports;

use Tests\TestCase;
use App\Services\Reports\GetAuditReportService;
use App\DTOs\Reports\AuditReportFilterDTO;
use App\Repositories\Reports\AuditReportRepository;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use Mockery;

class GetAuditReportServiceTest extends TestCase
{
    public function test_can_download_audit_report()
    {
        Excel::fake();
        
        $queryMock = Mockery::mock(Builder::class);
        $queryMock->shouldReceive('get')->andReturn(collect([]));

        $repositoryMock = Mockery::mock(AuditReportRepository::class);
        $repositoryMock->shouldReceive('getQuery')
                       ->with(Mockery::type(AuditReportFilterDTO::class))
                       ->andReturn($queryMock);
                       
        $service = new GetAuditReportService($repositoryMock);
        
        $filters = AuditReportFilterDTO::fromArray(['acao' => 'Todos']);
        
        $service->execute($filters);
        
        Excel::assertDownloaded('relatorio_auditoria.xlsx');
    }
}

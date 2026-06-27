<?php
namespace Tests\Unit\Exports\Reports;

use Tests\TestCase;
use App\Exports\Reports\AuditExport;
use App\DTOs\Reports\AuditReportFilterDTO;
use App\Repositories\Reports\AuditReportRepository;
use Illuminate\Database\Eloquent\Builder;
use Mockery;

class AuditExportTest extends TestCase
{
    public function test_export_headings_and_mapping()
    {
        $filters = AuditReportFilterDTO::fromArray([]);
        $repoMock = Mockery::mock(AuditReportRepository::class);
        $queryMock = Mockery::mock(Builder::class);

        $queryMock->shouldReceive('get')->andReturn(collect([
            (object) [
                'CodL' => 1, 'acao' => 'UPDATE', 'data_alteracao' => '2026-06-21 12:00:00',
                'Titulo' => 'Livro A', 'Editora' => 'Ed', 'Edicao' => 1,
                'AnoPublicacao' => 2020, 'Preco' => 10.5
            ]
        ]));
        
        $repoMock->shouldReceive('getQuery')->andReturn($queryMock);

        $export = new AuditExport($filters, $repoMock);

        $this->assertEquals([
            '2026-06-21 12:00:00', 'UPDATE', 1, 'Livro A', 'Ed', 1, 2020, '10,50', '', ''
        ], $export->map($export->collection()->first()));
        
        $this->assertEquals([
            'Data Alteração', 'Ação', 'Código', 'Título', 'Editora', 'Edição', 'Ano de Publicação', 'Preço', 'Autor 1', 'Assunto 1'
        ], $export->headings());
    }
}

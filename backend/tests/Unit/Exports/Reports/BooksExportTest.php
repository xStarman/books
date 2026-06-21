<?php
namespace Tests\Unit\Exports\Reports;

use Tests\TestCase;
use App\Exports\Reports\BooksExport;

class BooksExportTest extends TestCase
{
    public function test_export_headings_and_mapping()
    {
        $filters = \App\DTOs\Reports\BookReportFilterDTO::fromArray([]);
        $repoMock = \Mockery::mock(\App\Repositories\Reports\BookReportRepository::class);
        $queryMock = \Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);
        
        $queryMock->shouldReceive('get')->andReturn(collect([
            (object) [
                'CodL' => 1, 'Titulo' => 'Livro A', 'Editora' => 'Ed', 'Edicao' => 1,
                'AnoPublicacao' => 2020, 'Preco' => 10.5,
                'Autores' => 'Autor 1', 'Assuntos' => 'Fantasia'
            ]
        ]));
        
        $repoMock->shouldReceive('getQuery')->andReturn($queryMock);

        $export = new BooksExport($filters, $repoMock);

        $this->assertEquals([1, 'Livro A', 'Ed', 1, 2020, '10,50', 'Autor 1', 'Fantasia'], $export->map($export->collection()->first()));
        $this->assertEquals(['Código', 'Título', 'Editora', 'Edição', 'Ano de Publicação', 'Preço', 'Autor 1', 'Assunto 1'], $export->headings());
    }
}

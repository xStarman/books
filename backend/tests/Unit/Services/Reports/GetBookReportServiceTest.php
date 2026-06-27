<?php

namespace Tests\Unit\Services\Reports;

use Tests\TestCase;
use App\Services\Reports\GetBookReportService;
use App\DTOs\Reports\BookReportFilterDTO;
use App\Repositories\Reports\BookReportRepository;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use Mockery;

class GetBookReportServiceTest extends TestCase
{
    public function test_can_download_book_report()
    {
        Excel::fake();
        
        $queryMock = Mockery::mock(Builder::class);
        $queryMock->shouldReceive('get')->andReturn(collect([]));

        $repositoryMock = Mockery::mock(BookReportRepository::class);
        $repositoryMock->shouldReceive('getQuery')
                       ->with(Mockery::type(BookReportFilterDTO::class))
                       ->andReturn($queryMock);
                       
        $service = new GetBookReportService($repositoryMock);
        
        $filters = BookReportFilterDTO::fromArray([]);
        
        $service->execute($filters);
        
        Excel::assertDownloaded('relatorio_livros.xlsx');
    }
}

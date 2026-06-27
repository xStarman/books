<?php

namespace Tests\Unit\Services\Books;

use Tests\TestCase;
use App\Services\Books\GetBookListService;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Books\BookRepository;
use App\DTOs\Books\BookFiltersDTO;
use App\DTOs\OrderDTO;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;

class GetBookListServiceTest extends TestCase
{
    public function test_can_get_book_list()
    {
        $repositoryMock = Mockery::mock(BookRepository::class);
        
        $queryMock = Mockery::mock(Builder::class);
        $queryMock->shouldReceive('paginate')
                  ->with(10)
                  ->andReturn(new LengthAwarePaginator([], 0, 10));

        $repositoryMock->shouldReceive('getFilteredQuery')
                       ->with(Mockery::type(BookFiltersDTO::class), Mockery::type('array'))
                       ->andReturn($queryMock);

        $service = new GetBookListService($repositoryMock);
        
        $result = $service->execute(['Titulo' => 'Teste'], ['CodL' => 'asc'], 10);
        
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }
}

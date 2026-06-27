<?php

namespace Tests\Unit\Services\Authors;

use Tests\TestCase;
use App\Services\Authors\GetAuthorListService;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Authors\AuthorRepository;
use App\DTOs\Authors\AuthorFiltersDTO;
use App\DTOs\OrderDTO;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;

class GetAuthorListServiceTest extends TestCase
{
    public function test_can_get_author_list()
    {
        $repositoryMock = Mockery::mock(AuthorRepository::class);
        
        $queryMock = Mockery::mock(Builder::class);
        $queryMock->shouldReceive('paginate')
                  ->with(10)
                  ->andReturn(new LengthAwarePaginator([], 0, 10));

        $repositoryMock->shouldReceive('getFilteredQuery')
                       ->with(Mockery::type(AuthorFiltersDTO::class), Mockery::type('array'))
                       ->andReturn($queryMock);

        $service = new GetAuthorListService($repositoryMock);
        
        $result = $service->execute(['Nome' => 'Teste'], ['CodAu' => 'asc'], 10);
        
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }
}

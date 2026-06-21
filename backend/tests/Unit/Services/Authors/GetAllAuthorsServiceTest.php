<?php

namespace Tests\Unit\Services\Authors;

use Tests\TestCase;
use App\Services\Authors\GetAllAuthorsService;
use App\Repositories\Authors\AuthorRepository;
use App\DTOs\Authors\AuthorFiltersDTO;
use App\DTOs\OrderDTO;
use Illuminate\Database\Eloquent\Collection;
use Mockery;

class GetAllAuthorsServiceTest extends TestCase
{
    public function test_can_get_all_authors()
    {
        $repositoryMock = Mockery::mock(AuthorRepository::class);
        
        $queryMock = Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);
        $queryMock->shouldReceive('get')
                  ->andReturn(new Collection([]));

        $repositoryMock->shouldReceive('getFilteredQuery')
                       ->with(Mockery::type(AuthorFiltersDTO::class), Mockery::type('array'))
                       ->andReturn($queryMock);

        $service = new GetAllAuthorsService($repositoryMock);
        
        $result = $service->execute(['Nome' => 'Teste'], ['CodAu' => 'asc']);
        
        $this->assertInstanceOf(Collection::class, $result);
    }
}

<?php

namespace Tests\Unit\Services\Subjects;

use Tests\TestCase;
use App\Services\Subjects\GetSubjectListService;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Subjects\SubjectRepository;
use App\DTOs\Subjects\SubjectFiltersDTO;
use App\DTOs\OrderDTO;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;

class GetSubjectListServiceTest extends TestCase
{
    public function test_can_get_subject_list()
    {
        $repositoryMock = Mockery::mock(SubjectRepository::class);
        
        $queryMock = Mockery::mock(Builder::class);
        $queryMock->shouldReceive('paginate')
                  ->with(10)
                  ->andReturn(new LengthAwarePaginator([], 0, 10));

        $repositoryMock->shouldReceive('getFilteredQuery')
                       ->with(Mockery::type(SubjectFiltersDTO::class), Mockery::type('array'))
                       ->andReturn($queryMock);

        $service = new GetSubjectListService($repositoryMock);
        
        $result = $service->execute(['Descricao' => 'Teste'], ['CodAs' => 'asc'], 10);
        
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }
}

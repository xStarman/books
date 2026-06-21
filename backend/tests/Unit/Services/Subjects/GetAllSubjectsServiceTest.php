<?php

namespace Tests\Unit\Services\Subjects;

use Tests\TestCase;
use App\Services\Subjects\GetAllSubjectsService;
use App\Repositories\Subjects\SubjectRepository;
use App\DTOs\Subjects\SubjectFiltersDTO;
use App\DTOs\OrderDTO;
use Illuminate\Database\Eloquent\Collection;
use Mockery;

class GetAllSubjectsServiceTest extends TestCase
{
    public function test_can_get_all_subjects()
    {
        $repositoryMock = Mockery::mock(SubjectRepository::class);
        
        $queryMock = Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);
        $queryMock->shouldReceive('get')
                  ->andReturn(new Collection([]));

        $repositoryMock->shouldReceive('getFilteredQuery')
                       ->with(Mockery::type(SubjectFiltersDTO::class), Mockery::type('array'))
                       ->andReturn($queryMock);

        $service = new GetAllSubjectsService($repositoryMock);
        
        $result = $service->execute(['Descricao' => 'Teste'], ['CodAs' => 'asc']);
        
        $this->assertInstanceOf(Collection::class, $result);
    }
}

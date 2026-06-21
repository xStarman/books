<?php

namespace Tests\Unit\Repositories\Subjects;

use Tests\TestCase;
use App\Repositories\Subjects\SubjectRepository;
use App\Models\Assunto;
use App\DTOs\Subjects\SubjectFiltersDTO;
use App\DTOs\OrderDTO;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SubjectRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_filter_by_descricao()
    {
        $subject1 = Assunto::create(['Descricao' => 'Assunto Teste A ' . \Illuminate\Support\Str::random(4)]);
        $subject2 = Assunto::create(['Descricao' => 'Outro Assunto ' . \Illuminate\Support\Str::random(4)]);

        $repository = new SubjectRepository();
        $filters = SubjectFiltersDTO::fromArray(['Descricao' => 'Teste A']);

        $query = $repository->getFilteredQuery($filters);
        $results = $query->get();

        $this->assertTrue($results->contains('CodAs', $subject1->CodAs));
        $this->assertFalse($results->contains('CodAs', $subject2->CodAs));
    }

    public function test_can_order_results()
    {
        $subject1 = Assunto::create(['Descricao' => 'Z Assunto ' . \Illuminate\Support\Str::random(4)]);
        $subject2 = Assunto::create(['Descricao' => 'A Assunto ' . \Illuminate\Support\Str::random(4)]);

        $repository = new SubjectRepository();
        $filters = SubjectFiltersDTO::fromArray([]);
        $orders = OrderDTO::fromArray(['Descricao' => 'desc']);

        $query = $repository->getFilteredQuery($filters, $orders);

        $this->assertStringContainsString('order by "Descricao" desc', $query->toSql());
    }

    public function test_default_order_is_cod_as_desc()
    {
        $repository = new SubjectRepository();
        $filters = SubjectFiltersDTO::fromArray([]);

        $query = $repository->getFilteredQuery($filters);

        $this->assertStringContainsString('order by "CodAs" desc', $query->toSql());
    }
}

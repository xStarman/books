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

    public function test_save_creates_new_subject()
    {
        $repository = new SubjectRepository();
        $descricao = 'Subj ' . \Illuminate\Support\Str::random(4);

        $assunto = $repository->save(['Descricao' => $descricao]);

        $this->assertEquals($descricao, $assunto->Descricao);
        $this->assertDatabaseHas('assuntos', ['Descricao' => $descricao]);
    }

    public function test_save_updates_existing_subject()
    {
        $repository = new SubjectRepository();
        $assunto = Assunto::create(['Descricao' => 'Old ' . \Illuminate\Support\Str::random(4)]);
        $novaDesc = 'New ' . \Illuminate\Support\Str::random(4);

        $atualizado = $repository->save(['Descricao' => $novaDesc], $assunto->CodAs);

        $this->assertEquals($novaDesc, $atualizado->Descricao);
        $this->assertDatabaseHas('assuntos', ['CodAs' => $assunto->CodAs, 'Descricao' => $novaDesc]);
    }

    public function test_save_throws_exception_on_duplicate_description()
    {
        $repository = new SubjectRepository();
        $descricao = 'Dup ' . \Illuminate\Support\Str::random(4);
        Assunto::create(['Descricao' => $descricao]);

        $this->expectException(\App\Exceptions\SubjectAlreadyExistsException::class);
        $repository->save(['Descricao' => $descricao]);
    }
}

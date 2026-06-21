<?php
namespace Tests\Unit\Repositories\Authors;

use Tests\TestCase;
use App\Repositories\Authors\AuthorRepository;
use App\Models\Autor;
use App\DTOs\Authors\AuthorFiltersDTO;
use App\DTOs\OrderDTO;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthorRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_filter_by_nome()
    {
        $author1 = Autor::create(['Nome' => 'Autor Teste A ' . \Illuminate\Support\Str::random(4)]);
        $author2 = Autor::create(['Nome' => 'Outro Autor ' . \Illuminate\Support\Str::random(4)]);

        $repository = new AuthorRepository();
        $filters = AuthorFiltersDTO::fromArray(['Nome' => 'Teste A']);
        
        $query = $repository->getFilteredQuery($filters);
        $results = $query->get();

        $this->assertTrue($results->contains('CodAu', $author1->CodAu));
        $this->assertFalse($results->contains('CodAu', $author2->CodAu));
    }

    public function test_can_order_results()
    {
        $repository = new AuthorRepository();
        $filters = AuthorFiltersDTO::fromArray([]);
        $orders = OrderDTO::fromArray(['Nome' => 'desc']);
        
        $query = $repository->getFilteredQuery($filters, $orders);
        
        $this->assertStringContainsString('order by "Nome" desc', $query->toSql());
    }

    public function test_default_order_is_nome_asc()
    {
        $repository = new AuthorRepository();
        $filters = AuthorFiltersDTO::fromArray([]);
        
        $query = $repository->getFilteredQuery($filters);
        
        $this->assertStringContainsString('order by "Nome" asc', $query->toSql());
    }
}

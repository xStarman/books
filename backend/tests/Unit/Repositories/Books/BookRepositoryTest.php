<?php
namespace Tests\Unit\Repositories\Books;

use Tests\TestCase;
use App\Repositories\Books\BookRepository;
use Illuminate\Support\Str;
use App\Models\Livro;
use App\DTOs\Books\BookFiltersDTO;
use App\DTOs\OrderDTO;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BookRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_filter_by_titulo_and_edicao()
    {
        $book1 = Livro::create([
            'Titulo' => 'Livro Teste A ' . Str::random(4),
            'Editora' => 'Ed',
            'Edicao' => 1,
            'AnoPublicacao' => 2024,
            'Preco' => 10.50,
        ]);
        $book2 = Livro::create([
            'Titulo' => 'Outro Livro ' . Str::random(4),
            'Editora' => 'Ed',
            'Edicao' => 2,
            'AnoPublicacao' => 2024,
            'Preco' => 10.50,
        ]);

        $repository = new BookRepository();
        $filters = BookFiltersDTO::fromArray(['Titulo' => 'Teste A']);
        
        $query = $repository->getFilteredQuery($filters);
        $results = $query->get();

        $this->assertTrue($results->contains('CodL', $book1->CodL));
        $this->assertFalse($results->contains('CodL', $book2->CodL));
        
        $filters2 = BookFiltersDTO::fromArray(['Edicao' => 2]);
        $query2 = $repository->getFilteredQuery($filters2);
        $results2 = $query2->get();
        
        $this->assertFalse($results2->contains('CodL', $book1->CodL));
        $this->assertTrue($results2->contains('CodL', $book2->CodL));
    }

    public function test_can_order_results()
    {
        $repository = new BookRepository();
        $filters = BookFiltersDTO::fromArray([]);
        $orders = OrderDTO::fromArray(['Titulo' => 'desc']);
        
        $query = $repository->getFilteredQuery($filters, $orders);
        
        $this->assertStringContainsString('order by "Titulo" desc', $query->toSql());
    }

    public function test_default_order_is_cod_l_desc()
    {
        $repository = new BookRepository();
        $filters = BookFiltersDTO::fromArray([]);
        
        $query = $repository->getFilteredQuery($filters);
        
        $this->assertStringContainsString('order by "CodL" desc', $query->toSql());
    }
}

<?php
namespace Tests\Unit\Repositories\Authors;

use Tests\TestCase;
use App\Repositories\Authors\AuthorRepository;
use Illuminate\Support\Str;
use App\Exceptions\AuthorAlreadyExistsException;
use App\Models\Autor;
use App\DTOs\Authors\AuthorFiltersDTO;
use App\DTOs\OrderDTO;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthorRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_filter_by_nome()
    {
        $author1 = Autor::create(['Nome' => 'Autor Teste A ' . Str::random(4)]);
        $author2 = Autor::create(['Nome' => 'Outro Autor ' . Str::random(4)]);

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

    public function test_default_order_is_cod_au_desc()
    {
        $repository = new AuthorRepository();
        $filters = AuthorFiltersDTO::fromArray([]);
        
        $query = $repository->getFilteredQuery($filters);
        
        $this->assertStringContainsString('order by "CodAu" desc', $query->toSql());
    }

    public function test_save_creates_new_author()
    {
        $repository = new AuthorRepository();
        $nome = 'Autor Repo ' . Str::random(8);

        $autor = $repository->save(['Nome' => $nome]);

        $this->assertEquals($nome, $autor->Nome);
        $this->assertDatabaseHas('autores', ['Nome' => $nome]);
    }

    public function test_save_updates_existing_author()
    {
        $repository = new AuthorRepository();
        $autor = Autor::create(['Nome' => 'Velho ' . Str::random(8)]);
        $novoNome = 'Atualizado ' . Str::random(8);

        $atualizado = $repository->save(['Nome' => $novoNome], $autor->CodAu);

        $this->assertEquals($novoNome, $atualizado->Nome);
        $this->assertDatabaseHas('autores', ['CodAu' => $autor->CodAu, 'Nome' => $novoNome]);
    }

    public function test_save_throws_exception_on_duplicate_name()
    {
        $repository = new AuthorRepository();
        $nome = 'Duplicado ' . Str::random(8);
        Autor::create(['Nome' => $nome]);

        $this->expectException(AuthorAlreadyExistsException::class);
        $repository->save(['Nome' => $nome]);
    }
}

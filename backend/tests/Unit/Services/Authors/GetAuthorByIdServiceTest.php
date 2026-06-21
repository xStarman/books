<?php

namespace Tests\Unit\Services\Authors;

use Tests\TestCase;
use App\Services\Authors\GetAuthorByIdService;
use App\Models\Autor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GetAuthorByIdServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_get_author_by_id()
    {
        $author = Autor::create(['Nome' => 'Autor Teste']);
        $service = app(GetAuthorByIdService::class);
        
        $result = $service->execute($author->CodAu);
        
        $this->assertEquals($author->CodAu, $result->CodAu);
        $this->assertEquals('Autor Teste', $result->Nome);
    }

    public function test_throws_exception_if_author_not_found()
    {
        $service = app(GetAuthorByIdService::class);
        
        $this->expectException(ModelNotFoundException::class);
        $service->execute(999999);
    }
}

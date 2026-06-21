<?php
namespace Tests\Unit\Requests\Books;

use PHPUnit\Framework\TestCase;
use App\Http\Requests\Books\ListBooksRequest;

class ListBooksRequestTest extends TestCase
{
    public function test_rules_and_authorization()
    {
        $request = new ListBooksRequest();
        $this->assertTrue($request->authorize());
        $rules = $request->rules();
        
        $this->assertArrayHasKey('filters.Titulo', $rules);
        $this->assertArrayHasKey('filters.Editora', $rules);
    }
}

<?php
namespace Tests\Unit\Requests\Books;

use PHPUnit\Framework\TestCase;
use App\Http\Requests\Books\SaveBookRequest;

class SaveBookRequestTest extends TestCase
{
    public function test_rules_and_authorization()
    {
        $request = new SaveBookRequest();
        $this->assertTrue($request->authorize());
        $rules = $request->rules();
        
        $this->assertArrayHasKey('Titulo', $rules);
        $this->assertArrayHasKey('Preco', $rules);
        $this->assertArrayHasKey('autores', $rules);
    }
}

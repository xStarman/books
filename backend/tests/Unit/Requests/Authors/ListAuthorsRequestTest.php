<?php
namespace Tests\Unit\Requests\Authors;

use PHPUnit\Framework\TestCase;
use App\Http\Requests\Authors\ListAuthorsRequest;

class ListAuthorsRequestTest extends TestCase
{
    public function test_rules_and_authorization()
    {
        $request = new ListAuthorsRequest();
        $this->assertTrue($request->authorize());
        $rules = $request->rules();
        
        $this->assertArrayHasKey('filters.Nome', $rules);
    }
}

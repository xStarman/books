<?php
namespace Tests\Unit\Requests\Subjects;

use PHPUnit\Framework\TestCase;
use App\Http\Requests\Subjects\ListSubjectsRequest;

class ListSubjectsRequestTest extends TestCase
{
    public function test_rules_and_authorization()
    {
        $request = new ListSubjectsRequest();
        $this->assertTrue($request->authorize());
        $rules = $request->rules();
        
        $this->assertArrayHasKey('filters.Descricao', $rules);
    }
}

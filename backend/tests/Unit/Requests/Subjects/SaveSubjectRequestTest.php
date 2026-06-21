<?php
namespace Tests\Unit\Requests\Subjects;

use PHPUnit\Framework\TestCase;
use App\Http\Requests\Subjects\SaveSubjectRequest;

class SaveSubjectRequestTest extends TestCase
{
    public function test_rules_and_authorization()
    {
        $request = new SaveSubjectRequest();
        $this->assertTrue($request->authorize());
        $rules = $request->rules();
        
        $this->assertArrayHasKey('Descricao', $rules);
    }
}

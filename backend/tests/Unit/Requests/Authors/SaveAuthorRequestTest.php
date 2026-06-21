<?php
namespace Tests\Unit\Requests\Authors;

use PHPUnit\Framework\TestCase;
use App\Http\Requests\Authors\SaveAuthorRequest;

class SaveAuthorRequestTest extends TestCase
{
    public function test_rules_and_authorization()
    {
        $request = new SaveAuthorRequest();
        $this->assertTrue($request->authorize());
        $rules = $request->rules();
        
        $this->assertArrayHasKey('Nome', $rules);
        $this->assertContains('required', $rules['Nome']);
    }
}

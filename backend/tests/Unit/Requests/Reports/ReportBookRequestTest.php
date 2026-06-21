<?php
namespace Tests\Unit\Requests\Reports;

use PHPUnit\Framework\TestCase;
use App\Http\Requests\Reports\ReportBookRequest;

class ReportBookRequestTest extends TestCase
{
    public function test_rules_and_authorization()
    {
        $request = new ReportBookRequest();
        $this->assertTrue($request->authorize());
        $rules = $request->rules();
        
        $this->assertArrayHasKey('Titulo', $rules);
        $this->assertArrayHasKey('Editora', $rules);
        $this->assertArrayHasKey('autores', $rules);
    }
}

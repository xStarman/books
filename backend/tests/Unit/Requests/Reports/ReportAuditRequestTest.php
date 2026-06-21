<?php
namespace Tests\Unit\Requests\Reports;

use PHPUnit\Framework\TestCase;
use App\Http\Requests\Reports\ReportAuditRequest;

class ReportAuditRequestTest extends TestCase
{
    public function test_rules_and_authorization()
    {
        $request = new ReportAuditRequest();
        $this->assertTrue($request->authorize());
        $rules = $request->rules();
        
        $this->assertArrayHasKey('Titulo', $rules);
        $this->assertArrayHasKey('acao', $rules);
        $this->assertArrayHasKey('dataInicial', $rules);
        $this->assertArrayHasKey('dataFinal', $rules);
    }
}

<?php
namespace Tests\Feature\Controllers;

use Tests\TestCase;

class ReportsControllerTest extends TestCase
{
    public function test_can_download_books_report()
    {
        $response = $this->get('/api/reports/books');
        
        $response->assertStatus(200);
        $this->assertEquals(
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            $response->headers->get('Content-Type')
        );
    }

    public function test_can_download_audits_report()
    {
        $response = $this->get('/api/reports/audits?acao=Todos');
        
        $response->assertStatus(200);
        $this->assertEquals(
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            $response->headers->get('Content-Type')
        );
    }

    public function test_returns_validation_error_for_invalid_audit_acao()
    {
        $response = $this->getJson('/api/reports/audits?acao=INVALIDO');
        
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['acao']);
    }
}
<?php
namespace Tests\Unit\Models;

use App\Models\LivroAudit;
use PHPUnit\Framework\TestCase;

class LivroAuditTest extends TestCase
{
    public function test_model_configuration()
    {
        $model = new LivroAudit();
        $this->assertEquals('livros_audit', $model->getTable());
        $this->assertEquals('id_audit', $model->getKeyName());
        $this->assertFalse($model->usesTimestamps());
        $this->assertEquals([], $model->getFillable());
    }
}

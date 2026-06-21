<?php
namespace Tests\Unit\Models;

use App\Models\Assunto;
use PHPUnit\Framework\TestCase;

class SubjectTest extends TestCase
{
    public function test_model_configuration()
    {
        $model = new Assunto();
        $this->assertEquals('assuntos', $model->getTable());
        $this->assertEquals('CodAs', $model->getKeyName());
        $this->assertFalse($model->usesTimestamps());
        $this->assertEquals(['Descricao'], $model->getFillable());
    }
}
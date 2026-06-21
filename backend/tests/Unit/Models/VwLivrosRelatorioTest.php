<?php
namespace Tests\Unit\Models;

use App\Models\VwLivrosRelatorio;
use PHPUnit\Framework\TestCase;

class VwLivrosRelatorioTest extends TestCase
{
    public function test_model_configuration()
    {
        $model = new VwLivrosRelatorio();
        $this->assertEquals('vw_livros_relatorio', $model->getTable());
        $this->assertEquals('CodL', $model->getKeyName());
        $this->assertFalse($model->usesTimestamps());
        $this->assertFalse($model->getIncrementing());
        $this->assertEquals([], $model->getFillable());
    }
}

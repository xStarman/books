<?php
namespace Tests\Unit\Models;

use App\Models\Autor;
use PHPUnit\Framework\TestCase;

class AuthorTest extends TestCase
{
    public function test_model_configuration()
    {
        $model = new Autor();
        $this->assertEquals('autores', $model->getTable());
        $this->assertEquals('CodAu', $model->getKeyName());
        $this->assertFalse($model->usesTimestamps());
        $this->assertEquals(['Nome'], $model->getFillable());
    }
}
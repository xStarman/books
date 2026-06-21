<?php
namespace Tests\Unit\Models;

use App\Models\Livro;
use PHPUnit\Framework\TestCase;

class BookTest extends TestCase
{
    public function test_model_configuration()
    {
        $model = new Livro();
        $this->assertEquals('livros', $model->getTable());
        $this->assertEquals('CodL', $model->getKeyName());
        $this->assertFalse($model->usesTimestamps());
        $this->assertEquals(['Titulo', 'Editora', 'Edicao', 'AnoPublicacao', 'Preco'], $model->getFillable());
    }
}
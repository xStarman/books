<?php
namespace Tests\Unit\Models;

use App\Models\Livro;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function test_autores_relation()
    {
        $model = new Livro();
        $relation = $model->autores();
        $this->assertInstanceOf(BelongsToMany::class, $relation);
        $this->assertEquals('livro_autor', $relation->getTable());
        $this->assertEquals('livro_autor.Livro_CodL', $relation->getQualifiedForeignPivotKeyName());
        $this->assertEquals('livro_autor.Autor_CodAu', $relation->getQualifiedRelatedPivotKeyName());
    }

    public function test_assuntos_relation()
    {
        $model = new Livro();
        $relation = $model->assuntos();
        $this->assertInstanceOf(BelongsToMany::class, $relation);
        $this->assertEquals('livro_assunto', $relation->getTable());
        $this->assertEquals('livro_assunto.Livro_CodL', $relation->getQualifiedForeignPivotKeyName());
        $this->assertEquals('livro_assunto.Assunto_CodAs', $relation->getQualifiedRelatedPivotKeyName());
    }
}
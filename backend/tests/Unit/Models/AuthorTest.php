<?php
namespace Tests\Unit\Models;

use App\Models\Autor;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function test_livros_relation()
    {
        $model = new Autor();
        $relation = $model->livros();
        $this->assertInstanceOf(BelongsToMany::class, $relation);
        $this->assertEquals('livro_autor', $relation->getTable());
        $this->assertEquals('livro_autor.Autor_CodAu', $relation->getQualifiedForeignPivotKeyName());
        $this->assertEquals('livro_autor.Livro_CodL', $relation->getQualifiedRelatedPivotKeyName());
    }
}
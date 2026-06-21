<?php
namespace Tests\Unit\Models;

use App\Models\Assunto;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function test_livros_relation()
    {
        $model = new Assunto();
        $relation = $model->livros();
        $this->assertInstanceOf(BelongsToMany::class, $relation);
        $this->assertEquals('livro_assunto', $relation->getTable());
        $this->assertEquals('livro_assunto.Assunto_CodAs', $relation->getQualifiedForeignPivotKeyName());
        $this->assertEquals('livro_assunto.Livro_CodL', $relation->getQualifiedRelatedPivotKeyName());
    }
}
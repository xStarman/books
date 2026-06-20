<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Livro extends Model
{
    protected $primaryKey = 'CodL';
    public $timestamps = false;
    protected $table = 'livros';
    protected $keyType = 'int';

    protected $fillable = [
        'Titulo',
        'Editora',
        'Edicao',
        'AnoPublicacao',
        'Preco',
    ];

    public function autores()
    {
        return $this->belongsToMany(Autor::class, 'livro_autor', 'Livro_CodL', 'Autor_CodAu');
    }

    public function assuntos()
    {
        return $this->belongsToMany(Assunto::class, 'livro_assunto', 'Livro_CodL', 'Assunto_CodAs');
    }
}

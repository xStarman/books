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
}

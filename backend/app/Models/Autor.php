<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    protected $primaryKey = 'CodAu';
    public $timestamps = false;
    protected $table = 'autores';
    protected $keyType = 'int';

    protected $fillable = [
        'Nome',
    ];

    public function livros()
    {
        return $this->belongsToMany(Livro::class, 'livro_autor', 'Autor_CodAu', 'Livro_CodL');
    }
}

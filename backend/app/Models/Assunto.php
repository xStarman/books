<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assunto extends Model
{
    protected $primaryKey = 'CodAs';
    public $timestamps = false;
    protected $table = 'assuntos';
    protected $keyType = 'int';

    protected $fillable = [
        'Descricao',
    ];

    public function livros()
    {
        return $this->belongsToMany(Livro::class, 'livro_assunto', 'Assunto_CodAs', 'Livro_CodL');
    }
}

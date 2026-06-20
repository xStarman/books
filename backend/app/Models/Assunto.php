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
}

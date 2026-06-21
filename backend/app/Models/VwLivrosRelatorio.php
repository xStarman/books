<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VwLivrosRelatorio extends Model
{
    protected $table = 'vw_livros_relatorio';
    public $timestamps = false;
    protected $primaryKey = 'CodL';
    public $incrementing = false;

    protected $fillable = [];
}

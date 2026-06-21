<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LivroAudit extends Model
{
    protected $table = 'livros_audit';
    public $timestamps = false;
    protected $primaryKey = 'id_audit';

    protected $fillable = [];
}

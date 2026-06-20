<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \Illuminate\Support\Facades\DB::table('livros')->update([
            'Titulo' => \Illuminate\Support\Facades\DB::raw('SUBSTR("Titulo", 1, 40)'),
            'Editora' => \Illuminate\Support\Facades\DB::raw('SUBSTR("Editora", 1, 40)')
        ]);

        Schema::table('livros', function (Blueprint $table) {
            $table->string('Titulo', 40)->change();
            $table->string('Editora', 40)->change();
            
            $table->unique(['Titulo', 'Editora', 'Edicao', 'AnoPublicacao'], 'livros_unique_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livros', function (Blueprint $table) {
            $table->dropUnique('livros_unique_key');
            
            $table->string('Titulo', 200)->change();
            $table->string('Editora', 200)->change();
        });
    }
};

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
        Schema::table('livro_autor', function (Blueprint $table) {
            $table->dropForeign(['Autor_CodAu']);
            $table->foreign('Autor_CodAu')->references('CodAu')->on('autores')->onDelete('restrict');
        });

        Schema::table('livro_assunto', function (Blueprint $table) {
            $table->dropForeign(['Assunto_CodAs']);
            $table->foreign('Assunto_CodAs')->references('CodAs')->on('assuntos')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livro_autor', function (Blueprint $table) {
            $table->dropForeign(['Autor_CodAu']);
            $table->foreign('Autor_CodAu')->references('CodAu')->on('autores')->onDelete('cascade');
        });

        Schema::table('livro_assunto', function (Blueprint $table) {
            $table->dropForeign(['Assunto_CodAs']);
            $table->foreign('Assunto_CodAs')->references('CodAs')->on('assuntos')->onDelete('cascade');
        });
    }
};

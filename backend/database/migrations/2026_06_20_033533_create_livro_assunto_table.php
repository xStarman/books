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
        Schema::create('livro_assunto', function (Blueprint $table) {
            $table->integer('Livro_CodL');
            $table->integer('Assunto_CodAs');

            $table->primary(['Livro_CodL', 'Assunto_CodAs']);
            $table->foreign('Livro_CodL')->references('CodL')->on('livros')->onDelete('cascade');
            $table->foreign('Assunto_CodAs')->references('CodAs')->on('assuntos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livro_assunto');
    }
};

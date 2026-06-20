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
        Schema::create('livros', function (Blueprint $table) {
            $table->integer('CodL')->autoIncrement()->primary();
            $table->string('Titulo', 200);
            $table->string('Editora', 200);
            $table->integer('Edicao')->index();
            $table->smallInteger('AnoPublicacao')->index();
            $table->decimal('Preco', 10, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livros');
    }
};

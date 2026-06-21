<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('
            CREATE OR REPLACE VIEW vw_livros_relatorio AS
            SELECT 
                l."CodL",
                l."Titulo",
                l."Editora",
                l."Edicao",
                l."AnoPublicacao",
                l."Preco",
                (
                    SELECT string_agg(a."Nome", \', \') 
                    FROM livro_autor la 
                    JOIN autores a ON a."CodAu" = la."Autor_CodAu" 
                    WHERE la."Livro_CodL" = l."CodL"
                ) AS "Autores",
                (
                    SELECT string_agg(asnt."Descricao", \', \') 
                    FROM livro_assunto las 
                    JOIN assuntos asnt ON asnt."CodAs" = las."Assunto_CodAs" 
                    WHERE las."Livro_CodL" = l."CodL"
                ) AS "Assuntos"
            FROM livros l;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS vw_livros_relatorio;');
    }
};

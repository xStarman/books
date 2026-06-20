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
        Schema::create('livros_audit', function (Blueprint $table) {
            $table->id('id_audit');
            $table->integer('CodL');
            $table->string('Titulo', 200);
            $table->string('Editora', 200);
            $table->integer('Edicao');
            $table->smallInteger('AnoPublicacao');
            $table->decimal('Preco', 10, 2);
            $table->json('Autores')->nullable();
            $table->json('Assuntos')->nullable();
            $table->string('acao', 10);
            $table->timestamp('data_alteracao')->useCurrent();
        });

        DB::unprepared("
            CREATE OR REPLACE FUNCTION trg_livros_audit_func()
            RETURNS TRIGGER AS $$
            DECLARE
                v_acao VARCHAR(10);
                v_autores JSON;
                v_assuntos JSON;
            BEGIN
                IF TG_OP = 'UPDATE' THEN
                    v_acao := 'UPDATE';
                ELSIF TG_OP = 'DELETE' THEN
                    v_acao := 'DELETE';
                END IF;

                SELECT json_agg(json_build_object('CodAu', a.\"CodAu\", 'Nome', a.\"Nome\"))
                INTO v_autores
                FROM autores a
                JOIN livro_autor la ON la.\"Autor_CodAu\" = a.\"CodAu\"
                WHERE la.\"Livro_CodL\" = OLD.\"CodL\";

                SELECT json_agg(json_build_object('CodAs', ass.\"CodAs\", 'Descricao', ass.\"Descricao\"))
                INTO v_assuntos
                FROM assuntos ass
                JOIN livro_assunto las ON las.\"Assunto_CodAs\" = ass.\"CodAs\"
                WHERE las.\"Livro_CodL\" = OLD.\"CodL\";

                INSERT INTO livros_audit (\"CodL\", \"Titulo\", \"Editora\", \"Edicao\", \"AnoPublicacao\", \"Preco\", \"Autores\", \"Assuntos\", \"acao\")
                VALUES (
                    OLD.\"CodL\", OLD.\"Titulo\", OLD.\"Editora\", OLD.\"Edicao\", OLD.\"AnoPublicacao\", OLD.\"Preco\",
                    COALESCE(v_autores, '[]'::json), COALESCE(v_assuntos, '[]'::json), v_acao
                );

                IF TG_OP = 'DELETE' THEN
                    RETURN OLD;
                ELSE
                    RETURN NEW;
                END IF;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER trg_livros_audit_update
            AFTER UPDATE ON livros
            FOR EACH ROW
            EXECUTE FUNCTION trg_livros_audit_func();

            CREATE TRIGGER trg_livros_audit_delete
            BEFORE DELETE ON livros
            FOR EACH ROW
            EXECUTE FUNCTION trg_livros_audit_func();
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("
            DROP TRIGGER IF EXISTS trg_livros_audit_update ON livros;
            DROP TRIGGER IF EXISTS trg_livros_audit_delete ON livros;
            DROP FUNCTION IF EXISTS trg_livros_audit_func();
        ");
        Schema::dropIfExists('livros_audit');
    }
};

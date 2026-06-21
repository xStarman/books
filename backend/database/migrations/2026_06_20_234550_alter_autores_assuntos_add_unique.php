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
        Schema::table('autores', function (Blueprint $table) {
            $table->unique('Nome');
        });

        Schema::table('assuntos', function (Blueprint $table) {
            $table->unique('Descricao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('autores', function (Blueprint $table) {
            $table->dropUnique(['Nome']);
        });

        Schema::table('assuntos', function (Blueprint $table) {
            $table->dropUnique(['Descricao']);
        });
    }
};

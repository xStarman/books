<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Livro;
use App\Models\Autor;
use App\Models\Assunto;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Faker::create('pt_BR');

        $autoresIds = [];
        for ($i = 0; $i < 50; $i++) {
            $autor = Autor::create([
                'Nome' => $faker->name,
            ]);
            $autoresIds[] = $autor->CodAu;
        }

        $assuntosIds = [];
        for ($i = 0; $i < 20; $i++) {
            $assunto = Assunto::create([
                'Descricao' => substr($faker->unique()->word, 0, 20),
            ]);
            $assuntosIds[] = $assunto->CodAs;
        }

        for ($i = 0; $i < 500; $i++) {
            $livro = Livro::create([
                'Titulo' => $faker->sentence(3),
                'Editora' => $faker->company,
                'Edicao' => $faker->numberBetween(1, 10),
                'AnoPublicacao' => $faker->year,
                'Preco' => $faker->randomFloat(2, 10, 200),
            ]);

            $randomAutores = (array) array_rand(array_flip($autoresIds), rand(1, 3));
            $livro->autores()->attach($randomAutores);

            $randomAssuntos = (array) array_rand(array_flip($assuntosIds), rand(1, 2));
            $livro->assuntos()->attach($randomAssuntos);
        }
    }
}
